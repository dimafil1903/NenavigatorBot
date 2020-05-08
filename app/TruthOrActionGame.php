<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TruthOrActionGame extends Model
{


    protected $table = "TruthOrActionGame";
    protected $fillable = ['chat_id', 'players', 'status', 'used_questions', 'token', 'message_id'];
}
