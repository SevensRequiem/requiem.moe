<?php

// app/Http/Controllers/HitCounterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class HitCounterController extends Controller
{
    public function gethits()
    {
        return File::get(storage_path('counter.txt'));
    }
    public function addhit()
    {

        $wanip = Cache::remember('wanip', 60*48, function () {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://httpbin.org/ip");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            curl_close($curl);
            return json_decode($output, true);
        });
        $ip = $_SERVER['REMOTE_ADDR'];
        $last_visit = Session::get('last_visit')[$ip] ?? 0;

        if (time() - $last_visit >= 8 * 60 * 60 && $wanip['origin'] != $ip) {
            $userAgents = ['Better Uptime Bot', 'Uptime Kuma', 'Wayback'];

            if (!isset($_SERVER['HTTP_USER_AGENT']) || !array_filter($userAgents, function($ua) {
                return stripos($_SERVER['HTTP_USER_AGENT'], $ua) !== false;
            })) {
                $file = storage_path('counter.txt');
                $count = intval(File::get($file));
                File::put($file, $count + 1);
            }

            Session::put('last_visit', [$ip => time()]);
        }
    }

}

