<?php
declare(strict_types=1);

namespace App\Domain\Question\Model;

interface AnswerRepository
{
    /**
     * Return question answer
     *
     * @param string $questionId
     * @return Answer|null
     */
    public function findByQuestionId(string $questionId): ?Answer;

    /**
     * Save answer to a question
     *
     * @param Answer $answer
     * @return void
     */
    public function save(Answer $answer): void;
}
