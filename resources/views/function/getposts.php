<?php
$postsDir = './data/posts';
$posts = array_filter(glob($postsDir . '/*'), 'is_dir');

usort($posts, function ($a, $b) {
    $aPostMdTime = filemtime($a . "/post.md");
    $bPostMdTime = filemtime($b . "/post.md");
    return $bPostMdTime - $aPostMdTime;
});

foreach ($posts as $post) {
    $postJson = json_decode(file_get_contents($post . '/post.json'));
    $postDate = $postJson->date;
    $postAuthor = isset($postJson->author) ? $postJson->author : '';
    $postImage = isset($postJson->image) ? str_replace('./static/images/', '', $postJson->image) : '';
    $postTitle = $postJson->title;
    $postHex = isset($postJson->hex) ? $postJson->hex : '';
    $postQuote = isset($postJson->quote) ? $postJson->quote : '';
    $postContent = file_get_contents($post . '/post.md');
}
?>