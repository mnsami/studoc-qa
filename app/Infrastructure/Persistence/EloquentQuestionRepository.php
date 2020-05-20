<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Model\QuestionRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentQuestionRepository implements QuestionRepository
{
    /**
     * @inheritDoc
     */
    public function questions(): Collection
    {
        return Question::all();
    }

    /**
     * @inheritDoc
     */
    public function findById(string $id): ?Question
    {
        return Question::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function save(Question $question): Question
    {
        $question->save();

        return $question;
    }
}
