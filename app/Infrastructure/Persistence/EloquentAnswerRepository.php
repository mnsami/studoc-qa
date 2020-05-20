<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Question\Model\Answer;
use App\Domain\Question\Model\AnswerRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EloquentAnswerRepository implements AnswerRepository
{
    /**
     * @return Builder
     */
    private function getTableQueryBuilder(): Builder
    {
        return DB::table(Answer::tableName());
    }

    public function findByQuestionId(string $questionId): ?Answer
    {
        $answer = $this->getTableQueryBuilder()
            ->where('question_id', '=', $questionId)
            ->first();

        if ($answer !== null) {
            return Answer::createFromStdClass($answer);
        }

        return null;
    }

    public function save(Answer $answer): int
    {
        return $this->getTableQueryBuilder()
            ->insertGetId([
                'question_id' => $answer->questionId,
                'answer' => $answer->answer,
                'is_correct' => $answer->isCorrect
            ]);
    }
}
