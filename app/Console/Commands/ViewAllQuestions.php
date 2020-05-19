<?php

namespace App\Console\Commands;

use App\Console\InteractiveConsoleCommand;
use App\Domain\Question\Model\Question;
use App\Services\AddNewQuestion\AddNewQuestionHandler;
use App\Services\ViewAllQuestions\ViewAllQuestionsCommand;
use App\Services\ViewAllQuestions\ViewAllQuestionsHandler;
use Illuminate\Console\Command;

class ViewAllQuestions extends InteractiveConsoleCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:view-all-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive command line to view all stored questions.';

    /** @var ViewAllQuestionsHandler */
    private $viewAllQuestionsHandler;

    /**
     * Create a new command instance.
     * @param ViewAllQuestionsHandler $allQuestionsHandler
     */
    public function __construct(
        ViewAllQuestionsHandler $allQuestionsHandler
    ) {
        parent::__construct();
        $this->viewAllQuestionsHandler = $allQuestionsHandler;
        $this->running = true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while ($this->running) {
            $this->showMenu();

            $questions = $this->viewAllQuestionsHandler->handle(new ViewAllQuestionsCommand());
            $this->table(Question::COLUMNS, $questions->toArray());
            die;
        }
    }

    /**
     * @inheritDoc
     */
    protected function showMenu(): void
    {
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('-', '-')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders(' ', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('View All questions', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders(' ', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('-', '-')
        );
    }
}
