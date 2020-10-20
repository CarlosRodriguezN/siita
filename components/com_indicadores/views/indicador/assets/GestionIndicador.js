/**
 * 
 * Gestiona Informacion de indicadores operativos como son 
 * Indicadores Economicos, Financieros, Beneficiarios Directos, Indirectos, 
 * Indicadores de Grupos de Atencion Prioritaria ( GAP ).
 * 
 */

jQuery(document).ready(function() {
    var dtaObjIndicador = parent.window.objGestionIndicador;

    var idIndicador     = jQuery('#idIndicador').val();
    var idRegIndicador  = parseInt(jQuery('#idRegIndicador').val());
    var tpoIndicador    = jQuery('#tpoIndicador').val();
    var tpo             = jQuery('#tpo').val();
    var ent             = jQuery('#ent').val();
    

    var fchInicio       = jQuery('#jform_dteFechaInicio_stmdoPry', parent.window.document).val();
    var fchFin          = jQuery('#jform_dteFechaFin_stmdoPry', parent.window.document).val();
    var idUGResponsable = jQuery('#jform_intIdUndGestion', parent.window.document).val();
    var idResponsableUG = jQuery('#jform_intIdUGResponsable', parent.window.document).val();
    var idResponsable   = jQuery('#jform_idResponsable', parent.window.document).val();

    lstTmpLB = new Array();
    lstTmpRG = new Array();
    lstTmpUT = new Array();
    lstTmpVar = new Array();
    lstTmpDim = new Array();
    lstTmpPln = new Array();
    lstTmpIndicadores = new Array();
    lstTmpSg = new Array();
    strAccesoTableu = new Array();

    var dtaLstLB = [];
    var dtaLstUT = [];
    var dtaLstRG = [];
    var dtaLstVar = [];
    var dtaLstDim = [];
    var dtaLstPln = [];

    var lstTmpVariables = new Array();
    var banIdRegVar = -1;

    updFrmAtributos();

    function updFrmAtributos()
    {
        var objIndicador = false;
        var umbral;

        //  Cargo informacion de variables acuerdo al tipo de indicador
        switch (true) {
            //  INDICADORES ECONOMICOS
            case (tpoIndicador === 'eco' && (typeof (dtaObjIndicador.indEconomico[idRegIndicador]) !== 'undefined')):
                objIndicador = dtaObjIndicador.indEconomico[idRegIndicador];

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];
                break;

                //  INDICADORES FINANCIEROS
            case (tpoIndicador === 'fin' && typeof (dtaObjIndicador.indFinanciero[idRegIndicador]) !== 'undefined'):
                objIndicador = dtaObjIndicador.indFinanciero[idRegIndicador];

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];
                break;

                //  BENEFICIARIOS DIRECTOS
            case (tpoIndicador === 'bd' && typeof (dtaObjIndicador.indBDirecto[idRegIndicador]) !== 'undefined'):
                objIndicador = dtaObjIndicador.indBDirecto[idRegIndicador];

                switch (tpo) {
                    case 'm':
                        objIndicador.idUndAnalisis = 6;
                        break;

                    case 'f':
                        objIndicador.idUndAnalisis = 7;
                        break;

                    case 't':
                        objIndicador.idUndAnalisis = 4;
                        break;
                }

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];
                break;

                //  BENEFICIARIOS INDIRECTOS
            case (tpoIndicador === 'bi' && typeof (dtaObjIndicador.indBIndirecto[idRegIndicador]) !== 'undefined'):
                objIndicador = dtaObjIndicador.indBIndirecto[idRegIndicador];

                switch (tpo) {
                    case 'm':
                        objIndicador.idUndAnalisis = 6;
                        break;

                    case 'f':
                        objIndicador.idUndAnalisis = 7;
                        break;

                    case 't':
                        objIndicador.idUndAnalisis = 4;
                        break;
                }

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];
            break;

            //  GRUPOS DE ATENCION PRIORITARIA
            case (tpoIndicador === 'gap' && typeof (dtaObjIndicador.lstGAP[idRegIndicador]) !== 'undefined'):

                jQuery('#jform_umbralIndicador').attr('disabled', 'disabled');

                switch (tpo) {
                    case 'm':
                        objIndicador = dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino;
                        break;

                    case 'f':
                        objIndicador = dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino;
                        break;

                    case 't':
                        objIndicador = dtaObjIndicador.lstGAP[idRegIndicador].gapTotal;
                        break;
                }

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];

            break;

            //  Enfoque de Igualdad
            case (tpoIndicador === 'ei' && typeof (dtaObjIndicador.lstEnfIgualdad[idRegIndicador]) !== 'undefined'):

                jQuery('#jform_umbralIndicador').attr('disabled', 'disabled');

                switch (tpo) {
                    case 'm':
                        objIndicador = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino;
                        break;

                    case 'f':
                        objIndicador = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino;
                        break;

                    case 't':
                        objIndicador = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal;
                        break;
                }

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];
            break;

            //  Enfoque Ecorae
            case (tpoIndicador === 'ee' && typeof (dtaObjIndicador.lstEnfEcorae[idRegIndicador]) !== 'undefined'):

                jQuery('#jform_umbralIndicador').attr('disabled', 'disabled');

                switch (tpo) {
                    case 'm':
                        objIndicador = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeMasculino;
                        break;

                    case 'f':
                        objIndicador = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeFemenino;
                        break;

                    case 't':
                        objIndicador = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeTotal;
                        break;
                }

                //  Seteo informacion de Lineas Base
                dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                        ? objIndicador.lstLineaBase
                        : [];

                //  Seteo informacion de Unidades Territoriales
                dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                        ? objIndicador.lstUndsTerritoriales
                        : [];

                //  Seteo informacion de Rangos de Gestion
                dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                        ? objIndicador.lstRangos
                        : [];

                //  Seteo informacion de Variables
                dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                        ? objIndicador.lstVariables
                        : [];

                //  Seteo informacion de Variables
                dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                        ? objIndicador.lstPlanificacion
                        : [];
                break;

            //  Otros Indicadores
            case (tpoIndicador === 'oi'):

                if (idRegIndicador != -1) {
                    objIndicador = dtaObjIndicador.lstOtrosIndicadores[idRegIndicador];

                    //  Seteo informacion de Lineas Base
                    dtaLstLB = (typeof (objIndicador.lstLineaBase) != "undefined") 
                            ? objIndicador.lstLineaBase
                            : [];

                    //  Seteo informacion de Unidades Territoriales
                    dtaLstUT = (typeof (objIndicador.lstUndsTerritoriales) != "undefined") 
                            ? objIndicador.lstUndsTerritoriales
                            : [];

                    //  Seteo informacion de Rangos de Gestion
                    dtaLstRG = (typeof (objIndicador.lstRangos) != "undefined") 
                            ? objIndicador.lstRangos
                            : [];

                    //  Seteo informacion de Variables
                    dtaLstVar = (typeof (objIndicador.lstVariables) != "undefined") 
                            ? objIndicador.lstVariables
                            : [];

                    //  Seteo informacion de Dimensiones
                    dtaLstDim = (typeof (objIndicador.lstDimensiones) != "undefined") 
                            ? objIndicador.lstDimensiones
                            : [];

                    //  Seteo informacion de Variables
                    dtaLstPln = (typeof (objIndicador.lstPlanificacion) != "undefined") 
                            ? objIndicador.lstPlanificacion
                            : [];
                } else {
                    objIndicador = new Indicador();
                }

                break;
        }

        if (objIndicador !== false) {
            updDtaIndicador(objIndicador);
        }
    }

    function updDtaIndicador(objIndicador)
    {
        var valor = !isNaN( objIndicador.umbral )   ? objIndicador.umbral
                                                    : 0;

        var valorUmbral = objIndicador.setDataUmbral( valor );

        banIdRegVar = (typeOf(objIndicador.idIndEntidad) === "null") 
                        ? -1
                        : objIndicador.idIndEntidad;

        var fchInicioUG = jQuery( '#jform_fchInicioPeriodoUG', window.parent.document ).val();
        var idUGR       = jQuery( '#jform_intIdUndGestion', window.parent.document ).val();
        var idUGFR      = jQuery( '#jform_intIdUGResponsable', window.parent.document ).val();
        var idFR        = jQuery( '#jform_idResponsable', window.parent.document ).val();

        switch( ent ){
            case "contrato": 
                var fchInicioPln   = jQuery( '#jform_dteFechaInicio_ctr', window.parent.document ).val();
                var fchFinPln      = jQuery( '#jform_dteFechaFin_ctr', window.parent.document ).val();
            break;
        }

        //  Actualizo nombre del indicador
        jQuery( '#jform_idTpoIndicador' ).attr( 'value', objIndicador.idTpoIndicador );

        //  Actualizo nombre del indicador
        jQuery('#jform_nombreIndicador').attr('value', objIndicador.nombreIndicador);

        //  Actualizo valor de Umbral
        jQuery('#jform_umbralIndicador').attr('value', valorUmbral);

        //  Actualizo descripcion
        jQuery('#jform_descripcionIndicador').attr('value', objIndicador.descripcion);
        
        //  informacion complementaria del indicador
        jQuery('#jform_metodologia').attr( 'value', objIndicador.metodologia );
        jQuery('#jform_limitacion').attr( 'value', objIndicador.limitaciones );
        jQuery('#jform_interpretacion').attr( 'value', objIndicador.interpretacion );
        jQuery('#jform_disponibilidad').attr( 'value', objIndicador.disponibilidad );
        
        //  Actualizo formula
        jQuery('#formulaDescripcion').attr('value', objIndicador.formula);

        //  Actualizo Horizonte fchInicio
        if (objIndicador.fchHorzMimimo === '0000-00-00') {
            jQuery('#jform_hzFchInicio').attr('value', fchInicioPln );
        } else {
            jQuery('#jform_hzFchInicio').attr('value', objIndicador.fchHorzMimimo);
        }

        //  Actualizo Horizonte fchFin
        if (objIndicador.fchHorzMaximo === '0000-00-00') {
            jQuery('#jform_hzFchFin').attr('value', fchFinPln );
        } else {
            jQuery('#jform_hzFchFin').attr('value', objIndicador.fchHorzMaximo);
        }

        jQuery('#jform_valMinimo').attr('value', objIndicador.umbMinimo);
        jQuery('#jform_valMaximo').attr('value', objIndicador.umbMaximo);

        //  Actualizo combo de tendencia
        recorrerCombo(jQuery('#jform_idTendencia option'), objIndicador.idTendencia);

        //  Actualizo combo de Unidad de Analisis
        recorrerCombo(jQuery('#jform_intIdUndAnalisis option'), objIndicador.idUndAnalisis);

        //  Actualizo combo de Tipo unidad de medida
        recorrerCombo(jQuery('#jform_intIdTpoUndMedida option'), objIndicador.idTpoUndMedida);

        //  Unidad de Medida
        jQuery('#jform_intIdTpoUndMedida').trigger('change', objIndicador.idUndMedida);

        //  Actualizo combo de Frecuencia de Monitoreo
        recorrerCombo(jQuery('#jform_idFrcMonitoreo option'), objIndicador.idFrcMonitoreo);

        //  Unidad de Gestion Responsable
        if (banIdRegVar === -1) {
            jQuery('#jform_fchInicioPeriodoUG').attr('value', fchInicioUG);
            recorrerCombo(jQuery('#jform_intIdUndGestion option'), idUGResponsable);
        } else {
            
            if( typeOf( objIndicador.fchInicioUG ) === "null" || objIndicador.fchInicioUG === '0000-00-00' ){
                jQuery( '#jform_fchInicioPeriodoUG' ).attr( 'value', fchInicioUG );
            }else{
                jQuery( '#jform_fchInicioPeriodoUG' ).attr( 'value', objIndicador.fchInicioUG );
            }
            
            if( typeOf( objIndicador.idUGResponsable ) === "null" || parseInt( objIndicador.idUGResponsable ) === 0 ){
                recorrerCombo( jQuery( '#jform_intIdUndGestion option' ), idUGR );
            }else{
                //  Actualizo combo de unidad de Gestion
                recorrerCombo( jQuery( '#jform_intIdUndGestion option' ), objIndicador.idUGResponsable );
            }
        }

        //  Funcionario Responsable
        if (banIdRegVar === -1) {
            jQuery('#jform_fchInicioPeriodoFuncionario').attr('value', fchInicioUG);

            //  Actualizo combo de Unidad de Gestion del Funcionario un Responsable
            recorrerCombo(jQuery('#jform_intIdUGResponsable option'), idResponsableUG);

            //  Actualizo informacion de responsable de acuerdo a la unidad de gestion del mismo
            jQuery('#jform_intIdUGResponsable').trigger('change', idResponsable);
        } else {
            if( typeOf( objIndicador.fchInicioUG ) === "null" || objIndicador.fchInicioUG === '0000-00-00'  ){
                jQuery( '#jform_fchInicioPeriodoFuncionario' ).attr( 'value', fchInicioUG );
            }else{
                jQuery( '#jform_fchInicioPeriodoFuncionario' ).attr( 'value', objIndicador.fchInicioUG );
            }

            //  Actualizo combo de Unidad de Gestion del Funcionario un Responsable
            if( typeOf( objIndicador.idResponsableUG ) === "null" || parseInt( objIndicador.idResponsableUG ) === 0 ){
                recorrerCombo( jQuery('#jform_intIdUGResponsable option'), idUGFR );
            }else{
                recorrerCombo( jQuery('#jform_intIdUGResponsable option'), objIndicador.idResponsableUG );
            }

            //  Actualizo informacion de responsable de acuerdo a la unidad de gestion del mismo
            if( typeOf( objIndicador.idResponsable ) === "null" || parseInt( objIndicador.idResponsable ) === 0 ){
                jQuery('#jform_intIdUGResponsable').trigger('change', idFR );
            }else{
                jQuery('#jform_intIdUGResponsable').trigger('change', objIndicador.idResponsable);
            }
        }

        jQuery('#jform_IdHorizonte').attr('value', objIndicador.idHorizonte );

        if( parseInt( objIndicador.senplades ) === 1 ){
            jQuery('#jform_intSenplades_indEnt0').attr( 'checked', 'checked' );
        }else{
            jQuery('#jform_intSenplades_indEnt1').attr( 'checked', 'checked' );
        }

        //  Actualizo combo Clase del Indicador
        recorrerCombo(jQuery('#jform_idClaseIndicador option'), objIndicador.idClaseIndicador);

        //  Actualizo combo del grupo del indicador por Dimension
        recorrerCombo(jQuery('#jform_idGpoDimension option'), objIndicador.idGpoDimension);

        //  Actualizo combo del grupo del indicador por Decision
        recorrerCombo(jQuery('#jform_idGpoDecisiones option'), objIndicador.idGpoDecision);

        //  Actualizo informacion de Lineas Base
        setDtaLineasBase(objIndicador.lstLineaBase);

        //  Actualizo informacion de Variables
        setDtaVariables(objIndicador.lstVariables, objIndicador.idTpoUndMedida, objIndicador.idUndMedida );

        //  Actualizo informacion de Unidades Territoriales
        setDtaUT(objIndicador.lstUndsTerritoriales);

        //  Actualizo informacion de Dimensiones
        setDtaDimensiones(objIndicador.lstDimensiones);

        //  Actualizo informacion de Rangos de Gestion
        setDtaRangos(objIndicador.lstRangos, objIndicador.idTpoUndMedida, objIndicador.idUndMedida );

        //  Actualizo informacion de la planificacion
        setDtaPlanificacion( objIndicador );

        if (typeOf(objIndicador.idIndEntidad) === "null") {
            jQuery('#tabsAttrIndicador').tabs({disabled: [8]});
        } else {
            jQuery('#tabsAttrIndicador').tabs({active: [8]});

            //  Actualizo combo de seguimiento de variables
            updCBSeguimiento(objIndicador.lstVariables);
        }

        if( tpoIndicador != 'oi' ){
            jQuery('#jform_idClaseIndicador').attr('disabled', 'disabled');
            jQuery('#jform_intIdUndAnalisis').attr('disabled', 'disabled');
            jQuery('#jform_intIdTpoUndMedida').attr('disabled', 'disabled');
            jQuery('#jform_idUndMedida').attr('disabled', 'disabled');
        }

    }

    /**
     * Seteo informacion de Lineas base
     * @param {Array} lstLB     Lista de Objetos con Informacion de Lineas Base
     * @returns {undefined}
     */
    function setDtaLineasBase(lstLB)
    {
        lstTmpLB = new Array();
        var objLB;
        var nrLB = lstLB.length;
        
        if( nrLB > 0 ){
            for (var x = 0; x < nrLB; x++) {
                objLB = new LineaBase();
                objLB.setDtaLineaBase(lstLB[x]);
                objLB.idRegLB = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstLineasBase > tbody:last').append(objLB.getFilaLineaBase(0));
                lstTmpLB.push(objLB);
            }
        }else{
            objLB = new LineaBase();
            jQuery('#lstLineasBase > tbody:last').append( objLB.getFilaSinRegistros() );
        }
        
        return;
    }



    /**
     * Seteo Informacion de Unidades Territoriales
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * @returns {undefined}
     */
    function setDtaUT(lstUT)
    {
        lstTmpUT = new Array();
        var objUT;
        var nrUT = lstUT.length;
        
        if( nrUT > 0 ){
            for (var x = 0; x < lstUT.length; x++) {
                objUT = new UnidadTerritorial();
                objUT.setDtaUT(lstUT[x]);

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstUndTerritorialesInd > tbody:last').append(objUT.addFilaUT(0));
                lstTmpUT.push(objUT);
            }
        }else{
            objUT = new UnidadTerritorial();
            jQuery('#lstUndTerritorialesInd > tbody:last').append( objUT.addFilaSinRegistros() );
        }
        
        return;
    }


    /**
     * Seteo Informacion de Unidades Territoriales
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * @returns {undefined}
     */
    function setDtaRangos( lstRg, idTpoUndMedida, idUndMedida )
    {
        lstTmpRG = new Array();
        var objRg;
        var nrR = lstRg.length;
        
        if( nrR > 0 ){
            for (var x = 0; x < nrR; x++) {
                objRg = new Rango();
                objRg.setDtaRango( lstRg[x], idTpoUndMedida, idUndMedida );
                objRg.idRegRG = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstRangos > tbody:last').append(objRg.addFilaRG(0));
                lstTmpRG.push(objRg);

            }

            if( lstTmpRG.length === 3 ){
                jQuery( '#addLnRangoTable' ).attr( 'disabled', 'disabled' );
            }
        }else{
            objRg = new Rango();
            jQuery('#lstRangos > tbody:last').append( objRg.addFilaSinRegistros() );
        }

        return;
    }

    /**
     * 
     * Seteo Informacion de Unidades Territoriales
     * 
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * 
     * @returns {undefined}
     * 
     */
    function setDtaDimensiones(lstDim)
    {
        lstTmpDim = new Array();
        var objDim;
        var nrDim = lstDim.length;
        
        if( nrDim > 0 ){
            for (var x = 0; x < nrDim; x++) {
                objDim = new Dimension();
                objDim.setDtaDimension(lstDim[x]);
                objDim.idRegDimension = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstDimensiones > tbody:last').append(objDim.addFilaDimension(0));
                lstTmpDim.push(objDim);
            }
        }else{
            objDim = new Dimension();
            
            //  Agrego una fila a la tabla de lineas base
            jQuery('#lstDimensiones > tbody:last').append( objDim.addFilaSinRegistros() );
        }
            
        return;
    }

    /**
     * 
     * Seteo Informacion de Variables asociadas al Indicador
     * 
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * 
     * @returns {undefined}
     * 
     */
    function setDtaVariables(lstVar, idTpoUndMedida, idUndMedida)
    {
        lstTmpVar = new Array();
        var objVar;
        var nrV = lstVar.length;

        if( nrV > 0 ) {
            for (var x = 0; x < nrV; x++) {
                objVar = new Variable();
                lstVar[x].idRegVar = x;
                objVar.setDtaVariable(lstVar[x], idTpoUndMedida, idUndMedida);

                if( parseInt( objVar.published ) === 1 ){
                    //  Agrego una fila a la tabla de Variables
                    jQuery('#lstVarIndicadores > tbody:last').append(objVar.addFilaVar(0));

                    //  Agrego una fila a la tabla de Variables
                    lstTmpVar.push(objVar);
                }
            }
        }else{
            objVar = new Variable();
            jQuery('#lstVarIndicadores > tbody:last').append( objVar.addFilaSinRegistros() );
        }

        return;
    }


    /**
     * 
     * Calcula el valor total planificado, sumando el total de valores planificados
     * 
     * @returns {float}
     */
    function getTotalSeguimiento()
    {
        var ntp = 0;
        var nrp = lstTmpVar.length;

        if (nrp) {
            for (var x = 0; x < nrp; x++) {
                ntp += parseFloat(lstTmpVar[x].valor);
            }
        }

        return ntp.toFixed(2);
    }


    /**
     * 
     * Seteo Informacion de Unidades Territoriales
     * 
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * 
     * @returns {undefined}
     * 
     */
    function setDtaFormula(lstVar)
    {
        lstTmpVar = new Array();
        var objVar;

        if (lstVar.length) {
            for (var x = 0; x < lstVar.length; x++) {
                objVar = new Variable();
                lstVar[x].idRegVar = x;
                objVar.setDtaVariable(lstVar[x]);
                lstTmpVar.push(objVar);

                switch (true) {
                    //  Variable Numerador
                    case (parseInt(objVar.idTpoElemento) === 1 || parseInt(objVar.idTpoElemento) === 3):
                        jQuery('#numerador').attr('value', objVar.nombre);
                        break;

                        //  Variable Denominador
                    case (parseInt(objVar.idTpoElemento) === 2 || parseInt(objVar.idTpoElemento) === 4):
                        jQuery('#denominador').attr('value', objVar.nombre);
                        break;
                }
            }
        }

        return;
    }

    /**
     *  Seteo Informacion de planificacion del idicador
     * @param {type} lstPln         Lista de la planificacion
     * @returns {unresolved}
     */
    function setDtaPlanificacion( objIndicador )
    {
        lstTmpPln = new Array();
        var lstPln = objIndicador.lstPlanificacion;
        var nrpln = lstPln.length;

        if( nrpln > 0 ) {
            for (var x = 0; x < nrpln; x++) {
                var objPln = new Planificacion();
                lstPln[x].idRegPln = x;
                objPln.setDtaPlanificacion( lstPln[x], 
                                            objIndicador.idTpoUndMedida, 
                                            objIndicador.idUndMedida );

                //  Agrego una fila al final de tabla de Planificacion
                jQuery('#lstPlanificacionIndicadores > tbody:last').append(objPln.addFilaPln(0));
                lstTmpPln.push(objPln);
            }
        }else{
            objPlanificacion = new Planificacion();
            jQuery('#lstPlanificacionIndicadores > tbody:last').append( objPlanificacion.addFilaSinRegistros() );
        }

        return;
    }

    /**
     * 
     * Calcula el valor total planificado, sumando el total de valores planificados
     * 
     * @returns {float}
     */
    function getTotalPlanificado()
    {
        var ntp = 0;
        var nrp = lstTmpPln.length;

        if (nrp) {
            for (var x = 0; x < nrp; x++) {
                ntp += parseFloat(lstTmpPln[x].valor);
            }
        }

        return ntp.toFixed(2);
    }

    /**
     * 
     * Retorno el tipo de horizonte en funcion a la diferencia de años entre la 
     * fecha de inicio y fin del horizonte
     * 
     * @param {string} fchInicioHz    Fecha de inicio horizonte
     * @param {string} fchFinHz       Fecha de inicio horizonte
     * 
     * @returns {int}
     * 
     */
    function getTpoHorizonte(fchInicioHz, fchFinHz)
    {
        var fchInicio = new Date(fchInicioHz);
        var fchFin = new Date(fchFinHz);
        var diferencia = fchFin - fchInicio;

        return (diferencia > 1) ? 8
                : 7;
    }

    /**
     * 
     * Actualizo ComboBox con la lista de variables a dar seguimiento
     * 
     * @param {type} lstVariables   Lista de variables
     * @returns {undefined}
     */
    function updCBSeguimiento(lstVariables)
    {
        var dataInfo = eval(lstVariables);
        var numRegistros = dataInfo.length;
        var items = [];

        if (numRegistros > 0) {
            items.push('<option value="0">SELECCIONE VARIABLE</option>');
            for (var x = 0; x < numRegistros; x++) {
                
                if( parseInt( dataInfo[x].idTpoElemento ) == 1 ){
                    items.push('<option value="' + dataInfo[x].idIndVariable + '">' + dataInfo[x].nombre + '</option>');
                }
                
            }
        } else {
            items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
        }

        jQuery('#jform_idVariableIndicador').html(items.join(''));
    }

    /**
     * 
     * Recorre los comboBox del Formulario a la posicion inicial
     * 
     * @param {type} combo
     * @param {type} posicion
     * @returns {undefined}
     */
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
        if (task == 'indicador.asignar') {

            if ( validarFomulario() ) {
                switch (tpoIndicador) {
                    //  Gestion de Registro de Indicadores Economicos
                    case 'eco':
                        registroIndEconomicos();
                    break;

                    //  Gestion de Registro de Indicadores Financieros
                    case 'fin':
                        registroIndFinancieros();
                    break;

                    //  Gestion de Registro de Beneficiarios Directos
                    case 'bd':
                        registroIndBenfDirectos();
                    break;

                    //  Gestion de Registro de Beneficiarios InDirectos
                    case 'bi':
                        registroIndBenfIndirectos()
                    break;

                    //  Gestion de Registro GAP
                    case 'gap':
                        registroGap();
                    break;

                    //  Gestion de Registro Enfoque Igualdad
                    case 'ei':
                        registroEnfoqueIgualdad()
                    break;

                    //  Gestion de Registro Enfoque Ecorae
                    case 'ee':
                        enfoqueEcorae();
                    break;

                    //  Gestion Otros Indicadores
                    case 'oi':
                        otrosIndicadores();
                    break;
                }
                
                //  Cierro la ventana modal( popup )
                window.parent.SqueezeBox.close();
            }else{
                jAlert( 'campos obligatorios', 'SIITA' );
            }

        } else {
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }


    function validarFomulario()
    {
        var ban = false;
        
        var idTpoIndicador  = jQuery( '#jform_idTpoIndicador' );
        var nombreIndicador = jQuery( '#jform_nombreIndicador' );
        var umbral          = jQuery( '#jform_umbralIndicador' );
        var idClase         = jQuery( '#jform_idClaseIndicador' );
        var idUndAnalisis   = jQuery( '#jform_intIdUndAnalisis' );
        var idTpoUM         = jQuery( '#jform_intIdTpoUndMedida' );
        var idUM            = jQuery( '#jform_idUndMedida' );
        
        var idHorizonte     = jQuery( '#jform_IdHorizonte' );
        var fchInicioHz     = jQuery( '#jform_hzFchInicio' );
        var fchFinHz        = jQuery( '#jform_hzFchFin' );
        var idFrcMonitoreo  = jQuery( '#jform_idFrcMonitoreo' );
        
        var idUG            = jQuery( '#jform_intIdUndGestion' );
        var fchInicioUG     = jQuery( '#jform_fchInicioPeriodoUG' );
        var idUGF           = jQuery( '#jform_intIdUGResponsable' );
        var idResponsable   = jQuery( '#jform_idResponsable' );
        var fchInicioF      = jQuery( '#jform_fchInicioPeriodoFuncionario' );
        
        if(     idTpoIndicador.val() !== ""
                && nombreIndicador.val() !== "" 
                && unformatNumber( umbral.val() ) !== 0 
                && idClase.val() !== "" 
                && idUndAnalisis.val() !== ""
                && idTpoUM.val() !== "" 
                && idUM.val() !== ""
                && idHorizonte.val() !== "" 
                && fchInicioHz.val() !== "" 
                && fchFinHz.val() !== "" 
                && idFrcMonitoreo.val() !== "" 
                && idUG.val() !== "" 
                && fchInicioUG.val() !== "" 
                && idUGF.val() !== "" 
                && idResponsable.val() !== "" 
                && fchInicioF.val() !== "" ){
            ban = true;
        }else{
            validarElemento( idTpoIndicador );
            validarElemento( nombreIndicador );
            validarElemento( umbral );
            validarElemento( idClase );
            validarElemento( idUndAnalisis );
            validarElemento( idTpoUM );
            validarElemento( idUM );
            validarElemento( idHorizonte );
            validarElemento( fchInicioHz );
            validarElemento( fchFinHz );
            validarElemento( idUG );
            validarElemento( idFrcMonitoreo );
            validarElemento( fchInicioUG );
            validarElemento( idUGF );
            validarElemento( idResponsable );
            validarElemento( fchInicioF );
        }

        return ban;
    }


    function validarElemento( obj )
    {
        var ban = 1;
        
        if( obj.val() === "" || obj.val() === "0" ){
            ban = 0;
            obj.attr( 'class', 'required invalid' );
            
            var lbl = obj.selector + '-lbl';
            jQuery( lbl ).attr( 'class', 'hasTip required invalid' );
            jQuery( lbl ).attr( 'aria-invalid', 'true' );
        }
        
        return ban;
    }

    function existeOtroIndicador(oi)
    {
        var lstOI = parent.window.objGestionIndicador.lstOtrosIndicadores;
        var ban = 0;
        for (var x = 0; x < lstOI.length; x++) {
            if (lstOI[x].toString() == oi.toString()) {
                ban = 1;
            }
        }

        return ban;
    }



    function registroIndEconomicos()
    {
        var dtaIndEconomico = parent.window.objGestionIndicador.indEconomico[idRegIndicador];

        var indEconomico = (typeOf(dtaIndEconomico) == 'number') 
                                ? new Indicador()
                                : dtaIndEconomico;

        indEconomico.idTpoIndicador = jQuery('#jform_idTpoIndicador').val();
        indEconomico.idClaseIndicador = jQuery('#jform_idClaseIndicador').val();
        indEconomico.idUndAnalisis = jQuery('#jform_intIdUndAnalisis').val();
        indEconomico.idTpoUndMedida = jQuery('#jform_intIdTpoUndMedida').val()
        indEconomico.idUndMedida = jQuery('#jform_idUndMedida').val();
        indEconomico.idTendencia = jQuery('#jform_idTendencia').val();
        indEconomico.idUGResponsable = jQuery('#jform_intIdUndGestion').val();
        indEconomico.idResponsableUG = jQuery('#jform_intIdUGResponsable').val();
        indEconomico.idResponsable = jQuery('#jform_idResponsable').val();
        indEconomico.idFrcMonitoreo = jQuery('#jform_idFrcMonitoreo').val();
        indEconomico.nombreIndicador        = jQuery('#jform_nombreIndicador').val();
        indEconomico.umbral                 = unformatNumber(jQuery('#jform_umbralIndicador').val());
        indEconomico.undMedida              = jQuery('#jform_idUndMedida :selected').text();
        indEconomico.descripcion            = jQuery('#jform_descripcionIndicador').val();
        indEconomico.formula                = jQuery('#formulaDescripcion').val();
        indEconomico.idHorizonte            = jQuery('#jform_IdHorizonte').val();
        indEconomico.fchHorzMimimo          = jQuery('#jform_hzFchInicio').val();
        indEconomico.fchHorzMaximo          = jQuery('#jform_hzFchFin').val();
        indEconomico.fchInicioUG            = jQuery('#jform_fchInicioPeriodoUG').val();
        indEconomico.fchInicioFuncionario   = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        indEconomico.umbMinimo              = jQuery('#jform_valMinimo').val();
        indEconomico.umbMaximo              = jQuery('#jform_valMaximo').val();
        indEconomico.formula                = jQuery('#formulaDescripcion').val();
        indEconomico.idGpoDimension         = jQuery('#jform_idGpoDimension').val();
        indEconomico.idGpoDecision          = jQuery('#jform_idGpoDecisiones').val();
        indEconomico.senplades              = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        indEconomico.metodologia    = jQuery('#jform_metodologia').val();
        indEconomico.limitaciones   = jQuery('#jform_limitacion').val();
        indEconomico.interpretacion = jQuery('#jform_interpretacion').val();
        indEconomico.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        indEconomico.lstLineaBase = new Array();

        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            indEconomico.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        indEconomico.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            indEconomico.lstPlanificacion.push(lstTmpPln[x]);
        }

        indEconomico.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            indEconomico.accesoTableu.push(strAccesoTableu[x]);
        }

        switch (parseInt(idRegIndicador)) {
            case 0:
                jQuery('#jform_intTasaDctoEco', parent.window.document).attr('value', indEconomico.umbral);
                break;

            case 1:
                jQuery('#jform_intValActualNetoEco', parent.window.document).attr('value', indEconomico.umbral);
                break;

            case 2:
                jQuery('#jform_intTIREco', parent.window.document).attr('value', indEconomico.umbral);
                break;
        }

        //  Cambio el color del semaforo del indicador
        var id = '#ECO' + idRegIndicador + 'AI';
        var ds = indEconomico.semaforoImagen();
        jQuery(id).html('<img src="' + ds["imgAtributo"] + '" class="hasTip" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">');

        return;
    }



    function registroIndFinancieros()
    {
        var dtaIndFinanciero = parent.window.objGestionIndicador.indFinanciero[idRegIndicador];

        var indFinanciero = (typeOf(dtaIndFinanciero) == 'number') 
                                ? new Indicador()
                                : dtaIndFinanciero;

        indFinanciero.idTpoIndicador        = jQuery('#jform_idTpoIndicador').val();
        indFinanciero.idClaseIndicador      = jQuery('#jform_idClaseIndicador').val();
        indFinanciero.idUndAnalisis         = jQuery('#jform_intIdUndAnalisis').val();
        indFinanciero.idTpoUndMedida        = jQuery('#jform_intIdTpoUndMedida').val()
        indFinanciero.idUndMedida           = jQuery('#jform_idUndMedida').val();
        indFinanciero.idTendencia           = jQuery('#jform_idTendencia').val();
        indFinanciero.idUGResponsable       = jQuery('#jform_intIdUndGestion').val();
        indFinanciero.idResponsableUG       = jQuery('#jform_intIdUGResponsable').val();
        indFinanciero.idResponsable         = jQuery('#jform_idResponsable').val();
        indFinanciero.idFrcMonitoreo        = jQuery('#jform_idFrcMonitoreo').val();
        indFinanciero.nombreIndicador       = jQuery('#jform_nombreIndicador').val();
        indFinanciero.umbral                = unformatNumber(jQuery('#jform_umbralIndicador').val());
        indFinanciero.undMedida             = jQuery('#jform_idUndMedida :selected').text();
        indFinanciero.descripcion           = jQuery('#jform_descripcionIndicador').val();
        indFinanciero.formula               = jQuery('#formulaDescripcion').val();
        indFinanciero.idHorizonte           = jQuery('#jform_IdHorizonte').val();
        indFinanciero.fchHorzMimimo         = jQuery('#jform_hzFchInicio').val();
        indFinanciero.fchHorzMaximo         = jQuery('#jform_hzFchFin').val();
        indFinanciero.fchInicioUG           = jQuery('#jform_fchInicioPeriodoUG').val();
        indFinanciero.fchInicioFuncionario  = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        indFinanciero.umbMinimo             = jQuery('#jform_valMinimo').val();
        indFinanciero.umbMaximo             = jQuery('#jform_valMaximo').val();
        indFinanciero.formula               = jQuery('#formulaDescripcion').val();
        indFinanciero.idGpoDimension        = jQuery('#jform_idGpoDimension').val();
        indFinanciero.idGpoDecision         = jQuery('#jform_idGpoDecisiones').val();
        indFinanciero.senplades             = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        indFinanciero.metodologia    = jQuery('#jform_metodologia').val();
        indFinanciero.limitaciones   = jQuery('#jform_limitacion').val();
        indFinanciero.interpretacion = jQuery('#jform_interpretacion').val();
        indFinanciero.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        indFinanciero.lstLineaBase = new Array();

        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            indFinanciero.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        indFinanciero.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            indFinanciero.lstPlanificacion.push(lstTmpPln[x]);
        }

        indFinanciero.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            indFinanciero.accesoTableu.push(strAccesoTableu[x]);
        }

        switch (parseInt(idRegIndicador)) {
            case 0:
                jQuery('#jform_intTasaDctoFin', parent.window.document).attr('value', indFinanciero.umbral);
                break;

            case 1:
                jQuery('#jform_intValActualNetoFin', parent.window.document).attr('value', indFinanciero.umbral);
                break;

            case 2:
                jQuery('#jform_intTIRFin', parent.window.document).attr('value', indFinanciero.umbral);
                break;
        }

        //  Cambia el color del semaforo del indicador
        var id = '#FIN' + idRegIndicador + 'AI';
        var ds = indFinanciero.semaforoImagen();
        jQuery(id, window.parent.document).html('<img src="' + ds["imgAtributo"] + '" class="hasTip" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">');

        return;
    }


    function registroIndBenfDirectos()
    {
        var dtaBDirecto = parent.window.objGestionIndicador.indBDirecto[idRegIndicador];

        var BDirecto = (typeOf(dtaBDirecto) == 'number') 
                            ? new Indicador()
                            : dtaBDirecto;

        BDirecto.idTpoIndicador         = jQuery('#jform_idTpoIndicador').val();
        BDirecto.idClaseIndicador       = jQuery('#jform_idClaseIndicador').val();
        BDirecto.idUndAnalisis          = jQuery('#jform_intIdUndAnalisis').val();
        BDirecto.idTpoUndMedida         = jQuery('#jform_intIdTpoUndMedida').val()
        BDirecto.idUndMedida            = jQuery('#jform_idUndMedida').val();
        BDirecto.idTendencia            = jQuery('#jform_idTendencia').val();
        BDirecto.idUGResponsable        = jQuery('#jform_intIdUndGestion').val();
        BDirecto.idResponsableUG        = jQuery('#jform_intIdUGResponsable').val();
        BDirecto.idResponsable          = jQuery('#jform_idResponsable').val();
        BDirecto.idFrcMonitoreo         = jQuery('#jform_idFrcMonitoreo').val();
        BDirecto.nombreIndicador        = jQuery('#jform_nombreIndicador').val();
        BDirecto.umbral                 = unformatNumber(jQuery('#jform_umbralIndicador').val());
        BDirecto.undMedida              = jQuery('#jform_idUndMedida :selected').text();
        BDirecto.descripcion            = jQuery('#jform_descripcionIndicador').val();
        BDirecto.formula                = jQuery('#formulaDescripcion').val();
        BDirecto.idHorizonte            = jQuery('#jform_IdHorizonte').val();
        BDirecto.fchHorzMimimo          = jQuery('#jform_hzFchInicio').val();
        BDirecto.fchHorzMaximo          = jQuery('#jform_hzFchFin').val();
        BDirecto.fchInicioUG            = jQuery('#jform_fchInicioPeriodoUG').val();
        BDirecto.fchInicioFuncionario   = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        BDirecto.umbMinimo              = jQuery('#jform_valMinimo').val();
        BDirecto.umbMaximo              = jQuery('#jform_valMaximo').val();
        BDirecto.formula                = jQuery('#formulaDescripcion').val();
        BDirecto.idGpoDimension         = jQuery('#jform_idGpoDimension').val();
        BDirecto.idGpoDecision          = jQuery('#jform_idGpoDecisiones').val();
        BDirecto.senplades              = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        BDirecto.metodologia    = jQuery('#jform_metodologia').val();
        BDirecto.limitaciones   = jQuery('#jform_limitacion').val();
        BDirecto.interpretacion = jQuery('#jform_interpretacion').val();
        BDirecto.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        BDirecto.lstLineaBase = new Array();

        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            BDirecto.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        BDirecto.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            BDirecto.lstPlanificacion.push(lstTmpPln[x]);
        }

        BDirecto.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            BDirecto.accesoTableu.push(strAccesoTableu[x]);
        }

        switch (parseInt(idRegIndicador)) {
            case 0:
                jQuery('#jform_intBenfDirectoHombre', parent.window.document).attr('value', BDirecto.umbral);
                break;

            case 1:
                jQuery('#jform_intBenfDirectoMujer', parent.window.document).attr('value', BDirecto.umbral);
                break;

            case 2:
                jQuery('#jform_intTotalBenfDirectos', parent.window.document).attr('value', BDirecto.umbral);
                break;
        }

        //  Cambia el color del semaforo del indicador
        var id = '#BD' + idRegIndicador + 'AI';
        var ds = BDirecto.semaforoImagen();
        jQuery(id, window.parent.document).html('<img src="' + ds["imgAtributo"] + '" class="hasTip" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">');

        return;
    }


    function registroIndBenfIndirectos()
    {
        var dtaBInDirecto = parent.window.objGestionIndicador.indBIndirecto[idRegIndicador];

        var BInDirecto = (typeOf(dtaBInDirecto) == 'number') ? new Indicador()
                : dtaBInDirecto;

        BInDirecto.idTpoIndicador       = jQuery('#jform_idTpoIndicador').val();
        BInDirecto.idClaseIndicador     = jQuery('#jform_idClaseIndicador').val();
        BInDirecto.idUndAnalisis        = jQuery('#jform_intIdUndAnalisis').val();
        BInDirecto.idTpoUndMedida       = jQuery('#jform_intIdTpoUndMedida').val()
        BInDirecto.idUndMedida          = jQuery('#jform_idUndMedida').val();
        BInDirecto.idTendencia          = jQuery('#jform_idTendencia').val();
        BInDirecto.idUGResponsable      = jQuery('#jform_intIdUndGestion').val();
        BInDirecto.idResponsableUG      = jQuery('#jform_intIdUGResponsable').val();
        BInDirecto.idResponsable        = jQuery('#jform_idResponsable').val();
        BInDirecto.idFrcMonitoreo       = jQuery('#jform_idFrcMonitoreo').val();
        BInDirecto.nombreIndicador      = jQuery('#jform_nombreIndicador').val();
        BInDirecto.umbral               = unformatNumber(jQuery('#jform_umbralIndicador').val());
        BInDirecto.undMedida            = jQuery('#jform_idUndMedida :selected').text();
        BInDirecto.descripcion          = jQuery('#jform_descripcionIndicador').val();
        BInDirecto.formula              = jQuery('#formulaDescripcion').val();
        BInDirecto.idHorizonte          = jQuery('#jform_IdHorizonte').val();
        BInDirecto.fchHorzMimimo        = jQuery('#jform_hzFchInicio').val();
        BInDirecto.fchHorzMaximo        = jQuery('#jform_hzFchFin').val();
        BInDirecto.fchInicioUG          = jQuery('#jform_fchInicioPeriodoUG').val();
        BInDirecto.fchInicioFuncionario = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        BInDirecto.umbMinimo            = jQuery('#jform_valMinimo').val();
        BInDirecto.umbMaximo            = jQuery('#jform_valMaximo').val();
        BInDirecto.formula              = jQuery('#formulaDescripcion').val();
        BInDirecto.idGpoDimension       = jQuery('#jform_idGpoDimension').val();
        BInDirecto.idGpoDecision        = jQuery('#jform_idGpoDecisiones').val();
        BInDirecto.senplades            = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        BInDirecto.metodologia    = jQuery('#jform_metodologia').val();
        BInDirecto.limitaciones   = jQuery('#jform_limitacion').val();
        BInDirecto.interpretacion = jQuery('#jform_interpretacion').val();
        BInDirecto.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        BInDirecto.lstLineaBase = new Array();

        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            BInDirecto.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        BInDirecto.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            BInDirecto.lstPlanificacion.push(lstTmpPln[x]);
        }

        BInDirecto.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            BInDirecto.accesoTableu.push(strAccesoTableu[x]);
        }

        switch (parseInt(idRegIndicador)) {
            case 0:
                jQuery('#jform_intBenfIndDirectoHombre', parent.window.document).attr('value', BInDirecto.umbral);
                break;

            case 1:
                jQuery('#jform_intBenfIndDirectoMujer', parent.window.document).attr('value', BInDirecto.umbral);
                break;

            case 2:
                jQuery('#jform_intTotalBenfIndDirectos', parent.window.document).attr('value', BInDirecto.umbral);
                break;
        }

        //  Cambia el color del semaforo del indicador
        var id = '#BI' + idRegIndicador + 'AI';
        var ds = BInDirecto.semaforoImagen();
        jQuery(id, window.parent.document).html('<img src="' + ds["imgAtributo"] + '" class="hasTip" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">');

        return;
    }


    function registroGap()
    {
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
        GAP.idTpoIndicador      = jQuery('#jform_idTpoIndicador').val();
        GAP.idClaseIndicador    = jQuery('#jform_idClaseIndicador').val();
        GAP.idUndAnalisis       = jQuery('#jform_intIdUndAnalisis').val();
        GAP.idTpoUndMedida      = jQuery('#jform_intIdTpoUndMedida').val()
        GAP.idUndMedida         = jQuery('#jform_idUndMedida').val();
        GAP.idTendencia         = jQuery('#jform_idTendencia').val();
        GAP.idUGResponsable     = jQuery('#jform_intIdUndGestion').val();
        GAP.idResponsableUG     = jQuery('#jform_intIdUGResponsable').val();
        GAP.idResponsable       = jQuery('#jform_idResponsable').val();
        GAP.idFrcMonitoreo      = jQuery('#jform_idFrcMonitoreo').val();
        GAP.nombreIndicador     = jQuery('#jform_nombreIndicador').val();
        GAP.umbral              = unformatNumber(jQuery('#jform_umbralIndicador').val());
        GAP.undMedida           = jQuery('#jform_idUndMedida :selected').text();
        GAP.descripcion         = jQuery('#jform_descripcionIndicador').val();
        GAP.formula             = jQuery('#formulaDescripcion').val();
        GAP.idHorizonte         = jQuery('#jform_IdHorizonte').val();
        GAP.fchHorzMimimo       = jQuery('#jform_hzFchInicio').val();
        GAP.fchHorzMaximo       = jQuery('#jform_hzFchFin').val();
        GAP.fchInicioUG         = jQuery('#jform_fchInicioPeriodoUG').val();
        GAP.fchInicioFuncionario= jQuery('#jform_fchInicioPeriodoFuncionario').val();
        GAP.umbMinimo           = jQuery('#jform_valMinimo').val();
        GAP.umbMaximo           = jQuery('#jform_valMaximo').val();
        GAP.formula             = jQuery('#formulaDescripcion').val();
        GAP.idGpoDimension      = jQuery('#jform_idGpoDimension').val();
        GAP.idGpoDecision       = jQuery('#jform_idGpoDecisiones').val();
        GAP.senplades           = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        GAP.metodologia    = jQuery('#jform_metodologia').val();
        GAP.limitaciones   = jQuery('#jform_limitacion').val();
        GAP.interpretacion = jQuery('#jform_interpretacion').val();
        GAP.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        GAP.lstLineaBase = new Array();
        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            GAP.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        GAP.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            GAP.lstPlanificacion.push(lstTmpPln[x]);
        }

        GAP.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            GAP.accesoTableu.push(strAccesoTableu[x]);
        }

        //  Cambia el color del semaforo del indicador
        var id = '#ai' + tpo + '-' + tpoIndicador + '-' + idRegIndicador;
        var color = GAP.semaforoValor();
        updSemaforoAttrInd(id, color);
    }

    function registroEnfoqueIgualdad()
    {
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
        EI.idTpoIndicador       = jQuery('#jform_idTpoIndicador').val();
        EI.idClaseIndicador     = jQuery('#jform_idClaseIndicador').val();
        EI.idUndAnalisis        = jQuery('#jform_intIdUndAnalisis').val();
        EI.idTpoUndMedida       = jQuery('#jform_intIdTpoUndMedida').val()
        EI.idUndMedida          = jQuery('#jform_idUndMedida').val();
        EI.idTendencia          = jQuery('#jform_idTendencia').val();
        EI.idUGResponsable      = jQuery('#jform_intIdUndGestion').val();
        EI.idResponsableUG      = jQuery('#jform_intIdUGResponsable').val();
        EI.idResponsable        = jQuery('#jform_idResponsable').val();
        EI.idFrcMonitoreo       = jQuery('#jform_idFrcMonitoreo').val();
        EI.nombreIndicador      = jQuery('#jform_nombreIndicador').val();
        EI.umbral               = unformatNumber(jQuery('#jform_umbralIndicador').val());
        EI.undMedida            = jQuery('#jform_idUndMedida :selected').text();
        EI.descripcion          = jQuery('#jform_descripcionIndicador').val();
        EI.formula              = jQuery('#formulaDescripcion').val();
        EI.idHorizonte          = jQuery('#jform_IdHorizonte').val();
        EI.fchHorzMimimo        = jQuery('#jform_hzFchInicio').val();
        EI.fchHorzMaximo        = jQuery('#jform_hzFchFin').val();
        EI.fchInicioUG          = jQuery('#jform_fchInicioPeriodoUG').val();
        EI.fchInicioFuncionario = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        EI.umbMinimo            = jQuery('#jform_valMinimo').val();
        EI.umbMaximo            = jQuery('#jform_valMaximo').val();
        EI.formula              = jQuery('#formulaDescripcion').val();
        EI.idGpoDimension       = jQuery('#jform_idGpoDimension').val();
        EI.idGpoDecision        = jQuery('#jform_idGpoDecisiones').val();
        EI.senplades            = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        EI.metodologia    = jQuery('#jform_metodologia').val();
        EI.limitaciones   = jQuery('#jform_limitacion').val();
        EI.interpretacion = jQuery('#jform_interpretacion').val();
        EI.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        EI.lstLineaBase = new Array();
        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            EI.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        EI.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            EI.lstPlanificacion.push(lstTmpPln[x]);
        }

        EI.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            EI.accesoTableu.push(strAccesoTableu[x]);
        }

        //  Cambia el color del semaforo del indicador
        var id = '#ai' + tpo + '-' + tpoIndicador + '-' + idRegIndicador;
        var color = EI.semaforoValor();
        updSemaforoAttrInd(id, color);

        return;
    }


    function enfoqueEcorae()
    {
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
        EE.idTpoIndicador       = jQuery('#jform_idTpoIndicador').val();
        EE.idClasEEndicador     = jQuery('#jform_idClasEEndicador').val();
        EE.idUndAnalisis        = jQuery('#jform_intIdUndAnalisis').val();
        EE.idTpoUndMedida       = jQuery('#jform_intIdTpoUndMedida').val()
        EE.idUndMedida          = jQuery('#jform_idUndMedida').val();
        EE.idTendencia          = jQuery('#jform_idTendencia').val();
        EE.idUGResponsable      = jQuery('#jform_intIdUndGestion').val();
        EE.idResponsableUG      = jQuery('#jform_intIdUGResponsable').val();
        EE.idResponsable        = jQuery('#jform_idResponsable').val();
        EE.idFrcMonitoreo       = jQuery('#jform_idFrcMonitoreo').val();
        EE.nombreIndicador      = jQuery('#jform_nombreIndicador').val();
        EE.umbral               = unformatNumber(jQuery('#jform_umbralIndicador').val());
        EE.undMedida            = jQuery('#jform_idUndMedida :selected').text();
        EE.descripcion          = jQuery('#jform_descripcionIndicador').val();
        EE.formula              = jQuery('#formulaDescripcion').val();
        EE.idHorizonte          = jQuery('#jform_IdHorizonte').val();
        EE.fchHorzMimimo        = jQuery('#jform_hzFchInicio').val();
        EE.fchHorzMaximo        = jQuery('#jform_hzFchFin').val();
        EE.fchInicioUG          = jQuery('#jform_fchInicioPeriodoUG').val();
        EE.fchInicioFuncionario = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        EE.umbMinimo            = jQuery('#jform_valMinimo').val();
        EE.umbMaximo            = jQuery('#jform_valMaximo').val();
        EE.formula              = jQuery('#formulaDescripcion').val();
        EE.idGpoDimension       = jQuery('#jform_idGpoDimension').val();
        EE.idGpoDecision        = jQuery('#jform_idGpoDecisiones').val();
        EE.senplades            = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        EE.metodologia    = jQuery('#jform_metodologia').val();
        EE.limitaciones   = jQuery('#jform_limitacion').val();
        EE.interpretacion = jQuery('#jform_interpretacion').val();
        EE.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        EE.lstLineaBase = new Array();
        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            EE.lstLineaBase.push(lstTmpLB[x]);
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

        //  Vacío Lista de Planificacion
        EE.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            EE.lstPlanificacion.push(lstTmpPln[x]);
        }

        EE.accesoTableu = new Array();
        //  Agrego Lista editada de Acceso a Tableu
        for (var x = 0; x < strAccesoTableu.length; x++) {
            EE.accesoTableu.push(strAccesoTableu[x]);
        }

        //  Cambia el color del semaforo del indicador
        var id = '#ai' + tpo + '-' + tpoIndicador + '-' + idRegIndicador;
        var color = EE.semaforoValor();
        updSemaforoAttrInd(id, color);
    }


    function otrosIndicadores()
    {
        var OI;
        var idRegistro;
        var objOtroInd;
        var idIndicador;
        var gi = new parent.window.GestionIndicador();

        if (idRegIndicador == -1) {
            OI = new parent.window.Indicador();
            objOtroInd = parent.window.objGestionIndicador.lstOtrosIndicadores;
            idRegistro = objOtroInd.length;
            idIndicador = 0;
        } else {
            OI = parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador];
            idIndicador = OI.idIndicador;
            idRegistro = idRegIndicador;
        }

        //  Si el indicador es nuevo le asigno un nuevo identificador de registro
        OI.idRegIndicador       = idRegistro;
        OI.idTpoIndicador       = jQuery('#jform_idTpoIndicador').val();
        OI.idClasOIndicador     = jQuery('#jform_idClasOIndicador').val();
        OI.idUndAnalisis        = jQuery('#jform_intIdUndAnalisis').val();
        OI.idTpoUndMedida       = jQuery('#jform_intIdTpoUndMedida').val()
        OI.idUndMedida          = jQuery('#jform_idUndMedida').val();
        OI.idTendencia          = jQuery('#jform_idTendencia').val();
        OI.idUGResponsable      = jQuery('#jform_intIdUndGestion').val();
        OI.idResponsableUG      = jQuery('#jform_intIdUGResponsable').val();
        OI.idResponsable        = jQuery('#jform_idResponsable').val();
        OI.idFrcMonitoreo       = jQuery('#jform_idFrcMonitoreo').val();
        OI.nombreIndicador      = jQuery('#jform_nombreIndicador').val();
        OI.umbral               = unformatNumber(jQuery('#jform_umbralIndicador').val());
        OI.undMedida            = jQuery('#jform_idUndMedida :selected').text();
        OI.descripcion          = jQuery('#jform_descripcionIndicador').val();
        OI.formula              = jQuery('#formulaDescripcion').val();
        OI.idHorizonte          = jQuery('#jform_IdHorizonte').val();
        OI.fchHorzMimimo        = jQuery('#jform_hzFchInicio').val();
        OI.fchHorzMaximo        = jQuery('#jform_hzFchFin').val();
        OI.fchInicioUG          = jQuery('#jform_fchInicioPeriodoUG').val();
        OI.fchInicioFuncionario = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        OI.umbMinimo            = jQuery('#jform_valMinimo').val();
        OI.umbMaximo            = jQuery('#jform_valMaximo').val();
        OI.formula              = jQuery('#formulaDescripcion').val();
        OI.idGpoDimension       = jQuery('#jform_idGpoDimension').val();
        OI.idGpoDecision        = jQuery('#jform_idGpoDecisiones').val();
        OI.senplades            = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

        //  Informacion complementaria
        OI.metodologia    = jQuery('#jform_metodologia').val();
        OI.limitaciones   = jQuery('#jform_limitacion').val();
        OI.interpretacion = jQuery('#jform_interpretacion').val();
        OI.disponibilidad = jQuery('#jform_disponibilidad').val();

        //  Vacío Lista de Lineas Base
        OI.lstLineaBase = new Array();
        //  Agrego Lista editada de Lineas Base
        for (var x = 0; x < lstTmpLB.length; x++) {
            OI.lstLineaBase.push(lstTmpLB[x]);
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
        for (var x = 0; x < lstTmpDim.length; x++) {
            OI.lstDimensiones.push(lstTmpDim[x]);
        }

        //  Vacío Lista de Planificacion
        OI.lstPlanificacion = new Array();
        //  Agrego Lista editada de Planificacion
        for (var x = 0; x < lstTmpPln.length; x++) {
            OI.lstPlanificacion.push(lstTmpPln[x]);
        }

        if (idRegIndicador == -1) {
            if (existeOtroIndicador(OI) == 0) {
                //  Agrego indicador a lista de Nuevos Indicadores
                objOtroInd.push(OI);

                //  Agrego la fila creada a la tabla
                jQuery('#lstOtrosInd > tbody:last', window.parent.document).append(gi.addFilaOtroIndicador(OI, 0));
            } else {
                jAlert(COM_INDICADORES_INDICADOR_YA_REGISTRADO, COM_INDICADORES_SISTEMA_SIITA);
            }

        } else {
            //  Actualizo contenido Fila
            gi.updInfoFilaOI(idRegIndicador, gi.addFilaOtroIndicador(OI, 1));
        }

        //  Cambia el color del semaforo del indicador
        var id = '#ai-oi-' + idRegistro;
        var color = OI.semaforoValor();
        updSemaforoAttrInd(id, color);
    }



})

function updSemaforoAttrInd(id, color)
{
    switch (color) {
        case 0:// amarillo
            jQuery(id, window.parent.document).css('background-position', '0 0px');
            break;
        case 1:// blanco
            jQuery(id, window.parent.document).css('background-position', '0 -66px');
            break;
        case 2:// rojo
            jQuery(id, window.parent.document).css('background-position', '0 -132px');
            break;
        case 3:// verde
            jQuery(id, window.parent.document).css('background-position', '0 -198px');
            break;
    }
}
;

var decodeHtmlEntity = function(str) {
    if (typeOf(str) != "null") {
        return str.replace(/&/g, '&amp;').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&apos;/g, '\'').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;', function(match, dec) {
            return String.fromCharCode(dec);
        });
    }
};


var encodeHtmlEntity = function(str) {
    var buf = [];
    for (var i = str.length - 1; i >= 0; i--) {
        buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
    }
    return buf.join('');
};


jQuery('#jform_strAccesoTableu').live('blur', function() {
    var htmlTb      = jQuery(this).val();
    var div1        = jQuery(htmlTb);

    var src         = jQuery(div1[0])[0].src;
    var host_name   = jQuery(div1[2].childNodes[1].children[0])[0].value;
    var site_root   = jQuery(div1[2].childNodes[1].children[1])[0].value;
    var name        = jQuery(div1[2].childNodes[1].children[2])[0].value;
    var tabs        = jQuery(div1[2].childNodes[1].children[3])[0].value;
    var toolbar     = jQuery(div1[2].childNodes[1].children[4])[0].value;

    jQuery('#jform_url_script').attr('value', src);
    jQuery('#jform_host_url').attr('value', host_name);
    jQuery('#jform_site_root').attr('value', site_root);
    jQuery('#jform_tabs').attr('value', name);

    strAccesoTableu["src"] = src;
    strAccesoTableu["host_name"] = host_name;
    strAccesoTableu["site_root"] = site_root;
    strAccesoTableu["name"] = name;
    strAccesoTableu["tabs"] = tabs;
    strAccesoTableu["toolbar"] = toolbar;
})