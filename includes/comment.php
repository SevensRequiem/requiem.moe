<?php

require '../includes/discord.php';
require '../Parsedown.php';

use Parsedown;

// Create a new instance of Parsedown
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $postId = $_POST['post_id'];
    $author = $_SESSION['user']['username'] . '#' . $_SESSION['user']['discriminator'];
    $content = $_POST['content'];
    $date = date('Y-m-d H:i:s');
    $comments_file = '../data/posts/'.$postId.'/comments/comments.xml';

    if (!file_exists($comments_file)) {
        create_comments_file($comments_file);
    }

    $commentsXml = simplexml_load_file($comments_file);

    $commentXml = $commentsXml->addChild('comment');
    $commentXml->addChild('author', $author);
    $commentXml->addChild('content', $content);
    $commentXml->addChild('date', $date);

    $commentsXml->asXML($comments_file);

    echo("<meta http-equiv='refresh' content='1'>");
    exit;
}

function create_comments_file($comments_file) {
    $new_comments_data = '<comments></comments>';
    return file_put_contents($comments_file, $new_comments_data);
}

?>
