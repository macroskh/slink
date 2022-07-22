<?php

namespace App\Http\Services\LinkGenerators;

interface LinkGenerator
{
    /**
     * @param string $origin
     * @param int $expiration In minutes
     * @param int|null $max_follows null - no limit
     * @return string
     */
    public function shorten(string $origin, int $expiration, ?int $max_follows): string;
}
