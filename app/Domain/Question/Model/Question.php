<?php

namespace App\Domain\Question\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Get answer record associated with a question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answer()
    {
        return $this->hasOne('App\Domain\Question\Model\Answer');
    }
}
