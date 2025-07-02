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

        const setupAddElement = function(elementType) {
            $('div.vra-data').on('click', '.' + elementType + '-add', function() {
                var addButton = $(this);
                if (elementType == 'parent-element') {
                    var elementContainer = addButton.parents('.vra-data').first();
                } else {
                    var elementContainer = addButton.parents('.vra-drawer').first();
                }
                var newElementCount = elementContainer.find('.new.vra-' + elementType).length;
                var nameBase = addButton.data('namebase');
                var omekaElementName = addButton.data('omeka-element-name');
                var data = {
                        'newElementCount'  : newElementCount + 1,
                        'nameBase'         : nameBase,
                        'omekaElementName' : omekaElementName
                };

                if (elementType == 'subelement') {
                    var subelementName = addButton.data('subelement-name');
                    data.subelementName = subelementName;
                    data.vraParentId = addButton.data('vra-parent-id');
                    data.newSubelementCount = newElementCount;
                }

                $.get(OmekaWebDir + '/vra-core/ajax/' + elementType, data, function(response) {
                    addButton.before(response);
                }).done(function() {
                    addButton.prev().find('*:focusable').first().focus();
                });
            });
        }

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

        $(document).ready(function() {
            Omeka.manageDrawers('.vra-data', '.vra-drawer');
            setupAddElement('element');
            setupAddElement('parent-element');
            setupAddElement('subelement');
        });
    });
}(jQuery));
