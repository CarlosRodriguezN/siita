jQuery( 'document' ).ready( function(){
    
    var banIdEI = -1;

    jQuery( '#jform_intEIMasculino, #jform_intEIFemenino' ).on( 'change', function(){

        var v1 = jQuery( '#jform_intEIMasculino' ).val();
        var v2 = jQuery( '#jform_intEIFemenino' ).val();
        
        var valor1 = ( v1 !== "" && isNaN( v1 ) === false ) ? parseInt( v1 ) 
                                                            : 0;
                                                                        
        var valor2 = ( v2 !== "" && isNaN( v2 ) === false ) ? parseInt( v2 )
                                                            : 0;

        var rst = valor1 + valor2;

        jQuery( '#jform_intEITotal').attr( 'value', rst );
    })

    //
    //  Gestiona Enfoque de Igualdad
    //
    jQuery('#jform_cbEnfoqueIgualdad').change( function( event, idTpoEnfoque, idEnfoque ) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_idEnfoqueIgualdad').html('<option value="0">CARGANDO...</option>');

        var dataIdTpoEnf = (typeof(idTpoEnfoque) != "undefined") ? idTpoEnfoque
                : jQuery('#jform_cbEnfoqueIgualdad').val();

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: { option          : 'com_indicadores',
                    view            : 'indicador',
                    tmpl            : 'component',
                    format          : 'json',
                    action          : 'getTiposEnfoqueIgualdad',
                    idTipoEnfoque   : dataIdTpoEnf
            },
            error: function(jqXHR, status, error) {
                alert('Proyectos - Gestion Enfoque Igualdad: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">SELECCIONE ENFOQUE DE IGUALDAD</option>');
                for (x = 0; x < numRegistros; x++) {
                    var selected = (dataInfo[x].id == idEnfoque) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_idEnfoqueIgualdad').html(items.join(''));
        });
    });
    
    //  Gestiono el registro de Indicador de Igualdad
    jQuery( '#btnAddEI' ).live( 'click', function(){
        
        var idEnfoque = jQuery( '#jform_cbEnfoqueIgualdad' ).val();
        var enfoque = jQuery( '#jform_cbEnfoqueIgualdad :selected' ).text();

        var idDimension = jQuery( '#jform_idEnfoqueIgualdad' ).val();
        var dimension = jQuery( '#jform_idEnfoqueIgualdad :selected' ).text();
        
        var objDimension;
        
        if( validarFormularioEI() ){
            if( banIdEI == -1 ){
                if( !objGestionIndicador.existeIndEIgualdad( idDimension ) ) {
                    objDimension = new Dimension();
                    objDimension.idEnfoque  = idEnfoque;
                    objDimension.idDimension= idDimension;

                    var objIndMasculino = new Indicador( 0, 0, dimension,'bh', jQuery('#jform_intEIMasculino').val() );
                    objIndMasculino.idClaseIndicador= 1;
                    objIndMasculino.idTpoUndMedida  = 2;
                    objIndMasculino.idUndMedida     = 6;
                    objIndMasculino.idUndAnalisis   = 6;
                    objIndMasculino.idEnfoque       = idEnfoque;
                    objIndMasculino.enfoque         = enfoque;
                    objIndMasculino.idCategoria     = 1;
                    objIndMasculino.idTpoIndicador  = 3;
                    
                    objIndMasculino.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndMasculino.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndMasculino.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                    
                    objIndMasculino.lstDimensiones.push( objDimension );


                    var objIndFemenino = new Indicador( 0, 0, dimension, 'bm', jQuery('#jform_intEIFemenino').val(), '' );
                    objIndFemenino.idClaseIndicador = 1;
                    objIndFemenino.idTpoUndMedida   = 2;
                    objIndFemenino.idUndMedida      = 6;
                    objIndFemenino.idUndAnalisis    = 7;
                    objIndFemenino.idEnfoque        = idEnfoque;
                    objIndFemenino.enfoque          = enfoque;
                    objIndFemenino.idCategoria      = 1;
                    objIndFemenino.idTpoIndicador   = 3;
                    
                    objIndFemenino.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndFemenino.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndFemenino.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                    
                    objIndFemenino.lstDimensiones.push( objDimension );

                    var objIndTotal = new Indicador( 0, 0, dimension, 'b', jQuery('#jform_intEITotal').val(), '' );
                    objIndTotal.idClaseIndicador= 1;
                    objIndTotal.idTpoUndMedida  = 2;
                    objIndTotal.idUndMedida     = 6;
                    objIndTotal.idUndAnalisis   = 4;
                    objIndTotal.idEnfoque       = idEnfoque;
                    objIndTotal.enfoque         = enfoque;
                    objIndTotal.idCategoria     = 1;
                    objIndTotal.idTpoIndicador  = 3;
                    
                    objIndTotal.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndTotal.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndTotal.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                    
                    objIndTotal.lstDimensiones.push( objDimension );

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

            //  limpio el formulario
            limpiarFrmEI();
        }
    })
    
    
    function validarFormularioEI()
    {
        var ban = false;
        
        var idTpoEI     = jQuery( '#jform_cbEnfoqueIgualdad' );
        var idEI        = jQuery( '#jform_idEnfoqueIgualdad' );
        var masculino   = jQuery( '#jform_intEIMasculino' );
        var femenino    = jQuery( '#jform_intEIFemenino' );
        var total       = jQuery( '#jform_intEITotal' );
        
        if( jQuery.isNumeric( idTpoEI.val() ) && parseInt( idTpoEI.val() ) > 0 ){

            if( jQuery.isNumeric( idEI.val() ) && parseInt( idEI.val() ) > 0 ){
                if( masculino.val() !== "" && femenino.val() !== "" && total.val() !== "" ){
                    ban = true;
                }else{
                    validarElemento( masculino );
                    validarElemento( femenino );
                    validarElemento( total );
                    
                    jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
                }
            }else{
                validarElemento( idEI );
                jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
            }
        }else{
            validarElemento( idTpoEI );
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
    
    


    jQuery( '.updEI' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        var dtaEIgualdad = objGestionIndicador.lstEnfIgualdad[idFila];
        banIdEI = idFila;
        
        var idEnfoque = dtaEIgualdad.eiMasculino.idEnfoque;
        var idDimension = dtaEIgualdad.eiMasculino.lstDimensiones[0].idDimension;
        
        //  Recorro el combo de tipos de Enfoque de Igualdad hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbEnfoqueIgualdad option' ) , idEnfoque );
        
        //  Ejecuto un desencadenante
        jQuery( '#jform_cbEnfoqueIgualdad' ).trigger( 'change', [idEnfoque, idDimension] );
        
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
    
    
    jQuery( '#btnLimpiarEI' ).live( 'click', function(){
        limpiarFrmEI();
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
    
    /**
     *  Resetea los valores de un combo determinado
     * @param {object} combo
     * @returns {undefined}
     */
    function enCerarComboEI(combo){
        //  Recorro contenido del combo
        jQuery(combo).each(function() {
            if (jQuery(this).val() > 0) {
                //  Actualizo contenido del combo
                jQuery(this).remove();
            }
        });
    }
    
    
    function limpiarFrmEI()
    {
        banIdEI = -1;
        
        //  Recorro el combo de tipos de Enfoque de Igualdad hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbEnfoqueIgualdad option' ) , 0 );
        
        //  Vacio Informacion del ComboBox Enfoque de Igualdad
        enCerarComboEI( jQuery( '#jform_idEnfoqueIgualdad option' ) );
        
        //  Vacio valor
        jQuery( '#jform_intEIMasculino, #jform_intEIFemenino, #jform_intEITotal' ).attr( 'value', '' );
    }
    
})

