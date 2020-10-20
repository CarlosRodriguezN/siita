jQuery( document ).ready( function(){
    var banEI = 0;
    
    jQuery( '#btnAddEI' ).click( function(){
        
        var lstEI = [];

        if( banEI == 0 ){

            lstEI["idEI"] = jQuery( '#jform_idEnfoqueIgualdad' ).val();
            lstEI["enfoqueEI"] = jQuery( '#jform_idEnfoqueIgualdad :selected' ).text();
            lstEI["idTipoEI"] = jQuery( '#jform_cbEnfoqueIgualdad' ).val();
            lstEI["tipoEI"] = jQuery( '#jform_cbEnfoqueIgualdad :selected' ).text();
            lstEI["eiMasculino"] = jQuery( '#jform_intEIMasculino' ).val();
            lstEI["eiFemenino"] = jQuery( '#jform_intEIFemenino' ).val();
            lstEI["eiTotal"] = jQuery( '#jform_intEITotal' ).val();
            lstEI["published"] = 1;

            lstEnfIgu.push( lstEI );

            //  Agrego una fila con informacion de enfoque de igualdad
            addFilaEI( lstEI );
        }else{
            //  Actualizo el registro con nueva informacion
            updRegEI();
        }
        
    })
    
    //
    //  Agrego un nuevo beneficiario
    //
    function addFilaEI( data )
    {
        //  Construyo la Fila
        var fila = "<tr id='"+ data["idEI"] +"'>"
                + "     <td align='center'>"+ data["tipoEI"] +"</td>"
                + "     <td align='center'>"+ data["enfoqueEI"] +"</td>"

                + "     <td align='center'>"+ data["eiMasculino"] +"</td>"
                + "     <td align='center'>"+ data["eiFemenino"] +"</td>"
                + "     <td align='center'>"+ data["eiTotal"] +"</td>"

                + "     <td align='center'> <a class='updEI'> Editar </a> </td>"
                + "     <td align='center'> <a class='delEI'> Eliminar </a> </td>"
                +"  </tr>";

        //  Agrego la fila creada a la tabla
        jQuery( '#lstEnfIgu > tbody:last' ).append( fila );
        
        //  Agrego Lineas Base al Enfoque Igualdad
        addLineasBaseEI()
        
        //  Limpio informacion del formulario
        limpiarFrmEI();
    }
    
    
    
    //  Seteo Lineas Base desde la lista temporal de Lineas base a 
    //  lista general de lineas base
    function addLineasBaseEI()
    {
        var numRegTmp = lstLineasBaseTmp.length;
        
        for( var x = 0; x < numRegTmp; x++ ){
            //  Verifico si el indicador tiene linea base
            if( getEITieneLineaBase( lstLineasBaseTmp[x]["idDimension"], lstLineasBaseTmp[x]["idEnfoque"] ) ){
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
    jQuery( '.updEI' ).live( 'click', function(){
        var delFila = jQuery( this ).parent().parent();
        var idFila = delFila.attr( 'id' );
      
        var dataEI = getDataEI( idFila );
        
        if( dataEI ){
            banEI = 1;
            
            //  Recorro el combo de Tipos de Enfoque de igualdad a una determinada posicion
            recorrerCombo( jQuery( '#jform_cbEnfoqueIgualdad option' ), dataEI["idTipoEI"] );

            //  Recorro el combo de Enfoque de Igualdad mediante la ejecucion de un desencadenante
            jQuery( '#jform_cbEnfoqueIgualdad' ).trigger( 'change', [dataEI["idTipoEI"], dataEI["idEI"]] );
            
            //  Deshabilito los combos
            jQuery( '#jform_cbEnfoqueIgualdad' ).attr( 'disabled', 'disabled' );
            jQuery( '#jform_idEnfoqueIgualdad' ).attr( 'disabled', 'disabled' );
            
            //  Agrego el contenido
            jQuery( '#jform_intEIMasculino' ).attr( 'value', dataEI["eiMasculino"] );
            jQuery( '#jform_intEIFemenino' ).attr( 'value', dataEI["eiFemenino"] );
            jQuery( '#jform_intEITotal' ).attr( 'value', dataEI["eiTotal"] );
            
            //  Cambio el nombre del boton agregar enfoque
            jQuery( '#btnAddEI' ).attr( 'value', 'Editar Enfoque de Igualdad' );
            
            //  Enfoque 
            jQuery( '#idEnfIg' ).attr( 'value', idFila );
            
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var mlb = ( getEITieneLineaBase( dataEI["idEI"], 2 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                    : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var flb = ( getEITieneLineaBase( dataEI["idEI"], 1 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                    : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
            
            //  retorno un mensaje indicando si el indicador tiene asignada una linea Base
            var tlb = ( getEITieneLineaBase( dataEI["idEI"], 6 ) )  ? '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' 
                                                                    : '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">';
                                                                    
            jQuery( '#EIMLB' ).html( mlb );
            jQuery( '#EIFLB' ).html( flb );
            jQuery( '#EITLB' ).html( tlb );
        }
    })
    
    
    function getDataEI( idReg )
    {
        var numReg = lstEnfIgu.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstEnfIgu[x]["idEI"] == idReg ){
                return lstEnfIgu[x];
            }
        }
        
        return false;
    }

    
    //
    //  Verifico si un determinado indicador tiene asignada una linea base
    //
    function getEITieneLineaBase( idDimension, idEnfoque )
    {
        var numReg = lstLineasBase.length;
        for( x = 0; x < numReg; x++ ){
            if( lstLineasBase[x]["idDimension"] == idDimension && lstLineasBase[x]["idEnfoque"] == idEnfoque ){
                return true;
            }
        }
        
        return false;
    }
    
    
    function updRegEI()
    {
        var numReg = lstEnfIgu.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstEnfIgu[x]["idEI"] == jQuery( '#idEnfIg' ).val() ){
                
                lstEnfIgu[x]["eiMasculino"] = jQuery( '#jform_intEIMasculino' ).val();
                lstEnfIgu[x]["eiFemenino"] = jQuery( '#jform_intEIFemenino' ).val();
                lstEnfIgu[x]["eiTotal"] = jQuery( '#jform_intEITotal' ).val();
                
                updFilaEI( lstEnfIgu[x] );
            }
        }
    }
    
    
    function updFilaEI( data )
    {
        jQuery( '#lstEnfIgu tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == jQuery( '#idEnfIg' ).val() ){
                
                //  Agrego color a la fila actualizada
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
                var fila =  "   <td align='center'>"+ jQuery( '#jform_cbEnfoqueIgualdad :selected' ).text() +"</td>"
                            + " <td align='center'>"+ jQuery( '#jform_idEnfoqueIgualdad :selected' ).text() +"</td>"
                            + " <td align='center'>"+ data["eiMasculino"] +"</td>"
                            + " <td align='center'>"+ data["eiFemenino"] +"</td>"
                            + " <td align='center'>"+ data["eiTotal"] +"</td>"
                            + " <td align='center'> <a class='updEI'> Editar </a> </td>"
                            + " <td align='center'> <a class='delEI'> Eliminar </a> </td>";

                //  actualizo el contenido de la fila
                jQuery( this ).html( fila );
                
                //  Limpio formulario de EI
                limpiarFrmEI();
                
                //  EnCero la bandera que identifica a un determinado enfoque de igualdad
                jQuery( '#idEnfIg' ).attr( 'value', '' );
                
                //  Cambio el nombre del boton agregar enfoque
                jQuery( '#btnAddEI' ).attr( 'value', 'Agregar Enfoque de Igualdad' );
                
                //  Agrego lineas base
                addLineasBaseEI();
                
                banEI = 0;
            }
        })
    }
    
    
    jQuery( '#btnLimpiarEI' ).live( 'click', function(){
        limpiarFrmEI();
    })
    
    
    //
    //  Limpia Grupo de Atencion Prioritaria
    //
    function limpiarFrmEI()
    {
        //  Recorro el combo hasta una determinada posicion
        recorrerCombo( jQuery( '#jform_cbEnfoqueIgualdad option' ) , 0 );
        
        //  Recorrer el combo de enfoque de igualdad
        recorrerCombo( jQuery( '#jform_idEnfoqueIgualdad option' ) , 0 );
        
        //  Habilito combos
        jQuery( '#jform_cbEnfoqueIgualdad' ).removeAttr( 'disabled', 'disabled' );
        jQuery( '#jform_idEnfoqueIgualdad' ).removeAttr( 'disabled', 'disabled' );
        
        
        //  Elimino contenido
        jQuery( '#jform_intEIMasculino' ).attr( 'value', '' );
        jQuery( '#jform_intEIFemenino' ).attr( 'value', '' );
        jQuery( '#jform_intEITotal' ).attr( 'value', '' );
        
        //  Cambio el nombre del boton agregar enfoque
        jQuery( '#btnAddEI' ).attr( 'value', 'Agregar' );
        
        jQuery( '#EIMLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        jQuery( '#EIFLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        jQuery( '#EITLB' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
        
        jQuery( '#EIMUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        jQuery( '#EIFUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        jQuery( '#EITUT' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_rojo_small.png">' );
        
        banEI = 0;
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