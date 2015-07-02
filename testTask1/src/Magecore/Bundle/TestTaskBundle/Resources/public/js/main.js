function initAjaxForm()
{
    $('body').on('submit', '.ajaxForm', function (e) {

        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
            .done(function( data, textStatus, jqXHR )  {
                if (typeof data.message !== 'undefined') {
                    //alert(data.message);
                    //$('#comments_list_div').html(jqXHR.responseJSON.message);
                    $('#comments_list_div').html(data.message);
                    initAjaxForm();
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof jqXHR.responseJSON !== 'undefined') {
                    if (jqXHR.responseJSON.hasOwnProperty('form')) {
                        $('#form_body').html(jqXHR.responseJSON.form);
                    }

                    $('.form_error').html(jqXHR.responseJSON.message);

                } else {
                    alert(errorThrown);
                }

            });
    });

    $('a.comment_edit_action').on('click',function(e){
        e.preventDefault();

        $.ajax({
            type: 'post',
            url: $(this).attr('data-content')

        })
            .done(function( data, textStatus, jqXHR )  {
                if (typeof data.message !== 'undefined') {
                    //alert(data.message);
                    //$('#comments_list_div').html(jqXHR.responseJSON.message);
                    $('#comments_list_div').html(data.message);

                    initAjaxForm();
                    //$('body').scrollTo('#form_body');
                    //scrollTop: ('#form_body');
                    $('html, body').animate({
                        scrollTop: $("#form_body").offset().top
                    }, 1000);

                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof jqXHR.responseJSON !== 'undefined') {
                    if (jqXHR.responseJSON.hasOwnProperty('form')) {
                        $('#form_body').html(jqXHR.responseJSON.form);
                    }

                    $('.form_error').html(jqXHR.responseJSON.message);

                } else {
                    alert(errorThrown);
                }

            });

    });
    $('a.comment_remove_action').on('click',function(e) {
        e.preventDefault();

        if (confirm('Do you Really want to delete this comment?')) {

            $.ajax({
                type: 'post',
                url: $(this).attr('data-content')

            })
                .done(function (data, textStatus, jqXHR) {
                    if (typeof data.message !== 'undefined') {
                        //alert(data.message);
                        //$('#comments_list_div').html(jqXHR.responseJSON.message);
                        $('#comments_list_div').html(data.message);
                        initAjaxForm();
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    if (typeof jqXHR.responseJSON !== 'undefined') {
                        if (jqXHR.responseJSON.hasOwnProperty('form')) {
                            $('#form_body').html(jqXHR.responseJSON.form);
                        }

                        $('.form_error').html(jqXHR.responseJSON.message);

                    } else {
                        alert(errorThrown);
                    }

                });

        }
    });


}