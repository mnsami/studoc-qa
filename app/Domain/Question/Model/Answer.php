<?php

namespace App\Domain\Question\Model;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo('App\Domain\Question\Model\Question');
    }
}
