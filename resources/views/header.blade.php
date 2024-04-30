<header>
  <nav>
  <span>[ <a href="#" onclick="loadPage('home')" alt="Home" class="ps2-select">home</a> ]</span>
<span>[ <a href="#" onclick="loadPage('blog')" alt="Blog" class="ps2-select">blog</a> ]</span>
<span>[ <a href="#" onclick="loadPage('projects')" alt="Projects" class="ps2-select">projects</a> ]</span>
<span>[ <a href="#" onclick="loadPage('tools')" alt="Tools" class="ps2-select">tools</a> ]</span>
<span>[ <a href="#" onclick="loadPage('gallery')" alt="Gallery" class="ps2-select">gallery</a> ]</span>
<span>[ <a href="#" onclick="loadPage('about')" alt="About" class="ps2-select">about</a> ]</span>
<span>[ <a href="#" onclick="loadDonate()" alt="Donate" class="ps2-select">donate</a> ]</span>
<span>[ <a href="#" onclick="loadForm()" alt="Contact" class="ps2-select">contact</a> ]</span>
  </nav>
  <canvas id="visualizer" style="width: 100%; height: 100%; margin-bottom: 20px; max-width: 200px;"></canvas>
  <div class="musicplayer">
        <audio id="myAudio" preload="" autoplay>
          <source src="" type="audio/mpeg">
        </audio>
        <span id="audio-controls">
          <span id="track-info"><span id="current-track"></span><span>[<span id="current-duration">00:00</span>] / [<span id="total-duration">00:00</span>]</span></span>
          <span id="player-controls">
            <a href="#" onclick="playPause()" class="ps2-select-short">
              <i class="fa fa-play"></i>
            </a>
            <a href="#" onclick="skip(-1)" class="ps2-select-short">[&lt;-]</a>
            <a href="#" onclick="skip(1)" class="ps2-select-short">[-&gt;]</a>
            <a href="#" onclick="ptoggle()" class="ps2-select">[trk]</a>
            <span id="volume-control" onwheel="changeVolume(event)">
              <span id="volume-level">[============]</span>
</span>
          </span>
          <div style="display: none;">
            <ol>


            </ol>
          </div>
        </div>
      </div>
</header>
<div id="playlist" style="display: none;">
  <ol>
    <span>tracklist</span>
    <hr>
    <?php

use Illuminate\Support\Facades\File; // Import the File facade

$directory = resource_path('music');
$files = File::glob($directory . '/*.mp3');

foreach ($files as $file) {
    $getID3 = new getID3;
    $fileinfo = $getID3->analyze($file);
    $duration = $fileinfo['playtime_seconds'];
    $tracktime = gmdate('i:s', $duration);
    $fileName = basename($file);
    $assetPath = asset('music/' . $fileName); // Generate asset path

    echo "<li><a href='#' data-src='./assets/music/" . $fileName . "'class='ps2-select'>" . $fileName . "</a><span>[<span id='pltime'>" . $tracktime . "</span>]</span></li>";
}
?>

  </ol>
</div>
<script>
// made by https://requiem.moe/
var audio = document.getElementById("myAudio");
audio.volume = 0.2;
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
    controls[0].innerHTML = '[<span>&gt;</span>/<span class="glowlightred">#</span>]';
    controls[0].classList.remove("pulse");
    controls[0].classList.add("glow");
  } else {
    controls[0].innerHTML = '[<span class="glowlightpurple">&gt;</span>/<span>#</span>]';
    controls[0].classList.remove("glow");
    controls[0].classList.add("pulse");
  }

  if (audio.autoplay && !audio.paused) {
    controls[0].innerHTML = '[<span class="glowlightpurple">&gt;</span>/<span>#</span>]';
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
  var totalDurationElement = document.getElementById("total-duration");
  currentTrackElement.innerHTML = playlist[currentTrack].innerHTML;
  currentDurationElement.innerHTML = formatTime(audio.currentTime);
  totalDurationElement.innerHTML = formatTime(audio.duration);
}
function formatTime(seconds) {
  var minutes = Math.floor(seconds / 60);
  var remainingSeconds = Math.floor(seconds % 60);
  if (remainingSeconds < 10) {
    remainingSeconds = "0" + remainingSeconds;
  }
  return minutes + ":" + remainingSeconds;
}
audio.addEventListener("timeupdate", function () {
  updateTrackInfo();
});
function ptoggle() {
  var playlist = document.getElementById("playlist");
  if (playlist.style.display === "block") {
    playlist.style.display = "none";
  } else {
    playlist.style.display = "block";
  }
}
audio.src = playlist[currentTrack].getAttribute("data-src");
updatePlayPauseButton();
updateVolumeLevel();
audio.addEventListener("ended", function () {
  skip(1);
});
for (var i = 0; i < playlist.length; i++) {
  playlist[i].addEventListener("click", function (event) {
    event.preventDefault();
    audio.src = this.getAttribute("data-src");
    currentTrack = Array.prototype.indexOf.call(playlist, this);
    playAudio();
    updatePlayPauseButton();
  });
}
volumeControl.addEventListener("input", function () {
  audio.volume = this.value / 100;
  updateVolumeLevel();
});
// made by https://requiem.moe/
</script>




<script>
var viscanvas = document.getElementById("visualizer");
var visctx = viscanvas.getContext("2d");
var analyser = null;
var bufferLength = null;
var dataArray = null;
var barColors = ["rgb(41, 104, 158)", "rgb(36, 110, 173)", "rgb(21, 113, 191)"];
var numBars = 3;

function setupVisualizer() {
  var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  analyser = audioCtx.createAnalyser();
  var source = audioCtx.createMediaElementSource(audio);
  source.connect(analyser);
  analyser.connect(audioCtx.destination);
  analyser.fftSize = 256;
  bufferLength = analyser.frequencyBinCount;
  dataArray = new Uint8Array(bufferLength);
}

function drawVisualizer() {
  requestAnimationFrame(drawVisualizer);
  analyser.getByteFrequencyData(dataArray);
  visctx.clearRect(0, 0, viscanvas.width, viscanvas.height);
  var barWidth = viscanvas.width / bufferLength;
  var barHeight;
  var x = 0;
  for (var i = 0; i < bufferLength; i++) {
    barHeight = dataArray[i] / 2;
    visctx.fillStyle = barColors[i % numBars];
    visctx.fillRect(x, viscanvas.height - barHeight, barWidth, barHeight);
    x += barWidth + 1;
  }

  visctx.fillStyle = "rgba(255, 255, 255, 0.5)";
  for (var i = 0; i < bufferLength; i++) {
    var sparkleHeight = Math.random() * 10;
    visctx.fillRect(x - barWidth, viscanvas.height - barHeight - sparkleHeight, barWidth, sparkleHeight);
  }
}

setupVisualizer();
drawVisualizer();
</script>
