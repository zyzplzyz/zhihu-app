<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Naux\Mail\SendCloudTemplate;
use Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','confirmation_token','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public  function owns(Model $model)
    {
        return $this->id == $model->user_id;
    }

    public function followers()
    {
        return $this->belongsToMany(Question::class,'followers')->withTimestamps();
    }

    public function followThis($question)
    {
        return $this->followers()->toggle($question);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function followed($question)
    {
        return !! $this->followers()->where('question_id',$question)->count();
    }

    public function sendPasswordResetNotification($token)
    {
        $data = ['url' => url('password/reset', $token)];
        $template = new SendCloudTemplate('zhihu_app_password_reset', $data);
        Mail::raw($template, function ($message) {
            $message->from('578275252@qq.com', 'zhihu');
            $message->to($this->email);
        });

    }
}
