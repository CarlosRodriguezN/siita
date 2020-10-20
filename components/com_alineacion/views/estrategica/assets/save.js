jQuery(document).ready(function() {
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'alineacion.asignar':
                var regObjetivo = jQuery("#idRegObjetivo").val();

                for( var j = 0; j < window.parent.objLstObjetivo.lstObjetivos.length; j++ ) {
                    if( window.parent.objLstObjetivo.lstObjetivos[j].registroObj == regObjetivo ) {
                        for( var k = 0; k < oGestionAlineacion.lstAlineaciones.length; k++ ) {
                            window.parent.objLstObjetivo.lstObjetivos[j].lstAlineaciones = oGestionAlineacion.lstAlineaciones;

                            if (typeof (jQuery("#tpoPln").val()) != 'undefined' && (jQuery("#tpoPln").val() == 3 || jQuery("#tpoPln").val() == 4 || jQuery("#tpoPln").val() == 2)) {
                                window.parent.semaforoAlineacion(j, jQuery("#tpoPln").val(), jQuery("#registroPln").val() );
                            } else {
                                window.parent.semaforoAlineacion(j);
                            }

                        }
                    }
                }

                window.parent.SqueezeBox.close();
            break;
                
            case 'alineacion.cancel':
                window.parent.SqueezeBox.close();
            break;
                
            default:
                Joomla.submitform(task);
            break;
        }
    };

    /**
     * 
     * @param {type} val
     * @returns {undefined}
     */
    function semaforoAlineacion( id ) {
        
        if( window.parent.objLstObjetivo.lstObjetivos[j].lstAlineaciones.length > 0 ){
            jQuery( id, window.parent.document ).attr( 'src', val["imgAtributo"] );
            jQuery( id, window.parent.document ).attr( 'title', val["msgAtributo"] );
            jQuery( id, window.parent.document ).attr( 'style', val["msgStyle"] );
        }
        
    }    
    
});
