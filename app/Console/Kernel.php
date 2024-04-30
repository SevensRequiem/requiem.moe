<?php

namespace App\Console;

// app/Console/Kernel.php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp;


class Kernel extends ConsoleKernel
{


    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
    
            $ytapiKey = 'AIzaSyBhOT2Shz-t6DQdmVrR9GKFqVz4luamiPE';
            $ytchannelId = 'UCWCLrljiADMzumB21isaMbg'; // Replace with your YouTube channel ID
    
            $ytapiUrl = "https://www.googleapis.com/youtube/v3/channels?key=$ytapiKey&id=$ytchannelId&part=snippet,statistics";
    
            // Fetch YouTube data
            $response = $client->get($ytapiUrl);
            $youtubeData = json_decode($response->getBody(), true);
    
            // Save YouTube data to a JSON file in the storage directory
            Storage::put('youtube.json', json_encode($youtubeData));
        })->everySixHours();

        $schedule->call(function () {
            $tor = file_get_contents("https://check.torproject.org/cgi-bin/TorBulkExitList.py");
            $vpn = file_get_contents("https://raw.githubusercontent.com/X4BNet/lists_vpn/main/ipv4.txt");
            Storage::put('tor_exit_nodes.txt', $tor);
            Storage::put('vpn_ip_list.txt', $vpn);
            Cache::forget('tor_exit_nodes');
            Cache::forget('vpn_ip_list');
            Cache::remember('tor_exit_nodes', now()->addHours(6), function () {
                return explode("\n", File::get(storage_path('tor_exit_nodes.txt')));
            });
            Cache::remember('vpn_ip_list', now()->addHours(6), function () {
                return explode("\n", File::get(storage_path('vpn_ip_list.txt')));
            });
            
        })->everySixHours();
        $schedule->call('App\Http\Controllers\GithubController@getallstats')->daily();
        $schedule->call('App\Http\Controllers\GithubController@getwebring')->everySixHours();

    }
    

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}