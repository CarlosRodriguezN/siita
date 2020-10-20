var mapContratos = null;
var gMarkerSelect = null;
/**
 * 
 */
jQuery(document).ready(function() {
    var LatLngc = new google.maps.LatLng(-1.66872587, -78.65233094);
    var myOptions = {
        center: LatLngc,
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    mapContratos = new google.maps.Map(document.getElementById("mapaPrograma"), myOptions);
    google.maps.event.trigger(mapContratos, 'resize');
    drawProyects();

});
/**
 * 
 * @returns {undefined}
 */
function drawProyects() {
    if (lstContratos.length > 0) {
        for (var j = 0; j < lstContratos.length; j++) {
            if (lstContratos[j].ubicaciones.length > 0) {
                for (var k = 0; k < lstContratos[j].ubicaciones.length; k++) {
                    if (lstContratos[j].ubicaciones[k].dpaUbGeo)
                        var glPosition = new google.maps.LatLng(lstContratos[j].ubicaciones[k].dpaUbGeo.coordenada.lat, lstContratos[j].ubicaciones[k].dpaUbGeo.coordenada.lng);

                    lstContratos[j].provincia = lstContratos[j].ubicaciones[k].dpaUbGeo.coordenada.provincia;
                    lstContratos[j].canton = lstContratos[j].ubicaciones[k].dpaUbGeo.coordenada.canton;
                    lstContratos[j].parroquia = lstContratos[j].ubicaciones[k].dpaUbGeo.coordenada.parroquia;



                    drawMarker(glPosition, lstContratos[j]);
                }
            }
        }
    }
}

/**
 * 
 * @param {type} coordenada
 * @param {type} data
 * @returns {undefined}
 */
function drawMarker(coordenada, data) {
    var marker = new google.maps.Marker({
        map: mapContratos,
        position: coordenada
    });
    addInfowindos(marker, data);
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
        infowindow.open(mapContratos, marker);
        marker.infowindows = infowindow;
        gMarkerSelect = marker;
        google.maps.event.addListener(infowindow, 'domready', function() {
            jQuery("#tabsinfo").tabs().height(220);
            jQuery("#tabImg").click(function() {
                jQuery("#carrusel").jcarousel();
            });
        });
    });
}

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function getContent(data) {
    var cad = '';
    cad += '<div id="tabsinfo" class="tabdiv">';
    cad += '    <ul>';
    cad += '        <li><a href ="#tabProyectos">Proyecto</a></li>';
    cad += '        <li><a href ="#tabCovertura">Covertura</a></li>';
    cad += '    </ul>';
    cad += '    <div id="tabProyectos">';
    cad += '        <div class="contentTab">';
    cad += '            ' + getContrato(data);
    cad += '        </div>';
    cad += '    </div>';
    cad += '    <div id ="tabCovertura">';
    cad += '        <div class="contentTab">';
    cad += '            ' + getCoberturaContrato(data);
    cad += '        </div>';
    cad += '    </div>';
    cad += '</div>';
    return cad;
}

/**
 * 
 * @param {type} data
 * @returns {String}  */
function getContrato(data) {
    var plazo = (data.intPlazo_ctr) ? data.intPlazo_ctr : 'No Asignado';
    var monto = (data.dcmMonto_ctr) ? data.dcmMonto_ctr : "0";

    var provincia = (data.provincia) ? data.provincia : "";
    var canton = (data.canton) ? data.canton : "";
    var parroquia = (data.parroquia) ? data.parroquia : "";

    var ubicacion = provincia + ', ' + canton + ', ' + parroquia;

    var content = '';
    content += '<table style="width: 95%">';
    content += '    <tr>';
    content += '        <td                style="text-align:center;"><img class="tableImgLog" src="images/logo_ecorae.png"></td>';
    content += '        <td colspan="3"    style="text-align:center;"><b> Contrato </b></td>';
    content += '    </tr>';
    content += '    <tr> ';
    content += '        <td colspan="4"><hr></td> ';
    content += '    </tr>';
    content += '</table>';
    content += '<table style="width: 95%">';
    content += '    <tr>';
    content += '        <td ><b>Plazo:</b></td>';
    content += '        <td colspan="3">' + plazo + '</td>';
    content += '    </tr>';
    content += '    <tr>';
    content += '        <td><b>Monto:</b></td>';
    content += '        <td colspan="3">' + monto + '</td>';
    content += '    </tr>';
    content += '    <tr>';
    content += '        <td><b>Ubicación:</b></td>';
    content += '        <td colspan="3">' + ubicacion + '</td>';
    content += '    </tr>';
    content += '    <tr>';
    content += '        <td colspan="4">' + verMasContrato(data) + '</td>';
    content += '    </tr>';
    content += '</table>';
    return content;
}
/** 
 * @param {type} data
 * @returns {String}}
 */
function getImagenes(data) {
    var path = 'http://' + window.location.host + '/components/com_proyectos/images/';
    var cad = '';
    cad += '<div class ="jcarousel-skin-ie7">';
    cad += '    <div class = "jcarousel-container" >';
    cad += '        <div id="carrusel" class="jcarousel-clip">';
    cad += '           <ul class="jcarousel-list jcarousel-list-horizontal">';
    if (data.imagenes.length > 0) {
        for (var i = 0; i < data.imagenes.length; i++) {
            cad += '          <li class="jcarousel-item-' + (i + 1) + '">';
            cad += '             <a  href="' + path + data.imagenes[i].strNombre_img + '" ';
            cad += '                    rel="lightbox[roadtrip]">';
            cad += '                  <img height="127px" width="200px"';
            cad += '                    src= "' + path + data.imagenes[i].strNombre_img + '"';
            cad += '                />';
            cad += '                </a>';
            cad += '            </li>';
        }
    }
    else {
        cad += '                <li class="jcarousel-item-1">';
        cad += '                    <a  href="' + path + 'default.png" ';
        cad += '                        rel="lightbox[roadtrip]">';
        cad += '                        <img height="127px" width="200px"';
        cad += '                        src= "' + path + 'default.png"';
        cad += '                    />';
        cad += '                    </a>';
        cad += '                </li>';
    }
    cad += '            </ul>';
    cad += '        </div>';
    cad += '    <div disabled = "disabled" class  "jcarousel-prev jcarousel-prev-disabled" > </div>';
    cad += '    <div class = "jcarousel-next" > </div>';
    cad += '    </div>';
    cad += '</div>';
    return cad;
}
/**
 * 
 * @param {type} data
 * @returns {String}
 */
function verMasContrato(data) {
    var ancla = '';
    ancla += '<a href="/index.php?option=com_contratos&view=contratosview&idContrato=' + data.intIdContrato_ctr + '">Ver mas...</a>';
    return ancla;
}

/**
 * Arma el hthml que permite mostrar la cobertura de un proyecto
 * @param {type} undTerritoria
 * @returns {String}
 */
function getCoberturaContrato(undTerritoria) {
    var undTerr = '';
    undTerr += '<div class="contentTab";">';
    undTerr += ' <table>';
    var ubicaciones = undTerritoria.ubicaciones;

    for (var j = 0; j < ubicaciones.length; j++) {
        var provincia = (ubicaciones[j].dpaUbGeo.coordenada.provincia) ? ubicaciones[j].dpaUbGeo.coordenada.provincia : '';
        var canton = (ubicaciones[j].dpaUbGeo.coordenada.canton) ? ubicaciones[j].dpaUbGeo.coordenada.canton : '';
        var parroquia = (ubicaciones[j].dpaUbGeo.coordenada.parroquia) ? ubicaciones[j].dpaUbGeo.coordenada.parroquia : '';


        var ubicacion = provincia + ',' + canton + ',' + parroquia;
        undTerr += '<tr>';
        undTerr += ' <td>';
        undTerr += ' ' + ubicacion;
        undTerr += ' </td>';
        undTerr += '</tr>';
    }
    undTerr += ' </table>';
    undTerr += '</div>';
    return undTerr;
}