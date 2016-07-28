jQuery(document).ready(function(){

	var wheight = $(document).height();
  var wwidth = $(window).width();

  $("body > .wrap").css('min-height', wheight);

  //
  var path = $(location).attr('pathname');
  // console.log(path);

  var pathlenght = path.split('/').length;

  if (pathlenght > 5) {
    $("body > .wrap").addClass("interne");
  }

  $.each(path.split('/'), function(index, value){
    // console.log(index+" :"+value);

    if (index == 4) {
        $("body > .wrap").attr('id',value);
    } else {
        if (index > 4){
            $("body > .wrap").addClass(value);
        }
    }

  });



	var accordionsMenu = $('.cd-accordion-menu');

	/* check if firstlevel have submenu */
	$('.firstlevel > ul > li').each(function(){
		if ($(this).text().length == 0 ) {
			$(this).parent().parent().removeClass('has-children');
		}
	});

	$('.secondlevel').each(function(){
		if ($(this).children('ul').length == 0 ) {
			$(this).removeClass('has-children');
		}
	});

	$('.firstlevel > label > a').each(function(){
		var id = $(this).attr('id');
		$(this).parent().parent().attr('id', id.split('_')[1]);
	});



	if( accordionsMenu.length > 0 ) {

		$('.cd-accordion-menu > li').each(function(){
			var id = $(this).attr("id");
			if (path.split('/')[4] != id) {
				$(this).addClass('element-invisible');

			} else {
				$(this).children('ul').attr('style', 'display:block;')
			}
		});

		$('.secondlevel.has-children > label > a').on('click', function(event){
			event.preventDefault();
			if ($(this).parent().siblings('ul').hasClass('visible')) {
				$(this).parent().siblings('ul').removeClass('visible').slideDown(300);
			} else {
				$(this).parent().siblings('ul').addClass('visible').slideUp(300);
			}
		});

		// accordionsMenu.each(function(){
		// 	var accordion = $(this);
		// 	//detect change in the input[type="checkbox"] value
		// 	accordion.on('change', 'input[type="checkbox"]', function(){
		// 		var checkbox = $(this);
		// 		console.log(checkbox);
		// 		console.log(checkbox.prop('checked'));
		// 		checkbox.parent().toggleClass('checked');
		// 		( checkbox.prop('checked') ) ? checkbox.siblings('ul').attr('style', 'display:none;').slideDown(300): checkbox.siblings('ul').attr('style', 'display:block;').slideUp(300);
		// 	});
		// });
	}


});
