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

        $('div#vra-core-metadata').on('keydown click', 'div.vra-element-header, div.vra-attributes-header', function(e) {
            if(e.type == "click" || e.keyCode == 13) {
                e.stopPropagation();
                var drawerContainer = $(this);
                drawerContainer.siblings('fieldset').toggle();
                drawerContainer.children('.drawer').toggleClass('opened').toggleClass('closed');
            }
        });

        $('div.vra-data').on('click', "input.element-add", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var addButton = $(this);
            var newElementCount = addButton.siblings('div.vra-element-inputs.new').length;
            var nameBase = addButton.data('namebase');
            var omekaElementName = addButton.data('omeka-element-name');
            var data = {
                    'newElementCount'  : newElementCount,
                    'nameBase'         : nameBase,
                    'omekaElementName' : omekaElementName
            };

            $.get(OmekaWebDir + '/vra-core/ajax/element', data, function(response, textStatus, jqXHR) {
                $(e.target).after(response);
            }).done(function() {
                addButton.next().children(':focusable').focus();
            });
        });

        $('div.vra-data').on('click', "input.subelement-add", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var addButton = $(this);
            var newSubelementCount = addButton.siblings('div.vra-subelement.new').length;
            var nameBase = addButton.data('namebase');
            var subelementName = addButton.data('subelement-name');
            var omekaElementName = addButton.data('omeka-element-name');
            var vraParentId = addButton.data('vra-parent-id');
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
            }).done(function() {
                addButton.next().children(':focusable').focus();
            });
        });

        $('div.vra-data').on('click', "input.parent-element-add", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var addButton = $(this);
            var newElementCount = addButton.siblings('div.vra-element.new').length;
            var nameBase = addButton.data('namebase');
            var omekaElementName = addButton.data('omeka-element-name');
            var data = {
                    'newElementCount' : newElementCount,
                    'nameBase'        : nameBase,
                    'omekaElementName' : omekaElementName,
            };
            $.get(OmekaWebDir + '/vra-core/ajax/parent-element', data, function(response, textStatus, jqXHR) {
                $(e.target).after(response);
            }).done(function() {
                addButton.next().children(':focusable').focus();
            });
        });

        //mark changed display elements so @dataDate can be updated
        $('#vra-core-metadata').on('keypress', 'div.input textarea', function(e) {
            var target = $(this);
            if (! target.data('changed')) {
                var field = target.parents('.field');
                var id = field.attr('id').replace('element-', '');
                var newInput = $('<input />');
                newInput.attr('name', "vra-element[" + id + "][display][is-changed]");
                newInput.attr('type', 'hidden');
                newInput.val(1);
                newInput.insertAfter(target);
                target.data('changed', true);
            }
        });
    });
}(jQuery));
