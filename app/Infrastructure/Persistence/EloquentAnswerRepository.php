<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Question\Model\Answer;
use App\Domain\Question\Model\AnswerRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EloquentAnswerRepository implements AnswerRepository, EloquentRepository
{
    /**
     * @inheritDoc
     */
    public function getTableQueryBuilder(): Builder
    {
        return DB::table(Answer::tableName());
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function save(Answer $answer): Answer
    {
        if ($answer->id === null) {
            $this->getTableQueryBuilder()
                ->insert([
                    'question_id' => $answer->question_id,
                    'answer' => $answer->answer,
                    'is_correct' => $answer->is_correct
                ]);
        }

        $this->getTableQueryBuilder()
            ->where('id', $answer->id)
            ->update([
                'question_id' => $answer->question_id,
                'answer' => $answer->answer,
                'is_correct' => $answer->is_correct
            ]);

        return $answer->refresh();
    }
}
