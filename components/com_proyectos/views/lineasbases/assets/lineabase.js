jQuery( document ).ready( function(){
    var dtaObjIndicador = parent.window.objGestionIndicador;

    var tpoIndicador = jQuery( '#tpoIndicador' ).val();
    var idRegIndicador = jQuery( '#idRegIndicador' ).val();
    var tpo = jQuery( '#tpo' ).val();

    var dtaLstLB = false;
    var idLineaBase;
    var lstTmpLineaBase = new Array();
    var banIdLB = -1;

    //  Actualiza informacion de una determinada Linea Base Marcada
    updLBRegistrada();

    function updLBRegistrada()
    {
        switch( tpoIndicador ){
            //  Lineas Base de Indicadores Economicos
            case 'eco': 
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( typeof( dtaObjIndicador.indEconomico[idRegIndicador].lstLineaBase ) != "undefined" ){
                    dtaLstLB = dtaObjIndicador.indEconomico[idRegIndicador].lstLineaBase;
                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x] );
                    }
                }
                
            break;
            
            //  Lineas Base de Indicadores Financieros
            case 'fin': 
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( typeof( dtaObjIndicador.indFinanciero[idRegIndicador].lstLineaBase ) != "undefined" ){
                    dtaLstLB = dtaObjIndicador.indFinanciero[idRegIndicador].lstLineaBase;
                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x] );
                    }
                }
            break;
            
            //  Lineas Base de Indicadores Beneficiarios Directos
            case 'bd': 
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( typeof( dtaObjIndicador.indBDirecto[idRegIndicador].lstLineaBase ) != "undefined" ){
                    dtaLstLB = dtaObjIndicador.indBDirecto[idRegIndicador].lstLineaBase;
                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x] );
                    }
                }
            break;
            
            //  Lineas Base de Indicadores Beneficiarios Indirectos
            case 'bi': 
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( typeof( dtaObjIndicador.indBIndirecto[idRegIndicador].lstLineaBase ) != "undefined" ){
                    dtaLstLB = dtaObjIndicador.indBIndirecto[idRegIndicador].lstLineaBase;
                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x] );
                    }
                }

            break;
            
            case 'gap':
                switch( tpo ){
                    //  Lineas Base de Indicadores Masculino
                    case 'm': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino.lstLineaBase;
                        }
                        
                    break;
                    
                    //  Lineas Base de Indicadores Femenino
                    case 'f': 

                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino.lstLineaBase;
                        }

                    break;
                    
                    //  Lineas Base de Indicadores Total
                    case 't': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapTotal.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstGAP[idRegIndicador].gapTotal.lstLineaBase;
                        }
                    break;
                }
                
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( dtaLstLB != false ){

                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x] );
                    }
                }
                
            break;
            
            case 'ei':
                switch( tpo ){
                    //  Lineas Base de Indicadores Masculino
                    case 'm': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstLineaBase;
                        }
                    break;
                    
                    //  Lineas Base de Indicadores Femenino
                    case 'f': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstLineaBase;
                        }
                    break;
                    
                    //  Lineas Base de Indicadores Total
                    case 't': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstLineaBase;
                        }
                    break;
                }
                
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( dtaLstLB != false ){

                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x] );
                    }
                }
            break;
            
            case 'ee':
                switch( tpo ){
                    //  Lineas Base de Indicadores Masculino
                    case 'm': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eeMasculino.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eeMasculino.lstLineaBase;
                        }
                    break;
                    
                    //  Lineas Base de Indicadores Femenino
                    case 'f': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eeFemenino.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eeFemenino.lstLineaBase;
                        }
                    break;
                    
                    //  Lineas Base de Indicadores Total
                    case 't': 
                        //  Actualizo informacion de la tabla de Unidades Territoriales
                        if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eeTotal.lstLineaBase ) != "undefined" ){
                            dtaLstLB = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eeTotal.lstLineaBase;
                        }
                    break;
                }
                
            break;
            
            //  Lineas Base de Indicadores Beneficiarios Indirectos
            case 'oi': 
                //  Actualizo informacion de la tabla de Unidades Territoriales
                if( typeof( dtaObjIndicador.lstOtrosIndicadores[idRegIndicador].lstLineaBase ) != "undefined" ){
                    dtaLstLB = dtaObjIndicador.lstOtrosIndicadores[idRegIndicador].lstLineaBase;
                    var nrut = dtaLstLB.length;

                    for( var x = 0; x < nrut; x++  ){
                        lstTmpLineaBase.push( dtaLstLB[x].idLineaBase );
                    }
                }

            break;
        }
    
        //  Marco las lineas base seleccionadas
        checkLineasBase();
    }


    function checkLineasBase()
    {
        jQuery("input[type=checkbox]").each(function(){
            var val = jQuery( this ).val();
            if( jQuery.inArray( val, lstTmpLineaBase ) != -1 ){
                jQuery( this ).attr( 'checked', 'checked' );
            }
        });
    }


    /**
     * 
     * Obtiene una lista de lineas base seleccionadas por el usuario
     * 
     * @returns {undefined}
     * 
     */
    function listaLineasBase()
    {
        //  enCero Arreglo temporal
        lstTmpLineaBase = new Array();
        
        jQuery("input[type=checkbox]:checked").each(function(){
            lstTmpLineaBase.push( jQuery( this ).val() );
        });
    }

    //  Agrego informacion de linea base
    Joomla.submitbutton = function( task )
    {
        if( task == 'lineabase.asignar' ){

            //  Obtengo el indentificador de linea base seleccionadas
            listaLineasBase();

            switch( tpoIndicador ){
                case 'eco': 
                    //  Vacio Lista de Lineas Base de Indicador Economico
                    parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstLineaBase = new Array();

                    //  Agrego Lista editada de Lineas Base de Indicador Economico
                    for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                        parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstLineaBase.push( lstTmpLineaBase[x] );
                    }
                break;
                
                case 'fin': 
                    //  Vacio Lista de Lineas Base de Indicador Financiero
                    parent.window.objGestionIndicador.indFinanciero[idRegIndicador].lstLineaBase = new Array();

                    //  Agrego Lista editada de Lineas Base de Indicador Financiero
                    for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                        parent.window.objGestionIndicador.indFinanciero[idRegIndicador].lstLineaBase.push( lstTmpLineaBase[x] );
                    }
                    
                break;
            
                case 'bd': 
                    //  Vacio Lista de Lineas Base de Indicador Beneficiario Directo
                    parent.window.objGestionIndicador.indBDirecto[idRegIndicador].lstLineaBase = new Array();

                    //  Agrego Lista editada de Indicador Beneficiario Directo
                    for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                        parent.window.objGestionIndicador.indBDirecto[idRegIndicador].lstLineaBase.push( lstTmpLineaBase[x] );
                    }
                break;

                case 'bi': 
                    //  Vacio Lista de Lineas Base de Indicador Beneficiario Indirecto
                    parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].lstLineaBase = new Array();

                    //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                    for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                        parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].lstLineaBase.push( lstTmpLineaBase[x] );
                    }
                    
                break;
                
                case 'gap':
                    switch( tpo ){
                        //  Lineas Base de Indicadores Masculino
                        case 'm': 
                            //  Vacio Lista de Lineas Base de Indicador Masculino
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;

                        //  Lineas Base de Indicadores Femenino
                        case 'f': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;

                        //  Lineas Base de Indicadores Total
                        case 't':
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;
                    }

                break;
                
                case 'ei':
                    switch( tpo ){
                        //  Lineas Base de Indicadores Masculino
                        case 'm': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;

                        //  Lineas Base de Indicadores Femenino
                        case 'f': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].eiFemenino.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].eiFemenino.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                        break;

                        //  Lineas Base de Indicadores Total
                        case 't': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                        break;
                    }

                break;
                
                case 'ee':
                    switch( tpo ){
                        //  Lineas Base de Indicadores Masculino
                        case 'm': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;

                        //  Lineas Base de Indicadores Femenino
                        case 'f': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;

                        //  Lineas Base de Indicadores Total
                        case 't': 
                            //  Vacio Lista de Lineas Base de Indicador Femenino
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstLineaBase = new Array();

                            //  Agrego Lista editada de Lineas Base de Indicador Beneficiario Indirecto
                            for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstLineaBase.push( lstTmpLineaBase[x] );
                            }
                            
                        break;
                    }

                break;
                
                
                case 'oi': 
                    //  Vacio Lista de Lineas Base de Otros Indicadores
                    parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstLineaBase = new Array();

                    //  Agrego Lista editada de Lineas Base de Otros Indicadores
                    for( var x = 0; x < lstTmpLineaBase.length; x++ ){
                        parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstLineaBase.push( lstTmpLineaBase[x] );
                    }
                    
                break;
            }

            if( lstTmpLineaBase.length > 0 ){
                //  Cambio la imagen del indicador seleccionado
                jQuery( '#'+ tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() +'LB', window.parent.document ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_verde_small.png">' );
            }else{
                //  Cambio la imagen del indicador seleccionado
                jQuery( '#'+ tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() +'LB', window.parent.document ).html( '<img src="/media/com_proyectos/images/btnLineaBase/LB/lb_rojo_small.png">' );
            }
        }
        
        //  enCero Arreglo temporal
        lstTmpLineaBase = new Array();
        
        //  Cierro la ventana modal( popup )
        window.parent.SqueezeBox.close();
    }
})