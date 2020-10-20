jQuery( 'document' ).ready( function(){
    var banIdEE = -1;
    
    jQuery( '#btnAddEnfEco' ).live( 'click', function(){
        var idEEcorae = jQuery( '#jform_cbEnfoqueEcorae' ).val();
        var EEcorae = jQuery( '#jform_cbEnfoqueEcorae :selected' ).text();
        
        if( banIdEE == -1 ){
            
            var objIndMasculino = new Indicador( 0, 0, EEcorae,'bh',jQuery('#jform_intEnfEcoMasculino').val() );
            objIndMasculino.idClaseIndicador = 1;
            objIndMasculino.idTpoUndMedida = 2;
            objIndMasculino.idUndMedida = 6;
            objIndMasculino.idUndAnalisis = 6;
            objIndMasculino.idDimension = idEEcorae;
            objIndMasculino.idCategoria = 1;
            
            var objIndFemenino = new Indicador( 0, 0, EEcorae,'bm', jQuery('#jform_intEnfEcoFemenino').val() );
            objIndFemenino.idClaseIndicador = 1;
            objIndFemenino.idTpoUndMedida = 2;
            objIndFemenino.idUndMedida = 6;
            objIndFemenino.idUndAnalisis = 7;
            objIndFemenino.idDimension = idEEcorae;
            objIndFemenino.idCategoria = 1;
            
            var objIndTotal = new Indicador( 0, 0, EEcorae,'b', jQuery('#jform_intEnfEcoTotal').val() );
            objIndTotal.idClaseIndicador = 1;
            objIndTotal.idTpoUndMedida = 2;
            objIndTotal.idUndMedida = 6;
            objIndTotal.idUndAnalisis = 4;
            objIndTotal.idDimension = idEEcorae;
            objIndTotal.idCategoria = 1;
            
            if( !objGestionIndicador.existeIndEEcorae( objIndMasculino ) ) {
                var dtaIndEEcorae = objGestionIndicador.addIndEEcorae( objIndMasculino, objIndFemenino, objIndTotal );

                //  Agrego la fila creada a la tabla
                jQuery('#lstEnfEco > tbody:last').append( objGestionIndicador.addFilaIndEEcorae( EEcorae, dtaIndEEcorae, 0 ) );
            }else{
                jAlert( 'Indicador ya Registrado', 'SIITA - ECORAE' );
            }
        }else{
            //  Actualizo contenido
            objGestionIndicador.lstEnfEcorae[banIdEE].eeMasculino.umbral = jQuery('#jform_intEnfEcoMasculino').val();
            objGestionIndicador.lstEnfEcorae[banIdEE].eeFemenino.umbral = jQuery('#jform_intEnfEcoFemenino').val();
            objGestionIndicador.lstEnfEcorae[banIdEE].eeTotal.umbral = jQuery('#jform_intEnfEcoTotal').val();

            //  Actualizo contenido Fila
            updInfoFilaEEcorae( banIdEE, objGestionIndicador.updFilaIndEEcorae( EEcorae, objGestionIndicador.lstEnfEcorae[banIdEE] ) );
            
            //  EnCero la bandera
            banIdEE = -1;
        }
    })
    
    
    jQuery( '.updEE' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var dtaEE = objGestionIndicador.lstEnfEcorae[idFila];
        banIdEE = idFila;
        
        //  Recorro el combo hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbEnfoqueEcorae option' ) , dtaEE.eeMasculino.idDimension );
        
        //  Actualizo informacion de formulario GAP 
        jQuery('#jform_intEnfEcoMasculino').attr( 'value', dtaEE.eeMasculino.umbral );
        jQuery('#jform_intEnfEcoFemenino').attr( 'value', dtaEE.eeFemenino.umbral );
        jQuery('#jform_intEnfEcoTotal').attr( 'value', dtaEE.eeTotal.umbral );
    })
    
    //
    //  Gestiono la eliminacion de un registro
    //
    jQuery( '.delEE' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Indicador", "SIITA - ECORAE", function( result ){
            if( result ) {
                //  Elimino Registro de la lista GAP
                objGestionIndicador.delIndEEcorae( idFila );
                
                //  Elimino Fila de la Tabla de Indicadores GAP
                delFilaTbEEcorae( idFila );
            } 
        });
    })
    
    //
    //  Actualizo informacion de una determinada fila de la tabla GAP
    //
    function updInfoFilaEEcorae( idFila, dataUpd )
    {
        jQuery( '#lstEnfEco tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).html( dataUpd );
            }
        })
    }

    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaTbEEcorae( idEE )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstEnfEco tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idEE ){
                jQuery( this ).remove();
            }
        })
    }
    
    //
    //  Recorre los comboBox del Formulario a la posicion inicial
    //
    function recorrerCombo( combo, posicion )
    {
        jQuery( combo ).each( function(){
            if( jQuery( this ).val() == posicion ){
                jQuery( this ).attr( 'selected', 'selected' );
            }
        })
    }
})