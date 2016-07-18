$(document).ready(function()
{
    $(".account").click(function()
    {
        $(this).toggleClass('opened');
        var X=$(this).attr('id');
        if(X==1)
        {
            $(".submenu").hide();
            $(this).removeAttr('id');
        }
        else
        {
            $(".submenu").show();
            $(this).attr('id', '1');
        }

    });

//Mouse click on sub menu
    $(".submenu").mouseup(function()
    {
        return false
    });

//Mouse click on my account link
    $(".account").mouseup(function()
    {
        return false
    });


//Document Click
    $(document).mouseup(function()
    {
        $(".submenu").hide();
        $(this).removeAttr('id');
        $(this).removeClass('opened');
    });
    $(function() {
        $( '#dl-menu' ).dlmenu();
    });
});