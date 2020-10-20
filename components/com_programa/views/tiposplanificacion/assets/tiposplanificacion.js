jQuery( document ).ready( function(){
    var tpoIndicador = jQuery( '#tpoIndicador' ).val();
    var idRegIndicador = jQuery( '#idRegIndicador' ).val();
    var tpo = jQuery( '#tpo' ).val();

    //  Actualiza informacion de una determinada Linea Base Marcada
    updTPRegistrada();

    function updTPRegistrada()
    {
        switch( tpoIndicador ){
            case 'eco': 
                var idTPRegistrada = parent.window.objGestionIndicador.indEconomico[idRegIndicador].idTpoPlanificacion;
            break;
            
            case 'fin': 
                var idTPRegistrada = parent.window.objGestionIndicador.indFinanciero[idRegIndicador].idTpoPlanificacion;
            break;
            
            case 'bd': 
                var idTPRegistrada = parent.window.objGestionIndicador.indBDirecto[idRegIndicador].idTpoPlanificacion;
            break;
            
            case 'bi': 
                var idTPRegistrada = parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].idTpoPlanificacion;
            break;
            
            case 'gap':
                switch( tpo ){
                    //  Lineas Base de Indicadores Masculino
                    case 'm': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.idTpoPlanificacion;
                    break;
                    
                    //  Lineas Base de Indicadores Femenino
                    case 'f': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.idTpoPlanificacion;
                    break;
                    
                    //  Lineas Base de Indicadores Total
                    case 't': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.idTpoPlanificacion;
                    break;
                }
            break;
            
            
            case 'ei':
                switch( tpo ){
                    //  Lineas Base de Indicadores Masculino
                    case 'm': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.idTpoPlanificacion;
                    break;
                    
                    //  Lineas Base de Indicadores Femenino
                    case 'f': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.idTpoPlanificacion;
                    break;
                    
                    //  Lineas Base de Indicadores Total
                    case 't': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.idTpoPlanificacion;
                    break;
                }
            break;
            
            case 'ee':
                switch( tpo ){
                    //  Lineas Base de Indicadores Masculino
                    case 'm': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.idTpoPlanificacion;
                    break;
                    
                    //  Lineas Base de Indicadores Femenino
                    case 'f': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.idTpoPlanificacion;
                    break;
                    
                    //  Lineas Base de Indicadores Total
                    case 't': 
                        var idTPRegistrada = parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.idTpoPlanificacion;
                    break;
                }
            break;
            
            case 'oi': 
                var idTPRegistrada = parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].idTpoPlanificacion;
            break;
            
        }
        
        recorreRadioTpPlanificacion( jQuery( 'input[name=idTipoPlanificacion]:radio' ), idTPRegistrada );
    }

    //  Marca como seleccionado una determinada Linea Base
    function recorreRadioTpPlanificacion( radio, posicion )
    {
        jQuery( radio ).each( function(){
            if( jQuery( this ).val() == posicion ){
                //  marca como seleccionado a una determinada Linea Base
                jQuery( this ).attr( 'checked', true );
            }
        })
    }
    
    //  Agrego informacion de linea base
    Joomla.submitbutton = function( task )
    {
        if( task == 'tiposplanificacion.asignar' ){

            //  Obtengo el indentificador de linea base seleccionada
            var idTpoPlanificacion = jQuery( 'input:radio[name=idTipoPlanificacion]:checked' ).val();

            switch( tpoIndicador ){
                case 'eco': 
                    parent.window.objGestionIndicador.indEconomico[idRegIndicador].idTpoPlanificacion = idTpoPlanificacion;
                break;
                
                case 'fin': 
                    parent.window.objGestionIndicador.indFinanciero[idRegIndicador].idTpoPlanificacion = idTpoPlanificacion;
                break;
            
                case 'bd': 
                    parent.window.objGestionIndicador.indBDirecto[idRegIndicador].idTpoPlanificacion = idTpoPlanificacion;
                break;

                case 'bi': 
                    parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].idTpoPlanificacion = idTpoPlanificacion;
                break;
                
                case 'gap':
                    switch( tpo ){
                        //  Lineas Base de Indicadores Masculino
                        case 'm': 
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.idTpoPlanificacion = idTpoPlanificacion;
                        break;

                        //  Lineas Base de Indicadores Femenino
                        case 'f': 
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.idTpoPlanificacion = idTpoPlanificacion;
                        break;

                        //  Lineas Base de Indicadores Total
                        case 't': 
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.idTpoPlanificacion = idTpoPlanificacion;
                        break;
                    }
                break;
                
                case 'ei':
                    switch( tpo ){
                        //  Lineas Base de Indicadores Masculino
                        case 'm': 
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.idTpoPlanificacion = idTpoPlanificacion;
                        break;

                        //  Lineas Base de Indicadores Femenino
                        case 'f': 
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.idTpoPlanificacion = idTpoPlanificacion;
                        break;

                        //  Lineas Base de Indicadores Total
                        case 't': 
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.idTpoPlanificacion = idTpoPlanificacion;
                        break;
                    }
                break;
                
                case 'ee':
                    switch( tpo ){
                        //  Lineas Base de Indicadores Masculino
                        case 'm': 
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.idTpoPlanificacion = idTpoPlanificacion;
                        break;

                        //  Lineas Base de Indicadores Femenino
                        case 'f': 
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.idTpoPlanificacion = idTpoPlanificacion;
                        break;

                        //  Lineas Base de Indicadores Total
                        case 't': 
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.idTpoPlanificacion = idTpoPlanificacion;
                        break;
                    }
                break;
                
                case 'oi': 
                    parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].idTpoPlanificacion = idTpoPlanificacion;
                break;
            }

            //  Cambio la imagen del indicador seleccionado
            jQuery( '#'+ tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() +'TP', window.parent.document ).html( '<img src="/media/com_proyectos/images/btnLineaBase/PN/pn_verde_small.png">' );

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }else{
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
})