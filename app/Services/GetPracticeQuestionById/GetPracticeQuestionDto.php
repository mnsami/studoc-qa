<?php
declare(strict_types=1);

namespace App\Services\GetPracticeQuestionById;

use App\Domain\Question\Model\Question;
use App\Infrastructure\Shared\DataTransformer;

class GetPracticeQuestionDto implements DataTransformer
{
    /** @var Question */
    private $question;

    /**
     * GetPracticeQuestionDto constructor.
     * @param Question $question
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->question->id,
            'body' => $this->question->body,
        ];
    }
}
