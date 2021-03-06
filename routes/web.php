<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','QuestionsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('email/verify/{token}',['as'=>'send email','uses'=>'EmailController@verify']);
Route::resource('questions','QuestionsController',['name'=>[
    'create' => 'questions.create',
    'show' =>'questions.show'
]]);

Route::post('questions/{question}/answer','AnswersController@store');

Route::get('questions/{question}/follower','QuestionFollowerController@follow');
