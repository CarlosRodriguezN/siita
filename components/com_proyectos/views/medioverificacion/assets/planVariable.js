jQuery( document ).ready( function(){
    
    jQuery.tablesorter.defaults.widgets = ['zebra']; 
    jQuery.tablesorter.defaults.sortInitialOrder = 'desc';
    
    //  Orden de pestañas
    jQuery( '#tbAnual' ).tablesorter();
    
    var banPlan = false;
    var np = 0;
    var banUpdVP = 0;
    var idRegistro;

    var idIndicador = jQuery( '#idIndicador' ).val();
    var idVariable = jQuery( '#idVariable' ).val();
    
    //  Verifico si la lista temporal esta con informacion
    if( lstTmpVarPlanificadas.length > 0 ){
        var numReg = lstTmpVarPlanificadas.length;
        for( x = 0; x < numReg; x++ ){
            //  Agrego la planificacion
            addPlanificacion( lstTmpVarPlanificadas[x] );
            np = lstTmpVarPlanificadas[x]["idRegistro"];
        }
        
        var sorting = [[0, 0]];
        jQuery( '#tbAnual' ).trigger( 'sorton', [sorting] );
    }
    
    //
    //  Verifica la existencia la planificacion de una variable en un determinado indicador
    //
    function existePlanificacionVariable( fecha, valor, tipoPlan )
    {
        var numReg = lstTmpVarPlanificadas.length;
        
        for( x = 0; x < numReg; x++ ){
            if( lstTmpVarPlanificadas[x]["fecha"] == fecha && lstTmpVarPlanificadas[x]["valor"] == valor && lstTmpVarPlanificadas[x]["tpoPlanificacion"] == tipoPlan ){
                return true;
            }
        }

        return false;
    }
    
    //
    //  Agrego una planificacion Anual
    //
    jQuery('#btnAddPA').click( function(){
        var planVariable = [];
        var fecha = jQuery( '#jform_dtFecha_pa' ).val();
        var valor = jQuery( '#jform_valorVariable_pa' ).val();
        var tpoPlan = 1
        
        //  Verifico la NO existencia
        banPlan = existePlanificacionVariable( fecha, valor, tpoPlan );


        switch( true ){
            //  Si es nuevo y no esta ya registrado lo agrego a la lista temporal de variables planificadas
            case( banUpdVP == 0 && !banPlan == true ): 
                planVariable["idRegistro"] = ++np;
                planVariable["idPlanificacion"] = 0;
                planVariable["fecha"] = fecha;
                planVariable["valor"] = valor;
                planVariable["tpoPlanificacion"] = tpoPlan;
                planVariable["published"] = 1;

                //  Agrego la planificacion
                addPlanificacion( planVariable );

                limpiarFrmPlanificacion( tpoPlan );

                //  Agrego a la lista temporal el nuevo valor planificado
                lstTmpVarPlanificadas.push( planVariable );
            break;
            
            //  Nueva Planificacion, pero ya registrada
            case( banUpdVP == 0 && banPlan == true ):
                jAlert( 'Planificacion Registrada', 'SIITA - ECORAE' );
            break;
            
            //  Planificacion esta siendo actualizada y no esta ya registrado 
            //  edito a la lista temporal con los nuevos valores de variables planificadas
            case( banUpdVP == 1 && !banPlan == true ): 
                updDataLstTmpVarPlan( idRegistro, fecha, valor, tpoPlan );
                updInfoFilaPlanificacion( idRegistro, fecha, valor, tpoPlan );
                limpiarFrmPlanificacion( tpoPlan );
                jQuery( '#btnAddPA' ).attr( 'value', 'Agregar Planificación Anual' );
                banUpdVP = 0;
            break;
            
            //  Planificacion
            case( banUpdVP == 1 && banPlan == true ): 
                jAlert( 'Planificacion Registrada', 'SIITA - ECORAE' );
            break;
        }
    })

    //
    //  Actualizo el contenido 
    //
    function updDataLstTmpVarPlan( idRegistro, fecha, valor, tpoPlan )
    {
        var numReg = lstTmpVarPlanificadas.length;
        for( x = 0; x < numReg; x++ ){
            if( lstTmpVarPlanificadas[x]["idRegistro"] == idRegistro && lstTmpVarPlanificadas[x]["tpoPlanificacion"] == tpoPlan ){
                lstTmpVarPlanificadas[x]["fecha"] = fecha;
                lstTmpVarPlanificadas[x]["valor"] = valor;
            }
        }
    }

    //
    //  Actualizo informacion en a fila actualizada
    //
    function updInfoFilaPlanificacion( idRegistro, fecha, valor, tpoPlan )
    {
        var tbUdpPlan = ( tpoPlan == 1 )    ? jQuery('#tbAnual tr')
                                            : jQuery('#tbPluriAnual tr');
        
        
        tbUdpPlan.each( function(){
            if( jQuery( this ).attr( 'id' ) == idRegistro ){
                
                //  Agrego color a la fila actualizada
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
                //  Construyo la Fila
                var fila = "    <td align='center'>"+ fecha +"</td>"
                            + " <td align='center'>"+ valor +"</td>"
                            + " <td align='center'> <a class='updPlanificacion'>Editar</a> </td>"                
                            + " <td align='center'> <a class='delPlanificacion'>Eliminar</a> </td>";

                jQuery( this ).html( fila );
            }
        })
    }

    //
    //  Agrego una planificacion Pluri Anual
    //
    jQuery('#btnAddPP').click( function(){
        
        var planVariable = [];
        var fecha = jQuery( '#jform_dtFecha_pp' ).val();
        var valor = jQuery( '#jform_valorVariable_pp' ).val();
        var tpoPlan = 2
        
        //  Verifico la NO existencia
        banPlan = existePlanificacionVariable( fecha, valor, tpoPlan );
        
        switch( true ){
            //  Si es nuevo y no esta ya registrado lo agrego a la lista temporal de variables planificadas
            case( banUpdVP == 0 && !banPlan == true ): 
                planVariable["idRegistro"] = ++np;
                planVariable["idPlanificacion"] = 0;
                planVariable["fecha"] = fecha;
                planVariable["valor"] = valor;
                planVariable["tpoPlanificacion"] = tpoPlan;
                planVariable["published"] = 1;

                //  Agrego la planificacion
                addPlanificacion( planVariable );

                limpiarFrmPlanificacion( tpoPlan );

                //  Agrego a la lista temporal el nuevo valor planificado
                lstTmpVarPlanificadas.push( planVariable );
            break;
            
            //  Nueva Planificacion, pero ya registrada
            case( banUpdVP == 0 && banPlan == true ):
                jAlert( 'Planificacion Registrada', 'SIITA - ECORAE' );
            break;
            
            //  Planificacion esta siendo actualizada y no esta ya registrado 
            //  edito a la lista temporal con los nuevos valores de variables planificadas
            case( banUpdVP == 1 && !banPlan == true ): 
                updDataLstTmpVarPlan( idRegistro, fecha, valor, tpoPlan );
                updInfoFilaPlanificacion( idRegistro, fecha, valor, tpoPlan );
                limpiarFormulario( tpoPlan );
                jQuery( '#btnAddPP' ).attr( 'value', 'Agregar Planificación Pluri Anual' );
                banUpdVP = 0;
            break;
            
            //  Planificacion
            case( banUpdVP == 1 && banPlan == true ): 
                jAlert( 'Planificacion Registrada', 'SIITA - ECORAE' );
            break;
        }
    })
    
    
    //  Borro informaicon de campos del formulario
    function limpiarFrmPlanificacion( tpoPlan )
    {
        if( tpoPlan == 1 ){
            jQuery( '#jform_dtFecha_pa' ).attr( 'value', '' );
            jQuery( '#jform_valorVariable_pa' ).attr( 'value', '' );
        }else{
            jQuery( '#jform_dtFecha_pp' ).attr( 'value', '' );
            jQuery( '#jform_valorVariable_pp' ).attr( 'value', '' );
        }
    }
    
    
    //
    //  Gestiono la actualizacion de una variable de planificacion
    //
    jQuery( '.updPlanificacion' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var numReg = lstTmpVarPlanificadas.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstTmpVarPlanificadas[x]["idRegistro"] == idFila ){
                idRegistro = idFila;
                if( lstTmpVarPlanificadas[x]["tpoPlanificacion"] == 1 ){
                    jQuery( '#jform_dtFecha_pa' ).attr( 'value', lstTmpVarPlanificadas[x]["fecha"] );
                    jQuery( '#jform_valorVariable_pa' ).attr( 'value', lstTmpVarPlanificadas[x]["valor"] );
                    jQuery( '#btnAddPA' ).attr( 'value', 'Editar Planificación Anual' );
                }else{
                    jQuery( '#jform_dtFecha_pp' ).attr( 'value', lstTmpVarPlanificadas[x]["fecha"] );
                    jQuery( '#jform_valorVariable_pp' ).attr( 'value', lstTmpVarPlanificadas[x]["valor"] );
                    jQuery( '#btnAddPP' ).attr( 'value', 'Editar Planificación Pluri Anual' );
                }
                
                banUpdVP = 1;
            }
        }
    })
    
    //
    //  Cuenta el numero de elementos de un determinado tipo de planificacion
    //
    function getNumElementos( tpoPlan )
    {
        numReg = lstTmpVarPlanificadas.length;
        var numElementos = 0;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstTmpVarPlanificadas[x]["tpoPlanificacion"] == tpoPlan && lstTmpVarPlanificadas[x]["published"] != 0 ){
                ++numElementos;
            }
        }
        
        return numElementos;
    }
    
    
    jQuery('.delPlanificacion').live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Planificación", "SIITA - ECORAE", function( result ){
            if( result ) {
                //  Actualizo a 0 el campo publish de una determinada Planificacion
                //  y retorno el numero de registros de un determinado tipo de planificacion
                if( delRegistroPlanificacion( idFila ) == 0 ){
                    addFilaMensaje( tpoPlan );
                }
            } 
        });
    })


    function delRegistroPlanificacion( idFila )
    {
        var numReg = lstTmpVarPlanificadas.length;

        for( var x = 0; x < numReg; x++ ){
            if( lstTmpVarPlanificadas[x]["idRegistro"] == idFila ){
                lstTmpVarPlanificadas[x]["published"] = 0;

                //  Elimino fila de la tabla de planificaciones
                delFilaPlanificacion( idFila, lstTmpVarPlanificadas[x]["tpoPlanificacion"] );
                
                //  Numero de elementos restantes
                if( getNumElementos( lstTmpVarPlanificadas[x]["tpoPlanificacion"] ) == 0 ){
                    //  Si la planificacion no tiene registros agregamos un fila la final de la tabla
                    addFilaMensaje( lstTmpVarPlanificadas[x]["tpoPlanificacion"] ); 
                }
            }
        }
        
        return false;
    }


    //  Elimino la fila 
    function delFilaPlanificacion( idFila, tpoPlanificacion )
    {
        var tbUdpPlan = ( tpoPlanificacion == 1 )   ? jQuery('#tbAnual tr')
                                                    : jQuery('#tbPluriAnual tr');
        
        //  Elimino fila de la tabla del registro eliminado
        tbUdpPlan.each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    
    //
    //  Agrego una nueva Planificacion a una variable de un determinado Indicador
    //
    function addPlanificacion( data )
    {
        //  Construyo la Fila
        var fila = "    <tr id='"+ data["idRegistro"] +"'>"
                    + "     <td align='center'>"+ data["fecha"] +"</td>"
                    + "     <td align='center'>"+ data["valor"] +"</td>"
                    + "     <td align='center'> <a class='updPlanificacion'>Editar</a> </td>"                
                    + "     <td align='center'> <a class='delPlanificacion'>Eliminar</a> </td>"
                    + " </tr>";

        //  Agrego la fila creada a la tabla
        if( data["tpoPlanificacion"] == 1 ){
            jQuery( '#tbAnual > tbody:last' ).append( fila );
            jQuery( '#tbAnual' ).trigger( 'update' );
            jQuery( "#tbAnual" ).trigger( 'applyWidgets' );
        }else{
            jQuery( '#tbPluriAnual > tbody:last' ).append( fila );
        }
        
        return false;
    }
     

    //  Agrego Mensaje en la una determinadad tabla de acuerdo a la planificacion
    function addFilaMensaje( tpoPlanificacion )
    { 
        //  Construyo la Fila
        var fila = "    <tr>"
                    + "     <td colspan='4' align='center'>Sin Registros Disponibles</td>"
                    + " </tr>";

        //  Agrego la fila creada a la tabla
        if( tpoPlanificacion == 1 ){
            jQuery( '#tbAnual > tbody:last' ).append( fila );
        }else{
            jQuery( '#tbPluriAnual > tbody:last' ).append( fila );
        }
    }
     
     
    //  
    //  De acuerdo a la tarea realizo el proceso de asignacion de planificacion de variables
    //
    Joomla.submitbutton = function( task )
    {
        if( task == 'planVariable.asignar' ){
            //  Asigno los valores temporales
            var lstVariables = window.parent.lstVariables;
            var numReg = lstVariables.length;
            
            for( var x = 0; x < numReg; x++ ){
                if( lstVariables[x].idIndicador == idIndicador && lstVariables[x].idVariable == idVariable ){
                    var numRegTmp = lstTmpVarPlanificadas.length;
                    //  EnCero el arreglo de planficacion de variables
                    window.parent.lstVariables[x].planificacion = new Array();
                    
                    //  Inserto el nuevo arreglo de planificacion de variables
                    for( var y = 0; y < numRegTmp; y++ ){
                        window.parent.lstVariables[x].planificacion.push( lstTmpVarPlanificadas[y] );
                    }
                }
            }
        }
        
        //  Cierro la ventana modal( popup )
        window.parent.SqueezeBox.close();
    }
});