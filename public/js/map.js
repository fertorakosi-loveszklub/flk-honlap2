var map;

function initialize() {
    var myLatlng = new google.maps.LatLng(47.723967,16.641917);
    var mapOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    console.log(document.getElementById("map-canvas"));
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    console.log(map);
    var contentString = "<h3>Fertőrákosi Lövészklub</h3>" +
        "<p>9421 Fertőrákos, Felsőszikla sor</p>";


    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: "Fertőrákosi Lövészklub"
    });
    google.maps.event.addListener(marker, "click", function() {
        infowindow.open(map,marker);
    });
}

$(document).ready(function() {
    doIt();
});

function doIt() {
    if ($("#map-canvas").length) {
        initialize();
    } else {
        setTimeout(doIt, 50);
    }
}