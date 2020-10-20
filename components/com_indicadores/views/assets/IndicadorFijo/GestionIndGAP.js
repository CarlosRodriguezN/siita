jQuery(document).ready(function() {

    var banIdGAP = -1;
    
    
    jQuery( '#jform_intGAPMasculino, #jform_intGAPFemenino' ).on( 'change', function(){

        var v1 = jQuery( '#jform_intGAPMasculino' ).val();
        var v2 = jQuery( '#jform_intGAPFemenino' ).val();
        
        var valor1 = ( v1 !== "" && isNaN( v1 ) === false ) ? parseInt( v1 ) 
                                                            : 0;
                                                                        
        var valor2 = ( v2 !== "" && isNaN( v2 ) === false ) ? parseInt( v2 )
                                                            : 0;

        var rst = valor1 + valor2;

        jQuery( '#jform_intGAPTotal').attr( 'value', rst );
    })

    //
    //  GRUPO DE ATENCION PRIORITARIA
    //

    //  Gestiono el registro de un nuevo indicador GAP
    jQuery('#btnAddGAP').live('click', function() {
        var tpoGap  = jQuery('#jform_cbGpoAtencionPrioritario :selected').text();
        var idTpoGap= jQuery('#jform_cbGpoAtencionPrioritario').val();
        var objDimension;

        if( validarFormularioGAP() ){
            if( banIdGAP === -1  ){
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
                    objIndMasculino.idTpoIndicador  = 3;
                    
                    objIndMasculino.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndMasculino.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndMasculino.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                    
                    objIndMasculino.lstDimensiones.push( objDimension );

                    var objIndFemenino = new Indicador( 0, 0, tpoGap, 'bm', jQuery('#jform_intGAPFemenino').val() );
                    objIndFemenino.idClaseIndicador = 1;
                    objIndFemenino.idTpoUndMedida   = 2;
                    objIndFemenino.idUndMedida      = 6;
                    objIndFemenino.idUndAnalisis    = 7;
                    objIndFemenino.idCategoria      = 1;
                    objIndFemenino.idTpoIndicador   = 3;
                    
                    objIndFemenino.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndFemenino.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndFemenino.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                    
                    objIndFemenino.lstDimensiones.push( objDimension );

                    var objIndTotal = new Indicador( 0, 0, tpoGap, 'b',jQuery('#jform_intGAPTotal').val() );
                    objIndTotal.idClaseIndicador= 1;
                    objIndTotal.idTpoUndMedida  = 2;
                    objIndTotal.idUndMedida     = 6;
                    objIndTotal.idUndAnalisis   = 4;
                    objIndTotal.idCategoria     = 1;
                    objIndTotal.idTpoIndicador  = 3;
                    
                    objIndTotal.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndTotal.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndTotal.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                    
                    objIndTotal.lstDimensiones.push( objDimension );

                    var dtaIndGap = objGestionIndicador.addIndGAP( objIndMasculino, objIndFemenino, objIndTotal);

                    //  Agrego la fila creada a la tabla
                    jQuery('#tbLstGAP > tbody:last').append( objGestionIndicador.addFilaIndicadorGAP( dtaIndGap, 0 ) );
                }else{
                    jAlert( 'Indicador ya Registrado', 'SIITA - ECORAE' );
                }
            }else{
                //  Actualizo contenido
                objGestionIndicador.lstGAP[banIdGAP].idTpoGap           = jQuery('#jform_cbGpoAtencionPrioritario').val();
                objGestionIndicador.lstGAP[banIdGAP].gapMasculino.umbral= jQuery('#jform_intGAPMasculino').val();
                objGestionIndicador.lstGAP[banIdGAP].gapFemenino.umbral = jQuery('#jform_intGAPFemenino').val();
                objGestionIndicador.lstGAP[banIdGAP].gapTotal.umbral    = jQuery('#jform_intGAPTotal').val();

                //  Actualizo contenido Fila
                updInfoFilaGap( banIdGAP, objGestionIndicador.addFilaIndicadorGAP( objGestionIndicador.lstGAP[banIdGAP], 1 ) );

                //  EnCero la bandera
                banIdGAP = -1;
            }

            //  Limpio Formulario GAP
            limpiarFrmGap();
        }

    })
    
    
    
    function validarFormularioGAP()
    {
        var ban = false;
        
        var idGAP       = jQuery( '#jform_cbGpoAtencionPrioritario' );
        var masculino   = jQuery( '#jform_intGAPMasculino' );
        var femenino    = jQuery( '#jform_intGAPFemenino' );
        var total       = jQuery( '#jform_intGAPTotal' );
        
        if( jQuery.isNumeric( idGAP.val() ) && parseInt( idGAP.val() ) > 0 ){
            if( masculino.val() !== "" && femenino.val() !== "" && total.val() !== "" ){
                ban = true;
            }else{
                validarElemento( masculino );
                validarElemento( femenino );
                validarElemento( total );

                jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
            }
        }else{
            validarElemento( idGAP );
            jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
        }

        return ban;
    }
    
    
    function validarElemento( obj )
    {
        var ban = 1;
        
        if( obj.val() === "" || obj.val() === "0" ){
            ban = 0;
            obj.attr( 'class', 'required invalid' );
            
            var lbl = obj.selector + '-lbl';
            jQuery( lbl ).attr( 'class', 'hasTip required invalid' );
            jQuery( lbl ).attr( 'aria-invalid', 'true' );
        }
        
        return ban;
    }
    
    
    
    jQuery( '.updGAP' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        var dtaGAP = objGestionIndicador.lstGAP[idFila];
        banIdGAP = idFila;
        
        //  Recorro el combo hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbGpoAtencionPrioritario option' ) , dtaGAP.gapMasculino.lstDimensiones[0].idDimension );
        
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
    
    
    jQuery( '#btnLimpiarGAP' ).live( 'click', function(){
        limpiarFrmGap();
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
        banIdGAP = -1;

        recorrerCombo( jQuery( '#jform_cbGpoAtencionPrioritario option' ) , 0 );
        jQuery( '#jform_intGAPMasculino' ).attr( 'value', '' );
        jQuery( '#jform_intGAPFemenino' ).attr( 'value', '' );
        jQuery( '#jform_intGAPTotal' ).attr( 'value', '' );
    }
    
})