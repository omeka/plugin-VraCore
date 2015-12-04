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
        
        $('div.vra-data').on('click', "input[type='submit']", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var newElementCount = $(this).siblings('div.vra-element-inputs.new').length;
            console.log(newElementCount);
            console.log(nameBase);
        });
    });
}(jQuery));