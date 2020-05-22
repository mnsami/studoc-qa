<?php
declare(strict_types=1);

namespace App\Services\ViewAllQuestions;

use App\Domain\Question\Model\QuestionRepository;
use App\Exceptions\SorryWrongCommand;
use App\Infrastructure\Shared\Command;
use App\Infrastructure\Shared\CommandHandler;
use App\Infrastructure\Shared\DataTransformer;

class ViewAllQuestionsCommandHandler implements CommandHandler
{
    /** @var QuestionRepository */
    private $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function handles(): string
    {
        return ViewAllQuestionsCommand::class;
    }

    public function handle(Command $command): DataTransformer
    {
        $this->assertItHandlesCommand($command);

        $questions = $this->questionRepository->questions();

        return new AllQuestionsDto(...$questions);
    }

    public function assertItHandlesCommand(Command $command)
    {
        if (!$command instanceof ViewAllQuestionsCommand) {
            throw new SorryWrongCommand('Passed wrong command to handle.');
        }
    }
}
