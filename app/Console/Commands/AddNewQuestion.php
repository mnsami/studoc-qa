<?php

namespace App\Console\Commands;

use App\Console\ConsoleStringFormatter;
use App\Console\InteractiveConsoleCommand;
use App\Services\AddNewQuestion\AddNewQuestionCommand;
use App\Services\AddNewQuestion\AddNewQuestionHandler;

class AddNewQuestion extends InteractiveConsoleCommand
{
    use ConsoleStringFormatter;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:add-new-question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive command line to add a new question and its answer.';

    /** @var AddNewQuestionHandler */
    private $addNewQuestionHandler;

    /**
     * Create a new command instance.
     *
     * @param AddNewQuestionHandler $addNewQuestionHandler
     */
    public function __construct(
        AddNewQuestionHandler $addNewQuestionHandler
    ) {
        parent::__construct();
        $this->addNewQuestionHandler = $addNewQuestionHandler;
        $this->running = true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->showMenu();

        while ($this->running) {

            $question = $this->promptQuestion();
            if ($this->shouldCancel($question)) {
                return self::CANCEL_EXIT_CODE;
            } elseif ($this->shouldQuit($question)) {
                $this->quit();
                return self::TERMINATE_EXIT_CODE;
            }

            $answer = $this->promptQuestionAnswer($question);
            if ($this->shouldCancel($answer)) {
                return self::CANCEL_EXIT_CODE;
            } elseif ($this->shouldQuit($answer)) {
                $this->quit();
                return self::TERMINATE_EXIT_CODE;
            }

            $this->addNewQuestionHandler->handle(
                new AddNewQuestionCommand($question, $answer)
            );
        }

        return self::SUCCESS_EXIT_CODE;
    }

    /**
     * * Prompt to ask user for the question's answer
     *
     * @param string $question
     * @return string
     */
    private function promptQuestionAnswer(string $question): string
    {
        do {
            $answer = trim($this->ask("Add the model answer for question {$question}"));
        } while ($answer === '');

        return $answer;
    }

    /**
     * Prompt to ask user for question body
     *
     * @return string
     */
    private function promptQuestion(): string
    {
        do {
            $question = trim($this->ask("Enter the question"));
        } while ($question === '');

        return $question;
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
            $this->writePaddedStringWithLeftRightBorders('Add New Question details', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders(' ', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('-', '-')
        );
    }
}
