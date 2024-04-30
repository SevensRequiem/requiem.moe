<!--simple homepage that will be loaded inside of another div-->
<div id="projects">
<x-markdown>
# some projects
- Aura <span class="glowlightred">[null]</span>
-- a discord bot that is currently in development going to be a backend for alot of things that are inter-connected with an 'aura'
- [Blog](https://requiem.moe/blog) <span class="glowlightblue">[working]</span><span class="glowlightorange">[dev]</span>
-- fully featured blog system integrated with a discord bot, laravel cms (custom), and more
- PS2 Game <span class="glowlightred">[null]</span> 
-- going to make a horror game to learn c++ and to sasiate my love for horror games (silent hill is so nostalgic :D)
- Unicorn <span class="glowlightred">[null]</span> -- funny name for a mutex "worm" i always wanted to make
- [Imageboard](https://chan.requiem.moe) <span class="glowlightorange">[dev]</span> --rChan, being built from scratch with symfony instead of using a vichan fork. (Decided to stop using laravel for this and use symfony, laravel is too bloated for this project)
- [ShutChan](https://shutin.network) <span class="glowlightorange">[dev]</span> -- fork of rchan for neet's / hikikomori's 
- Gallery <span class="glowlightorange">[dev]</span> -- screenshots/videos/media pulled from my steam, youtube, twitch and imgsrv
- Store <span class="glowlightorange">[dev]</span> -- sticker / merch store
- Radio <span class="glowlightorange">[dev]</span> -- webradio for here and the imageboard

## game servers
### aurora:
- minecraft <span class="glowlightorange">[dev]</span>
- terraria <span class="glowlightblue">[working]</span><span id="anetter" class="glowlightgreen">[ok]</span><span class="glowlightpurple">[calamity/thestarsabove/qol]</span>  -- whitelisted @ aurora
- gmod <span class="glowlightorange">[dev]</span><span class="glowlightpurple">[ttt/sandbox/darkrp/prophunt]</span> 
- tf2 <span class="glowlightorange">[dev]</span>
- csgo <span class="glowlightorange">[dev]</span>
- rust <span class="glowlightorange">[dev]</span>
- ark <span class="glowlightorange">[dev]</span>
- 7 days to die <span class="glowlightorange">[dev]</span>
- space engineers <span class="glowlightblue">[working]</span><span id="anetse" class="glowlightgreen">[ok]</span> -- whitelisted @ aurora
- scp: secret laboratory <span class="glowlightorange">[dev]</span>
- arma3 <span class="glowlightorange">[dev]</span><span class="glowlightpurple">[warlords]</span> 

### shutin network
- [minecraft](https://map.shutin.network) <span class="glowlightblue">[working]</span><span id="shutcraft" class="glowlightgreen">[ok]</span> -- whitelisted @ mc.shutin.network

</x-markdown>
</div>
<script>
const serverIp = 'mc.shutin.network';
const statusElementId = 'shutcraft';

function updateStatus() {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
      const response = JSON.parse(this.responseText);
      const statusElement = document.getElementById(statusElementId);
      if (response.online) {
        statusElement.classList.remove('glowlightred');
        statusElement.classList.add('glowlightgreen');
        statusElement.textContent = `[${response.players.online}/${response.players.max}]`;
      } else {
        statusElement.classList.remove('glowlightgreen');
        statusElement.classList.add('glowlightred');
        statusElement.textContent = '[query failed/rate limited]';
      }
    }
  };
  xhr.open('GET', `https://api.mcsrvstat.us/2/${serverIp}`);
  xhr.send();
}
setInterval(updateStatus, 5000);
</script>