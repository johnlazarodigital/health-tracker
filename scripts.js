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
                get_daily_status();
                $('.heatra-details-form-add').slideToggle();
            },
            error: function(error) {
                // alert(error);
            }
        });

    });

    $(document).on('click', '.heatra-food-logs-button', function() {
        $('.heatra-food-logs').slideToggle();
    });

    $(document).on('click', '.heatra-food-logs button', function() {

        var that = $(this);

        if( $(this).parent().parent().next().is(":hidden") ) {

            var foodId = $(this).data('food-id');
            var amount = $(this).data('amount');

            var data = {
                action: 'heatra_get_nutrition',
                food_id: foodId,
                amount: amount
            };

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajax_url,
                data: data,
                success: function(response) {

                    var html = '<ul>';

                    response.forEach(function(item) {

                        html += '<li>' + item.name + ': ' + item.total_amount + ' ' + item.unit + '</td>';

                    });

                    html += '</ul>';

                    that.parent().parent().next().find('td').html(html);
            
                    that.parent().parent().next().slideDown();

                },
                error: function(error) {
                    // alert(error);
                }
            });

        } else
            $(this).parent().parent().next().slideUp();

    });

    get_records();

    function get_records() {

        var data = {
            action: 'heatra_get_record'
        };

        $.ajax({
            type: 'get',
            dataType: 'json',
            url: ajax_url,
            data: data,
            success: function(response) {

                var html = '';

                response.forEach(function(item) {
                    html += '<tr>\
                                <td>' + item.date_posted + '</td>\
                                <td>' + item.name + '</td>\
                                <td>' + item.amount + '</td>\
                                <td><button data-food-id="' + item.ref_food_id + '" data-amount="' + item.amount + '">View details</button></td>\
                            </tr>\
                            <tr>\
                                <td colspan="4"></td>\
                            </tr>';
                });

                $('.heatra-food-logs tbody').html(html);

            },
            error: function(error) {
                // alert(error);
            }
        });

    }

    get_foods();

    function get_foods() {

        var data = {
            action: 'heatra_get_foods'
        };

        $.ajax({
            type: 'get',
            dataType: 'json',
            url: ajax_url,
            data: data,
            success: function(response) {

                var html = '';

                response.forEach(function(item) {
                    html += '<option value="' + item.id + '">' + item.name + '</option>';
                });

                $('.input-food').html(html);

            },
            error: function(error) {
                // alert(error);
            }
        });

    }

    document.getElementById('input-date').valueAsDate = new Date();

    get_daily_status();

    function get_daily_status() {

        var data = {
            action: 'heatra_daily_status',
            date: $('#input-date').val()
        };

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url,
            data: data,
            success: function(response) {

                $('.nutri-calories').html( response.calories.count + '<br>' + response.calories.percentage );
                $('.nutri-protein').html( response.protein.count + '<br>' + response.protein.percentage );
                $('.nutri-carbs').html( response.carbs.count + '<br>' + response.carbs.percentage );
                $('.nutri-fat').html( response.fat.count + '<br>' + response.fat.percentage );
                $('.nutri-fiber').html( response.fiber.count + '<br>' + response.fiber.percentage );
                $('.nutri-sugar').html( response.sugar.count + '<br>' + response.sugar.percentage );

            },
            error: function(error) {
                // alert(error);
            }
        });

    }

    $(document).on('click', '.get-daily-status', function() {
        get_daily_status();
    });

})( jQuery );