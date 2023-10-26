<?php

namespace App\Console\Commands\Users;

use App\Services\UserService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Command';

    /**
     * Execute the console command.
     */
    public function handle(UserService $userService): void
    {
        $this->info('Start Command.');
        $userService->testCommand();
        $this->info('End Command.');
    }
}
