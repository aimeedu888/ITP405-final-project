<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use DB;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Models\girlGroup;

class FavController extends Controller
{
    public function addAlbum(Request $request,$group,$album_id,$user_id)
    {
        //check if already exist
        $existingFavorite = DB::table('favorite_albums')
        ->where('album_id', $album_id)
        ->where('user_id', $user_id)
        ->first();

        if ($existingFavorite) {
            return redirect()->route('groupDetail', ['group' => $group])->with('albumError', 'Album already exists in favorites')->with('album_id', $album_id);
        }
    
        DB::table('favorite_albums')->insert([
            'user_id'=> $user_id,
            'album_id'=> $album_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->route('groupDetail',['group' => $group])->with('albumSuccess', 'Successully add to favorites')->with('album_id', $album_id);
    }
    public function addTrack(Request $request,$group,$album_id,$track_id,$user_id,$commentIndex)
    {
        //check if already exist
        $existingFavorite = DB::table('favorite_tracks')
        ->where('track_id', $track_id)
        ->where('user_id', $user_id)
        ->first();

        if ($existingFavorite) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $album_id, 'commentIndex' => $commentIndex])->with('trackError', 'Track already exists in favorites')->with('track_id', $track_id);
        }
    
        DB::table('favorite_tracks')->insert([
            'user_id'=> $user_id,
            'track_id'=> $track_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $album_id, 'commentIndex' => $commentIndex])->with('trackSuccess', 'Successully add to favorites')->with('track_id', $track_id);
    }
    public function removeAlbum(Request $request,$group,$album_id)
    {
        //check if exists
        $existingFavorite = DB::table('favorite_albums')
        ->where('album_id', $album_id)
        ->where('user_id', Auth::id())
        ->first();

        if (!$existingFavorite) {
            return redirect()->route('groupDetail', ['group' => $group])->with('albumError', 'Album does not exist in favorites')->with('album_id', $album_id);
        }
    
        DB::table('favorite_albums')->where('user_id', Auth::id())->where('album_id', $album_id)->delete();
        return redirect()->route('groupDetail',['group' => $group])->with('albumSuccess', 'Successully remove from favorites')->with('album_id', $album_id);
    }
    public function removeTrack(Request $request,$group,$album_id,$track_id,$commentIndex)
    {
        //check if already exist
        $existingFavorite = DB::table('favorite_tracks')
        ->where('track_id', $track_id)
        ->where('user_id', Auth::id())
        ->first();

        if (!$existingFavorite) {
            return redirect()->route('albumDetail', ['group' => $group, 'albumID' => $album_id, 'commentIndex' => $commentIndex])->with('trackError', 'Track does not exist in favorites')->with('track_id', $track_id);
        }
    
        DB::table('favorite_tracks')->where('user_id', Auth::id())->where('track_id', $track_id)->delete();
        return redirect()->route('albumDetail',['group' => $group, 'albumID' => $album_id, 'commentIndex' => $commentIndex])->with('trackSuccess', 'Successully remove from favorites')->with('track_id', $track_id);
    }
    public function viewPage(Request $request)
    {
        //fetch token that expires every 1 hour
        $client = new Client();
        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
            ],
        ]);
        $body = json_decode($response->getBody(), true);
        $accessToken = $body['access_token'];

        $seconds = 120;

        //fetch favorite albums
        $favorite_albums = DB::table('favorite_albums')->where('user_id', Auth::id())->get();
        $albums = [];
        foreach ($favorite_albums as $album){
            $album_id = $album->album_id;
            $album_cacheKey = "spotify-api-album-$album_id";
            $album = Cache::remember($album_cacheKey, $seconds, function () use ($accessToken,$album_id) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->get("https://api.spotify.com/v1/albums/$album_id", [
                    'limit' => 1,
                ]);
                return json_decode($response->getBody(), true);
            });
            array_push($albums, $album);
        }
        // dd($albums);

        //fetch favorite tracks
        $favorite_tracks = DB::table('favorite_tracks')->where('user_id', Auth::id())->get();
        $tracks = [];
        foreach ($favorite_tracks as $track){
            $track_id = $track->track_id;
            $track_cacheKey = "spotify-api-track-$track_id";
            $client = new Client();
                $track = Cache::remember($track_cacheKey, $seconds, function () use ($accessToken,$track_id) {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                    ])->get("https://api.spotify.com/v1/tracks/$track_id", [
                        'limit' => 1,
                    ]);
                    return json_decode($response->getBody(), true);
                });
                array_push($tracks, $track);
        }
        // dd($tracks);

        return view('favorite', [
            'username' => Auth::user()->name,
            'favorite_albums' => $albums,
            'favorite_tracks' => $tracks,
            'groups' => girlGroup::all(),
        ]);
    }
    public function viewByFilter($username,$groupIds,$filter)
    {
        $accessToken = $this->fetchTocken();
        $groupIdsArray = explode(',', $groupIds);

        if ($filter==='ALBUM'){
            $favorite_albums = $this->fetchFavoriteAlbumsByGroupIds($accessToken,$groupIdsArray);
            $data = [
                'albums' => $favorite_albums,
            ];
            return response()->json($data);
        }
        else if ($filter==='TRACK'){
            $favorite_tracks = $this->fetchFavoriteTracksByGroupIds($accessToken,$groupIdsArray);
            $data = [
                'tracks' => $favorite_tracks,
            ];
            return response()->json($data);
        }
    }

    //helper functions
    private function fetchTocken()
    {
        //fetch token that expires every 1 hour
        $client = new Client();
        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
            ],
        ]);
        $body = json_decode($response->getBody(), true);
        return $body['access_token'];
    }
    private function fetchFavoriteAlbumsByGroupIds($accessToken,$groupIds)
    {
        $favorite_albums = DB::table('favorite_albums')->where('user_id', Auth::id())->get();
        $albums = [];
        foreach ($favorite_albums as $album){
            $album_id = $album->album_id;
            $album_cacheKey = "spotify-api-album-$album_id";
            $album_array = Cache::remember($album_cacheKey, 120, function () use ($accessToken,$album_id) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->get("https://api.spotify.com/v1/albums/$album_id", [
                    'limit' => 1,
                ]);
                return json_decode($response->getBody(), true);
            });
            $album_array['added_to_favorite_at'] = $album->updated_at;

            if (in_array($album_array['artists'][0]['id'], $groupIds)) {
                array_push($albums, $album_array);
            }
        }
        return $albums;
    }
    private function fetchFavoriteTracksByGroupIds($accessToken,$groupIds)
    {
        $favorite_tracks = DB::table('favorite_tracks')->where('user_id', Auth::id())->get();
        $tracks = [];
        foreach ($favorite_tracks as $track){
            $track_id = $track->track_id;
            $track_cacheKey = "spotify-api-track-$track_id";
            $track_array = Cache::remember($track_cacheKey, 120, function () use ($accessToken,$track_id) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->get("https://api.spotify.com/v1/tracks/$track_id", [
                    'limit' => 1,
                ]);
                return json_decode($response->getBody(), true);
            });
            $track_array['added_to_favorite_at'] = $track->updated_at;

            if (in_array($track_array['artists'][0]['id'], $groupIds)) {
                array_push($tracks, $track_array);
            }
        }
        return $tracks;
    }
}
