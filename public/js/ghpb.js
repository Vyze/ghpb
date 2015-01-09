//Event handler registration
$(window).ready(function(){

    $('#refresh-b').click(function(event){
        event.preventDefault();
        location.reload();
    });
});
