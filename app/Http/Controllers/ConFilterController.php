<?php

// app/Http/Controllers/HitCounterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ConFilterController extends Controller
{
    public function check(){
        $ip = $_SERVER['REMOTE_ADDR'];
        $last_visit = Session::get('last_visit')[$ip] ?? 0;
        $torlist = curl ("https://check.torproject.org/cgi-bin/TorBulkExitList.py");
        $torlist = explode("\n", $torlist);
        $vpnlist = curl ("https://raw.githubusercontent.com/X4BNet/lists_vpn/main/ipv4.txt");
        if (in_array($ip, $torlist)) {
            die("Due to abuse, Tor is not allowed.");
        }
        if (in_array($ip, $vpnlist)) {
            die("Due to abuse, VPNs are not allowed.");
        }
    }
}