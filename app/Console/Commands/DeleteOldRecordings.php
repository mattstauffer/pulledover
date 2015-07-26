<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Services_Twilio;

class DeleteOldRecordings extends Command
{
    protected $signature = 'recordings:clean';

    protected $description = 'Delete all expired recordings.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Services_Twilio $twilio)
    {
        $recordings = $twilio->account->recordings->getIterator(0, 1000, [
            'DateCreated<' => Carbon::now()->subDays(2)->format('Y-m-d')
        ]);

        foreach ($recordings as $recording) {
            Log::info("Deleting recording {$recording->sid}.");
            $twilio->account->recordings->delete($recording->sid);
        }
    }
}
