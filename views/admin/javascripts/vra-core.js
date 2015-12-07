(function($) {
    $(function() {
        $('div.vra-data').on('click', 'div.vra-element-header', function(e) {
            e.stopPropagation();
            var drawer = $(this).children('.drawer');
            $(this).siblings('fieldset').toggle();
            if(drawer.hasClass('opened')) {
                drawer.removeClass('opened').addClass('closed');
            } else {
                drawer.removeClass('closed').addClass('opened');
            }
            
        });
        
        $('div.vra-data').on('click', 'div.vra-attributes-header', function(e) {
            e.stopPropagation();
            var drawer = $(this).children('.drawer');
            $(this).siblings('fieldset').toggle();
            if(drawer.hasClass('opened')) {
                drawer.removeClass('opened').addClass('closed');
            } else {
                drawer.removeClass('closed').addClass('opened');
            }
        });
        
        $('div.vra-data').on('click', "input.element-add", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var newElementCount = $(this).siblings('div.vra-element-inputs.new').length;
            var data = {
                    'newElementCount' : newElementCount,
                    'nameBase'        : nameBase
            };
            
            $.get(OmekaWebDir + '/vra-core/ajax/element', data, function(response, textStatus, jqXHR) {
                $(e.target).after(response);
            });
        });
    });
}(jQuery));