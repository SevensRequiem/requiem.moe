<?php
$opts = [
    "http" => [
        "method" => "GET",
        "header" => 'Authorization: Bot MTEwNzk4ODE2NTg0OTAwMjAzNA.GuE5Hk.cfHn0E2-RJE1Ss7ZDaDTb_MRFanqXs2aJNu-I0'
    ]
];


$context = stream_context_create($opts);

$file = file_get_contents('https://discordapp.com/api/guilds/509327631313928193?with_counts=true', false, $context);
$file = json_decode($file, true);
$member_count = intval($file["approximate_member_count"]);
?>