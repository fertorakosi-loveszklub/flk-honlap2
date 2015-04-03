function getThumbnail(type, url) {
    var pattern = /^(https?:\/\/i.imgur.com\/[a-zA-Z0-9]+)(\.(jpe?g|png))$/;

    return url.replace(pattern, "$1" + type + "$2");
}