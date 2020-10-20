///////////////////////////////////////
//  INDICADORES GRUPO DE ATENCION PRIORITARIA
///////////////////////////////////////

jQuery( document ).ready( function(){
    //  Bandera de Actualizacion de un determinado Beneficiario
    var banUpdGap = 0;
    
    
    //  Verifico la existencia de un GAP
    jQuery( '#jform_cbGpoAtencionPrioritario' ).change( function(){
        var idGap = jQuery( this ).val();
        var numReg = lstGap.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( parseInt( lstGap[x]["idGAP"] ) == parseInt( idGap ) ){
                jQuery( '#btnAddGAP' ).attr( 'disabled', 'disabled' );
                jAlert( 'Grupo de Atencion, Ya Registrado', 'SIITA - ECORAE' );
                return false;
            }else{
                jQuery( '#btnAddGAP' ).removeAttr( 'disabled', 'disabled' );
            }
        }
    })
    
    //
    //  Agregar Beneficiario
    //
    jQuery( '#btnAddGAP' ).click(function(){
        var dataGAP = [];

        dataGAP["idGAP"] = jQuery( '#jform_cbGpoAtencionPrioritario' ).val();
        dataGAP["tipoGap"] = jQuery( '#jform_cbGpoAtencionPrioritario' ).val();
        dataGAP["infoTipoGap"] = jQuery( '#jform_cbGpoAtencionPrioritario :selected' ).text();
        dataGAP["gapMasculino"] = jQuery( '#jform_intGAPMasculino' ).val();
        dataGAP["gapFemenino"] = jQuery( '#jform_intGAPFemenino' ).val();
        dataGAP["gapTotal"] = jQuery( '#jform_intGAPTotal' ).val();
        dataGAP["published"] = '1';

        if( banUpdGap == 0 ){
            //  Agrego el nuevo registro de 
            addBeneficiario( dataGAP )
        }else{
            //  Actualizo informacion de un indicador
            updDataBeneficiario( dataGAP );
            banUpdGap = 0;
        }
    })

    //
    //  Agrego un nuevo beneficiario
    //
    function addBeneficiario( dataBeneficiarios )
    {
        //  Construyo la Fila
        var fila = "<tr id='"+ dataBeneficiarios["idGAP"] +"'>"
                    + " <td align='center'>"+ dataBeneficiarios["infoTipoGap"] +"</td>"
                    + " <td align='center'>"+ dataBeneficiarios["gapMasculino"] +"</td>"
                    + " <td align='center'>"+ dataBeneficiarios["gapFemenino"] +"</td>"
                    + " <td align='center'>"+ dataBeneficiarios["gapTotal"] +"</td>"
                    + " <td align='center'> <a class='updBeneficiario'> Editar </a> </td>"
                    + " <td align='center'> <a class='delBeneficiario'> Eliminar </a> </td>"
                +"  </tr>";

        //  Agrego a Enfoque de igualdad a la lista
        lstGap.push( dataBeneficiarios );
        
        //  Agrego Lineas Base al nuevo indicador 
        addLineasBaseGAP()

        //  Agrego la fila creada a la tabla
        jQuery( '#tbLstGAP > tbody:last' ).append( fila );
        
        //  Limpio informacion del formulario
        limpiarFrmGAP();
    }

    //  Seteo Lineas Base desde la lista temporal de Lineas base a 
    //  lista general de lineas base
    function addLineasBaseGAP()
    {
        var numRegTmp = lstLineasBaseTmp.length;
        
        for( var x = 0; x < numRegTmp; x++ ){
            //  Verifico si el indicador tiene linea base
            if( getTieneLineaBase( lstLineasBaseTmp[x]["idDimension"], lstLineasBaseTmp[x]["idEnfoque"] ) ){
                //  Si, tiene lo reemplazo
                lstLineasBase.splice( x, 1, lstLineasBaseTmp[x] );
            }else{
                //  Si NO, tiene lo ingreso
                lstLineasBase.push( lstLineasBaseTmp[x] );
            }
        }
        
        //  Reinicio la lista temporal de lineas base
        lstLineasBaseTmp = new Array();
    }


    //
    //  Actualizo un determinado aporte de financiamiento
    //
    jQuery( '.updBeneficiario' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );

        //  Obtengo datos GAP a actualizar
        var dataGAP = getDataGAP( idFila );

        if( dataGAP ){
            //  Recorro el combo hasta una determinada posicion
            recorrerCombo( jQuery( '#jform_cbGpoAtencionPrioritario option' ) , dataGAP["idGAP"] );

            //  Agrego atributo del combo GAP de solo lectura
            jQuery( '#jform_cbGpoAtencionPrioritario' ).attr( 'disabled', 'disabled' );

            //  Actualizo a Cero el Porcentaje de aporte
            jQuery( '#jform_intGAPMasculino' ).attr( 'value', dataGAP["gapMasculino"] );

            //  Actualizo a Cero el Porcentaje de aporte
            jQuery( '#jform_intGAPFemenino' ).attr( 'value', dataGAP["gapFemenino"] );

            //  Actualizo a Cero el Porcentaje de aporte
            jQuery( '#jform_intGAPTotal' ).attr( 'value', dataGAP["gapTotal"] );

            //  Actualizo a Cero el Porcentaje de aporte
            jQuery( '#btnAddGAP' ).attr( 'value', 'Editar Grupo de Atencion Prioritario' );
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var mlb = ( getTieneLineaBase( dataGAP["idGAP"], 2 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                    : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var flb = ( getTieneLineaBase( dataGAP["idGAP"], 1 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                    : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var tlb = ( getTieneLineaBase( dataGAP["idGAP"], 6 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                    : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';

            jQuery( '#gapMLB' ).html( mlb );
            jQuery( '#gapFLB' ).html( flb );
            jQuery( '#gapTLB' ).html( tlb );
            
            //  Cambio la Bandera a 1
            banUpdGap = 1;
        }
    })

    //
    //  Verifico si un determinado indicador tiene asignada una linea base
    //
    function getTieneLineaBase( idDimension, idEnfoque )
    {
        var numReg = lstLineasBase.length;
        for( x = 0; x < numReg; x++ ){
            if( lstLineasBase[x]["idDimension"] == idDimension && lstLineasBase[x]["idEnfoque"] == idEnfoque ){
                return true;
            }
        }
        
        return false;
    }


    //
    //  Retorno informacion de un determinado GAP
    //
    function getDataGAP( idFila )
    {
        var numReg = lstGap.length;
        for( x = 0; x < numReg; x++ ){
            if( lstGap[x]["idGAP"] == idFila ){
                banBeneficiario = 1;
                return lstGap[x];
            }
        }
        
        return false;
    }

    //
    //  Actualizo informacion de un determinado Beneficiario
    //
    function updDataBeneficiario( dataBenf )
    {
        var numReg = lstGap.length;
        for( x = 0; x < numReg; x++ ){
            if( lstGap[x]["idGAP"] == dataBenf["idGAP"] ){

                lstGap[x]["tipoGap"] = dataBenf["tipoGap"];
                lstGap[x]["infoTipoGap"] = dataBenf["infoTipoGap"];
                lstGap[x]["gapMasculino"] = dataBenf["gapMasculino"];
                lstGap[x]["gapFemenino"] = dataBenf["gapFemenino"];
                lstGap[x]["gapTotal"] = dataBenf["gapTotal"];
                
                //  Encero bandera que muestra si el registro esta siendo actualizado
                banBeneficiario = 0;
                
                //  Actualizo informacion de fila a ser actualizada
                updInfoFilaBeneficiario( dataBenf );
                
                //  Actualizo informacion de lineaBase
                addLineasBaseGAP();
                
                //  Limpio informacion del formulario
                limpiarFrmGAP();
            }
        }
    }

    //
    //  Actualizo informacion en a fila actualizada
    //
    function updInfoFilaBeneficiario( dataUpd )
    {
        jQuery( '#lstGAP tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == dataUpd["idGAP"] ){
                
                //  Construyo la Fila
                var fila = "    <td align='center'>"+ dataUpd["infoTipoGap"] +"</td>"
                            + " <td align='center'>"+ dataUpd["gapMasculino"] +"</td>"
                            + " <td align='center'>"+ dataUpd["gapFemenino"] +"</td>"
                            + " <td align='center'>"+ dataUpd["gapTotal"] +"</td>"
                            + " <td align='center'> <a class='updBeneficiario'> Editar </a> </td>"
                            + " <td align='center'> <a class='delBeneficiario'> Eliminar </a> </td>";

                jQuery( this ).html( fila );
            }
        })
    }

    //
    //  Elimino un determinado aporte de financiamiento
    //
    jQuery( '.delBeneficiario' ).live( 'click', function(){
        var delFila = jQuery( this ).parent().parent();
        var idFila = delFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este GAP", "SIITA - ECORAE", function( result ){
            if( result && delRegistroGAP( idFila ) ) {
                //  Actualizo a 0 el campo publish de un determinado registro GAP
                if( delRegistroGAP( idFila ) ){
                    //  Elimino una fila de la tabla de la lista GAP
                    delFilaGap( idFila );
                }
            } 
        });
    })
    
    //
    //  Eliminado logico de un registro GAP, se lo realiza 
    //  cambiando en estatus "published" del indicador a cero "0"
    //
    function delRegistroGAP( idGAP )
    {
        var numReg = lstGap.length;
        for( x = 0; x < numReg; x++ ){
            if( lstGap[x]["idGAP"] == idGAP ){
                lstGap[x]["published"] = 0;
                return true;
            }
        }

        return false;
    }
    
    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaGap( idGap )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstGAP tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idGap ){
                jQuery( this ).remove();
            }
        })
    }
    
    //
    //  Limpiar Formulario de Grupo de Atencion Prioritaria
    //
    jQuery( '#btnLimpiarGAP' ).click(function(){
        limpiarFrmGAP();
        banUpdGap = 0;
    })
    
    //
    //  Limpia Grupo de Atencion Prioritaria
    //
    function limpiarFrmGAP()
    {
        //  Recorro el combo hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbGpoAtencionPrioritario option' ) , 0 );

        //  Elimino atributo de solo lectura
        jQuery( '#jform_cbGpoAtencionPrioritario' ).removeAttr( 'disabled' );

        //  Actualizo a Cero GAP - Masculino
        jQuery( '#jform_intGAPMasculino' ).attr( 'value', '' );

        //  Actualizo a Cero GAP - Femenino
        jQuery( '#jform_intGAPFemenino' ).attr( 'value', '' );

        //  Actualizo a Cero GAP - Total
        jQuery( '#jform_intGAPTotal' ).attr( 'value', '' );
        
        jQuery( '#gapMLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        jQuery( '#gapFLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        jQuery( '#gapTLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        
        jQuery( '#gapMUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        jQuery( '#gapFUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        jQuery( '#gapTUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );

        //  Cambio la etiqueta del boton a su valor inicial
        jQuery( '#btnAddGAP' ).attr( 'value', 'Agregar Grupo de Atencion Prioritario' );
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