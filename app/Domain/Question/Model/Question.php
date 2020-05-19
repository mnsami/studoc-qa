<?php

namespace App\Domain\Question\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public const COLUMNS = [
        'id',
        'question',
        'answer'
    ];
}
