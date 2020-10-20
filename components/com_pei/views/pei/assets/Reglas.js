jQuery( document ).ready( function(){

    //  Pestañas General
    jQuery('#tabsPlaEstIns').tabs();
    
    //  Pestaña Ubicacion Geografica
    jQuery('#tabsPAPP').tabs();
    
    //
    //  Controlo el ingreso de caracteres alfanumercos en la descripción de una propuesta de proyecto
    //
    jQuery( '#jform_strDescripcion_ob' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 ) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    });
    
   
    //
    //  Especifica el tamaño de los select
    //
    jQuery("#jform_intId_tpoObj").css({width: "235px"});
    jQuery("#jform_intPrioridad_ob").css({width: "235px"});
    jQuery("#jform_strDescripcion_ob").css({width: "235px"});
    jQuery("#jform_blnVigente_pi").css({width: "90px"});
    jQuery("#jform_strNombreContexto-lbl, #jform_strDescripcionContexto-lbl").attr( 'style', 'min-width: 75px;' );

    //Readonly de los campos fecha
    jQuery('#jform_dteFechainicio_pi' ).attr( 'readonly', 'readonly' );
    jQuery('#jform_dteFechafin_pi' ).attr( 'readonly', 'readonly' );
    
    var optionsAccordion = {collapsible : true,
                            heightStyle : "content",
                            autoHeight  : false,
                            clearStyle  : true,
                            header      : 'h3'};
                
     jQuery("#accordion").accordion(optionsAccordion);
    
});