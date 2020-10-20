jQuery( 'document' ).ready( function(){
    
    var banIdEI = -1;
    
    //  Gestiono el registro de Indicador de Igualdad
    jQuery( '#btnAddEI' ).live( 'click', function(){
        
        var idEnfoque = jQuery( '#jform_cbEnfoqueIgualdad' ).val();
        var enfoque = jQuery( '#jform_cbEnfoqueIgualdad :selected' ).text();
        
        var idDimension = jQuery( '#jform_idEnfoqueIgualdad' ).val();
        var dimension = jQuery( '#jform_idEnfoqueIgualdad :selected' ).text();
        
        if( banIdEI == -1 ){
            if( !objGestionIndicador.existeIndEIgualdad( idDimension ) ) {
                var objIndMasculino = new Indicador( 0, 0, dimension,'bh', jQuery('#jform_intEIMasculino').val() );
                objIndMasculino.idClaseIndicador = 1;
                objIndMasculino.idTpoUndMedida = 2;
                objIndMasculino.idUndMedida = 6;
                objIndMasculino.idUndAnalisis = 6;
                objIndMasculino.idEnfoque = idEnfoque;
                objIndMasculino.enfoque = enfoque;
                objIndMasculino.idDimension = idDimension;
                objIndMasculino.idCategoria = 1;
                
                
                var objIndFemenino = new Indicador( 0, 0, dimension, 'bm', jQuery('#jform_intEIFemenino').val(), '' );
                objIndFemenino.idClaseIndicador = 1;
                objIndFemenino.idTpoUndMedida = 2;
                objIndFemenino.idUndMedida = 6;
                objIndFemenino.idUndAnalisis = 7;
                objIndFemenino.idEnfoque = idEnfoque;
                objIndFemenino.enfoque = enfoque;
                objIndFemenino.idDimension = idDimension;
                objIndFemenino.idCategoria = 1;
                
                var objIndTotal = new Indicador( 0, 0, dimension, 'b', jQuery('#jform_intEITotal').val(), '' );
                objIndTotal.idClaseIndicador = 1;
                objIndTotal.idTpoUndMedida = 2;
                objIndTotal.idUndMedida = 6;
                objIndTotal.idUndAnalisis = 4;
                objIndTotal.idEnfoque = idEnfoque;
                objIndTotal.enfoque = enfoque;
                objIndTotal.idDimension = idDimension;
                objIndTotal.idCategoria = 1;

                var dtaIndEIgualdad = objGestionIndicador.addIndEIgualdad( objIndMasculino, objIndFemenino, objIndTotal );

                //  Agrego la fila creada a la tabla
                jQuery('#lstEnfIgu > tbody:last').append( objGestionIndicador.addFilaIndEIgualdad( enfoque, dimension, dtaIndEIgualdad, 0 ) );
            }else{
                jAlert( 'Indicador ya Registrado', 'SIITA - ECORAE' );
            }
        }else{
            //  Actualizo contenido
            objGestionIndicador.lstEnfIgualdad[banIdEI].eiMasculino.umbral = jQuery('#jform_intEIMasculino').val();
            objGestionIndicador.lstEnfIgualdad[banIdEI].eiFemenino.umbral = jQuery('#jform_intEIFemenino').val();
            objGestionIndicador.lstEnfIgualdad[banIdEI].eiTotal.umbral = jQuery('#jform_intEITotal').val();

            //  Actualizo contenido Fila
            updInfoFilaEIgualdad( banIdEI, objGestionIndicador.addFilaIndEIgualdad( enfoque, dimension, objGestionIndicador.lstEnfIgualdad[banIdEI], 1 ) );
            
            //  EnCero la bandera
            banIdEI = -1;
        }
    })


    jQuery( '.updEI' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        var dtaEIgualdad = objGestionIndicador.lstEnfIgualdad[idFila];
        banIdEI = idFila;
        
        //  Recorro el combo de tipos de Enfoque de Igualdad hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbEnfoqueIgualdad option' ) , dtaEIgualdad.eiMasculino.idEnfoque );
        
        //  Ejecuto un desencadenante
        jQuery( '#jform_cbEnfoqueIgualdad' ).trigger( 'change', [dtaEIgualdad.eiMasculino.idEnfoque, dtaEIgualdad.eiMasculino.idDimension] );
        
        //  Actualizo informacion de formulario Enfoque de Igualdad
        jQuery('#jform_intEIMasculino').attr( 'value', dtaEIgualdad.eiMasculino.umbral );
        jQuery('#jform_intEIFemenino').attr( 'value', dtaEIgualdad.eiFemenino.umbral );
        jQuery('#jform_intEITotal').attr( 'value', dtaEIgualdad.eiTotal.umbral );
    })



    //
    //  Actualizo informacion de una determinada fila de la tabla GAP
    //
    function updInfoFilaEIgualdad( idFila, dataUpd )
    {
        jQuery( '#lstEnfIgu tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).html( dataUpd );
            }
        })
    }

    //
    //  Gestiono la eliminacion de un registro
    //
    jQuery( '.delEI' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Indicador", "SIITA - ECORAE", function( result ){
            if( result ) {
                //  Elimino Registro de la lista GAP
                objGestionIndicador.delEIgualdad( idFila );
                
                //  Elimino Fila de la Tabla de Indicadores GAP
                delFilaTbEIgualdad( idFila );
            } 
        });
    })
    
    
    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaTbEIgualdad( idGap )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstEnfIgu tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idGap ){
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

