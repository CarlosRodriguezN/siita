///////////////////////////////////////
//  INDICADORES ENFOQUE ECORAE
///////////////////////////////////////

jQuery( document ).ready( function(){
    
    var banEE = 0;
    var numRegEE = lstEnfEco.length;
    //
    //  Agregar Enfoque Ecorae
    //
    jQuery( '#btnAddEnfEco' ).click(function(){
        var dataEnfEco = [];

        if( banEE == 0 && existeEE( jQuery( '#jform_cbEnfoqueEcorae' ).val() ) == false ){
            dataEnfEco["idEnfEco"]          = jQuery( '#jform_cbEnfoqueEcorae' ).val();
            dataEnfEco["infoEnfEco"]        = jQuery( '#jform_cbEnfoqueEcorae :selected' ).text();
            dataEnfEco["enfEcoMasculino"]   = jQuery( '#jform_intEnfEcoMasculino' ).val();
            dataEnfEco["enfEcoFemenino"]    = jQuery( '#jform_intEnfEcoFemenino' ).val();
            dataEnfEco["enfEcoTotal"]       = jQuery( '#jform_intEnfEcoTotal' ).val();
            dataEnfEco["published"]         = '1';

            //  Agrego una fila con informacion de enfoque de igualdad
            addFilaEE( dataEnfEco );
        }else if( banEE == 1 ){
            //  Actualizo el registro con nueva informacion
            updRegEE();
        }else if( existeEE( jQuery( '#jform_cbEnfoqueEcorae' ).val() ) ){
            jAlert( 'Enfoque de Igualdad Registrado', 'SIITA - ECORAE' )
        }

    })

    //
    //  Verifico la existencia de un determinado Enfoque Igualdad
    //
    function existeEE( idDim )
    {
        var numReg = lstEnfEco.length;
        for( var x = 0; x < numReg; x++ ){
            if( lstEnfEco[x]["idEnfEco"] == idDim ){
                return true;
            }
        }
        
        return false;
    }


    //
    //  Agrego Fila a la tabla Enfoque Ecorae
    //
    function addFilaEE( data )
    {
        //  Construyo la Fila
        var fila = "<tr id='"+ data["idEnfEco"] +"'>"
                + "     <td align='center'>"+ data["infoEnfEco"] +"</td>"
                + "     <td align='center'>"+ data["enfEcoMasculino"] +"</td>"
                + "     <td align='center'>"+ data["enfEcoFemenino"] +"</td>"
                + "     <td align='center'>"+ data["enfEcoTotal"] +"</td>"
                + "     <td align='center'> <a class='updEE'> Editar </a> </td>"
                + "     <td align='center'> <a class='delEE'> Eliminar </a> </td>"
                +"  </tr>";

        //  Agrego la fila creada a la tabla
        jQuery( '#lstEnfEco > tbody:last' ).append( fila );
        
        //  Agrego informacion a la lista de Enfoque Ecorae
        lstEnfEco.push( data );
        
        //  Agrego Lineas Base al Enfoque Ecorae
        addLineasBaseEE()
        
        //  Limpio informacion del formulario Enfoque Ecorae
        limpiarFrmEE();
    }



    //  Seteo Lineas Base desde la lista temporal de Lineas base a 
    //  lista general de lineas base
    function addLineasBaseEE()
    {
        var numRegTmp = lstLineasBaseTmp.length;
        
        for( var x = 0; x < numRegTmp; x++ ){
            //  Verifico si el indicador tiene linea base
            if( getEETieneLineaBase( lstLineasBaseTmp[x]["idDimension"], lstLineasBaseTmp[x]["idEnfoque"] ) ){
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
    //  Gestiono la edicion de un determinado Enfoque de igualdad
    //
    jQuery( '.updEE' ).live( 'click', function(){
        var delFila = jQuery( this ).parent().parent();
        var idFila = delFila.attr( 'id' );
      
        var dataEE = getDataEE( idFila );
        
        if( dataEE ){
            banEE = 1;
            
            //  Recorro el combo de Tipos de Enfoque de igualdad a una determinada posicion
            recorrerCombo( jQuery( '#jform_cbEnfoqueEcorae option' ), dataEE["idEnfEco"] );
            
            //  Deshabilito los combos
            jQuery( '#jform_cbEnfoqueEcorae' ).attr( 'disabled', 'disabled' );
            
            //  Agrego el contenido
            jQuery( '#jform_intEnfEcoMasculino' ).attr( 'value', dataEE["enfEcoMasculino"] );
            jQuery( '#jform_intEnfEcoFemenino' ).attr( 'value', dataEE["enfEcoFemenino"] );
            jQuery( '#jform_intEnfEcoTotal' ).attr( 'value', dataEE["enfEcoTotal"] );
            
            //  Cambio el nombre del boton agregar enfoque
            jQuery( '#btnAddEnfEco' ).attr( 'value', 'Editar Enfoque de Ecorae' );
            
            //  Enfoque que esta siendo actualizado
            jQuery( '#idEnfEc' ).attr( 'value', idFila );
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var mlb = ( getEETieneLineaBase( dataEE["idEnfEco"], 2 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                        : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var flb = ( getEETieneLineaBase( dataEE["idEnfEco"], 1 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                        : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var tlb = ( getEETieneLineaBase( dataEE["idEnfEco"], 6 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                        : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
                                                                    
            jQuery( '#EEMLB' ).html( mlb );
            jQuery( '#EEFLB' ).html( flb );
            jQuery( '#EETLB' ).html( tlb );
        }
    })

    //
    //  Funcion que retorna informacion de un determ¡nado Enfoque Ecorae
    //
    function getDataEE( idReg )
    {
        var numReg = lstEnfEco.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstEnfEco[x]["idEnfEco"] == idReg ){
                return lstEnfEco[x];
            }
        }
        
        return false;
    }


    //
    //  Verifico si un determinado indicador tiene asignada una linea base
    //       
    function getEETieneLineaBase( idDimension, idEnfoque )
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
    //  Actualizo el contenido de Enfoque de Igualdad
    //
    function updRegEE()
    {
        var numReg = lstEnfEco.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstEnfEco[x]["idEnfEco"] == jQuery( '#idEnfEc' ).val() ){
                
                lstEnfEco[x]["enfEcoMasculino"]   = jQuery( '#jform_intEnfEcoMasculino' ).val();
                lstEnfEco[x]["enfEcoFemenino"]    = jQuery( '#jform_intEnfEcoFemenino' ).val();
                lstEnfEco[x]["enfEcoTotal"]       = jQuery( '#jform_intEnfEcoTotal' ).val();
                
                updFilaEE( lstEnfEco[x] );
            }
        }
    }

    //
    //  Actualizo Fila de la Tabla de Enfoque de Igualdad
    //
    function updFilaEE( data )
    {
        jQuery( '#lstEnfEco tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == jQuery( '#idEnfEc' ).val() ){
                
                //  Agrego color a la fila actualizada
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
                var fila =  "   <td align='center'>"+ jQuery( '#jform_cbEnfoqueEcorae :selected' ).text() +"</td>"
                            + " <td align='center'>"+ data["enfEcoMasculino"] +"</td>"
                            + " <td align='center'>"+ data["enfEcoFemenino"] +"</td>"
                            + " <td align='center'>"+ data["enfEcoTotal"] +"</td>"
                            + " <td align='center'> <a class='updEE'> Editar </a> </td>"
                            + " <td align='center'> <a class='delEE'> Eliminar </a> </td>";

                //  actualizo el contenido de la fila
                jQuery( this ).html( fila );
                
                //  Limpio formulario de EI
                limpiarFrmEE();
                
                //  EnCero la bandera que identifica a un determinado enfoque de igualdad
                jQuery( '#idEnfEc' ).attr( 'value', '' );
                
                //  Cambio el nombre del boton agregar enfoque
                jQuery( '#btnAddEnfEco' ).attr( 'value', 'Agregar Enfoque de Igualdad' );
                
                //  Agrego lineas base
                addLineasBaseEE();
                
                banEE = 0;
            }
        })
    }
    
    //
    //  Limpio Formulario
    //
    jQuery( '#btnLimpiarEnfEco' ).click( function(){
        limpiarFrmEE();
    })


    //
    //  Limpia Grupo de Atencion Prioritaria
    //
    function limpiarFrmEE()
    {
        //  Recorro el combo hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbEnfoqueEcorae option' ) , 0 );
        
        //  Habilito combos
        jQuery( '#jform_cbEnfoqueEcorae' ).removeAttr( 'disabled', 'disabled' );
        
        
        //  Elimino contenido
        jQuery( '#jform_intEnfEcoMasculino' ).attr( 'value', '' );
        jQuery( '#jform_intEnfEcoFemenino' ).attr( 'value', '' );
        jQuery( '#jform_intEnfEcoTotal' ).attr( 'value', '' );
        
        //  Cambio el nombre del boton agregar enfoque
        jQuery( '#btnAddEnfEco' ).attr( 'value', 'Agregar Enfoque de Ecorae' );
        
        jQuery( '#EEMLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        jQuery( '#EEFLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        jQuery( '#EETLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        
        jQuery( '#EEMUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        jQuery( '#EEFUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        jQuery( '#EETUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        
        banEE = 0;
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