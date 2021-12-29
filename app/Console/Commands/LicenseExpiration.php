<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LicenseExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:license_expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loops through license table and marks expired licenses as expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $licenses = License::where(['expireable' => true, 'status' => 'Active'])->get();
        foreach ($licenses as $license) {
            if ($license->expires_at < now()) {
                $license->status = 'Expired';
                $license->save();
                Log::info("Expired License " . $license->key);
            }
        }
    }
}
