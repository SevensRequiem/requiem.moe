<?php
/* Discord Oauth v.4.1
 * This file contains the core functions of the oauth2 script.
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */

# Starting session so we can store all the variables
session_start();

# Setting the base url for API requests
$GLOBALS['base_url'] = "https://discord.com";

# Setting bot token for related requests
$GLOBALS['bot_token'] = 'OTk1NTUxMDQyMTg1OTkwMTY1.GrSk0N.3CVV3526NpH_4Hs2Rb5bM2Lb9QTlLdm-8Xo3Go';
$guildid = '509327631313928193';
$adminid = '585659362236694549';
$roleid = '1091351812591861780';

# A function to generate a random string to be used as state | (protection against CSRF)
function gen_state()
{
    $_SESSION['state'] = bin2hex(openssl_random_pseudo_bytes(12));
    return $_SESSION['state'];
}

# A function to generate oAuth2 URL for logging in
function url($clientid, $redirect, $scope)
{
    $state = gen_state();
    return 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . $clientid . '&redirect_uri=' . $redirect . '&scope=' . $scope . "&state=" . $state;
}

# A function to initialize and store access token in SESSION to be used for other requests
function init($redirect_url, $client_id, $client_secret, $bot_token = null)
{
    if ($bot_token != null) {
        # Check if bot_token is a valid access token
        if (!preg_match('/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/i', $bot_token)) {
            die("Invalid bot token");
        }
        $GLOBALS['bot_token'] = $bot_token;
    }

    # Check if code parameter is set
    if (!isset($_GET['code'])) {
        die("Code parameter not found");
    }

    # Check if state parameter is set
    if (!isset($_GET['state'])) {
        die("State parameter not found");
    }

    $code = $_GET['code'];
    $state = $_GET['state'];

    # Check if $state == $_SESSION['state'] to verify if the login is legit | CHECK THE FUNCTION get_state($state) FOR MORE INFORMATION.
    if ($state != $_SESSION['state']) {
        die("Invalid state parameter");
    }

    # Validate redirect URL
    if (!filter_var($redirect_url, FILTER_VALIDATE_URL)) {
        die("Invalid redirect URL");
    }

    $url = $GLOBALS['base_url'] . "/api/oauth2/token";
    $data = array(
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => $redirect_url
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);


    $_SESSION['access_token'] = $results['access_token'];
}


# A function to get user information | (identify scope)
function get_user($email = null)
{
    $url = $GLOBALS['base_url'] . "/api/users/@me";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $_SESSION['user'] = $results;
    $_SESSION['username'] = $results['username'];
    $_SESSION['discrim'] = $results['discriminator'];
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['user_avatar'] = $results['avatar'];
    # Fetching email 
    if ($email == True) {
        $_SESSION['email'] = $results['email'];
    }
}

# A function to give roles to the user
# Note : The bot has to be a member of the server with MANAGE_ROLES permission.
#        The bot DOES NOT have to be online, just has to be a bot application and has to be a member of the server.
#        This is the basic function which requires few parameters. [ 1: Guild ID,  2: Role ID ]
function give_role($guildid, $roleid)
{
    $data = json_encode(array("roles" => array("$roleid")));
    $url = $GLOBALS['base_url'] . "/api/guilds/" . $guildid . "/members/" . $_SESSION['user_id'];
    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $_SESSION['access_token']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}


# A function to get user guilds | (guilds scope)
function get_guilds()
{
    $url = $GLOBALS['base_url'] . "/api/users/@me/guilds";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

# A function to fetch information on a single guild | (requires bot token)
function get_guild($guildid)
{
    $url = $GLOBALS['base_url'] . "/api/guilds/$guildid";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bot ' . $GLOBALS['bot_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

# A function to get user connections | (connections scope)
function get_connections()
{
    $url = $GLOBALS['base_url'] . "/api/users/@me/connections";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

# Function to make user join a guild | (guilds.join scope)
# Note : The bot has to be a member of the server with CREATE_INSTANT_INVITE permission.
#        The bot DOES NOT have to be online, just has to be a bot application and has to be a member of the server.
#        This is the basic function with no parameters, you can build on this to give the user a nickname, mute, deafen or assign a role.      
function join_guild($guildid)
{
    $data = json_encode(array("access_token" => $_SESSION['access_token']));
    $url = $GLOBALS['base_url'] . "/api/guilds/$guildid/members/" . $_SESSION['user_id'];
    $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $GLOBALS['bot_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}
# A function to check if a member has a specific role
# Note: This function requires the guild ID and the role ID as parameters

# A function to verify if login is legit
function check_state($state)
{
    if ($state == $_SESSION['state']) {
        return true;
    } else {
        # The login is not valid, so you should probably redirect them back to home page
        http_response_code(401);
        header('index.php');
        return false;
    }
}
?>