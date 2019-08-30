<?php

declare(strict_types=1);

namespace Rinvex\Attributes\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:migrate:attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Rinvex Attributes Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Migrate rinvex/attributes:');
        $this->call('migrate', ['--step' => true, '--path' => 'vendor/rinvex/attributes/database/migrations']);
    }
}
