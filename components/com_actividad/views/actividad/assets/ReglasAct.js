registroObj = parseInt(jQuery("#registroObj").val());
idObjetivo = 0;
registroAct = 0;
idPlan = 0;

tmpLstActividades=  new Array();
lstTmpArchivos = new Array();

url = window.location.href;
path = url.split('?')[0];

flagUpdActs = false;

jQuery(document).ready(function() {
    
    /**
     * Aplica los estilos para los tabs
     */
    jQuery("#tabsActividades").tabs();

    /**
     * Desavilita los input de fechas
     */
    jQuery( '#jform_fchActividad_tpg' ).attr( 'readonly', 'readonly' );

    /**
     *  Especifica el tamaño de los select
     */
    jQuery("#jform_intIdTipoGestion_tpg").css({width: "250px"});
    jQuery("#jform_strDescripcion_act").css({width: "249px"});
    jQuery("#jform_strObservacion_tpg").css({width: "249px"});
    
    /**
     * cambios en responsable
     */
    jQuery("#jform_intIdUnidadGestion").change(function(event, idResponsable) {

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_actividad',
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
