<?php

namespace App\Cache\Replacers;

use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Replacers\Replacer;

class CommentReplacer implements Replacer
{
    protected string $replacementString = '{{comments}}';

    public function replaceInCachedResponse(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $postUUID = $postJson->uuid;
        $comments = $commentJson->comments ?? [];

        if ((isset($postJson->timestamp) && File::lastModified($post . '/post.json') > $postJson->timestamp) ||
            (isset($commentJson->timestamp) && File::lastModified($post . '/comments.json') > $commentJson->timestamp) ||
            (isset($postContent->timestamp) && File::lastModified($post . '/post.md') > $postContent->timestamp)) {
            $postJson = json_decode(File::get($post . '/post.json'));
            $commentJson = json_decode(File::get($post . '/comments.json'));
            $postContent = File::get($post . '/post.md');
        }

        $commentsHtml = '';
        if (count($comments) == 0) {
            $commentsHtml = '<p>No comments yet.</p>';
        } else {
            foreach ($comments as $comment) {
                $commentsHtml .= '<legend>
                    [<span class="commentdate">' . $comment->date . '</span>]
                    [<span class="commentauthor">' . $comment->author . '</span>]
                </legend>
                <p class="commentcontent">
                    <x-markdown>' . $comment->content . '</x-markdown>
                </p>';
            }
        }

        $response->setContent(str_replace(
            $this->replacementString,
            '<fieldset id="comments">
                <legend>
                    <span class="glowlightpurple">[comments]</span>
                    <span>[<span class="glowlightgreen">' . count($comments) . '</span>]</span>
                </legend>' . $commentsHtml,
            $response->getContent()
        ));
    }
    public function prepareResponseToCache(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $response->setContent(str_replace(
            $this->replacementString,
            '',
            $response->getContent()
        ));
    }
}