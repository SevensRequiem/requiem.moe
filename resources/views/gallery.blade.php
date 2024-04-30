<link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
                <div class="gallery">
    <?php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\File;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Str;

try {
    $file = Storage::get('gallery/gallery.json');
    $json = json_decode($file, true);
    $json = $json ?? [];

    usort($json, function ($a, $b) {
        return strtotime($b['created']) - strtotime($a['created']);
    });

} catch (\Exception $e) {
    // Handle the exception, e.g., log it or display an error message.
    dd($e->getMessage());
}
    ?>
    @foreach($json as $value)
        <fieldset>
            <legend>{{ $value['title'] }}</legend>
            <img class="gallery-image" src="./static/gallery/{{ $value['uuid'] }}" alt="{{ $value['title'] }}" onclick="showImage('{{ $value['uuid'] }}')">
            <div class="gallery-info">
                <p>{{ $value['description'] }}</p>
                <p>{{ $value['size'] }}</p>
                <p>{{ $value['created'] }}</p>
            </div>

            @if (in_array(Auth::id(), explode(',', env('ADMIN_IDS'))))
            <span class="glowlightcyan" style="font-size: 0.4em;">{{ $value['uuid'] }}</span>
            @endif
        </fieldset>

    @endforeach
</div>