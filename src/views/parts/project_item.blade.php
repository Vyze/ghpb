<div class="project_item">
    <div class="row">
        {{HTML::linkRoute('project',$repo['name'],
            array('owner'=>$repo['owner'], 'name'=>$repo['name']),
            array('class'=>'h3 col-md-6 link-color','title'=>$repo['name'],'target'=>'_self')
        )}}

        @if($repo['homepage'])
        {{HTML::link($repo['homepage'],'homepage', array('class'=>'h4 col-md-2 text-right link-color','title'=>'to homepage','target'=>'_blank')  )}}
        @else
        <span class="col-md-2"></span>
        @endif

        @if($show_repo_user)
        {{HTML::linkRoute('user',$repo['owner'],
            array('name'=>$repo['owner']),
            array('class'=>'h4 col-md-4 text-right link-color','title'=>'user '.$repo['owner'],'target'=>'_self')
        )}}
        @endif

    </div>
    <div class="row">
        {{$repo['description']}}
    </div>
    <div class="row">
        <span class="col-md-6">Watchers: {{$repo['watchers']}}</span>
        <span class="col-md-3">Forks: {{$repo['forks']}}</span>
        @include('ghpb::parts.like_btn',array('el'=>$repo, 'like_class'=>'pull-right', 'uname'=>$repo['name']))
    </div>
</div>