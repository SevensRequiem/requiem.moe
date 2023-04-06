<!DOCTYPE html>
<?php
require __DIR__ . "/includes/functions.php";
require __DIR__ . "/includes/discord.php";
require __DIR__ . "/config.php";
require_once 'vendor/autoload.php'; // Include the Composer autoloader
require __DIR__ . "/ipbans.php";
echo '<span style="display: none;">'.$welcome .'</span>';
?>


<html lang="en" >
<head>
	<script src="https://cdn.jsdelivr.net/gh/foobar404/wave.js/dist/bundle.js"></script>
	<script src="./assets/js/audio.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./style.css">
<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

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
    <marquee behavior="scroll" direction="left" scrollamount="6">
		<span style="color: red; font-weight: bold;">Latest News: </span>yeah shits in dev be patient
	</marquee>
	</nav>
  <div style="display: flex;"> 
  <audio id="myAudio" preload>
    <source src="" type="audio/mpeg">
  </audio>

<div id="audio-controls">
  <div id="track-info">
    <span id="current-track"></span>
    [<span id="current-duration">00:00</span>] / [<span id="remaining-duration">00:00</span>]
  </div>
  <div id="player-controls">
<a onclick="playPause()"><i class="fa fa-play"></i></a>
    <a onclick="skip(-1)">[<-]</a>
    <a onclick="skip(1)">[->]</a> 
    <a onclick="ptoggle()">[+++]</a>
    <div id="playlist">
      <ol>
        <?php
          //list mp3 files
          $directory = './assets/music/';
          $files = array_map('basename', glob($directory . "*.{mp3}", GLOB_BRACE));
          foreach($files as $file) {
            echo "<li><a href='#' data-src='./assets/music/".$file."'>".$file."</a></li>";
          }
        ?>
      </ol>
    </div>
  </div>
  <div id="volume-control" onwheel="changeVolume(event)">
    <span id="volume-level">[============]</span>
  </div>
</div> 
  </div>

  <script>
    var audio = document.getElementById("myAudio");
    var playlist = document.getElementById("playlist").getElementsByTagName("a");
    var controls = document.getElementById("audio-controls").getElementsByTagName("a");
    var volumeControl = document.getElementById("volume-control");
    var volumeLevel = document.getElementById("volume-level");
    var currentTrack = 0;

    function playAudio() {
      audio.play();
    }

    function playPause() {
  if (audio.paused) {
    audio.play();
  } else {
    audio.pause();
  }
  updatePlayPauseButton();
}


function updatePlayPauseButton() {
  if (audio.paused) {
    controls[0].innerHTML = '[<span>&gt;</span>/<span class="glow">#</span>]';
    controls[0].classList.remove("pulse");
    controls[0].classList.add("glow");
  } else {
    controls[0].innerHTML = '[<span class="pulse">&gt;</span>/<span>#</span>]';
    controls[0].classList.remove("glow");
    controls[0].classList.add("pulse");
  }
}




    function skip(direction) {
      currentTrack += direction;
      if (currentTrack < 0) {
        currentTrack = playlist.length - 1;
      } else if (currentTrack > playlist.length - 1) {
        currentTrack = 0;
      }
      audio.src = playlist[currentTrack].getAttribute("data-src");
      playAudio();
    }

    function changeVolume(event) {
      event.preventDefault();
      var direction = Math.sign(event.deltaY);
      audio.volume -= direction * 0.1;
      if (audio.volume < 0) {
        audio.volume = 0;
      } else if (audio.volume > 1) {
        audio.volume = 1;
      }
      updateVolumeLevel();
    }

    function updateVolumeLevel() {
      var level = Math.round(audio.volume * 10);
      var levelString = "[";
      for (var i = 0; i < 10; i++) {
        if (i < level) {
          levelString += "=";
        } else {
          levelString += "-";
        }
      }
      levelString += "]";
      volumeLevel.innerHTML = levelString;
    }
    function updateTrackInfo() {
  var currentTrackElement = document.getElementById("current-track");
  var currentDurationElement = document.getElementById("current-duration");
  var remainingDurationElement = document.getElementById("remaining-duration");

  currentTrackElement.innerHTML = playlist[currentTrack].innerHTML;
  currentDurationElement.innerHTML = formatTime(audio.currentTime);
  remainingDurationElement.innerHTML = formatTime(audio.duration - audio.currentTime);
}

function formatTime(seconds) {
  var minutes = Math.floor(seconds / 60);
  var remainingSeconds = Math.floor(seconds % 60);
  if (remainingSeconds < 10) {
    remainingSeconds = "0" + remainingSeconds;
  }
  return minutes + ":" + remainingSeconds;
}

audio.addEventListener("timeupdate", function() {
  updateTrackInfo();
});
function ptoggle() {
  var playlist = document.getElementById("playlist");
  playlist.classList.toggle("show");
}



// Initialize
audio.src = playlist[currentTrack].getAttribute("data-src");
updatePlayPauseButton();
updateVolumeLevel();

// Event listeners
audio.addEventListener("ended", function() {
  skip(1);
});

for (var i = 0; i < playlist.length; i++) {
  playlist[i].addEventListener("click", function(event) {
    event.preventDefault();
    audio.src = this.getAttribute("data-src");
    currentTrack = Array.prototype.indexOf.call(playlist, this);
    playAudio();
    updatePlayPauseButton();
  });
}
</script>
    <div id="uptime" style="float:right;"></div>
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
    var page = window.location.pathname.split("/").pop();
    if (page === "blog") {
      load_blog();
    } else if (page === "versions") {
      load_versions();
    } else if (page === "server") {
      load_server();
    } else {
      load_home();
    }
  };

  function load_home() {
    document.getElementById("content").innerHTML='<object type="text/html" data="home.php"></object>';
  }

  function load_blog() {
    document.getElementById("content").innerHTML='<object type="text/html" data="blog.php"></object>';
  }

  function load_versions() {
    document.getElementById("content").innerHTML='<object type="text/html" data="versions.php"></object>';
  }

  function load_server() {
    document.getElementById("content").innerHTML='<object type="text/html" data="server.php"></object>';
  }
</script>