<?php
// Replace YOUR_BOT_TOKEN with your actual bot token
$bot_token = 'MTEwNzk4ODE2NTg0OTAwMjAzNA.GuE5Hk.cfHn0E2-RJE1Ss7ZDaDTb_MRFanqXs2aJNu-I0';

// Replace GUILD_ID with the ID of the guild you want to check the user status in
$guild_id = '509327631313928193';

// Replace USER_ID with the ID of the user you want to check the status of
$user_id = '228343232520519680';

// Make a request to the Discord API to get the user presence information in the specified guild
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://discord.com/api/guilds/$guild_id/members/$user_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bot $bot_token"
));
$response = curl_exec($ch);
curl_close($ch);

// Parse the response to get the user presence information
$data = json_decode($response, true);
$online_status = $data['status'];
$user_status = $data['activities'][0]['state'];

// Output the online status and user status
?>