<?php 
    $clientid = "6c57f2a80ce53e7f86bfb84cb1799821"; // https://myanimelist.net/apiconfig
    $username = "sevensrequiem"; //your MAL username
    $rvars = "status=watching&sort=list_updated_at"; // your request vars https://myanimelist.net/apiconfig/references/api/v2#tag/user-animelist
    $url = "https://api.myanimelist.net/v2/users/{$username}/animelist?{$rvars}";
    $ch = curl_init($url); // Initialise cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'X-MAL-CLIENT-ID: '.$clientid )); // Inject the token into the header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch); // Execute the cURL statement
    // Decoding JSON data
    $decodedData =
        json_decode($result, true);
    curl_close($ch); // Close the cURL connection
?>