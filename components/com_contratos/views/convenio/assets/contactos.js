/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var idContacto = 0
jQuery(document).ready(function() {

    // cargar la data de contratista

    reloadCbContratistaContactos();

    //cancelar  

    jQuery('#cancelContratistaContacto').click(function() {
        clCmpContactoContratista();
        jQuery('#editContactoContratistaForm').addClass("hide");
        jQuery('#imgContactoContratistaForm').removeClass("hide");

    });
    jQuery('#addTableContratistaContacto').click(function() {
        jQuery('#editContactoContratistaForm').removeClass("hide");
        jQuery('#imgContactoContratistaForm').addClass("hide");
    });

    //listar contactos
    jQuery('#jform_strContratistaContacto_cta').change(function() {
        reloadContactosContratistaTable(jQuery('#jform_strContratistaContacto_cta').val());
    });
//agregar contacto
    jQuery('#addContratistaContacto').click(function() {
        // mostrat el formulario
        var data = new Array();
        data["idCargo"] = jQuery("#jform_intIdCargo_cgo").val();
        data["cgoCargo"] = jQuery("#jform_intIdCargo_cgo option:selected").text();
        data["idPersona"] = jQuery("#jform_strContratistaContacto_cta").val();
        data["idPersona"] = jQuery("#jform_intIdPersonasCargo_cgo").val();
        data["perApellido"] = jQuery("#jform_strApellidos_pc").val();
        data["perCedula"] = jQuery("#jform_strCedula_pc").val();
        data["perCelular"] = jQuery("#jform_strCelular_pc").val();
        data["perCorreo"] = jQuery("#jform_strCorreoElectronico_pc").val();
        data["perNombre"] = jQuery("#jform_strNombres_pc").val();
        data["perTelefono"] = jQuery("#jform_strTelefono_pc").val();

        if (validarCampContratistaContrato(data)) {
            if (idContacto == 0) {
                data["idContacto"] = 'nc' + getContactosContratista(jQuery("#jform_strContratistaContacto_cta").val()).length;
                data["published"] = 1;
                addContactoContratista(data);
                addContactoContratistaData(jQuery("#jform_strContratistaContacto_cta").val(), data);
                clCmpContactoContratista();
            }
            else {
                data["idContacto"] = idContacto;
                actContartistaContrato(jQuery("#jform_strContratistaContacto_cta").val(), data);
                //cambio el texto del boton 
                idContacto = 0;
            }
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }
        jQuery('#editContactoContratistaForm').addClass("hide");
        jQuery('#imgContactoContratistaForm').removeClass("hide");
    });

    jQuery('.editContactoContratista').live("click", function() {
        var idContratistaContacto = jQuery("#jform_strContratistaContacto_cta").val();
        idContacto = this.parentNode.parentNode.id;
        var contactos = getContactosContratista(idContratistaContacto);
        var data = [];
        for (var j = 0; j < contactos.length; j++) {
            if (contactos[j].idContacto == idContacto)
                data = contactos[j];
        }
        recorrerCombo(jQuery("#jform_intIdCargo_cgo option"), data.idCargo);
        recorrerCombo(jQuery("#jform_intIdPersonasCargo_cgo option"), data.idPersona);
        jQuery("#jform_intIdPersonasCargo_cgo").trigger('change', data.idPersona);

        jQuery('#editContactoContratistaForm').removeClass("hide");
        jQuery('#imgContactoContratistaForm').addClass("hide");

    });

    jQuery('.delContactoContratista').live("click", function() {
        var delIdContacto = this.parentNode.parentNode.id;
        var idContratistaContacto = jQuery("#jform_strContratistaContacto_cta").val()
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmContactoContratista(idContratistaContacto, delIdContacto);
                //reloadContactosContratistaTable();
            } else {

            }
        });
        idContacto = 0;
    });
});


function validarCampContratistaContrato(data) {
    if (data["idCargo"] != "" &&
            data["cgoCargo"] != "" &&
            data["idPersona"] != 0 &&
            data["idPersona"] != 0 &&
            data["perApellido"] != "" &&
            data["perCedula"] != "" &&
            data["perCelular"] != "" &&
            data["perCorreo"] != "" &&
            data["perNombre"] != "" &&
            data["perTelefono"] != ""
            ) {
        return true;
    }
    else {
        return false;
    }
}
/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addContactoContratista(data) {
    var row = '';
    row += '<tr id="' + data.idContacto + '">';
    row += ' <td>' + data.cgoCargo + ' </td>';
    row += ' <td>' + data.perCedula + ' </td>';
    row += ' <td>' + data.perApellido + ' ' + data.perNombre + ' </td>';
    row += ' <td>' + data.perTelefono + ' / ' + data.perCelular + ' </td>';
    row += ' <td>' + data.perCorreo + ' </td>';
    row += ' <td style="width: 15px"><a class="editContactoContratista" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delContactoContratista" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLtsContacto > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpContactoContratista() {
    recorrerCombo(jQuery("#jform_intIdCargo_cgo option"), 0);
    recorrerCombo(jQuery("#jform_intIdPersonasCargo_cgo option"), 0);
    jQuery("#jform_intIdPersonasCargo_cgo").trigger('change', 0);
    //ocultar
}

/**
 * 
 * Elimina un contacto de un contratista.
 * 
 * @param {type} idContratistaContrato  Identificador del contratista.
 * @param {type} delIdContacto          registro del contacto a eliminar.
 * @returns {undefined}
 */
function elmContactoContratista(idContratistaContrato, delIdContacto) {
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratista == idContratistaContrato) {
            for (var k = 0; k < contratos.lstContratistas[j].contactos.length; k++)
                if (contratos.lstContratistas[j].contactos[k].idContacto == delIdContacto) {
                    contratos.lstContratistas[j].contactos[k].published = 0;
                }
        }
    }
    reloadContactosContratistaTable(idContratistaContrato);
}

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actContartistaContrato(idContratistaContrato, data) {
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratista == idContratistaContrato) {
            for (var k = 0; k < contratos.lstContratistas[j].contactos.length; k++)
                if (contratos.lstContratistas[j].contactos[k].idContacto == data.idContacto) {
                    contratos.lstContratistas[j].contactos[k].cgoCargo = data.cgoCargo;
                    contratos.lstContratistas[j].contactos[k].idCargo = data.idCargo;
                    contratos.lstContratistas[j].contactos[k].idPersona = data.idPersona;
                    contratos.lstContratistas[j].contactos[k].perApellido = data.perApellido;
                    contratos.lstContratistas[j].contactos[k].perCedula = data.perCedula;
                    contratos.lstContratistas[j].contactos[k].perCelular = data.perCelular;
                    contratos.lstContratistas[j].contactos[k].perCorreo = data.perCorreo;
                    contratos.lstContratistas[j].contactos[k].perNombre = data.perNombre;
                    contratos.lstContratistas[j].contactos[k].perTelefono = data.perTelefono;
                }
        }
    }
    reloadContactosContratistaTable(idContratistaContrato);
    clCmpContactoContratista();

}


/**
 * 
 * @param {type} idContratista
 * @returns {undefined}
 */
function reloadContactosContratistaTable(idContratista) {
    jQuery("#tbLtsContacto > tbody").empty();
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratista == idContratista) {
            for (var k = 0; k < contratos.lstContratistas[j].contactos.length; k++)
                if (contratos.lstContratistas[j].contactos[k].published == 1) {
                    addContactoContratista(contratos.lstContratistas[j].contactos[k]);
                }
        }
    }
}

/**
 * 
 * @param {type} idContratista
 * @returns {Array}
 */
function getContactosContratista(idContratista) {
    var contactos = [];
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratista == idContratista) {
            contactos = contratos.lstContratistas[j].contactos;
        }
    }
    return contactos;
}

function addContactoContratistaData(idContratista, data) {
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratista == idContratista) {
            contratos.lstContratistas[j].contactos.push(data);
        }
    }

}