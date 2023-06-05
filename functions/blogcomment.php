<?php
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
    ?>