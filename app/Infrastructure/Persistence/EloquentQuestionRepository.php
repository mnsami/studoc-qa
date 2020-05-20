<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Model\QuestionRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentQuestionRepository implements QuestionRepository
{
    /**
     * @return Builder
     */
    private function getTableQueryBuilder(): Builder
    {
        return DB::table(Question::tableName());
    }

    /**
     * @inheritDoc
     */
    public function questions(): Collection
    {
        return $this->getTableQueryBuilder()->get();
    }

    /**
     * @inheritDoc
     */
    public function findById(string $id): ?Question
    {
        $question = $this->getTableQueryBuilder()
            ->find($id);

        if ($question !== null) {
            return Question::createFromStdClass($question);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function save(Question $question): void
    {
        $this->getTableQueryBuilder()
            ->insert(
                [
                    'question' => $question->question,
                    'answer' => $question->answer,
                    'is_answered' => $question->is_answered
                ]
            );
    }
}
