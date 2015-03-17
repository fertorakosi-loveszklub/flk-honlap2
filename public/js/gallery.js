var albumPattern = /^http(?:s)?:\/\/(?:www\.)?imgur.com\/a\/([a-zA-Z0-9]+)(?:#[0-9]+)?$/;

$(document).ready(function() {
    // Hide wrong pattern warning
    $('#wrongpattern').hide();

    // Hide wrong pattern menu when album URL gets changed
    $('#album_url').change(function() {
        if ($(this).val().match(albumPattern) == null) {
            $('#wrongpattern').show();
        } else {
            $('#wrongpattern').hide();
        }
    })

    // New album menu
    $('#NewAlbum').click(function() {
        $('#AlbumEditForm').attr('action', '/galeria/uj');
        $('#AlbumEditTitle').html('Új album létrehozása');
        $('#AlbumEdit').modal('show');
    })

    // Edit album menu
    $('#EditAlbum').click(function() {
        $('#AlbumEditForm').attr('action', '/galeria/szerkesztes/' + $('#hAlbumId').val());
        $('#AlbumEditTitle').html('Album szerkesztése');
        $('#albumTitle').val($('#hAlbumTitle').val());
        $('#album_url').val($('#hAlbumURL').val());
        $('#AlbumEdit').modal('show');
    })

    // Check album pattern
    $('#AlbumEditForm').submit(function(event) {
        if ($('#album_url').val().match(albumPattern) == null) {
            event.preventDefault();
            return false;
        }
    })
});

/**
 * Loads an album with the given URL
 * @param albumURL Imgur URL of the album.
 */
function loadAlbum(albumURL) {
    var match = albumPattern.exec(albumURL);
    if(match == null) {
        showError('Érvénytelen album URL.');
        $('#loadingicon').hide();
        return;
    }
    var albumID = match[1];

    $.ajax({
        url         : "https://api.imgur.com/3/album/" + albumID + "/images",
        type        : "GET",
        timeout     : 10000,
        beforeSend  : function(xhr) {
            xhr.setRequestHeader('Authorization', 'Client-ID cc952edeff6ae38');
        },
        success     : function(data) {
            loadImages(data.data);
        },
        error       : function() {
            showError('Hiba történt a képek betöltése közben.');
        }
    });
}

/**
 * Loads an array of images and inserts them to the DOM.
 * @param imgArray Array of images.
 */
function loadImages(imgArray) {
    // Images is an array of images
    imgArray.forEach(function (image) {
        // Replace to https
        var link = image.link.replace('http://', 'https://');

        // Thumbnail
        var thumbnail = getThumbnail('s', link);
        
        // 'Full' image
        var bigthumb = getThumbnail('h', link);
        var domText = '<a tabindex="1" rel="group" href="' + bigthumb + '" class="fancyImage" rel="myGallery" target="_blank"><img src="' + thumbnail + '" style="max-width:90px; max-height: 90px; border: 1px solid black; margin: 3px;" /></a>\r\n';
        $("#images").append(domText);
    });

    // Enable the slideshow  and hide loader
    $("#loadingicon").hide();

    $(".fancyImage").fancybox();
}

/**
 * Displays an error message on the DOM.
 */
function showError(message) {
    var errorMessage = '<div class="alert alert-danger alert-dismissible" role="alert">' +
    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
    message +
    '</div>';

    $(".content h2").after(errorMessage);
}