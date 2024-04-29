<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_YouTube;
use App\Models\girlGroup;

class YouTubeController extends Controller
{
    public function getYouTubeFans()
    {
        $client = new Google_Client();
        $client->setApplicationName('YourApplicationName');
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $youtube = new Google_Service_YouTube($client);

        $girlGroups = girlGroup::all();

        foreach ($girlGroups as $girlGroup) {
            $youtube_handle = $girlGroup->youtube_handle;
            $params = [
                'part' => 'statistics',
                'maxResults' => 1,
                'forHandle' => $youtube_handle,
            ];
            $response = $youtube->channels->listChannels('statistics', $params);
            $girlGroup->youtube_fans = $response->items[0]->statistics->subscriberCount;
            $girlGroup->save();
        }

        //sort by fans decs and save to table
        $sortedGirlGroups = girlGroup::orderBy('youtube_fans', 'desc')->get();

        return view('index', ['groups' => $sortedGirlGroups]);
    }
}
