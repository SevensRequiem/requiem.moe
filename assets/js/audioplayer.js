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
audio.addEventListener("timeupdate", function () {
  updateTrackInfo();
});
function ptoggle() {
  var playlist = document.getElementById("playlist");
  playlist.classList.toggle("show");
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