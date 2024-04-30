<?php 
use Illuminate\Support\Facades\File;
?>
<side>
        <div id="audiowarning" style="display: none;"><span>please enable audio autoplay</span>
        </div>
        <div>
            <h1 class="glow">requiem.moe</h1>
        </div>
        <div><span class="shimmer1">Close the World. Open the nExt</span></div>
        <div><span style="font-size: small;" title="L(106.13hz) R(113.96hz)" class="shimmer2">7.83 hz - 20.3hz</span></div>
        <noscript>
            <div><span style="font-size: 9px;">Without JS the site loses 80% functionality.</span></div>
        </noscript>
        <div>
@include('function.user')
        </div>
        <span id="status" class="glowlightpurple">[rss]</span>|<span id="status" class="glowlightpurple">[rss]</span>|<span id="status" class="glowlightpurple">[rss]</span>
        <fieldset>
            <legend>servers</legend>
            <div class="serverstats">
                <ul>
<li><span>Luna <span id="status" class="glowlightgreen">[ok]</span> <span id="lunaup" class="glowlightblue">[<span id="uptime" v-text="uptime">

<?php
$uptime_seconds = shell_exec("cat /proc/uptime | awk '{print \$1}'");
$uptime_months = floor($uptime_seconds / (30 * 24 * 60 * 60));
$uptime_seconds -= $uptime_months * (30 * 24 * 60 * 60);
$uptime_days = floor($uptime_seconds / (24 * 60 * 60));
$uptime_seconds -= $uptime_days * (24 * 60 * 60);
$uptime_hours = floor($uptime_seconds / (60 * 60));
$uptime_seconds -= $uptime_hours * (60 * 60);
$uptime_minutes = floor($uptime_seconds / 60);
$uptime_seconds -= $uptime_minutes * 60;
$uptime = sprintf("%d:%02d:%02d:%02d:%02d", $uptime_months, $uptime_days, $uptime_hours, $uptime_minutes, $uptime_seconds);

echo $uptime;
?>

</span>]</span></span></li>
<li><span>ImgSrv <span id="status" class="glowlightgreen">[ok]</span></span></li>
<li><span>WebSrv <span id="status" class="glowlightgreen">[ok]</span><span id="status" class="glowlightred">[0]</span></span></li>
<li><span>Aura <span id="status" class="glowlightcyan">[<span class="rainbow">0.0.1</span>]</span></span></li>
</ul>
            </div>
        </fieldset>
        <fieldset>
            <legend>stats</legend>
            <span id="total-users">Users [<span id="global-users">0</span>][<span id="moe-users">0</span>][<span id="achan-users">0</span>]</span>
            <span id="live-viewers">0</span> <span>Online</span>
            <div id="website-version" class="status">version: <?php echo $currentVer ?></div>
            <div id="website-size" class="status">size:<span id="totalsize"></span></div>
            <script>
                let loadingInterval;
                const totalsize = document.getElementById('totalsize');

                // Start loading animation
                function startLoadingAnimation() {
                  let dots = '.';
                  loadingInterval = setInterval(() => {
                    totalsize.textContent = dots;
                    dots = dots === '...' ? '.' : dots + '.';
                  }, 444); // Change the interval as needed
                }

                // Stop loading animation
                function stopLoadingAnimation() {
                  clearInterval(loadingInterval);
                }

                // Fetch data
                function fetchData() {
                  fetch('/stats')
                    .then(response => response.json())
                    .then(data => {
                      stopLoadingAnimation();
                      totalsize.textContent = data.fileSize;
                    })
                    .catch(error => {
                      stopLoadingAnimation();
                      totalsize.textContent = 'Error loading data';
                    });
                }

                // Start loading animation and fetch data
                startLoadingAnimation();
                fetchData();
              </script>
            <?php
$file = storage_path('counter.txt');
$count = intval(File::get($file));
$digits = str_split($count);
?>
            <div id="site-counter" class="status" title="{{ implode('', $digits) }}">
            @foreach ($digits as $digit)
              <img src="/static/counter/rule34_{{ $digit }}.gif">
            @endforeach
            </div>
            <span id="weekly-hits" class="status">weekly: {{ $weeklyHits ?? '0' }}</span>
            <span id="daily-hits" class="status">today: {{ $dailyHits ?? '0' }}</span>
        </fieldset>
        <img src="/banner.gif" alt="cat" style="position: relative; max-width: 100%; max-height: 100%; width: auto; height: auto;">
        <fieldset>
        [<span class="glowlightyellow">$B</span>]<span>GREYDAY<span class="glowlightred">[x]</span></span>
        <br></br>
        [<span class="glowlightyellow">Ski</span>]<span class="glowlightred">[x]</span>
        <br></br>
        [<span class="glowlightyellow">$Not</span>]<span class="glowlightred">[x]</span>
        <br></br>
        [<span class="glowlightyellow">Darkie</span>]<span class="glowlightred">[x]</span>
        <br></br>
        [<span class="glowlightyellow">BSavage</span>]<span class="glowlightred">[x]</span>
        <br></br>
        [<span class="glowlightyellow">SlipKnot</span>]<span class="glowlightred">[x]</span>
        <br></br>
        [<span class="glowlightyellow">FFDP</span>]<span class="glowlightred">[x]</span>
        </fieldset>
        @if (in_array($userId, $adminIds))
        <fieldset class="admin-buttons">
        <span>[<a href="./admin">shiet</a>]</span>
</fieldset>
    @endif
    </side>
    <script>
function updateUptime() {
  const uptimeDiv = document.getElementById('uptime');
  const uptimeText = uptimeDiv.textContent.trim();
  const uptimeParts = uptimeText.split(':');

  if (uptimeParts.length === 5) {
    const uptimeMonths = parseInt(uptimeParts[0]);
    const uptimeDays = parseInt(uptimeParts[1]);
    const uptimeHours = parseInt(uptimeParts[2]);
    const uptimeMinutes = parseInt(uptimeParts[3]);
    const uptimeSeconds = parseInt(uptimeParts[4]);
    const totalUptimeSeconds =
      uptimeSeconds +
      uptimeMinutes * 60 +
      uptimeHours * 3600 +
      uptimeDays * 86400 +
      uptimeMonths * 2592000;
    const newTotalUptimeSeconds = totalUptimeSeconds + 1;
    const newUptimeMonths = Math.floor(newTotalUptimeSeconds / 2592000);
    const newUptimeDays = Math.floor((newTotalUptimeSeconds % 2592000) / 86400);
    const newUptimeHours = Math.floor((newTotalUptimeSeconds % 86400) / 3600);
    const newUptimeMinutes = Math.floor((newTotalUptimeSeconds % 3600) / 60);
    const newUptimeSeconds = newTotalUptimeSeconds % 60;
    const newUptimeText = `${newUptimeMonths}:${newUptimeDays.toString().padStart(2, '0')}:${newUptimeHours.toString().padStart(2, '0')}:${newUptimeMinutes.toString().padStart(2, '0')}:${newUptimeSeconds.toString().padStart(2, '0')}`;
    uptimeDiv.textContent = newUptimeText;
  } else {
    console.error("Uptime text content does not have the expected format");
  }
}


  window.addEventListener('load', function() {
    updateUptime();
    setInterval(updateUptime, 1000);
  });
</script>

<script>
  // get data from ./data-moeusers.json 

  const moeUsers = document.getElementById('moe-users');

  function updateUsers() {
    fetch('/data-moeusers')
      .then(response => response.json())
      .then(data => {
        moeUsers.textContent = data.moeUsers;
      });
  }

  window.addEventListener('load', function() {
    updateUsers();
    setInterval(updateUsers, 10000);
  });
</script>