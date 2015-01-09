@if($el['liked'])
<button class="{{$like_class or ''}} like-b liked" name="like" data-id="{{$el['id']}}" data-type="{{strtolower($el['type'])}}" data-name="{{$uname}}">@include('ghpb::parts.btn_star',['text'=>'Unlike'])</button>
@else
<button class="{{$like_class or ''}} like-b" name="like" data-id="{{$el['id']}}" data-type="{{strtolower($el['type'])}}" data-name="{{$uname}}">@include('ghpb::parts.btn_star',['text'=>'Like'])</button>
@endif

