<?php
$dir = "./data/posts/"; // replace with the name of your subdirectory
$dirs = array_filter(glob($dir . '/*'), 'is_dir'); // get all directories in subdirectory
foreach ($dirs as $d) {
    
  $posts = glob($d . '/*.md'); // get all post.md files in directory
  foreach ($posts as $p) {
    $json = $d . '/post.json'; // get path to post.json file
    if (file_exists($json)) { // check if post.json file exists
      $contents = file_get_contents($json); // get contents of post.json file
      $data = json_decode($contents, true); // decode contents of post.json file into an array
      $uuid = $data['uuid'] ?? '';
      $title = $data['title'] ?? '';
      $date = $data['date'] ?? '';
      $author = $data['author'] ?? '';
      $description = $data['description'] ?? '';
      $tags = $data['tags'] ?? '';
      $image = $data['image'] ?? '';
    }
    $blogpost = file_get_contents($p); // get contents of post.md file
    echo "<fieldset class='post'>"; // start post div
    echo "<p>".$blogpost."</p>"; // display post content
    echo "</p>"; // end tags p
    echo "</fieldset>"; // end post div

 }

}
 ?>