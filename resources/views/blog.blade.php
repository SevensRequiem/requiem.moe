<!-- Simple homepage that will be loaded inside of another div -->
<div id="blog">
    <?php
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Cache;
    $posts = File::directories(storage_path('app/blog'));
    usort($posts, function ($a, $b) {
        $aPostMdTime = filemtime($a . "/post.md");
        $bPostMdTime = filemtime($b . "/post.md");
        return $bPostMdTime - $aPostMdTime;
    });
    ?>
    @foreach ($posts as $post)
    <?php

        $postJson = Cache::rememberForever('postJson', function () use ($post) {
            return json_decode(File::get($post . '/post.json'));
        });

        $commentJson = Cache::rememberForever('commentJson', function () use ($post) {
            return json_decode(File::get($post . '/comments.json'));
        });

        $postDate = $postJson->date;
        $postAuthor = $postJson->author ?? '';
        $postTitle = $postJson->title;
        $postHex = $postJson->hex ?? '';
        $postQuote = $postJson->quote ?? '';

        $postContent = Cache::rememberForever('postContent', function () use ($post) {
            return File::get($post . '/post.md');
        });

        $postUUID = $postJson->uuid;
        $comments = $commentJson->comments ?? [];

        if (Cache::has('postJson') && Cache::has('commentJson') && Cache::has('postContent')) {
            if (File::lastModified($post . '/post.json') > Cache::get('postJson')->timestamp ||
                File::lastModified($post . '/comments.json') > Cache::get('commentJson')->timestamp ||
                File::lastModified($post . '/post.md') > Cache::get('postContent')->timestamp) {
                Cache::forget('postJson');
                Cache::forget('commentJson');
                Cache::forget('postContent');
                $postJson = Cache::rememberForever('postJson', function () use ($post) {
                    return json_decode(File::get($post . '/post.json'));
                });
                $commentJson = Cache::rememberForever('commentJson', function () use ($post) {
                    return json_decode(File::get($post . '/comments.json'));
                });
                $postContent = Cache::rememberForever('postContent', function () use ($post) {
                    return File::get($post . '/post.md');
                });
            }
        }
        ?>
    <fieldset id="blogfieldset">
        <!--tbhimstillproudofthebackendhere:33-req-->
        <legend id="bloglegend">
            <span class="glowlightgreen">[#{{ $loop->count - $loop->index }}]</span>
            [<span class="blogdate">{{ $postDate }}</span>]
            [<span class="blogtitle">{{ $postTitle }}</span>]
            <span class="blogauthor">{{ $postAuthor }}</span>
            <span class="blogquote" style="color: #EEF; background-color: {{ $postHex }};">{{ $postQuote }}</span>
        </legend>
        <div id="blogpost">
            <img src="./static/blog/{{ $postUUID }}" alt="{{ $postTitle }}">
            <x-markdown>{{ $postContent }}</x-markdown>
        </div>
        <hr>
        @if (in_array($userId, $adminIds))
        <div class="admin-buttons">
            <a>Edit</a><a>Delete</a><span>[<span>{{ $postUUID }}</span>]</span>
        </div>
        @endif
        <hr>
        <fieldset id="comments">
            <legend>
                <span class="glowlightpurple">[comments]</span>
                <span>[<span class="glowlightgreen">{{ count($comments ?? '') }}</span>]</span>
            </legend>
            @if (count($comments) == 0)
            <p>No comments yet.</p>
            @else
            @foreach ($comments as $comment)
            <legend>
                [<span class="commentdate">{{ $comment->date }}</span>]
                [<span class="commentauthor">{{ $comment->author }}</span>]
            </legend>
            <p class="commentcontent">
                <x-markdown>{{ $comment->content }}</x-markdown>
            </p>
            @endforeach

            @endif
        </fieldset>
        <form class="commentform">
            @csrf
            <input type="hidden" class="postuuid" name="postuuid" value="{{ $postUUID }}">
            <textarea class="username" name="username" required>Anonymous</textarea>
            <textarea class="comment" name="comment" required></textarea>
            <a href="#" class="submit-comment">Post Comment</a>
        </form>
    </fieldset>
    <br></br>
    @endforeach
</div>