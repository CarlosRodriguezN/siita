jQuery(document).ready(function() {

    //  Pestaña Ubicacion Geografica
    jQuery('#tabsUbicacion').tabs();

    //
    //  Controlo el ingreso de caracteres para el campo codigo con aceptacion de ".", "_", "-"
    //  numeros y letras.
    //
    jQuery('#jform_strDescripcion_pi').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });


    jQuery('#jform_strAlias_pi').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });
    
    jQuery('#jform_strDescripcion_ob').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });

});




