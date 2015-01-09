@extends('ghpb::layout.main')

@section('content')
<div class="pull-left col-md-6">
    @include('ghpb::parts.proj_info')
</div>
<div class="pull-right col-md-6">
    @include('ghpb::parts.contributors')
</div>
@stop