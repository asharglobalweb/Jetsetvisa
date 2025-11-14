/*-------------------------
  Quote Form (Formspree)
---------------------------*/
$(function() {

    var form = $('#quote-form');
    var formMessages = $('.form-message');

    $(form).submit(function(e) {
        e.preventDefault();

        var formData = $(form).serialize();

        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData,
            dataType: 'json',
            headers: { 'Accept': 'application/json' }
        })
        .done(function() {
            $(formMessages).removeClass('error').addClass('success');
            $(formMessages).text('Thank you! Your request has been received.');
            $(form).find('input, textarea, select').val('');
        })
        .fail(function(data) {
            $(formMessages).removeClass('success').addClass('error');
            if (data.responseJSON && data.responseJSON.error) {
                $(formMessages).text(data.responseJSON.error);
            } else {
                $(formMessages).text('Oops! Something went wrong. Please try again later.');
            }
        });
    });
});
