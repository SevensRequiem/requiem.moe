<?php

namespace App\Cache\Replacers;

use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Replacers\Replacer;

class CsrfTokenReplacer implements Replacer
{
    protected string $replacementString = '{{ csrf_token() }}';

    public function prepareResponseToCache(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $response->setContent(str_replace(
            csrf_token(),
            $this->replacementString,
            $response->getContent()
        ));
    }

    public function replaceInCachedResponse(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $response->setContent(str_replace(
            $this->replacementString,
            csrf_token(),
            $response->getContent()
        ));
    }
}