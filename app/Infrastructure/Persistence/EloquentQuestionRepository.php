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
    public function questions(): array
    {
        $questionsCollection = $this->getTableQueryBuilder()->get()->all();

        return array_map(function (\stdClass $question) {
            return Question::createFromStdClass($question);
        }, $questionsCollection);
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
        if ($question->id === null) {
            $this->getTableQueryBuilder()
                ->insert(
                    [
                        'body' => $question->body,
                        'answer' => $question->answer,
                        'is_answered' => $question->is_answered
                    ]
                );
        } else {
            $this->getTableQueryBuilder()
                ->where('id', $question->id)
                ->update([
                    'body' => $question->body,
                    'answer' => $question->answer,
                    'is_answered' => $question->is_answered
                ]);
        }
    }
}
