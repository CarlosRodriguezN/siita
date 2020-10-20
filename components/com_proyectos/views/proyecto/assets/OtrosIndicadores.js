
///////////////////////////////////////
//  OTROS INDICADORES - OTROS INDICADORES
///////////////////////////////////////

jQuery( document ).ready( function(){
    //  Numero de Otros Indicadores
    var numOtroInd = 0;
    
    //  Bandera de Actualizacion de un determinado Indicador
    var banOtroIndicador = 0;
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    
    //
    //  Agregar Indicadores
    //
    jQuery( '#btnAddIndicador' ).click(function(){
        
        var newInd = [];

        newInd["nombreInd"]     = jQuery( '#jform_strNombreIndicador' ).val();
        newInd["descripcionInd"]= jQuery( '#jform_strDescripcionIndicador' ).val();
        newInd["uaInd"]         = jQuery( '#jform_idUndAnalisisNewInd' ).val();
        newInd["infoUAInd"]     = jQuery( '#jform_idUndAnalisisNewInd :selected' ).text();
        newInd["tpoUM"]         = jQuery( '#jform_idTpoUndMedida' ).val();
        newInd["umInd"]         = jQuery( '#jform_idUndMedidaMetaNewInd' ).val();
        newInd["infoUMInd"]     = jQuery( '#jform_idUndMedidaMetaNewInd :selected' ).text();
        newInd["valorInd"]      = jQuery( '#jform_valorMetaNewInd' ).val();
        newInd["formulaInd"]    = jQuery( '#jform_formulaMetaNewInd' ).val();
        newInd["idLineaBase"]   = getLineaBase();
        newInd["published"]     = '1';

        if( banOtroIndicador == 0 ){
            numOtroInd = numOtroInd + 1;
            newInd["idOtroInd"] = numOtroInd;
            
            //  Agrego un Nuevo Indicador
            addOtrosIndicadores( newInd );
            
            //  Limpio formulario de registro de nuevo indicador
            limpiarFrmOtroIndicador();
        }else if( banOtroIndicador == 1 ) {
            //  Asigno identificador que va a actualizar informacion
            newInd["idOtroInd"] = jQuery( '#jform_idOtrosInd' ).val();
            
            //  Actualizo informacion de un indicador
            updOtroIndicador( newInd );
        }
    })

    //
    //  Retorno la linea base
    //
    function getLineaBase()
    {
        //  Lista de Lineas Base
        var idLineaBase = ( lstLineasBaseTmp.lenght )   ? lstLineasBaseTmp[0].idLineaBase 
                                                        : false;
        
        //  Reinicio la lista temporal de lineas base
        lstLineasBaseTmp = new Array();
        
        return idLineaBase;
    }

    //
    //  Gestiono el Agregar Otros Indicadores
    //
    function addOtrosIndicadores( dataOtrosInd )
    {
        //  Construyo la Fila
        var fila = "<tr id='"+ dataOtrosInd["idOtroInd"] +"'>"
                    + " <td align='center'>"+ dataOtrosInd["nombreInd"] +"</td>"
                    + " <td align='center'>"+ dataOtrosInd["descripcionInd"] +"</td>"
                    + " <td align='center'>"+ dataOtrosInd["infoUAInd"] +"</td>"
                    + " <td align='center'>"+ dataOtrosInd["valorInd"] + ' / ' + dataOtrosInd["infoUMInd"] +"</td>"
                    + " <td align='center'>"+ dataOtrosInd["formulaInd"] +"</td>";

        if( roles["core.create"] === true || roles["core.edit"] === true ){
            fila+= " <td align='center'> <a class='updOtrosInd'> Editar </a> </td>"
                + " <td align='center'> <a class='delOtrosInd'> Eliminar </a> </td>"
        }else{
            fila+= " <td align='center'> Editar </td>"
                + " <td align='center'>  Eliminar </td>"
        }
        
        fila += "  </tr>";

        //  Agrego a Enfoque de igualdad
        lstNewInd.push( dataOtrosInd );

        //  Agrego la fila creada a la tabla
        jQuery( '#lstOtrosInd > tbody:last' ).append( fila );
    }

    //
    //  Actualizo un determinado aporte de financiamiento
    //
    jQuery( '.updOtrosInd' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Retorno informacion de un determinado Indicador
        var dataFila = getDataOtroInd( idFila );
        
        if( dataFila ){
            //  Agrego indentificador del indicador
            jQuery( '#jform_idOtrosInd' ).attr( 'value', dataFila["idOtroInd"] );
            
            //  Recorro el combo "Unidad de Analisis" hasta una determinada posicion
            recorrerCombo( jQuery( '#jform_idUndAnalisisNewInd option' ) , dataFila["uaInd"] );
            
            //  Recorro el combo "Unidad de Medida" hasta una determinada posicion
            recorrerCombo( jQuery( '#jform_idTpoUndMedida option' ) , dataFila["tpoUM"] );
            
            //  Actualizo contenido del combo de Unidades de medida
            jQuery( '#jform_idTpoUndMedida' ).trigger( 'change', [ dataFila["tpoUM"], dataFila["umInd"] ] );

            //  Actualizo el Nombre del Indicador
            jQuery( '#jform_strNombreIndicador' ).attr( 'value', dataFila["nombreInd"] );
            
            //  Cambio Estado de campo Nombre ya que la edicion del Indicador se hace a nivel de valores
            jQuery( '#jform_strNombreIndicador' ).attr( 'readonly', 'readonly' );
        
            //  Cambio etiqueta del boton indicando que el boton se va a actualizar
            jQuery( '#btnAddIndicador' ).attr( 'value', 'Editar Indicador' );

            //  Actualizo descripcion del Indicador
            jQuery( '#jform_strDescripcionIndicador' ).attr( 'value', dataFila["descripcionInd"] );
            
            //  Actualizo Valor de Indicador a Cero el Porcentaje de aporte
            jQuery( '#jform_valorMetaNewInd' ).attr( 'value', dataFila["valorInd"] );
            
            //  Actualizo Formula de Indicador asociada al indicador
            jQuery( '#jform_formulaMetaNewInd' ).attr( 'value', dataFila["formulaInd"] );
        }
    })

    //  Retorno Fila con informacion de un determinado Indicador
    function getDataOtroInd( idFila )
    {
        var numReg = lstNewInd.length;
        for( x = 0; x < numReg; x++ ){
            if( lstNewInd[x]["idOtroInd"] == idFila ){
                //  Cambio estado de bandera de actualiacion de un determinado indicador
                banOtroIndicador = 1;
                return lstNewInd[x];
            }
        }
        
        return false;
    }

    //
    //  Actualizo informacion de un determinado Indicador
    //
    function updOtroIndicador( newInd )
    {
        var numReg = lstNewInd.length;
        for( x = 0; x < numReg; x++ ){
            if( lstNewInd[x]["idOtroInd"] == newInd["idOtroInd"] ){

                lstNewInd[x]["tipoGap"] = newInd["tipoGap"];
                lstNewInd[x]["infoTipoGap"] = newInd["infoTipoGap"];
                lstNewInd[x]["descripcionInd"] = newInd["descripcionInd"];
                lstNewInd[x]["uaInd"] = newInd["uaInd"];
                lstNewInd[x]["infoUAInd"] = newInd["infoUAInd"];
                lstNewInd[x]["umInd"] = newInd["umInd"];
                lstNewInd[x]["infoUMInd"] = newInd["infoUMInd"];
                lstNewInd[x]["valorInd"] = newInd["valorInd"];
                lstNewInd[x]["formulaInd"] = newInd["formulaInd"];
                
                //  Encero bandera que muestra si el registro esta siendo actualizado
                banOtroIndicador = 0;
                
                //  Actualizo informacion de fila a ser actualizada
                updInfoFilaOtrosIndicadores( newInd );
                
                //  Limpio informacion del formulario
                limpiarFrmOtroIndicador();
            }
        }
    }

    //
    //  Actualizo informacion en a fila actualizada
    //
    function updInfoFilaOtrosIndicadores( dataUpd )
    {
        jQuery( '#lstOtrosInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == dataUpd["idOtroInd"] ){
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
                var fila = "<td align='center'>"+ dataUpd["nombreInd"] +"</td>"
                        + " <td align='center'>"+ dataUpd["descripcionInd"] +"</td>"
                        + " <td align='center'>"+ dataUpd["infoUAInd"] +"</td>"
                        + " <td align='center'>"+ dataUpd["valorInd"] + ' / ' + dataUpd["infoUMInd"] +"</td>"
                        + " <td align='center'>"+ dataUpd["formulaInd"] +"</td>";

                if( roles["core.create"] === true || roles["core.edit"] === true ){
                    fila+= "<td align='center'> <a class='updOtrosInd'> Editar </a> </td>"
                        + " <td align='center'> <a class='delOtrosInd'> Eliminar </a> </td>";
                }else{
                    fila+= "<td align='center'> Editar </td>"
                        + " <td align='center'> Eliminar </td>";
                }

                jQuery( this ).html( fila );
            }
        })
    }

    //
    //  Limpio los campos del formulario de registro de otros indicadores
    //
    jQuery( '#btnLimpiarOtrosIndicadores' ).click(function(){
        limpiarFrmOtroIndicador();
    })

    //
    // Limpia y encera informacion registrada en el formulario de registro de otros Indicadores
    //
    function limpiarFrmOtroIndicador()
    {
        //  Recorro el combo "Unidad de Analisis" hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_idUndAnalisisNewInd option' ) , 0 );

        //  Recorro el combo "Unidad de Medida" hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_idUndMedidaMetaNewInd option' ) , 0 );

        //  Actualizo el Nombre del Indicador
        jQuery( '#jform_strNombreIndicador' ).attr( 'value', '' );
        
        //  Retorno estado de lectura y escritura al campo Nombre del indicador
        jQuery( '#jform_strNombreIndicador' ).removeAttr( 'readonly' );
        
        //  Cambio etiqueta del boton indicando que el boton se va a actualizar
        jQuery( '#btnAddIndicador' ).attr( 'value', 'Agregar Indicador' );

        //  Actualizo descripcion del Indicador
        jQuery( '#jform_strDescripcionIndicador' ).attr( 'value', '' );

        //  Actualizo Valor de Indicador a Cero el Porcentaje de aporte
        jQuery( '#jform_valorMetaNewInd' ).attr( 'value', '' );        
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
    
    //
    //  Elimino un determinado aporte de financiamiento
    //
    jQuery( '.delOtrosInd' ).live( 'click', function(){
        var delFila = jQuery( this ).parent().parent();
        var idFila = delFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Indicador", "SIITA - ECORAE", function( result ){
            if( result && delOtroIndicador( idFila ) ) {
                limpiarFrmOtroIndicador();
                delFilaOtroIndicador( idFila );
                delOtroIndicador( idFila );
            }else{
                jAlert( "Registro no Existente", "SIITA - ECORAE" );
            } 
        });
    })
    
    //
    //  Eliminado logico de un registro GAP, se lo realiza 
    //  cambiando en estatus "published" del indicador a cero "0"
    //
    function delOtroIndicador( idOtroInd )
    {
        var numReg = lstNewInd.length;
        for( x = 0; x < numReg; x++ ){
            if( lstNewInd[x]["idOtroInd"] == idOtroInd ){
                lstNewInd[x]["published"] = 0;
                return true;
            }
        }
        
        return false;
    }
    
    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaOtroIndicador( idOtroInd )
    {
        jQuery( '#lstOtrosInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idOtroInd ){
                jQuery( this ).remove();
            }
        })
    }
})