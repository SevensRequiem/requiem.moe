<?php
/**
 * Get the user's IP address.
 *
 * @return string The user's IP address.
 */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Get the user's data.
 *
 * @return array The user's data.
 */
function getUserData() {
    return $_POST; 
}

/**
 * Ban the user's IP address.
 *
 * @param string $ip The IP address to ban.
 * @param string $reason The reason for the ban.
 */
function banIP($ip, $reason) {
    $banData = array(
        'ip' => $ip,
        'data' => null,
        'time' => time(),
        'reason' => $reason
    );
    $banList = json_decode(file_get_contents('bans.json'), true);
    $banList[] = $banData;
    file_put_contents('bans.json', json_encode($banList));
    // Add code here to ban the IP address.
}

/**
 * Ban the user's data.
 *
 * @param array $data The data to ban.
 * @param string $reason The reason for the ban.
 */
function banData($data, $reason) {
    $banData = array(
        'ip' => getUserIP(),
        'data' => $data,
        'time' => time(),
        'reason' => $reason
    );
    $banList = json_decode(file_get_contents('bans.json'), true);
    $banList[] = $banData;
    file_put_contents('bans.json', json_encode($banList));
    // Add code here to ban the user's data.
}
?>