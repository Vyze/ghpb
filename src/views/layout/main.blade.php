<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>GitHub project browser</title>
{{HTML::style('packages/vyze/ghpb/css/styles.css')}}
    {{--{{HTML::style('css/styles.css')}}--}}
    {{--{{HTML::script('js/jquery-2.1.3.min.js')}}--}}
    {{--{{HTML::script('packages/bootstrap/js/bootstrap.min.js') }}--}}
    {{--{{HTML::script('js/ghpb.js') }}--}}
    {{HTML::style('packages/vyze/ghpb/css/styles.css')}}
    {{HTML::script('packages/vyze/ghpb/js/jquery-2.1.3.min.js')}}
    {{HTML::script('packages/bootstrap/js/bootstrap.min.js') }}
    {{HTML::script('packages/vyze/ghpb/js/ghpb.js') }}
    <script>
    //change like status
    $().ready(function(){
        $('.like-b').click(function(){

            var
                self = jQuery(this),
                liked = +self.hasClass('liked'),
                data = {
                    datatype: self.attr('data-type'),
                    id: self.attr('data-id'),
                    name: self.attr('data-name'),
                    liked: liked };

            if(liked){
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: data,
                    url: "{{route('changelike')}}",
                    success: function(msg){
                        self.html(msg);
                        self.removeClass('liked');
                    }
                });
            }else{
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: data,
                    dataType: "html",
                    url: "{{route('changelike')}}",
                    success: function(msg){
                        self.html(msg);
                        self.addClass('liked');
                    }
                });
            }
        });
    });
    </script>
</head>
<body>
<div class="navbar navbar-inverse navbar-static-top header"  role="header" >
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
          </button>
          <h1>GitHub project browser >>> {{$properties['title'] or 'Main'}}</h1>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" {{--style="height: 1px;"--}}>

          {{Form::model(null, array('route'=>'search', 'class'=>'navbar-form navbar-right'))}}
          {{--<div class="form-group">--}}
            <div class="form-group">
              {{Form::text('query', null, array('id'=>"search_input", 'placeholder'=>"Search", 'class'=>"w0", 'autocomplete'=>"off"))}}
              <button type="submit" id="search_submit" class="input-b"><span class="glyphicon glyphicon-search"></span></button>
              <button type="reset" id="search_clear" class="input-b"><span class="glyphicon glyphicon-remove-sign"></span></button>
            </div>
          {{--</div>--}}
          {{Form::close()}}
          <div class="main-nav navbar-right">
            <a id="main-b" href="{{URL::route('project')}}" title="home"><span class="glyphicon glyphicon-home"></span><span class="visible-xs-inline-block">Home</span></a>
            <span class="visible-lg-inline-block visible-md-inline-block visible-sm-inline-block seperator"></span>
            <a id="refresh-b" href="#" title="refresh"><span class="glyphicon glyphicon-refresh"></span><span class="visible-xs-inline-block">Refresh</span></a>
          </div>
        </div>
    </div>

    @yield('header-content')

</div>
<main role="main" class="container main-container">
    @yield('content')
</main>
</body>
</html>
