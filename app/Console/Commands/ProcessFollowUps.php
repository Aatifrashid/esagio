<?php

namespace App\Console\Commands;

use App\Services\FollowUp\FollowUpEngine;
use Illuminate\Console\Command;

class ProcessFollowUps extends Command
{
    protected $signature = 'followups:process';

    protected $description = 'Process due follow-up sequences and send queued messages';

    public function handle(FollowUpEngine $engine): int
    {
        $this->info('Processing follow-ups...');

        $count = $engine->processDueFollowUps();

        $this->info("Processed {$count} follow-up message(s).");

        return self::SUCCESS;
    }
}
