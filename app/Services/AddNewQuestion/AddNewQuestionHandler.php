<?php
declare(strict_types=1);

namespace App\Services\AddNewQuestion;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Model\QuestionRepository;
use App\Exceptions\SorryWrongCommand;
use App\Infrastructure\Shared\Command;
use App\Infrastructure\Shared\CommandHandler;
use App\Infrastructure\Shared\DataTransformer;
use App\Infrastructure\Shared\EmptyDto;

class AddNewQuestionHandler implements CommandHandler
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
        return AddNewQuestionCommand::class;
    }

    /**
     * @param AddNewQuestionCommand $command
     * @throws SorryWrongCommand
     */
    public function handle(Command $command): DataTransformer
    {
        $this->assertItHandlesCommand($command);

        $this->questionRepository->save(
            Question::createFromData(
                $command->question(),
                $command->answer()
            )
        );

        return new EmptyDto();
    }

    /**
     * @inheritDoc
     * @throws SorryWrongCommand
     */
    public function assertItHandlesCommand(Command $command)
    {
        if (!$command instanceof AddNewQuestionCommand) {
            throw new SorryWrongCommand('Passed wrong command to handle.');
        }
    }
}
