@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach($questions as $question)
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img width="48px" src="{{$question->user->avatar}}" alt="{{$question->user->name}}">
                            </a>
                        </div>
                        <div class="media-body">
                            <a href="/questions/{{$question->id}}">
                                <h4 class="media-heading">
                                  {{$question->title}}
                                </h4>
                            </a>
                        </div>
                    </div>
                    @endforeach
            </div>
        </div>
    </div>
@endsection
