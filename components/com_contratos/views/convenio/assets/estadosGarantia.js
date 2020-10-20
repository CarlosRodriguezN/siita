var idGarantiaEstado = 0;

jQuery(document).ready(function() {

    jQuery(".trGarantia").live('click', function() {
        var idTrGarantiEstado = this.id;
        reloadEstadoGarantia(idTrGarantiEstado);
        recorrerCombo(jQuery("#jform_intIdGarantia_sele_eg option"), idTrGarantiEstado);
    });

    jQuery('#addGarantiaEstado').click(function() {
        if (jQuery("#jform_intIdGarantia_sele_eg").val() != 0) {
            var data = new Array();
            var d = new Date();
            data["idEstadoGarantia"] = jQuery('#jform_intIdEstadoGarantia_eg').val();
            data["nmbEstadoGarantia"] = jQuery("#jform_intIdEstadoGarantia_eg option:selected").text();
            data["observacion"] = jQuery('#jform_strObservasion_ge').val();
            data["fchRegistro"] = d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate();

            if (validarEstado(data)) {
                if (idGarantiaEstado == 0) {
                    setDataGarantiaEstado(data, jQuery('#jform_intIdGarantia_sele_eg option:selected').text());
                    addEstadoGarantia(data);
                    clCmpGarantiaEstado();
                }
                else {
                    data["idGarantiaEstado"] = idGarantiaEstado;
                    updDataGarantiaEstado(data, jQuery('#jform_intIdGarantia_sele_eg option:selected').text());
                    reloadEstadoGarantia(jQuery('#jform_intIdGarantia_sele_eg option:selected').val());
                }
                jQuery('#editEstGarantiaForm').css("display", "none");
                jQuery('#imgEstGarantiaForm').css("display", "block");
            } else {
                jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA-ECORAE");
            }
        } else {
            jAlert("Seleccione una Garantía", "SIITA-ECORAE");
        }
    });

    jQuery(".editEstadoGarantia").live("click", function() {
        var data = null;
        jQuery('#imgEstGarantiaForm').css("display", "none");
        jQuery('#editEstGarantiaForm').css("display", "block");
        idGarantiaEstado = this.parentNode.parentNode.id;
        //idGarantia=getIdGarantiaByCodGarantia(jform_intIdGarantia_sele_eg);
        for (var j = 0; j < contratos.lstGarantias.length; j++) {
            for (var k = 0; k < contratos.lstGarantias[j].estados.length; k++) {
                if (contratos.lstGarantias[j].estados[k].idGarantiaEstado == idGarantiaEstado) {
                    data = contratos.lstGarantias[j].estados[k];
                }
            }
        }
        if (data) {
            recorrerCombo(jQuery("#jform_intIdEstadoGarantia_eg option"), data.idEstadoGarantia);
            jQuery("#jform_strObservasion_ge").val(data.observacion);
        }
    });

    jQuery(".delEstadoGarantia").live("click", function() {
        var idGarantiaEstado = this.parentNode.parentNode.id;
        var codGarantia = jQuery("#jform_intIdGarantia_sele_eg option:selected").text();
        var idGarantia = getIdGarantiaByCodGarantia(codGarantia);
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                delGarantiaEstado(idGarantiaEstado);
                reloadEstadoGarantia(idGarantia);
            } else {

            }
        });
    });
    jQuery("#jform_intIdGarantia_sele_eg").live("change",function() {
        var idGarantiaSelected = jQuery("#jform_intIdGarantia_sele_eg").val();
        reloadEstadoGarantia(idGarantiaSelected);
    });

    jQuery("#addGarantiaEstadoTable").click(function() {
        jQuery('#imgEstGarantiaForm').css("display", "none");
        jQuery('#editEstGarantiaForm').css("display", "block");
    });
    jQuery("#cancelGarantiaEstado").click(function() {
        clCmpGarantiaEstado();
        idGarantiaEstado = 0;
        jQuery('#editEstGarantiaForm').css("display", "none");
        jQuery('#imgEstGarantiaForm').css("display", "block");
    });

});
/**
 * valida si los campos de el estado de un formulario estan completos
 * @param {type} data
 * @returns {Boolean}
 */
function validarEstado(data) {
    if (data["idEstadoGarantia"] != 0 &&
            data["observacion"] != "") {
        return true;
    } else {
        return false;
    }
}

function updDataGarantiaEstado(data, codGarantia) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].codGarantia == codGarantia) {
            for (var k = 0; k < contratos.lstGarantias[j].estados.length; k++) {
                if(contratos.lstGarantias[j].estados[k].idGarantiaEstado==data.idGarantiaEstado){
                contratos.lstGarantias[j].estados[k].fchRegistro = data.fchRegistro;
                contratos.lstGarantias[j].estados[k].idEstadoGarantia = data.idEstadoGarantia;
                contratos.lstGarantias[j].estados[k].nmbEstadoGarantia = data.nmbEstadoGarantia;
                contratos.lstGarantias[j].estados[k].observacion = data.observacion;
                contratos.lstGarantias[j].estados[k].published = 1;
            }
            }
        }
    }
}

/**
 * Agrega un estado a una garantia.
 * @param {type} data
 * @param {type} codGarantia
 * @returns {undefined}
 */
function setDataGarantiaEstado(data, codGarantia) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].codGarantia == codGarantia) {
            data["idGarantiaEstado"] = "n-" + contratos.lstGarantias[j].estados.length + 1;
            data["published"] = 1;
            data["estadoAct"] = 1;
            changeEstadoGarantias();
            contratos.lstGarantias[j].estados.push(data);
        }
    }
}
/**
 * recupera el ultimo estado de la garantía
 * @param {type} idGarantia
 * @returns {String}
 */
function getLastEstadoGarantia(idGarantia) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].idGarantia == idGarantia) {
            for (var k = 0; k < contratos.lstGarantias[j].estados.length; k++) {
                if (contratos.lstGarantias[j].estados[k].estadoAct == 1) {
                    return contratos.lstGarantias[j].estados[k];
                }
            }
        }
    }
}

/**
 * genera el html para estado de una garantia
 *  @description Agrega una estado de garantia
 * @param {type} data
 * @returns {undefined}
 */
function addEstadoGarantia(data) {
    var row = '';
    row += '<tr id="' + data.idGarantiaEstado + '">';
    row += ' <td>' + data.nmbEstadoGarantia + ' </td>';
    row += ' <td>' + data.observacion + ' </td>';
    row += ' <td>' + data.fchRegistro + ' </td>';
    row += ' <td style="width: 15px"><a class="editEstadoGarantia">Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delEstadoGarantia">Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLstGarantiasEstados > tbody:first').append(row);
}
/**
 * recupera los estado de una garantia
 * @param {type} idGarantia
 * @returns {unresolved}
 */
function getEstadosGarantia(idGarantia) {
    var garantia = null;
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].idGarantia == idGarantia) {
            garantia = contratos.lstGarantias[j];
        }
    }
    return garantia;
}
/**
 * redibuja toda la tabla de estados de una garantía
 * @param {type} idGarantia
 * @returns {undefined}
 */
function reloadEstadoGarantia(idGarantia) {
    var garantia = getEstadosGarantia(idGarantia);
    jQuery("#tbLstGarantiasEstados > tbody").empty();
    if (garantia.estados.length != 0) {
        for (var j = 0; j < garantia.estados.length; j++) {
            if (garantia.estados[j].published == 1)
                addEstadoGarantia(garantia.estados[j]);
        }
    } else {
        var row = '';
        row += '<tr><td colspan="5">Sin estados disponibles</td></tr>';
        jQuery('#tbLstGarantiasEstados > tbody:first').append(row);
    }
}
/**
 * limpia los campos de los estados de una garantía
 * @returns {undefined}
 */
function clCmpGarantiaEstado() {
    jQuery("#jform_strObservasion_ge").val("");
    recorrerCombo(jQuery('#jform_intIdEstadoGarantia_eg option'), 0);
}
/**
 * elimina logicamente el estado de una garantía
 * @param {type} idGarantiaEstado
 * @returns {undefined}
 */
function delGarantiaEstado(idGarantiaEstado) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        for (var k = 0; k < contratos.lstGarantias[j].estados.length; k++) {
            if (contratos.lstGarantias[j].estados[k].idGarantiaEstado == idGarantiaEstado)
                contratos.lstGarantias[j].estados[k].published = 0;
        }
    }
}
/**
 * recupera el id de una garantia dado el  codigo de la garantia
 * @param {type} codGarantia
 * @returns {Number}
 */
function getIdGarantiaByCodGarantia(codGarantia) {
    var idGarantia = 0;
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].codGarantia == codGarantia)
            idGarantia = contratos.lstGarantias[j].idGarantia;
    }
    return idGarantia;
}
/**
 * recupera el estado actual de una garantia.
 * @param {type} idGarantia
 * @returns {Number}
 */
function getEstadoActualGarantia(idGarantia) {
    var idEstadoGarantia = 0;
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].idGarantia == idGarantia)
            idGarantia = contratos.lstGarantias[j].estados[0];
    }
    return idEstadoGarantia;
}
