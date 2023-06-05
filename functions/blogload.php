<?php
require "./Parsedown.php"; // Create a new instance of Parsedown
$Parsedown = new Parsedown();

$Parsedown->setSafeMode(true); // Get all post UUIDs and sort by creation date of post.md file (most recent first)
$postDirs = glob("../data/posts/*", GLOB_ONLYDIR);
usort($postDirs, function ($a, $b) {
    $aPostMdTime = filemtime($a . "/post.md");
    $bPostMdTime = filemtime($b . "/post.md");
    return $bPostMdTime - $aPostMdTime;
}); // loop through post UUIDs
foreach ($postDirs as $postDir) {
    // get post data from post.json
    $postJsonPath = $postDir . "/post.json";
    $postData = json_decode(file_get_contents($postJsonPath), true);
    $postTitle = $postData["title"];
    $postContent = file_get_contents($postDir . "/post.md"); // get post content from post.md
    $postImage = $postData["image"];
    $postQuote = $postData["quote"];
    $postDate = $postData["date"];
    $postId = $postData["uuid"];
    $postAuthor = $postData["author"];
    $postUrl = "../data/posts/" . $postId . "/"; // display post data
    echo '<fieldset class="blogpost">';
        echo "<legend>". $postAuthor .":  " . $postTitle . ".txt " . $postDate . "</legend>";
        echo $Parsedown->text($postContent);
        // Parse post content with Parsedown
        if (!empty($postImage)) {
            echo '<img src="' . $postImage . '" width="50%" height="20%">';
        }
        if (!empty($postQuote)) {
            echo "<p>" . $postQuote . "</p>";
        }
     // Set comments file path
    $comments_file = "../data/posts/" . $postId . "/comments/comments.xml"; // Load comments from file
    $comments = simplexml_load_file($comments_file); // Display existing comments
    if ($comments->comment) {
        foreach ($comments->comment as $comment) {
            echo '<div class="comment">';
            echo "<p><strong>" .
                $comment->author .
                "</strong> said on " .
                $comment->date .
                ":</p>";
            echo "<p>" . $Parsedown->text($comment->content) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No comments yet. Be the first to leave a comment!</p>";
    } // Display comment form if user is logged in
    echo "</fieldset>";
}
 ?>