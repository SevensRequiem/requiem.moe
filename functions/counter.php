<?php
// Start the session
session_start();

// Check if the visitor has a session cookie
if(isset($_SESSION['visited'])){
    // If they do, increment the hit count but don't count them as a unique visitor
    $_SESSION['hits']++;
} else {
    // If they don't, set the session cookie and count them as a unique visitor
    $_SESSION['visited'] = true;
    $_SESSION['hits'] = 1;
}

// Increment the total hit count and write it to a file
$filename = 'hitcount.txt';
if(file_exists($filename)){
    $count = file_get_contents($filename);
    $count++;
} else {
    $count = 1;
}
file_put_contents($filename, $count);
?>