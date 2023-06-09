<?php
require 'functions/counter.php';
$member_count = 123;
?>
<head>
  <title>requiem.moe</title>
  <meta name="description" content="This is My personal website.">
  <meta name="keywords"
    content="requiem, moe, website, sevensrequiem, @requiem, lain, cyber, cyberpunk, aesthetic, store, blog, tech">
  <meta name="author" content="Requiem">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/foobar404/wave.js/dist/bundle.js"></script>
<body>
  <link rel="stylesheet" type="text/css" href="./assets/css/starfield.css">
  <link rel="stylesheet" href="./assets/css/os-font.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  <script src="https://unpkg.com/cursor-effects@latest/dist/browser.js"></script>
  </head>
  <div class="pentagram-container">
    <svg id='pentagram' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000">
      <g transform="translate(359, 420)">
        <path class="pentagram" stroke="#aaf" stroke-width="3px" fill="#000"
          d="m 86.498597,-332.09939 -147.343765,460.46878 29.84375,24.0625 113.750015,-358.75003 58.437503,175.312528 43.43748,-1.25 -98.124983,-299.843778 z m 35.468753,59.84375 12.34375,40.9375 c 118.54078,15.24525 218.36868,90.26253 268.43748,193.750028 l 44.21875,1.875 C 392.47668,-165.99559 268.90308,-260.07714 121.96735,-272.25564 Z m -69.062503,0.3125 c -149.745215,13.99075 -274.720827,113.1363 -325.937517,248.593778 l 42.65625,0 C -183.29587,-132.96641 -81.581868,-213.53674 40.561097,-230.69314 l 12.34375,-41.25 z m 474.062483,247.187528 -489.062483,8.125 -10.78125,36.09375 386.406233,-4.375 -162.03125,119.375002 10,38.12502 265.46875,-197.343772 z m -557.499998,9.375 -320.000002,5.3125 396.406267,282.187522 33.59375,-26.09375 -307.812517,-223.593772 187.031252,-2.1875 10.78125,-35.625 z m 234.999998,43.90625 -42.96875,1.25 L 282.59233,393.52566 131.65485,283.83816 95.873597,307.74441 356.65483,493.52566 204.46733,28.525638 Z m 263.90625,4.0625 -35.78125,24.0625 c 3.066,18.5752 4.6875,37.58585 4.6875,57.031252 0,99.59017 -41.85025,189.37292 -108.90625,252.81252 l 11.71875,42.65625 c 83.63895,-71.0893 136.71875,-177.11585 136.71875,-295.46877 0,-27.815952 -2.86715,-54.940452 -8.4375,-81.093752 z m -760.3125,13.4375 c -3.86972,21.97085 -5.9375,44.5748 -5.9375,67.656252 0,115.89167 50.91753,219.91462 131.5625,290.93752 l 12.65625,-42.03125 c -64.70806,-63.1933 -104.84375,-151.3518 -104.84375,-248.90627 0,-15.180002 0.92571,-30.177502 2.8125,-44.843752 l -36.25,-22.8125 z m 488.90625,128.750022 -306.71875,226.25 57.812502,-182.5 -31.09375,-21.25 -97.187502,303.59375 387.96875,-288.4375 -10.78125,-37.65625 z m 42.8125,252.8125 c -45.4765,21.8266 -96.49763,34.0625 -150.312483,34.0625 -53.67945,0 -104.5929,-12.18275 -150.000015,-33.90625 l -37.03125,25.3125 c 55.44755,30.65065 119.197515,48.125 187.031265,48.125 67.817433,0 131.592983,-17.4881 187.031233,-48.125 l -36.71875,-25.46875 z">
        </path>
      </g>
    </svg>
  </div>
  <canvas id="stars"></canvas>
  <div id="static"></div>
  <div id="overlay"></div>
  <side>
    <div id="audiowarning" style="display: none;"><span>please enable audio autoplay for full site functions</span>
    </div>
    <div>
      <h1>requiem.moe</h1>
    </div>
    <div>[<span id="rain">RAIN</span>]</div>
    <div><span>Close the World. Open the nExt</span></div>
    <div><span style="font-size: small;" title="L(106.13hz) R(113.96hz)">resonance: 7.83 hz - 20.3hz</span></div>
    <noscript>
      <div><span style="font-size: 9px;">Please enable JavaScript to use this site.</span></div>
    </noscript>
    <div>
      <a class="ajax-link" href="#" onclick="loadHome()">home</a>
      <a class="ajax-link" href="#" onclick="loadBlog()">blog</a>
      <a href="#" onclick="loadProjects()">projects</a>
      <a href="#" onclick="loadVersions()">versions</a>
    </div>
    <?php
    $randomStrings = array("slide:yellow:", "scroll:green:", "shake:red:", "glow1:wave2:");
    $randomString = $randomStrings[array_rand($randomStrings)];
    $splash = file("splash.txt");
    $quote = $splash[array_rand($splash)];
    echo "<p>$randomString$quote</p>";
    ?>
    <fieldset>
      <legend>servers</legend>
      <div>
        <div id="luna-status" class="status">Luna <span id="son">[ OK ]</span></div>
        <div id="icarus-status" class="status">Icarus <span id="soff">[ N/A ]</span></div>
        <div id="aura-status" class="status">Aura <span id="soff" class="">[ N/A ]</span></div>
        <?php echo '<div id="aurora-status"><a id="invite" href=\'https://discord.gg/zzZzRJy\'>Aurora // オーロラ <span id="ppl">[ ' . $member_count . ' ]</span></a></div>'; ?>
      </div>

    </fieldset>
    <fieldset>
      <legend>stats</legend>
      <div id="website-version" class="status">version: [1.4]</div>
      <?php echo '<div id="site-counter" class="status">Site Counter: [<span id="ppl">' . $count . '</span>]</div>'; ?>
      <div id="last-edit" class="status">
        <?php $file = 'index.php';
        if (file_exists($file)) {
          echo "Last modified: " . date("F d Y.", filemtime($file));
        } ?>
      </div>
    </fieldset>
    <fieldset>
      <div class="musicplayer">
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
            <div id="volume-control" onwheel="changeVolume(event)">
              <span id="volume-level">[============]</span>
            </div>
          </div>
          <div id="playlist">
            <ol>
              <?php $directory = './assets/music/';
              $files = array_map('basename', glob($directory . "*.{mp3}", GLOB_BRACE));
              foreach ($files as $file) {
                echo "<li><a href='#' data-src='./assets/music/" . $file . "'>" . $file . "</a></li>";
              } ?>
            </ol>
          </div>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div id="webbanner"><img src="banner.gif" alt="Web Banner" width="88px" height="31px"></div>
    </fieldset>
    <fieldset id="tips">
      <legend>tips</legend>
<ul>
  <li id="tip1">BTC: <a>
      <p>1HjxuvfznfS9o9BG5LMwTuhHxE6DTXCHrz</p>
    </a></li>
    <hr>
  <li id="tip2">DOGE: <a>
      <p>DHhmtYytVSJnRAt2HRUsTK5i2EdPKPc64C</p>
    </a></li>
    <hr>
  <li id="tip3">ETH(ERC20): <a>
      <p>0xeba38b169a6053753c1441e383c1c004a895287e</p>
    </a></li>
    <hr>
  <li id="tip4"><a href="https://www.paypal.com/paypalme/sevensrequiem" target="_blank">PayPal</a></li>
</ul>
    </fieldset>


  </side>
  <fieldset id="content" class="content">
  </fieldset>
  <footer>
    <a href="https://galladite.net/~galladite/"><img src="./webring/galladite.gif" width="88px" height="31px"></a>
    <a href="https://adacayiseverim.neocities.org"><img src="./webring/adacayiseverim.png" width="88px"height="31px"></a>
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
    <script type="text/javascript" src="./assets/js/resonance.js"></script>
    <script type="text/javascript" src="./assets/js/SFX.js"></script>
  </footer>
</body>
<script type="text/javascript" src="./assets/js/codrainjtag.js"></script>
<script type="text/javascript" src="./assets/js/stars.js"></script>
<script type="text/javascript" src="./assets/js/navfunctions.js"></script>
<script type="text/javascript" src="./assets/js/audioplayer.js"></script>
<script type="text/javascript" src="./assets/js/os-font.js"></script>
<script type="text/javascript" src="./assets/js/tips.js"></script>
<script type="text/javascript">
  OsFont.compile();
  if (document.cookie.indexOf("reloaded=true") == -1) {
    var date = new Date();
    date.setTime(date.getTime() + (8 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toUTCString();
    document.cookie = "reloaded=true" + expires + "; path=/";
    location.reload();
  }
</script>
</script>
<!--XaTuring Lives-->