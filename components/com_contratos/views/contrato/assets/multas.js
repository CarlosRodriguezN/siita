var idMulta = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {
    
    /**
     * 
     */
    if ( contratos.lstMultas.length > 0 ) {
        reloadMultasTable();
    }
    
    /**
     * 
     */
    jQuery('#addMulta').click(function() {
        var data = new Array();

        data["codMulta"] = jQuery("#jform_intCodMulta_mta").val();
        data["monto"] = getMonto( "#jform_dcmMonto_mta" );
        data["observacion"] = jQuery("#jform_strObservacioin_mta").val();

        if (data["codMulta"] != '' && data["monto "] != "" && data["observacion"] != "") {
            if (idMulta == 0) {
                data["idMulta"] = 'mu-' + contratos.lstMultas.length;
                data["published"] = 1;
                addMulta(data);
                clCmpMulta();
                contratos.lstMultas.push(data);
            }
            else {
                data["idMulta"] = idMulta;
                actMulta(data);
            }
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }

    });

    jQuery('.editMulta').live("click", function() {
        jQuery('#imgMultaForm').css("display", "none");
        jQuery('#editMultaForm').css("display", "block");
        idMulta = this.parentNode.parentNode.id;
        //  recupero la data del array
        var data;
        for (var j = 0; j < contratos.lstMultas.length; j++) {
            if (contratos.lstMultas[j].idMulta == idMulta) {
                data = contratos.lstMultas[j];
            }
        }
        //  muestro en el formulario
        jQuery("#jform_intCodMulta_mta").val(data.codMulta);
        jQuery("#jform_dcmMonto_mta").attr( 'value', formatNumber(data.monto, '$' ) );  
        jQuery("#jform_strObservacioin_mta").val(data.observacion);

        resetValidateForm( "#formMultasCnt" );

    });

    jQuery('.delMulta').live("click", function() {
        var idMlt = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                if ( idMulta == idMlt ) {
                    clCmpMulta();
                }
                elmMulta(idMlt);
                reloadMultasTable();
            } 
        });
    });

    jQuery("#addMultaTable").click(function() {
        if ( idMulta != 0 ) {
            clCmpMulta();
        }
        jQuery('#imgMultaForm').css("display", "none");
        jQuery('#editMultaForm').css("display", "block");
    });
    
    jQuery("#cancelMulta").click(function() {
        clCmpMulta();
    });

});

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addMulta(data) {
    var row = '';
    row += '<tr id="' + data.idMulta + '">';
    row += ' <td>' + data.codMulta + ' </td>';
    row += ' <td>' + data.observacion + ' </td>';
    row += ' <td>' + formatNumber( data.monto, '$' ) + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a class="editMulta" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delMulta" >Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px">Editar</td>';
        row += ' <td style="width: 15px">Eliminar</td>';
    }

    row += '</tr>';
    jQuery('#tblstMultas > tbody:last').append(row);
}

/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpMulta() {
    jQuery('#editMultaForm').css("display", "none");
    jQuery('#imgMultaForm').css("display", "block");
    
    idMulta = 0;
    resetValidateForm( "#formMultasCnt" );
    
    jQuery("#jform_intCodMulta_mta").val("");
    jQuery("#jform_dcmMonto_mta").val("");
    jQuery("#jform_strObservacioin_mta").val("");
}

/**
 * 
 * @param {type} idMulta
 * @returns {undefined}
 */
function elmMulta(idMulta) {
    for (var j = 0; j < contratos.lstMultas.length; j++) {
        if (contratos.lstMultas[j].idMulta == idMulta) {
            contratos.lstMultas[j].published = 0;
        }
    }
}
function actMulta(data) {
    for (var j = 0; j < contratos.lstMultas.length; j++) {
        if (contratos.lstMultas[j].idMulta == data.idMulta) {
            contratos.lstMultas[j].codMulta = data.codMulta;
            contratos.lstMultas[j].monto = data.monto;
            contratos.lstMultas[j].observacion = data.observacion;
        }
    }
    clCmpMulta();
    reloadMultasTable();
}
function reloadMultasTable() {
    jQuery("#tblstMultas > tbody").empty();
    for (var j = 0; j < contratos.lstMultas.length; j++) {
        if (contratos.lstMultas[j].published == 1)
            addMulta(contratos.lstMultas[j]);
    }
}