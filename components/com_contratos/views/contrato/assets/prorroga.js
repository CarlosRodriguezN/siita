function Prorroga(data) {
    this.idProrroga = data.idProrroga;
    this.idCodigoProrroga = data.idCodigoProrroga;
    this.mora = data.mora;
    this.plazo = data.plazo;
    this.documento = data.documento;
    this.observacion = data.observacion;
    this.regProrroga = data.regProrroga;
    this.published = data.published;
}


var regProrroga = -1;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    if ( contratos.lstProrrogas.length > 0 ) {
        reloadProrrogaTable();
    }

    jQuery('#addProrroga').click(function() {

        var data = new Array();
        data["idCodigoProrroga"] = jQuery("#jform_intCodProroga_prrga").val();
        data["mora"] = getMonto( "#jform_dcmMora_prrga" );
        data["plazo"] = jQuery("#jform_intPlazo_prrga").val();
        data["documento"] = jQuery("#jform_strDocumento_prrga").val();
        data["observacion"] = jQuery("#jform_strObservacion_prrga").val();

        if (validarCampProrrogra(data)) {
            if (regProrroga == -1) {
                data["idProrroga"] = 0;
                data["published"] = 1;
                data["regProrroga"] = contratos.lstProrrogas.length;
                addProrroga(data);
                clCmpProrroga();
                var oProrroga = new Prorroga(data);
                contratos.lstProrrogas.push(oProrroga);
            }
            else {
                data.regProrroga = regProrroga;
                actProrroga(data);
            }
        } else {
            jAlert( JSL_ALERT_ALL_NEED, SIITA_ECORAE );
            return;
        }

    });
    
    /**
     * 
     */
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
        jQuery('#jform_dcmMora_prrga' ).attr( 'value', formatNumber( data.mora, '$' ) );
        jQuery("#jform_intPlazo_prrga").val(data.plazo);
        jQuery("#jform_strDocumento_prrga").val(data.documento);
        jQuery("#jform_strObservacion_prrga").val(data.observacion);
        
        resetValidateForm( "#formProrrogaCnt" );
        
    });

    jQuery('.delProrroga').live("click", function() {
        var regProrrogaDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", SIITA_ECORAE, function(r) {
            if (r) {
                if ( regProrrogaDel == regProrroga ) {
                    clCmpProrroga();
                }
                elmProrroga(regProrrogaDel);
                reloadProrrogaTable();
            } 
        });
    });
    
    jQuery("#addProrrogaTable").click(function() {
        if (regProrroga != -1 ){
            clCmpProrroga();
        }
        jQuery('#ieavProrroga').css("display", "none");
        jQuery('#editProrrogaForm').css("display", "block");
    });
    
    jQuery("#cancelarProrroga").click(function() {
        clCmpProrroga();
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
    row += ' <td>' + formatNumber( data.mora, '$' ) + ' </td>';
    row += ' <td>' + data.plazo + ' </td>';
    row += ' <td>' + data.documento + ' </td>';
    row += ' <td>' + data.observacion + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a class="editProrroga" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delProrroga" >Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px">Editar</td>';
        row += ' <td style="width: 15px">Eliminar</td>';
    }
    
    row += '</tr>';
    jQuery('#tbProrrogaContrato > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpProrroga() {
    jQuery('#editProrrogaForm').css("display", "none");
    jQuery('#ieavProrroga').css("display", "block");
    
    regProrroga = -1;
    resetValidateForm( "#formProrrogaCnt" );
    
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

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
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

/**
 * 
 * @returns {undefined}
 */
function reloadProrrogaTable() {
    jQuery("#tbProrrogaContrato > tbody").empty();
    if ( contratos.lstProrrogas.length > 0) {
        for (var j = 0; j < contratos.lstProrrogas.length; j++) {
            if (contratos.lstProrrogas[j].published == 1)
                addProrroga(contratos.lstProrrogas[j]);
        }
    }
}

