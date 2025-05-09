<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use App\Jobs\IdentifyEngine;

class DispatchIdentifyEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:identify-engine {enrollmentId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch the IdentifyEngine job for a given enrollment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enrollmentId = $this->argument('enrollmentId');

        // Fetch the Enrollment record
        $enrollment = Enrollment::find($enrollmentId);

        if (!$enrollment) {
            $this->error("Enrollment with ID {$enrollmentId} not found.");
            return Command::FAILURE;
        }

        // Dispatch the job
        IdentifyEngine::dispatch($enrollment);

        $this->info("IdentifyEngine job dispatched for Enrollment ID {$enrollmentId}.");
        return Command::SUCCESS;
    }
}
