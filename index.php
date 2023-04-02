<!DOCTYPE html>
<?php
require __DIR__ . "/includes/functions.php";
require __DIR__ . "/includes/discord.php";
require __DIR__ . "/config.php";
?>
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
     <header id="header">
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
                <?php
			$auth_url = url($client_id, $redirect_url, $scopes);
			if (isset($_SESSION['user'])) {
				echo '<a href="includes/logout.php"><button class="log-in">LOGOUT</button></a>';
			} else {
				echo "<a href='$auth_url'><button class='log-in'>LOGIN</button></a>";
			}
			?>
		</ul>
</nav>
<div id="uptime"></div>
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
    </header>
      <hr>
		<div id="content">
		</div>
		</div>

<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'></script><script  src="./script.js"></script>
<script>
$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            url: './includes/uptime.php',
            success: function(data) {
                $('#uptime').text('Server Uptime: ' + data);
            }
        });
    }, 5000); // Refresh every 5 seconds
});

  </script>
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