<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\GirlGroup;
use App\Models\AlbumComment;
use App\Models\TrackComment;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Auth;
use DB;

class DetailController extends Controller
{
    public function groupDetail($groupName)
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

        //get spotifyID for this girl group
        $group = girlGroup::where('name','=',$groupName)->first();
        $spotifyID = $group -> spotify_id;

        //fetch group info
        $cacheKey = "spotify-api-group-$groupName";
        $seconds = 1;
        $group = Cache::remember($cacheKey, $seconds, function () use ($accessToken,$spotifyID) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get("https://api.spotify.com/v1/artists/$spotifyID", [
                'limit' => 50,
            ]);
            return json_decode($response->getBody(), true);
        });

        //fetch all albums of this group
        $cacheKey = "spotify-api-albums-$groupName";
        $albums = Cache::remember($cacheKey, $seconds, function () use ($accessToken,$spotifyID) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get("https://api.spotify.com/v1/artists/$spotifyID/albums", [
                'limit' => 50,
                'include_groups' => 'album,single'
            ]);
            return json_decode($response->getBody(), true);
        });

        //fetch favorite list of current user
        if (Auth::check()){
            $user = Auth::user();
            $favorites = DB::table('favorite_albums')->where('user_id','=',$user->id)->get();
        }
        else {
            $favorites = NULL;
        }
        
        
        return view('groupDetail', ['groupName' => $groupName, 'group' => $group,'albums' => $albums['items'],'favorites' => $favorites]);
    }

    public function albumDetail($groupName,$albumID,$commentIndex)
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
   
        //fetch this album info
        $cacheKey = "spotify-api-album-$albumID";
        $seconds = 120;
        $album = Cache::remember($cacheKey, $seconds, function () use ($accessToken,$albumID) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get("https://api.spotify.com/v1/albums/$albumID", [
                'limit' => 1,
            ]);
            return json_decode($response->getBody(), true);
        });

        //fetch all tracks under this album
        $cacheKey = "spotify-api-tracks-$albumID";
        $tracks = Cache::remember($cacheKey, $seconds, function () use ($accessToken,$albumID) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get("https://api.spotify.com/v1/albums/$albumID/tracks", [
                'limit' => 50,
            ]);
            return json_decode($response->getBody(), true);
        });

        if (Auth::check()){
            //fetch favorite tracks of current user
            $user = Auth::user();
            $favorite_tracks = DB::table('favorite_tracks')->where('user_id','=',$user->id)->get();
            $favorite_album_exist = DB::table('favorite_albums')->where('user_id','=',$user->id)->where('album_id','=',$albumID)->get();
            //fetch comments for this album
            if ($commentIndex==0){
                $comments = AlbumComment::where('album_id', $albumID)->get();
            }
            else {
                $commentTrack = $tracks['items'][$commentIndex-1];
                $comments = TrackComment::where('track_id', $commentTrack['id'])->get();
            }
        }
        else {
            //TODO: front-end
            $favorite_tracks = NULL;
            $favorite_album_exist = NULL;
            $comments = NULL;
        }
        

        return view('albumDetail', [
            'groupName' => $groupName,
            'album' => $album,
            'tracks' => $tracks['items'],
            'favorite_tracks' => $favorite_tracks,
            'favorite_album_exist' => $favorite_album_exist,
            'comments' => $comments,
            'commentIndex' => $commentIndex,
        ]);
    }
}
