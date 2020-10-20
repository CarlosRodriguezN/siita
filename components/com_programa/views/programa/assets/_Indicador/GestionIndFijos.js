jQuery( document ).ready( function(){
    var ds;
    objGestionIndicador = new GestionIndicador();
    
    //  Verifico la existencia de informacion 
    if( jQuery( '#jform_dataIndicadores' ).val().length > 0 ){
        updObjIndicador( jQuery( '#jform_dataIndicadores' ).val() );
    }
    
    //
    //  Gestiono el cambio de informacion de un indicador Economico
    //
    jQuery( '#jform_intTasaDctoEco, #jform_intValActualNetoEco, #jform_intTIREco' ).live( 'change', function(){
        
        if( objGestionIndicador.existenIndsEconomicos() == 0 ){
            //  Indicador Economico - Tasa de Descuento - TD
            var objIndTd = new Indicador( 0, 1, 'td', jQuery( '#jform_intTasaDctoEco-lbl' )[0].innerText, jQuery( '#jform_intTasaDctoEco' ).val() );
            objIndTd.idClaseIndicador = 4;
            objIndTd.idUndAnalisis = 14;
            objIndTd.idTpoUndMedida = 2;
            objIndTd.idUndMedida = 7;

            //  Indicador Economico - Valor Actual Neto - VAN
            var objIndVan = new Indicador( 0, 2, 'van', jQuery( '#jform_intValActualNetoEco-lbl' )[0].innerText, jQuery( '#jform_intValActualNetoEco' ).val() );
            objIndVan.idClaseIndicador = 4;
            objIndVan.idUndAnalisis = 15;
            objIndVan.idTpoUndMedida = 5;
            objIndVan.idUndMedida = 17;            
            
            //  Indicador Economico - Tasa Interna de Retorno - TIR
            var objIndTir = new Indicador( 0, 3, 'tir', jQuery( '#jform_intTIREco-lbl' )[0].innerText, jQuery( '#jform_intTIREco' ).val() );
            objIndTir.idClaseIndicador = 4;
            objIndTir.idUndAnalisis = 16;
            objIndTir.idTpoUndMedida = 2;
            objIndTir.idUndMedida = 7;            

            objGestionIndicador.addIndEconomico( objIndTd, objIndVan, objIndTir );
        }else{
            var valIndTd = jQuery( '#jform_intTasaDctoEco' ).val();
            var valIndVan = jQuery( '#jform_intValActualNetoEco' ).val();
            var valIndTir = jQuery( '#jform_intTIREco' ).val();
            
            objGestionIndicador.updIndEconomico( valIndTd, valIndVan, valIndTir );
        }

    });
    
    //
    //  Gestiono el cambio de informacion de un indicador Financiero
    //
    jQuery( '#jform_intTasaDctoFin, #jform_intValActualNetoFin, #jform_intTIRFin' ).live( 'change', function(){ 
        if( objGestionIndicador.existenIndsFinancieros() == 0 ){
            //  Indicador Financiero - Tasa de Descuento - TD
            var objIndTd = new Indicador( 0, 4, jQuery( '#jform_intTasaDctoFin-lbl' )[0].innerText, 'td', jQuery( '#jform_intTasaDctoFin' ).val(), '' );
            objIndTd.idClaseIndicador = 3;
            objIndTd.idUndAnalisis = 14;
            objIndTd.idTpoUndMedida = 2;
            objIndTd.idUndMedida = 7;
            
            //  Indicador Financiero - Valor Actual Neto - VAN
            var objIndVan = new Indicador( 0, 5, jQuery( '#jform_intValActualNetoFin-lbl' )[0].innerText, 'van', jQuery( '#jform_intValActualNetoFin' ).val(), '' );
            objIndVan.idClaseIndicador = 3;
            objIndVan.idUndAnalisis = 15;
            objIndVan.idTpoUndMedida = 5;
            objIndVan.idUndMedida = 17;
            
            //  Indicador Financiero - Tasa Interna de Retorno - TIR
            var objIndTir = new Indicador( 0, 6, jQuery( '#jform_intTIRFin-lbl' )[0].innerText, 'tir', jQuery( '#jform_intTIRFin' ).val(), '' );
            objIndTir.idClaseIndicador = 3;
            objIndTir.idUndAnalisis = 16;
            objIndTir.idTpoUndMedida = 2;
            objIndTir.idUndMedida = 7;

            objGestionIndicador.addIndFinanciero( objIndTd, objIndVan, objIndTir );
        }else{
            var valIndTd = jQuery( '#jform_intTasaDctoFin' ).val();
            var valIndVan = jQuery( '#jform_intValActualNetoFin' ).val();
            var valIndTir = jQuery( '#jform_intTIRFin' ).val();
            
            objGestionIndicador.updIndFinanciero( valIndTd, valIndVan, valIndTir );
        }
    });
    
    //
    //  Gestiono el cambio de informacion de un indicador Beneficiario Directo
    //
    jQuery( '#jform_intBenfDirectoHombre, #jform_intBenfDirectoMujer, #jform_intTotalBenfDirectos' ).live( 'change', function(){
        if( objGestionIndicador.existenIndsBDirectos() == 0 ){
            var objBDH = new Indicador( 0, 7, jQuery( '#jform_intBenfDirectoHombre-lbl' )[0].innerText, 'bh', jQuery( '#jform_intBenfDirectoHombre' ).val(), '' );
            objBDH.idClaseIndicador = 1;
            objBDH.idUndAnalisis = 6;
            objBDH.idTpoUndMedida = 2;
            objBDH.idUndMedida = 6;
            
            var objBDM = new Indicador( 0, 8, jQuery( '#jform_intBenfDirectoMujer-lbl' )[0].innerText, 'bm', jQuery( '#jform_intBenfDirectoMujer' ).val(), '' );
            objBDM.idClaseIndicador = 1;
            objBDM.idUndAnalisis = 7;
            objBDM.idTpoUndMedida = 2;
            objBDM.idUndMedida = 6;
            
            var objBDT = new Indicador( 0, 9, jQuery( '#jform_intTotalBenfDirectos-lbl' )[0].innerText, 'b', jQuery( '#jform_intTotalBenfDirectos' ).val(), '' );
            objBDT.idClaseIndicador = 1;
            objBDT.idUndAnalisis = 4;
            objBDT.idTpoUndMedida = 2;
            objBDT.idUndMedida = 6;

            objGestionIndicador.addIndBeneficiariosDirecto( objBDH, objBDM, objBDT );
        }else{
            var valBDH = jQuery( '#jform_intBenfDirectoHombre' ).val();
            var valBDM = jQuery( '#jform_intBenfDirectoMujer' ).val();
            var valBDT = jQuery( '#jform_intTotalBenfDirectos' ).val();
            
            objGestionIndicador.updIndBDirecto( valBDH, valBDM, valBDT );
        }
    });
    
    //
    //  Gestiono el cambio de informacion de un indicador Beneficiario Indirecto
    //
    jQuery( '#jform_intBenfIndDirectoHombre, #jform_intBenfIndDirectoMujer, #jform_intTotalBenfIndDirectos' ).live( 'change', function(){ 
        
        if( objGestionIndicador.existenIndsBIndirectos() == 0 ){
            
            var objBIH = new Indicador( 0, 10, jQuery( '#jform_intBenfIndDirectoHombre-lbl' )[0].innerText, 'bh', jQuery( '#jform_intBenfIndDirectoHombre' ).val(), '' );
            objBIH.idClaseIndicador = 1;
            objBIH.idUndAnalisis = 6;
            objBIH.idTpoUndMedida = 2;
            objBIH.idUndMedida = 6;
            
            var objBIM = new Indicador( 0, 11, jQuery( '#jform_intBenfIndDirectoMujer-lbl' )[0].innerText, 'bm', jQuery( '#jform_intBenfIndDirectoMujer' ).val(), '' );
            objBIM.idClaseIndicador = 1;
            objBIM.idUndAnalisis = 7;
            objBIM.idTpoUndMedida = 2;
            objBIM.idUndMedida = 6;
            
            var objBIT = new Indicador( 0, 12, jQuery( '#jform_intTotalBenfIndDirectos-lbl' )[0].innerText, 'b', jQuery( '#jform_intTotalBenfIndDirectos' ).val(), '' );
            objBIT.idClaseIndicador = 1;
            objBIT.idUndAnalisis = 4;
            objBIT.idTpoUndMedida = 2;
            objBIT.idUndMedida = 6;

            objGestionIndicador.addIndBeneficiariosInDirecto( objBIH, objBIM, objBIT );
        }else{
            var valBIH = jQuery( '#jform_intBenfIndDirectoHombre' ).val();
            var valBIM = jQuery( '#jform_intBenfIndDirectoMujer' ).val();
            var valBIT = jQuery( '#jform_intTotalBenfIndDirectos' ).val();

            objGestionIndicador.updIndBIndirecto( valBIH, valBIM, valBIT );
        }
    });
  
    /**
     * 
     * Actualizo informacion de objetos de gestion de indicadores
     * 
     * @param {JSon} dtaIndicador   Datos de indicadores
     * 
     * @returns {undefined}
     * 
     */
    function updObjIndicador( dtaIndicador )
    {
        var objIndicador = eval("(" + dtaIndicador + ")");

        //  Actualizo informacion de Indicadores Economicos        
        if( objIndicador.indEconomicos != null ){
            updIndEconomicos( objIndicador.indEconomicos );
        }

        //  Actualizo informacion de Indicadores Financieros
        if( objIndicador.indFinancieros != null ){
            updIndFinancieros( objIndicador.indFinancieros );            
        }
        
        //  Actualizo informacion de Indicadores Beneficiarios Directos
        if( objIndicador.indBDirectos != null ){
            updIndBDirectos( objIndicador.indBDirectos );
        }
        
        //  Actualizo informacion de Indicadores Beneficiarios Indirectos        
        if( objIndicador.indBIndirectos != null ){
            updIndBIndirectos( objIndicador.indBIndirectos );
        }

        //  Actualizo informacion de Indicadores GAP
        if( objIndicador.lstGAP != null ){
            for( var x = 0; x < objIndicador.lstGAP.length; x++ ){
                var indGap = [];

                indGap["idRegGap"] = x;
                indGap["gapMasculino"] = updIndGap( objIndicador.lstGAP[x].gapMasculino );
                indGap["gapFemenino"] = updIndGap( objIndicador.lstGAP[x].gapFemenino );
                indGap["gapTotal"] = updIndGap( objIndicador.lstGAP[x].gapTotal );

                objGestionIndicador.lstGAP.push( indGap );
                
                jQuery('#tbLstGAP > tbody:last').append( objGestionIndicador.addFilaIndicadorGAP( indGap, 0 ) );
            }
        }

        //  Actualizo informacion de Indicadores Enfoque de Igualdad
        if( objIndicador.lstEnfIgualdad != null ){
            for( var x = 0; x < objIndicador.lstEnfIgualdad.length; x++ ){
                var indEI = [];

                indEI["idRegEI"] = x;
                indEI["eiMasculino"] = updIndEI( objIndicador.lstEnfIgualdad[x].eiMasculino );
                var enfoque = objIndicador.lstEnfIgualdad[x].eiMasculino.enfoque;
                var dimension = objIndicador.lstEnfIgualdad[x].eiMasculino.nombreIndicador;
                
                indEI["eiFemenino"] = updIndEI( objIndicador.lstEnfIgualdad[x].eiFemenino );
                indEI["eiTotal"] = updIndEI( objIndicador.lstEnfIgualdad[x].eiTotal );

                objGestionIndicador.lstEnfIgualdad.push( indEI );
                
                jQuery('#lstEnfIgu > tbody:last').append( objGestionIndicador.addFilaIndEIgualdad( enfoque, dimension, indEI, 0 ) );
            }
        }

        //  Actualizo informacion de Indicadores Enfoque de Ecorae
        if( objIndicador.lstEnfEcorae != null ){
            for( var x = 0; x < objIndicador.lstEnfEcorae.length; x++ ){
                var indEE = [];

                indEE["idRegEE"] = x;
                indEE["eeMasculino"] = updIndEE( objIndicador.lstEnfEcorae[x].eeMasculino );
                var dimension = objIndicador.lstEnfEcorae[x].eeMasculino.nombreIndicador;
                
                indEE["eeFemenino"] = updIndEE( objIndicador.lstEnfEcorae[x].eeFemenino );
                indEE["eeTotal"] = updIndEE( objIndicador.lstEnfEcorae[x].eeTotal );

                objGestionIndicador.lstEnfEcorae.push( indEE );
                
                jQuery('#lstEnfEco > tbody:last').append( objGestionIndicador.addFilaIndEEcorae( dimension, indEE, 0 ) );                
            }
        }
        
        //  Actualizo informacion de Otros Indicadores
        if( objIndicador.lstOtrosIndicadores != null ){
            updOtrosIndicadores( objIndicador.lstOtrosIndicadores );
        }
    }
    
    
    function getDtaSemaforizacion( banIndicador )
    {
        var dtaSemaforizacion = new Array();

        switch( banIndicador ){
            //  Rojo
            case 0: 
                dtaSemaforizacion["imgAtributo"] = COM_PROGRAMA_RG_ATRIBUTO;
                dtaSemaforizacion["msgAtributo"] = COM_PROGRAMA_TITLE_RG_ATRIBUTO;
            break;

            //  Amarillo
            case 1: 
                dtaSemaforizacion["imgAtributo"]= COM_PROGRAMA_AM_ATRIBUTO;
                dtaSemaforizacion["msgAtributo"]= COM_PROGRAMA_TITLE_AM_ATRIBUTO;
                dtaSemaforizacion["msgStyle"]   = COM_PROGRAMA_STYLE_AM;
                
            break;

            //  Verde
            case 2: 
                dtaSemaforizacion["imgAtributo"]= COM_PROGRAMA_VD_ATRIBUTO;
                dtaSemaforizacion["msgAtributo"]= COM_PROGRAMA_TITLE_VD_ATRIBUTO;
            break;
        }
        
        return dtaSemaforizacion;
    }
    
    
    
    /**
     * 
     * Actualizo informacion de objetos de gestion de indicadores Economicos
     * 
     * @param {Object} indEconomico   Indicadores economicos
     * 
     * @returns {undefined}
     * 
     */
    function updIndEconomicos( indEconomico )
    {
        //  Agrego unidades territoriales
        for( var x = 0; x < indEconomico.length; x++ ){
            //  Creo Objeto Indicador
            var indicador = new Indicador();

            //  Seteo Informacion del Indicador
            indicador.setDtaIndicador( indEconomico[x] );

            //  De Acuerdo al modelo del indicador ( Tasa de Descuento, Valor Actual Neto, etc ), 
            //  lo asigno al indicador Economico
            switch( indEconomico[x].modeloIndicador ){
                case 'td':
                    objGestionIndicador.indEconomico[0] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );
                    
                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#ECO' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'"style="'+ ds["msgStyle"] +'">');
                break;

                case 'van': 
                    objGestionIndicador.indEconomico[1] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#ECO' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'tir':
                    objGestionIndicador.indEconomico[2] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#ECO' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;
            }
            
        }
    }
    
    /**
     * 
     * Actualizo informacion de objetos de gestion de Indicadores Financieros
     * 
     * @param {Object} indFinanciero
     * 
     * @returns {undefined}
     * 
     */
    function updIndFinancieros( indFinanciero )
    {
         //  Recorro Informacion de Indicadores Financieros
        for( var x = 0; x < indFinanciero.length; x++ ){
            //  Creo Objeto Indicador
            var indicador = new Indicador();
            
            //  Seteo los valores correspondientes al indicador
            indicador.setDtaIndicador( indFinanciero[x] );

            //  De Acuerdo al modelo del indicador ( Tasa de Descuento, Valor Actual Neto, etc ), 
            //  lo asigno al indicador Financiero
            switch( indFinanciero[x].modeloIndicador ){
                case 'td':
                    objGestionIndicador.indFinanciero[0] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );
                    
                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#FIN' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'van': 
                    objGestionIndicador.indFinanciero[1] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#FIN' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'tir': 
                    objGestionIndicador.indFinanciero[2] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#FIN' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;
            }
        }
    }

    /**
     * 
     * Actualizo informacion de objetos de gestion de Indicadores Beneficiarios Directos
     * 
     * @param {Object} indBDirectos
     * 
     * @returns {undefined}
     * 
     */
    function updIndBDirectos( indBDirectos )
    {
       //  Recorro Informacion de Indicadores Financieros
        for( var x = 0; x < indBDirectos.length; x++ ){
            //  Creo Objeto Indicador
            var indicador = new Indicador();

            //  Seteo los valores correspondientes al indicador
            indicador.setDtaIndicador( indBDirectos[x] );

            //  De Acuerdo al modelo del indicador ( Tasa de Descuento, Valor Actual Neto, etc ), 
            //  lo asigno al indicador Beneficiarios - Directos
            switch( indBDirectos[x].modeloIndicador ){
                case 'bh':
                    objGestionIndicador.indBDirecto[0] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#BD' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'bm': 
                    objGestionIndicador.indBDirecto[1] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );
                    
                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#BD' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'b': 
                    objGestionIndicador.indBDirecto[2] =  indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#BD' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;
            }
        }
    }
    
    /**
     * 
     * Actualizo informacion de objetos de gestion de Indicadores Beneficiarios Indirectos
     * 
     * @param {Object} indBIndirectos
     * 
     * @returns {undefined}
     * 
     */
    function updIndBIndirectos( indBIndirectos )
    {
        //  Recorro Informacion de Indicadores Beneficiarios Indirectos
        for( var x = 0; x < indBIndirectos.length; x++ ){
            //  Creo Objeto Indicador
            var indicador = new Indicador();

            //  Seteo los valores correspondientes al indicador
            indicador.setDtaIndicador( indBIndirectos[x] );

            //  De Acuerdo al modelo del indicador ( Tasa de Descuento, Valor Actual Neto, etc ), 
            //  lo asigno al indicador Economico
            switch( indBIndirectos[x].modeloIndicador ){
                case 'bh':
                    objGestionIndicador.indBIndirecto[0] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );
                    
                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#BI' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'bm': 
                    objGestionIndicador.indBIndirecto[1] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#BI' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'b': 
                    objGestionIndicador.indBIndirecto[2] = indicador;
                    ds = getDtaSemaforizacion( indicador.semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#BI' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;
            }
        }
    }

    /**
     * 
     * Gestiono informacion de indicadores GAP
     * 
     * @param {type} lstIndGap  Lista de informacion de indicadores GAP
     * 
     * @returns {undefined}
     * 
     */
    function updIndGap( gap )
    {
        //  Recorro Informacion de Indicadores Beneficiarios Indirectos
        var indicador = new Indicador(  gap.idIndEntidad, 
                                        gap.idIndicador, 
                                        gap.nombreIndicador, 
                                        gap.modeloIndicador, 
                                        gap.umbral );

        //  Asigno informacion complementaria del indicador
        indicador.tendencia = gap.tendencia;
        indicador.descripcion = gap.descripcion;
        indicador.idUndAnalisis = gap.idUndAnalisis;
        indicador.idTpoUndMedida = gap.idTpoUndMedida;
        indicador.idUndMedida = gap.idUndMedida;
        indicador.undMedida = gap.undMedida;
        indicador.tpoIndicador = gap.idTpoIndicador;
        indicador.formula = gap.formula;
        indicador.fchHorzMimimo = gap.fchHorzMimimo;
        indicador.fchHorzMaximo = gap.fchHorzMaximo;
        indicador.umbMinimo = gap.umbMinimo;
        indicador.umbMaximo = gap.umbMaximo;
        indicador.idClaseIndicador = gap.idClaseIndicador;
        indicador.idFrcMonitoreo = gap.idFrcMonitoreo;
        indicador.idUGResponsable = gap.idUGResponsable;
        indicador.idResponsableUG = gap.idResponsableUG;
        indicador.idResponsable = gap.idResponsable;
        indicador.idDimension = gap.idDimension;
        indicador.idEnfoque = gap.idEnfoque;
        indicador.enfoque = gap.enfoque;
        

        //  Verifico si el indicador esta asociadas a unidades Territoriales
        if( gap.lstUndTerritorial != null && gap.lstUndTerritorial.length > 0 ){
            for( var y = 0; y < gap.lstUndTerritorial.length; y++ ){
                indicador.lstUndsTerritoriales.push( gap.lstUndTerritorial[y] );
            }
        }

        //  Verifico si el indicador tiene "Lineas Base" asociadas
        if( gap.lstLineaBase != null && gap.lstLineaBase.length > 0 ){
            for( var y = 0; y < gap.lstLineaBase.length; y++ ){
                indicador.lstLineaBase.push( gap.lstLineaBase[y] );
            }
        }

        //  Verifico si el indicador tiene "Rangos" asociadas
        if( gap.lstRangos != null && gap.lstRangos.length > 0 ){
            for( var y = 0; y < gap.lstRangos.length; y++ ){
                indicador.lstRangos.push( gap.lstRangos[y] );
            }
        }

        //  Verifico si el indicador tiene asociadas variables
        if( gap.lstVariables != null && gap.lstVariables.length > 0 ){
            for( var y = 0; y < gap.lstVariables.length; y++ ){
                indicador.lstVariables.push( gap.lstVariables[y] );
            }
        }

        //  Verifico si el indicador tiene asociada una planificacion
        if( gap.lstPlanificacion != null && gap.lstPlanificacion.length > 0 ){
            indicador.lstPlanificacion = gap.lstPlanificacion;
        }

        return indicador;
    }
    
    /**
     * 
     * Gestiono Informacion de indicadores Enfoque de Igualdad
     * 
     * @param {type} ei         Enfoque de Igualdad
     * 
     * @returns {Indicador}
     * 
     */
    function updIndEI( ei )
    {
        //  Recorro Informacion de Indicadores Beneficiarios Indirectos
        var indicador = new Indicador(  ei.idIndEntidad, 
                                        ei.idIndicador, 
                                        ei.nombreIndicador, 
                                        ei.modeloIndicador, 
                                        ei.umbral );

        //  Asigno informacion complementaria del indicador
        indicador.tendencia = ei.tendencia;
        indicador.descripcion = ei.descripcion;
        indicador.idUndAnalisis = ei.idUndAnalisis;
        indicador.idTpoUndMedida = ei.idTpoUndMedida;
        indicador.idUndMedida = ei.idUndMedida;
        indicador.undMedida = ei.undMedida;
        indicador.tpoIndicador = ei.idTpoIndicador;
        indicador.formula = ei.formula;
        indicador.fchHorzMimimo = ei.fchHorzMimimo;
        indicador.fchHorzMaximo = ei.fchHorzMaximo;
        indicador.umbMinimo = ei.umbMinimo;
        indicador.umbMaximo = ei.umbMaximo;
        indicador.idClaseIndicador = ei.idClaseIndicador;
        indicador.idFrcMonitoreo = ei.idFrcMonitoreo;
        indicador.idUGResponsable = ei.idUGResponsable;
        indicador.idResponsableUG = ei.idResponsableUG;
        indicador.idResponsable = ei.idResponsable;
        indicador.idDimension = ei.idDimension;
        indicador.idEnfoque = ei.idEnfoque;
        indicador.enfoque = ei.enfoque;

        //  Verifico si el indicador esta asociadas a unidades Territoriales
        if( ei.lstUndTerritorial != null && ei.lstUndTerritorial.length > 0 ){
            for( var y = 0; y < ei.lstUndTerritorial.length; y++ ){
                indicador.lstUndsTerritoriales.push( ei.lstUndTerritorial[y] );
            }
        }

        //  Verifico si el indicador tiene "Lineas Base" asociadas
        if( ei.lstLineaBase != null && ei.lstLineaBase.length > 0 ){
            for( var y = 0; y < ei.lstLineaBase.length; y++ ){
                indicador.lstLineaBase.push( ei.lstLineaBase[y] );
            }
        }

        //  Verifico si el indicador tiene "Rangos" asociadas
        if( ei.lstRangos != null && ei.lstRangos.length > 0 ){
            for( var y = 0; y < ei.lstRangos.length; y++ ){
                indicador.lstRangos.push( ei.lstRangos[y] );
            }
        }

        //  Verifico si el indicador tiene asociadas variables
        if( ei.lstVariables != null && ei.lstVariables.length > 0 ){
            for( var y = 0; y < ei.lstVariables.length; y++ ){
                indicador.lstVariables.push( ei.lstVariables[y] );
            }
        }

        //  Verifico si el indicador tiene asociada una planificacion
        if( ei.lstPlanificacion != null && ei.lstPlanificacion.length > 0 ){
            indicador.lstPlanificacion = ei.lstPlanificacion;
        }

        return indicador;
    }
    
    /**
     * 
     * Gestiono informacion de indicadores Enfoque de Igualdad
     * 
     * @param {type} ee  Lista de informacion de indicadores GAP
     * 
     * @returns {undefined}
     * 
     */
    function updIndEE( ee )
    {
        //  Recorro Informacion de Indicadores Beneficiarios Indirectos
        var indicador = new Indicador(  ee.idIndEntidad, 
                                        ee.idIndicador, 
                                        ee.nombreIndicador, 
                                        ee.modeloIndicador, 
                                        ee.umbral );

        //  Asigno informacion complementaria del indicador
        indicador.tendencia = ee.tendencia;
        indicador.descripcion = ee.descripcion;
        indicador.idUndAnalisis = ee.idUndAnalisis;
        indicador.idTpoUndMedida = ee.idTpoUndMedida;
        indicador.idUndMedida = ee.idUndMedida;
        indicador.undMedida = ee.undMedida;
        indicador.tpoIndicador = ee.idTpoIndicador;
        indicador.formula = ee.formula;
        indicador.fchHorzMimimo = ee.fchHorzMimimo;
        indicador.fchHorzMaximo = ee.fchHorzMaximo;
        indicador.umbMinimo = ee.umbMinimo;
        indicador.umbMaximo = ee.umbMaximo;
        indicador.idClaseIndicador = ee.idClaseIndicador;
        indicador.idFrcMonitoreo = ee.idFrcMonitoreo;
        indicador.idUGResponsable = ee.idUGResponsable;
        indicador.idResponsableUG = ee.idResponsableUG;
        indicador.idResponsable = ee.idResponsable;
        indicador.idDimension = ee.idDimension;
        indicador.idEnfoque = ee.idEnfoque;
        indicador.enfoque = ee.enfoque;

        //  Verifico si el indicador esta asociadas a unidades Territoriales
        if( ee.lstUndTerritorial != null && ee.lstUndTerritorial.length > 0 ){
            for( var y = 0; y < ee.lstUndTerritorial.length; y++ ){
                indicador.lstUndsTerritoriales.push( ee.lstUndTerritorial[y] );
            }
        }

        //  Verifico si el indicador tiene "Lineas Base" asociadas
        if( ee.lstLineaBase != null && ee.lstLineaBase.length > 0 ){
            for( var y = 0; y < ee.lstLineaBase.length; y++ ){
                indicador.lstLineaBase.push( ee.lstLineaBase[y] );
            }
        }

        //  Verifico si el indicador tiene "Rangos" asociadas
        if( ee.lstRangos != null && ee.lstRangos.length > 0 ){
            for( var y = 0; y < ee.lstRangos.length; y++ ){
                indicador.lstRangos.push( ee.lstRangos[y] );
            }
        }

        //  Verifico si el indicador tiene asociadas variables
        if( ee.lstVariables != null && ee.lstVariables.length > 0 ){
            for( var y = 0; y < ee.lstVariables.length; y++ ){
                indicador.lstVariables.push( ee.lstVariables[y] );
            }
        }

        //  Verifico si el indicador tiene asociada una planificacion
        if( ee.lstPlanificacion != null && ee.lstPlanificacion.length > 0 ){
            indicador.lstPlanificacion = ee.lstPlanificacion;
        }

        return indicador;
    }
    
    /**
     * 
     * Gestiono informacion de otros indicadores
     * 
     * @param {Object} otrosInd     Objeto con informacion de indicadores
     * @returns {undefined}
     * 
     */
    function updOtrosIndicadores( lstOtrosInd )
    {
        for( var x = 0; x < lstOtrosInd.length; x++ ){
            var indicador = new Indicador(  lstOtrosInd[x].idIndEntidad, 
                                            lstOtrosInd[x].idIndicador, 
                                            lstOtrosInd[x].nombreIndicador, 
                                            lstOtrosInd[x].modeloIndicador, 
                                            lstOtrosInd[x].umbral );

            //  Asigno informacion complementaria del indicador
            indicador.idRegIndicador = x;
            indicador.tendencia = lstOtrosInd[x].tendencia;
            indicador.descripcion = lstOtrosInd[x].descripcion;
            indicador.idUndAnalisis = lstOtrosInd[x].idUndAnalisis;
            indicador.idTpoUndMedida = lstOtrosInd[x].idTpoUndMedida;
            indicador.idUndMedida = lstOtrosInd[x].idUndMedida;
            indicador.undMedida = lstOtrosInd[x].undMedida;
            indicador.tpoIndicador = lstOtrosInd[x].idTpoIndicador;
            indicador.formula = lstOtrosInd[x].formula;
            indicador.fchHorzMimimo = lstOtrosInd[x].fchHorzMimimo;
            indicador.fchHorzMaximo = lstOtrosInd[x].fchHorzMaximo;
            indicador.umbMinimo = lstOtrosInd[x].umbMinimo;
            indicador.umbMaximo = lstOtrosInd[x].umbMaximo;
            indicador.idClaseIndicador = lstOtrosInd[x].idClaseIndicador;
            indicador.idFrcMonitoreo = lstOtrosInd[x].idFrcMonitoreo;
            indicador.idUGResponsable = lstOtrosInd[x].idUGResponsable;
            indicador.idResponsableUG = lstOtrosInd[x].idResponsableUG;
            indicador.idResponsable = lstOtrosInd[x].idResponsable;
            
            //  Verifico si el indicador esta asociadas a unidades Territoriales
            if( lstOtrosInd[x].lstUndTerritorial != null && lstOtrosInd[x].lstUndTerritorial.length > 0 ){
                for( var y = 0; y < lstOtrosInd[x].lstUndTerritorial.length; y++ ){
                    indicador.lstUndsTerritoriales.push( lstOtrosInd[x].lstUndTerritorial[y] );
                }
            }
            
            //  Verifico si el indicador tiene "Lineas Base" asociadas
            if( lstOtrosInd[x].lstLineaBase != null && lstOtrosInd[x].lstLineaBase.length > 0 ){
                for( var y = 0; y < lstOtrosInd[x].lstLineaBase.length; y++ ){
                    indicador.lstLineaBase.push( lstOtrosInd[x].lstLineaBase[y] );
                }
            }
            
            //  Verifico si el indicador tiene "Rangos" asociadas
            if( lstOtrosInd[x].lstRangos != null && lstOtrosInd[x].lstRangos.length > 0 ){
                for( var y = 0; y < lstOtrosInd[x].lstRangos.length; y++ ){
                    indicador.lstRangos.push( lstOtrosInd[x].lstRangos[y] );
                }
            }
            
            //  Verifico si el indicador tiene asociadas variables
            if( lstOtrosInd[x].lstVariables != null && lstOtrosInd[x].lstVariables.length > 0 ){
                for( var y = 0; y < lstOtrosInd[x].lstVariables.length; y++ ){
                    indicador.lstVariables.push( lstOtrosInd[x].lstVariables[y] );
                }
            }
            
            //  Verifico si el indicador tiene Dimensiones asociadoas
            if( lstOtrosInd[x].lstDimensiones != null && lstOtrosInd[x].lstDimensiones.length > 0 ){
                for( var y = 0; y < lstOtrosInd[x].lstDimensiones.length; y++ ){
                    indicador.lstDimensiones.push( lstOtrosInd[x].lstDimensiones[y] );
                }
            }
            
            //  Verifico si el indicador tiene asociada una planificacion
            if( lstOtrosInd[x].lstPlanificacion != null && lstOtrosInd[x].lstPlanificacion.length > 0 ){
                indicador.lstPlanificacion = lstOtrosInd[x].lstPlanificacion;
                
                //  Cambio la imagen del indicador seleccionado
                jQuery( '#ECO'+ x +'TP' ).html( '<img src="/media/com_proyectos/images/btnLineaBase/PN/pn_verde_small.png">' );
            }
            
            objGestionIndicador.lstOtrosIndicadores[x] = indicador;
            
            //  Agrego la fila creada a la tabla
            jQuery( '#lstOtrosInd > tbody:last', window.parent.document ).append( objGestionIndicador.addFilaOtroIndicador( indicador, 0 ) );
        }
    }

})