jQuery( document ).ready( function(){

    //
    //  Controlo el ingreso de caracteres alfabeticos en el campo nombre de rubro
    //
    jQuery( '#jform_strDescripcionTipo_ind' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) || tecla > 123 ){
            return false;
        }
    })


    //  Funcionalidades del os Botones
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            
            case 'tpoindicador.cancel':
                event.preventDefault();
                history.back();
                break;
                
            case 'tpoindicador.delete':
                jConfirm( "Esta seguro que quiere eliminar", "SIITA-ECORAE", function(result) {
                    if (result) {
                        jQuery("#jform_published").val(0);
                        task = 'tpoindicador.save';
                        Joomla.submitform( task );
                    } else {
                        event.preventDefault();
                        history.back();
                    }
                });
                break;

            default: 
                Joomla.submitform( task );
                break;
        }
          return false;
    };
    
});