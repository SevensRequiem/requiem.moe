<?php
require './includes/discord.php';
function has_admin($guildid, $adminid)
{
    $url = $GLOBALS['base_url'] . "/api/guilds/$guildid/members/" . $_SESSION['user_id'];
    $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $GLOBALS['bot_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $roles = $results['roles'];
    return in_array($adminid, $roles);
}
// Load Parsedown library for Markdown parsing
require_once './vendor/autoload.php';
$Parsedown = new Parsedown();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Get post UUID from query string parameter
$postId = $_GET['post'];

// Set post file path
$post_file = './data/posts/' . $postId . '/post.md';

// Check if post file exists
if (!file_exists($post_file)) {
    echo 'Post not found.';
    exit;
}

// Load post content from file
$post_content = file_get_contents($post_file);

// Handle form submission
if (($_SERVER['REQUEST_METHOD'] === 'POST') && (has_admin($guildid, $adminid))) {

    // Get new post content from form data
    $new_post_content = $_POST['content'];

    // Save new post content to file
    $result = file_put_contents($post_file, $new_post_content);

    // Display success or error message
    if ($result !== false) {
        echo '<p>Post saved successfully.</p>';
    } else {
        echo '<p>Error saving post.</p>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post | Requiem</title>
</head>
<body>
    <h1>Edit Post</h1>
    <form method="post">
        <textarea name="content" rows="20" cols="80"><?php echo $post_content; ?></textarea>
        <br>
        <input type="submit" value="Save">
    </form>
    <p><a href="blog.php">Back to Blog</a></p>
</body>
</html>
