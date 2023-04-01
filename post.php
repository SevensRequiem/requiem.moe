<?php
//post
require './includes/discord.php';
if(!empty($_POST['content']) && !empty($_POST['title']) && has_admin == true){
    $uuid = bin2hex(openssl_random_pseudo_bytes(16));
    for($cnt = 8; $cnt <=23; $cnt+=5)
       $uuid = substr($uuid, 0, $cnt) . "-" . substr($uuid, $cnt);
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image'];
    $quote = $_POST['quote'];
    $hex = $_POST['hex'];
    $put = $content;
    $date = date("m-d-Y @ H:i:s");
    $base64 = base64_encode($title.$date);
//make post folder
    mkdir("./data/posts/".$date.$title."", 0700);

//make post file
    $myFile = "./data/posts/".$date.$title."/post.md";
    $fh = fopen($myFile, 'w');
    fwrite($fh, $put);
    echo "" . $title . "_" . $content . "";
    fclose($fh);
//make other files
mkdir("./data/posts/".$date.$title."/comments/", 0700);
function copy_template_to_post_dir($post_dir) {
    $template_path = './ptemplate.php';
    $index_path = $post_dir . '/index.php';
    if (!copy($template_path, $index_path)) {
        echo "Failed to copy template file...";
    } else {
        echo "Template file copied to post directory as index.php!";
    }
}
// Create post.json
$postData = [
    'uuid' => $uuid,
    'title' => $title,
    'date' => $date,
    'image' => $image,
    'quote' => $quote,
    'hex' => $hex
];
$postJson = json_encode($postData);
$postJsonFile = "./data/posts/".$date.$title."/post.json";
file_put_contents($postJsonFile, $postJson);

// Add post data to posts.json
$postsJsonFile = "./data/posts.json";
$postsJson = file_get_contents($postsJsonFile);
$postsData = json_decode($postsJson, true);
$postsData[] = [
    'uuid' => $uuid,
    'title' => $title,
    'date' => $date
];
$postsJson = json_encode($postsData);
file_put_contents($postsJsonFile, $postsJson);

// Respond with success message
echo "Post created successfully!";

//make comment json
    $myFile = "./data/posts/".$date.$title."/comments/comments.json";
    $fh = fopen($myFile, 'w');
    fwrite($fh, '{}');
    fclose($fh);
//file upload and change file name to uuid
$currentDirectory = getcwd();
$uploadDirectory = "./static/images/";
$errors = []; // Store all foreseen/unforeseen errors here
$fileExtensions = ['jpeg','jpg','png','gif','webm']; // Get all the file extensions
$fileName = $_FILES['fileToUpload']['name'];
$fileSize = $_FILES['fileToUpload']['size'];
$fileTmpName  = $_FILES['fileToUpload']['tmp_name'];
$fileType = $_FILES['fileToUpload']['type'];
$fileExtension = strtolower(end(explode('.',$fileName)));
$uploadPath = $currentDirectory . $uploadDirectory . basename($uuid.'.'.$fileExtension);
if (isset($_POST['save'])) {
    if (! in_array($fileExtension,$fileExtensions)) {
        $errors[] = "This file extension is not allowed.";
    }
    if ($fileSize > 2000000) {
        $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
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
}
}

?>