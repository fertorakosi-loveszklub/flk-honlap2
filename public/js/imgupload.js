/**
 * Created by nxu on 2015.01.16..
 */
$(document).ready(function() {
    // Hide progressbar, preview and errormessage
    $('#progress').hide();
    $('#preview').hide();
    $('#error').hide();

    // Disable other elements
    $('.default-disabled').prop( "disabled", true );

    // Handle file upload
    $('#imageupload').fileupload({
            url: 'https://api.imgur.com/3/image',
            headers: {
                Authorization: 'Client-ID cc952edeff6ae38'
            },
            dataType: 'json',
            paramName: 'image',
            acceptFileTypes: /(\.|\/)(jpg)$/i,
            start: function() {
                // Upload started
                // Hide file upload and error message
                $('#imageupload').hide();
                $('#error').hide();

                // Show progress
                $('#progress').show();
            },
            progressall: function (e, data) {
                // Display progress
                var progress = parseInt(data.loaded / data.total * 100, 10);
                setProgress(progress);
            },
            done: function (e, data) {
                // Upload finished, save url
                $('#imgurl').val(data.result.data.link);

                // Hide progress bar
                $('#progress').hide();

                // Display preview
                var link = data.result.data.link;
                link = link.replace("http://", "https://");
                link = getThumbnail('t', link);
                $('#preview').attr('src', link).show();

                // Enable controls
                $('#imageprompt').hide();
                $('.default-disabled').prop( "disabled", false );
            },
            error: function() {
                // Error. Hide progressbar
                setProgress(0);
                $('#progress').hide();

                // Display upload again
                $('#imageupload').show();

                // Show error message
                $('#error').show();
            }
        }).prop('disabled', !$.support.fileInput);

    // Validate form
    $('.form-horizontal').validate({
        rules   : {
            points  : {
                required: true,
                min     : 1,
                max     : 300
            },
            shots  : {
                required: true,
                min     : 1,
                max     : 30
            },
            shot_at : {
                required: true,
                dateISO : true
            }
        },
        messages: {
            shots       : 'Minimum 1, maximum 30 lövést írhatsz be.',
            points      : 'Minimum 1, maximum 300 köregységet írhatsz be.',
            shot_at     : 'A következő formátumot használd: Év-hó-nap (2015-01-01)'
        }
    });
});

function setProgress(progress) {
    $('#progressbar').css(
        'width',
        progress + '%'
    );
    $('#percentage').html(progress);
}