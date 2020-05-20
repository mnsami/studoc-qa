<?php

namespace App\Console\Commands;

use App\Console\InteractiveConsoleCommand;
use App\Services\GetQuestionById\GetQuestionByIdCommand;
use App\Services\GetQuestionById\GetQuestionByIdHandler;
use App\Services\GetQuestionById\GetQuestionDto;
use App\Services\SubmitQuestionAnswer\SubmitQuestionAnswerCommand;
use App\Services\SubmitQuestionAnswer\SubmitQuestionAnswerHandler;
use App\Services\ViewAllQuestions\AllQuestionsDto;
use App\Services\ViewAllQuestions\ViewAllQuestionsCommand;
use App\Services\ViewAllQuestions\ViewAllQuestionsHandler;

class PracticeQuestion extends InteractiveConsoleCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:practice-question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive command line to practice a question given its Id.';

    /** @const string */
    private const CMD_PRACTICE = 'practice';
    private const CMD_PRACTICE_SHORT = 'p';
    private const CMD_PRACTICE_CHOICE = 'Start practicing';

    /** @var ViewAllQuestionsHandler */
    private $viewAllQuestionsHandler;

    /** @var GetQuestionByIdHandler */
    private $getQuestionByIdHandler;

    /** @var SubmitQuestionAnswerHandler */
    private $submitQuestionAnswerHandler;

    /**
     * Create a new command instance.
     * @param ViewAllQuestionsHandler $viewAllQuestionsHandler
     * @param GetQuestionByIdHandler $getQuestionByIdHandler
     * @param SubmitQuestionAnswerHandler $submitQuestionAnswerHandler
     */
    public function __construct(
        ViewAllQuestionsHandler $viewAllQuestionsHandler,
        GetQuestionByIdHandler $getQuestionByIdHandler,
        SubmitQuestionAnswerHandler $submitQuestionAnswerHandler
    ) {
        parent::__construct();
        $this->viewAllQuestionsHandler = $viewAllQuestionsHandler;
        $this->getQuestionByIdHandler = $getQuestionByIdHandler;
        $this->submitQuestionAnswerHandler = $submitQuestionAnswerHandler;
    }

    /**
     * Print all question
     */
    protected function printAllQuestions(): void
    {
        /** @var AllQuestionsDto $questions */
        $questions = $this->viewAllQuestionsHandler->handle(new ViewAllQuestionsCommand());
        $this->line($this->writePaddedString("\nList of all available questions for practice.\n"));
        $this->table($questions->fields(), $questions->toArray());
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
                case self::CMD_PRACTICE:
                case self::CMD_PRACTICE_SHORT:
                    $this->handlePracticeQuestion();
                    break;
                default:
                    return $this->handleCommonInputChoice($choice);
                    break;
            }
        }

        return self::SUCCESS_EXIT_CODE;
    }

    protected function handlePracticeQuestion()
    {
        $questionId = $this->ask('Select a question by entering its Id');

        if (!$this->shouldQuit($questionId) || !$this->shouldCancel($questionId)) {
            /** @var GetQuestionDto $questionDto */
            $questionDto = $this->getQuestionByIdHandler
                ->handle(
                    new GetQuestionByIdCommand($questionId)
                );

            $this->askQuestion($questionDto);
        }
    }

    private function askQuestion(GetQuestionDto $questionDto)
    {
        $question = $questionDto->toArray();
        $answer = $this->ask('Question: ' . $question['question']);

        if (!$this->shouldQuit($answer) || !$this->shouldCancel($answer)) {
            $this->submitQuestionAnswerHandler
                ->handle(
                    new SubmitQuestionAnswerCommand($answer, $question['id'])
                );
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
        $this->info(
            $this->writePaddedStringWithLeftRightBorders('Practice Arena', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders(' ', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('Here you can see list of all questions', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('to practice you need to select a question', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('by entering its Id.', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders(' ', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('***', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders(' ', ' ')
        );
        $this->line(
            $this->writePaddedStringWithLeftRightBorders('-', '-')
        );

        $this->printAllQuestions();
    }

    protected function commandChoices(): array
    {
        return [
            self::CMD_PRACTICE => self::CMD_PRACTICE_CHOICE
        ];
    }
}
