jQuery( document ).ready( function(){

    jQuery("#cargofnc-form").validate({
        rules: {
            jform_strNombre_cargo: {
                    required: true,
                    minlength: 2
            },
            jform_strDescripcion_cargo: {
                    required: true,
                    minlength: 2
            }
        },
        messages: {
            jform_strNombre_cargo: {
                    required: "Nombre requerido",
                    minlength: "Ingrese almenos 2 caracteres en nombre"
            },
            jform_strDescripcion_cargo: {
                    required: "Descripci&oacute;n requerida",
                    minlength: "Ingrese almenos 2 caracteres en Descripci&oacute;n"
            }
        },
        submitHandler: function () { 
            return false;
        },
        errorElement: "span"
    });
    
    //  Controlo el ingreso de caracteres alfabeticos y guion en el nombre de la agenda
    jQuery( '#jform_strDescripcion_ag' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) ) 
             || (tecla > 122 && !( tecla > 159 && tecla < 166 ) ) ) {
            return false;
        }
    });
   
    //  Controlo el ingreso de caracteres alfabeticos y guion en el campo de detalles de la agenda
    jQuery( '#jform_strCampo_dt' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) ) 
             || (tecla > 122 && !( tecla > 159 && tecla < 166 ) ) ) {
            return false;
        }
    });
   
    //  Controlo el ingreso de caracteres alfanumericos y guion para el valor del campo
    jQuery( '#jform_strValorCampo_dt' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) ) 
             || (tecla > 122 && !( tecla > 159 && tecla < 166 ) ) ) {
            return false;
        }
    });

});