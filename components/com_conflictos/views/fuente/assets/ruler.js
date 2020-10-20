jQuery(document).ready(function() {
    
    jQuery.alerts.okButton = JSL_SI;
    jQuery.alerts.cancelButton = JSL_NO;
    
    jQuery("#temaTab").tabs();
    
    /**
     *  Homologo los tamaños de los selects
     */
    jQuery("#jform_intId_tf").css({width: "220px"});
    jQuery("#jform_undTerr_provicia").css({width: "220px"});
    jQuery("#jform_undTerr_canton").css({width: "220px"});
    jQuery("#jform_undTerr_parroquia").css({width: "220px"});
    jQuery("#jform_intId_inc").css({width: "220px"});
    jQuery("#jform_intId_leg").css({width: "220px"});
    
    
    /**
     *  Desabilita los input para las fechas
     */
    jQuery('#jform_dtefecha_fi').attr( 'readonly', 'readonly' );
    jQuery('#jform_dteFecha_fl').attr( 'readonly', 'readonly' );
    
    /**
     * VALIDACION DE LOS CAMPOS
     */
    jQuery("#fuentes-form").validate({
        rules: {
            jform_intId_tf              : { requiredlist: true },
            jform_strDescripcion_fte    : { required: true, minlength: 2 },
            jform_strObservaciones_fte  : { required: true, minlength: 2 },
            
            jform_undTerr_provicia      : { requiredlist: true },
            jform_undTerr_canton        : { requiredlist: true },
            jform_undTerr_parroquia     : { requiredlist: true },
            
            jform_intId_inc     : { requiredlist: true },
            jform_dtefecha_fi   : { required: true, dateAMD: true },
            
            jform_intId_leg         : { requiredlist: true },
            jform_dteFecha_fl       : { required: true, dateAMD: true },
            jform_strDescripcion_fl : { required: true, minlength: 2 }
        },
        
        messages: {
            jform_intId_tf              : { requiredlist: 'Seleccione Tipo de Fuente' },
            jform_strDescripcion_fte    : { required: 'Nonbre de fuente requerido',
                                            minlength: 'Ingrese almenos 2 caracteres en fuente' },
            jform_strObservaciones_fte  : { required: 'Observaci&oacute;n requerida',
                                            minlength: 'Ingrese almenos 2 caracteres en observaci&oacute;n' },
            
            jform_undTerr_provicia      : { requiredlist: 'Seleccione Provincia' },
            jform_undTerr_canton        : { requiredlist: 'Seleccione Cant&oacute;n' },
            jform_undTerr_parroquia     : { requiredlist: 'Seleccione Parroquia' },
            
            jform_intId_inc     : { requiredlist: 'Seleccione Incidencia' },
            jform_dtefecha_fi   : { required: 'Fecha requerida' },
            
            jform_intId_leg         : { requiredlist: 'Seleccione Legitimidad' },
            jform_dteFecha_fl       : { required: 'Fecha requerida' },
            jform_strDescripcion_fl : { required: 'Descripci&oacute;n requerida', 
                                        minlength: 'Ingrese almenos 2 caracteres en descripci&oacute;n' }
            
        },
        submitHandler: function () { 
            return false;
        },
        errorElement: "span"
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


/**
 *  Resetea los sms del validate en los input de una determinada parte del formulario 
 * @param {type} form
 * @returns {undefined}
 */
function cleanValidateForn ( form )
{
    jQuery("#fuentes-form").submit();
    
    jQuery( form + " select").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( form + " input[type=text]").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( form + " textarea").each(function () {
        jQuery( this ).removeClass( "error" );
    });
}