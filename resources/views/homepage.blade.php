<!--simple homepage that will be loaded inside of another div-->
<?php
$githubjson = Storage::get('github.json');
$githubdata = json_decode($githubjson, true);
$githubfollowers = $githubdata['followers'];
$githubrepos = $githubdata['public_repos'];
$languagePercentages = $githubdata['language_percentages'];

$youtubejson = Storage::get('youtube.json');
$youtubedata = json_decode($youtubejson, true);
$subscriberCount = $youtubedata['items'][0]['statistics']['subscriberCount'];
$totalVideoViews = $youtubedata['items'][0]['statistics']['viewCount'];






?>
    <fieldset id="main">
        <ul style="list-style: none;">
            <li>Welcome to <span class="glowlightpurple">[requiem.moe]</span></li>
            <li>smoked-out lain themed code fiend w bpd</li>
            <li><span class="glowdiscord">><a href="#" id="discordtagclick">[discord]</a><</span></li>
            <li style="display: none;"><span class="glowlightred">><a href="mailto:null" target="_blank">[email]</a><</span><span>{}</span</li>
            <li><span class="glowgithub">><a href="https://github.com/sevensrequiem" target="_blank">[github]</a><</span></li>
            <li><span>[</span><span title="Total Stars">{{ $githubrepos }}</span><span>]</span><span>[</span><span title="Followers">{{ $githubfollowers }}</span><span>]</span></li>
            @foreach ($languagePercentages as $language => $percentage)
                    <code>[</span><span title="{{ $language }}">{{ $language }} <span id="githubpercent">{{ $percentage }}%</span></span><span>]</code>
            @endforeach
            <li><span class="glowtwitter">><a href="https://twitter.com/sevensrequiem" target="_blank">[twitter]</a><</span></li>
            <li><span class="glowyoutube">><a href="https://www.youtube.com/@sevensrequiem" target="_blank">[youtube]</a><</span></li>
            <li><span><code>[</span><span>{{ $subscriberCount }}</span><span>]</span><span>[</span><span>{{ $totalVideoViews }}</span><span>]</code></li>
            <li><span class="glowtwitch">><a href="https://www.twitch.tv/sevensrequiem" target="_blank">[twitch]</a><</span></li>
            <li><span class="glowsteam">><a href="https://steamcommunity.com/id/sevensrequiem" target="_blank">[steam]</a><</span></li>
            <li id="discordtag" style="display: none;"><span class="glowlightgreen">- Copied <span class="glowlightblue">requiem.moe</span> to clipboard -</li>
        </ul>
        <hr>
        <span class="glowlightpurple">[motd]</span>
        <p>moe > /archive for ps2 games of mine (physical disk uploads) coming soon !</p>
        <hr>
        <p>tryna go to <span class="glowlightgreen">><a href="https://youtu.be/aNm5LvlkPaA?t=280"target="_blank">grey-day</a><</span> tour prettyyy soon :v</p>
        <p>this place is going to be flooded with so many pics/vids from there i swear.... its not like $crim or Ruby
            saved my life or anything...</p>
    </fieldset>
    <fieldset id="anime">
    <ul class="anime-list">
<script>
fetch('/fetchanime')
    .then(response => response.json())
    .then(data => {
        const list = document.querySelector('.anime-list');
        data.data.Page.activities.forEach(activity => {
            const li = document.createElement('li');
            li.innerHTML = `
                <div class="anime">
                    <img src="${activity.media.coverImage.medium}" class="anime-img">
                    <div class="info">
                        <span class="glowlightpink">&gt;<a href="" target="_blank" class="anime-title">${activity.media.title.romaji}</a>&lt;</span>
                        <span>[<span class="glowlightblue">${activity.status}</span>]</span>
                        <span>[<span class="glowlightgreen">${activity.progress || 'N/A'}</span>]</span>
                        <span>[<span class="glowlightyellow">${new Date(activity.createdAt * 1000).toLocaleDateString()}</span>]</span>
                    </div>
                </div>
            `;
            list.appendChild(li);
        });
    });
</script>
</ul>
</fieldset>
    <fieldset id="chat">
@include('chat')
    </fieldset>
<fieldset id="banner">
    <?php 
        // pick random banner from storage/app/public/banners
        $banners = Storage::files('banner');
        $banner = $banners[array_rand($banners)];
        $uuid = pathinfo($banner, PATHINFO_FILENAME); // get the filename without extension
        echo '<img src="/static/banner/' . $uuid . '" alt="banner">';
    ?>
</fieldset>
<fieldset id="misc">
    <span>add urself to the webring @ <a href="https://github.com/SevensRequiem/requiem.moe" target="_blank">github</a></span>
    <hr>
<div id="webring">
<?php


$webringdir = storage_path('webring');
$webring = json_decode(file_get_contents($webringdir . '/webring.json'), true);
foreach ($webring as $item) {
    $link = $item['link'];
    $title = $item['title'];
    $image = $item['image'];
    echo '<a href="' . $link . '" title="' . $title . '"><img src="./webring/' . $image . '" alt="' . $title . '"></a>';
}
?>
</div>
</fieldset>
<script>
const link = document.getElementById('discordtagclick');
const discordtag = document.getElementById('discordtag');
link.addEventListener('click', () => {
    discordtag.style.display = 'inline';
    const text = 'requiem.moe';
    navigator.clipboard.writeText(text);
    setTimeout(() => {
        discordtag.style.opacity = '0';
    }, 3000);
});
</script>