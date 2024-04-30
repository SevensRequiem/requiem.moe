<?php
// ("https://check.torproject.org/cgi-bin/TorBulkExitList.py"));
//ile_get_contents("https://raw.githubusercontent.com/X4BNet/lists_vpn/main/ipv4.txt"));
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class IPFilterMiddleware
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();

        // Check if the IP is in the Tor exit node list
        $torlist = Cache::get('tor_exit_nodes', function () {
            return explode("\n", File::get(storage_path('tor_exit_nodes.txt')));
        });

        if (in_array($ip, $torlist)) {
            return response("Due to abuse, Tor is not allowed.", 403);
        }

        // Check if the IP is in the VPN list
        $vpnlist = Cache::get('vpn_ip_list', function () {
            return explode("\n", File::get(storage_path('vpn_ip_list.txt')));
        });

        if (in_array($ip, $vpnlist)) {
            return response("Due to abuse, VPNs are not allowed.", 403);
        }

        // filter out requests with no user agent
        if (!$request->server('HTTP_USER_AGENT')) {
            return response("Due to abuse, requests with no user agent are not allowed.", 403);
        }

        return $next($request);

    }
}
