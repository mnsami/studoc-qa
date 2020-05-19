<?php
declare(strict_types=1);

namespace App\Services\ViewAllQuestions;

use App\Domain\Question\Model\Question;
use App\Infrastructure\Shared\DataTransformer;

class AllQuestionsDto implements DataTransformer
{
    private $questions;

    public function __construct(Question ...$questions)
    {
        $this->questions = $questions;
    }

    public function toArray(): array
    {
        return array_map(function (Question $question) {
            return [
                'id' => $question->id,
                'question' => $question->question,
                'answer' => $question->answer,
            ];
        }, $this->questions);
    }
}
