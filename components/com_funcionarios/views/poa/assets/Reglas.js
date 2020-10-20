jQuery(document).ready(function() {
    
    /**
     *  Controla el cambio en el select de años
     */
    jQuery("#jform_anioPOA").change(function() {
        var anio = jQuery("#jform_anioPOA :selected").text();
        jQuery("#jform_dteFechainicio_pi").val(anio + "-01-01");
        jQuery("#jform_dteFechafin_pi").val(anio + "-12-31");
        
    });
    
    /**
     * Controlo el ingreso de caracteres para el campo descripcion con aceptacion 
     * de ".", "_", "-" numeros y letras.
     */
    jQuery('#jform_strDescripcion_pi').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });

    /**
     * Controlo el ingreso de caracteres para el campo Alias
     */
    jQuery('#jform_strAlias_pi').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });
    
    /**
     * Desavilita los input de fechas
     */
    jQuery( '#jform_dteFechainicio_pi, #jform_dteFechafin_pi' ).attr( 'readonly', 'readonly' );
    
});




