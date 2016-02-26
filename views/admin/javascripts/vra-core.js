(function($) {
    $(function() {
        //need to disable vra data with no value to avoid
        //exceeding php's max_inputs security setting
        //leave the vra elements untouched, so usual process of deleting
        //by erasing will still be in place
        $('form').submit(function(e) {
            $('.vra-new').each(function() {
                if ($(this).val() == '') {
                    $(this).prop('disabled', true);
                }
            });
        });
        
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
            var nameBase = $(this).data('namebase');
            var omekaElementName = $(this).data('omeka-element-name');
            var data = {
                    'newElementCount'  : newElementCount,
                    'nameBase'         : nameBase,
                    'omekaElementName' : omekaElementName
            };

            $.get(OmekaWebDir + '/vra-core/ajax/element', data, function(response, textStatus, jqXHR) {
                $(e.target).after(response);
            });
        });

        $('div.vra-data').on('click', "input.subelement-add", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var newSubelementCount = $(this).siblings('div.vra-subelement.new').length;
            var nameBase = $(this).data('namebase');
            var subelementName = $(this).data('subelement-name');
            var omekaElementName = $(this).data('omeka-element-name');
            var vraParentId = $(this).data('vra-parent-id');
            var data = {
                    'newSubelementCount' : newSubelementCount,
                    'nameBase'        : nameBase,
                    'omekaElementName' : omekaElementName,
                    'subelementName'  : subelementName,
                    'vraParentId'     : vraParentId,
            };
            
            if (subelementName === 'dates') {
                data.newAgentIndex = $(this).data('newagentindex');
            }
            $.get(OmekaWebDir + '/vra-core/ajax/subelement', data, function(response, textStatus, jqXHR) {
                $(e.target).after(response);
            });
        });

        $('div.vra-data').on('click', "input.parent-element-add", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var newElementCount = $(this).siblings('div.vra-element.new').length;
            var nameBase = $(this).data('namebase');
            var omekaElementName = $(this).data('omeka-element-name');
            var data = {
                    'newElementCount' : newElementCount,
                    'nameBase'        : nameBase,
                    'omekaElementName' : omekaElementName,
                    //'subelementName'  : subelementName
            };
            $.get(OmekaWebDir + '/vra-core/ajax/parent-element', data, function(response, textStatus, jqXHR) {
                $(e.target).after(response);
            });
        });
    });
}(jQuery));
