@extends('ghpb::layout.main')

@section('content')
<h4>Searching for:</h4>
<p>'  {{$properties['query']}}  '</p>

{{--{{dd($repositories)}}--}}
<div class="container search_list">
@forelse($repositories as $repo)
    @include('ghpb::parts.project_item',array('repo'=>$repo,'show_repo_user'=>$properties['show_repo_user']))
@empty
    <div class="text-center">
    <h2><span class="glyphicon glyphicon-search"></span></h2>
    <h3>We couldn't find any repositories matching your request</h3>
    </div>
@endforelse
</div>
@stop