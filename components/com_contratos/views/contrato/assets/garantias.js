/*
 * maneja las garanatias
 */
var idGarantia = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    if ( contratos.lstGarantias.length > 0 ) {
        reloadGarantiaTable();
    }

    jQuery('#addGarantia').click(function() {
        var data = new Array();
        data["idTipoGarantia"]  = jQuery("#jform_intIdTpoGarantia_tg").val();
        data["idFormaGarantia"] = jQuery("#jform_intIdFrmGarantia_fg").val();
        data["codGarantia"]     = jQuery("#jform_intCodGarantia_gta").val();
        data["monto"]           = getMonto( "#jform_dcmMonto_gta" );
        data["fchDesde"]        = jQuery("#jform_dteFechaDesde_gta").val();
        data["fchHasta"]        = jQuery("#jform_dteFechaHasta_gta").val();
        if (validarCampGarantia(data)) {
            if (idGarantia == 0) {
                var d = new Date();
                data["idGarantia"] = "ng-" + contratos.lstGarantias.length;
                data["published"] = 1;
                // estados por defecto
                data["estados"] = new Array();
                contratos.lstGarantias.push(data);

                // estado por default de la garantia
                var nEstado = new Object();
                nEstado.fchRegistro = d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate();
                nEstado.idEstadoGarantia = "1";
                nEstado.estadoAct = "1";
                nEstado.nmbEstadoGarantia = jQuery("#jform_intIdEstadoGarantia_eg option[value=1]").text();
                nEstado.observacion = "Vigente";
                nEstado.published = "1";

                setDataGarantiaEstado(nEstado, data["codGarantia"]);

                addGarantia(data);
                clCmpGarantia();
            } else {
                data["idGarantia"] = idGarantia;
                actGarantia(data);
                idGarantia = 0;
            }
            reloadEstadoGarantiaCB();
            jQuery('#editGarantiaForm').css("display", "none");
            jQuery('#imgGarantiaForm').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE");
            return;
        }
    });
//edicion de las garantias
    jQuery('.editGarantia').live("click", function() {
        jQuery('#imgGarantiaForm').css("display", "none");
        jQuery('#editGarantiaForm').css("display", "block");
        idGarantia = this.parentNode.parentNode.id;
        //  recupero la data del array
        var data;
        for (var j = 0; j < contratos.lstGarantias.length; j++) {
            if (contratos.lstGarantias[j].idGarantia == idGarantia) {
                data = contratos.lstGarantias[j];
            }
        }
//  muestro en el formulario

        recorrerCombo(jQuery('#jform_intIdTpoGarantia_tg option'), data.idTipoGarantia);
        recorrerCombo(jQuery('#jform_intIdFrmGarantia_fg option'), data.idFormaGarantia);
        jQuery("#jform_intCodGarantia_gta").val(data.codGarantia);
        jQuery("#jform_dcmMonto_gta").attr( 'value', formatNumber(data.monto, '$' ) );  
        jQuery("#jform_dteFechaDesde_gta").val(data.fchDesde);
        jQuery("#jform_dteFechaHasta_gta").val(data.fchHasta);
        jQuery("#jform_attrCodigo").val(data.codAtributo);
        jQuery("#jform_attrNombre").val(data.nombre);
        jQuery("#jform_attrValor").val(data.valor);
        jQuery("#jform_strObservasion_ge").val(data.obsGarantiaEstado);
        recorrerCombo(jQuery('#jform_intIdEstadoGarantia_eg option'), data.idEstadoGarantia);
        
        //  Limpio la clses de validacion 
        resetValidateForm( "#formGarantiaCnt" );
    });
//eliminar garantia
    jQuery('.delGarantia').live("click", function() {
        var regGarantia = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                if ( availableDelGrt(regGarantia) ) {
                    elmGarantia(regGarantia);
                    reloadGarantiaTable();
                    reloadEstadoGarantiaCB();
                    if( regGarantia == idGarantia ){
                        clCmpGarantia();
                    }
                } else {
                    jAlert( JSL_ALERT_NO_ELIMINAR_REG, SIITA_ECORAE);
                }
            } 
        });
    });

    jQuery("#addGarantiasTable").click(function() {
        if ( idGarantia != 0 ) {
            clCmpGarantia();
        }
        jQuery('#editGarantiaForm').css("display", "block");
        jQuery('#imgGarantiaForm').css("display", "none");
    });

    jQuery("#cancelGarantia").click(function() {
        clCmpGarantia();
    });
});
/*
 * valida que todos los campos esten llenos.
 */
function validarCampGarantia(data) {
    if (
            data["idGarantia"] != '' &&
            data["idTipoGarantia"] != 0 &&
            data["idFormaGarantia"] != 0 &&
            data["codGarantia"] != '' &&
            data["monto"] != '' &&
            data["fchDesde"] != '' &&
            data["fchHasta"] != '' &&
            data["idEstadoGarantia"] != '' &&
            data)
    {
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
function addGarantia(data) {
    var estadoGarantia = getLastEstadoGarantia(data.idGarantia);
    var est='';
    if(estadoGarantia){
        est=estadoGarantia.nmbEstadoGarantia;
    }
    var row = '';
    row += '<tr class="trGarantia" id="' + data.idGarantia + '">';
    row += ' <td>' + data.codGarantia + ' </td>';
    row += ' <td>' + formatNumber( data.monto, '$' ) + ' </td>';
    row += ' <td>' + data.fchDesde + ' </td>';
    row += ' <td>' + data.fchHasta + ' </td>';
    row += ' <td>' + est + ' </td>';

    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width:15px"><a class="editGarantia">Editar</a></td>';
        row += ' <td style="width:15px"><a class="delGarantia">Eliminar</a></td>';
    }else{
        row += ' <td style="width:15px">Editar</td>';
        row += ' <td style="width:15px">Eliminar</td>';
    }
    
    row += '</tr>';
    jQuery('#tbLstGarantias > tbody:last').append(row);
}



/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpGarantia() {
    jQuery('#editGarantiaForm').css("display", "none");
    jQuery('#imgGarantiaForm').css("display", "block");

    idGarantia = 0;
    resetValidateForm( "#formGarantiaCnt" );

    recorrerCombo(jQuery('#jform_intIdTpoGarantia_tg option'), 0);
    recorrerCombo(jQuery('#jform_intIdFrmGarantia_fg option'), 0);
    jQuery("#jform_intCodGarantia_gta").val("");
    jQuery("#jform_dcmMonto_gta").val("");
    jQuery("#jform_dteFechaDesde_gta").val("");
    jQuery("#jform_dteFechaHasta_gta").val("");
    jQuery("#jform_strObservasion_ge").val("");
    recorrerCombo(jQuery('#jform_intIdEstadoGarantia_eg option'), 0);
}
/**
 * 
 * @param {type} idGarantia
 * @returns {undefined}
 */
function elmGarantia(idGarantia) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].idGarantia == idGarantia) {
            contratos.lstGarantias[j].published = 0;
        }
    }
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actGarantia(data) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].idGarantia == data.idGarantia) {
            contratos.lstGarantias[j].idTipoGarantia = data.idTipoGarantia;
            contratos.lstGarantias[j].idFormaGarantia = data.idFormaGarantia;
            contratos.lstGarantias[j].codGarantia = data.codGarantia;
            contratos.lstGarantias[j].monto = data.monto;
            contratos.lstGarantias[j].fchDesde = data.fchDesde;
            contratos.lstGarantias[j].fchHasta = data.fchHasta;
            contratos.lstGarantias[j].idEstadoGarantia = data.idEstadoGarantia;
            contratos.lstGarantias[j].nmbEstadoGarantia = data.nmbEstadoGarantia;
            contratos.lstGarantias[j].obsGarantiaEstado = data.obsGarantiaEstado;
        }
    }
    clCmpGarantia();
    reloadGarantiaTable();
}

/**
 * 
 * @returns {undefined}
 */
function reloadGarantiaTable() {
    jQuery("#tbLstGarantias > tbody").empty();
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].published == 1)
            addGarantia(contratos.lstGarantias[j]);
    }
}
/**
 * 
 * @param {type} combo
 * @param {type} posicion
 * @returns {undefined}
 */
function recorrerCombo(combo, posicion)
{
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}

function reloadEstadoGarantiaCB() {
    var items = [];
    jQuery('#jform_intIdGarantia_sele_eg').html(items.join(''));
    items.push('<option value="">SELECCIONE UNA GARANTÍA</option>');
    if (contratos.lstGarantias.length > 0) {
        for (var x = 0; x < contratos.lstGarantias.length; x++) {
            if ( contratos.lstGarantias[x].published ==1  ) {
                items.push('<option value="' + contratos.lstGarantias[x].idGarantia + '">' + contratos.lstGarantias[x].codGarantia + '</option>');
            }
        }
    } else {
        items.push('GARANTÍA');
    }
    jQuery('#jform_intIdGarantia_sele_eg').html(items.join(''));
}

function changeEstadoGarantias(idGarantia) {
    for (var j = 0; j < contratos.lstGarantias.length; j++) {
        if (contratos.lstGarantias[j].idGarantia == idGarantia) {
            for (var k = 0; k < contratos.lstGarantias[j].estados.length; k++) {
                contratos.lstGarantias[j].estados[k].estadoAct = 0;
            }
        }
    }

}

/**
 *  Valida si una garania se puede eliminar
 * @param {type} regGarantia
 * @returns {Boolean}
 */
function availableDelGrt(id){
    var result = true;
    var regGrt = -1;
    for (var i = 0, max = contratos.lstGarantias.length; i < max; i++) {
        if ( contratos.lstGarantias[i].idGarantia == id ) {
            regGrt = i;
        }
    }
    
    if ( regGrt != -1 ){
        var estados = contratos.lstGarantias[regGrt].estados;
        if ( estados.length > 0) {
            for (var i = 0, max = estados.length; i < max; i++) {
                if ( estados[i].published == 1 ) {
                    result = false;
                }
            }
        }
    }
    return result;
}

