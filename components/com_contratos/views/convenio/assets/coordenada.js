var regCoordenada = 0;
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
    jQuery("#addCoordenadaCirculoTable").click(function() {
        jQuery("#coordenasCirculoForm").css("display", "block");
    });

    jQuery("#addCoordenadaTable").click(function() {
        jQuery("#coordenasForm").css("display", "block");
    });
    jQuery("#cancelCoordenadaGrafico").click(function(event) {
        jQuery("#jform_dcmLatitud_coor").val("");
        jQuery("#jform_dcmLlong_coor").val("");

    });
    jQuery("#cancelCirculoGrafico").click(function(event) {
        jQuery("#jform_dcmLatitud_coorCir").val("");
        jQuery("#jform_dcmRadio_coorCir").val("");
        jQuery("#jform_dcmLongitud_coorCir").val("");
    });

    jQuery("#addGraficoTable").click(function(event) {
        jQuery("#jform_intId_tg").removeAttr("disabled", "");
        jQuery("#addCoordenadasFormGrafico").removeAttr("disabled", "");
        jQuery("#cancelCoordenadaGrafico").trigger('click');
        jQuery("#cancelCirculoGrafico").trigger('click');
        showFormsGrafico(0) ;
        disableCoordenadaBtn();
        reloadCoordenadasTables();
        regGrafico = 0;
        regCoordenada = 0;
        clCmpGrafico();
    });

    jQuery(".cancelGrafico").click(function() {
       // jQuery("#addGraficoTable").trigger('click');
        jQuery("#jform_intId_tg").attr("disabled", "disabled");
        jQuery("#addCoordenadasFormGrafico").attr("disabled", "disabled");
        jQuery('#ieavGraficoForm').css("display", "block");
        disableCoordenadaBtn();
        reloadCoordenadasTables();
        regGrafico = 0;
        regCoordenada = 0;
        clCmpGrafico();
    });
    /**
     * solo para las coordenadas normales.
     */

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
                var grafico = getGraficoByReg(regGrafico);
                oCoordenada.regCoordenada = grafico.lstCoordenadas.length + 1;
                addCoordenadaGraficoList(regGrafico, oCoordenada);
                addCoordenadaGraficoTable(oCoordenada);
                clCmpCoordenadaGrafico();
            }
            else {
                data["regCoordenada"] = regCoordenada;
                actCoordenadaGrafico(regGrafico, data);
                regCoordenada = 0;
            }
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }
    });
    /**
     * 
     */
    jQuery('.editCoordenada').live('click', function() {
        regCoordenada = this.parentNode.parentNode.id;
        var coordenada = getCoordenadaGrafico(regGrafico, regCoordenada);
        showFormsGrafico();
        jQuery('#coordendasGrafico').css("display", "block");
        jQuery("#jform_dcmLatitud_coor").val(coordenada.lat);
        jQuery("#jform_dcmLlong_coor").val(coordenada.lng);
    });
    /**
     * el caso del circulo
     */
    jQuery('#addCirculoGrafico').click(function() {
        var data = new Array();
        data["lat"] = jQuery("#jform_dcmLatitud_coorCir").val();
        data["lng"] = jQuery("#jform_dcmLongitud_coorCir").val();
        data["rad"] = jQuery("#jform_dcmRadio_coorCir").val();
        if (validarCoordenadaGrafico(data)) {
            var grafico = getGraficoByReg(regGrafico);
            if (grafico.lstCoordenadas.length > 0) {
                addCirculoCoordendas(regGrafico, data);
                addCirculoGraficoTable();
            }
            else {
                addCirculoCoordendas(regGrafico, data);
                addCirculoGraficoTable();
            }
            clCmpCirculoaGrafico();
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE");
            return;
        }
    });
    /**
     * Edición de la coordenada cuando el gráfico es un circulo.
     */
    jQuery(".editCirculoCoordenada").live("click", function() {
        var grafico = getGraficoByReg(regGrafico);
        regCoordenada = 1;
        var lat = 0;
        var lng = 0;
        var centro = 0;
        var borde = 0;
        var radio = 0;
        if (grafico.lstCoordenadas.length > 0) {
            centro = getGLatlng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng);
            borde = getGLatlng(grafico.lstCoordenadas[1].lat, grafico.lstCoordenadas[1].lng);
            radio = getRadioRevese(centro, borde);
            lat = grafico.lstCoordenadas[0].lat;
            lng = grafico.lstCoordenadas[0].lng;
        }
        jQuery('#circuloGrafico').css("display", "block");
        jQuery("#jform_dcmLatitud_coorCir").val(lat);
        jQuery("#jform_dcmLongitud_coorCir").val(lng);
        jQuery("#jform_dcmRadio_coorCir").val(radio);
    });

    /**
     * Eliminar la coordenada de un grafico cualquiera
     */
    jQuery(".delCoordenada").live("click", function() {
        var regCoordenadaDel = this.parentNode.parentNode.id;
        for (var j = 0; j < contratos.lstGraficos.length; j++) {
            if (contratos.lstGraficos[j].regGrafico == regGrafico)
                if (contratos.lstGraficos[j].idTipoGrafico != 1) {
                    for (var k = 0; k < contratos.lstGraficos[j].lstCoordenadas.length; k++) {
                        if (contratos.lstGraficos[j].lstCoordenadas[k].regCoordenada == regCoordenadaDel) {
                            contratos.lstGraficos[j].lstCoordenadas[k].published = 0;
                        }
                    }
                }
                else {
                    jAlert("No se puede eliminar la Coordenada de un Punto.", "SIITA-ECORAE");
                }
        }
        reloadCoordenadasGraficosTable();
    });
    /**
     * eliminar la coordenada de un circulo.
     */
    jQuery(".delCirculoCoordenada").live("click", function() {
        jAlert("No se puede eliminar la coordenada de un circulo.", "SIITA-ECORAE");
    });
});
/**
 * 
 * @param {type} regGrafico
 * @returns {undefined}
 */
function getGraficoByReg(regGrafico) {
    var data = null;
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            data = contratos.lstGraficos[j];
        }
    }
    return data;
}

function validarCoordenadaGrafico(data) {
    var flag = false;
    if (data["lat"] != "" && data["lng"] != "") {
        flag = true;
    }
    return flag;
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
    row += ' <td style="width: 15px"><a id="p-' + oCoordenada.regCoordenada + '" class="verCoordenada" >Ver</a></td>';
    row += ' <td style="width: 15px"><a class="editCoordenada" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delCoordenada" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLstCoordenadas  >tbody:last').append(row);
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actCoordenadaGrafico(regGrafico, data) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            for (var k = 0; k < contratos.lstGraficos[j].lstCoordenadas.length; k++) {
                if (contratos.lstGraficos[j].lstCoordenadas[k].regCoordenada == data.regCoordenada) {
                    contratos.lstGraficos[j].lstCoordenadas[k].lat = data.lat;
                    contratos.lstGraficos[j].lstCoordenadas[k].lng = data.lng;
                }
            }
        }
    }
    clCmpCoordenadaGrafico();
    reloadCoordenadasGraficosTable();
}

function clCmpCoordenadaGrafico() {
    jQuery("#jform_dcmLatitud_coor").val("");
    jQuery("#jform_dcmLlong_coor").val("");
}
/**
 * 
 * @returns {undefined}
 */
function clCmpCirculoaGrafico() {
    jQuery("#jform_dcmLatitud_coorCir").val("");
    jQuery("#jform_dcmLongitud_coorCir").val("");
    jQuery("#jform_dcmRadio_coorCir").val("");
}

function validarGrafico(data) {
    var flag = false;
    if (data["lat"] != "" && data["lng"] != "" && data["rdo"] != "") {
        flag = true;
    }
    return flag;
}

function reloadCoordenadasGraficosTable() {
    jQuery("#tbLstCoordenadas > tbody").empty();
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            for (var k = 0; k < contratos.lstGraficos[j].lstCoordenadas.length; k++) {
                if (contratos.lstGraficos[j].lstCoordenadas[k].published == 1)
                    addCoordenadaGraficoTable(contratos.lstGraficos[j].lstCoordenadas[k]);
            }
        }
    }
}

function addCoordenadaGraficoList(regGrafico, oCoordenada) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            if (contratos.lstGraficos[j].idTipoGrafico == 1) {
                jQuery("#tbLstCoordenadas > tbody").empty();
                oCoordenada.regCoordenada = 1;
                if (contratos.lstGraficos[j].lstCoordenadas.length > 0) {
                    oCoordenada.idCoordenada = contratos.lstGraficos[j].lstCoordenadas[0].idCoordenada;
                } else {
                    oCoordenada.idCoordenada = 0;
                }
                contratos.lstGraficos[j].lstCoordenadas[0] = oCoordenada;
            } else {
                contratos.lstGraficos[j].lstCoordenadas.push(oCoordenada);
            }
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
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            for (var k = 0; k < contratos.lstGraficos[j].lstCoordenadas.length; k++)
                if (contratos.lstGraficos[j].lstCoordenadas[k].regCoordenada == regCoordenada)
                    coodenada = contratos.lstGraficos[j].lstCoordenadas[k];
        }
    }
    return coodenada;
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
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            contratos.lstGraficos[j].lstCoordenadas[0] = centro;
            contratos.lstGraficos[j].lstCoordenadas[1] = borde;
        }
    }
}





function getCirclePoint2(lat, lng, radio) {
    var centro = getGLatlng(lat, lng);
    var punto_final = google.maps.geometry.spherical.computeOffset(centro, radio, 90);
    return punto_final;
}
function getRadioRevese(center, border) {
    var distancia = google.maps.geometry.spherical.computeDistanceBetween(center, border);
    return parseFloat(distancia.toFixed(2));
}
function getGLatlng(lat, lng) {
    //aqui validar el rango
    var glPoint = new google.maps.LatLng(lat, lng);
    return glPoint;
}


function reloadCoordenadasTables() {
    jQuery("#tbLstCoordenadasCirculo > tbody").empty();
    jQuery("#tbLstCoordenadas > tbody").empty();
}



function addCirculoGraficoTable() {
    jQuery("#tbLstCoordenadasCirculo > tbody").empty();
    var grafico = getGraficoByReg(regGrafico);
    var lat = 0;
    var lng = 0;
    var centro = 0;
    var borde = 0;
    var radio = 0;
    if (grafico.lstCoordenadas.length > 0) {
        centro = getGLatlng(grafico.lstCoordenadas[0].lat, grafico.lstCoordenadas[0].lng);
        borde = getGLatlng(grafico.lstCoordenadas[1].lat, grafico.lstCoordenadas[1].lng);
        radio = getRadioRevese(centro, borde);
        lat = grafico.lstCoordenadas[0].lat;
        lng = grafico.lstCoordenadas[0].lng;
    }
    var row = '';
    row += '<tr id="1">';
    row += ' <td>' + lat + ' </td>';
    row += ' <td>' + lng + ' </td>';
    row += ' <td>' + radio + ' </td>';
    row += ' <td style="width: 15px"><a id="p-1" class="verCoordenada" >Ver</a></td>';
    row += ' <td style="width: 15px"><a class="editCirculoCoordenada" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delCirculoCoordenada" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLstCoordenadasCirculo  >tbody:last').append(row);
}

function reloadCirculoGraficosTable() {
    jQuery("#tbLstCoordenadasCirculo > tbody").empty();
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            addCirculoGraficoTable(contratos.lstGraficos[j].lstCoordenadas[0]);
        }
    }
}

function enableCoordenadaBtn() {
    jQuery("#addCoordenadaGrafico").removeAttr("disabled", "");
    jQuery("#cancelCoordenadaGrafico").removeAttr("disabled", "");
    jQuery("#addCirculoGrafico").removeAttr("disabled", "");
    jQuery("#cancelCirculoGrafico").removeAttr("disabled", "");

}
function disableCoordenadaBtn() {
    jQuery("#addCoordenadaGrafico").attr("disabled", "disabled");
    jQuery("#cancelCoordenadaGrafico").attr("disabled", "disabled");
    jQuery("#addCirculoGrafico").attr("disabled", "disabled");
    jQuery("#cancelCirculoGrafico").attr("disabled", "disabled");

}
