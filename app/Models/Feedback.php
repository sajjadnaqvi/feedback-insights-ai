<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = ['user_id', 'comment', 'sentiment'];

    public function getUserNameAttribute()
    {
        // Avoid error if user is not loaded
        return $this->user ? $this->user->name : null;
    }

    public $appends = ['user_name'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
