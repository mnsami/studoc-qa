<?php
declare(strict_types=1);

namespace App\Domain\Question\Model;

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
     * @param string $id
     * @return Question|null
     */
    public function findById(string $id): ?Question;

    /**
     * Add new question
     *
     * @param Question $question
     * @return Question
     */
    public function save(Question $question): Question;
}
