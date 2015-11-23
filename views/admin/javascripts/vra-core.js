(function($) {
    $(function() {
        $('div.vra-data label').on('click', function(e) {
            e.stopPropagation();
            $(this).siblings('fieldset').toggle();
        });
    });
}(jQuery));