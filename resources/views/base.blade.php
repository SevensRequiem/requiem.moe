<!DOCTYPE html>
<?php 
$currentVer = "1.4.5";
$userId = auth()->id();
$adminIds = explode(',', env('ADMIN_IDS'));
use App\Http\Controllers\HitCounterController;
$gethits = new HitCounterController();
use MatthiasMullie\Minify;
use Illuminate\Support\Facades\File;

if (app()->environment('local')) {
  $jsFiles = File::files(resource_path('js'));

  foreach ($jsFiles as $file) {
    $minifier = new Minify\JS($file->getPathname());
    $minifier->minify(public_path('assets/js/'.$file->getBasename('.js').'.min.js'));
  }

  $cssFiles = File::files(resource_path('css'));

  $minifier = new Minify\CSS();

  foreach ($cssFiles as $file) {
    $minifier->add($file->getPathname());
  }

  $minifier->minify(public_path('assets/css/app.min.css'));
}
?>

<html>

<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width">
    <meta name="theme-color" content="#000">
    <meta name="keywords" content="requiem, sevens, sevensrequiem, requiem.moe">
    <meta name="description" content="requiem.moe; Personal Website and  quality VPS/Game/Webserver store">
    <meta name="author" content="requiem">
    <meta property="og:description" content="requiem.moe; Personal Website and  quality VPS/Game/Webserver store">
    <meta name="robots" content="index, follow">
    <meta property="og:locale" content="en_US">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="requiem.moe">
    <meta property="og:site_name" content="requiem.moe">
    <meta property="og:url" content="https://requiem.moe">
    <meta property="og:image" content="https://requiem.moe/banner.gif">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <link rel="icon" type="image/png" href="/static/favicon.png">
    <link rel="icon" type="image/png" href="/static/favicon.png">
    <title>>> requiem.moe</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <audio id="bzzz">
        <source src="./assets/sfx/p.mp3" type="audio/mpeg">
    </audio>
    <script>
    var audio = document.getElementById("bzzz");
    audio.volume = 0.03;
    audio.play();
    </script>
    <audio id="resonance">
    </audio>
    <!--hmm... im wondering if i should merge the css and js files into one or not...-->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!--i dont like js, well, i dont like having a site rely on it due to ppls privacy concerns, but idk an alternative for the same effects :/-->
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
<!--whatdouthinkofthestars, user?-->
<div id="static"></div>
<div id="donate-box">
<a href="javascript:void(0)" id="close" onclick="document.getElementById('donate-box').style.display='none';document.getElementById('static').style.display='none'">[X]</a>
<p>Donate through Stripe.</p>
        <form id="payment-form">
            <div class="form-row">
                <label for="amount">Donation Amount</label>
                <input id="amount" name="amount" type="number" min="1" placeholder="Enter donation amount" required>
            </div>
            <div class="form-row">
                <label for="recurring">
                    <input id="recurring" name="recurring" type="checkbox"> Make this a monthly donation
                </label>
            </div>
            <button id="submit">Donate</button>
        </form>
</div>
<div id="contact-box">
<a href="javascript:void(0)" id="close" onclick="document.getElementById('contact-box').style.display='none';document.getElementById('static').style.display='none'">[X]</a>
<form id="contact-form">
        <div class="form-row">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" placeholder="Enter your name" required>
        </div>
        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="Enter your email" required>
        </div>
        <div class="form-row">
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
        </div>
        <button id="submit">Send</button>
        </form>
        <span id="contact-status"></span>
    </div>
</div>

<body>
    <div id="sprite-container">
        <img id="ghost1" src="/static/sprites/ghost1.gif" title="gh☆sty"></img>
    </div>
    @include('header')
    @include('side')
    <fieldset id="content">
    <div id="popup" style="display:none;">
                    <img id="popup-image" src="" alt="">
                </div>
        <legend>
            @if(request()->is('blog'))
            {{ "> blog <" }}
            @elseif(request()->is('projects'))
            {{ "> projects <" }}
            @elseif(request()->is('versios'))
            {{ "> version <" }}
            @elseif(request()->is('tools'))
            {{ "> tools <" }}
            @elseif(request()->is('store'))
            {{ "> store <" }}
            @elseif(request()->is('about'))
            {{ "> about <" }}
            @elseif(request()->is('gallery'))
            {{ "> gallery <" }}
            @elseif(request()->is('donate'))
            {{ "> donate <" }}
            @elseif(request()->is('post'))
            @if(isset($_SESSION['token']))
            {{ "> post <" }}
            @else
            {{ "> not logged in <" }}
            @endif
            @else
            {{ "> homepage <" }}
            @endif

        </legend>
        <content>

            @if(request()->is('blog'))
            @include('blog')
            @elseif(request()->is('projects'))
            @include('projects')
            @elseif(request()->is('version'))
            @include('version')
            @elseif(request()->is('tools'))
            @include('tools')
            @elseif(request()->is('store'))
            @include('store')
            @elseif(request()->is('about'))
            @include('about')
            @elseif(request()->is('gallery'))
            @include('gallery')
            @elseif(request()->is('donate'))
            @include('donate')
            @elseif(request()->is('admin'))
            @if (in_array($userId, $adminIds))
            @include('admin.base')
            @endif
            @elseif(request()->is('admin/post'))
            @if (in_array($userId, $adminIds))
            @include('admin.post')
            @endif
            @elseif(request()->is('admin/logs'))
            @if (in_array($userId, $adminIds))
            @include('admin.logs')
            @endif
            @elseif(request()->is('admin/analytics'))
            @if (in_array($userId, $adminIds))
            @include('admin.analytics')
            @endif
            @else
            @include('homepage')
            @endif
        </content>
    </fieldset>
    @include('footer')
</body>
<script src="{{ asset('assets/js/stars.min.js') }}"></script>
<script src="{{ asset('assets/js/nav.min.js') }}"></script>
<script src="{{ asset('assets/js/rainbow.min.js') }}"></script>
<script src="{{ asset('assets/js/resonance.min.js') }}"></script>
<script src="{{ asset('assets/js/donate.min.js') }}"></script>
<script src="{{ asset('assets/js/blogcomment.min.js') }}"></script>
<script src="{{ asset('assets/js/sprites.min.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
const images = document.querySelectorAll('.gallery-image');
const popup = document.getElementById('popup');
const popupImage = document.getElementById('popup-image');

images.forEach(image => {
    image.addEventListener('mouseover', e => {
        const src = e.target.getAttribute('src');
        popupImage.setAttribute('src', src);
        popup.style.display = 'block';
    });

    image.addEventListener('mousemove', e => {
        const x = e.clientX - popup.offsetWidth / 2;
        const y = e.clientY - popup.offsetHeight / 2;
        popup.style.left = x + 'px';
        popup.style.top = y + 'px';
    });

    image.addEventListener('mouseout', e => {
        popup.style.display = 'none';
    });
});
</script>
<script>
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
      cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
      encrypted: true
    });
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var channel = pusher.subscribe('dev-channel');
    channel.bind('dev-event', function(data) {
      var chatbox = document.getElementById('chatbox');
      var glowingOrb = document.getElementById('notiforb');
      var currentScrollPosition = chatbox.scrollTop + chatbox.clientHeight;
      var totalScrollHeight = chatbox.scrollHeight;
      var difference = totalScrollHeight - currentScrollPosition;
      var chatmessage = document.createElement('div');
      chatmessage.className = 'chatmessage';
      var chatid = document.createElement('span');
      chatid.className = 'chatid';
      chatid.innerHTML = "[" + data.id + "]";
      var chatusername = document.createElement('span');
      chatusername.className = 'chatusername';
      if (data.trueuser && data.trueuser !== 'none') {
        chatusername.innerHTML = "*[" + data.username + "]";
      } else if (data.trueuser === 'none' && data.username !== 'Anonymous') {
        chatusername.innerHTML = "~[" + data.username + "]";
      } else {
        chatusername.innerHTML = "[" + data.username + "]";
      }
      /*hi idiot*/
      chatusername.setAttribute('alt', data.trueuser);
      var chatmessagecontent = document.createElement('p');
      chatmessagecontent.className = 'chatmessagecontent';
      chatmessagecontent.innerHTML = data.message;
      var chattimestamp = document.createElement('span');
      chattimestamp.className = 'chattimestamp';
      var date = new Date(data.timestamp * 1000);
      var chatboxmsgstatus = document.getElementById('chatboxmsgstatus');
      chattimestamp.innerHTML = "[" + date.toLocaleString() + "]";
      chatmessage.appendChild(chatid);
      chatmessage.appendChild(chatusername);
      chatmessage.appendChild(chatmessagecontent);
      chatmessage.appendChild(chattimestamp);
      @if (in_array($userId, $adminIds))
        var deleteButton = document.createElement('span');
        deleteButton.innerHTML = "[<a href='/messages/" + data.uuid + "' data-method='delete'>D</a>]";
        chatmessage.appendChild(deleteButton);
      @endif
      chatbox.appendChild(chatmessage);
      var hr = document.createElement('hr');
      chatbox.appendChild(hr);
      if (difference > totalScrollHeight * 0.01) {
        glowingOrb.style.display = 'block';
      }

      if (difference <= totalScrollHeight * 0.10) {
        chatbox.scrollTop = chatbox.scrollHeight;
      }
      chatbox.addEventListener('scroll', function() {
        if (chatbox.scrollTop + chatbox.clientHeight >= chatbox.scrollHeight) {
          glowingOrb.style.display = 'none';
        }
      });
    });
    document.addEventListener('submit', function(e) {
      if(e.target.matches('#chatboxmsg')) {
        e.preventDefault();
        var chatusername = document.getElementById('chatusername').value;
        var chatboxmsginput = document.getElementById('chatboxmsginput').value;
        var chatboxmsgstatus = document.getElementById('chatboxmsgstatus');
        chatboxmsgstatus.innerHTML = 'Sending...';
        axios.post('/send-message', {
          username: chatusername,
          message: chatboxmsginput
        })
        .then(function (response) {
          if (response.data.status) {
            chatboxmsgstatus.innerHTML = response.data.status;
            setTimeout(function() {
              chatboxmsgstatus.innerHTML = '';
            }, 3000);
            chatboxmsginput.value = '';
          } else if (response.data.error) {
            chatboxmsgstatus.innerHTML = response.data.error;
            setTimeout(function() {
              chatboxmsgstatus.innerHTML = '';
            }, 3000);
          } else {
            chatboxmsgstatus.innerHTML = 'Message sent successfully.';
            setTimeout(function() {
              chatboxmsgstatus.innerHTML = '';
            }, 3000);
            chatboxmsginput.value = '';
          }
        })
        .catch(function (error) {
          chatboxmsgstatus.innerHTML = error.response.data.error;
          setTimeout(function() {
              chatboxmsgstatus.innerHTML = '';
            }, 3000);
        });
      }
    });
    axios.get('/get-messages')
      .then(function (response) {
        var messages = response.data.messages;
        var chatbox = document.getElementById('chatbox');
        messages.forEach(function (message) {
          var chatmessage = document.createElement('div');
          chatmessage.className = 'chatmessage';
          var chatid = document.createElement('span');
          chatid.className = 'chatid';
          chatid.innerHTML = "[" + message.id + "]";
          var chattimestamp = document.createElement('span');
          chattimestamp.className = 'chattimestamp';
          var date = new Date(message.timestamp * 1000);
          chattimestamp.innerHTML = "[" + date.toLocaleString() + "]";
          var chatusername = document.createElement('span');
          chatusername.className = 'chatusername';
          if (message.trueuser && message.trueuser !== 'none') {
            chatusername.innerHTML = "*[" + message.username + "]";
          } else if (message.trueuser === 'none' && message.username !== 'Anonymous') {
            chatusername.innerHTML = "~[" + message.username + "]";
          } else {
            chatusername.innerHTML = "[" + message.username + "]";
          }
          chatusername.setAttribute('alt', message.trueuser);
          var chatmessagecontent = document.createElement('p');
          chatmessagecontent.className = 'chatmessagecontent';
          chatmessagecontent.innerHTML = message.message;
          chatmessage.appendChild(chatid);
          chatmessage.appendChild(chatusername);
          chatmessage.appendChild(chatmessagecontent);
          chatmessage.appendChild(chattimestamp);
          @if (in_array($userId, $adminIds))
        var deleteButton = document.createElement('span');
        deleteButton.innerHTML = "[<a href='/messages/" + message.uuid + "' data-method='delete'>D</a>]";
        chatmessage.appendChild(deleteButton);
        deleteButton.addEventListener('click', function (event) {
          event.preventDefault();
          axios.delete('/messages/' + message.uuid)
            .then(function (response) {
              if (response.data.success) {
                chatmessage.remove();
              } else {
                console.log(response.data.message);
              }
            })
            .catch(function (error) {
              console.log(error);
            });
        });
      @endif
          chatbox.appendChild(chatmessage);
          var hr = document.createElement('hr');
          chatbox.appendChild(hr);
          chatbox.scrollTop = chatbox.scrollHeight;
        });
      })
      .catch(function (error) {
        console.log(error);
      });
const chatMessages = document.querySelectorAll('.chatbox p');

// igottafixmymania... and add. coding in spurts then forgetting about it for a few months is not good for my brain
chatMessages.forEach(message => {
  const wordCount = message.textContent.trim().split(/\s+/).length;
  if (wordCount <= 5) {
    message.style.fontSize = '16px';
  } else if (wordCount <= 10) {
    message.style.fontSize = '14px';
  } else {
    message.style.fontSize = '12px';
  }
});
</script>

</html>