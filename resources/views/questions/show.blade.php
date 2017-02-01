@extends('layouts.app')
@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$question->title}}
                        @foreach($question->topics as $topic)
                            <a class="topic" href="/topic/{{$topic->id}}">{{$topic->name}}</a>
                            @endforeach
                    </div>
                    <div class="panel-body">
                        {!! $question->body !!}
                    </div>
                    <div class="actions">
                        @if(Auth::check() && Auth::user()->owns($question))
                            <span class="edit"><a href="/questions/{{$question->id}}/edit">编辑</a></span>
                            <form class="delete-form" action="/questions/{{$question->id}}" method="post">
                                {{method_field('DELETE')}}
                                {!! csrf_field() !!}
                                <button class="button is-naked delete-button">删除</button>
                            </form>
                            @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading question-follower">
                        <h2>{{$question->followers_count}}</h2>
                        <span >关注者</span>
                    </div>
                    <div class="panel-body">
                        {{--<a href="/questions/{{$question->id}}/follower"--}}
                           {{--class="btn btn-default {{Auth::user()->followed($question->id) ? 'btn-success':''}} ">--}}
                            {{--{{Auth::user()->followed($question->id) ? '已关注':'关注问题'}}--}}
                        {{--</a>--}}
                        <question-follower-button question="{{$question->id}}"></question-follower-button>
                        <a href="#editor" class="btn btn-primary">撰写答案</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$question->answers_count}}个答案
                    </div>
                    <div class="panel-body">
                        @foreach($question->answers as $answer)
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img width="36px" src="{{$answer->user->avatar}}"
                                             alt="{{$answer->user->name}}">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <a href="/user/{{$answer->id}}">
                                        {{$question->user->name}}
                                    </a>
                                    <h4 class="media-heading">
                                        {!! $answer->body !!}
                                    </h4>

                                </div>
                            </div>
                        @endforeach
                        @if(Auth::check())
                        <form action="/questions/{{$question->id}}/answer" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body">描述</label>
                                <script id="container" name="body" type="text/plain" style="height:120px">
                                    {!! old('body') !!}
                                </script>
                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success pull-right" >提交答案</button>
                        </form>
                            @else
                            <a href="/login" class="btn btn-block btn-success">登录提交答案</a>
                            @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@section('js')
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', Laravel.csrfToken); // 设置 CSRF token.
        });
    </script>
@endsection
@endsection
