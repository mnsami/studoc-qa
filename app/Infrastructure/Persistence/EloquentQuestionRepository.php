<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Model\QuestionRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EloquentQuestionRepository implements QuestionRepository, EloquentRepository
{
    /**
     * @inheritDoc
     */
    public function getTableQueryBuilder(): Builder
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
    public function save(Question $question): Question
    {
        if ($question->id === null) {
            $this->getTableQueryBuilder()
                ->insert(
                    [
                        'body' => $question->body,
                        'model_answer' => $question->model_answer,
                    ]
                );
        }

        $this->getTableQueryBuilder()
            ->where('id', $question->id)
            ->update([
                'body' => $question->body,
                'model_answer' => $question->model_answer,
            ]);

        return $question->refresh();
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->getTableQueryBuilder()->count();
    }
}
