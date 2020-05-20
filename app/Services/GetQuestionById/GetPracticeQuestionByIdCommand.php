<?php
declare(strict_types=1);

namespace App\Services\GetQuestionById;

use App\Infrastructure\Shared\Command;

class GetPracticeQuestionByIdCommand implements Command
{
    /** @var string */
    private $questionId;

    /**
     * GetPracticeQuestionByIdCommand constructor.
     * @param string $questionId
     */
    public function __construct(string $questionId)
    {
        $this->questionId = $questionId;
    }

    public function questionId(): string
    {
        return $this->questionId;
    }
}
