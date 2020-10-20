jQuery(document).ready(function() {
    var dtaObjIndicador = parent.window.objGestionIndicador;
    var idIndicador = jQuery('#idIndicador').val();
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var tpo = jQuery('#tpo').val();

    lstTmpLineasBase = new Array();
    lstTmpRG = new Array();
    lstTmpUT = new Array();
    lstTmpVar = new Array();
    lstTmpDimension = new Array();

    var dtaLstLB = [];
    var dtaLstUT = [];
    var dtaLstRG = [];
    var dtaLstVar = [];
    var dtaLstDim = [];
    
    var lstTmpVariables = new Array();
    var banIdRegVar = -1;
    
    var idTpoUndMedida;
    var idUndMedida;
    var idFrcMonitoreo;
    var idUndAnalisis;
    var idUndMedida;
    var idUGResponsable;
    var idResponsableUG;
    var idResponsable;
    var idClaseIndicador;
    var idTpoIndicador;
    
    var nombreInd;
    var umbral;
    var descripcion;
    var formula;
    var idTendencia;
    var hzFchInicio;
    var hzFchFin;
    var rgValMinimo;
    var rgValMaximo;
    var tpoUndMedida;
    
    var indEconomico;
    var indFinanciero;
    var indBDirectos;
    var indBIndirecto;
    var indGAP;
    var indEI;
    var indEE;
    var indOI;

    updFrmAtributos();

    function updFrmAtributos()
    {
        //  Cargo informacion de variables acuerdo al tipo de indicador
        switch ( true ) {
            //  INDICADORES ECONOMICOS
            case ( tpoIndicador == 'eco' && ( typeof( dtaObjIndicador.indEconomico[idRegIndicador] ) != 'undefined' ) ):
                indEconomico = dtaObjIndicador.indEconomico[idRegIndicador];
                
                idTpoUndMedida  = indEconomico.idTpoUndMedida;
                idUndMedida     = indEconomico.idUndMedida;
                idUndAnalisis   = indEconomico.idUndAnalisis;
                idUGResponsable = indEconomico.idUGResponsable;
                idResponsableUG = indEconomico.idResponsableUG;
                idResponsable   = indEconomico.idResponsable;
                idClaseIndicador= indEconomico.idClaseIndicador;
                idTpoIndicador  = indEconomico.idTpoIndicador;
                idFrcMonitoreo  = indEconomico.idFrcMonitoreo;
                nombreInd       = indEconomico.nombreInd;
                umbral          = indEconomico.umbral;
                idTendencia     = indEconomico.tendencia;
                descripcion     = indEconomico.descripcion;
                formula         = indEconomico.formula;
                hzFchInicio     = indEconomico.fchHorzMimimo;
                hzFchFin        = indEconomico.fchHorzMaximo;
                rgValMinimo     = indEconomico.umbMaximo;
                rgValMaximo     = indEconomico.umbMinimo;
                tpoUndMedida    = indEconomico.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indEconomico.lstLineaBase ) != "undefined") ? indEconomico.lstLineaBase
                                                                                : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indEconomico.lstUndsTerritoriales ) != "undefined") ? indEconomico.lstUndsTerritoriales
                                                                                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indEconomico.lstRangos ) != "undefined")? indEconomico.lstRangos 
                                                                            : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indEconomico.lstVariables ) != "undefined")? indEconomico.lstVariables
                                                                                : [];

            break;
            
            //  INDICADORES FINANCIEROS
            case ( tpoIndicador == 'fin' && typeof( dtaObjIndicador.indFinanciero[idRegIndicador] ) != 'undefined' ):
                indFinanciero = dtaObjIndicador.indFinanciero[idRegIndicador];
                
                nombreInd       = indFinanciero.nombreInd;
                idTpoUndMedida  = indFinanciero.idTpoUndMedida;
                idUndMedida     = indFinanciero.idUndMedida;
                idUndAnalisis   = indFinanciero.idUndAnalisis;
                idUGResponsable = indFinanciero.idUGResponsable;
                idResponsableUG = indFinanciero.idResponsableUG;
                idResponsable   = indFinanciero.idResponsable;
                idClaseIndicador= indFinanciero.idClaseIndicador;
                idTpoIndicador  = indFinanciero.idTpoIndicador;
                idFrcMonitoreo  = indFinanciero.idFrcMonitoreo;
                nombreInd       = indFinanciero.nombreInd;
                umbral          = indFinanciero.umbral;
                idTendencia     = indFinanciero.tendencia;
                descripcion     = indFinanciero.descripcion;
                formula         = indFinanciero.formula;
                hzFchInicio     = indFinanciero.fchHorzMimimo;
                hzFchFin        = indFinanciero.fchHorzMaximo;
                rgValMinimo     = indFinanciero.umbMaximo;
                rgValMaximo     = indFinanciero.umbMinimo;
                tpoUndMedida    = indFinanciero.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indFinanciero.lstLineaBase ) != "undefined")? indFinanciero.lstLineaBase
                                                                                : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indFinanciero.lstUndsTerritoriales ) != "undefined")? indFinanciero.lstUndsTerritoriales
                                                                                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indFinanciero.lstRangos ) != "undefined")   ? indFinanciero.lstRangos 
                                                                                : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indFinanciero.lstVariables ) != "undefined")   ? indFinanciero.lstVariables
                                                                                    : [];

            break;
            
            //  BENEFICIARIOS DIRECTOS
            case ( tpoIndicador == 'bd' && typeof( dtaObjIndicador.indBDirecto[idRegIndicador] ) != 'undefined' ):
                indBDirectos = dtaObjIndicador.indBDirecto[idRegIndicador];
                
                switch (tpo) {
                    case 'm':
                        indBDirectos.idUndAnalisis = 6;
                    break;

                    case 'f':
                        indBDirectos.idUndAnalisis = 7;
                    break;

                    case 't':
                        indBDirectos.idUndAnalisis = 4;
                    break;
                }
                
                nombreInd       = indBDirectos.nombreInd;
                idTpoUndMedida  = indBDirectos.idTpoUndMedida;
                idUndMedida     = indBDirectos.idUndMedida;
                idUndAnalisis   = indBDirectos.idUndAnalisis;
                idUGResponsable = indBDirectos.idUGResponsable;
                idResponsableUG = indBDirectos.idResponsableUG;
                idResponsable   = indBDirectos.idResponsable;
                idClaseIndicador= indBDirectos.idClaseIndicador;
                idTpoIndicador  = indBDirectos.idTpoIndicador;
                idFrcMonitoreo  = indBDirectos.idFrcMonitoreo;
                nombreInd       = indBDirectos.nombreInd;
                umbral          = indBDirectos.umbral;
                idTendencia     = indBDirectos.tendencia;
                descripcion     = indBDirectos.descripcion;
                formula         = indBDirectos.formula;
                hzFchInicio     = indBDirectos.fchHorzMimimo;
                hzFchFin        = indBDirectos.fchHorzMaximo;
                rgValMinimo     = indBDirectos.umbMaximo;
                rgValMaximo     = indBDirectos.umbMinimo;
                tpoUndMedida    = indBDirectos.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indBDirectos.lstLineaBase ) != "undefined") ? indBDirectos.lstLineaBase
                                                                                : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indBDirectos.lstUndsTerritoriales ) != "undefined") ? indBDirectos.lstUndsTerritoriales
                                                                                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indBDirectos.lstRangos ) != "undefined")    ? indBDirectos.lstRangos 
                                                                                : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indBDirectos.lstVariables ) != "undefined")    ? indBDirectos.lstVariables
                                                                                    : [];
            break;
            
            //  BENEFICIARIOS INDIRECTOS
            case ( tpoIndicador == 'bi' && typeof( dtaObjIndicador.indBIndirecto[idRegIndicador] ) != 'undefined' ):
                indBIndirecto = dtaObjIndicador.indBIndirecto[idRegIndicador];
                
                switch (tpo) {
                    case 'm':
                        indBIndirecto.idUndAnalisis = 6;
                    break;

                    case 'f':
                        indBIndirecto.idUndAnalisis = 7;
                    break;

                    case 't':
                        indBIndirecto.idUndAnalisis = 4;
                    break;
                }
                
                nombreInd       = indBIndirecto.nombreInd;
                idTpoUndMedida  = indBIndirecto.idTpoUndMedida;
                idUndMedida     = indBIndirecto.idUndMedida;
                idUndAnalisis   = indBIndirecto.idUndAnalisis;
                idUGResponsable = indBIndirecto.idUGResponsable;
                idResponsableUG = indBIndirecto.idResponsableUG;
                idResponsable   = indBIndirecto.idResponsable;
                idClaseIndicador= indBIndirecto.idClaseIndicador;
                idTpoIndicador  = indBIndirecto.idTpoIndicador;
                idFrcMonitoreo  = indBIndirecto.idFrcMonitoreo;
                nombreInd       = indBIndirecto.nombreInd;
                umbral          = indBIndirecto.umbral;
                idTendencia     = indBIndirecto.tendencia;
                descripcion     = indBIndirecto.descripcion;
                formula         = indBIndirecto.formula;
                hzFchInicio     = indBIndirecto.fchHorzMimimo;
                hzFchFin        = indBIndirecto.fchHorzMaximo;
                rgValMinimo     = indBIndirecto.umbMaximo;
                rgValMaximo     = indBIndirecto.umbMinimo;
                tpoUndMedida    = indBIndirecto.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indBIndirecto.lstLineaBase ) != "undefined")? indBIndirecto.lstLineaBase
                                                                                : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indBIndirecto.lstUndsTerritoriales ) != "undefined")? indBIndirecto.lstUndsTerritoriales
                                                                                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indBIndirecto.lstRangos ) != "undefined")   ? indBIndirecto.lstRangos 
                                                                                : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indBIndirecto.lstVariables ) != "undefined")   ? indBIndirecto.lstVariables
                                                                                    : [];
            break;
            
            //  GRUPOS DE ATENCION PRIORITARIA
            case ( tpoIndicador == 'gap' && typeof( dtaObjIndicador.lstGAP[idRegIndicador] ) != 'undefined' ):
                switch (tpo) {
                    case 'm':
                        indGAP = dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino;
                    break;

                    case 'f':
                        indGAP = dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino;
                    break;

                    case 't':
                        indGAP = dtaObjIndicador.lstGAP[idRegIndicador].gapTotal;
                    break;
                }

                nombreInd       = indGAP.nombreInd;
                idTpoUndMedida  = indGAP.idTpoUndMedida;
                idUndMedida     = indGAP.idUndMedida;
                idUndAnalisis   = indGAP.idUndAnalisis;
                idUGResponsable = indGAP.idUGResponsable;
                idResponsableUG = indGAP.idResponsableUG;
                idResponsable   = indGAP.idResponsable;
                idClaseIndicador= indGAP.idClaseIndicador;
                idTpoIndicador  = indGAP.idTpoIndicador;
                idFrcMonitoreo  = indGAP.idFrcMonitoreo;
                nombreInd       = indGAP.nombreInd;
                umbral          = indGAP.umbral;
                idTendencia     = indGAP.tendencia;
                descripcion     = indGAP.descripcion;
                formula         = indGAP.formula;
                hzFchInicio     = indGAP.fchHorzMimimo;
                hzFchFin        = indGAP.fchHorzMaximo;
                rgValMinimo     = indGAP.umbMaximo;
                rgValMaximo     = indGAP.umbMinimo;
                tpoUndMedida    = indGAP.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indGAP.lstLineaBase ) != "undefined")   ? indGAP.lstLineaBase
                                                                            : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indGAP.lstUndsTerritoriales ) != "undefined")   ? indGAP.lstUndsTerritoriales
                                                                                    : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indGAP.lstRangos ) != "undefined")  ? indGAP.lstRangos 
                                                                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indGAP.lstVariables ) != "undefined")  ? indGAP.lstVariables
                                                                            : [];
            break;
            
            //  Enfoque de Igualdad
            case ( tpoIndicador == 'ei' && typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador] ) != 'undefined' ):
                switch (tpo) {
                    case 'm':
                        indEI = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino;
                    break;

                    case 'f':
                        indEI = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino;
                    break;

                    case 't':
                        indEI = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal;
                    break;
                }

                nombreInd       = indEI.nombreInd;
                idTpoUndMedida  = indEI.idTpoUndMedida;
                idUndMedida     = indEI.idUndMedida;
                idUndAnalisis   = indEI.idUndAnalisis;
                idUGResponsable = indEI.idUGResponsable;
                idResponsableUG = indEI.idResponsableUG;
                idResponsable   = indEI.idResponsable;
                idClaseIndicador= indEI.idClaseIndicador;
                idTpoIndicador  = indEI.idTpoIndicador;
                idFrcMonitoreo  = indEI.idFrcMonitoreo;
                nombreInd       = indEI.nombreInd;
                umbral          = indEI.umbral;
                idTendencia     = indEI.tendencia;
                descripcion     = indEI.descripcion;
                formula         = indEI.formula;
                hzFchInicio     = indEI.fchHorzMimimo;
                hzFchFin        = indEI.fchHorzMaximo;
                rgValMinimo     = indEI.umbMaximo;
                rgValMaximo     = indEI.umbMinimo;
                tpoUndMedida    = indEI.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indEI.lstLineaBase ) != "undefined")   ? indEI.lstLineaBase
                                                                            : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indEI.lstUndsTerritoriales ) != "undefined")   ? indEI.lstUndsTerritoriales
                                                                                    : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indEI.lstRangos ) != "undefined")  ? indEI.lstRangos 
                                                                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indEI.lstVariables ) != "undefined")  ? indEI.lstVariables
                                                                            : [];
            break;
            
            //  Enfoque Ecorae
            case ( tpoIndicador == 'ee' && typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador] ) != 'undefined' ):
                switch (tpo) {
                    case 'm':
                        indEE = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeMasculino;
                    break;

                    case 'f':
                        indEE = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeFemenino;
                    break;

                    case 't':
                        indEE = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeTotal;
                    break;
                }

                nombreInd       = indEE.nombreInd;
                idTpoUndMedida  = indEE.idTpoUndMedida;
                idUndMedida     = indEE.idUndMedida;
                idUndAnalisis   = indEE.idUndAnalisis;
                idUGResponsable = indEE.idUGResponsable;
                idResponsableUG = indEE.idResponsableUG;
                idResponsable   = indEE.idResponsable;
                idClaseIndicador= indEE.idClaseIndicador;
                idTpoIndicador  = indEE.idTpoIndicador;
                idFrcMonitoreo  = indEE.idFrcMonitoreo;
                nombreInd       = indEE.nombreInd;
                umbral          = indEE.umbral;
                idTendencia     = indEE.tendencia;
                descripcion     = indEE.descripcion;
                formula         = indEE.formula;
                hzFchInicio     = indEE.fchHorzMimimo;
                hzFchFin        = indEE.fchHorzMaximo;
                rgValMinimo     = indEE.umbMaximo;
                rgValMaximo     = indEE.umbMinimo;
                tpoUndMedida    = indEE.tpoUndMedida;
                
                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indEE.lstLineaBase ) != "undefined")   ? indEE.lstLineaBase
                                                                            : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indEE.lstUndsTerritoriales ) != "undefined")   ? indEE.lstUndsTerritoriales
                                                                                    : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indEE.lstRangos ) != "undefined")  ? indEE.lstRangos 
                                                                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indEE.lstVariables ) != "undefined")   ? indEE.lstVariables
                                                                            : [];

            break;

            //  Otros Indicadores
            case ( tpoIndicador == 'oi' && typeof( dtaObjIndicador.lstOtrosIndicadores[idRegIndicador] ) != 'undefined' ):
                indOI = dtaObjIndicador.lstOtrosIndicadores[idRegIndicador];

                idRegIndicador  = indOI.idRegIndicador;
                nombreInd       = indOI.nombreInd;
                idTpoUndMedida  = indOI.idTpoUndMedida;
                idUndMedida     = indOI.idUndMedida;
                idUndAnalisis   = indOI.idUndAnalisis;
                idUGResponsable = indOI.idUGResponsable;
                idResponsableUG = indOI.idResponsableUG;
                idResponsable   = indOI.idResponsable;
                idClaseIndicador= indOI.idClaseIndicador;
                idTpoIndicador  = indOI.idTpoIndicador;
                idFrcMonitoreo  = indOI.idFrcMonitoreo;
                nombreInd       = indOI.nombreInd;
                umbral          = indOI.umbral;
                idTendencia     = indOI.tendencia;
                descripcion     = indOI.descripcion;
                formula         = indOI.formula;
                hzFchInicio     = indOI.fchHorzMimimo;
                hzFchFin        = indOI.fchHorzMaximo;
                rgValMinimo     = indOI.umbMaximo;
                rgValMaximo     = indOI.umbMinimo;
                tpoUndMedida    = indOI.tpoUndMedida;

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof( indOI.lstLineaBase ) != "undefined")    ? indOI.lstLineaBase
                                                                            : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof( indOI.lstUndsTerritoriales ) != "undefined")    ? indOI.lstUndsTerritoriales
                                                                                    : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof( indOI.lstRangos ) != "undefined")   ? indOI.lstRangos 
                                                                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof( indOI.lstVariables ) != "undefined")   ? indOI.lstVariables
                                                                            : [];

                //  Seteo informacion de Dimensiones
                dtaLstDim = (typeof( indOI.lstDimensiones ) != "undefined") ? indOI.lstDimensiones
                                                                            : [];
            break;
            
        }

        //  Actualizo nombre del indicador
        jQuery('#jform_nombreIndicador').attr('value', nombreInd );

        //  Actualizo valor de Umbral
        jQuery('#jform_umbralIndicador').attr( 'value', umbral );

        //  Actualizo descripcion
        jQuery('#jform_descripcionIndicador').attr( 'value', descripcion );

        //  Actualizo formula
        jQuery('#jform_strFormulaIndicador').attr( 'value', formula );
        
        //  Actualizo Horizonte fchFin
        jQuery('#jform_hzFchInicio').attr( 'value', hzFchInicio );
        jQuery('#jform_hzFchFin').attr( 'value', hzFchFin );
        jQuery('#jform_valMinimo').attr( 'value', rgValMinimo );
        jQuery('#jform_valMaximo').attr( 'value', rgValMaximo );

        //  Actualizo combo de tendencia
        recorrerCombo( jQuery('#jform_idTendencia option'), idTendencia );

        //  Actualizo combo de Unidad de Analisis
        recorrerCombo( jQuery('#jform_intIdUndAnalisis option'), idUndAnalisis );
        jQuery('#jform_intIdUndAnalisis').attr( 'disabled', 'disabled' );

        //  Actualizo combo de Tipo unidad de medida
        recorrerCombo( jQuery('#jform_intIdTpoUndMedida option'), idTpoUndMedida );
        
        //  Actualizo combo de Frecuencia de Monitoreo
        recorrerCombo( jQuery('#jform_intIdFrcMonitoreo option'), idFrcMonitoreo );
        
        //  Unidad de Medida
        jQuery( '#jform_intIdTpoUndMedida' ).trigger( 'change', idUndMedida );

        //  Actualizo combo de unidad de Gestion
        recorrerCombo( jQuery('#jform_intIdUndGestion option'), idUGResponsable );

        //  Actualizo combo de Unidad de Gestion del Funcionario un Responsable
        recorrerCombo( jQuery('#jform_intIdUGResponsable option'), idResponsableUG );

        //  Actualizo informacion de responsable de acuerdo a la unidad de gestion del mismo
        jQuery('#jform_intIdUGResponsable').trigger( 'change', idResponsable );

        //  Actualizo combo Clase del Indicador
        recorrerCombo(jQuery('#jform_idClaseIndicador option'), idClaseIndicador );
        
        //  Actualizo informacion de Lineas Base
        for (var x = 0; x < dtaLstLB.length; x++) {
            lstTmpLineasBase.push(dtaLstLB[x]);
        }

        //  Actualizo informacion de Unidades Territoriales
        for (var x = 0; x < dtaLstUT.length; x++) {
            lstTmpUT.push(dtaLstUT[x]);
        }

        //  Actualizo informacion de Rangos de Gestion
        for (var x = 0; x < dtaLstRG.length; x++) {
            lstTmpRG.push(dtaLstRG[x]);
        }

        //  Actualizo informacion de Variables
        for (var x = 0; x < dtaLstVar.length; x++) {
            lstTmpVar.push( dtaLstVar[x] );
        }

        //  Actualizo informacion de Dimensiones
        for (var x = 0; x < dtaLstDim.length; x++) {
            lstTmpDimension.push( dtaLstDim[x] );
        }
    }

    //
    //  Recorre los comboBox del Formulario a la posicion inicial
    //
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }

    //  Agrego informacion de linea base
    Joomla.submitbutton = function(task)
    {
        if (task == 'atributosindicador.asignar') {
            
            idTpoIndicador  = jQuery( '#jform_idTpoIndicador' ).val();
            idClaseIndicador= jQuery( '#jform_idClaseIndicador' ).val();
            idUndAnalisis   = jQuery( '#jform_intIdUndAnalisis' ).val();
            idTpoUndMedida  = jQuery( '#jform_intIdTpoUndMedida' ).val()
            idUndMedida     = jQuery( '#jform_idUndMedida' ).val();
            idTendencia     = jQuery( '#jform_idTendencia' ).val();
            idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
            idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
            idResponsable   = jQuery( '#jform_idResponsable' ).val();
            
            nombreInd       = jQuery( '#jform_nombreIndicador' ).val();
            umbral          = jQuery( '#jform_umbralIndicador' ).val();
            descripcion     = jQuery( '#jform_descripcionIndicador' ).val();
            formula         = jQuery( '#jform_strFormulaIndicador' ).val();
            idFrcMonitoreo  = jQuery( '#jform_intIdFrcMonitoreo' ).val();
            hzFchInicio     = jQuery( '#jform_hzFchInicio' ).val();
            hzFchFin        = jQuery( '#jform_hzFchFin' ).val();
            rgValMinimo     = jQuery( '#jform_valMinimo' ).val();
            rgValMaximo     = jQuery( '#jform_valMaximo' ).val();

            switch (tpoIndicador) {
                
                //  Gestion de Registro de Indicadores Economicos
                case 'eco':
                    var indEconomico = parent.window.objGestionIndicador.indEconomico[idRegIndicador];                    
                    
                    indEconomico.descripcion      = descripcion;
                    indEconomico.tendencia        = idTendencia;
                    indEconomico.idUndAnalisis    = idUndAnalisis;
                    indEconomico.idTpoIndicador   = idTpoIndicador;
                    indEconomico.formula          = formula;
                    indEconomico.fchHorzMimimo    = hzFchInicio;
                    indEconomico.fchHorzMaximo    = hzFchFin;
                    indEconomico.umbMaximo        = rgValMinimo;
                    indEconomico.umbMinimo        = rgValMaximo;
                    indEconomico.idTpoUndMedida   = idTpoUndMedida;
                    indEconomico.idUndMedida      = idUndMedida;
                    indEconomico.idUGResponsable  = idUGResponsable;
                    indEconomico.idResponsableUG  = idResponsableUG;
                    indEconomico.idResponsable    = idResponsable;
                    indEconomico.idClaseIndicador = idClaseIndicador;
                    indEconomico.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    indEconomico.lstLineaBase = new Array();
                    
                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        indEconomico.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    indEconomico.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        indEconomico.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    indEconomico.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        indEconomico.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    indEconomico.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        indEconomico.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Gestion de Registro de Indicadores Financieros
                case 'fin':
                    var indFinanciero = parent.window.objGestionIndicador.indFinanciero[idRegIndicador];
                    
                    indFinanciero.descripcion      = descripcion;
                    indFinanciero.tendencia        = idTendencia;
                    indFinanciero.idUndAnalisis    = idUndAnalisis;
                    indFinanciero.idTpoIndicador   = idTpoIndicador;
                    indFinanciero.formula          = formula;
                    indFinanciero.fchHorzMimimo    = hzFchInicio;
                    indFinanciero.fchHorzMaximo    = hzFchFin;
                    indFinanciero.umbMaximo        = rgValMinimo;
                    indFinanciero.umbMinimo        = rgValMaximo;
                    indFinanciero.idTpoUndMedida   = idTpoUndMedida;
                    indFinanciero.idUndMedida      = idUndMedida;
                    indFinanciero.idUGResponsable  = idUGResponsable;
                    indFinanciero.idResponsableUG  = idResponsableUG;
                    indFinanciero.idResponsable    = idResponsable;
                    indFinanciero.idClaseIndicador = idClaseIndicador;
                    indFinanciero.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    indFinanciero.lstLineaBase = new Array();
                    
                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        indFinanciero.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    indFinanciero.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        indFinanciero.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    indFinanciero.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        indFinanciero.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    indFinanciero.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        indFinanciero.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Gestion de Registro de Beneficiarios Directos
                case 'bd':
                    var BDirecto = parent.window.objGestionIndicador.indBDirecto[idRegIndicador];

                    BDirecto.descripcion      = descripcion;
                    BDirecto.tendencia        = idTendencia;
                    BDirecto.idUndAnalisis    = idUndAnalisis;
                    BDirecto.idTpoIndicador   = idTpoIndicador;
                    BDirecto.formula          = formula;
                    BDirecto.fchHorzMimimo    = hzFchInicio;
                    BDirecto.fchHorzMaximo    = hzFchFin;
                    BDirecto.umbMaximo        = rgValMinimo;
                    BDirecto.umbMinimo        = rgValMaximo;
                    BDirecto.idTpoUndMedida   = idTpoUndMedida;
                    BDirecto.idUndMedida      = idUndMedida;
                    BDirecto.idUGResponsable  = idUGResponsable;
                    BDirecto.idResponsableUG  = idResponsableUG;
                    BDirecto.idResponsable    = idResponsable;
                    BDirecto.idClaseIndicador = idClaseIndicador;
                    BDirecto.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    BDirecto.lstLineaBase = new Array();

                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        BDirecto.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    BDirecto.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        BDirecto.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    BDirecto.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        BDirecto.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    BDirecto.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        BDirecto.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Gestion de Registro de Beneficiarios InDirectos
                case 'bi':
                    var BInDirecto = parent.window.objGestionIndicador.indBIndirecto[idRegIndicador];

                    BInDirecto.descripcion      = descripcion;
                    BInDirecto.tendencia        = idTendencia;
                    BInDirecto.idUndAnalisis    = idUndAnalisis;
                    BInDirecto.idTpoIndicador   = idTpoIndicador;
                    BInDirecto.formula          = formula;
                    BInDirecto.fchHorzMimimo    = hzFchInicio;
                    BInDirecto.fchHorzMaximo    = hzFchFin;
                    BInDirecto.umbMaximo        = rgValMinimo;
                    BInDirecto.umbMinimo        = rgValMaximo;
                    BInDirecto.idTpoUndMedida   = idTpoUndMedida;
                    BInDirecto.idUndMedida      = idUndMedida;
                    BInDirecto.idUGResponsable  = idUGResponsable;
                    BInDirecto.idResponsableUG  = idResponsableUG;
                    BInDirecto.idResponsable    = idResponsable;
                    BInDirecto.idClaseIndicador = idClaseIndicador;
                    BInDirecto.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    BInDirecto.lstLineaBase = new Array();

                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        BInDirecto.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    BInDirecto.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        BInDirecto.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    BInDirecto.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        BInDirecto.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    BInDirecto.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        BInDirecto.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Gestion de Registro GAP
                case 'gap':
                    var GAP;
                    
                    switch (tpo) {
                        case 'm':
                            GAP = parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino;
                        break;

                        case 'f':
                            GAP = parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino;
                        break;

                        case 't':
                            GAP = parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal;
                        break;
                    }
                    
                    //  Seteo informacion de acuerdo al indicador seleccionado
                    GAP.descripcion      = descripcion;
                    GAP.tendencia        = idTendencia;
                    GAP.idUndAnalisis    = idUndAnalisis;
                    GAP.idTpoIndicador   = idTpoIndicador;
                    GAP.formula          = formula;
                    GAP.fchHorzMimimo    = hzFchInicio;
                    GAP.fchHorzMaximo    = hzFchFin;
                    GAP.umbMaximo        = rgValMinimo;
                    GAP.umbMinimo        = rgValMaximo;
                    GAP.idTpoUndMedida   = idTpoUndMedida;
                    GAP.idUndMedida      = idUndMedida;
                    GAP.idUGResponsable  = idUGResponsable;
                    GAP.idResponsableUG  = idResponsableUG;
                    GAP.idResponsable    = idResponsable;
                    GAP.idClaseIndicador = idClaseIndicador;
                    GAP.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    GAP.lstLineaBase = new Array();
                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        GAP.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    GAP.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        GAP.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    GAP.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        GAP.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    GAP.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        GAP.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Gestion de Registro EI
                case 'ei':
                    var EI;
                    
                    switch (tpo) {
                        case 'm':
                            EI = parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino;
                        break;

                        case 'f':
                            EI = parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino;
                        break;

                        case 't':
                            EI = parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal;
                        break;
                    }
                    
                    //  Seteo informacion de acuerdo al indicador seleccionado
                    EI.descripcion      = descripcion;
                    EI.tendencia        = idTendencia;
                    EI.idUndAnalisis    = idUndAnalisis;
                    EI.idTpoIndicador   = idTpoIndicador;
                    EI.formula          = formula;
                    EI.fchHorzMimimo    = hzFchInicio;
                    EI.fchHorzMaximo    = hzFchFin;
                    EI.umbMaximo        = rgValMinimo;
                    EI.umbMinimo        = rgValMaximo;
                    EI.idTpoUndMedida   = idTpoUndMedida;
                    EI.idUndMedida      = idUndMedida;
                    EI.idUGResponsable  = idUGResponsable;
                    EI.idResponsableUG  = idResponsableUG;
                    EI.idResponsable    = idResponsable;
                    EI.idClaseIndicador = idClaseIndicador;
                    EI.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    EI.lstLineaBase = new Array();
                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        EI.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    EI.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        EI.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    EI.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        EI.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    EI.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        EI.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Gestion de Registro EEcorae
                case 'ee':
                    var EE;
                    
                    switch (tpo) {
                        case 'm':
                            EE = parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino;
                        break;

                        case 'f':
                            EE = parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino;
                        break;

                        case 't':
                            EE = parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal;
                        break;
                    }
                    
                    //  Seteo informacion de acuerdo al indicador seleccionado
                    EE.descripcion      = descripcion;
                    EE.tendencia        = idTendencia;
                    EE.idUndAnalisis    = idUndAnalisis;
                    EE.idTpoIndicador   = idTpoIndicador;
                    EE.formula          = formula;
                    EE.fchHorzMimimo    = hzFchInicio;
                    EE.fchHorzMaximo    = hzFchFin;
                    EE.umbMaximo        = rgValMinimo;
                    EE.umbMinimo        = rgValMaximo;
                    EE.idTpoUndMedida   = idTpoUndMedida;
                    EE.idUndMedida      = idUndMedida;
                    EE.idUGResponsable  = idUGResponsable;
                    EE.idResponsableUG  = idResponsableUG;
                    EE.idResponsable    = idResponsable;
                    EE.idClaseIndicador = idClaseIndicador;
                    EE.idFrcMonitoreo   = idFrcMonitoreo;

                    //  Vacío Lista de Lineas Base
                    EE.lstLineaBase = new Array();
                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        EE.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de Unidades Territoriales
                    EE.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de Unidades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        EE.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    EE.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        EE.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    EE.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        EE.lstVariables.push(lstTmpVar[x]);
                    }
                    
                break;
                
                //  Otros Indicadores
                case 'oi':
                    var OI;
                    var idRegistro;
                    var objOtroInd;
                    var idIndicador;
                    var gi = new parent.window.GestionIndicador();
                    
                    if( idRegIndicador == -1 ){
                        OI = new parent.window.Indicador();
                        objOtroInd = parent.window.objGestionIndicador.lstOtrosIndicadores;
                        idRegistro = objOtroInd.length;
                        idIndicador = 0;
                    }else{
                        OI = parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador];
                        idIndicador = OI.idIndicador;
                        idRegistro = idRegIndicador;
                    }
                    
                    //  Si el indicador es nuevo le asigno un nuevo identificador de registro
                    OI.idRegIndicador   = idRegistro;
                    OI.idIndicador      = idIndicador;
                    OI.nombreInd        = nombreInd;
                    OI.descripcion      = descripcion;
                    OI.tendencia        = idTendencia;
                    OI.idUndAnalisis    = idUndAnalisis;
                    OI.idTpoIndicador   = idTpoIndicador;
                    OI.formula          = formula;
                    OI.fchHorzMimimo    = hzFchInicio;
                    OI.fchHorzMaximo    = hzFchFin;
                    OI.umbral           = umbral;
                    OI.umbMaximo        = rgValMinimo;
                    OI.umbMinimo        = rgValMaximo;
                    OI.idTpoUndMedida   = idTpoUndMedida;
                    OI.idUndMedida      = idUndMedida;
                    OI.idUGResponsable  = idUGResponsable;
                    OI.idResponsableUG  = idResponsableUG;
                    OI.idResponsable    = idResponsable;
                    OI.idClaseIndicador = idClaseIndicador;
                    OI.idFrcMonitoreo   = idFrcMonitoreo;
                    OI.idCategoria      = 2;
                    OI.idDimension      = 35;

                    //  Vacío Lista de Lineas Base
                    OI.lstLineaBase = new Array();
                    //  Agrego Lista editada de Lineas Base
                    for (var x = 0; x < lstTmpLineasBase.length; x++) {
                        OI.lstLineaBase.push(lstTmpLineasBase[x]);
                    }

                    //  Vacío Lista de UOIdades Territoriales
                    OI.lstUndsTerritoriales = new Array();
                    //  Agrego Lista editada de UOIdades Territoriales
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        OI.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Vacío Lista de Rangos de gestion
                    OI.lstRangos = new Array();
                    //  Agrego Lista editada de Rangos de Gestion
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        OI.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Vacío Lista de Variables
                    OI.lstVariables = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        OI.lstVariables.push(lstTmpVar[x]);
                    }

                    //  Vacío Lista de Dimensiones
                    OI.lstDimensiones = new Array();
                    //  Agrego Lista editada de Variables
                    for (var x = 0; x < lstTmpDimension.length; x++) {
                        OI.lstDimensiones.push(lstTmpDimension[x]);
                    }
                    
                    if( idRegIndicador == -1 ){
                        if( existeOtroIndicador( OI ) == 0 ){
                            //  Agrego indicador a lista de Nuevos Indicadores
                            objOtroInd.push( OI );
                            
                            //  Agrego la fila creada a la tabla
                            jQuery( '#lstOtrosInd > tbody:last', window.parent.document ).append( gi.addFilaOtroIndicador( OI, 0 ) );
                        }else{
                            jAlert( 'Indicador ya Registrado', 'SIITA - ECORAE' );
                        }

                    }else{
                        //  Actualizo contenido Fila
                        gi.updInfoFilaOI( idRegIndicador, gi.addFilaOtroIndicador( OI, 1 ) );
                    }
                    
                break;
            }

            //  Cambio la imagen del indicador seleccionado
            jQuery('#' + tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() + 'AI', window.parent.document).html('<img src="/media/com_proyectos/images/btnLineaBase/PN/pn_verde_small.png">');

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        } else {
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
    
    
    function existeOtroIndicador( oi )
    {
        var lstOI = parent.window.objGestionIndicador.lstOtrosIndicadores;
        var ban = 0;
        for( var x = 0; x < lstOI.length; x++ ){
            if( lstOI[x].toString() == oi.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
})