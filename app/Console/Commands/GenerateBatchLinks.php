<?php

namespace App\Console\Commands;

use App\Http\Services\LinkGenerators\Pregenerated;
use App\Http\Services\LinkService;
use Illuminate\Console\Command;

class GenerateBatchLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shortlinks:pregenerate {count} {--c|clear : Clean up before generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pregenerate links';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('clear')) {
            LinkService::cleanUp();
        }

        $generator = new Pregenerated();
        $generator->preGenerateUniqueLinks($this->argument('count'));

        return 0;
    }
}
