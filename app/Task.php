<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'TruthOrActionQuestions';
//    public $category_id;
//    public $type;
//    public $question;
//    public $active;
    public function TaskCategories()
    {
        return $this->belongsToMany('TaskCategory', 'TruthOrActionCategories');
    }

}
