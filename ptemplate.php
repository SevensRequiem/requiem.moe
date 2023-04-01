<?php

// Get the post content
$postContent = file_get_contents('post.md');

// Get the comments
$comments = json_decode(file_get_contents('comments/comments.json'), true);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
</head>
<body>
    <h1>My Blog Post</h1>

    <div>
        <?php echo nl2br($postContent); ?>
    </div>

    <h2>Comments</h2>

    <?php foreach ($comments as $comment) : ?>
        <div>
            <p>Author: <?php echo $comment['author']; ?></p>
            <p>Comment: <?php echo $comment['comment']; ?></p>
            <p>Timestamp: <?php echo $comment['timestamp']; ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
