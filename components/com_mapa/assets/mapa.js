var map;
function initialize() {
    var LatLngc = new google.maps.LatLng(-1.66872587, -78.65233094);
    var myOptions = {
        center: LatLngc,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false,
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    var divProgramas = document.getElementById("accordion");
    divProgramas.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_TOP].push(divProgramas);
    var divMapas = document.getElementById("accordionMapas");
    divMapas.index = 1;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(divMapas);
}





document.addEvent('domready', function() {
    initialize();
});