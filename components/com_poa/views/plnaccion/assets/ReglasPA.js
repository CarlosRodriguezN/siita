jQuery( document ).ready( function(){

    //  Pestañas General
    jQuery('#tabsPlaEstIns').tabs();
    
    //  Pestaña Ubicacion Geografica
    jQuery('#tabsUbicacion').tabs();
    
    //
    //  Controlo el ingreso de caracteres alfanumercos en la descripción de una Acción
    //
    jQuery( '#jform_strDescripcion_plnAccion' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 ) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres numericos en el campo presupuesto para la acción
    //
    jQuery( '#jform_mnPresupuesto_plnAccion' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 )){
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres para el campo observación con aceptacion de ".", "_", "-"
    //  numeros y letras.
    //
    jQuery( '#jform_strObservacion_plnAccion' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    });

    //
    //  Controlo el ingreso de caracteres numericos en el campo fecha de ejecución
    //
    jQuery( '#jform_dteFechaEjecucion_planAccion' ).keypress( function( e ){
        var tecla = e.which;
        if (  tecla != 45 && !(tecla > 47 && tecla < 58 )){
            return false;
        }
    });
    
    
    //
    //  Especifica el tamaño de los select
    //
     jQuery("#jform_intTpoActividad_plnAccion").css({width: "160px"});
     jQuery("#jform_strDescripcion_plnAccion").css({width: "154px"});
     jQuery("#jform_unidad_gestion").css({width: "160px"});
     jQuery("#jform_intId_ugf").css({width: "160px"});
     jQuery("#jform_intCodigo_ug").css({width: "160px"});
     jQuery("#jform_mnPresupuesto_plnAccion").css({width: "158px"});
     jQuery("#jform_strObservacion_plnAccion").css({width: "154px"});
    
});