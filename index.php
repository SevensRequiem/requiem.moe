
<?php
require 'functions/counter.php';
//require 'functions/members.php';
$member_count = 123;
?>

<style>
    /* Note: This example only works with Windows Insider Preview Builds 16237+ or the Fall Creators Update. */
/* Custom highlight color */
::selection {
  background-color: rgba(0, 0, 0, 0.8);
  color: #AAF;
}

/* Custom scrollbar color */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background-color: rgba(0, 0, 0, 0.8);
}

::-webkit-scrollbar-thumb {
  background-color: #AAF;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background-color: #EEF;
}
a {
  color: #AAF;
  text-decoration: none;
}
a:hover {
  color: #EEF;
  text-decoration: none;
}
a:active {
  color: #AAF;
  text-decoration: none;
}
a:visited {
  color: #AAF;
  text-decoration: none;
}
body { 
    font-family: segoe-ui_normal,Segoe UI,Segoe,Segoe WP,Helvetica Neue,Helvetica,sans-serif;
    display: grid;
    grid-template-areas: 
    "side section section"
    "side section section"
    "footer footer footer";
    grid-template-rows: 80px 1fr 50px;
    grid-template-columns: 15% 1fr 18%;
    grid-gap: 5px;
  height: 100%;
  margin: 0; 
  padding: 0;
  background-color: black; 
}

header {
    background-color: rgba(0, 0, 0, 0.8);
    grid-area: header;
    text-shadow: 0px 0px 16px #AAF;
    mix-blend-mode: lighten;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 32px #AAF;
  color: #EEF;
}

side {
    background-color: rgba(0, 0, 0, 0.8);
    grid-area: side; 
    text-shadow: 0px 0px 16px #AAF;
    mix-blend-mode: lighten;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 32px #AAF;
  color: #EEF;
  text-align: center;
  overflow-x: hidden; /* horizontal overflow */
  overflow-y: scroll; /* vertical overflow */
}
p {
  text-align: center;
  font-size: 1.5em;
  padding: 0;
  margin: 0;
}
.content {
    background-color: rgba(0, 0, 0, 0.8);
    grid-area: section;  
    text-shadow: 0px 0px 16px #AAF;
    mix-blend-mode: lighten;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 32px #AAF;  
  color: #EEF;
  overflow: scroll;
}
.blogpost {
    background-color: rgba(0, 0, 0, 0.8);
    grid-area: section;  
    text-shadow: 0px 0px 16px #AAF;
    mix-blend-mode: lighten;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 32px #AAF;  
  color: #EEF;
  text-align: center;
} 
aside {
    background-color: rgba(0, 0, 0, 0.8);
    grid-area: aside; 
    text-shadow: 0px 0px 16px #AAF;
    mix-blend-mode: lighten;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 32px #AAF;
  color: #EEF;
}

footer {
    background-color: rgba(0, 0, 0, 0.8);
    grid-area: footer;
    text-shadow: 0px 0px 16px #AAF;
    mix-blend-mode: lighten;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 32px #AAF;
  color: #EEF;
  font-size: small;
  word-wrap: break-word;
  text-align: center;
  overflow: scroll;
}

header, side, section, aside, footer {
    padding: 5px;
}
#son {
  color: rgba(57, 255, 20, 0.8);
  text-shadow: 0px 0px 16px green;
}

#soff {
  color: red;
  text-shadow: 0px 0px 16px red;
}
#ppl {
  color: rgb(0, 145, 255);
  text-shadow: 0px 0px 16px rgb(0, 145, 255);
}
h1 {
  font-size: 2em;
  padding: 0;
  margin: 0;
}
#overlay {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 999;
	background-image:url('assets/img/animated-overlay.gif');
	opacity: 0.1;
  pointer-events: none;
}
#stars {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: -90;
}
#invite {
  text-decoration: none;
}
ol {
    list-style: none;
    text-align: center;
}

.no-anim #static {
          display: none;
      }

      #static {
          position: fixed;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          z-index: 50;
          background-image: url("assets/img/static.gif");
          background-repeat: repeat;
          opacity: 0.03;
          pointer-events: none;
          animation: 0.5s StaticIn linear, 3s 0.5s StaticLoop linear alternate infinite;
      }

      #static.fireaway {
          animation: 3s okFuckOFF linear forwards;
      }

      @keyframes StaticIn {
          0%   { opacity: 1; }
          100% { opacity: 0.03; }
      }

      @keyframes StaticLoop {
          0%   { opacity: 0.03; }
          100% { opacity: 0.1; }
      }
      @keyframes pentagram-flip {
    from {
        fill: #rgb(170, 170, 255);
        stroke-dashoffset: 1832.365;
    }
    to {
        fill: #000;
        stroke-dashoffset: 0;
    }
}

.pentagram-container {
  margin: 0;
  padding: 0;
  position: fixed;
  top: 0;
  left: 0;
  z-index: -1000;
  animation: rotate 60s linear infinite;

}
@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
.pentagram-container svg {
  width: 100%;
  height: 100%;
}

#pentagram .pentagram {
  transform-origin: 50% 50%;
  animation: pentagram-flip 3s ease forwards;
  stroke-dashoffset: 1832.365;
  stroke-dasharray: 1832.365;
}
.pentagram-container::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url(./assets/img/s.gif);
  opacity: 1;
}
        /* Style the marquee */
        .marquee {
    overflow: hidden;
    width: 100%;
    height: 50px;
    padding: 10px;
    box-sizing: border-box;
    pointer-events: none;
    /* Add your other styles here */
}
        .marquee p {
            display: inline-block;
            margin: 0;
            padding-right: 50px;
            font-size: 20px;
            font-weight: bold;
            color: #AAf;
        }
</style>
<title>requiem.moe</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/foobar404/wave.js/dist/bundle.js"></script>
<body>
<link rel="stylesheet" type="text/css" href="./assets/css/starfield.css">
<link rel="stylesheet" href="./assets/css/os-font.css" />
<div class="pentagram-container">
  <svg id='pentagram' 
       xmlns="http://www.w3.org/2000/svg" 
       viewBox="0 0 1000 1000">
    <g transform="translate(359, 420)">
      <path class="pentagram" 
            stroke="#aaf"
            stroke-width="3px"
            fill="#000"
            d="m 86.498597,-332.09939 -147.343765,460.46878 29.84375,24.0625 113.750015,-358.75003 58.437503,175.312528 43.43748,-1.25 -98.124983,-299.843778 z m 35.468753,59.84375 12.34375,40.9375 c 118.54078,15.24525 218.36868,90.26253 268.43748,193.750028 l 44.21875,1.875 C 392.47668,-165.99559 268.90308,-260.07714 121.96735,-272.25564 Z m -69.062503,0.3125 c -149.745215,13.99075 -274.720827,113.1363 -325.937517,248.593778 l 42.65625,0 C -183.29587,-132.96641 -81.581868,-213.53674 40.561097,-230.69314 l 12.34375,-41.25 z m 474.062483,247.187528 -489.062483,8.125 -10.78125,36.09375 386.406233,-4.375 -162.03125,119.375002 10,38.12502 265.46875,-197.343772 z m -557.499998,9.375 -320.000002,5.3125 396.406267,282.187522 33.59375,-26.09375 -307.812517,-223.593772 187.031252,-2.1875 10.78125,-35.625 z m 234.999998,43.90625 -42.96875,1.25 L 282.59233,393.52566 131.65485,283.83816 95.873597,307.74441 356.65483,493.52566 204.46733,28.525638 Z m 263.90625,4.0625 -35.78125,24.0625 c 3.066,18.5752 4.6875,37.58585 4.6875,57.031252 0,99.59017 -41.85025,189.37292 -108.90625,252.81252 l 11.71875,42.65625 c 83.63895,-71.0893 136.71875,-177.11585 136.71875,-295.46877 0,-27.815952 -2.86715,-54.940452 -8.4375,-81.093752 z m -760.3125,13.4375 c -3.86972,21.97085 -5.9375,44.5748 -5.9375,67.656252 0,115.89167 50.91753,219.91462 131.5625,290.93752 l 12.65625,-42.03125 c -64.70806,-63.1933 -104.84375,-151.3518 -104.84375,-248.90627 0,-15.180002 0.92571,-30.177502 2.8125,-44.843752 l -36.25,-22.8125 z m 488.90625,128.750022 -306.71875,226.25 57.812502,-182.5 -31.09375,-21.25 -97.187502,303.59375 387.96875,-288.4375 -10.78125,-37.65625 z m 42.8125,252.8125 c -45.4765,21.8266 -96.49763,34.0625 -150.312483,34.0625 -53.67945,0 -104.5929,-12.18275 -150.000015,-33.90625 l -37.03125,25.3125 c 55.44755,30.65065 119.197515,48.125 187.031265,48.125 67.817433,0 131.592983,-17.4881 187.031233,-48.125 l -36.71875,-25.46875 z"></path>
    </g>
  </svg>
</div>
<canvas id="stars"></canvas>
<div id="static"></div>
<div id="overlay"></div>
<side>
    <!--website version, webbanner, webring, site counter, current ammount of visitors, personal links-->
    <div><h1>requiem.moe</h1></div>
    <div>[<span id="rain">RAIN</span>]</div>
    <div><span>Close the World. Open the nExt</span></div>
    <div><span style="font-size: small;" title="L(106.13hz) R(113.96hz)">resonance: 7.83 hz - 20.3hz</span></div>
    <noscript>
      <div><span style="font-size: 9px;">Please enable JavaScript to use this site.</span></div>
    </noscript>
    <div id="audio-disabled" style="display: none;">
      <span style="font-size: 9px;">Please turn on Audio to enable site functionality.</span>
    </div>
    <div>
    <a class="ajax-link" href="#" onclick="loadHome()">home</a>
    <a class="ajax-link" href="#" onclick="loadBlog()">blog</a>
    <a href="#" onclick="loadProjects()">projects</a>
    <a href="#" onclick="loadVersions()">versions</a>
    </div>
    <?php
// Define an array of random strings
$randomStrings = array("slide:yellow:", "scroll:green:", "shake:red:", "glow1:wave2:");

// Select a random string from the array
$randomString = $randomStrings[array_rand($randomStrings)];

// Read the splash.txt file and display a random quote with the random string appended
$splash = file("splash.txt");
$quote = $splash[array_rand($splash)];
echo "<p>$randomString$quote</p>";
    ?>
    <fieldset>
        <legend>servers</legend>
        <div>
        <div id="luna-status">Luna <span id="son">[ OK ]</span></div>
        <div id="icarus-status">Icarus <span id="soff">[ N/A ]</span></div>
        <div id="aura-status">Aura <span id="soff">[ N/A ]</span></div>
<?php echo '<div id="aurora-status"><a id="invite" href=\'https://discord.gg/zzZzRJy\'>Aurora // オーロラ <span id="ppl">[ ' . $member_count . ' ]</span></a></div>'; ?>
        </div>

    </fieldset>
    <fieldset>
        <legend>stats</legend>
        <div id="website-version">version: [ 1.4 ]</div>
        <?php echo '<div id="site-counter">Site Counter: [<span id="ppl">' . $count . '</span>]</div>'; ?>
        <!--<div>Online Users: 1</div>-->
        <div id="last-edit"><?php $file = 'index.php';
        if (file_exists($file)) {
            echo "Last modified: " . date("F d Y.", filemtime($file));
        }?></div>
        <!--<div id="last-post">Last Post: 2021-08-30</div>-->
        <!--<div>I am <span id="son">Online</span><span style="display: none;" id="soff">Offline</span></div>-->
    </fieldset>
    <fieldset>
    <div>
			<audio id="myAudio" preload="">
				<source src="" type="audio/mpeg">
			</audio>
			<div id="audio-controls">
				<div id="track-info">
					<span id="current-track"></span>
					[<span id="current-duration">00:00</span>] / [<span id="remaining-duration">00:00</span>]
				</div>
				<div id="player-controls">
					<a onclick="playPause()">
						<i class="fa fa-play"></i>
					</a>
					<a onclick="skip(-1)">[&lt;-]</a>
					<a onclick="skip(1)">[-&gt;]</a>
					<a onclick="ptoggle()">[+++]</a>
					<div id="playlist">
						<ol>            <?php $directory = './assets/music/'; $files = array_map('basename', glob($directory . "*.{mp3}", GLOB_BRACE)); foreach ($files as $file) { echo "<li><a href='#' data-src='./assets/music/" . $file . "'>" . $file . "</a></li>"; } ?></ol>
					</div>
					<div id="volume-control" onwheel="changeVolume(event)">
						<span id="volume-level">[============]</span>
					</div>
				</div>
			</div>
		</div>
      </fieldset>
      <fieldset>
        <div id="webbanner"><img src="banner.gif" alt="Web Banner" width="88px" height="31px"></div>
        <p style="font-size: small; color: red;">&lt;a href="https://requiem.moe"&gt;&lt;img src="https://requiem.moe/banner.gif" width="88px" height="31px"&gt;&lt;/a&gt;</p>

    </fieldset>


</side>
<fieldset id="content" class="content">
</fieldset>
<footer>
<a href="https://galladite.net/~galladite/"><img src="./webring/galladite.gif" width="88px" height="31px"></a>
<a href="https://adacayiseverim.neocities.org"><img src="./webring/adacayiseverim.png" width="88px" height="31px"></a>
<a href="https://yukinu.com"><img src="./webring/yukinu.gif" width="88px" height="31px"></a>
<a href="https://heimdall.pm"><img src="./webring/heimdall.gif" width="88px" height="31px"></a>
<a href="https://korosama.neocities.org"><img src="./webring/korosama.gif" width="88px" height="31px"></a>
<a href="https://forum.agoraroad.com/index.php"><img src="./webring/agoraroad.gif" width="88px" height="31px"></a>
<a href="https://ophanim.neocities.org"><img src="./webring/ophanim.gif" width="88px" height="31px"></a>
<a href="https://newdigitalera.neocities.org"><img src="./webring/newdigitalera.png" width="88px" height="31px"></a>
<a href="https://e3-l18-3.xyz/"><img src="https://e3-l18-3.xyz/Graphics/Banner.gif"></a>
  <div id="fps"></div>

  <audio id="ambient">
  <source src="./assets/sfx/end.mp3" type="audio/mpeg">
</audio>
<script>
  var audio = document.getElementById("ambient");
  audio.volume = 0.03;
  audio.play();
</script>
<audio id="bzzz">
  <source src="./assets/sfx/bzzz.mp3" type="audio/mpeg">
</audio>
<script>
  var audio = document.getElementById("bzzz");
  audio.volume = 0.03;
  audio.play();
</script>
<audio id="resonance">
</audio>
<script>
// Configuration
const sampleRate = 44100; // Samples per second
const frequencyLeft = 106.13; // Frequency for left channel
const frequencyRight = 113.96; // Frequency for right channel
const volumeLeft = 0.0013; // Volume for left channel (0 to 1)
const volumeRight = 0.0013; // Volume for right channel (0 to 1)

// Create audio context
const audioContext = new AudioContext({ sampleRate });

// Create audio buffer
const bufferSize = sampleRate; // One second of audio
const audioBuffer = audioContext.createBuffer(2, bufferSize, sampleRate);

// Fill audio buffer with sine wave
const leftChannel = audioBuffer.getChannelData(0);
const rightChannel = audioBuffer.getChannelData(1);
for (let i = 0; i < bufferSize; i++) {
  const t = i / sampleRate;
  leftChannel[i] = volumeLeft * Math.sin(2 * Math.PI * frequencyLeft * t);
  rightChannel[i] = volumeRight * Math.sin(2 * Math.PI * frequencyRight * t);
}

// Create audio source
const audioSource = audioContext.createBufferSource();
audioSource.buffer = audioBuffer;
audioSource.loop = true;

// Connect audio source to output
audioSource.connect(audioContext.destination);

// Start audio
audioSource.start();

// Load audio into specific audio element
const audioElement = document.getElementById("resonance");
audioElement.src = audioContext.createMediaElementSource(audioSource).connect(audioContext.destination);
</script>
<script>
const hoverSound = new Audio('./assets/sfx/scph10000_00022.wav');
hoverSound.volume = 0; // Set the volume to 50%
const clickSound = new Audio('./assets/sfx/scph10000_00023.wav');
clickSound.volume = 0.2; // Set the volume to 80%

const links = document.querySelectorAll('a');

links.forEach(link => {
  link.addEventListener('mouseenter', () => {
    hoverSound.play();
  });

  link.addEventListener('click', (event) => {
    event.preventDefault();
    clickSound.play();
    window.location.href = link.href;
  });
});
</script>
</footer>
</body>
<script>
    // CODWAW Rain Tag
    const span = document.getElementById("rain");
    const colors = ["red", "orange", "yellow", "green", "blue", "indigo", "violet"];
    function animateRainbow() {
        let colorIndex = 0;
        setInterval(() => {
            span.style.color = colors[colorIndex];
            colorIndex = (colorIndex + 1) % colors.length;
        }, 100);
    }
    animateRainbow();
</script>
<script>
const canvas = document.getElementById("stars");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
const ctx = canvas.getContext("2d");
let stars = [];
const minNumStars = 1300;
const maxNumStars = 2300;
const numStars = Math.floor(Math.random() * (maxNumStars - minNumStars + 1)) + minNumStars;
const minMaxStarSize = 0.8;
const maxMaxStarSize = 2.3;
const maxStarSize = Math.random() * (maxMaxStarSize - minMaxStarSize) + minMaxStarSize;
const minMaxStarSpeed = 1.5;
const maxMaxStarSpeed = 2;
const maxStarSpeed = Math.random() * (maxMaxStarSpeed - minMaxStarSpeed) + minMaxStarSpeed;
const minMaxStarOpacity = 0.8;
const maxMaxStarOpacity = 1;
const maxStarOpacity = Math.random() * (maxMaxStarOpacity - minMaxStarOpacity) + minMaxStarOpacity;
class Star {
  constructor() {
    this.x = Math.random() * canvas.width;
    this.y = Math.random() * canvas.height;
    this.size = Math.random() * maxStarSize;
    this.speed = Math.random() * maxStarSpeed;
    this.opacity = Math.random() * maxStarOpacity;
    this.color = `rgb(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)})`;
  }

  update() {
    this.x -= this.speed;
    if (this.x < -this.size) {
      this.x = canvas.width + this.size;
      this.y = Math.random() * canvas.height;
      this.opacity = Math.random() * maxStarOpacity;
    }
    if (Math.random() < 0.05) {
      this.opacity = Math.random() * maxStarOpacity;
    }
  }

  draw() {
    const gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.size);
    gradient.addColorStop(0, this.color);
    gradient.addColorStop(0.5, `rgba(255, 255, 255, ${this.opacity})`);
    gradient.addColorStop(1, `rgba(255, 255, 255, 0)`);
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
    ctx.fillStyle = gradient;
    ctx.fill();
  }
}

for (let i = 0; i < numStars; i++) {
  stars.push(new Star());
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  for (let i = 0; i < stars.length; i++) {
    stars[i].update();
    stars[i].draw();
  }

  requestAnimationFrame(animate);
}

animate();
</script>
<script>
  function loadBlog() {

    // Clear the content container
    document.getElementById("content").innerHTML = "";

    // Make an AJAX request to fetch the blog content
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {

        // Update the content container with the fetched content
        document.getElementById("content").innerHTML = xhr.responseText;

        // Set the legend element with the title of the loaded file
        var title = document.createElement("legend");
        title.innerHTML = ">cat blog.txt";
        document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
      }
    };
    xhr.open("GET", "home.html", true);
    xhr.send();
  }
function loadHome() {

    // Clear the content container
    document.getElementById("content").innerHTML = "";

    // Make an AJAX request to fetch the blog content
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {

        // Update the content container with the fetched content
        document.getElementById("content").innerHTML = xhr.responseText;

        // Set the legend element with the title of the loaded file
        var title = document.createElement("legend");
        title.innerHTML = ">cat home.txt";
        document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
      }
    };
    xhr.open("GET", "home.html", true);
    xhr.send();
  }

  window.addEventListener('load', loadHome);
  function loadVersions() {

// Clear the content container
document.getElementById("content").innerHTML = "";

// Make an AJAX request to fetch the blog content
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
  if (xhr.readyState === 4) {

    // Update the content container with the fetched content
    document.getElementById("content").innerHTML = xhr.responseText;

    // Set the legend element with the title of the loaded file
    var title = document.createElement("legend");
    title.innerHTML = ">curl oldversions.php";
    document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
  }
};
xhr.open("GET", "home.html", true);
xhr.send();
}
function loadProjects() {

// Clear the content container
document.getElementById("content").innerHTML = "";

// Make an AJAX request to fetch the blog content
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
  if (xhr.readyState === 4) {

    // Update the content container with the fetched content
    document.getElementById("content").innerHTML = xhr.responseText;

    // Set the legend element with the title of the loaded file
    var title = document.createElement("legend");
    title.innerHTML = ">ls /projects/";
    document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
  }
};
xhr.open("GET", "home.html", true);
xhr.send();
}
</script>
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
<script>
// Get the audio element
const audio = document.createElement('audio');

// Check if audio is disabled
if (audio.disabled || audio.muted || audio.readyState === 0) {
  // Show the audio-disabled element
  const audioDisabled = document.getElementById('audio-disabled');
  audioDisabled.style.display = 'block';
}
</script>
<script type="text/javascript" src="./assets/js/os-font.js"></script>
        <script type="text/javascript">
            OsFont.compile();
        </script>
<!--XaTuring Lives-->