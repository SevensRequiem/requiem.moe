<?php

function uptime() {
    $uptime = '';
    $uptime_seconds = time() - strtotime('2023-04-01 00:00:00'); // Replace with your server start time
    $years = floor($uptime_seconds / 31536000);
    $months = floor(($uptime_seconds - ($years * 31536000)) / 2628000);
    $days = floor(($uptime_seconds - ($years * 31536000) - ($months * 2628000)) / 86400);
    $hours = floor(($uptime_seconds - ($years * 31536000) - ($months * 2628000) - ($days * 86400)) / 3600);
    $minutes = floor(($uptime_seconds - ($years * 31536000) - ($months * 2628000) - ($days * 86400) - ($hours * 3600)) / 60);
    $seconds = $uptime_seconds - ($years * 31536000) - ($months * 2628000) - ($days * 86400) - ($hours * 3600) - ($minutes * 60);
    if ($years > 0) {
        $uptime .= $years . 'Y:';
    }
    if ($months > 0) {
        $uptime .= $months . 'M:';
    }
    if ($days > 0) {
        $uptime .= $days . 'D:';
    }
    if ($hours > 0) {
        $uptime .= $hours . 'H:';
    }
    if ($minutes > 0) {
        $uptime .= $minutes . 'M:';
    }
    $uptime .= $seconds . 'S';
    return $uptime;
}

echo uptime();
?>