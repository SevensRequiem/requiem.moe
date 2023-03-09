//Add the staticeffect
var hairuClick = false;
function makeStatic()
{
	var canvas = document.getElementById("staticeffect"),
	    context = canvas.getContext("2d");

	canvas.width = window.innerWidth;
	canvas.height = window.innerHeight;

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
resizeModal();