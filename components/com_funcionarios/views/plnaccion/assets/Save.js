jQuery(document).ready(function() {
    Joomla.submitbutton = function(task)
    {
        switch (task) {
            case 'plnaccion.registrar':
                var idObjetivo = jQuery( '#idRegObjetivo' ).val();
                var idPoa = jQuery( '#idRegPoa' ).val();
                window.parent.objLstPoas.lstPoas[idPoa].lstObjetivos[idObjetivo].lstAcciones = new Array();
                var lstAcciones = window.parent.objLstPoas.lstPoas[idPoa].lstObjetivos[idObjetivo].lstAcciones;
                
                for( var x = 0; x < lstTmpAcciones.length; x++ ){
                    lstAcciones.push( lstTmpAcciones[x] );
                }
                
                window.parent.SqueezeBox.close();
                break;

            case 'plnaccion.cancel':
                window.parent.SqueezeBox.close();
                break;
        }
    };

});
