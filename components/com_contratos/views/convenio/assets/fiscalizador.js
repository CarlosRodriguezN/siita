var idFiscaContrato = 0;
jQuery(document).ready(function() {

    jQuery('#addFiscalizador').click(function() {
        var f = new Date();
        var fecha = f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();
        jQuery("#jform_dteFechaRegistro_cctr").val(fecha);
        var data = new Array();
        data["fchFin"] = jQuery("#jform_dteFiscFechaHasta_gta").val();
        data["fchIncio"] = jQuery("#jform_dteFiscFechaDesde_gta").val();
        data["cedula"] = jQuery("#jform_strFiscaCedula_pc").val();
        data["ruc"] = jQuery("#jform_strFiscaRuc_fs").val();
        data["apellidos"] = jQuery("#jform_strFiscaApellidos_pc").val();
        data["nombres"] = jQuery("#jform_strFiscaNombres_pc").val();
        data["correo"] = jQuery("#jform_strFiscaCorreoElectronico_pc").val();
        data["telefono"] = jQuery("#jform_strFiscaTelefono_pc").val();
        data["celular"] = jQuery("#jform_strFiscaCelular_pc").val();
        data["idFiscalizador"] = jQuery("#jform_intIdPersonasFiscalizador_cgo").val();
        data["fschRegisto"] = fecha;
        if (validarFiscalizador(data)) {
            if (idFiscaContrato == 0) {
                data["idFiscaContrato"] = 'fc-' + contratos.lstFiscalizadores.length;
                data["published"] = 1;
                addFiscalizador(data);
                clCmpFiscalizador();
                contratos.lstFiscalizadores.push(data);
            }
            else {
                data["idFiscaContrato"] = idFiscaContrato;
                actFiscalizador(data);
                idFiscaContrato = 0;
            }
            jQuery('#editFiscalizadorForm').css("display", "none");
            jQuery('#imgFiscalizadorForm').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE");
            return;
        }

    });
    jQuery('.editFiscalizador').live("click", function() {
        jQuery('#imgFiscalizadorForm').css("display", "none");
        jQuery('#editFiscalizadorForm').css("display", "block");
        idFiscaContrato = this.parentNode.parentNode.id;

        //  recupero la data del array
        var data;
        for (var j = 0; j < contratos.lstFiscalizadores.length; j++) {
            if (contratos.lstFiscalizadores[j].idFiscaContrato == idFiscaContrato) {
                data = contratos.lstFiscalizadores[j];
            }
        }

        recorrerCombo(jQuery("#jform_intIdPersonasFiscalizador_cgo option"), data.idFiscalizador);
        jQuery("#jform_intIdPersonasFiscalizador_cgo option").trigger('change', data.idFiscalizador);
        jQuery('#jform_dteFiscFechaDesde_gta').val(data.fchIncio);
        jQuery('#jform_dteFiscFechaHasta_gta').val(data.fchFin);

    });

    jQuery('.delFiscalizador').live("click", function() {
        idFiscaContratoDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmFiscalizador(idFiscaContratoDel);
                reloadFiscalizadorTable();
            } else {

            }
        });
    });

    jQuery("#addFiscalizadorTable").click(function() {
        jQuery('#imgFiscalizadorForm').css("display", "none");
        jQuery('#editFiscalizadorForm').css("display", "block");
        idFiscaContrato = 0;
        clCmpFiscalizador();
    });

    jQuery("#cancelFiscalizador").click(function() {
        jQuery('#editFiscalizadorForm').css("display", "none");
        jQuery('#imgFiscalizadorForm').css("display", "block");
        idFiscaContrato = 0;
        clCmpFiscalizador();
    });
});
/**
 * valida si los campos estan llenos.
 * @param {type} data
 * @returns {Boolean}
 */
function validarFiscalizador(data) {
    if (
            data["fchFin"] != '' &&
            data["fchIncio"] != '' &&
            data["cedula"] != '' &&
            data["ruc"] != '' &&
            data["apellidos"] != '' &&
            data["nombres"] != '' &&
            data["correo"] != '' &&
            data["telefono"] != '' &&
            data["celular"] != '' &&
            data["idFiscalizador"] != 0
            ) {
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
function addFiscalizador(data) {
    var row = '';
    row += '<tr id="' + data.idFiscaContrato + '">';
    row += ' <td>' + data.cedula + ' </td>';
    row += ' <td>' + data.apellidos + ' ' + data.nombres + ' </td>';
    row += ' <td>' + data.fchIncio + ' </td>';
    row += ' <td>' + data.fchFin + ' </td>';
    row += ' <td>' + data.celular + '/ ' + data.telefono + '/ ' + data.correo + ' </td>';
    row += ' <td style="width: 15px"><a class="editFiscalizador" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delFiscalizador" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tblstFiscalizadores > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpFiscalizador() {

    jQuery("#jform_intIdPersonasFiscalizador_cgo").val();

    recorrerCombo(jQuery("#jform_intIdPersonasFiscalizador_cgo option"), 0)

    jQuery("#jform_dteFiscFechaHasta_gta").val("");
    jQuery("#jform_dteFiscFechaDesde_gta").val("");
    jQuery("#jform_strFiscaCedula_pc").val("");
    jQuery("#jform_strFiscaRuc_fs").val("");
    jQuery("#jform_strFiscaApellidos_pc").val("");
    jQuery("#jform_strFiscaNombres_pc").val("");
    jQuery("#jform_strFiscaCorreoElectronico_pc").val("");
    jQuery("#jform_strFiscaTelefono_pc").val("");
    jQuery("#jform_strFiscaCelular_pc").val("");

}
/**
 * 
 * @param {type} idFiscaContrato
 * @returns {undefined}
 */
function elmFiscalizador(idFiscaContratoDel) {
    for (var j = 0; j < contratos.lstFiscalizadores.length; j++) {
        if (contratos.lstFiscalizadores[j].idFiscaContrato == idFiscaContratoDel) {
            contratos.lstFiscalizadores[j].published = 0;
        }
    }
}

function actFiscalizador(data) {
    for (var j = 0; j < contratos.lstFiscalizadores.length; j++) {
        if (contratos.lstFiscalizadores[j].idFiscaContrato == data.idFiscaContrato) {
            contratos.lstFiscalizadores[j].apellidos = data.apellidos;
            contratos.lstFiscalizadores[j].cedula = data.cedula;
            contratos.lstFiscalizadores[j].celular = data.celular;
            contratos.lstFiscalizadores[j].correo = data.correo;
            contratos.lstFiscalizadores[j].fchFin = data.fchFin;
            contratos.lstFiscalizadores[j].fchIncio = data.fchIncio;
            contratos.lstFiscalizadores[j].idFiscalizador = data.idFiscalizador;
            contratos.lstFiscalizadores[j].nombres = data.nombres;
            contratos.lstFiscalizadores[j].published = data.published;
            contratos.lstFiscalizadores[j].ruc = data.ruc;
            contratos.lstFiscalizadores[j].telefono = data.telefono;
        }
    }
    clCmpFiscalizador();
    reloadFiscalizadorTable();
}
function reloadFiscalizadorTable() {
    jQuery("#tblstFiscalizadores > tbody").empty();
    for (var j = 0; j < contratos.lstFiscalizadores.length; j++) {
        if (contratos.lstFiscalizadores[j].published == 1)
            addFiscalizador(contratos.lstFiscalizadores[j]);
    }
}