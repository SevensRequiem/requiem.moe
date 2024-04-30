<?php
// Set the start date and time
$startDate = strtotime("2023-01-01 00:00:00");

// Calculate the uptime in seconds
$uptime = time() - $startDate;

// Convert the uptime to years, months, days, hours, and minutes
$years = floor($uptime / 31536000);
$months = floor(($uptime % 31536000) / 2592000);
$days = floor(($uptime % 2592000) / 86400);
$hours = floor(($uptime % 86400) / 3600);
$minutes = floor(($uptime % 3600) / 60);

// Output the uptime in the desired format
echo "$years:$months:$days:$hours:$minutes";
?>