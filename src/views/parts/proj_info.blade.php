<div class="w0 proj_info">
<div class="row">
    <a title="{{$project['owner']['login']}}" class="h2" href="{{URL::route('user',array('name'=>$project['owner']['login']))}}">{{$project['owner']['login']}}</a><span class="h2">/{{$project['name']}}</span>
</div>
<div class="row">
    <span class="h4">Description:</span><span>{{$project['description']}}</span>
</div>
<div class="row">
    <span class="h4">Watchers:</span><span>{{$project['watchers_count']}}</span>
</div>
<div class="row">
    <span class="h4">Forks:</span><span>{{$project['forks_count']}}</span>
</div>
<div class="row">
    <span class="h4">homepage:</span><span><a href="{{$project['homepage']}}" title="go to homepage"  target="_blank">{{$project['homepage']}}</a></span>
</div>
<div class="row">
    <span class="h4">Github repo:</span><span><a href="{{$project['github_repo']}}" title="go to github repo" target="_blank">{{$project['github_repo']}}</a></span>
</div>
<div class="row">
    <span class="h4">Created at:</span><span>{{substr($project['created_at'],0,10)}}</span>
</div>
<div class="row">
    @include('ghpb::parts.like_btn',array('el'=>$project, 'like_class'=>'pull-left', 'uname'=>$project['name']))
</div>
</div>
