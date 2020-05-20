<?php
declare(strict_types=1);

namespace App\Services\GetQuestionById;

use App\Domain\Question\Model\QuestionRepository;
use App\Exceptions\ErrorExceptionDto;
use App\Exceptions\SorryWrongCommand;
use App\Infrastructure\Shared\Command;
use App\Infrastructure\Shared\CommandHandler;
use App\Infrastructure\Shared\DataTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetQuestionByIdHandler implements CommandHandler
{
    /** @var QuestionRepository */
    private $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @inheritDoc
     */
    public function handles(): string
    {
        return GetPracticeQuestionByIdCommand::class;
    }

    /**
     *
     * @param GetPracticeQuestionByIdCommand $command
     * @return DataTransformer
     * @throws SorryWrongCommand
     */
    public function handle(Command $command): DataTransformer
    {
        $this->assertItHandlesCommand($command);

        $question = $this->questionRepository->findById($command->questionId());

        return new GetQuestionDto($question);
    }

    /**
     * @inheritDoc
     * @throws SorryWrongCommand
     */
    public function assertItHandlesCommand(Command $command)
    {
        if (!$command instanceof GetPracticeQuestionByIdCommand) {
            throw new SorryWrongCommand('Passed wrong command to handle.');
        }
    }
}
