// made by https://requiem.moe/
var requiemMoeAudio = document.getElementById("requiemMoeAudio");
requiemMoeAudio.volume = 0.2;
var requiemMoePlaylist = document.getElementById("requiemMoePlaylist").getElementsByTagName("a");
var requiemMoeControls = document.getElementById("requiemMoeControls").getElementsByTagName("a");
var requiemMoeVolumeControl = document.getElementById("requiemMoeVolumeControl");
var requiemMoeVolumeLevel = document.getElementById("requiemMoeVolumeLevel");
var requiemMoeCurrentTrack = 0;
function requiemMoePlayAudio() {
  requiemMoeAudio.play();
}
function requiemMoePlayPause() {
  if (requiemMoeAudio.paused) {
    requiemMoeAudio.play();
  } else {
    requiemMoeAudio.pause();
  }
  requiemMoeUpdatePlayPauseButton();
}
function requiemMoeUpdatePlayPauseButton() {
  if (requiemMoeAudio.paused) {
    requiemMoeControls[0].innerHTML = '[<span>&gt;</span>/<span class="glow">#</span>]';
    requiemMoeControls[0].classList.remove("pulse");
    requiemMoeControls[0].classList.add("glow");
  } else {
    requiemMoeControls[0].innerHTML = '[<span class="pulse">&gt;</span>/<span>#</span>]';
    requiemMoeControls[0].classList.remove("glow");
    requiemMoeControls[0].classList.add("pulse");
  }
}
function requiemMoeSkip(direction) {
  requiemMoeCurrentTrack += direction;
  if (requiemMoeCurrentTrack < 0) {
    requiemMoeCurrentTrack = requiemMoePlaylist.length - 1;
  } else if (requiemMoeCurrentTrack > requiemMoePlaylist.length - 1) {
    requiemMoeCurrentTrack = 0;
  }
  requiemMoeAudio.src = requiemMoePlaylist[requiemMoeCurrentTrack].getAttribute("data-src");
  requiemMoePlayAudio();
}
function requiemMoeChangeVolume(event) {
  event.preventDefault();
  var direction = Math.sign(event.deltaY);
  requiemMoeAudio.volume -= direction * 0.1;
  if (requiemMoeAudio.volume < 0) {
    requiemMoeAudio.volume = 0;
  } else if (requiemMoeAudio.volume > 1) {
    requiemMoeAudio.volume = 1;
  }
  requiemMoeUpdateVolumeLevel();
}
function requiemMoeUpdateVolumeLevel() {
  var level = Math.round(requiemMoeAudio.volume * 10);
  var levelString = "[";
  for (var i = 0; i < 10; i++) {
    if (i < level) {
      levelString += "=";
    } else {
      levelString += "-";
    }
  }
  levelString += "]";
  requiemMoeVolumeLevel.innerHTML = levelString;
}
function requiemMoeUpdateTrackInfo() {
  var requiemMoeCurrentTrackElement = document.getElementById("requiemMoeCurrentTrack");
  var requiemMoeCurrentDurationElement = document.getElementById("requiemMoeCurrentDuration");
  var requiemMoeRemainingDurationElement = document.getElementById("requiemMoeRemainingDuration");
  requiemMoeCurrentTrackElement.innerHTML = requiemMoePlaylist[requiemMoeCurrentTrack].innerHTML;
  requiemMoeCurrentDurationElement.innerHTML = requiemMoeFormatTime(requiemMoeAudio.currentTime);
  requiemMoeRemainingDurationElement.innerHTML = requiemMoeFormatTime(requiemMoeAudio.duration - requiemMoeAudio.currentTime);
}
function requiemMoeFormatTime(seconds) {
  var minutes = Math.floor(seconds / 60);
  var remainingSeconds = Math.floor(seconds % 60);
  if (remainingSeconds < 10) {
    remainingSeconds = "0" + remainingSeconds;
  }
  return minutes + ":" + remainingSeconds;
}
requiemMoeAudio.addEventListener("timeupdate", function () {
  requiemMoeUpdateTrackInfo();
});
function requiemMoeTogglePlaylist() {
  var requiemMoePlaylist = document.getElementById("requiemMoePlaylist");
  requiemMoePlaylist.classList.toggle("show");
}
requiemMoeAudio.src = requiemMoePlaylist[requiemMoeCurrentTrack].getAttribute("data-src");
requiemMoeUpdatePlayPauseButton();
requiemMoeUpdateVolumeLevel();
requiemMoeAudio.addEventListener("ended", function () {
  requiemMoeSkip(1);
});
for (var i = 0; i < requiemMoePlaylist.length; i++) {
  requiemMoePlaylist[i].addEventListener("click", function (event) {
    event.preventDefault();
    requiemMoeAudio.src = this.getAttribute("data-src");
    requiemMoeCurrentTrack = Array.prototype.indexOf.call(requiemMoePlaylist, this);
    requiemMoePlayAudio();
    requiemMoeUpdatePlayPauseButton();
  });
}
requiemMoeVolumeControl.addEventListener("input", function () {
  requiemMoeAudio.volume = this.value / 100;
  requiemMoeUpdateVolumeLevel();
});
// made by https://requiem.moe/