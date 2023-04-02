<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Markdown Editor</title>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
	<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
</head>
<body>
<?php
error_reporting(E_ERROR);
require "./includes/discord.php";

function has_admin($guildid, $adminid)
{
    $url =
        $GLOBALS["base_url"] .
        "/api/guilds/$guildid/members/" .
        $_SESSION["user_id"];
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bot " . $GLOBALS["bot_token"],
    ];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $roles = $results["roles"];
    return is_array($roles) && in_array($adminid, $roles);
}
function save_post($post_id, $post_content) {
    $post_dir = './data/posts/' . $post_id . '/';
    $post_md_file = $post_dir . 'post.md';
    $new_post_content = htmlspecialchars($post_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $result = file_put_contents($post_md_file, $new_post_content);
    return ($result !== false);
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
    $postContent = $_POST['content'];
    $postId = $_POST['post_id'];
    save_post($postId, $postContent);
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
}

// Check if post ID is set and user has admin role
if (isset($_GET['post']) && has_admin($guildid, $adminid)) {
    // Get post ID from URL
    $postId = $_GET['post'];
    // Get post data
    $postJsonPath = './data/posts/' . $postId . '/post.json';
    $postData = json_decode(file_get_contents($postJsonPath), true);
    $postContent = file_get_contents('./data/posts/' . $postId . '/post.md');
    // Display editor
    echo '<form method="post">';
    echo '<textarea id="editor" name="content">' . $postContent . '</textarea>';
    echo '<input type="hidden" name="post_id" value="' . $postId . '">';
    echo '<input type="submit" name="submit" value="Save">';
    echo '</form>';
} else {
    // Display error message if post ID is not set or user doesn't have admin role
    echo '<p>Forbidden</p>';
}
?>

	<script>
		var editor = new SimpleMDE({
			element: document.getElementById("editor"),
			autofocus: true,
			spellChecker: false
		});
	</script>
</body>
</html>
