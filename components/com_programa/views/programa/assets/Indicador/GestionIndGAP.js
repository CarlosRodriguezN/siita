jQuery(document).ready(function() {

    var banIdGAP = -1;

    //
    //  GRUPO DE ATENCION PRIORITARIA
    //

    //  Gestiono el registro de un nuevo indicador GAP
    jQuery('#btnAddGAP').live('click', function() {
        var tpoGap = jQuery('#jform_cbGpoAtencionPrioritario :selected').text();
        var idTpoGap = jQuery('#jform_cbGpoAtencionPrioritario').val();
        var objDimension;

        if( banIdGAP == -1 ){
            if( !objGestionIndicador.existeIndGap( idTpoGap ) ) {
                
                objDimension = new Dimension();
                objDimension.idEnfoque  = 7;
                objDimension.idDimension= idTpoGap;
                
                var objIndMasculino = new Indicador( 0, 0, tpoGap, 'bh', jQuery('#jform_intGAPMasculino').val() );
                objIndMasculino.idClaseIndicador= 1;
                objIndMasculino.idTpoUndMedida  = 2;
                objIndMasculino.idUndMedida     = 6;
                objIndMasculino.idUndAnalisis   = 6;
                objIndMasculino.idCategoria     = 1;
                objIndMasculino.lstDimensiones.push( objDimension );
                
                var objIndFemenino = new Indicador( 0, 0, tpoGap, 'bm', jQuery('#jform_intGAPFemenino').val() );
                objIndFemenino.idClaseIndicador = 1;
                objIndFemenino.idTpoUndMedida   = 2;
                objIndFemenino.idUndMedida      = 6;
                objIndFemenino.idUndAnalisis    = 7;
                objIndFemenino.idCategoria      = 1;
                objIndFemenino.lstDimensiones.push( objDimension );
                
                var objIndTotal = new Indicador( 0, 0, tpoGap, 'b',jQuery('#jform_intGAPTotal').val() );
                objIndTotal.idClaseIndicador= 1;
                objIndTotal.idTpoUndMedida  = 2;
                objIndTotal.idUndMedida     = 6;
                objIndTotal.idUndAnalisis   = 4;
                objIndTotal.idCategoria     = 1;
                objIndTotal.lstDimensiones.push( objDimension );

                var dtaIndGap = objGestionIndicador.addIndGAP( objIndMasculino, objIndFemenino, objIndTotal);

                //  Agrego la fila creada a la tabla
                jQuery('#tbLstGAP > tbody:last').append( objGestionIndicador.addFilaIndicadorGAP( dtaIndGap, 0 ) );
            }else{
                jAlert( 'Indicador ya Registrado', 'SIITA - ECORAE' );
            }
        }else{
            //  Actualizo contenido
            objGestionIndicador.lstGAP[banIdGAP].idTpoGap = jQuery('#jform_cbGpoAtencionPrioritario').val();
            objGestionIndicador.lstGAP[banIdGAP].gapMasculino.umbral = jQuery('#jform_intGAPMasculino').val();
            objGestionIndicador.lstGAP[banIdGAP].gapFemenino.umbral = jQuery('#jform_intGAPFemenino').val();
            objGestionIndicador.lstGAP[banIdGAP].gapTotal.umbral = jQuery('#jform_intGAPTotal').val();

            //  Actualizo contenido Fila
            updInfoFilaGap( banIdGAP, objGestionIndicador.addFilaIndicadorGAP( objGestionIndicador.lstGAP[banIdGAP], 1 ) );
            
            //  EnCero la bandera
            banIdGAP = -1;
        }
        
        //  Limpio Formulario GAP
        limpiarFrmGap();
    })
    
    jQuery( '.updGAP' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        var dtaGAP = objGestionIndicador.lstGAP[idFila];
        banIdGAP = idFila;
        
        //  Recorro el combo hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbGpoAtencionPrioritario option' ) , dtaGAP.gapMasculino.idDimension );
        
        //  Actualizo informacion de formulario GAP 
        jQuery('#jform_intGAPMasculino').attr( 'value', dtaGAP.gapMasculino.umbral );
        jQuery('#jform_intGAPFemenino').attr( 'value', dtaGAP.gapFemenino.umbral );
        jQuery('#jform_intGAPTotal').attr( 'value', dtaGAP.gapTotal.umbral );
    })
    
    //
    //  Actualizo informacion de una determinada fila de la tabla GAP
    //
    function updInfoFilaGap( idFila, dataUpd )
    {
        jQuery( '#tbLstGAP tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).html( dataUpd );
            }
        })
    }
    
    //
    //  Gestiono la eliminacion de un registro
    //
    jQuery( '.delGAP' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este GAP", "SIITA - ECORAE", function( result ){
            if( result ) {
                //  Elimino Registro de la lista GAP
                objGestionIndicador.delGap( idFila );
                
                //  Elimino Fila de la Tabla de Indicadores GAP
                delFilaTbGAP( idFila );
            } 
        });
    })
    
    
    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaTbGAP( idGap )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstGAP tr' ).each( function(){
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
    
    
    function limpiarFrmGap()
    {
        recorrerCombo( jQuery( '#jform_cbGpoAtencionPrioritario option' ) , 0 );
        jQuery( '#jform_intGAPMasculino' ).attr( 'value', '' );
        jQuery( '#jform_intGAPFemenino' ).attr( 'value', '' );
        jQuery( '#jform_intGAPTotal' ).attr( 'value', '' );
    }
    
})