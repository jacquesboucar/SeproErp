$(document).ready(function() {

    //
    var path = $(location).attr('pathname');
    console.log(path);

    if (path.split('/').length > 5) {
        $("body > .wrap").addClass("interne");
    }

    $.each(path.split('/'), function(index, value){
        console.log(index+" :"+value);


        if (index == 4) {
            $("body > .wrap").attr('id',value);
        } else {
            if (index > 4){
                $("body > .wrap").addClass(value);
            }
        }

    });


    var wheight = $(document).height();
    var wwidth = $(window).width();

    $("body > .wrap").css('min-height', wheight);

});
