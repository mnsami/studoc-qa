<?php
declare(strict_types=1);

namespace App\Repositories\Question;

use App\Question;
use Illuminate\Database\Eloquent\Collection;

interface QuestionRepository
{
    /**
     * Return all questions
     *
     * @return Collection
     */
    public function questions(): Collection;

    /**
     * Return a question by Id
     *
     * @param int $id
     * @return Question|null
     */
    public function findById(int $id): ?Question;
}
