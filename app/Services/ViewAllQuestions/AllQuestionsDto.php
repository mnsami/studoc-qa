<?php
declare(strict_types=1);

namespace App\Services\ViewAllQuestions;

use App\Domain\Question\Model\Answer;
use App\Domain\Question\Model\Question;
use App\Infrastructure\Shared\DataTransformer;

class AllQuestionsDto implements DataTransformer
{
    /** @var Question[] */
    private $questions;

    public function __construct(Question ...$questions)
    {
        $this->questions = $questions;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_map(function (Question $question) {
            /** @var Answer $answer */
            $answer = $question->answer ?? null;

            return [
                'id' => $question->id,
                'body' => $question->body,
                'answer' => $answer ? $answer->toArray() : null
            ];
        }, $this->questions);
    }

    /**
     * Fields
     * @return array|string[]
     */
    public function fields(): array
    {
        return [
            'id',
            'body',
            'yourAnswer',
            'isCorrect'
        ];
    }
}
