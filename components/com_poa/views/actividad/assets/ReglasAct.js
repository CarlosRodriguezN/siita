/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

registroAct = 0;
idObjetivo = 0;
lstTmpObjetivos = new Array();
lstTmpActividad = new Array();
lstTmpArchivos = new Array();
registroObj = 0;

jQuery(document).ready(function() {
    registroObj = jQuery("#registroObj").val();
    idObjetivo = jQuery("#idObjetivo").val();
    lstTmpObjetivos = parent.window.objLstObjetivo.lstObjetivos;
    lstTmpArchivos = parent.window.lstArchivos;
    lstTmpActividad = getLstActividadesObjetivo();

    reloadActividadesTable();

    jQuery("#jform_intIdResponsable").css("width", "135px");



    var url = window.location.href;
    var path = url.split('?')[0];

    jQuery("#jform_intIdUnidadGestion").change(function(event, idResponsable) {

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_poa',
                view: 'actividad',
                tmpl: 'component',
                format: 'json',
                action: 'getResonsables',
                idUnidadGestion: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert('Plan Estrategico Istitucional - Gestión Actividades: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">Responsable</option>');
                for (x = 0; x < numRegistros; x++) {
                    var select = (dataInfo[x].id == idResponsable) ? "selected" : "";
                    items.push('<option value="' + dataInfo[x].id + '" ' + select + ' >' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">Sin registros disponibles</option>');
            }
            jQuery('#jform_intIdResponsable').html(items.join(''));
        });
    });
});

/**
 * 
 
 * @returns {Array} */
function getLstActividadesObjetivo() {
    var lstTmpActividades = new Array();
    for (var j = 0; j < lstTmpObjetivos.length; j++) {
        if (lstTmpObjetivos[j].registroObj == parseInt(registroObj)) {
            lstTmpActividades = lstTmpObjetivos[j].lstActividades;
        }
    }
    return lstTmpActividades;
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