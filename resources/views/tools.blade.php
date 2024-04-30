<div id="toolscontent">
<fieldset id="info">
<h3>info</h3>
<span>some domains, tools, and various scripts i have made.</span>
</fieldset>
<fieldset id="domains">
    <div><span>[<a href="">youtube to mp3/mp4</a></span>]</div>
    <div><span>[<a href="">steam txt center</a></span>]</div>
    <div><span>[<a href="">web to discord webhook</a>]</span></div>
</fieldset>

<fieldset id="scripts">
<h3>scripts n shit ive made</h3>
<span>yeah code is jank !!</span>

<hr></hr>
<?php
$extensions = array('py', 'bat');
$dir = base_path('storage/app/public/tools');
$files = array();

$json = file_get_contents($dir . '/tools.json');
$data = json_decode($json, true);

foreach ($data as $title => $fileData) {
    $var1 = $fileData['title'];
$filePath = $dir . '/' . $var1;
    $fileSize = filesize($filePath);
    if ($fileSize < 1024) {
        $fileSizeFormatted = $fileSize . ' bytes';
    } elseif ($fileSize < 1048576) {
        $fileSizeFormatted = round($fileSize / 1024, 2) . ' KB';
    } elseif ($fileSize < 1073741824) {
        $fileSizeFormatted = round($fileSize / 1048576, 2) . ' MB';
    } else {
        $fileSizeFormatted = round($fileSize / 1073741824, 2) . ' GB';
    }
    echo '<fieldset id="scriptfield">';
    echo '<legend>' . $fileData['title'] . '</legend>';
    echo '<p class="scriptdesc">' . $fileData['description'] . '</p>';
    echo '<p class="scriptsum">summary: <code class="scriptcode">' . htmlspecialchars(substr($fileData['summary'], 0, 999)) . '</code>...</p>';
    echo '<a href="' . route('download', ['file' => $fileData['title']]) . '">download </a><span>' . $fileSizeFormatted . '</span>';
    
    echo '</fieldset>';
}
?>
</fieldset>
</div>