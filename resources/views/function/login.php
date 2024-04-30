<?php

use Wohali\OAuth2\Client\Provider\Discord;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
// Initialize Discord provider 
$provider = new Discord([
    'clientId' => '1107988165849002034',
    'clientSecret' => 'VT5UOnW_5SZFyNiisuVQzs4CV-rah_QU',
    'redirectUri' => 'http://localhost/moe/public/login',
    'state' => bin2hex(random_bytes(64)),
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the 
    // urlAuthorize option and generates and applies any necessary parameters 
    // (e.g. state). 
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session. 
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL. 
    header('Location: ' . $authorizationUrl);
    exit;
    // Check given state against previously stored one to mitigate CSRF attack 
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {

    // Try to get an access token (using the authorization code grant), then save it in the session and redirect to the homepage
// Try to get an access token (using the authorization code grant), then save it in the session and redirect to the homepage
try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
    $_SESSION['token'] = $token->getToken();
    $_SESSION['refresh_token'] = $token->getRefreshToken();
    $_SESSION['expires'] = $token->getExpires();
    $_SESSION['values'] = $token->getValues();
    $_SESSION['user'] = $provider->getResourceOwner($token)->toArray();
    $userId = $provider->getResourceOwner($token)->getId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['csrf_token'] = bin2hex(random_bytes(64));

    // Set the session as admin if USER_ID matches the id in the array
    $admins = array(228343232520519680, 410115099634696192); // Replace with your array of admin IDs
    if (in_array($userId, $admins)) {
        $_SESSION['admin'] = true;
    }

    // Log the user's login information
    $logMessage = "----------------------------------------------\n[" . json_encode($_SESSION['user']) . "] [{$_SESSION['user_id']}] logged in from IP address [{$_SERVER['REMOTE_ADDR']}] with user agent [{$_SERVER['HTTP_USER_AGENT']}] at " . date('Y-m-d H:i:s') . "\n";
    $logFile = '../storage/logs/login.log';
    $logDir = dirname($logFile);

    if (!is_dir($logDir)) {
        $directories = scandir(dirname($logDir));
        echo "Could not find directory: $logDir\n";
        echo "Directories in current directory: \n";
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                echo "$directory\n";
            }
        }
    } else {
        error_log($logMessage, 3, $logFile);
    }

    header("location: /moe/public/home");
    exit;
} catch (Exception $e) {
    // Failed to get the access token or user details. 
    exit($e->getMessage());
}
}
?>
