var regCoordenada = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

function Coordenada(data) {
    if (data == null) {
        data = {
            "idCoordenada": 0,
            "lat": "0",
            "lng": "0",
            "published": 1};
    }
    this.idCoordenada = (data.idCoordenada) ? data.idCoordenada : "0";
    this.lat = (data.lat) ? data.lat : "0";
    this.lng = (data.lng) ? data.lng : "0";
    this.published = (data.published) ? data.published : "1";
}

jQuery(document).ready(function() {

//<editor-fold defaultstate="collapsed" desc="Coordenadas Circulo">
    
    /**
     * Agraga una coordenada del grafico tipo circulo
     */
    jQuery('#addCirculoGrafico').click(function() {
        var data = new Array();
        data["lat"] = jQuery("#jform_dcmLatitud_coorCir").val();
        data["lng"] = jQuery("#jform_dcmLongitud_coorCir").val();
        data["rad"] = jQuery("#jform_dcmRadio_coorCir").val();
        if (validarCoordenadaGrafico( data )) {
            addCirculoCoordendas(regGrafico, data);
            addCirculoGraficoTable();
            if ( regCoordenada == 1 ) {
                jQuery("#addCirculoGrafico").attr( "value", JSL_ADD_COORDENADA);
                jQuery("#cancelCirculoGrafico").attr( "value", JSL_CLEAN);
                jQuery('#ecc-' + regCoordenada).html (JSL_EDIT );
            }
            clCmpCirculoaGrafico();
        } else {
            jAlert( JSL_ALERT_ALL_NEED, SIITA_ECORAE );
            return;
        }
    });
    
    /**
     * Edición de la coordenada cuando el gráfico es un circulo.
     */
    jQuery(".editCirculoCoordenada").live("click", function() {
        regCoordenada = 1;
        var lat = 0;
        var lng = 0;
        var centro = 0;
        var borde = 0;
        var radio = 0;
        jQuery('#ecc-' + regCoordenada).html (JSL_EDITANDO );
        if (oGrafico.lstCoordenadas.length > 0) {
            centro = getGLatlng(oGrafico.lstCoordenadas[0].lat, oGrafico.lstCoordenadas[0].lng);
            borde = getGLatlng(oGrafico.lstCoordenadas[1].lat, oGrafico.lstCoordenadas[1].lng);
            radio = getRadioRevese(centro, borde);
            lat = oGrafico.lstCoordenadas[0].lat;
            lng = oGrafico.lstCoordenadas[0].lng;
        }
        
        jQuery("#addCirculoGrafico").attr( "value", JSL_SAVE_CHANGES);
        jQuery("#cancelCirculoGrafico").attr( "value", JSL_CANCEL_COOR);
        availableFrmCoorCir( 1 );
        jQuery("#jform_dcmLatitud_coorCir").val(lat);
        jQuery("#jform_dcmLongitud_coorCir").val(lng);
        jQuery("#jform_dcmRadio_coorCir").val(radio);
    });
    
    /**
     * eliminar la coordenada de un circulo.
     */
    jQuery(".delCirculoCoordenada").live("click", function() {
        jAlert( JSL_CANT_DELETE_CIRC, SIITA_ECORAE );
    });
    
    /**
     *  Cancela la edicon de una coordenada
     */
    jQuery("#cancelCirculoGrafico").click(function() {
        if ( regCoordenada == 1) {
            jQuery("#addCirculoGrafico").attr( "value", JSL_ADD_COORDENADA);
            jQuery("#cancelCirculoGrafico").attr( "value", JSL_CLEAN);
            jQuery('#ecc-' + regCoordenada).html (JSL_EDIT );
            clCmpCirculoaGrafico();
        } else {
            jQuery("#jform_dcmLatitud_coorCir").val("");
            jQuery("#jform_dcmLongitud_coorCir").val("");
            jQuery("#jform_dcmRadio_coorCir").val("");
        }
    });
    
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Coordenadas Grafico">
    
    /**
     * agrego una coordenada.
     */
    jQuery('#addCoordenadaGrafico').click(function() {
        var data = new Array();
        data["lat"] = jQuery("#jform_dcmLatitud_coor").val();
        data["lng"] = jQuery("#jform_dcmLlong_coor").val();
        if (validarCoordenadaGrafico(data)) {
            if (regCoordenada == 0) {
                var oCoordenada = new Coordenada(data);
                oCoordenada.regCoordenada = oGrafico.lstCoordenadas.length + 1;
                if ( oGrafico.idTipoGrafico == 1 ) {
                    availableCoorGrf(0);
                }
                addCoordenadaGraficoList(oCoordenada);
                addCoordenadaGraficoTable(oCoordenada);
                clCmpCoordenadaGrafico();
            } else {
                data["regCoordenada"] = regCoordenada;
                actCoordenadaGrafico(data);
                regCoordenada = 0;
            }
            if ( oGrafico.idTipoGrafico == 1 ) {
                availableCoorGrf(0);
            }
        } else {
            jAlert( JSL_ALERT_ALL_NEED, SIITA_ECORAE );
            return;
        }
    });
    
    /**
     * 
     */
    jQuery('.editCoordenada').live('click', function() {
        var regCoor = this.parentNode.parentNode.id;
        if ( regCoordenada != regCoor ){
            if ( regCoordenada != 0 ) {
                jQuery('#ecg-' + regCoordenada).html (JSL_EDIT );
            }
            regCoordenada = regCoor;
            jQuery('#ecg-' + regCoordenada).html (JSL_EDITANDO );
            var coordenada = getCoordenadaGrafico(regGrafico, regCoordenada);
            jQuery('#coordendasGrafico').css("display", "block");
            jQuery("#jform_dcmLatitud_coor").val(coordenada.lat);
            jQuery("#jform_dcmLlong_coor").val(coordenada.lng);
            jQuery("#addCoordenadaGrafico").attr( "value", JSL_SAVE_CHANGES);
            jQuery("#cancelCoordenadaGrafico").attr( "value", JSL_CANCEL_COOR);
            if ( oGrafico.idTipoGrafico == 1 ) {
                availableCoorGrf(1);
            }
        }
    });
    
    /**
     * Eliminar la coordenada de un grafico cualquiera
     */
    jQuery(".delCoordenada").live("click", function() {
        var regCoordenadaDel = this.parentNode.parentNode.id;
        if (oGrafico.idTipoGrafico != 1) {
            for (var k = 0; k < oGrafico.lstCoordenadas.length; k++) {
                if (oGrafico.lstCoordenadas[k].regCoordenada == regCoordenadaDel) {
                    oGrafico.lstCoordenadas[k].published = 0;
                }
            }
        } else {
            jAlert( JSL_CANT_DELETE_PUNTO_COOR, SIITA_ECORAE );
        }
        reloadCoordenadasGraficosTable();
    });
    
    jQuery("#cancelCoordenadaGrafico").click(function(event) {
        if ( oGrafico.idTipoGrafico == 1 && regCoordenada != 0 ) {
            availableCoorGrf(0);
        }
        if ( regCoordenada != 0 ){
            jQuery('#ecg-' + regCoordenada).html (JSL_EDIT );
        }
        clCmpCoordenadaGrafico();
    });

//</editor-fold>

});

/**
 *  Agrega una coordenada al grafico
 * @param {type} regGrafico
 * @param {type} oCoordenada
 * @returns {undefined}
 */
function addCoordenadaGraficoList(oCoordenada) {
    if (oGrafico.idTipoGrafico == 1) {
        jQuery("#tbLstCoordenadas > tbody").empty();
        oCoordenada.regCoordenada = 1;
        if (oGrafico.lstCoordenadas.length > 0) {
            oCoordenada.idCoordenada = oGrafico.lstCoordenadas[0].idCoordenada;
        } else {
            oCoordenada.idCoordenada = 0;
        }
        oGrafico.lstCoordenadas[0] = oCoordenada;
    } else {
        oGrafico.lstCoordenadas.push(oCoordenada);
    }
}

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actCoordenadaGrafico(data) {
    if ( oGrafico.lstCoordenadas.length > 0 ) {
        for (var k = 0; k < oGrafico.lstCoordenadas.length; k++) {
            if (oGrafico.lstCoordenadas[k].regCoordenada == data.regCoordenada) {
                oGrafico.lstCoordenadas[k].lat = data.lat;
                oGrafico.lstCoordenadas[k].lng = data.lng;
            }
        }
    }
    jQuery('#ecg-' + regCoordenada).html (JSL_EDIT );
    reloadCoordenadasGraficosTable();
    clCmpCoordenadaGrafico();
}

/**
 * agrega una fila a la lista de coordenadas
 * @param {type} oCoordenada
 * @returns {undefined}
 */
function addCoordenadaGraficoTable(oCoordenada) {
    var row = '';
    row += '<tr id="' + oCoordenada.regCoordenada + '">';
    row += ' <td>' + oCoordenada.lat + ' </td>';
    row += ' <td>' + oCoordenada.lng + ' </td>';
    row += ' <td style="width: 15px"><a id="dcg-' + oCoordenada.regCoordenada + '" class="verCoordenada" >Ver</a></td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a id="ecg-' + oCoordenada.regCoordenada + '" class="editCoordenada" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delCoordenada" >Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px">Editar</td>';
        row += ' <td style="width: 15px">Eliminar</td>';
    }

    row += '</tr>';
    jQuery('#tbLstCoordenadas  >tbody:last').append(row);
}

/**
 * 
 * @param {type} data
 * @returns {Boolean}
 */
function validarCoordenadaGrafico(data) {
    var flag = false;
    if ( typeof(data["rad"]) != "undefined" && data["rad"] != "" && data["lat"] != "" && data["lng"] != "" ) {
        flag = true;
    } else if (data["lat"] != "" && data["lng"] != "") {
        flag = true;
    }
    return flag;
}

//<editor-fold defaultstate="collapsed" desc="funciones para grafico de tipo circulo">

/**
 * 
 * @returns {undefined}
 */
function clCmpCirculoaGrafico() {
    jQuery("#jform_dcmLatitud_coorCir").val("");
    jQuery("#jform_dcmLongitud_coorCir").val("");
    jQuery("#jform_dcmRadio_coorCir").val("");
    
    availableFrmCoorCir( 0 );
    
    regCoordenada = 0;
}

/**
 *  habilita o desavilita los elementos del formulario del grafico tipo circulo
 * @param {type} op
 * @returns {undefined}
 */
function availableFrmCoorCir( op ){
    if ( op == 0 ) {
        jQuery("#jform_dcmLatitud_coorCir").attr("disabled", "disabled");
        jQuery("#jform_dcmLongitud_coorCir").attr("disabled", "disabled");
        jQuery("#jform_dcmRadio_coorCir").attr("disabled", "disabled");
        jQuery("#addCirculoGrafico").attr("disabled", "disabled");
        jQuery("#cancelCirculoGrafico").attr("disabled", "disabled");
    } else {
        jQuery("#jform_dcmLatitud_coorCir").removeAttr("disabled");
        jQuery("#jform_dcmLongitud_coorCir").removeAttr("disabled");
        jQuery("#jform_dcmRadio_coorCir").removeAttr("disabled");
        jQuery("#addCirculoGrafico").removeAttr("disabled");
        jQuery("#cancelCirculoGrafico").removeAttr("disabled");
    }
}

/**
 *  habilita o desavilita los elementos del formulario de grafico
 * @param {type} op
 * @returns {undefined}
 */
function availableCoorGrf( op ){
    if ( op == 0 ) {
        jQuery("#jform_dcmLatitud_coor").attr("disabled", "disabled");
        jQuery("#jform_dcmLlong_coor").attr("disabled", "disabled");
        jQuery("#addCoordenadaGrafico").attr("disabled", "disabled");
        jQuery("#cancelCoordenadaGrafico").attr("disabled", "disabled");
    } else {
        jQuery("#jform_dcmLatitud_coor").removeAttr("disabled");
        jQuery("#jform_dcmLlong_coor").removeAttr("disabled");
        jQuery("#addCoordenadaGrafico").removeAttr("disabled");
        jQuery("#cancelCoordenadaGrafico").removeAttr("disabled");
    }
}

/**
 * ingresa un circulo a la lista de graficos.
 * @param {type} regGrafico
 * @param {type} regCoordenda
 * @returns {undefined}
 */
function addCirculoCoordendas(regGrafico, data) {
    var centro = new Coordenada(data);
    var punto2 = getCirclePoint2(data.lat, data.lng, data.rad);
    var dataBorde = {"lat": punto2.lat(), "lng": punto2.lng()};
    var borde = new Coordenada(dataBorde);
    oGrafico.lstCoordenadas[0] = centro;
    oGrafico.lstCoordenadas[1] = borde;
}

/**
 * 
 * @param {type} lat
 * @param {type} lng
 * @param {type} radio
 * @returns {unresolved}
 */
function getCirclePoint2(lat, lng, radio) {
    var centro = getGLatlng(lat, lng);
    var punto_final = google.maps.geometry.spherical.computeOffset(centro, radio, 90);
    return punto_final;
}

/**
 * 
 * @param {type} center
 * @param {type} border
 * @returns {unresolved}
 */
function getRadioRevese(center, border) {
    var distancia = google.maps.geometry.spherical.computeDistanceBetween(center, border);
    return parseFloat(distancia.toFixed(2));
}

/**
 * 
 * @param {type} lat
 * @param {type} lng
 * @returns {google.maps.LatLng}
 */
function getGLatlng(lat, lng) {
    //aqui validar el rango
    var glPoint = new google.maps.LatLng(lat, lng);
    return glPoint;
}

/**
 * 
 * @returns {undefined}
 */
function reloadCoordenadasTables() {
    jQuery("#tbLstCoordenadasCirculo > tbody").empty();
    jQuery("#tbLstCoordenadas > tbody").empty();
}


/**
 * 
 * @returns {undefined}
 */
function addCirculoGraficoTable() {
    jQuery("#tbLstCoordenadasCirculo > tbody").empty();
    var lat = 0;
    var lng = 0;
    var centro = 0;
    var borde = 0;
    var radio = 0;
    if (oGrafico.lstCoordenadas.length > 0) {
        centro = getGLatlng(oGrafico.lstCoordenadas[0].lat, oGrafico.lstCoordenadas[0].lng);
        borde = getGLatlng(oGrafico.lstCoordenadas[1].lat, oGrafico.lstCoordenadas[1].lng);
        radio = getRadioRevese(centro, borde);
        lat = oGrafico.lstCoordenadas[0].lat;
        lng = oGrafico.lstCoordenadas[0].lng;
    }
    var row = '';
    row += '<tr id="1">';
    row += ' <td>' + lat + ' </td>';
    row += ' <td>' + lng + ' </td>';
    row += ' <td>' + radio + ' </td>';
    
    row += ' <td style="width: 15px"><a id="dcc-1" class="verCoordenada" >Ver</a></td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a id="ecc-1" class="editCirculoCoordenada" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delCirculoCoordenada" >Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px">Editar</td>';
        row += ' <td style="width: 15px">Eliminar</td>';
    }

    row += '</tr>';
    jQuery('#tbLstCoordenadasCirculo  >tbody:last').append(row);
}


//</editor-fold>









/**
 * 
 * @returns {undefined}
 */
function clCmpCoordenadaGrafico() {
    jQuery("#jform_dcmLatitud_coor").val("");
    jQuery("#jform_dcmLlong_coor").val("");
    if ( regCoordenada != 0 ) {
        jQuery("#addCoordenadaGrafico").attr( "value", JSL_ADD_COORDENADA);
        jQuery("#cancelCoordenadaGrafico").attr( "value", JSL_CLEAN);
    }
    regCoordenada = 0;
}

/**
 * 
 * @returns {undefined}
 */
function reloadCoordenadasGraficosTable() {
    jQuery("#tbLstCoordenadas > tbody").empty();
    if ( oGrafico.lstCoordenadas.length > 0 ) {
        for (var k = 0; k < oGrafico.lstCoordenadas.length; k++) {
            if (oGrafico.lstCoordenadas[k].published == 1)
                addCoordenadaGraficoTable(oGrafico.lstCoordenadas[k]);
        }
    }
}


/**
 * 
 * @param {type} regGrafico
 * @param {type} regCoordenada
 * @returns {unresolved}}
 */
function getCoordenadaGrafico(regGrafico, regCoordenada) {
    var coodenada = null
    for (var k = 0; k < oGrafico.lstCoordenadas.length; k++){
        if (oGrafico.lstCoordenadas[k].regCoordenada == regCoordenada)
            coodenada = oGrafico.lstCoordenadas[k];
    }
    return coodenada;
}


