<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class QuestionFollowerController extends Controller
{
    /**
     * QuestionFollowerController constructor.
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * @param $question
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow($question)
    {
        Auth::user()->followThis($question);
        return back();
    }
}
