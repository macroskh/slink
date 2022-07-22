<?php

namespace App\Http\Services;

use App\Http\Services\LinkGenerators\LinkGenerator;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
class LinkService
{

    public function __construct(
        private LinkGenerator $generator
    ) {
    }

    /**
     * @param string $origin
     * @param int $expiration
     * @param int $max_follows
     * @return string
     */
    public function shorten(string $origin, int $expiration, int $max_follows): string
    {
        return $this->generator->shorten(
            $origin,
            $expiration,
            $max_follows ?: null
        );
    }

    /**
     * @return mixed
     */
    public static function cleanUp()
    {
        return Link::query()
            ->where('expired_at', '<', Carbon::now())
            ->orWhere('max_follows', 0)
            ->delete();
    }

    /**
     * @param string $shortcut
     * @return string|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function follow(string $shortcut)
    {
        return DB::transaction(function () use ($shortcut) {
            /** @var Link $link */
            $link = Link::query()
                ->where('shortcut', $shortcut)
                ->where('expired_at', '>', Carbon::now())
                ->where(function (Builder $query) {
                    return $query->where('max_follows', '>', 0)
                        ->orWhereNull('max_follows');
                })
                ->lockForUpdate()
                ->firstOrFail();

            if ($link->max_follows !== null) {
                $link->update([
                    'max_follows' => $link->max_follows - 1
                ]);
            }

            return $link->origin;
        });
    }
}
