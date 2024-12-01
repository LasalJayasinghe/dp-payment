<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       Mail::to($this->argument('email'))->send(new TestEmail());
    }
}
