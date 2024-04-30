<?php
$clientID = '414fea1b24d3444fb60c7785a611294d';
$clientSecret = 'bbc03a818b4941b3a1adc06b19621c5b';

// Get access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode($clientID . ':' . $clientSecret)
]);

$response = curl_exec($ch);
$data = json_decode($response, true);

if (isset($data['access_token'])) {
    $accessToken = $data['access_token'];

    // Get top artists
    $ch = curl_init('https://api.spotify.com/v1/me/top/artists?limit=15');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken
    ]);

    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);

    // Print out the response for debugging
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // existing code...
} else {
    echo 'No access token found.';
}

?>