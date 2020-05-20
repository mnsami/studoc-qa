<?php
declare(strict_types=1);

namespace App\Services\GetQuestionById;

use App\Domain\Question\Model\AnswerRepository;
use App\Domain\Question\Model\QuestionRepository;
use App\Exceptions\SorryQuestionIsAlreadyAnswered;
use App\Exceptions\SorryWrongCommand;
use App\Infrastructure\Shared\Command;
use App\Infrastructure\Shared\CommandHandler;
use App\Infrastructure\Shared\DataTransformer;

class GetPracticeQuestionByIdHandler implements CommandHandler
{
    /** @var QuestionRepository */
    private $questionRepository;

    /** @var AnswerRepository */
    private $answerRepository;

    /**
     * GetPracticeQuestionByIdHandler constructor.
     * @param AnswerRepository $answerRepository
     * @param QuestionRepository $questionRepository
     */
    public function __construct(
        AnswerRepository $answerRepository,
        QuestionRepository $questionRepository
    ) {
        $this->answerRepository = $answerRepository;
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
     * @throws SorryQuestionIsAlreadyAnswered
     */
    public function handle(Command $command): DataTransformer
    {
        $this->assertItHandlesCommand($command);

        $questionId = $command->questionId();
        $question = $this->questionRepository->findById($questionId);

        $answer = $this->answerRepository->findByQuestionId($questionId);
        if ($answer !== null) {
            throw new SorryQuestionIsAlreadyAnswered(
                'You already practiced this question: .' . $questionId
            );
        }

        return new GetPracticeQuestionDto($question);
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
