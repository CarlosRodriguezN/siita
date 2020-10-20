/*
 * maneja las garanatias
 */
var idGarantia = 0;
jQuery(document).ready(function() {

    jQuery('#addGarantia').click(function() {
        var data = new Array();
        data["idTipoGarantia"] = jQuery("#jform_intIdTpoGarantia_tg").val();
        data["idFormaGarantia"] = jQuery("#jform_intIdFrmGarantia_fg").val();
        data["codGarantia"] = jQuery("#jform_intCodGarantia_gta").val();
        data["monto"] = jQuery("#jform_dcmMonto_gta").val();
        data["fchDesde"] = jQuery("#jform_dteFechaDesde_gta").val();
        data["fchHasta"] = jQuery("#jform_dteFechaHasta_gta").val();
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
                reloadEstadoGarantiaCB();
            }
            else {
                data["idGarantia"] = idGarantia;
                actGarantia(data);
                idGarantia = 0;
            }
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
        jQuery("#jform_dcmMonto_gta").val(data.monto);
        jQuery("#jform_dteFechaDesde_gta").val(data.fchDesde);
        jQuery("#jform_dteFechaHasta_gta").val(data.fchHasta);
        jQuery("#jform_attrCodigo").val(data.codAtributo);
        jQuery("#jform_attrNombre").val(data.nombre);
        jQuery("#jform_attrValor").val(data.valor);
        jQuery("#jform_strObservasion_ge").val(data.obsGarantiaEstado);
        recorrerCombo(jQuery('#jform_intIdEstadoGarantia_eg option'), data.idEstadoGarantia);
        //cambio el texto del boton 

    });
//eliminar garantia
    jQuery('.delGarantia').live("click", function() {
        idGarantia = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmGarantia(idGarantia);
                reloadGarantiaTable();
            } else {

            }
        });
    });

    jQuery("#addGarantiasTable").click(function() {
        jQuery('#editGarantiaForm').css("display", "block");
        jQuery('#imgGarantiaForm').css("display", "none");
    });

    jQuery("#cancelGarantia").click(function() {
        clCmpGarantia();
        jQuery('#editGarantiaForm').css("display", "none");
        jQuery('#imgGarantiaForm').css("display", "block");
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
    row += ' <td>' + data.monto + ' </td>';
    row += ' <td>' + data.fchDesde + ' </td>';
    row += ' <td>' + data.fchHasta + ' </td>';
    row += ' <td>' + est + ' </td>';
    row += ' <td style="width:15px"><a class="editGarantia">Editar</a></td>';
    row += ' <td style="width:15px"><a class="delGarantia">Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLstGarantias > tbody:last').append(row);
}



/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpGarantia() {

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
                items.push('<option value="' + contratos.lstGarantias[x].idGarantia + '">' + contratos.lstGarantias[x].codGarantia + '</option>');
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

