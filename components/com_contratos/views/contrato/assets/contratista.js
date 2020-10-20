var idContratistaContrato = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    //agregar contratista
    jQuery('#addContratistaTable').click(function() {
        if ( idContratistaContrato != 0 ) {
            clCmpContratistaContrato();
        }
        jQuery("#jform_dteFechaRegistro_cctr").val(getDateNow());
        jQuery('#imagenEcuAmaVida').css("display", "none");
        jQuery('#editContratista').css("display", "block");
    });

    jQuery('#addContratista').click(function() {
        var data = new Array();
        data["strContratista"] = jQuery("#jform_strContratista_cta option:selected").text();
        data["idContratista"] = jQuery("#jform_strContratista_cta").val();
        data["fechaInicio"] = jQuery("#jform_dteFechaInicio_cctr").val();
        data["fechaFin"] = jQuery("#jform_dteFechaFin_cctr").val();
        data["fechaRegistro"] = jQuery("#jform_dteFechaRegistro_cctr").val();
        data["observacion"] = jQuery("#jform_strObservacion_cctr").val();

        if (validarCampContratistaContrato(data)) {
            if (idContratistaContrato == 0) {
                data["idContratistaContrato"] = 'cc-' + contratos.lstContratistas.length;
                data["published"] = 1;
                data["contactos"] = new Array();
                addContratistaContrato(data);
                contratos.lstContratistas.push(data);
            }
            else {
                data["idContratistaContrato"] = idContratistaContrato;
                actContratistaContrato(data);
                //cambio el texto del boton 
                
            }
            reloadCbContratistaContactos();
            clCmpContratistaContrato();
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
        }
    });

    // editar un contratista
    jQuery('.editContratista').live("click", function() {
        // mustro el formulario
        jQuery('#imagenEcuAmaVida').css("display", "none");
        jQuery('#editContratista').css("display", "block");
        idContratistaContrato = this.parentNode.parentNode.id;
        //  recupero la data del array
        var data;
        for (var j = 0; j < contratos.lstContratistas.length; j++) {
            if (contratos.lstContratistas[j].idContratistaContrato == idContratistaContrato) {
                data = contratos.lstContratistas[j];
            }
        }
        recorrerCombo(jQuery('#jform_strContratista_cta option'), data.idContratista);
        jQuery("#jform_dteFechaInicio_cctr").val(data.fechaInicio);
        jQuery("#jform_dteFechaFin_cctr").val(data.fechaFin);
        jQuery("#jform_dteFechaRegistro_cctr").val(data.fechaRegistro);
        jQuery("#jform_strObservacion_cctr").val(data.observacion);
        
        resetValidateForm( "#formContratistaCnt" );
    });


    jQuery('.delContratista').live("click", function() {
        var idContraCont = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                if ( idContraCont == idContratistaContrato) {
                    clCmpContratistaContrato();
                }
                elmContratistaContrato(idContraCont);
                reloadContratistaContratoTable();
            } else {
            }
        });
    });


    jQuery('#cancelarContratista').click(function() {
        clCmpContratistaContrato();
    });
});

function validarCampContratistaContrato(data) {
    if (data["strContratista"] != "" &&
            data["idContratista"] != 0 &&
            data["fechaInicio"] != "" &&
            data["fechaFin"] != "") {
        return true;
    }
    else {
        return false;
    }
}

function addContratistaContrato(data) {
    var row = '';
    row += '<tr id="' + data.idContratistaContrato + '">';
    row += ' <td>' + data.strContratista + ' </td>';
    row += ' <td>';
    row += (data.observacion) ? data.observacion : JSL_SIN_DESCRIPCION;
    row += ' </td>';
    row += ' <td>' + data.fechaInicio + ' </td>';
    row += ' <td>' + data.fechaFin + ' </td>';
    row += ' <td>' + data.fechaRegistro + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){    
        row += ' <td style="width: 15px"><a class="editContratista" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delContratista" >Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px">Editar</td>';
        row += ' <td style="width: 15px">Eliminar</td>';
    }

    row += '</tr>';
    jQuery('#tbLtsContratistas > tbody:last').append(row);
}

function clCmpContratistaContrato() {
    jQuery('#imagenEcuAmaVida').css("display", "block");
    jQuery('#editContratista').css("display", "none");
    
    idContratistaContrato = 0;
    resetValidateForm( "#formContratistaCnt" );
    
    recorrerCombo(jQuery('#jform_strContratista_cta option'), 0);
    jQuery("#jform_strContratista_cta").val("");
    jQuery("#jform_dteFechaInicio_cctr").val("");
    jQuery("#jform_dteFechaFin_cctr").val("");
    jQuery("#jform_dteFechaRegistro_cctr").val(getDateNow());
    jQuery("#jform_strObservacion_cctr").val("");
}


function actContratistaContrato(data) {
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratistaContrato == data.idContratistaContrato) {
            contratos.lstContratistas[j].fechaFin = data.fechaFin;
            contratos.lstContratistas[j].fechaInicio = data.fechaInicio;
            contratos.lstContratistas[j].fechaRegistro = data.fechaRegistro;
            contratos.lstContratistas[j].observacion = data.observacion;
            contratos.lstContratistas[j].strContratista = data.strContratista;
            contratos.lstContratistas[j].idContratista = data.idContratista;
        }
    }

    clCmpContratistaContrato();
    reloadContratistaContratoTable();
}

function reloadContratistaContratoTable() {
    jQuery("#tbLtsContratistas > tbody").empty();
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].published == 1)
            addContratistaContrato(contratos.lstContratistas[j]);
    }
}

function elmContratistaContrato(idContratistaContrato) {
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].idContratistaContrato == idContratistaContrato) {
            contratos.lstContratistas[j].published = 0;
        }
    }
    reloadCbContratistaContactos();
}

/**
 * carga la lista de contratistas en la pestaña contactos.
 * @returns {undefined}
 */
function reloadCbContratistaContactos() {
    var options = [];
    jQuery('#jform_strContratistaContacto_cta').html(options.join(''));
    options.push('<option value="0">SELECCIONE UN CONTRATISTA</option>');
    for (var j = 0; j < contratos.lstContratistas.length; j++) {
        if (contratos.lstContratistas[j].published == 1) {
            options.push('<option value="' + contratos.lstContratistas[j].idContratista + '">' + contratos.lstContratistas[j].strContratista + '</option>');
        }
    }
    jQuery('#jform_strContratistaContacto_cta').html(options.join(''));
}