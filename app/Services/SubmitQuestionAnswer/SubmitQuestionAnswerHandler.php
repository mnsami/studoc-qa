<?php
declare(strict_types=1);

namespace App\Services\SubmitQuestionAnswer;

use App\Domain\Question\Model\Answer;
use App\Domain\Question\Model\AnswerRepository;
use App\Domain\Question\Model\QuestionRepository;
use App\Exceptions\SorryQuestionIsAlreadyAnswered;
use App\Exceptions\SorryQuestionNotFound;
use App\Exceptions\SorryWrongCommand;
use App\Infrastructure\Shared\Command;
use App\Infrastructure\Shared\CommandHandler;
use App\Infrastructure\Shared\DataTransformer;
use App\Infrastructure\Shared\EmptyDto;

class SubmitQuestionAnswerHandler implements CommandHandler
{
    /** @var AnswerRepository */
    private $answerRepository;

    /** @var QuestionRepository */
    private $questionRepository;

    /**
     * SubmitQuestionAnswerHandler constructor.
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


    public function handles(): string
    {
        return SubmitQuestionAnswerCommand::class;
    }

    /**
     * @param SubmitQuestionAnswerCommand $command
     * @return DataTransformer
     * @throws SorryWrongCommand
     * @throws SorryQuestionNotFound
     * @throws SorryQuestionIsAlreadyAnswered
     */
    public function handle(Command $command): DataTransformer
    {
        $this->assertItHandlesCommand($command);

        $questionId = $command->questionId();
        $userAnswer = $command->answer();

        $question = $this->questionRepository->findById($questionId);
        if ($question === null) {
            throw new SorryQuestionNotFound("Question with Id {$questionId} not found.");
        }

        $answer = $this->answerRepository->findByQuestionId($questionId);
        if ($answer !== null) {
            throw new SorryQuestionIsAlreadyAnswered(
                'You already practiced this question: .' . $questionId
            );
        }

        $answer = new Answer();
        $answer->questionId = $questionId;
        $answer->answer = $userAnswer;
        $answer->isCorrect = $userAnswer === $question->answer;

        $this->answerRepository->save($answer);

        return new EmptyDto();
    }

    /**
     * @inheritDoc
     * @throws SorryWrongCommand
     */
    public function assertItHandlesCommand(Command $command)
    {
        if (!$command instanceof SubmitQuestionAnswerCommand) {
            throw new SorryWrongCommand('Passed wrong command to handle.');
        }
    }
}
