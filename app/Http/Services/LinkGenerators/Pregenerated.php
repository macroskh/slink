<?php

namespace App\Http\Services\LinkGenerators;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Pregenerated implements LinkGenerator
{
    public function shorten(string $origin, int $expiration, ?int $max_follows): string
    {
        return DB::transaction(function () use ($origin, $expiration, $max_follows) {
            /** @var Link $link */
            $link = Link::query()
                ->whereNull('expired_at')
                ->lockForUpdate()
                ->first();

            $link->update([
                'origin' => $origin,
                'expired_at' => Carbon::now()->addMinutes($expiration),
                'max_follows' => $max_follows,
            ]);

            return $link->shortcut;
        }, 5);
    }

    public function preGenerateUniqueLinks(int $count): bool
    {
        $shortcuts = [];
        $batchCount = $count;

        // generate random unique tokens
        do {
            $generated = $this->generateShortcuts($batchCount);
            $shortcuts = array_unique(array_merge($generated, $shortcuts));
            $batchCount = $count - count($shortcuts);

            if (!$batchCount) {
                $exists = DB::table('links')->select('shortcut')
                    ->whereIn('shortcut', $shortcuts)
                    ->get();

                $shortcuts = array_diff($shortcuts, $exists->all());
                // TODO: solve potential collisions

            }
        } while ($batchCount);

        $now = Carbon::now()->toDateTimeString();

        return DB::table('links')->insert(array_map(function ($shortcut) use ($now) {
            return [
                'shortcut' => $shortcut,
                'origin' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $shortcuts));
    }

    /**
     * @param int $count
     * @return array<string>
     */
    private function generateShortcuts(int $count): array
    {
        $shortcuts = [];

        for ($i = 0; $i < $count; $i++) {
            $shortcuts[] = Str::random(config('links.length'));
        }

        return $shortcuts;
    }
}
