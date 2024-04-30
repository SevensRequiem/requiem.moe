<?php
session_start();

// Get the user's IP address
$ip = $_SERVER['REMOTE_ADDR'];

// Get the last visit time from the session
$last_visit = $_SESSION['last_visit'][$ip] ?? 0;

// Only update the count if the user hasn't visited in the last 8 hours
if (time() - $last_visit >= 8 * 60 * 60) {
    // Check if the User-Agent header contains "Better Uptime Bot"
    if (!isset($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'Better Uptime Bot') === false) {
        $file = 'counter.txt';
        $count = intval(file_get_contents($file));
        file_put_contents($file, $count + 1);
    }

    // Update the last visit time in the session
    $_SESSION['last_visit'][$ip] = time();
}

// Return the current count
echo file_get_contents('counter.txt');
?>