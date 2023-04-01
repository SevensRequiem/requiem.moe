<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the comment is not empty
if (empty($_POST['comment'])) {
    echo 'Please enter a comment.';
    exit();
}

// Read the comments from the comments.json file
$comments_file = 'data/posts/' . $_SESSION['post_id'] . '/comments/comments.json';
$comments = json_decode(file_get_contents($comments_file), true);

// Add the new comment
$new_comment = [
    'user_id' => $_SESSION['user_id'],
    'comment' => $_POST['comment'],
    'timestamp' => time(),
];
$comments[] = $new_comment;

// Write the updated comments back to the comments.json file
file_put_contents($comments_file, json_encode($comments));

echo 'Comment posted.';
?>
