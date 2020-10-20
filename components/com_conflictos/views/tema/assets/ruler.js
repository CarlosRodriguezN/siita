jQuery(document).ready(function() {
    
    var sysop = navigator.platform.slice(0,3);
    
    jQuery("#temaTab").tabs();
    
    /**
     *  Homologo los tamaños de los selects
     */
    jQuery("#jform_intId_tt").css({width: "220px"});
    jQuery("#jform_intId_ni").css({width: "220px"});
    jQuery("#jform_fuente_intId_tf").css({width: "220px"});
    jQuery("#jform_fuente_intId_fte").css({width: "220px"});
    jQuery("#jform_actor_intId_ad").css({width: "220px"});
    jQuery("#jform_estado_intId_ec").css({width: "220px"});
    jQuery("#jform_undTerr_provicia").css({width: "220px"});
    jQuery("#jform_undTerr_canton").css({width: "220px"});
    jQuery("#jform_undTerr_parroquia").css({width: "220px"});
    
    
    /**
     *  Desabilita los input para las fechas
     */
    jQuery('#jform_fuente_dteFecha_tf').attr( 'readonly', 'readonly' );
    jQuery('#jform_actor_dteFecha_ad').attr( 'readonly', 'readonly' );
    jQuery('#jform_estado_dteFechaInicio_te').attr( 'readonly', 'readonly' );
    jQuery('#jform_estado_dteFechaFin_te').attr( 'readonly', 'readonly' );
    
    /**
     * VALIDACION DE LOS CAMPOS
     */
    jQuery("#conflictos-form").validate({
        rules: {
            jform_intId_tt          : { requiredlist: true },
            jform_intId_ni          : { requiredlist: true },
            jform_strTitulo_tma     : { required: true, minlength: 2 },
            jform_strResumen_tma    : { required: true, minlength: 2 },
            
            jform_fuente_intId_tf       : { requiredlist: true },
            jform_fuente_intId_fte      : { requiredlist: true },
            jform_fuente_dteFecha_tf    : { required: true, dateAMD: true },
            jform_strObservacion_tf     : { required: true, minlength: 2 },
            
            jform_actor_intId_ad            : { requiredlist: true },
            jform_actor_dteFecha_ad         : { required: true, dateAMD: true },
            jform_actor_strDescripcion_ad   : { required: true, minlength: 2 },
            
            jform_estado_intId_ec           : { requiredlist: true },
            jform_estado_dteFechaInicio_te  : { required: true, dateAMD: true },
            jform_estado_dteFechaFin_te     : { dateAMD: true },
            
            jform_undTerr_provicia  : { requiredlist: true }
            
        },
        
        messages: {
            jform_intId_tt          : { requiredlist: "Seleccione Tipo de Tema" },
            jform_intId_ni          : { requiredlist: "Seleccione Nivel" },
            jform_strTitulo_tma     : { required: "Titulo requerido",
                                        minlength: "Ingrese almenos 2 caracteres en titulo" },
            jform_strResumen_tma    : { required: "Resumen requerido",
                                        minlength: "Ingrese almenos 2 caracteres en resumen" },
            
            jform_fuente_intId_tf       : { requiredlist: "Seleccione Tipo de Fuente" },
            jform_fuente_intId_fte      : { requiredlist: "Seleccione Fuente" },
            jform_fuente_dteFecha_tf    : { required: "Fecha requerida" },
            jform_strObservacion_tf     : { required: "Observaci&oacute;n requerida",
                                            minlength: "Ingrese almenos 2 caracteres en Observaci&oacute;n" },
            
            jform_actor_intId_ad            : { requiredlist: "Seleccione Actor" },
            jform_actor_dteFecha_ad         : { required: "Fecha requerida" },
            jform_actor_strDescripcion_ad   : { required: "Discurso requerido",
                                                minlength: "Ingrese almenos 2 caracteres en Discurso" },
                                            
            jform_estado_intId_ec           : { requiredlist: "Seleccione Estado" },
            jform_estado_dteFechaInicio_te  : { required: "Fecha requerida" },
            
            jform_undTerr_provicia  : { requiredlist: "Seleccione Provincia" }
            
        },
        submitHandler: function () { 
            return false;
        },
        errorElement: "span"
    });
    
    
    //<editor-fold defaultstate="collapsed" desc="Validacion solo caracteres incluido la tilde y ñ">
    
    var inputABC = '#jform_strTitulo_tma';
    
    jQuery( inputABC ).keypress( function( e ){
        var tecla = e.which;
        var val = false;
        if ( sysop == 'Win') {
            val = jQuery.inArray(tecla, [32, 201, 233, 225, 193, 205, 237, 211, 243, 218, 250, 209, 241]);
        } else {
            val = jQuery.inArray(tecla, [32, 209, 239, 241, 164, 165]);
        }
            if ( !(tecla >= 65 && tecla <= 90) && !(tecla >= 97 && tecla <= 122) && val == -1){
            return false;
        }
    });
    
    //</editor-fold>
    
    //<editor-fold defaultstate="collapsed" desc="Caracteres alfanumericos con caracteres especiales">
    
    var inptAbcNumE = '#jform_strResumen_tma, ';
    inptAbcNumE += '#jform_strObservaciones_tma, ';
    inptAbcNumE += '#jform_strSugerencias_tma, ';
    inptAbcNumE += '#jform_strObservacion_tf, ';
    inptAbcNumE += '#jform_actor_strDescripcion_ad';
    
    jQuery( inptAbcNumE ).keypress( function( e ){
        var tecla = e.which;
        if ( sysop == 'Win') {
            val = jQuery.inArray(tecla, [95, 201, 233, 225, 193, 205, 237, 211, 243, 218, 250, 209, 241]);
        } else {
            val = jQuery.inArray(tecla, [95, 209, 239, 241, 164, 165]);
        }
        if ( !(tecla >= 32 && tecla <= 90) && !(tecla >= 97 && tecla <= 122) && val == -1){
            return false;
        }
    });
    
    //</editor-fold>


});
