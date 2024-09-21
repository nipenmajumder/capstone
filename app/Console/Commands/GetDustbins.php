<?php

namespace App\Console\Commands;

use App\Events\DustbinDataRetrieved;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;

class GetDustbins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-dustbins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $database = Firebase::database();

        try {
            $reference = $database->getReference('dustbins');
//            $snapshot = $reference->getSnapshot();
            // Get the value of the snapshot
            $query = $reference->orderByKey()->limitToLast(5);

            // Get the snapshot of the query
            $snapshot = $query->getSnapshot();
            $value = $snapshot->getValue();
            Log::info('Dispatching DustbinDataRetrieved event', ['data' => $value]);
            DustbinDataRetrieved::dispatch($value);
            // Dump the value
        } catch (DatabaseException $e) {
            echo $e->getMessage();
        }
    }
}
