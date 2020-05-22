<?php
declare(strict_types=1);

namespace App\Services\ResetAllAnswers;

use App\Domain\Question\Model\AnswerRepository;
use App\Exceptions\SorryWrongCommand;
use App\Infrastructure\Shared\Command;
use App\Infrastructure\Shared\CommandHandler;
use App\Infrastructure\Shared\DataTransformer;
use App\Infrastructure\Shared\EmptyDto;

class ResetAllAnswersCommandHandler implements CommandHandler
{
    /** @var AnswerRepository */
    private $answerRepository;

    public function handles(): string
    {
        return ResetAllAnswersCommand::class;
    }

    /**
     * @param ResetAllAnswersCommand $command
     * @return DataTransformer
     * @throws SorryWrongCommand
     */
    public function handle(Command $command): DataTransformer
    {
        $this->assertItHandlesCommand($command);

        $this->answerRepository->reset();

        return new EmptyDto();
    }

    /**
     * @inheritDoc
     * @throws SorryWrongCommand
     */
    public function assertItHandlesCommand(Command $command)
    {
        if (!$command instanceof ResetAllAnswersCommand) {
            throw new SorryWrongCommand('Passed wrong command to handle.');
        }
    }
}
