<?php

namespace App\Jobs;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class IdentifyEngine implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        #[WithoutRelations]
        public Enrollment $enrollment,
    )
    {
        $this->enrollment->engineIdentifications()->where('script_version', $this->VERSION)->delete();
    }

    public int $VERSION = 4;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Storage::put('elements.json', $this->enrollment->html_fingerprint);
        Storage::put('api.json', $this->enrollment->api_fingerprint);
        $result = Process::path(storage_path('app'))->run('python3 identify_v' . $this->VERSION . '.py');
        if ($result->failed()) dd($result->errorOutput());

        $this->readCSVToDb(true);
        $this->readCSVToDb(false);

    }

    public function readCSVToDb(bool $isEngine): void
    {
        if ($isEngine){
            $fileContents = file(storage_path('app/engine.csv'));
        }else{
            $fileContents = file(storage_path('app/browser.csv'));
        }
        $rank = 0;
        foreach ($fileContents as $line) {
            if (!$rank){
                $rank++;
                continue;
            }
            $data = str_getcsv($line);
            $this->enrollment->engineIdentifications()->create([
                'script_version' => $this->VERSION,
                'rank' => $rank,
                'is_engine' => $isEngine,
                'version' => $data[0],
                'votes_for' => $data[1],
                'votes_against' => $data[2],
                'votes_net' => $data[3],
                'votes_total' => $data[4],
                'votes_ratio' => $data[5],
                'release_date' => $data[6]
            ]);
            $rank++;
        }
    }
}
