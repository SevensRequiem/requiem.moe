<?php
 require('getanime.php');
?>
<html>
<head>
<link rel="stylesheet" href="./style.css">
</head>
<body>
<div id="anime"><?php
    foreach ($decodedData["data"] as $item) { 
      $title = $item["node"]["title"];
        echo '<li><a>'.$title.'</a></li>';
      }
?>
</div>
</body>
</html>