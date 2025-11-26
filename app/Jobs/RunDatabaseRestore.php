<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class RunDatabaseRestore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Queueable;

    public $name;
    /**
     * Create a new job instance.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dd($this->name);
        Artisan::call('snapshot:load', [
            'name' => $this->name,
        ]);
    }
}
