<?php
declare(strict_types=1);

namespace App\Services\SubmitQuestionAnswer;

use App\Infrastructure\Shared\Command;

class SubmitQuestionAnswerCommand implements Command
{
    /** @var string */
    private $answer;

    /** @var string */
    private $questionId;

    /**
     * SubmitQuestionAnswerCommand constructor.
     * @param string $answer
     * @param string $questionId
     */
    public function __construct(string $answer, string $questionId)
    {
        $this->answer = $answer;
        $this->questionId = $questionId;
    }

    public function answer(): string
    {
        return $this->answer;
    }

    public function questionId(): string
    {
        return $this->questionId;
    }
}
