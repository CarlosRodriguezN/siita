jQuery( document ).ready( function(){
    
    objGestionIndicador = new GestionIndicador();
    
    var valIndTd;
    var valIndVan;
    var valIndTir;
    
    var valBDH;
    var valBDM;
    var valBDT;
    
    var valBIH;
    var valBIM;
    var valBIT;
    
    var ds;
    
    //  Verifico la existencia de informacion 
    if( jQuery( '#jform_dataIndicadores' ).val() != "null" && jQuery( '#jform_dataIndicadores' ).val().length > 0 ){
        updObjIndicador();
    }
    
    //
    //  Gestiono el cambio de informacion de un indicador Economico
    //
      jQuery( '#jform_intTasaDctoEco, #jform_intValActualNetoEco, #jform_intTIREco' ).live( 'change', function(){
        //  Seteo Informacion de Indicadores Economicos
        valIndTd    = parseInt( jQuery( '#jform_intTasaDctoEco' ).val() );
        valIndVan   = unformatNumber( jQuery( '#jform_intValActualNetoEco' ).val() );
        valIndTir   = parseInt( jQuery( '#jform_intTIREco' ).val() );

        //  Actualizo Informacion de dichos Indicadores
        objGestionIndicador.updIndEconomico( valIndTd, valIndVan, valIndTir );
    });
    
//    
    //  Gestiono el cambio de informacion de un indicador Financiero
    //
    jQuery( '#jform_intTasaDctoFin, #jform_intValActualNetoFin, #jform_intTIRFin' ).live( 'change', function(){ 
        valIndTd = jQuery( '#jform_intTasaDctoFin' ).val();
        valIndVan = unformatNumber( jQuery( '#jform_intValActualNetoFin' ).val() );
        valIndTir = jQuery( '#jform_intTIRFin' ).val();

        objGestionIndicador.updIndFinanciero( valIndTd, valIndVan, valIndTir );
    });
    
    //
    //  Gestiono el cambio de informacion de un indicador Beneficiario Directo
    //
    jQuery( '#jform_intBenfDirectoHombre, #jform_intBenfDirectoMujer, #jform_intTotalBenfDirectos' ).live( 'change', function(){
        valBDH = jQuery( '#jform_intBenfDirectoHombre' ).val();
        valBDM = jQuery( '#jform_intBenfDirectoMujer' ).val();
        valBDT = jQuery( '#jform_intTotalBenfDirectos' ).val();

        objGestionIndicador.updIndBDirecto( valBDH, valBDM, valBDT );
    });
    
    //
    //  Gestiono el cambio de informacion de un indicador Beneficiario Indirecto
    //
    jQuery( '#jform_intBenfIndDirectoHombre, #jform_intBenfIndDirectoMujer, #jform_intTotalBenfIndDirectos' ).live( 'change', function(){ 
        valBIH = jQuery( '#jform_intBenfIndDirectoHombre' ).val();
        valBIM = jQuery( '#jform_intBenfIndDirectoMujer' ).val();
        valBIT = jQuery( '#jform_intTotalBenfIndDirectos' ).val();

        objGestionIndicador.updIndBIndirecto( valBIH, valBIM, valBIT );
    });
  
    /**
     * 
     * Gestiono Informacion de indicadores
     * 
     * @returns {undefined}
     */
    function updObjIndicador()
    {
        //  Actualizo informacion de Indicadores Economicos
        if( objGestionIndicador.indEconomico != null ){
            updIndEconomicos( objGestionIndicador.indEconomico );
        }

        //  Actualizo informacion de Indicadores Financieros
        if( objGestionIndicador.indFinanciero != null ){
            updIndFinancieros( objGestionIndicador.indFinanciero );            
        }
        
        //  Actualizo informacion de Indicadores Beneficiarios Directos
        if( objGestionIndicador.indBDirecto != null ){
            updIndBDirectos( objGestionIndicador.indBDirecto );
        }
        
        //  Actualizo informacion de Indicadores Beneficiarios Indirectos        
        if( objGestionIndicador.indBIndirecto != null ){
            updIndBIndirectos( objGestionIndicador.indBIndirecto );
        }

        //  Actualizo informacion de Indicadores GAP
        if( objGestionIndicador.lstGAP !== null ){
            for( var x = 0; x < objGestionIndicador.lstGAP.length; x++ ){
                var indGap = objGestionIndicador.lstGAP[x];
                jQuery('#tbLstGAP > tbody:last').append( objGestionIndicador.addFilaIndicadorGAP( indGap, 0 ) );
            }
        }

        //  Actualizo informacion de Indicadores Enfoque de Igualdad
        if( objGestionIndicador.lstEnfIgualdad !== null ){
            for( var x = 0; x < objGestionIndicador.lstEnfIgualdad.length; x++ ){
                var indEI = objGestionIndicador.lstEnfIgualdad[x];
                var enfoque = indEI.eiMasculino.enfoque;
                var dimension = indEI.eiMasculino.nombreIndicador;
                
                jQuery('#lstEnfIgu > tbody:last').append( objGestionIndicador.addFilaIndEIgualdad( enfoque, dimension, indEI, 0 ) );
            }
        }

        //  Actualizo informacion de Indicadores Enfoque de Ecorae
        if( objGestionIndicador.lstEnfEcorae !== null ){
            for( var x = 0; x < objGestionIndicador.lstEnfEcorae.length; x++ ){
                var indEE = objGestionIndicador.lstEnfEcorae[x];
                var dimension = indEE.eeMasculino.nombreIndicador;

                jQuery('#lstEnfEco > tbody:last').append( objGestionIndicador.addFilaIndEEcorae( dimension, indEE, 0 ) );                
            }
        }
        
        //  Actualizo informacion de Otros Indicadores
        if( objGestionIndicador.lstOtrosIndicadores !== null ){
            updOtrosIndicadores( objGestionIndicador.lstOtrosIndicadores );
        }

        return;

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

            //  De Acuerdo al modelo del indicador ( Tasa de Descuento, Valor Actual Neto, etc ), 
            //  lo asigno al indicador Economico
            switch( indEconomico[x].modeloIndicador ){
                case 'td':
                    ds = getDtaSemaforizacion( objGestionIndicador.indEconomico[0].semaforoImagen() );
                    
                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#ECO' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'"style="'+ ds["msgStyle"] +'">');
                break;

                case 'van': 
                    ds = getDtaSemaforizacion( objGestionIndicador.indEconomico[1].semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#ECO' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;

                case 'tir':
                    ds = getDtaSemaforizacion( objGestionIndicador.indEconomico[2].semaforoImagen() );

                    //  Cambio la imagen del indicador seleccionado
                    jQuery('#ECO' + x + 'AI' ).html('<img src="'+ ds["imgAtributo"] +'" class="hasTip" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">');
                break;
            }
            
        }
        
        return false;
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
     * Gestiono Informacion de indicadores Enfoque de Igualdad
     * 
     * @param {type} ei         Enfoque de Igualdad
     * 
     * @returns {Indicador}
     * 
     */
    function updIndEI( ei )
    {
        //  Creo Objeto Indicador
        var indicador = new Indicador();

        //  Seteo los valores correspondientes al indicador
        indicador.setDtaIndicador( ei );
        
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
        //  Creo Objeto Indicador
        var indicador = new Indicador();

        //  Seteo los valores correspondientes al indicador
        indicador.setDtaIndicador( ee );
        
        return indicador;
    }
    
    /**
     * 
     * Gestiono informacion de otros indicadores
     * 
     * @param {array} lstOtrosInd   Lista con informacion de otros indicadores
     * @returns {undefined}
     */
    function updOtrosIndicadores( lstOtrosInd )
    {
        for( var x = 0; x < lstOtrosInd.length; x++ ){
            //  Creo Objeto Indicador
            var indicador = new Indicador();
            lstOtrosInd[x].idRegIndicador = x;

            //  Seteo los valores correspondientes al indicador
            indicador.setDtaIndicador( lstOtrosInd[x] );
            
            //  Agrego la fila creada a la tabla
            jQuery( '#lstOtrosInd > tbody:last', window.parent.document ).append( objGestionIndicador.addFilaOtroIndicador( indicador, 0 ) );
        }
    }
})