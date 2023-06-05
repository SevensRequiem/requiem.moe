<?php
require 'functions/honeypot.php';
$data = getUserData();
$ip = getUserIP();
banIP($ip, $reason);
banData($data, $reason);
echo 'You are banned.';
?>