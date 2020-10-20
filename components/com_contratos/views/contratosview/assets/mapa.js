var mapObras = null;
var gMarkerSelect = null;
/**
 * 
 */
jQuery(document).ready(function() {
    var LatLngc = getGLatlng(-1.66872587, -78.65233094);
    var myOptions = {
        center: LatLngc,
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    mapObras = new google.maps.Map(document.getElementById("mapaPrograma"), myOptions);
    google.maps.event.trigger(mapObras, 'resize');
    drawProyects();

});
/**
 * 
 * @returns {undefined}
 */
function drawProyects() {
    if (lstGraficos.length > 0) {
        for (var j = 0; j < lstGraficos.length; j++) {
            drawGrafico(lstGraficos[j]);
        }
    }
}


function drawGrafico(grafico) {
    if (grafico) {
        var tipoGrafico = parseInt(grafico.idTipoGrafico);
        switch (tipoGrafico)
        {
            case 1:
                drawMarker(grafico);
                break;
            case 2:
                drawLine(grafico);
                break;
            case 3:
                drawPoligone(grafico);
                break;
            case 4:
                drawCircle(grafico);
                break;
            default:
        }
    }

}

function drawMarker(grafico) {
    var coordenadas = grafico.lstCoordenadas[0];
    var position = new google.maps.LatLng(coordenadas.lat, coordenadas.lng);
    var marker = new google.maps.Marker({
        map: mapObras,
        title: grafico.descripcion,
        position: position
    });
}

function getGLatlng(lat, lng) {
    //aqui validar el rango
    var glPoint = new google.maps.LatLng(lat, lng);
    return glPoint;
}

function drawLine(grafico) {
    var coordenadas = getGooglePath(grafico.lstCoordenadas);
    var line = new google.maps.Polyline({
        title: grafico.descripcion,
        path: coordenadas,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 2
    });
    line.setMap(mapObras);
}

function drawPoligone(grafico) {
    var coordenadas = getGooglePath(grafico.lstCoordenadas);
    coordenadas.push(new google.maps.LatLng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng));
    var poligone = new google.maps.Polygon({
        paths: coordenadas,
        title: grafico.descripcion,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35
    });
    poligone.setMap(mapObras);
}

function drawCircle(grafico) {
    var centro = getGLatlng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng);
    var borde = getGLatlng(grafico.lstCoordenadas[1].lat, grafico.lstCoordenadas[1].lng);
    var radio = getRadioRevese(centro, borde);

    var populationOptions = {
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: mapObras,
        center: centro,
        radius: radio,
        title: grafico.descripcion
    };
    var cityCircle = new google.maps.Circle(populationOptions);
}

function getRadioRevese(center, border) {
    var distancia = google.maps.geometry.spherical.computeDistanceBetween(center, border);
    return parseFloat(distancia.toFixed(2));
}

function getGooglePath(arrayCoordenas) {
    var arrayGoogleCoordenas = new Array();
    for (var j = 0; j < arrayCoordenas.length; j++) {
        var coordenada = getGLatlng(arrayCoordenas.lat, arrayCoordenas.lng);
        arrayGoogleCoordenas.push(coordenada);
    }
    return(arrayGoogleCoordenas);
}

/**
 * 
 * @param {type} marker
 * @param {type} data
 * @returns {undefined}
 */
function addInfowindos(marker, data) {

    var contentString = getContent(data);
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    google.maps.event.addListener(marker, 'click', function() {
        if (gMarkerSelect != null) {
            gMarkerSelect.infowindows.close();
        }
        infowindow.open(mapObras, marker);
        marker.infowindows = infowindow;
        gMarkerSelect = marker;
        google.maps.event.trigger.infowindows(infowindow, 'domready');

    });
    // evento para juntar el ingoeindos al dom
    google.maps.event.addListener(infowindow, 'domready', function() {
        jQuery("#tabsinfo").tabs();
        jQuery("#tabImg").click(function() {
            jQuery("#carrusel").jcarousel();
        });
    });
}

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function getContent(data) {
    var tabs = '';
    tabs += '<div style="height:210px;width:400px">';
    tabs += '   <div id="tabsinfo" style="height:200px;width:375px">';
    tabs += '       <ul>';
    tabs += '           <li><a href ="#tabProyectos">Proyecto</a></li>';
    // tabs += '           <li><a href ="#tabCovertura">Covertura</a></li>';
    tabs += '           <li><a id="tabImg" href ="#tabImagenes">Imagenes</a></li>';
    tabs += '       </ul>';
    tabs += '       <div id="tabProyectos">';
    tabs += '           ' + getProyecto(data);
    tabs += '       </div>';
    //    tabs += '       <div id ="tabCovertura">';
    //    tabs += '           <p>Covetura </p>';
    //    tabs += '       </div>';
    tabs += '       <div id ="tabImagenes">';
    tabs += '           ' + getImagenes(data);
    tabs += '       </div>';
    tabs += '   </div>';
    tabs += '</div>';
    return tabs;
}

/**
 * 
 * @param {type} data
 * @returns {String}  */
function getProyecto(data) {

    var duracion = (data.inpDuracion_stmdoPry) ? data.inpDuracion_stmdoPry : "0";
    var unidMedi = (data.strSimbolo_unimed) ? data.strSimbolo_unimed : "";
    var content = '<table id="infowindo">' +
            '           <tr>' +
            '               <td                 style="text-align:center;"><img class="tableImgLog" src="images/logo_ecorae.png"></td>' +
            '               <td  colspan="2"    style="text-align:center;"><b> ' + data.strNombre_pry + ' </b></td>' +
            '               <td                 style="text-align:center;"><img style="height: 60px; width:60px" class="tableImgLog" src="components/com_proyectos/images/' + data.intCodigo_pry + '/icon/' + data.intCodigo_pry + '.jpg"></td>' +
            '           </tr>' +
            '           <tr> ' +
            '                <td  colspan="4"><hr></td> ' +
            '           </tr>' +
            '           <tr>' +
            '               <td colspan="4" style="width: 25%"><b>Duraci&oacute;n :</b>' + duracion + " ( " + unidMedi + " )" + '</td>' +
            '           </tr>' +
            '           <tr>' +
            '               <td><b>Fecha Inicio:</b></td>' +
            '               <td>' + data.dteFechaInicio_stmdoPry + '</td>' +
            '               <td><b>Fecha Fin:</b></td>' +
            '               <td>' + data.dteFechaFin_stmdoPry + '</td>' +
            '           </tr>' +
            '           <tr>' +
            '               <td><b>Ubicación:</b></td>' +
            '               <td colspan="3"></td>' +
            '           </tr>' +
            '       </table>';
    return content;
}
/**  * 
 * @param {type} data
 * @returns {String}}
 */
function getImagenes(data) {
    var path = 'http://' + window.location.host + '/components/com_proyectos/images/';
    var content = '';
    content += '<div class ="jcarousel-skin-ie7">';
    content += '    <div class = "jcarousel-container" >';
    content += '        <div id="carrusel" class="jcarousel-clip">';
    content += '           <ul class="jcarousel-list jcarousel-list-horizontal">';
    if (data.imagenes.length > 0) {
        for (var i = 0; i < data.imagenes.length; i++) {
            content += '          <li class="jcarousel-item-' + (i + 1) + '">';
            content += '             <a  href="' + path + data.imagenes[i].strNombre_img + '" ';
            content += '                    rel="lightbox[roadtrip]">';
            content += '                  <img height="127px" width="200px"';
            content += '                    src= "' + path + data.imagenes[i].strNombre_img + '"';
            content += '                />';
            content += '                </a>';
            content += '            </li>';
        }
    }
    else {
        content += '                <li class="jcarousel-item-1">';
        content += '                    <a  href="' + path + 'default.png" ';
        content += '                        rel="lightbox[roadtrip]">';
        content += '                        <img height="127px" width="200px"';
        content += '                        src= "' + path + 'default.png"';
        content += '                    />';
        content += '                    </a>';
        content += '                </li>';
    }
    content += '            </ul>';
    content += '        </div>';
    content += '    <div disabled = "disabled" class  "jcarousel-prev jcarousel-prev-disabled" > </div>';
    content += '    <div class = "jcarousel-next" > </div>';
    content += '    </div>';
    content += '</div>';
    return content;
}
