<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog</title>
    <link rel="stylesheet" href="blog.css">
</head>
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

if (
    !empty($_POST["content"]) &&
    !empty($_POST["title"]) &&
    has_admin($guildid, $adminid)
) {
    $uuid = bin2hex(openssl_random_pseudo_bytes(16));
    for ($cnt = 8; $cnt <= 23; $cnt += 5) {
        $uuid = substr($uuid, 0, $cnt) . "-" . substr($uuid, $cnt);
    }
    $title = $_POST["title"];
    $content = $_POST["content"];
    $image = $_POST["image"];
    $quote = $_POST["quote"];
    $put = $content;
    $date = date("m-d-Y @ H:i:s");
    $base64 = base64_encode($title . $date);
    //make post folder
    mkdir("./data/posts/" . $uuid . "/", 0700);

    //make post file
    $myFile = "./data/posts/" . $uuid . "/post.md";
    $fh = fopen($myFile, "w");
    fwrite($fh, $put);
    echo "" . $title . "_" . $content . "";
    fclose($fh);
    function copy_template_to_post_dir($post_dir)
    {
        $template_path = "./ptemplate.php";
        $index_path = $post_dir . "/index.php";
        if (!copy($template_path, $index_path)) {
            echo "Failed to copy template file...";
        } else {
            echo "Template file copied to post directory as index.php!";
        }
    }
    // Create post.json
    $postData = [
        "uuid" => $uuid,
        "title" => $title,
        "date" => $date,
        "image" => $image,
        "quote" => $quote,
        "author" => $_SESSION["username"],
    ];
    $postJson = json_encode($postData);
    $postJsonFile = "./data/posts/" . $uuid . "/post.json";
    file_put_contents($postJsonFile, $postJson);

    // Respond with success message
    echo "Post created successfully!";
    echo "<meta http-equiv='refresh' content='1'>";
    //file upload and change file name to uuid
    $currentDirectory = getcwd();
    $uploadDirectory = "./static/images/";
    $errors = []; // Store all foreseen/unforeseen errors here
    $fileExtensions = ["jpeg", "jpg", "png", "gif", "webm"]; // Get all the file extensions
    $fileName = $_FILES["fileToUpload"]["name"];
    $fileSize = $_FILES["fileToUpload"]["size"];
    $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];
    $fileType = $_FILES["fileToUpload"]["type"];
    $fileExtension = strtolower(end(explode(".", $fileName)));
    $uploadPath =
        $currentDirectory .
        $uploadDirectory .
        basename($uuid . "." . $fileExtension);
    if (isset($_POST["save"])) {
        if (!in_array($fileExtension, $fileExtensions)) {
            $errors[] = "This file extension is not allowed.";
        }
        if ($fileSize > 2000000) {
            $errors[] =
                "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }
        if (empty($errors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
            if ($didUpload) {
                echo "The file " . basename($fileName) . " has been uploaded";
            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
            }
        }
    } else {
    }
    //no admin access
} else {
}
//check if user has a specific role id (admin) and if they do, show the post panel / admin functions
//define has_admin
if (isset($_SESSION["state"])) {
    if (has_admin($guildid, $adminid)) {
        echo <<<HTML
<div class="post-panel">
    <form action="blog.php" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title">
        <input type="text" name="quote" placeholder="Quote">

        <textarea name="content" id="markdown-editor" cols="30" rows="10" placeholder="Content"></textarea>

        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Post" name="save">
    </form>
</div>

<!-- Import SimpleMDE -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<!-- Initialize SimpleMDE -->
<script>
    var simplemde = new SimpleMDE({
        element: document.getElementById("markdown-editor")
    });
</script>
HTML;
    }
}
?>
<?php
error_reporting(E_ERROR);
require "./Parsedown.php"; // Create a new instance of Parsedown
$Parsedown = new Parsedown();

$Parsedown->setSafeMode(true); // Get all post UUIDs and sort by creation date of post.md file (most recent first)
$postDirs = glob("./data/posts/*", GLOB_ONLYDIR);
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
    $postUrl = "./data/posts/" . $postId . "/"; // display post data
    echo '<fieldset class="blogpost">';
        if (has_admin($guildid, $adminid)) {
            echo '<a href="edit.php?post=' . $postId . '">Edit</a>';
            echo " <span>post id: " . $postId . "</span>";
        }
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
    $comments_file = "./data/posts/" . $postId . "/comments/comments.xml"; // Load comments from file
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
    if (isset($_SESSION["user"])) {
        echo "<h3>Leave a Comment</h3>";
        echo '<form method="post">';
        echo '<input type="hidden" name="post_id" value="' . $postId . '">';
        echo '<input type="text" name="username" value="' .
            $_SESSION["user"]["username"] .
            '" disabled>';
        echo '<input type="text" name="content" placeholder="Write your comment here..." required>';
        echo '<input type="submit" name="submit" value="Submit">';
        echo "</form>";
    } else {
        echo '<p>You must be <a href="./login.php">logged in</a> to leave a comment.</p>';
    }
    echo "</fieldset>";
    if (isset($_POST["submit"])) {
        $postId = $_POST["post_id"];
        $author =
            $_SESSION["user"]["username"] .
            "#" .
            $_SESSION["user"]["discriminator"];
        $content = $_POST["content"];
        $date = date("Y-m-d H:i:s");
        $comments_file = "./data/posts/" . $postId . "/comments/comments.xml";
        function create_comments_file($comments_file)
        {
            $new_comments_data = "<comments></comments>";
            $comments_dir = dirname($comments_file);
            if (!file_exists($comments_dir)) {
                mkdir($comments_dir, 0777, true); // create comments directory if it doesn't exist
            }
            return (file_put_contents($comments_file, $new_comments_data) !== false);
        }
        
        if (!file_exists($comments_file)) {
            if (!create_comments_file($comments_file)) {
                echo "<p>Error creating comments file.</p>";
                exit();
            }
        }
        
        $commentsXml = simplexml_load_file($comments_file);
        $commentXml = $commentsXml->addChild("comment");
        $commentXml->addChild("author", $author);
        $commentXml->addChild("content", $content);
        $commentXml->addChild("date", $date);
        $commentsXml->asXML($comments_file);
        echo "<meta http-equiv='refresh' content='1'>";
        exit();
    }
}
 ?>
