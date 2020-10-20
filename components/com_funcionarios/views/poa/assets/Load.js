jQuery(document).ready(function() {
    
    var idPoa = jQuery("#idRegPoa").val();
    var padrePoa = jQuery("#padrePoa").val();
    var opPoa = jQuery("#opPoa").val();
    
    //  Carga las fechas de inicio y fin del plan segun el select de años
    jQuery('#jform_anioPOA').trigger('change');
    
    //  Controla los casos de ingreso o edicion antes de cargar el formulario
    switch (true){
        case ( opPoa == 1 && padrePoa == 0 ):
            loadDataPoa(idPoa);
            break;
        case ( opPoa == 1 && padrePoa != 0 ):
            loadDataPoa(idPoa);
            jQuery("#toolbar-save").css("display", "none");
            break;
    }
    
    /**
     *  En caso de actualizacion o de revision de informacion carga los datos en el formulario
     * @param {type} id
     * @returns {undefined}
     */
    function loadDataPoa( id )
    {
        var poa = window.parent.objLstPoas.lstPoas[id];
        var anio = parseInt(poa.fechaInicioPoa.toString().split('-')[0]);
        jQuery("#jform_strDescripcion_pi").val(poa.descripcionPoa);
        jQuery("#jform_strAlias_pi").val(poa.aliasPoa);
        var pos = getPosition( jQuery("#jform_anioPOA option" ), anio );
        if ( pos ) {
            recorrerCombo( jQuery("#jform_anioPOA option" ), pos);
            jQuery('#jform_anioPOA').trigger('change');
        } else {
            jQuery("#jform_anioPOA" ).css("display", "none");
            jQuery("#jform_anioPOA-lbl" ).css("display", "none");
            jQuery("#jform_dteFechainicio_pi").val(poa.fechaInicioPoa);
            jQuery("#jform_dteFechafin_pi").val(poa.fechaFinPoa);
        }
    }
});

/**
 *  Recorro el combo de provincias a una determinada posicion
 * 
 * @param {type} combo
 * @param {type} posicion
 * @returns {undefined}
 */
function recorrerCombo( combo, posicion )
{
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}

/**
 *  Retorna la pocision del select donde el año es igual al especificado caso contracio retorna False
 * @param {type} combo          Select de años
 * @param {type} text           Año a obtener la pocision
 * @returns {unresolved}
 */
function getPosition(combo, text)
{
    var result = false;
    jQuery(combo).each(function() {
        if (jQuery(this).text() == text) {
            result = jQuery(this).val();
        }
    });
    return result;
}

    
