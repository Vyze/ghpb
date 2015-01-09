<h3>Contributors:</h3>
@foreach ($contributors as $contr)
<div class="contributor_item" style="display: inline-block;">
    {{HTML::linkRoute('user', $contr['login'],array('name'=>$contr['login'],'title'=>$contr['login']))}}
    {{--TODO: fix url--}}
    @include('ghpb::parts.like_btn',array('el'=>$contr, 'like_class'=>'pull-right', 'uname'=>$contr['login']))
</div>
@endforeach