<?php

namespace App\Domain\Question\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    private const TABLE_NAME = 'answers';

    public static function tableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * Get question it belongs to
     *
     * @return BelongsTo
     */
    public function question()
    {
        return $this->belongsTo('App\Domain\Question\Model\Question');
    }

    /**
     * @param \stdClass $data
     * @return Answer
     */
    public static function createFromStdClass(\stdClass $data): Answer
    {
        $answer = new Answer();

        $answer->id = $data->id;
        $answer->answer = $data->answer;
        $answer->question_id = $data->question_id;
        $answer->is_correct = $data->is_correct;

        return $answer;
    }
}
