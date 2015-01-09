@extends('ghpb::layout.main')

@section('content')

<div class="proj_info w0">
    <div class="pull-left col-md-3">
        <div class="avatar">
        <img src="{{$properties['avatar_url'] or asset('img/no_avatar.png')}}" class="pull-left"/>
            <div class="container">
                @include('ghpb::parts.like_btn',array('el'=>$properties, 'like_class'=>'pull-left', 'uname'=>$properties['login']))
            </div>
        </div>
    </div>

    <div class="col-sm-8 col-md-8">
        <div class="row">
            <h3>{{$properties['name']}}</h3>
        </div>
        <div class="row">
            <span class="h4">github username:</span><span>{{$properties['login']}}</span>
        </div>
        @if($properties['company'])
        <div class="row">
            <span class="h4">Company:</span><span>{{$properties['company']}}</span>
        </div>
        @endif
        @if($properties['blog'])
        <div class="row">
            {{--<span class="h4">Blog:</span><span>{{HTML::link($properties['blog'], $properties['blog'], array('title'=>$properties['blog']));}}</span>--}}
            <span class="h4">Blog:</span>{{HTML::link($properties['blog'], $properties['blog'], array('title'=>$properties['blog']));}}
        </div>
        @endif
        <div class="row">
            <span class="h4">Followers:</span><span>{{$properties['followers']}}</span>
        </div>
    </div>


</div>

<h3>Repositories:</h3>
@foreach($repositories as $repo)
    @include('ghpb::parts.project_item',array('repo'=>$repo,'show_repo_user'=>false))
@endforeach
@stop