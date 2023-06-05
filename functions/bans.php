<?php
// Read the bans.json file
$bans_file = $_SERVER['DOCUMENT_ROOT'] . '/bans.json';
$bans = json_decode(file_get_contents($bans_file), true);

// Get the user's IP address
$headers = array(
    'HTTP_CLIENT_IP',
    'HTTP_X_FORWARDED_FOR',
    'HTTP_X_FORWARDED',
    'HTTP_X_CLUSTER_CLIENT_IP',
    'HTTP_FORWARDED_FOR',
    'HTTP_FORWARDED',
    'REMOTE_ADDR'
);

foreach ($headers as $header) {
    if (array_key_exists($header, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$header]) as $ip) {
            $ip = trim($ip); // just to be safe

            if (in_array($ip, $bans)) {
                // If the user's IP address is in the bans list, prevent connection
                die('You are banned from accessing this website.');
            }
        }
    }

    // If the user's IP address is not in the bans list, allow connection
}
?>