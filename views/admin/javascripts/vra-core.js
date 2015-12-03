(function($) {
    $(function() {
        $('div.vra-element-header').on('click', function(e) {
            e.stopPropagation();
            var drawer = $(this).children('.drawer');
            $(this).siblings('fieldset').toggle();
            if(drawer.hasClass('opened')) {
                drawer.removeClass('opened').addClass('closed');
            } else {
                drawer.removeClass('closed').addClass('opened');
            }
            
        });
        
        $('div.vra-attributes-header').on('click', function(e) {
            e.stopPropagation();
            var drawer = $(this).children('.drawer');
            $(this).siblings('fieldset').toggle();
            if(drawer.hasClass('opened')) {
                drawer.removeClass('opened').addClass('closed');
            } else {
                drawer.removeClass('closed').addClass('opened');
            }
        });
    });
}(jQuery));