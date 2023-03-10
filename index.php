<!DOCTYPE html>
<html lang="en" >
<head>
	<script src="https://cdn.jsdelivr.net/gh/foobar404/wave.js/dist/bundle.js"></script>
	<script src="./assets/js/audio.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<body class="background">
	
	 	<canvas id="staticeffect"></canvas>
		<nav id="menu">
			<ul>
			<li><a href ="#" onclick="load_home()"><span style="font-size: 35px;">requiem.moe</span></a></li>
			<li><span>></span></li>
			<li><a href ="#" onclick="load_blog()"><btn>blog</btn></a></li>
			<li><span>></span></li>
				<li><a href ="#" onclick="load_server()"><btn>server stuff</btn></a></li>
				<li><span>></span></li>
						<li><a href ="#" onclick="load_versions()"><btn>version browser</btn></a></li>
						<li style="float:right;"><a>[usr:0]</a></li>
								<li style="float:right;"><a><btn>steam</btn></a></li>
								<li style="float:right;"><span>-</span></li>
								<li style="float:right;"><a><btn>Requiem#4613</btn></a></li>
								<li style="float:right;"><span>-</span></li>
								<li style="float:right;"><a><btn>youtube</btn></a></li>
								<li style="float:right;"><a>[]</a></li>
		</ul>
</nav>
<div>version 1.4!!</div>
<div style="display: flex;"> 
			<audio preload autoplay></audio>
<div id="playlist" style="display: none;"><ol>
	<?php
      //list mp3 files
      $directory = './assets/music/';
      $files = array_map('basename', glob($directory . "*.{mp3}", GLOB_BRACE));
      foreach($files as $file) {
        echo "<li><a href='#' data-src='./assets/music/$file'>$file</a></li>";
      } {
      }
      ?></ol>
</div>

			</div>
		<div id="content">
		</div>
		<div id="footer">
			footer
		</div>
		</div>
</body>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'></script>
  <script src="./script.js"></script>

</body>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'></script><script  src="./script.js"></script>

</body>
</html>
<script>
	window.onload = function() {
  load_home();
};
	function load_home() {
     document.getElementById("content").innerHTML='<object type="text/html" data="home.php" ></object>';
}
function load_blog() {
     document.getElementById("content").innerHTML='<object type="text/html" data="blog.php" ></object>';
}
function load_versions() {
     document.getElementById("content").innerHTML='<object type="text/html" data="versions.php" ></object>';
}
function load_server() {
     document.getElementById("content").innerHTML='<object type="text/html" data="server.php" ></object>';
}
</script>
<script>
      $(function() { 
        // Setup the player to autoplay the next track
        var a = audiojs.createAll({
          trackEnded: function() {
            var next = $('ol li.playing').next();
            if (!next.length) next = $('ol li').first();
            next.addClass('playing').siblings().removeClass('playing');
            audio.load($('a', next).attr('data-src'));
            audio.play();
          }
        });
        
        // Load in the first track
        var audio = a[0];
            first = $('ol a').attr('data-src');
        $('ol li').first().addClass('playing');
        audio.load(first);

        // Load in a track on click
        $('ol li').click(function(e) {
          e.preventDefault();
          $(this).addClass('playing').siblings().removeClass('playing');
          audio.load($('a', this).attr('data-src'));
          audio.play();
        });
      });
    </script>
<script>
function ptoggle () {
    $("#playlist").toggle();
} 
</script>