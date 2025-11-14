/*-------------------------
  Ajax Contact Form 
---------------------------*/
$(function() {

    // Get the form.
    var form = $('#contact-form');

    // Get the messages div.
    var formMessages = $('.form-messege');

    // Set up an event listener for the contact form.
    $(form).submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Serialize the form data.
        var formData = $(form).serialize();

        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData,
            dataType: 'json',
            headers: { 'Accept': 'application/json' }
        })
        .done(function() {
            // Success
            $(formMessages).removeClass('error');
            $(formMessages).addClass('success');

            // Set the message text.
            $(formMessages).text('Thank you! Your message has been sent.');

            // Clear the form.
            $('#contact-form input, #contact-form textarea').val('');
        })
        .fail(function(data) {
            // Error
            $(formMessages).removeClass('success');
            $(formMessages).addClass('error');

            // Set the message text.
            if (data.responseJSON && data.responseJSON.error) {
                $(formMessages).text(data.responseJSON.error);
            } else {
                $(formMessages).text('Oops! An error occurred and your message could not be sent.');
            }
        });
    });

});
