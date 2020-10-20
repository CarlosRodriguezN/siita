jQuery('document').ready(function() {
    var banIdEE = -1;

    jQuery( '#jform_intEnfEcoMasculino, #jform_intEnfEcoFemenino' ).on( 'change', function(){

        var v1 = jQuery( '#jform_intEnfEcoMasculino' ).val();
        var v2 = jQuery( '#jform_intEnfEcoFemenino' ).val();
        
        var valor1 = ( v1 !== "" && isNaN( v1 ) === false ) ? parseInt( v1 ) 
                                                            : 0;
                                                                        
        var valor2 = ( v2 !== "" && isNaN( v2 ) === false ) ? parseInt( v2 )
                                                            : 0;

        var rst = valor1 + valor2;

        jQuery( '#jform_intEnfEcoTotal').attr( 'value', rst );
    })


    jQuery('#btnAddEnfEco').live('click', function() {
        var idEEcorae = jQuery('#jform_cbEnfoqueEcorae').val();
        var EEcorae = jQuery('#jform_cbEnfoqueEcorae :selected').text();
        var objDimension;


        if (validarFormularioEE()) {
            if (banIdEE == -1) {
                objDimension = new Dimension();
                objDimension.idDimension = idEEcorae;

                var objIndMasculino = new Indicador(0, 0, EEcorae, 'bh', jQuery('#jform_intEnfEcoMasculino').val());
                objIndMasculino.idClaseIndicador= 1;
                objIndMasculino.idTpoUndMedida  = 2;
                objIndMasculino.idUndMedida     = 6;
                objIndMasculino.idUndAnalisis   = 6;
                objIndMasculino.idCategoria     = 1;
                objIndMasculino.idTpoIndicador  = 3;
                
                objIndMasculino.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                objIndMasculino.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                objIndMasculino.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                
                objIndMasculino.lstDimensiones.push(objDimension);

                var objIndFemenino = new Indicador(0, 0, EEcorae, 'bm', jQuery('#jform_intEnfEcoFemenino').val());
                objIndFemenino.idClaseIndicador = 1;
                objIndFemenino.idTpoUndMedida   = 2;
                objIndFemenino.idUndMedida      = 6;
                objIndFemenino.idUndAnalisis    = 7;
                objIndFemenino.idCategoria      = 1;
                objIndFemenino.idTpoIndicador   = 3;

                objIndFemenino.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                objIndFemenino.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                objIndFemenino.idResponsable   = jQuery( '#jform_idResponsable' ).val();

                objIndFemenino.lstDimensiones.push(objDimension);

                var objIndTotal = new Indicador(0, 0, EEcorae, 'b', jQuery('#jform_intEnfEcoTotal').val());
                objIndTotal.idClaseIndicador= 1;
                objIndTotal.idTpoUndMedida  = 2;
                objIndTotal.idUndMedida     = 6;
                objIndTotal.idUndAnalisis   = 4;
                objIndTotal.idCategoria     = 1;
                objIndTotal.idTpoIndicador  = 3;

                objIndTotal.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                objIndTotal.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                objIndTotal.idResponsable   = jQuery( '#jform_idResponsable' ).val();

                objIndTotal.lstDimensiones.push(objDimension);

                if (!objGestionIndicador.existeIndEEcorae(idEEcorae)) {
                    var dtaIndEEcorae = objGestionIndicador.addIndEEcorae(objIndMasculino, objIndFemenino, objIndTotal);

                    //  Agrego la fila creada a la tabla
                    jQuery('#lstEnfEco > tbody:last').append(objGestionIndicador.addFilaIndEEcorae(EEcorae, dtaIndEEcorae, 0));
                } else {
                    jAlert('Indicador ya Registrado', 'SIITA - ECORAE');
                }
            } else {
                //  Actualizo contenido
                objGestionIndicador.lstEnfEcorae[banIdEE].eeMasculino.umbral = jQuery('#jform_intEnfEcoMasculino').val();
                objGestionIndicador.lstEnfEcorae[banIdEE].eeFemenino.umbral = jQuery('#jform_intEnfEcoFemenino').val();
                objGestionIndicador.lstEnfEcorae[banIdEE].eeTotal.umbral = jQuery('#jform_intEnfEcoTotal').val();

                //  Actualizo contenido Fila
                updInfoFilaEEcorae(banIdEE, objGestionIndicador.addFilaIndEEcorae(EEcorae, objGestionIndicador.lstEnfEcorae[banIdEE], 1));

                //  EnCero la bandera
                banIdEE = -1;
            }

            limpiarFrmEE();
        }

    })


    function validarFormularioEE()
    {
        var ban = false;
        
        var idEE        = jQuery( '#jform_cbEnfoqueEcorae' );
        var masculino   = jQuery( '#jform_intEnfEcoMasculino' );
        var femenino    = jQuery( '#jform_intEnfEcoFemenino' );
        var total       = jQuery( '#jform_intEnfEcoTotal' );
        
        if( jQuery.isNumeric( idEE.val() ) && parseInt( idEE.val() ) > 0 ){
            if( masculino.val() !== "" && femenino.val() !== "" && total.val() !== "" ){
                ban = true;
            }else{
                validarElemento( masculino );
                validarElemento( femenino );
                validarElemento( total );
                
                jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
            }
        }else{
            validarElemento( idEE );
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



    jQuery('.updEE').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        var dtaEE = objGestionIndicador.lstEnfEcorae[idFila];
        var idDimension = dtaEE.eeMasculino.lstDimensiones[0].idDimension;

        banIdEE = idFila;

        //  Recorro el combo hasta una determinada posicion
        recorrerCombo(jQuery('#jform_cbEnfoqueEcorae option'), idDimension);

        //  Actualizo informacion de formulario GAP 
        jQuery('#jform_intEnfEcoMasculino').attr('value', dtaEE.eeMasculino.umbral);
        jQuery('#jform_intEnfEcoFemenino').attr('value', dtaEE.eeFemenino.umbral);
        jQuery('#jform_intEnfEcoTotal').attr('value', dtaEE.eeTotal.umbral);
    })

    //
    //  Gestiono la eliminacion de un registro
    //
    jQuery('.delEE').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("Está seguro de Eliminar este Indicador", "SIITA - ECORAE", function(result) {
            if (result) {
                //  Elimino Registro de la lista GAP
                objGestionIndicador.delIndEEcorae(idFila);

                //  Elimino Fila de la Tabla de Indicadores GAP
                delFilaTbEEcorae(idFila);
            }
        });
    })


    jQuery('#btnLimpiarEnfEco').live('click', function() {
        limpiarFrmEE();
    })

    //
    //  Actualizo informacion de una determinada fila de la tabla GAP
    //
    function updInfoFilaEEcorae(idFila, dataUpd)
    {
        jQuery('#lstEnfEco tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).html(dataUpd);
            }
        })
    }

    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaTbEEcorae(idEE)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstEnfEco tr').each(function() {
            if (jQuery(this).attr('id') == idEE) {
                jQuery(this).remove();
            }
        })
    }

    //
    //  Recorre los comboBox del Formulario a la posicion inicial
    //
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }

    function limpiarFrmEE()
    {
        banIdEE = -1;

        //  Recorro el combo de tipos de Enfoque de Igualdad hasta una determinada posicion
        recorrerCombo(jQuery('#jform_cbEnfoqueEcorae option'), 0);

        //  Vacio valor
        jQuery('#jform_intEnfEcoMasculino, #jform_intEnfEcoFemenino, #jform_intEnfEcoTotal').attr('value', '');
    }

})