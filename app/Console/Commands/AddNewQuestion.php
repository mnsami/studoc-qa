<?php

namespace App\Console\Commands;

use App\Console\ConsoleStringFormatter;
use App\Console\InteractiveConsoleCommand;
use App\Services\AddNewQuestion\AddNewQuestionCommand;
use App\Services\AddNewQuestion\AddNewQuestionCommandHandler;

class AddNewQuestion extends InteractiveConsoleCommand
{
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

    /** @var AddNewQuestionCommandHandler */
    private $addNewQuestionHandler;

    /** @const string */
    private const CMD_ADD_NEW = 'new';
    private const CMD_ADD_NEW_SHORT = 'n';
    private const CMD_ADD_CHOICE = 'Add New Question';

    /**
     * Create a new command instance.
     *
     * @param AddNewQuestionCommandHandler $addNewQuestionHandler
     */
    public function __construct(
        AddNewQuestionCommandHandler $addNewQuestionHandler
    ) {
        parent::__construct();
        $this->addNewQuestionHandler = $addNewQuestionHandler;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->running = true;

        while ($this->running) {

            $this->showMenu();

            $choice = $this->choice('Choose from menu below', $this->choices());

            switch ($choice) {
                case self::CMD_ADD_NEW:
                case self::CMD_ADD_NEW_SHORT:
                    $this->addNewQuestion();
                    break;
                default:
                    return $this->handleCommonInputChoice($choice);
                    break;
            }
        }

        return self::SUCCESS_EXIT_CODE;
    }

    private function addNewQuestion(): void
    {
        $question = $this->promptQuestion();
        if (!$this->shouldCancel($question) && !$this->shouldQuit($question)) {
            $answer = $this->promptQuestionAnswer($question);
            if (!$this->shouldCancel($answer) && !$this->shouldQuit($answer)) {
                $this->addNewQuestionHandler->handle(
                    new AddNewQuestionCommand($question, $answer)
                );
            }
        }
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

    /**
     * @inheritDoc
     */
    protected function commandChoices(): array
    {
        return [
            self::CMD_ADD_NEW => self::CMD_ADD_CHOICE
        ];
    }
}
