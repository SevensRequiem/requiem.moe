<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class LoggingController extends Controller
{

    public function log(Request $request, $url)
    {


            $request = request();
            $ip = $request->ip();
            $timestamp = time();

            $geoip = geoip($ip);


            $user_agent = $request->header('User-Agent');
            
            $session = $request->session()->getId();
            $user = $request->user();
            $user_id = $user->id ?? null;

            $data = <<<EOT
            ==================================
            Timestamp: {$timestamp}

            IP: {$ip}
            Country: {$geoip->country}
            Region: {$geoip->state_name}
            City: {$geoip->city}
            Coords: {$geoip->lat}, {$geoip->lon}
            Timezone: {$geoip->timezone}

            User Agent: {$user_agent}
            Session: {$session}
            User ID: {$user_id}
            URL: {$url}
            ##################################
            {$request}
            ==================================
            EOT;
        
            $file = storage_path('logs/visitors.log');
            File::append($file, $data);
            response()->json(['success' => true]);

            $db = DB::connection('visitors');
            $db->table('visitors')->insert([
                'timestamp' => $timestamp,
                'ip' => $ip,
                'country' => $geoip->country,
                'region' => $geoip->state_name,
                'city' => $geoip->city,
                'coords' => $geoip->lat . ', ' . $geoip->lon,
                'timezone' => $geoip->timezone,
                'user_agent' => $user_agent,
                'session' => $session,
                'user_id' => $user_id,
                'url' => $url,
                'referer' => $request->header('referer') ?? '',
                'full-request' => $request->__toString(),
                'uuid' => $timestamp."_".bin2hex(random_bytes(8)),
            ]);
    }
}