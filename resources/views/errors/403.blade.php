<html>
	<head>
		<title>Error: 403</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<body oncontextmenu="return false" onselectstart="return false" onselect="return false" oncopy="return false" ondragstart="return false" ondrag="return false">
    </head>
    <body>
            <div id="clockDisplay" class="clockStyle"></div>
            <script>
            function renderTime() {
            var currentTime = new Date();
            var diem = "am";
            var h = currentTime.getHours();
            var m = currentTime.getMinutes();
            var s = currentTime.getSeconds();
            setTimeout('renderTime()',1000);
            if (h == 0) {
              h = 12;
            } else if (h > 12) { 
              h = h - 12;
              diem="pm";
            }
            if (h < 10) {
              h = "0" + h;
            }
            if (m < 10) {
              m = "0" + m;
            }
            if (s < 10) {
              s = "0" + s;
            }
            var myClock = document.getElementById('clockDisplay');
            myClock.textContent = h + ":" + m + ":" + s + " " + diem;
            myClock.innerText = h + ":" + m + ":" + s + " " + diem;
            }
            renderTime();
            </script>		
    <!--CSS-->
<style>
@font-face {
	font-family: pixel;
	src: url(assets/pixel.otf);
  }
	  body {
		  background-color: black;
		  background-position: center;
		  background-attachment: fixed;
		  background-size: contain;
		  background-repeat: no-repeat;
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
          background-image:url("{{ env('APP_URL') }}/static/images/static.gif");
		  background-repeat: repeat;
		  opacity: 0.03;
		  pointer-events: none;
		  animation: 0.5s StaticIn linear, 3s 0.5s StaticLoop linear alternate infinite;
	  }

	  @keyframes StaticIn {
		  0%   { opacity: 1; }
		  100% { opacity: 0.03; }
	  }

	  @keyframes StaticLoop {
		  0%   { opacity: 0.03; }
		  100% { opacity: 0.1; }
	  }
a:link {
	color: #EEF;
	text-shadow: 0px 0px 16px #AAF;
	opacity: 0.8;
	mix-blend-mode: lighten;
	max-width: calc(100%  - 2em);
	position: relative;
	z-index: 1000;
  }
  
  /* visited link */
  a:visited {
	color: #EEF;
	text-shadow: 0px 0px 16px #AAF;
	opacity: 0.8;
	mix-blend-mode: lighten;
	max-width: calc(100%  - 2em);
	position: relative;
	z-index: 1000;
  }
  
  /* mouse over link */
  a:hover {
	color: rgb(217, 217, 245);
	text-shadow: 0px 0px 16px #AAF;
	opacity: 0.8;
	mix-blend-mode: lighten;
	max-width: calc(100%  - 2em);
	position: relative;
	z-index: 1000;
  }
  
  /* selected link */
  a:active {
	color: #EEF;
	text-shadow: 0px 0px 16px #AAF;
	opacity: 0.8;
	mix-blend-mode: lighten;
	max-width: calc(100%  - 2em);
	position: relative;
	z-index: 1000;
  }

.main {
    font-family: pixel;
	background-color: rgba(0, 0, 0, 0.8);
	display: inline-block;
	position: absolute;
    border-radius: 13em;
	top: 50%;
	left: 50%;
    color: #EEF;
	transform: translate(-50%, -50%);
	text-shadow: 0px 0px 16px #AAF;
	opacity: 0.8;
	mix-blend-mode: lighten;
	border: 1px solid #FFF;
	padding: 0.5em;
	width: 40em;
	box-shadow: 0px 0px 32px #AAF;
	text-align: center;
    background-image:url("{{ env('APP_URL') }}/static/images/s.gif");
}
</style>

<audio autoplay loop id="g">  
<source src="{{ env('APP_URL') }}/assets/g.mp3" type="audio/mp3">  
</audio>  
<audio autoplay loop id="p">  
<source src="{{ env('APP_URL') }}/assets/p.mp3" type="audio/mp3">  
</audio>  
<audio autoplay id="r">  
<source src="{{ env('APP_URL') }}/assets/r.mp3" type="audio/mp3">  
</audio>  
<script>
  var audio = document.getElementById("g");
  audio.volume = 0.8;
</script>
<script>
  var audio = document.getElementById("p");
  audio.volume = 0.05;
</script>
<script>
  var audio = document.getElementById("r");
  audio.volume = 0.7;
</script>
</head>
    <!--BODY-->
<body>
<div id="static"></div>
<div class="main">
    <div>
        <h1>403</h1>
    </div>
</div>
</body></html>

    <!--made by requiem#0666-->