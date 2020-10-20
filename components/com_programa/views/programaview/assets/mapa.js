var mapContratos = null;
var gMarkerSelect = null;
/**
 * 
 */
jQuery(document).ready(function() {
    var LatLngc = new google.maps.LatLng(-1.66872587, -78.65233094);
    var myOptions = {
        center: LatLngc,
        zoom: 6,
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
    var bounds = new google.maps.LatLngBounds();
    if (lstProyectos.length > 0) {
        for (var j = 0; j < lstProyectos.length; j++) {
            for (var k = 0; k < lstProyectos[j].UnidadTerr.length; k++) {
                var glPosition = new google.maps.LatLng(lstProyectos[j].UnidadTerr[k].coordenada.lat, lstProyectos[j].UnidadTerr[k].coordenada.lng);

                lstProyectos[j].provincia = lstProyectos[j].UnidadTerr[k].coordenada.provincia;
                lstProyectos[j].canton = lstProyectos[j].UnidadTerr[k].coordenada.canton;
                lstProyectos[j].parroquia = lstProyectos[j].UnidadTerr[k].coordenada.parroquia;

                drawMarker(glPosition, lstProyectos[j]);
            }
        }
    }
    //mapContratos.fitBounds(bounds);
}

/**
 * 
 * @param {type} position
 * @param {type} data
 * @returns {undefined}
 */
function drawMarker(position, data) {
    //var position = new google.maps.LatLng(coordenada.lat, coordenada.lng);
    var marker = new google.maps.Marker({
        map: mapContratos,
        position: position
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
        maxWidth: 400,
        height: 230,
        autoScroll: true,
        content: contentString
    });
    google.maps.event.addListener(marker, 'click', function() {
        if (gMarkerSelect != null) {
            gMarkerSelect.infowindows.close();
        }
        infowindow.open(mapContratos, marker);
        google.maps.event.addListener(infowindow, 'domready', function() {
            jQuery("#tabsinfo").tabs().height(220);
            jQuery("#tabImg").click(function() {
                jQuery("#carrusel").jcarousel();
            });
        });
        marker.infowindows = infowindow;
        gMarkerSelect = marker;
    });
    // evento para juntar el ingoeindos al dom

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
    cad += '        <li><a id="tabImg" href="#tabImagenes">Imagenes</a></li>';
    cad += '    </ul>';
    cad += '    <div id="tabProyectos">';
    cad += '         <div class="contentTab">';
    cad += '         ' + getProyecto(data);
    cad += '         </div>';
    cad += '    </div>';
    cad += '    <div id ="tabCovertura">';
    cad += '         <div class="contentTab">';
    cad += '        ' + getCoberturaProyecto(data);
    cad += '         </div>';
    cad += '    </div>';
    cad += '    <div id ="tabImagenes">';
    cad += '         <div class="contentTab">';
    cad += '        ' + getImagenes(data);
    cad += '         </div>';
    cad += '    </div>';
    cad += '</div>';
    return cad;
}
/**
 * 
 * @param {type} data
 * @returns {String}
 */
function getProyecto(data) {

    var duracion = (data.inpDuracion_stmdoPry) ? data.inpDuracion_stmdoPry : "0";
    var unidMedi = (data.strSimbolo_unimed) ? data.strSimbolo_unimed : "";

    var provincia = (data.provincia) ? data.provincia : "";
    var canton = (data.canton) ? data.canton : "";
    var parroquia = (data.parroquia) ? data.parroquia : "";

    var ubicacion = provincia + ', ' + canton + ', ' + parroquia;

    var cad = '';
    cad += '<table id="infowindo">';
    cad += '    <tr>';
    cad += '        <td                 style="text-align:center;"><img class="tableImgLog" src="images/logo_ecorae.png"></td>';
    cad += '        <td  colspan="2"    style="text-align:center;"><b> ' + data.strNombre_pry + ' </b></td>';
    cad += '        <td                 style="text-align:center;"><img style="height: 50px;width:50px" class="tableImgLog" src="components/com_proyectos/images/' + data.intCodigo_pry + '/icon/' + data.intCodigo_pry + '.jpg"></td>';
    cad += '    </tr>';
    cad += '    <tr> ';
    cad += '        <td  colspan="4"><hr></td> ';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <td colspan="4" style="width: 25%"><b>Duraci&oacute;n :</b>' + duracion + " ( " + unidMedi + " )" + '</td>';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <td><b>Fecha Inicio:</b></td>';
    cad += '        <td>' + data.dteFechaInicio_stmdoPry + '</td>';
    cad += '        <td><b>Fecha Fin:</b></td>';
    cad += '        <td>' + data.dteFechaFin_stmdoPry + '</td>';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <td><b>Ubicación:</b></td>';
    cad += '        <td colspan="3">' + ubicacion + '</td>';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <td colspan="4">' + verMasProyecto(data) + '</td>';
    cad += '    </tr>';
    cad += '</table>';
    return cad;
}

/**
 * @param {type} data
 * @returns {String}}
 */
function getImagenes(data) {
    var cad = '';
    if (data && data.imagenes && data.imagenes) {
        var arrayImg = data.imagenes;
        cad += '<div id="carrusel" class="contentTab">';
        cad += '     <div class="jcarousel-skin-ie7">';
        cad += '         <div class="jcarousel-container">';
        cad += '             <div id="carousel" class="jcarousel-clip" >';
        cad += '                 <ul class="jcarousel-list jcarousel-list-horizontal">';

        if (arrayImg && arrayImg.length > 0) {
            var path = 'http://' + window.location.host + '/components/com_proyectos/images/' + data.intCodigo_pry + '/images/';
            for (var i = 0; i < arrayImg.length; i++) {
                cad += '             <li class="jcarousel-item-' + (i + 1) + '">';
                cad += '                 <a  href="' + path + arrayImg[i].strNombre_img + '" ';
                cad += '                     rel="lightbox[roadtrip]">';
                cad += '                     <img    class="imgCarrusel"';
                cad += '                             src= "' + path + arrayImg[i].strNombre_img + '"';
                cad += '                     />';
                cad += '                 </a>';
                cad += '             </li>';
            }
        } else {
            var defPath = 'http://' + window.location.host + '/images/logo_default.jpg';
            cad += '                 <li class="jcarousel-item-1">';
            cad += '                     <a  href="' + defPath + '" ';
            cad += '                         rel="lightbox[roadtrip]">';
            cad += '                         <img    class="imgCarrusel"';
            cad += '                                 src= "' + defPath + '"';
            cad += '                         />';
            cad += '                     </a>';
            cad += '                 </li>';

        }
        cad += '                 </ul>';
        cad += '             </div>';
        cad += '             <div disabled="disabled" class="jcarousel-prev jcarousel-prev-disabled"></div>';
        cad += '             <div class="jcarousel-next"></div>';
        cad += '         </div>';
        cad += '     </div>';
        cad += '</div>';

    }
    return cad;
}


/**
 * 
 * @param {type} data
 * @returns {String}
 */
function verMasProyecto(data) {
    var ancla = '';
    ancla += '<a href="/index.php?option=com_proyectos&view=proyectoview&idPrograma=' + data.intCodigo_prg + '&idProyecto=' + data.intCodigo_pry + '">Ver mas...</a>';
    return ancla;
}


function getCoberturaProyecto(undTerritoria) {
    var uniter = '';
    uniter += '<div style="width: 100%; height: 140px; overflow-y: scroll;">';
    uniter += ' <table>';
    for (var j = 0; j < undTerritoria.UnidadTerr.length; j++) {
        var provincia = (undTerritoria.UnidadTerr[j].coordenada.provincia) ? undTerritoria.UnidadTerr[j].coordenada.provincia : '';
        var canton = (undTerritoria.UnidadTerr[j].coordenada.canton) ? undTerritoria.UnidadTerr[j].coordenada.canton : '';
        var parroquia = (undTerritoria.UnidadTerr[j].coordenada.parroquia) ? undTerritoria.UnidadTerr[j].coordenada.parroquia : '';
        var ubicacion = provincia + ',' + canton + ',' + parroquia;
        uniter += '<tr>';
        uniter += ' <td>';
        uniter += ' ' + ubicacion;
        uniter += ' </td>';
        uniter += '</tr>';
    }
    uniter += ' </table>';
    uniter += '</div>';
    return uniter;
}
