
//  Obtengo URL completa del sitio
var url = window.location.href;
var path = url.split('?')[0];
jQuery(document).ready(function() {

    jQuery('#jform_Alineacion').change(function(event, idItem) {
        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: { option      : 'com_alineacion',
                    view        : 'operativa',
                    tmpl        : 'component',
                    format      : 'json',
                    action      : 'getObjetivos',
                    tpoEntidad  : jQuery('#jform_Alineacion').val()
            },
            error: function(jqXHR, status, error) {
                alert('Proyectos - Gestion Politica Nacional: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var tipo = parseInt(jQuery('#jform_Alineacion').val());
            loadItems(data, tipo, idItem);
        });
    });
});

function loadSinReg()
{
    var cad = '';
    cad += '<option value = 0 >' + LB_SIN_REGISTROS + ' </option>';
    jQuery("#jform_Objetivo").empty().append(cad);
}

/**
 * Craga los items segun el tipo seleccionado.
 * @param {type} data
 * @param {type} type
 * @param {type} idItem
 * @returns {undefined}
 */
function loadItems(data, type, idItem) {
    
    var opEntidad = ( type == 0 )   ? parseInt( jQuery( '#tpoEntidad' ).val() )
                                    : type;

    if (data.responseText) {
        switch( opEntidad ) {

            case 1:
                var obj = eval('(' + data.responseText + ')');
                if (obj.length > 0) {
                    loadObjetivosConvenio(obj, idItem);
                }
            break;
            
            case 2:
                var obj = eval('(' + data.responseText + ')');
                if (obj.length > 0) {
                    loadObjetivosConvenio(obj, idItem);
                }
            break;
            
            case 3:
                var obj = eval('(' + data.responseText + ')');
                if (obj.length > 0) {
                    loadObjetivosConvenio(obj, idItem);
                }
            break;
                
            case 7:
                var obj = eval('(' + data.responseText + ')');
                if (obj.length > 0) {
                    loadObjetivosUnidadGestion(obj, idItem);
                }
            break;                
                
            case 12:
                var obj = eval('(' + data.responseText + ')');
                if (obj.lstObjetivos) {
                    loadObjetivosEcorae(obj, idItem);
                }
            break;
                
            case 13:
                var obj = eval('(' + data.responseText + ')');
                if (obj.lstObjetivos) {
                    loadObjetivosEcorae(obj, idItem);
                }
            break;
            
            default:
                loadSinReg();
            break;
        }
    }
    else {
        jQuery("#jform_Objetivo").empty().append('<option>' + SIN_REGISTROS + '</option>');
    }
}
/**
 * Caraga los item cuando se quiere alinear a los objetivos de una unidad de gestion
 * @param {type} lstUG
 * @param {type} idItem
 * @returns {undefined}
 */
function loadObjetivosUnidadGestion(lstUG, idItem) {
    var cad = '';
    cad += '<option value = 0 >' + LB_SELECCIONE_OPC + ' </option>';
    for (var j = 0; j < lstUG.length; j++) {
        cad += '<optgroup label="' + lstUG[j].alias + '">';
        if (lstUG[j].lstObjetivos.length > 0) {
            for (var k = 0; k < lstUG[j].lstObjetivos.length; k++) {
                var cadSel = (idItem == lstUG[j].lstObjetivos[k].idObjEnt) ? 'selected' : '';
                cad += '<option value ="' + lstUG[j].lstObjetivos[k].idObjEnt + '" ' + cadSel + '">' + lstUG[j].lstObjetivos[k].descripcion + ' </option>';
            }
        }
        cad += '</optgroup>';
    }
    jQuery("#jform_Objetivo").empty().append(cad);
}

/**
 * Caraga los item cuando se quiere alinear a los objetivos de una unidad de gestion
 * @param {type} lstUG
 * @param {type} idItem
 * @returns {undefined}
 */
function loadObjetivosConvenio(lstUG, idItem) {
    var cad = '';
    cad += '<option value = 0 >' + LB_SELECCIONE_OPC + ' </option>';
    for (var j = 0; j < lstUG.length; j++) {
        cad += '<optgroup label="' + lstUG[j].grDescripcion + '">';
        if (lstUG[j].lstObjetivos.length > 0) {
            for (var k = 0; k < lstUG[j].lstObjetivos.length; k++) {
                var cadSel = (idItem == parseInt(lstUG[j].lstObjetivos[k].idObjEnt)) ? 'selected' : '';
                cad += '<option value ="' + lstUG[j].lstObjetivos[k].idObjEnt + '" ' + cadSel + '>' + lstUG[j].lstObjetivos[k].descripcion + ' </option>';
            }
        }
        cad += '</optgroup>';
    }
    jQuery("#jform_Objetivo").empty().append(cad);
}



function loadObjetivosEcorae(items, idItem) {

    var valItem = ( typeOf( idItem ) == "null" )? 0 
                                                : idItem;
    
    var cad = '';
    cad += '<option value = 0 >' + LB_SELECCIONE_OPC + ' </option>';
    cad += '<optgroup label="' + items.descripcionPln + '">';
    if (items.lstObjetivos) {
        for (var k = 0; k < items.lstObjetivos.length; k++) {

            var cadSel = ( valItem == items.lstObjetivos[k].idObjEnt )  ? 'selected' 
                                                                        : '';

            cad += '<option value ="' + items.lstObjetivos[k].idObjEnt + '" ' + cadSel + '>' + items.lstObjetivos[k].descripcion + ' </option>';
        }
    }
    cad += '</optgroup>';
    jQuery("#jform_Objetivo").empty().append(cad);
}

/**
 * Recorre un combo 
 * @param {type} combo
 * @param {type} posicion
 * @returns {undefined}
 */
function recorrerCombo(combo, posicion) {
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    })
}