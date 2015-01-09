<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>GitHub project browser</title>
    {{HTML::style('packages/vyze/ghpb/css/styles.css')}}
</head>
<body>
<div class="global-wrapper">
    <iframe id="mainframe" class="w0"
{{--        src="{{URL::route('project',array('owner'=>'', 'name'=>''))}}">--}}
        src="{{URL::route('project')}}">
        @yield('content')
    </iframe>
</div>
</body>
</html>