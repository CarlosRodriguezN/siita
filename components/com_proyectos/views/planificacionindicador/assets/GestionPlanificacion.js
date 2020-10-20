jQuery( document ).ready( function(){
    
    var dtaObjIndicador = parent.window.objGestionIndicador;
    
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var tpo = jQuery('#tpo').val();
    
    var dtaLstUT = false;
    var idUndTerritorial;
    var lstTmpPlanificacion = new Array();
    var banIdRegPI = -1;
    
    
    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split('?')[0];

    //  Cargo informacion de acuerdo al tipo de indicador
    switch( tpoIndicador ){
        //  Indicadores Economicos
        case 'eco': 
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indEconomico[idRegIndicador].lstPlanificacion ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indEconomico[idRegIndicador].lstPlanificacion;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );
                    
                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );                    
                }
            }
        break;
        
        //  Indicadores Financieros
        case 'fin':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indFinanciero[idRegIndicador].lstPlanificacion ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indFinanciero[idRegIndicador].lstPlanificacion;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );
                    
                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );
                    
                }
            }
        break;
        
        //  Beneficiarios Directos
        case 'bd':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indBDirecto[idRegIndicador].lstPlanificacion ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indBDirecto[idRegIndicador].lstPlanificacion;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );
                    
                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );
                }
            }
        break;
        
        //  Beneficiarios Indirectos
        case 'bi':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indBIndirecto[idRegIndicador].lstPlanificacion ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indBIndirecto[idRegIndicador].lstPlanificacion;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );

                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );
                }
            }
        break;
        
        //  Grupos de Atencion prioritaria
        case 'gap':
            switch( tpo ){
                case 'm': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino.lstPlanificacion;
                    }
                break;
                
                case 'f': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino.lstPlanificacion;
                    }
                break;
                
                case 't': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapTotal.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstGAP[idRegIndicador].gapTotal.lstPlanificacion;
                    }
                break;
            }
            
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( dtaLstUT != false ){

                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );

                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );                    
                }
            }

        break;
        
        //  Enfoque de Igualdad
        case 'ei':
            switch( tpo ){
                case 'm': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstPlanificacion;
                    }
                break;
                
                case 'f': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstPlanificacion;
                    }
                break;
                
                case 't': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstPlanificacion;
                    }                    
                break;
            }
            
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( dtaLstUT != false ){

                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );

                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );
                }
            }

        break;
        
        //  Enfoque de Ecorae
        case 'ee':
            switch( tpo ){
                case 'm': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstPlanificacion;
                    }
                break;
                
                case 'f': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstPlanificacion;
                    }
                break;
                
                case 't': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstPlanificacion ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstPlanificacion;
                    }
                break;
            }
            
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( dtaLstUT != false ){

                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );

                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );
                }
            }
        break;
        
        //  Otros Indicadores
        case 'oi':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.lstOtrosIndicadores[idRegIndicador].lstPlanificacion ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.lstOtrosIndicadores[idRegIndicador].lstPlanificacion;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpPlanificacion.push( dtaLstUT[x] );
                    
                    //  Registro una nueva fila en la tabla de planificacion
                    jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( dtaLstUT[x], 0 ) );
                }
            }
        break;
    }
    
    
    /**
     *  Agrego un registro de unidad territorial
     */
    jQuery('#btnAddPlanIndicador').live('click', function() {
        var fecha = jQuery('#jform_fchPlanificada').val();
        var valor = jQuery('#jform_valorPlanificacion').val();
        var idRegUT = lstTmpPlanificacion.length;
                                            
        var objPlanificacion = new Planificacion( idRegUT, 0, fecha, valor );

        if( existePI( objPlanificacion ) == 0 ){

            if( banIdRegPI != -1 ){
                //  Actualizo el contenido
                lstTmpPlanificacion[banIdRegPI] = objPlanificacion;
                
                //  Actualizo informacion de la fila
                updInfoFilaPI( banIdRegPI, addFilaPI( objPlanificacion, 1 ) );
                
                //  EnCero el contenido de la bandera registro de planificacion
                banIdRegPI = -1;
            }else{
                //  Registro una nueva planificacion  
                lstTmpPlanificacion.push( objPlanificacion );
                
                //  Registro una nueva fila en la tabla de planificacion
                jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( addFilaPI( objPlanificacion, 0 ) );
            }
        }else{
            jAlert( 'Planificacion, ya existe', 'SIITA - ECORAE' );
        }
    })
    
    /**
     * 
     * Valido la no existencia de una planificaion
     * 
     * @param {type} pi
     * @returns {Number}
     */
    function existePI( pi )
    {
        var nrpi = lstTmpPlanificacion.length;
        var ban = 0;

        for( var x = 0; x < nrpi; x++ ){
            if( lstTmpPlanificacion[x].toString() == pi.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }

    /**
     * 
     * Agrego una fila a la tabla Planificacion Institucional
     * 
     * @param {type} dtaPlanificacion   Datos de Planificacion
     * @param {type} banTipo            Bandera que indica que controla si es nuevo o existente
     *                                  0: Nuevo, 1: Actualizacion
     *                                  
     * @returns {undefined}
     */
    function addFilaPI( dtaPlanificacion, banTipo )
    {
        //  Construyo la Fila
        var fila = ( banTipo == 0 ) ? "<tr id='"+ dtaPlanificacion.idRegPI +"'>" 
                                    : "";

        fila += "       <td align='center'>"+ dtaPlanificacion.fecha +"</td>"
                    + " <td align='center'>"+ dtaPlanificacion.valor +"</td>"
                    + " <td align='center'> <a class='updPI'> Editar </a> </td>"
                    + " <td align='center'> <a class='delPI'> Eliminar </a> </td>";
            
        fila += (banTipo == 0 ) ? "</tr>"
                                : "";

        return fila;
    }
    
    
    /**
     * 
     * Actualizo el contenido de una fila de Planificacion
     * 
     * @param {type} idFila     Identificador de la fila
     * @param {type} dataUpd    Datos de una fila
     * 
     * @returns {undefined}
     */
    function updInfoFilaPI( idFila, dataUpd )
    {
        jQuery( '#lstPlanificacionIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).html( dataUpd );
            }
        })
    }

    /**
     * Gestiono la actualizacion de la Planificacion de un indicador
     */
    jQuery( '.updPI' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        var dtaPI = lstTmpPlanificacion[idFila];
        banIdRegPI = idFila;
        
        //  Actualizo informacion de formulario Planificacion Indicador
        jQuery('#jform_fchPlanificada').attr( 'value', dtaPI.fecha );
        jQuery('#jform_valorPlanificacion').attr( 'value', dtaPI.valor );
    })
    
    //  Agrego informacion de linea base
    Joomla.submitbutton = function(task)
    {
        if (task == 'planificacionindicador.asignar') {
            switch (tpoIndicador) {
                case 'eco':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstPlanificacion = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                        parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstPlanificacion.push( lstTmpPlanificacion[x] );
                    }
                break;
                
                case 'fin':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indFinanciero[idRegIndicador].lstPlanificacion = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                        parent.window.objGestionIndicador.indFinanciero[idRegIndicador].lstPlanificacion.push( lstTmpPlanificacion[x] );
                    }
                break;
                
                case 'bd':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indBDirecto[idRegIndicador].lstPlanificacion = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                        parent.window.objGestionIndicador.indBDirecto[idRegIndicador].lstPlanificacion.push( lstTmpPlanificacion[x] );
                    }
                break;
                
                case 'bi':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].lstPlanificacion = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                        parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].lstPlanificacion.push( lstTmpPlanificacion[x] );
                    }
                break;
                
                case 'gap':
                    switch( tpo ){
                        case 'm': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                        
                        case 'f': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                        
                        case 't': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                    }

                break;
                
                case 'ei':
                    switch( tpo ){
                        case 'm': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                        
                        case 'f': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                        
                        case 't': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                    }

                break;
                
                case 'ee':
                    switch( tpo ){
                        case 'm': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                        
                        case 'f': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                        
                        case 't': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstPlanificacion = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstPlanificacion.push( lstTmpPlanificacion[x] );
                            }
                        break;
                    }

                break;
                
                //  Otros Indicadores
                case 'oi':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstPlanificacion = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpPlanificacion.length; x++ ){
                        parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstPlanificacion.push( lstTmpPlanificacion[x] );
                    }
                break;

            }
            
            //  Cambio la imagen del indicador seleccionado
            jQuery( '#'+ tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() +'TP', window.parent.document ).html( '<img src="/media/com_proyectos/images/btnLineaBase/PN/pn_verde_small.png">' );

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        } else {
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
})