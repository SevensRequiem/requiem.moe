var canvas = document.getElementById("staticeffect"),
    context = canvas.getContext("2d");

function resizeCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}

function makeStatic() {
  resizeCanvas();

  var staticeffect = context.createImageData(canvas.width, canvas.height);

  window.setInterval(function() {
    for (var i = 0; i < staticeffect.data.length; i=i+4) {
      staticeffect.data[i+3] = getRandomInt(0, 255);
    }
    context.putImageData(staticeffect, 0, 0);
  }, 50);
}

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

makeStatic();

window.addEventListener("resize", function() {
  makeStatic();
});
