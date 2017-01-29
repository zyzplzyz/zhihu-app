<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests\StoreAnswersRequest;
use Auth;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    public function store(storeAnswersRequest $request,$question)
    {
        $data = [
            'user_id' => Auth::id(),
            'question_id' => $question,
            'body' => $request->get('body')
        ];
        $answer = Answer::create($data);
        $answer->question()->increment('answers_count');
        return back();
    }
}
