<?php

use Illuminate\Support\Facades\File;

$file = storage_path('counter.txt');
$count = intval(File::get($file));

$digits = str_split($count);

?>
@foreach ($digits as $digit)
<img src="./static/counter/rule34_{{ $digit }}.gif">
@endforeach