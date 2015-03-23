$(document).ready(function() {
    // Initialize event handlers

    // Overwrite name change form submission
    $('#NameChangeForm').submit(function(event) {
        event.preventDefault();

        $('SaveNewName').val('<i class="fa fa-circle-o-notch fa-spin"></i>');
        $('SaveNewName').prop('disabled', true);

        $.ajax({
            url         : '/felhasznalo/uj-nev',
            type        :'POST',
            data        : {
                _token  : $('[name=_token]').val(),
                NewName : $('#NewName').val()
            },
            dataType    : 'json',
            error       : function(xhr, textStatus, error) {
                if (xhr.status == 422) {
                    // Validation error
                    $('#NameChangeError').html(xhr.responseJSON.NewName[0]);
                } else {
                    // Other error
                    $('#NameChangeError').html('A szerver nem elérhető');
                }
            },
            success     : function(data, textStatus, xhr) {
                if (!data.success) {
                    // Error
                    // Check if error message is an array
                    if (data.message.hasOwnProperty('real_name')) {
                        $('#NameChangeError').html(data.message.real_name[0]);
                    }
                    else {
                        $('#NameChangeError').html(data.message);
                    }
                } else {
                    // Success
                    $('#NameChangeError').hide();
                    $('#UserFullName').html(data.newName);
                    $('#NameChange').modal('hide');
                }
            },
            complete    : function() {
                $('SaveNewName').val('Mentés');
                $('SaveNewName').prop('disabled', false);
            }
        });
    });

    // Name change button
    $('#SaveNewName').click(function() {
        $('#NameChangeForm').submit();
    });

    // Prompt
    $(function() {
        $('.confirm').click(function() {
            return window.confirm("Biztos vagy benne?");
        });
    });
});
