function Pago(data) {
    if (data == null) {
        data = {
            "idPago     ": "0",
            "idTipoPago ": "0",
            "codPago    ": "",
            "cur        ": "",
            "monto      ": "",
            "documento  ": ""
        };
    }
    this.idPago = (data.idPago) ? data.idPago : "0";
    this.idTipoPago = (data.idTipoPago) ? data.idTipoPago : "0";
    this.codPago = (data.codPago) ? data.codPago : "0";
    this.cur = (data.cur) ? data.cur : "";
    this.monto = (data.monto) ? data.monto : "";
    this.documento = (data.documento) ? data.documento : "";
}

var regPago = 0;
jQuery(document).ready(function() {
    jQuery('#addPago').click(function() {
        var data = new Array();
        data["codPago"] = jQuery("#jform_intCodPago_pgo").val();
        data["cur"] = jQuery("#jform_strCUR_pgo").val();
        data["idTipoPago"] = jQuery("#jform_strCUR_pgo").val();
        data["monto"] = jQuery("#jform_dcmMonto_pgo").val();
        data["nombreTipoPago"] = jQuery("#jform_strCUR_pgo").val();
        if (validarCampProrrogra(data)) {
            if (regPago == 0) {
                data["idProrroga"] = 0;
                data["published"] = 1;
                addProrroga(data);
                clCmpProrroga();
                var oProrroga = new Prorroga(data);
                oProrroga.regProrroga = contratos.lstProrrogas.length + 1;
                contratos.lstProrrogas.push(oProrroga);
            }
            else {
                data.regProrroga = regPago;
                actProrroga(data);
                regPago = 0;
            }
            jQuery('#editProrrogaForm').css("display", "none");
            jQuery('#ieavProrroga').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }

    });
    jQuery('.editProrroga').live("click", function() {
        jQuery('#ieavProrroga').css("display", "none");
        jQuery('#editProrrogaForm').css("display", "block");
        regPago = parseInt(this.parentNode.parentNode.id);
        //  recupero la data del array
        var data;
        for (var j = 0; j < contratos.lstProrrogas.length; j++) {
            if (contratos.lstProrrogas[j].regProrroga == regPago) {
                data = contratos.lstProrrogas[j];
            }
        }
        jQuery("#jform_intCodProroga_prrga").val(data.idCodigoProrroga);
        jQuery("#jform_dcmMora_prrga").val(data.mora);
        jQuery("#jform_intPlazo_prrga").val(data.plazo);
        jQuery("#jform_strDocumento_prrga").val(data.documento);
        jQuery("#jform_strObservacion_prrga").val(data.observacion);
    });
    jQuery('.delProrroga').live("click", function() {
        var regProrrogaDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmProrroga(regProrrogaDel);
                reloadProrrogaTable();
            } else {

            }
        });
    });
    jQuery("#addProrrogaTable").click(function() {
        clCmpProrroga();
        regPago = 0;
        jQuery('#ieavProrroga').css("display", "none");
        jQuery('#editProrrogaForm').css("display", "block");
    });
    jQuery("#cancelarProrroga").click(function() {
        clCmpProrroga();
        jQuery('#editProrrogaForm').css("display", "none");
        jQuery('#ieavProrroga').css("display", "block");
    });
});
function validarCampProrrogra(data) {
    if (data["idCodigoProrroga"] != "" &&
            data["mora"] != "" &&
            data["plazo"] != "" &&
            data["documento"] != "" &&
            data["observacion"] != ""
            ) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * @description Agrega una fila a la tabla de prorrogas
 * @param {array} data
 * @returns {undefined}
 */
function addProrroga(data) {
    var row = '';
    row += '<tr id="' + data.regProrroga + '">';
    row += ' <td>' + data.idCodigoProrroga + ' </td>';
    row += ' <td>' + data.mora + ' </td>';
    row += ' <td>' + data.plazo + ' </td>';
    row += ' <td>' + data.documento + ' </td>';
    row += ' <td>' + data.observacion + ' </td>';
    row += ' <td style="width: 15px"><a class="editProrroga" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delProrroga" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbProrrogaContrato > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpProrroga() {
    jQuery("#jform_intCodProroga_prrga").val("");
    jQuery("#jform_dcmMora_prrga").val("");
    jQuery("#jform_intPlazo_prrga").val("");
    jQuery("#jform_strDocumento_prrga").val("");
    jQuery("#jform_strObservacion_prrga").val("");
}
/**
 * 
 * @param {type} idRegProrroga
 * @returns {undefined}
 */
function elmProrroga(idRegProrroga) {
    for (var j = 0; j < contratos.lstProrrogas.length; j++) {
        if (contratos.lstProrrogas[j].regProrroga == idRegProrroga) {
            contratos.lstProrrogas[j].published = 0;
        }
    }
}

function actProrroga(data) {
    for (var j = 0; j < contratos.lstProrrogas.length; j++) {
        if (contratos.lstProrrogas[j].regProrroga == data.regProrroga) {
            contratos.lstProrrogas[j].documento = data.documento;
            contratos.lstProrrogas[j].idCodigoProrroga = data.idCodigoProrroga;
            contratos.lstProrrogas[j].mora = data.mora;
            contratos.lstProrrogas[j].observacion = data.observacion;
            contratos.lstProrrogas[j].plazo = data.plazo;
            contratos.lstProrrogas[j].published = 1;
        }
    }
    clCmpProrroga();
    reloadProrrogaTable();
}

function reloadProrrogaTable() {
    jQuery("#tbProrrogaContrato > tbody").empty();
    for (var j = 0; j < contratos.lstProrrogas.length; j++) {
        if (contratos.lstProrrogas[j].published == 1)
            addProrroga(contratos.lstProrrogas[j]);
    }
}

