(function( $ ) {
	'use strict';

	var ajax_url = ajax_data.url;

    $(document).on('click', '.heatra-add-button', function() {
        $('.heatra-details-form-add').slideToggle();
    });

    $(document).on('submit', '.heatra-details-form', function(event) {

        event.preventDefault();

        console.log('submitted');

        var data = {
            action: 'heatra_insert_to_db_function',
            food: $(this).find('.input-food').val(),
            amount: $(this).find('.input-amount').val()
        };

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url,
            data: data,
            success: function(response) {
                $('.heatra-details-form-add').slideToggle();
            },
            error: function(error) {
                alert(error);
            }
        });

    });

})( jQuery );