<?php

namespace App\Http\Services\LinkGenerators;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RunTime implements LinkGenerator
{
    public function shorten(
        string $origin,
        int $expiration,
        ?int $max_follows
    ): string {
        // TODO: solve potential collisions
        $shortcut = Str::random(config('links.length'));
        $expired_at = Carbon::now()->addMinutes($expiration);

        $link = new Link([
            'shortcut' => $shortcut,
            'origin' => $origin,
            'expired_at' => $expired_at,
            'max_follows' => $max_follows
        ]);

        $link->save();

        return $shortcut;
    }
}
