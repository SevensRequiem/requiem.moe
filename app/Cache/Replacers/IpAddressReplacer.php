<?php

namespace App\Cache\Replacers;

use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Replacers\Replacer;

class IpAddressReplacer implements Replacer
{
    protected string $replacementString = '{{ip_address}}';

    public function prepareResponseToCache(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $response->setContent(str_replace(
            $ip,
            $this->replacementString,
            $response->getContent()
        ));
    }

    public function replaceInCachedResponse(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $response->setContent(str_replace(
            $this->replacementString,
            $ip,
            $response->getContent()
        ));
    }
}