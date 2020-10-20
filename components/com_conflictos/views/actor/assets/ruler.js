jQuery(document).ready(function() {
    
    jQuery.alerts.okButton = JSL_SI;
    jQuery.alerts.cancelButton = JSL_NO;
    
    jQuery("#temaTab").tabs();
    
    /**
     *  Homologo los tamaños de los selects
     */
    jQuery("#jform_intId_inc").css({width: "220px"});
    jQuery("#jform_intId_leg").css({width: "220px"});
    jQuery("#jform_intId_fcn").css({width: "220px"});
    
    
    /**
     *  Desabilita los input para las fechas
     */
    jQuery('#jform_dtefecha_fi').attr( 'readonly', 'readonly' );
    jQuery('#jform_dtefecha_ini_fi').attr( 'readonly', 'readonly' );
    jQuery('#jform_dtefecha_fin_fi').attr( 'readonly', 'readonly' );
    
    /**
     * VALIDACION DE LOS CAMPOS
     */
    jQuery("#actores-form").validate({
        rules: {
            jform_strNombre_act     : { required: true, minlength: 2 },
            jform_strApellido_act   : { required: true, minlength: 2 },
            jform_strCorreo_act     : { required: true, email: true },
            
            jform_intId_inc         : { requiredlist: true },
            jform_dtefecha_fi       : { required: true, dateAMD: true },
            
            jform_intId_leg         : { requiredlist: true },
            jform_strDescripcion_fl : { required: true, minlength: 2 },
            
            jform_intId_fcn         : { requiredlist: true },
            jform_dtefecha_ini_fi   : { required: true, dateAMD: true },
            jform_dtefecha_fin_fi   : { required: true, dateAMD: true }
            
        },
        
        messages: {
            jform_strNombre_act     : { required: "Nombre requerido", 
                                        minlength: "Ingrese almenos 2 caracteres en Nombre" },
            jform_strApellido_act   : { required: "Apellido requerido", 
                                        minlength: "Ingrese almenos 2 caracteres en Apellido" },
            jform_strCorreo_act     : { required: "Correo requerido", 
                                        email: "Ingrese un correo electronico valido" },
            
            jform_intId_inc         : { requiredlist: "Seleccione Incidencia" },
            jform_dtefecha_fi       : { required: "Fecha requerido" },
            
            jform_intId_leg         : { requiredlist: "Seleccione Legitimidad" },
            jform_strDescripcion_fl : { required: "Descripci&oacute;n requerida", 
                                        minlength: "Ingrese almenos 2 caracteres en descripci&oacute;n" },
            
            jform_intId_fcn         : { requiredlist: "Seleccione Funci&oacute;n" },
            jform_dtefecha_ini_fi   : { required: "Fecha requerida" },
            jform_dtefecha_fin_fi   : { required: "Fecha requerida" }
            
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
    jQuery("#actores-form").submit();
    
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