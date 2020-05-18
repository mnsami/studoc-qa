<?php
declare(strict_types=1);

namespace App\Repositories\Question;

use App\Question;
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
    public function findById(int $id): ?Question
    {
        return Question::find($id);
    }
}
