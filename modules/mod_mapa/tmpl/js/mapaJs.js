var poligono = new Array();
var puntos = new Array();
var gMarker = null;
/**
 * Gestiona la presentación en nodo
 * @param {object} node
 * @returns {undefined}
 */
function draw(node) {
    if (node.data) {
        if (node.data.elemento.ubUniTe) {
            var puntos = node.data.elemento.ubUniTe;
            for (var i = 0; i < puntos.length; i++) {
                drawPoint(puntos, node, i);
            }
        }
    }
}

/*
 *@name hideGrafic
 *@param idNode ide del nodo que sera borrado de el mapa
 *@description oculta los markers o poligonos del mapa.
 */
function hideGrafic(idNode) {
    var node = Mif.id(idNode);
    if (node && node.data && node.data.grafico) {
        for (var i = 0; i < node.data.grafico.length; i++) {
            node.data.grafico[i].setMap(null);
            hideGlFigsProye(node.id);//ocultamos los graficos si esque existen 
        }
        node.data.grafico = null;
    }
}


function ImageExist(url) 
{
    var img = new Image();
    var http = new XMLHttpRequest();
    img.src = url;

    http.open( 'HEAD', img.src, false );
    http.send();

    return http.status != 404;
}

/*
 *@name drawPoint 
 *@description  Dibuja un Marquer desde una array de coordenadas
 *@param punto  array de un punto de corrdenada 
 *@param node   Nodo del proyecto que es dibujado en el mapa.
 *@param index  posicion en el arrays.
 */
function drawPoint(punto, node, index) {// dibuja los proyectos , es muy diferente al que dibuja los graficos de un proyecto 
    if (node && node.data) {
        if (punto[index].fltLatitud_cord && punto[index].fltLongitud_cord) {
            var myLatlng = new google.maps.LatLng(punto[index].fltLatitud_cord, punto[index].fltLongitud_cord);
            var imagen;

            //  Iconos propios del proyecto
            var iconoProyecto = 'http://' + window.location.host + '/components/com_proyectos/images/' + node.data.id + '/icon/'+ node.data.iconoName;
            
            //  Iconos de proyectos historicos
            var icoPryHistoricos = 'http://' + window.location.host + '/modules/mod_mapa/assets/proyImages/' + node.data.id + '.png';
            
            if( ImageExist( iconoProyecto ) ){
                imagen = iconoProyecto;
            }else if( ImageExist( icoPryHistoricos ) ){
                imagen = icoPryHistoricos;
            }else{
                imagen = 'http://' + window.location.host + '/modules/mod_mapa/assets/proyImages/point.png';
            }

            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: imagen,
                title: "Proyecto",
                ubicacion: punto[index].strNombre_ut
            });

            addInfoWindoPoint(marker, node);
            map.setCenter(myLatlng);

            if (!node.data.grafico){
                node.data.grafico = new Array();
            }

            node.data.grafico[index] = marker;
        }
    }
}
/*
 * @name get_center
 * @param poligono necesita un objeto poligono de google maps
 * @description Centra el mapa en los bordes de un poligono
 * 
 */
function center(poligono) {
    var bounds = new google.maps.LatLngBounds( );
    if (poligono) {
        var path = poligono.getPath();
        for (var j = 0; j < path.getLength(); j++) {
            var point = path.getAt(j);
            bounds.extend(point);
        }
        map.fitBounds(bounds);
    }
    else {
        map.fitBounds(map.getCenter());
    }
}


/**
 *  @description crea la cadena para tener check o no las obras 
 * @param {JSON} node
 * @returns {String} cadena HTML
 */
function getcadChkShowFigs(node) {
    var cad = "";
    if (node && node.data.id) {
        if (node.data.elemento.figuras.flagShowfig == true) {
            cad = "<input id='proy-" + node.data.id + "' type='checkbox' onclick=drawHideProyFigs('proy-" + node.data.id + "') checked > Mostrar Obras.";
        } else
            cad = "<input id='proy-" + node.data.id + "' type='checkbox' onclick=drawHideProyFigs('proy-" + node.data.id + "') > Mostrar Obras.";

    }
    return cad;
}
/**
 * @description crea el HTML que forma el carrusel de las imgenes
 * @param {JSON} node
 * @returns {String} cadena HTML
 */
function getImgsProyecto(node) {
    var cadena = '';
    if (node.data && node.data.elemento && node.data.elemento.fotos) {
        var arrayImg = node.data.elemento.fotos;
        var cadena = '<div style="overflow: auto;">' +
                '<div class="jcarousel-skin-ie7">' +
                '   <div class="jcarousel-container">' +
                '       <div id="carousel" class="jcarousel-clip" >' +
                '           <ul class="jcarousel-list jcarousel-list-horizontal">';
        var imagenes2 = "";
        
        if (arrayImg && arrayImg.length > 0) {

            var path = 'http://' + window.location.host + '/components/com_proyectos/images/' + node.data.id + '/images/';

            for (var i = 0; i < arrayImg.length; i++) {
                imagenes2 = '   <li class="jcarousel-item-' + (i + 1) + '">' +
                                '       <a  href="' + path + arrayImg[i].strNombre_img + '" ' +
                                '           rel="lightbox[roadtrip]">' +
                            '           <img src= "' + path + arrayImg[i].strNombre_img + '"' +
                            '       />' +
                            '   </a>' +
                            '</li>';

                cadena = cadena + imagenes2;
            }
        } else {
            var defPath = 'http://' + window.location.host + '/images/logo_default.jpg';
            imagenes2 =
                    '<li class="jcarousel-item-1">' +
                    '   <a  href="' + defPath + '" ' +
                    '       rel="lightbox[roadtrip]">' +
                    '       <img src= "' + defPath + '"/>' +
                    '   </a>' +
                    '</li>';
            cadena = cadena + imagenes2;
        }
        cadena = cadena + '</ul></div>' +
                '<div disabled="disabled" class="jcarousel-prev jcarousel-prev-disabled"></div>' +
                '<div class="jcarousel-next"></div>' +
                '</div></div></div>';

    }
    return cadena;
}

/**
 * Retorna la ventana que muestra la información del proyecto.
 * 
 * @param {object}  node        Nodo con la información del proyecto.
 * @param {objet}   ubicacion   Array con la ubicacion del proyecto.
 * @returns {String}            Cadena con el html con la información del proyecto.
 */
function getCadInfoProyecto(node, ubicacion) {
    var nombre = (node.name)? node.name 
                            : '';
    
    var duracion = (node.data.inpDuracion_stmdoPry) ? node.data.inpDuracion_stmdoPry 
                                                    : '';
    
    var uniMedida = (node.data.simUnidadMedia)  ? '( ' + node.data.simUnidadMedia + ' )' 
                                                : '';
    
    var fechaInicio = (node.data.fInicioEst)? node.data.fInicioEst 
                                            : '';
    
    var fechafin = (node.data.fFinEst)  ? node.data.fFinEst 
                                        : '';

    var iconName = (node.data.iconoName)? node.data.iconoName 
                                        : 'default.png';

    var cad = '';
    cad += '<table id="infowindo">';
    cad += '    <tr>';
    cad += '        <td style="text-align:center;"> &nbsp; </td>';
    cad += '        <td style="text-align:center;"><b> ' + nombre + ' </b></td>';
    cad += '        <td style="text-align:center;"> <img class="tableImgLog" src="images/logo_ecorae.png"> </td>';
    cad += '    </tr>';
    cad += '</table>';
    cad += '<hr>';
    cad += '<table id="infowindo" width="100%" class="tablesorter" cellspacing="1">';
    cad += '    <tr>';
    cad += '        <th colspan="2">Duraci&oacute;n: </th>';
    cad += '        <td colspan="2"><b>'+ duracion + ' ' + uniMedida + '</b></td>';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <th>Fecha&nbsp;Inicio:</th>';
    cad += '        <td><b>' + fechaInicio + '</td>';
    cad += '        <th>Fecha&nbsp;Fin:</th>';
    cad += '        <td><b>' + fechafin + '</td>';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <th>Ubicaci&oacute;n:</th>';
    cad += '        <td colspan="3"><b>' + ubicacion + '</b></td>';
    cad += '    </tr>';
    cad += '    <tr>';
    cad += '        <td colspan="4">' + getcadChkShowFigs(node) + '</td>';
    cad += '    </tr>';
    cad += '</table>';

    return cad;
}

/**
 * @description genera el HTML que muestra las unidades territoriales donde esta el proyecto
 * @param {type} node
 * @returns {undefined}
 */
function getCadCobertura(node) {
    var cad = '';
    
    cad += '<br>';
    cad += '    <table width="100%" class="tablesorter" cellspacing="1">';

    if (node && node.data && node.data.elemento.cobertura) {
        var cobertura = node.data.elemento.cobertura;
        cad += '<thead>';
        cad += '    <tr>    <th align="center"> <b>Provincia</b> </th>'
        cad += '            <th align="center"> <b>Cant&oacute;n</b> </th>'
        cad += '            <th align="center"> <b>Parroquia</b> </th> </tr>';
        cad += '</thead>';

        for (var j = 0; j < cobertura.length; j++) {
            cad += '<tr>    <td align="center">' + cobertura[j].provincia + '</td>';
            cad += '        <td align="center">' + cobertura[j].canton + '</td>';
            cad += '        <td align="center">' + cobertura[j].parroquia + '</td> </tr>';
        }
    }else{
        cad += '<td align="center">Sin Registros Disponibles</td>';
    }
    
    cad += '    </table>';

    return  cad;
}

/*
 * @name <b>infWindContent
 * @description forma la cadena HTML que contiene la ventana de información
 * @param <b>node</b> onbjeto JSON con los datos necesarios
 * @return cad con la cadena HTML
 * @type string
 */
function infWindContent(node, ubicacion) {
    var cad = '';
    cad += '<div id="infTabs" class="tabdiv" style="height: 220px;width: 370px;">';
    cad += '    <ul>';
    cad += '        <li><a href="#infTabs-1">Proyecto</a></li>';
    cad += '        <li><a href="#infTabs-2">Cobertura</a></li>';
    cad += '        <li><a href="#infTabs-3" id="tabCarruselImg">Imagenes</a></li>';
    cad += '    </ul>';
    cad += '    <div id="infTabs-1">';
    cad += '        <div class="contentTab" style="height: 180px;">';
    cad += '            ' + getCadInfoProyecto(node, ubicacion);
    cad += '        </div>';
    cad += '    </div>';
    cad += '    <div id="infTabs-2">';
    cad += '        <div class="contentTab">';
    cad += '        ' + getCadCobertura(node);
    cad += '        </div>';
    cad += '    </div>';
    cad += '    <div id="infTabs-3">';
    cad += '        <div class="contentTab">';
    cad += '        ' + getImgsProyecto(node);
    cad += '        </div>';
    cad += '    </div>';
    cad += '</div>';
    return cad;
}
/*
 * @name addInfoWindoPoint
 * @description Agrega la ventana de información a un marker con un solo punto
 * @param elemento objeto google.map.marker 
 * @param nodo objeto mifftree
 */
function addInfoWindoPoint(elemento, nodo) {
    var infwind = new google.maps.InfoWindow({  maxWidth: 400,
                                                maxHeight: 240,
                                                autoScroll: true });
                                            
    elemento.nodo = nodo;

    if (!infwind.id){
        infwind.id = nodo.id;
    }
    
    google.maps.event.addListener(elemento, 'click', function() {
        if (gMarker){   gMarker.close();
                        gMarker = null;
                    }
                    
        infwind.setContent(infWindContent(elemento.nodo, elemento.ubicacion));
        infwind.open(map, elemento);
        
        google.maps.event.addListener(infwind, 'domready', function() {
            jQuery("#infTabs").height(220).tabs({   active: 1, 
                                                    fxAutoHeight: true });

            jQuery("#tabCarruselImg").click(function() {
                jQuery('#carousel').jcarousel({ auto: 2,    
                                                wrap: 'first'
                });
            });
        });

        gMarker = infwind;
    });
    
    infwind.node = nodo;
    google.maps.event.addListener(infwind, 'closeclick', function() {
        if (infwind.node.data.elemento.figuras.flagShowfig == true) {
            jConfirm("¿Ocultar obras?", "SIITA - ECORAE", function(r) {
                if (r) {
                    drawHideProyFigs('proy-' + infwind.node.data.id)
                } else {

                }
            });
        }
    });
    nodo.data.infowindow = infwind;
}
/*
 * @name hideAllShow
 * @description Oculta todos los graficos que están siendo mostrados
 */
function hideAllShow() {
    proytree.root.recursive(function() {
        hideGrafic(this.id);
    });
}
/**
 * 
 * centra el mapa y asigna un zoom
 * 
 * @param {double}  lat     Latitud del punto  
 * @param {double}  longi   longitud del punto
 * @param {int}     zoom    zoom a ser asignado.
 * @returns {undefined}
 */
function setMapCenterZonm(lat, longi, zoom) {
    map.setCenter(new google.maps.LatLng(lat, longi));
    map.setZoom(zoom);
}
/*
 * @name checkAllProgramsProyects
 * @param node , tipo "PROGRAMAS"
 * @description Marca el check todos los PROYECTOS de un PROGRAMA
 */
function checkAllProgramsProyects(node) {
    if (node && node.type[0] == "programa") {
        var misHijos = node.getChildren();
        if (misHijos.length >= 0) {
            for (var i = 0; i < misHijos.length; i++) {
                if (misHijos[i].state.checked != 'checked')
                    misHijos[i]['switch']();
            }
        }
    }
}
/*
 * @name unCheckAllProgrmasProyect
 * @param node , tipo "PROGRAMAS"
 * @description Retira el check de todos los PROYECTOS de un PROGRAMA
 */
function unCheckAllProgrmasProyect(node) {
    if (node && node.type[0] == "programa") {
        var misHijos = node.getChildren();
        if (misHijos.length >= 0) {
            for (var i = 0; i < misHijos.length; i++) {
                if (misHijos[i].state.checked == 'checked')
                    misHijos[i]['switch']();
            }
        }
    }
}
/*
 * @description Dibujas todas las figuras de un nodo
 */
function drawHideProyFigs(nodeId) {
    //recorreriamos todo el arrar de figuras
    var status = jQuery("#" + nodeId).is(':checked');
    var node = Mif.id(nodeId);
    if (status) {
        if (node && node.data.elemento.figuras) {
            var figurasArray = node.data.elemento.figuras;
            for (var m = 0; m < figurasArray.length; m++) {
                drawFigs(figurasArray[m], m, nodeId);
            }
        }
        //
        node.data.elemento.figuras.flagShowfig = true;
    } else {
        hideGlFigsProye(nodeId);
        node.data.elemento.figuras.flagShowfig = false;
    }
}

/*
 *@description identifica que figura dibujar
 *@param fig resive un figura , misma que contiene un id para poder dibujar.
 *@param index posicion en el array de figuras de un proyecto
 *@param nodeid identificador de el nodo.
 */
function drawFigs(fig, index, nodeid) {
    if (fig && fig.idTipFig)
        switch (fig.idTipFig) {
            case "1"://dibuja un punto
                drawPointFig(fig, index, nodeid);
                break;
            case "2"://dibuja una poli-linea
                drawLineFig(fig, index, nodeid);
                break;
            case "3"://dibuja un poligono
                drawPoligFig(fig, index, nodeid);
                break;
            case "4"://dibuja un circulo
                //todo@ fiuncion que dibuje los elementos tipo 4 (Circulo.)
                drawHideGraficCircle( fig, index, nodeid )
                break;
        }
}
/*
 *@name getPathgrafic
 *@description Crea un array de objetos coordenadas de google.
 *@param arrayPoints arrey de coordenasa tipo lat y longi
 */
function getPathgrafic(arrayPoints) {
    var arrayGooglePoints = new Array()
    for (var j = 0; j < arrayPoints.length; j++) {
        var punto = new google.maps.LatLng(arrayPoints[j].lat, arrayPoints[j].longi);
        arrayGooglePoints.push(punto);
    }
    return arrayGooglePoints;
}

/*
 *@name saveGraficInNode
 *@description almacena el grafico el nodo que le corresponde como un <br>
 *      un objeto de google
 *@param nodeid identificador de el nodo
 *@param index posicion de el vector en el que se va agregar, <br>
 *          es la misma que la del objeto
 *@param graficGL es el objeto de google que sera almacenado.
 */
function saveGraficInNode(nodeid, index, graficGL) {
    var node = Mif.id(nodeid);
    if (node.data.elemento.figuras)
        node.data.elemento.figuras[index].graficGl = graficGL;

}


/*
 * @description Dibuja un punto
 * @param fig arrary que contiene el punto del gráfico que sera dibujado <br>
 *         <b>Nota:</b> es array contiene un solo elemento.
 * @param index  posicion en la que se encuentra la figura
 * @param nodeid ID de el nodo
 */
function drawPointFig(fig, index, nodeid) {
    if (fig && fig.figpoints) {
        var myLatlng = new google.maps.LatLng(fig.figpoints[0].lat, fig.figpoints[0].longi);// posicion .
        var pointGl = new google.maps.Marker({//parametros de el punto
            position: myLatlng,
            map: map,
            icon: 'http://' + window.location.host + '/modules/mod_mapa/assets/proyImages/point.png',
            title: "Punto"
        });
        saveGraficInNode(nodeid, index, pointGl)
    }
}

/*
 *@name drawLineFig
 *@description Dibuja una poli-linea
 *@param fig objeto que contiene el array de puntos que formaran la linea
 *@param index  posicion en la que se encuentra la figura
 *@param nodeid ID de el nodo
 */
function drawLineFig(fig, index, nodeid) {
    if (fig && fig.figpoints) {
        var line = new google.maps.Polyline({
            path: getPathgrafic(fig.figpoints),
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 2,
            map: map
        });
        saveGraficInNode(nodeid, index, line)
    }
}
/*
 *@name drawPoligFig 
 *@description Dibuja un poligono desde una array de coordenadas
 *@param fig array de puntos corrdenadas 
 *@param index  posicion en la que se encuentra la figura
 *@param nodeid ID de el nodo
 */
function drawPoligFig(fig, index, nodeid) {
    if (fig && fig.figpoints) {
        var poligono = new google.maps.Polygon({
            paths: getPathgrafic(fig.figpoints),
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35
                    // map:map
        });

        poligono.setMap(map);
        saveGraficInNode(nodeid, index, poligono);
    }
}

/*
 * @name hideGlFigsProye
 * @description oculta todos los gáficos de un proyecto.
 * @param nodeId
 */
function hideGlFigsProye(nodeId) {
    var node = Mif.id(nodeId);
    //node.data.elemento.figuras[0].graficGl
    if (node && node.data.elemento.figuras && node.data.elemento.figuras.length > 0) {
        var figs = node.data.elemento.figuras;
        for (var i = 0; i < figs.length; i++) {
            if (figs[i].graficGl)
                figs[i].graficGl.setMap(null);
        }
    }
}




/*
 *@name getGLatlng
 *@param lat Latitud de una coordenada.
 *@param lng Longitud de una coordenada.
 *@return  Coordenada lat,lng de google.
 *@type gloogle.point
 */
function getGLatlng( lat,lng )
{
    //aqui validar el rango
    var glPoint= new google.maps.LatLng( lat,lng );
    return glPoint
}

function getRadioRevese(center, border)
{
    var distancia = google.maps.geometry.spherical.computeDistanceBetween( center, border, 0 );
    return parseFloat(distancia.toFixed(2));
}


//  function drawHideGraficCircle(coordenadas, idRegGrafico){
function drawHideGraficCircle( fig, index, idRegGrafico ){
    if( fig.figpoints.length > 0 ){
//        if( proyGraficos && !proyGraficos[idRegGrafico] ){
            if( fig.figpoints[0] && fig.figpoints[1] ){
                var centro= getGLatlng(fig.figpoints[0].lat, fig.figpoints[0].longi);
                var borde = getGLatlng(fig.figpoints[1].lat, fig.figpoints[1].longi);
                var radio = getRadioRevese(centro, borde);
                
                var populationOptions = {
                    strokeColor     : "#FF0000",
                    strokeOpacity   : 0.8,
                    strokeWeight    : 2,
                    fillColor       : "#FF0000",
                    fillOpacity     : 0.35,
                    map             : map,
                    center          : getGLatlng( fig.figpoints[0].lat, fig.figpoints[0].longi ),
                    radius          : radio 
                };
                //todo Es necesario hacer un bound
                
                map.setCenter(getGLatlng( fig.figpoints[0].lat, fig.figpoints[0].longi ) );
                var glCircle = new google.maps.Circle(populationOptions);
                
                saveGraficInNode( idRegGrafico, index, glCircle );
            }
            //cambiando el texto
            jQuery( '#showGraf-'+idRegGrafico ).html("Ocultar");
//        }else{
//            proyGraficos[idRegGrafico].setMap(null);
//            proyGraficos[idRegGrafico] = null;
//            //cambiando el texto
//            jQuery( '#showGraf-'+idRegGrafico ).html("Ver");
//        }
    }
}