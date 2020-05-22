<?php
declare(strict_types=1);

namespace App\Services\SubmitQuestionAnswer;

use App\Domain\Question\Model\Answer;
use App\Infrastructure\Shared\DataTransformer;

class SubmitQuestionAnswerResultDto implements DataTransformer
{
    /** @var Answer */
    private $answer;

    /**
     * SubmitQuestionAnswerResultDto constructor.
     * @param Answer $answer
     */
    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->answer->id,
            'question_id' => $this->answer->question_id,
            'answer' => $this->answer->answer,
            'is_correct' => $this->answer->is_correct
        ];
    }
}
