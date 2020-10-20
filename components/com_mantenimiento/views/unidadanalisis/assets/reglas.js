jQuery( document ).ready( function(){

    //
    //  Controlo el ingreso de caracteres alfabeticos en el campo nombre de rubro
    //
    jQuery( '#jform_strDescripcion_tipovar' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) || tecla > 123 ){
            return false;
        }
    })

})