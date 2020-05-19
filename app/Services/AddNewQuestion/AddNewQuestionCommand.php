<?php
declare(strict_types=1);

namespace App\Services\AddNewQuestion;

use App\Infrastructure\Shared\Command;

class AddNewQuestionCommand implements Command
{
    /** @var string */
    private $question;

    /** @var string */
    private $answer;

    public function __construct(string $question, string $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public function answer(): string
    {
        return $this->answer;
    }

    public function question(): string
    {
        return $this->question;
    }
}
