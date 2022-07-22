<?php

namespace App\Console\Commands;

use App\Http\Services\LinkService;
use Illuminate\Console\Command;

class CleanUpLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shortlinks:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up invalid links';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        LinkService::cleanUp();

        return 0;
    }
}
