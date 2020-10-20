jQuery( document ).ready( function(){

    //  Funcin para las pestañas
    jQuery("#tabsAgenda").tabs();

    jQuery("#agenda-form").validate({
        rules: {
            jform_strDescripcion_ag: { required: true, minlength: 2 },
            jform_strCampo_dt: { required: true, minlength: 2 },
            jform_strValorCampo_dt: { required: true, minlength: 2 } 
            
        },
        messages: {
            jform_strDescripcion_ag: {  required: "Nombre requerido",
                                        minlength: "Ingrese almenos 2 caracteres en nombre" },
            jform_strCampo_dt: {    required: "Campo requerido",
                                    minlength: "Ingrese almenos 2 caracteres en campo" },
            jform_strValorCampo_dt: {   required: "Valor requerido",
                                        minlength: "Ingrese almenos 2 caracteres en valor" }
                                    
        },
        submitHandler: function () { 
            return false;
        },
        errorElement: "span"
    });
    
    /**
     *  Desabilita los input para las fechas
     */
    jQuery('#jform_dteFechaInicio_ag').attr( 'readonly', 'readonly' );
    jQuery('#jform_dteFechaFin_ag').attr( 'readonly', 'readonly' );

    var sysop = navigator.platform.slice(0,3);

    //<editor-fold defaultstate="collapsed" desc="Validacion solo caracteres incluido la tilde y ñ">
    
    var inputABC = '#jform_strCampo_dt, ';
    inputABC += '#jform_strDescripcion_es, ';
    inputABC += '#jform_strDescripcion_ag';
    
    jQuery( inputABC ).keypress( function( e ){
        var tecla = e.which;
        var val = false;
        if ( sysop === 'Win') {
            val = jQuery.inArray(tecla, [32, 201, 233, 225, 193, 205, 237, 211, 243, 218, 250, 209, 241]);
        } else {
            val = jQuery.inArray(tecla, [32, 209, 239, 241, 164, 165]);
        }
            if ( !(tecla >= 65 && tecla <= 90) && !(tecla >= 97 && tecla <= 122) && val === -1){
            return false;
        }
    });
    
    //</editor-fold>
    
    //<editor-fold defaultstate="collapsed" desc="Caracteres alfanumericos con caracteres especiales">
    
    var inptAbcNumE = '#jform_strValorCampo_dt';
    
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

    //<editor-fold defaultstate="collapsed" desc="Solo numeros">
    
    var inputNum = '#jform_intNivel';
    
    jQuery( inputNum ).keypress( function( e ){
        var tecla = e.which;
        if ( !(tecla >= 48 && tecla <= 57) ){
            return false;
        }
    });
    
    //</editor-fold>


});