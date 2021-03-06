<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'user_questions';
    protected $fillable = ['title','body','user_id'];

    public function topics()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class,'followers')->withTimestamps();
    }



    public function scopePublish($query)
    {
        return $query->where('is_hidden','F');
    }
}
