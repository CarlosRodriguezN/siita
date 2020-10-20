function Prorroga(data) {
    this.idProrroga = data.idProrroga;
    this.idCodigoProrroga = data.idCodigoProrroga;
    this.mora = data.mora;
    this.plazo = data.plazo;
    this.documento = data.documento;
    this.observacion = data.observacion;
    this.observacion = data.observacion;
    this.published = data.published;
}


var regProrroga = 0;
jQuery(document).ready(function() {

    jQuery('#addProrroga').click(function() {

        var data = new Array();
        data["idCodigoProrroga"] = jQuery("#jform_intCodProroga_prrga").val();
        data["mora"] = jQuery("#jform_dcmMora_prrga").val();
        data["plazo"] = jQuery("#jform_intPlazo_prrga").val();
        data["documento"] = jQuery("#jform_strDocumento_prrga").val();
        data["observacion"] = jQuery("#jform_strObservacion_prrga").val();

        if (validarCampProrrogra(data)) {
            if (regProrroga == 0) {
                data["idProrroga"] = 0;
                data["published"] = 1;
                addProrroga(data);
                clCmpProrroga();
                var oProrroga = new Prorroga(data);
                oProrroga.regProrroga = contratos.lstProrrogas.length + 1;
                contratos.lstProrrogas.push(oProrroga);
            }
            else {
                data.regProrroga = regProrroga;
                actProrroga(data);
                regProrroga = 0;
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
        regProrroga = parseInt(this.parentNode.parentNode.id);
        //  recupero la data del array
        var data;
        for (var j = 0; j < contratos.lstProrrogas.length; j++) {
            if (contratos.lstProrrogas[j].regProrroga == regProrroga) {
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
        regProrroga = 0;
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

