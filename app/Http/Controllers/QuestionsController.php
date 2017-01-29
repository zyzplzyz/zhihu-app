<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionrequest;
use App\Question;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Auth;


class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::publish()->latest('updated_at')->with('user')->get();
        return view('questions.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.make');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionrequest $request)
    {
        $topics = $this->rewriteTopic($request->get('topics'));

        $data = [
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'user_id' => Auth::id(),
        ];

        $question = Question::create($data);
        $question->topics()->attach($topics);
        return redirect()->route('questions.show',[$question->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::where('id',$id)->with('topics','answers')->first();
        return view('questions.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
         if(Auth::user()->owns($question)){
             return view('questions.edit',compact('question'));
         }
         return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreQuestionrequest $request, $id)
    {
        $question = Question::find($id);
        $topics = $this->rewriteTopic($request->get('topics'));
        $question->update([
            'title' => $request->get('title'),
            'body' => $request->get('body')
        ]);
        $question->topics()->sync($topics);
        return redirect()->route('questions.show',[$question->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);
        if(Auth::user()->owns($question)){
            $question->delete();
            return redirect('/');
        }
        return back();

    }


    public function rewriteTopic($topics)
    {
        return collect($topics)->map(function ($topic){
            if(is_numeric($topic)){
                $data = Topic::find($topic);
                if($data){
                    $data->increment('questions_count');
                    return $topic;
                }else{
                    $data_topic = Topic::create(['name'=>(string)$topic,'questions_count'=>1]);
                    return $data_topic->id;
                }
            }
            $new_topic = Topic::create(['name'=>$topic,'questions_count'=>1]);
            return $new_topic->id;
        })->toArray();
    }
}
