<?php

namespace App\Console\Commands;

use App\Console\InteractiveConsoleCommand;
use App\Exceptions\KernelException;
use App\Services\GetPracticeQuestionById\GetPracticeQuestionByIdCommand;
use App\Services\GetPracticeQuestionById\GetPracticeQuestionByIdCommandHandler;
use App\Services\GetPracticeQuestionById\GetPracticeQuestionDto;
use App\Services\SubmitQuestionAnswer\SubmitQuestionAnswerCommand;
use App\Services\SubmitQuestionAnswer\SubmitQuestionAnswerCommandHandler;
use App\Services\SubmitQuestionAnswer\SubmitQuestionAnswerResultDto;
use App\Services\ViewAllQuestions\AllQuestionsDto;
use App\Services\ViewAllQuestions\ViewAllQuestionsCommand;
use App\Services\ViewAllQuestions\ViewAllQuestionsCommandHandler;

class PracticeQuestions extends InteractiveConsoleCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:practice-questions';

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

    /** @var ViewAllQuestionsCommandHandler */
    private $viewAllQuestionsHandler;

    /** @var GetPracticeQuestionByIdCommandHandler */
    private $getPracticeQuestionByIdHandler;

    /** @var SubmitQuestionAnswerCommandHandler */
    private $submitQuestionAnswerHandler;

    /**
     * Create a new command instance.
     * @param ViewAllQuestionsCommandHandler $viewAllQuestionsHandler
     * @param GetPracticeQuestionByIdCommandHandler $getPracticeQuestionByIdHandler
     * @param SubmitQuestionAnswerCommandHandler $submitQuestionAnswerHandler
     */
    public function __construct(
        ViewAllQuestionsCommandHandler $viewAllQuestionsHandler,
        GetPracticeQuestionByIdCommandHandler $getPracticeQuestionByIdHandler,
        SubmitQuestionAnswerCommandHandler $submitQuestionAnswerHandler
    ) {
        parent::__construct();
        $this->viewAllQuestionsHandler = $viewAllQuestionsHandler;
        $this->getPracticeQuestionByIdHandler = $getPracticeQuestionByIdHandler;
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

        $this->showCurrentProgress($questions);
    }

    /**
     * Print user progress
     * @param AllQuestionsDto $allQuestionsDto
     */
    protected function showCurrentProgress(AllQuestionsDto $allQuestionsDto): void
    {
        $answeredQuestions = array_filter($allQuestionsDto->toArray(),
            function (array $question) {
                return $question['isAnswered'];
            });

        $this->info('Your current progress is...');
        $progressBar = $this->output->createProgressBar(count($allQuestionsDto->toArray()));
        $progressBar->start();
        $progressBar->setProgress(count($answeredQuestions));
        $progressBar->clear();
        $progressBar->display();

        $this->line("\n");
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
        do {
            $questionId = trim($this->ask('Select a question by entering its Id'));
        } while ($questionId === '');

        if (!$this->shouldQuit($questionId) && !$this->shouldCancel($questionId)) {
            try {
                /** @var GetPracticeQuestionDto $questionDto */
                $questionDto = $this->getPracticeQuestionByIdHandler
                    ->handle(
                        new GetPracticeQuestionByIdCommand($questionId)
                    );

                $this->askQuestion($questionDto);
            } catch (KernelException $e) {
                $this->error($e->getMessage());
            }
        }
    }

    private function askQuestion(GetPracticeQuestionDto $questionDto)
    {
        $question = $questionDto->toArray();
        do {
            $answer = trim($this->ask('Question: ' . $question['body']));
        } while ($answer === '');


        if (!$this->shouldQuit($answer) && !$this->shouldCancel($answer)) {
            try{
                /** @var SubmitQuestionAnswerResultDto $submitAnswerResultDto */
                $submitAnswerResultDto = $this->submitQuestionAnswerHandler
                    ->handle(
                        new SubmitQuestionAnswerCommand($answer, $question['id'])
                    );
            } catch (KernelException $e) {
                $this->error($e->getMessage());
            }

            $this->showAnswerResult($submitAnswerResultDto);
        }
    }

    /**
     * @param SubmitQuestionAnswerResultDto $submitQuestionAnswerResultDto
     */
    protected function showAnswerResult(SubmitQuestionAnswerResultDto $submitQuestionAnswerResultDto)
    {
        if ($submitQuestionAnswerResultDto->isCorrectResult()) {
            $this->info('Your submitted answer is correct !');
        } else {
            $this->warn('Sorry, you submitted a wrong answer :(');
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
            $this->writePaddedStringWithLeftRightBorders('PracticeQuestions Arena', ' ')
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
