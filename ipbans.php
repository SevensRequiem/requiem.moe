<?php
require_once 'vendor/autoload.php'; // Include the Composer autoloader
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'bot') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Spider') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'crawler') !== false|| strpos($_SERVER['HTTP_USER_AGENT'], 'censys') !== false)) {
    header('HTTP/1.1 403 Forbidden');
    exit('This page is not accessible to bots');
}
function checkProxy($ip){
  $contactEmail="externalthunder@gmail.com"; //you must change this to your own email address
  $timeout=5; //by default, wait no longer than 5 secs for a response
  $banOnProbability=0.99; //if getIPIntel returns a value higher than this, function returns true, set to 0.99 by default
  
  //init and set cURL options
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

  //if you're using custom flags (like flags=m), change the URL below
  curl_setopt($ch, CURLOPT_URL, "http://check.getipintel.net/check.php?ip=$ip&contact=$contactEmail");
  $response=curl_exec($ch);
  
  curl_close($ch);
  
  
  if ($response > $banOnProbability) {
      return true;
  } else {
    if ($response < 0 || strcmp($response, "") == 0 ) {
      //The server returned an error, you might want to do something
      //like write to a log file or email yourself
      //This could be true due to an invalid input or you've exceeded
      //the number of allowed queries. Figure out why this is happening
      //because you aren't protected by the system anymore
      //Leaving this section blank is dangerous because you assume
      //that you're still protected, which is incorrect
      //and you might think GetIPIntel isn't accurate anymore
      //which is also incorrect.

      //failure to implement error handling is bad for the both of us

    }
      return false;
  }
}
$ip = $_SERVER['REMOTE_ADDR'];
$database = new \IP2Location\Database('./IP2LOCATION-LITE-DB1.BIN', \IP2Location\Database::FILE_IO);

$record = $database->lookup($ip, \IP2Location\Database::ALL);

$europeanCountries = [
  'AL', // Albania
  'AD', // Andorra
  'AM', // Armenia
  'AT', // Austria
  'AZ', // Azerbaijan
  'BY', // Belarus
  'BE', // Belgium
  'BA', // Bosnia and Herzegovina
  'BG', // Bulgaria
  'HR', // Croatia
  'CY', // Cyprus
  'CZ', // Czech Republic
  'DK', // Denmark
  'EE', // Estonia
  'FI', // Finland
  'FR', // France
  'GE', // Georgia
  'DE', // Germany
  'GR', // Greece
  'HU', // Hungary
  'IS', // Iceland
  'IE', // Ireland
  'IT', // Italy
  'KZ', // Kazakhstan
  'LV', // Latvia
  'LI', // Liechtenstein
  'LT', // Lithuania
  'LU', // Luxembourg
  'MK', // North Macedonia
  'MT', // Malta
  'MD', // Moldova
  'MC', // Monaco
  'ME', // Montenegro
  'NL', // Netherlands
  'NO', // Norway
  'PL', // Poland
  'PT', // Portugal
  'RO', // Romania
  'RU', // Russia
  'SM', // San Marino
  'RS', // Serbia
  'SK', // Slovakia
  'SI', // Slovenia
  'ES', // Spain
  'SE', // Sweden
  'CH', // Switzerland
  'TR', // Turkey
  'UA', // Ukraine
  'GB', // United Kingdom
  'VA', // Vatican City
];
$natoMemberCountries = [
  'AL', // Albania
  'BE', // Belgium
  'BG', // Bulgaria
  'CA', // Canada
  'HR', // Croatia
  'CZ', // Czech Republic
  'DK', // Denmark
  'EE', // Estonia
  'FR', // France
  'DE', // Germany
  'GR', // Greece
  'HU', // Hungary
  'IS', // Iceland
  'IT', // Italy
  'LV', // Latvia
  'LT', // Lithuania
  'LU', // Luxembourg
  'MT', // Malta
  'NL', // Netherlands
  'NO', // Norway
  'PL', // Poland
  'PT', // Portugal
  'RO', // Romania
  'SK', // Slovakia
  'SI', // Slovenia
  'ES', // Spain
  'TR', // Turkey
  'GB', // United Kingdom
  'US', // United States
];
$usMajorAllies = [
  'AU', // Australia
  'CA', // Canada
  'DK', // Denmark
  'FR', // France
  'DE', // Germany
  'IT', // Italy
  'JP', // Japan
  'KR', // South Korea
  'NL', // Netherlands
  'NO', // Norway
  'ES', // Spain
  'TR', // Turkey
  'GB', // United Kingdom
];


$allowedIPs = ['192.168.1.1']; // Add local IP ranges for country check

$welcome = '';
if (!in_array($record['countryCode'], $natoMemberCountries) && !in_array($ip, $allowedIPs) && !in_array($record['countryCode'], $europeanCountries) && !in_array($record['countryCode'], $usMajorAllies)) {
  header('HTTP/1.0 403 Forbidden');
  echo '<span>Access denied. Blacklisted country and non-local IPs are not allowed to access this page.</span><br>';
  echo '<span>Your IP address is ' . $ip . ' and your country is ' . $record['countryCode'] . '.</span>';
  $log_message = 'COUNTRY: Access denied for IP ' . $ip . ' from country ' . $record['countryCode'];
  error_log($log_message);
  exit;
} else if (checkProxy($ip)) {
  $allowedCloudflareIps = ['173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/12', '172.64.0.0/13', '131.0.72.0/22'];
  
  // Check if the IP is in the allowed Cloudflare IPs
  $ipLong = ip2long($ip);
  $isAllowedCloudflareIp = false;
  foreach ($allowedCloudflareIps as $allowedCloudflareIp) {
    list($subnet, $bits) = explode('/', $allowedCloudflareIp);
    $subnetLong = ip2long($subnet);
    $mask = ~((1 << (32 - $bits)) - 1);
    $subnetMasked = $subnetLong & $mask;
    if (($ipLong & $mask) == $subnetMasked) {
      $isAllowedCloudflareIp = true;
      break;
    }
  }
  
  if (!$isAllowedCloudflareIp) {
    header('HTTP/1.0 403 Forbidden');
    echo '<span>Access denied. Proxy IPs are not allowed to access this page.</span><br>';
    echo '<span>Your IP address is ' . $ip . ' and your country is ' . $record['countryCode'] . '.</span>';
      $log_message = 'PROXY: Access denied for IP ' . $ip . ' from country ' . $record['countryCode'];
  error_log($log_message);
    exit;
  }
} else {
}
$welcome = 'Welcome NATO member from ' . $record['countryCode'] . ' or ' . $ip . '!';

?>