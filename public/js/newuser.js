window.fbAsyncInit = function() {
    FB.init({
        appId      : '228336310678604',
        xfbml      : true,
        version    : 'v2.1'
    });

    if (typeof facebookInit == 'function') {
        facebookInit();
    }
};

function facebookInit() {
    FB.getLoginStatus(function(response) {
        if (response.authResponse) {
            token = response.authResponse.accessToken;
            FB.api('/me', function(response) {
                $('#joinGroup').click(function() {
                    FB.ui({
                        method: 'game_group_join',
                        id: '361318767380357',
                        display:'popup',
                    }, function(response) {
                        if (response.added == true) {
                            $('#join').hide();
                            $('#login').show();
                        } else {
                        }
                    });
                })
            });
        } else {
            alert('Kérlek, jelentkezz be a jobb felső sarokban található linken.');
            $('.content').hide();
        }
    });
};