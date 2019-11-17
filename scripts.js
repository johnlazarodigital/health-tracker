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
                get_records();
                $('.heatra-details-form-add').slideToggle();
            },
            error: function(error) {
                alert(error);
            }
        });

    });

    $(document).on('click', '.heatra-food-logs-button', function() {
        $('.heatra-food-logs').slideToggle();
    });

    var data = {
        action: 'heatra_get_record'
    };

    get_records();

    function get_records() {

        $.ajax({
            type: 'get',
            dataType: 'json',
            url: ajax_url,
            data: data,
            success: function(response) {

                var html = '';

                response.forEach(function(item) {
                    html += '<tr><td>' + item.date_posted + '</td><td>' + item.name + '</td><td>' + item.amount + '</td></tr>';
                });

                $('.heatra-food-logs tbody').html(html);

            },
            error: function(error) {
                alert(error);
            }
        });

    }

})( jQuery );