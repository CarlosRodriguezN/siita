function Grafico(data) {
    if (data == null) {
        data = {
            "idGrafico": "0",
            "descripcion": "0",
            "lstCoordenadas": new Array(),
            "idTipoGrafico": 0,
            "nmbTipoGrafico": "Sin Nombre",
            "published": 0
        };
    }
    this.idGrafico = (data.idGrafico) ? data.idGrafico : "0";
    this.descripcion = (data.descripcion) ? data.descripcion : "0";
    this.idTipoGrafico = (data.idTipoGrafico) ? data.idTipoGrafico : "0";
    this.published = (data.published) ? data.published : "1";
    this.nmbTipoGrafico = (data.nmbTipoGrafico) ? data.nmbTipoGrafico : "Sin Nombre";
    this.lstCoordenadas = new Array();
    this.buildLstCoordenada(data.lstCoordenadas);

}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
Grafico.prototype.addCoordenada = function(data) {
    if (data != null) {
        var oCoordenada = new Coordenada(data);
        oCoordenada.regCoordenada = this.lstCoordenadas.length + 1;
        this.lstCoordenadas.push(oCoordenada);
    }
};
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
Grafico.prototype.buildLstCoordenada = function(data) {
    if (data != null) {
        for (var j = 0; j < data.length; j++) {
            this.addCoordenada(data[j]);
        }
    }
};

/**
 * 
 * HASTA AQUI LA CLASE.
 * 
 */

var regGrafico = 0;

jQuery(document).ready(function() {

    jQuery('#addCoordenadasFormGrafico').click(function(event) {
        var data = new Array();
        data["idTipoGrafico"] = jQuery("#jform_intId_tg").val();
        data["nmbTipoGrafico"] = jQuery("#jform_intId_tg option:selected").text();
        data["descripcion"] = jQuery("#jform_strDescripcionGrafico_crtg").val();
        if (validarGrafico(data)) {
            if (regGrafico == 0) {
                data["idGrafico"] = 0;
                data["published"] = 0;
                var oGrafico = new Grafico(data);
                oGrafico.regGrafico = contratos.lstGraficos.length + 1;
                regGrafico = contratos.lstGraficos.length + 1;
                contratos.lstGraficos.push(oGrafico);
                reloadCirculoGraficosTable();
                reloadCoordenadasGraficosTable();
            }
            else {
                data["regGrafico"] = regGrafico;
                actGrafico(data);
                if (data["idTipoGrafico"] == 4) {
                    reloadCirculoGraficosTable();
                } else {
                    reloadCoordenadasGraficosTable();
                }
            }
            jQuery("#addCoordenadasFormGrafico").attr("disabled", "disabled");
            jQuery("#jform_intId_tg").attr("disabled", "disabled");
            enableCoordenadaBtn();
            showFormsGrafico(data["idTipoGrafico"]);
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE");
            return;
        }
    });

//    jQuery('.editGrafico').live("click", function(event) {
//        var aux = this.parentNode.parentNode.id;
//        if (regGrafico == 0) {
//            //  recupero la data del array
//            cargaFormGrafico(aux);
//        } else {
//            jConfirm("¿Desea guardar los cambios?", "SIITA - ECORAE", function(r) {
//                if (r) {
//                    jQuery(".saveGrafico").trigger("click");
//                    cargaFormGrafico(aux);
//                    regGrafico = aux;
//                } else {
//                    cargaFormGrafico(aux);
//                }
//            });
//        }
//    });
    jQuery(".editGrafico").live("click", function() {
        regGrafico = this.parentNode.parentNode.id;
        cargaFormGrafico(regGrafico);
    });



    jQuery(".cancelGrafico").click(function(event) {
        jQuery("#tbLstCoordenadas > tbody").empty();
        jQuery("#tbLstCoordenadasCirculo > tbody").empty();
        jQuery('#editGraficoContent').css("display", "none");
        regGrafico = 0;

    });

    jQuery('.delGrafico').live("click", function() {
        var regGraficoDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmGrafico(regGraficoDel);
                reloadGraficosTable();
            } else {

            }
        });
    });
    /**
     * 
     */
    jQuery("#addMultaTable").click(function() {
        jQuery('#imgMultaForm').css("display", "none");
        jQuery('#editMultaForm').css("display", "block");
    });
    /**
     * 
     */
    jQuery(".saveGrafico").click(function(event) {
        jQuery("#addCoordenadasFormGrafico").trigger('click');
        publicGrafico(regGrafico);
        regGrafico = 0;
        clCmpGrafico();
        reloadGraficosTable();
        jQuery(".cancelGrafico").trigger('click');

    });
});


/**
 * Oculta los formularios de ingreso de coordenadas
 * @returns {undefined}
 */
function ocultarFormsCoordenadas() {
    jQuery('#coordendasGrafico').css("display", "none");
    jQuery('#circuloGrafico').css("display", "none");
}

function validarGrafico(data) {
    if (
            data["idTipoGrafico"] != 0 &&
            data["descripcion"] != ""
            )
    {
        return true;
    } else {
        return false;
    }
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addGrafico(data) {
    if (data.published == 1) {
        var row = '';
        row += '<tr id="' + data.regGrafico + '">';
        row += ' <td>' + data.descripcion + ' </td>';
        row += ' <td>' + data.nmbTipoGrafico + ' </td>';
        row += ' <td style="width: 15px"><a id="g-' + data.regGrafico + '" class="verGrafico">Ver</a></td>';
        row += ' <td style="width: 15px"><a class="editGrafico" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delGrafico" >Eliminar</a></td>';
        row += '</tr>';
        jQuery('#tbLtsGraficos > tbody:last').append(row);
    }
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpGrafico() {
    //jQuery("#jform_intId_tg").val("");
    recorrerCombo(jQuery("#jform_intId_tg option"), 0);
    jQuery("#jform_strDescripcionGrafico_crtg").val("");
}
/**
 * 
 * @param {type} regGraficoDel
 * @returns {undefined}
 */
function elmGrafico(regGraficoDel) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGraficoDel) {
            contratos.lstGraficos[j].published = 0;
        }
    }
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actGrafico(data) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == data.regGrafico) {
            contratos.lstGraficos[j].descripcion = data.descripcion;
            contratos.lstGraficos[j].nmbTipoGrafico = data.nmbTipoGrafico;
            contratos.lstGraficos[j].idTipoGrafico = data.idTipoGrafico;
        }
    }
    reloadGraficosTable();
}
/**
 * 
 * @returns {undefined}
 */
function reloadGraficosTable() {
    jQuery("#tbLtsGraficos > tbody").empty();
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].published == 1)
            addGrafico(contratos.lstGraficos[j]);
    }
}

/**
 * Muestra el formulario segun el tipos de grafico.
 * @param {type} tipoGrafico
 * @returns {undefined}
 */
function showFormsGrafico(tipoGrafico) {
    ocultarFormsCoordenadas();
    jQuery('#editGraficoContent').css("display", "block");
    jQuery('#ieavGraficoForm').css("display", "none");
    if (tipoGrafico == 4) {
        jQuery('#coordendasGrafico').css("display", "none");
        jQuery('#circuloGrafico').css("display", "block");
    } else {
        jQuery('#coordendasGrafico').css("display", "block");
        jQuery('#circuloGrafico').css("display", "none");
    }
}


function publicGrafico(regGrafico) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            contratos.lstGraficos[j].published = 1;
        }
    }
}


function cargaFormGrafico(regGrafico) {
    var auxGrafico = null;
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrafico) {
            auxGrafico = contratos.lstGraficos[j];
        }
    }
    //  muestro en el formulario
    recorrerCombo(jQuery("#jform_intId_tg option"), auxGrafico.idTipoGrafico);
    jQuery("#jform_strDescripcionGrafico_crtg").val(auxGrafico.descripcion);

    jQuery("#addCoordenadasFormGrafico").trigger('click');

    jQuery("#jform_intId_tg").attr("disabled", "disabled");
    jQuery("#addCoordenadasFormGrafico").attr("disabled", "disabled");
}

