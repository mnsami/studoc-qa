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
    private $viewAllQuestionsCommandHandler;

    /** @var GetPracticeQuestionByIdCommandHandler */
    private $getPracticeQuestionByIdCommandHandler;

    /** @var SubmitQuestionAnswerCommandHandler */
    private $submitQuestionAnswerCommandHandler;

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
        $this->viewAllQuestionsCommandHandler = $viewAllQuestionsHandler;
        $this->getPracticeQuestionByIdCommandHandler = $getPracticeQuestionByIdHandler;
        $this->submitQuestionAnswerCommandHandler = $submitQuestionAnswerHandler;
    }

    /**
     * Print all question
     * @throws \App\Exceptions\SorryWrongCommand
     */
    protected function printAllQuestions(): void
    {
        /** @var AllQuestionsDto $questions */
        $questions = $this->viewAllQuestionsCommandHandler->handle(new ViewAllQuestionsCommand());
        $this->line($this->writePaddedString("\nList of all available questions for practice.\n"));
        $this->tablize($questions);

        $this->showCurrentProgress($questions);
    }

    protected function tablize(AllQuestionsDto $allQuestionsDto): void
    {
        $fields = $allQuestionsDto->fields();

        $records = array_map(function (array $question) {
            return [
                $question['id'],
                $question['body'],
                $question['answer']? $question['answer']['answer'] : null,
                $question['answer']? $question['answer']['is_correct'] : null
            ];
        }, $allQuestionsDto->toArray());

        $this->table($fields, $records);
    }

    /**
     * Print user progress
     * @param AllQuestionsDto $allQuestionsDto
     */
    protected function showCurrentProgress(AllQuestionsDto $allQuestionsDto): void
    {
        $answeredQuestions = array_filter(
            $allQuestionsDto->toArray(),
            function (array $question) {
                return $question['answer'] ? true : false;
            }
        );


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
                    if ($this->handleTerminationInputChoice($this->handlePracticeQuestion()) === self::QUIT_EXIT_CODE) {
                        return self::QUIT_EXIT_CODE;
                    }
                    break;
                default:
                    return $this->handleTerminationInputChoice($choice);
                    break;
            }
        }

        return self::SUCCESS_EXIT_CODE;
    }

    protected function handlePracticeQuestion(): string
    {
        do {
            $questionId = trim($this->ask('Select a question by entering its Id'));
        } while ($questionId === '');

        if (!$this->shouldQuit($questionId) && !$this->shouldCancel($questionId)) {
            try {
                /** @var GetPracticeQuestionDto $questionDto */
                $questionDto = $this->getPracticeQuestionByIdCommandHandler
                    ->handle(
                        new GetPracticeQuestionByIdCommand($questionId)
                    );

                $this->askQuestion($questionDto);
            } catch (KernelException $e) {
                $this->error($e->getMessage());
            }
        }

        return $questionId;
    }

    private function askQuestion(GetPracticeQuestionDto $questionDto): string
    {
        $question = $questionDto->toArray();
        do {
            $answer = trim($this->ask('Question: ' . $question['body']));
        } while ($answer === '');


        if (!$this->shouldQuit($answer) && !$this->shouldCancel($answer)) {
            try {
                /** @var SubmitQuestionAnswerResultDto $submitAnswerResultDto */
                $submitAnswerResultDto = $this->submitQuestionAnswerCommandHandler
                    ->handle(
                        new SubmitQuestionAnswerCommand($answer, $question['id'])
                    );
            } catch (KernelException $e) {
                $this->error($e->getMessage());
            }

            $this->showAnswerResult($submitAnswerResultDto);
        }

        return $answer;
    }

    /**
     * @param SubmitQuestionAnswerResultDto $submitQuestionAnswerResultDto
     */
    protected function showAnswerResult(SubmitQuestionAnswerResultDto $submitQuestionAnswerResultDto)
    {
        if ($submitQuestionAnswerResultDto->isCorrectResult()) {
            $this->info("Your submitted answer is correct !\n");
        } else {
            $this->warn("Sorry, you submitted a wrong answer :(\n");
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
