<?php

namespace App\Console\Commands;

use App\Services\ResetAllAnswers\ResetAllAnswersCommand;
use App\Services\ResetAllAnswers\ResetAllAnswersCommandHandler;
use Illuminate\Console\Command;

class ResetAnswers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var ResetAllAnswersCommandHandler */
    private $resetAllAnswersCommandHandler;

    /**
     * Create a new command instance.
     *
     * @param ResetAllAnswersCommandHandler $resetAllAnswersCommandHandler
     */
    public function __construct(ResetAllAnswersCommandHandler $resetAllAnswersCommandHandler)
    {
        parent::__construct();

        $this->resetAllAnswersCommandHandler = $resetAllAnswersCommandHandler;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \App\Exceptions\SorryWrongCommand
     */
    public function handle()
    {
        $choice = $this->confirm('This will reset all your practice answers progress');
        if ($choice) {
            $this->resetAllAnswersCommandHandler->handle(new ResetAllAnswersCommand());

            $this->info('Reset is done !');
        }

        $this->line('Bye!');
    }
}
