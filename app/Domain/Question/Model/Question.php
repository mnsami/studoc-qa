<?php

namespace App\Domain\Question\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    private const TABLE_NAME = 'questions';

    /**
     * Table name
     *
     * @return string
     */
    public static function tableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * Get answer record associated with a question
     *
     * @return HasOne
     */
    public function answer()
    {
        return $this->hasOne('App\Domain\Question\Model\Answer');
    }

    /**
     * @param \stdClass $data
     * @return Question
     */
    public static function createFromStdClass(\stdClass $data): Question
    {
        $question = new Question();

        $question->id = $data->id;
        $question->body = $data->body;
        $question->answer = $data->answer;
        $question->is_answered = $data->is_answered;

        return $question;
    }

    public static function createFromData(string $body, string $answer): Question
    {
        $questionModel = new Question();

        $questionModel->body = $body;
        $questionModel->answer = $answer;
        $questionModel->is_answered = false;

        return $questionModel;
    }
}
