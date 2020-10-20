jQuery( document ).ready( function(){
    //  Obtengo el identificador del contexto a gestionar
    var idObjetivo      = jQuery('#idRegObjetivo').val();
    var tipoIndicador   = jQuery('#tpoIndicador').val();
    var idPlan          = jQuery('#idPlan').val();
    var idPoa           = jQuery('#idPoa').val();
    var idRegObjetivo   = jQuery('#idRegObjetivo').val();
    
    var valorMeta;
    var fchInicioPlan;
    var fchFinPlan;
    var idRegIndMeta;
    var dtaIndMeta;
    var dtaIndMetaEconomico;
    var lstIndObjetivos;
    
    lstTmpLB            = new Array();
    lstTmpRG            = new Array();
    lstTmpUT            = new Array();
    lstTmpVar           = new Array();
    lstTmpDim           = new Array();
    lstTmpPln           = new Array();
    lstTmpIndicadores   = new Array();
    lstTmpSg            = new Array();
    
    switch( tipoIndicador ){
        case 'pei':
            //  Accedo a lista de objetivos del PEI desde la ventana padre
            lstIndObjetivos = window.parent.objLstObjetivo.lstObjetivos[idObjetivo];
        break;

        case 'pppp':
            //  Accedo a lista de objetivos de tipo PPPP desde la ventana padre
            lstIndObjetivos = parent.window.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo];
        break;

        case 'papp':
            //  Accedo a lista de objetivos de tipo PPPP desde la ventana padre
            lstIndObjetivos = window.parent.oLstPAPPs.lstPapp[idPlan].lstObjetivos[idObjetivo];
        break;
        
        //  POA por Unidad de Gestion
        case 'ug':
            lstIndObjetivos = parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo];
        break;
        
        //  POA por Funcionario
        case 'fun':
            lstIndObjetivos = parent.window.objLstPoas.lstPoas[idPoa].lstObjetivos[idObjetivo];
        break;
        
        //  Programas - Proyectos - Contratos - Convenios
        default:
            lstIndObjetivos = parent.window.objLstObjetivo.lstObjetivos[idRegObjetivo];
        break;
    }
    
    //  Obtengo informacion de los indicadores asociados a un objetivo
    var dtaLstIndicadores = ( typeOf( lstIndObjetivos ) !== "null" )
        ? lstIndObjetivos.lstIndicadores
        : new Array();

    //  Busco Indicador meta en la lista de indicadores 
    dtaIndMeta = ( tipoIndicador !== 'ime' )? getIndMeta( dtaLstIndicadores )
                                            : dtaIndMetaEconomico;

    //  Actualizo formulario con informacion del Indicador Meta
    if( dtaIndMeta ){
        updFrmIndicador( dtaIndMeta );
    }else{
        recorrerCombo( jQuery('#jform_idTpoIndicador option'), 1 );
        recorrerCombo( jQuery('#jform_IdHorizonte option'), 8 );
        recorrerCombo( jQuery('#jform_idFrcMonitoreo option'), 7 );

        fchInicioPlan = jQuery( '#jform_dteFechainicio_pi', window.parent.document ).val();
        fchFinPlan = jQuery( '#jform_dteFechafin_pi', window.parent.document ).val();

        jQuery('#jform_hzFchInicio').attr('value', fchInicioPlan );
        jQuery('#jform_hzFchFin').attr('value', fchFinPlan );

        jQuery('#jform_fchInicioPeriodoUG').attr('value', fchInicioPlan );
        jQuery('#jform_fchInicioPeriodoFuncionario').attr('value', fchInicioPlan );

        jQuery( '#tabsAttrIndicador' ).tabs( { disabled: [8] } );
    }

    jQuery( '#jform_umbralIndicador' ).on( 'change', function(){
        
        if( lstTmpRG.length > 0 ){
            //  Emito un mensaje de confirmacion antes ACTUALIZAR el valor del Umbral
            jConfirm( COM_INDICADORES_UPD_UMBRAL, COM_INIDCADORES_SIITA, function( result ){
                if( result ){
                    lstTmpRG = new Array();
                    jQuery('#btnAddRango').trigger( 'click' );
                }
            });
        }else{
            jQuery('#btnAddRango').trigger( 'click' );
        }
    })
    
    /**
     * 
     * Seteo informacion de Lineas base
     * 
     * @param {Array} lstLB     Lista de Objetos con Informacion de Lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function setDtaLineasBase( lstLB )
    {
        lstTmpLB = new Array();

        var objLB;
        for( var x = 0; x < lstLB.length; x++ ){
            objLB = new LineaBase();
            objLB.setDtaLineaBase( lstLB[x] );
            objLB.idRegLB = x;

            //  Agrego una fila a la tabla de lineas base
            jQuery( '#lstLineasBase > tbody:last' ).append( objLB.getFilaLineaBase( 0 ) );
            lstTmpLB.push( objLB );
        }
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
    function setDtaUT( lstUT )
    {
        lstTmpUT = new Array();

        var objUT;
        for( var x = 0; x < lstUT.length; x++ ){
            objUT = new UnidadTerritorial();
            objUT.setDtaUT( lstUT[x] );

            //  Agrego una fila a la tabla de lineas base
            jQuery( '#lstUndTerritorialesInd > tbody:last' ).append( objUT.addFilaUT( 0 ) );
            lstTmpUT.push( objUT );
        }
    }
    
    
    /**
     * 
     * Seteo Informacion de Rangos de Gestion
     * 
     * @param {type} lstRg      Lista de Informacion de Rangos de Gestion
     * 
     * @returns {undefined}
     * 
     */
    function setDtaRangos( lstRg, idTpoUndMedida, idUndMedida )
    {
        lstTmpRG = new Array();
        var nrgs = lstRg.length;

        if( nrgs > 0 ){
            for( var x = 0; x < nrgs; x++ ){
                var objRg = new Rango();
                objRg.setDtaRango( lstRg[x], idTpoUndMedida, idUndMedida );
                objRg.idRegRG = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery( '#lstRangos > tbody:last' ).append( objRg.addFilaRG( 0 ) );
                lstTmpRG.push( objRg );
            }
        }
        
        if( lstTmpRG.length === 3 ){
            jQuery( '#addLnRangoTable' ).attr( 'disabled', 'disabled' );
        }

        return;
    }
    
    /**
     * 
     * Seteo Informacion de Dimesiones de un indicador
     * 
     * @param {type} lstDim      Lista de Informacion de Enfoques
     * 
     * @returns {undefined}
     * 
     */
    function setDtaDimensiones( lstDim )
    {
        lstTmpDim = new Array();
        var objDim;

        for( var x = 0; x < lstDim.length; x++ ){
            objDim = new Dimension();
            objDim.setDtaDimension( lstDim[x] );
            objDim.idRegDimension = x;

            //  Agrego una fila a la tabla de lineas base
            jQuery( '#lstDimensiones > tbody:last' ).append( objDim.addFilaDimension( 0 ) );
            lstTmpDim.push( objDim );
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
    function setDtaVariables( lstVar, idTpoUndMedida, idUndMedida )
    {
        lstTmpVar = new Array();
        var objVar;

        for( var x = 0; x < lstVar.length; x++ ){
            objVar = new Variable();
            lstVar[x].idRegVar = x;
            objVar.setDtaVariable( lstVar[x], idTpoUndMedida, idUndMedida );

            //  Agrego una fila a la tabla de Variables
            jQuery( '#lstVarIndicadores > tbody:last' ).append( objVar.addFilaVar( 0 ) );
            lstTmpVar.push( objVar );
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
    function setDtaFormula( lstVar )
    {
        lstTmpVar = new Array();
        var objVar;

        if( lstVar.length ){
            for( var x = 0; x < lstVar.length; x++ ){
                objVar = new Variable();
                lstVar[x].idRegVar = x;
                objVar.setDtaVariable( lstVar[x] );
                lstTmpVar.push( objVar );

                switch( true ){
                    //  Variable Numerador
                    case ( parseInt( objVar.idTpoElemento ) === 1 || parseInt( objVar.idTpoElemento ) === 3 ): 
                        jQuery( '#numerador' ).attr( 'value', objVar.nombre );
                    break;

                    //  Variable Denominador
                    case ( parseInt( objVar.idTpoElemento ) === 2 || parseInt( objVar.idTpoElemento ) === 4 ): 
                        jQuery( '#denominador' ).attr( 'value', objVar.nombre );
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
    function setDtaPlanificacion( lstPln )
    {
        lstTmpPln = new Array();
        var nrpln = lstPln.length;
        var objPln;

        if( nrpln > 0 ){
            for( var x = 0; x < nrpln; x++ ){
                objPln = new Planificacion();
                lstPln[x].idRegPln = x;
                objPln.setDtaPlanificacion( lstPln[x] );

                //  Agrego una fila a la tabla de Variables
                jQuery( '#lstPlanificacionIndicadores > tbody:last' ).append( objPln.addFilaPln( 0 ) );
                lstTmpPln.push( objPln );
            }
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

        if( nrp ){
            for( var x = 0; x < nrp; x++ ){
                ntp += parseFloat( lstTmpPln[x].valor );
            }    
        }

        return ntp.toFixed( 2 );
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
    
    /**
     * 
     * Busco la existencia del Indicador Meta, indicador de tipo 3
     * 
     * @param {type} lstIndObjetivos    Lista de Indicadores del Objetivo
     * @returns {Boolean}
     */
    function getIndMeta( lstIndObjetivos )
    {
        var dtaIndMeta = false;

        for( var x = 0; x < lstIndObjetivos.length; x++ ){
            if( parseInt( lstIndObjetivos[x].idTpoIndicador ) == 1 ){
                var objIndMeta = new Indicador();
                objIndMeta.setDtaIndicador( lstIndObjetivos[x] );
                dtaIndMeta = objIndMeta;
                idRegIndMeta = x;

                break;
            }
        }
        
        return dtaIndMeta;
    }
    
    /**
     * 
     * Gestiona las operaciones:
     *      Asignacion de Indicadores
     *      Cancelar Transaccion
     * 
     * @param {type} task       Tarea a Ejecutar
     * @returns {undefined}
     * 
     */
    Joomla.submitbutton = function(task)
    {
        switch (task){
            case 'indicador.asignar':
                if( validarFrmIndMeta() ){
                    var objIndMeta;

                    if( dtaIndMeta == false || typeof( dtaIndMeta ) == 'undefined' ){
                        objIndMeta = new Indicador();
                    }else{
                        objIndMeta = dtaIndMeta;
                    }

                    objIndMeta.idTpoIndicador       = jQuery( '#jform_idTpoIndicador' ).val();
                    objIndMeta.idClaseIndicador     = jQuery( '#jform_idClaseIndicador' ).val();
                    objIndMeta.idUndAnalisis        = jQuery( '#jform_intIdUndAnalisis' ).val();
                    objIndMeta.idTpoUndMedida       = jQuery( '#jform_intIdTpoUndMedida' ).val()
                    objIndMeta.idUndMedida          = jQuery( '#jform_idUndMedida' ).val();
                    objIndMeta.idTendencia          = jQuery( '#jform_idTendencia' ).val();
                    objIndMeta.idUGResponsable      = jQuery( '#jform_intIdUndGestion' ).val();
                    objIndMeta.idResponsableUG      = jQuery( '#jform_intIdUGResponsable' ).val();
                    objIndMeta.idResponsable        = jQuery( '#jform_idResponsable' ).val();
                    objIndMeta.idFrcMonitoreo       = jQuery( '#jform_idFrcMonitoreo' ).val();
                    objIndMeta.nombreIndicador      = jQuery( '#jform_nombreIndicador' ).val();
                    objIndMeta.umbral               = unformatNumber( jQuery( '#jform_umbralIndicador' ).val() );
                    objIndMeta.undMedida            = jQuery( '#jform_idUndMedida :selected' ).text();
                    objIndMeta.descripcion          = jQuery( '#jform_descripcionIndicador' ).val();
                    objIndMeta.formula              = jQuery( '#formulaDescripcion' ).val();
                    objIndMeta.idHorizonte          = jQuery( '#jform_IdHorizonte' ).val();
                    objIndMeta.fchHorzMimimo        = jQuery( '#jform_hzFchInicio' ).val();
                    objIndMeta.fchHorzMaximo        = jQuery( '#jform_hzFchFin' ).val();
                    objIndMeta.fchInicioUG          = jQuery( '#jform_fchInicioPeriodoUG' ).val();
                    objIndMeta.fchInicioFuncionario = jQuery( '#jform_fchInicioPeriodoFuncionario' ).val();
                    objIndMeta.umbMinimo            = jQuery( '#jform_valMinimo' ).val();
                    objIndMeta.umbMaximo            = jQuery( '#jform_valMaximo' ).val();
                    objIndMeta.formula              = jQuery( '#formulaDescripcion' ).val();
                    objIndMeta.idGpoDimension       = jQuery( '#jform_idGpoDimension' ).val();
                    objIndMeta.idGpoDecision        = jQuery( '#jform_idGpoDecisiones' ).val();
                    objIndMeta.senplades            = jQuery( "input[name='jform[intSenplades_indEnt]']:checked" ).val();

                    //  Informacion complementaria
                    objIndMeta.metodologia          = jQuery('#jform_metodologia').val();
                    objIndMeta.limitaciones         = jQuery('#jform_limitacion').val();
                    objIndMeta.interpretacion       = jQuery('#jform_interpretacion').val();
                    objIndMeta.disponibilidad       = jQuery('#jform_disponibilidad').val();

                    //  Actualizo informacion de Lineas Base
                    objIndMeta.lstLineaBase = new Array();
                    for (var x = 0; x < lstTmpLB.length; x++) {
                        objIndMeta.lstLineaBase.push( lstTmpLB[x] );
                    }

                    //  Actualizo informacion de Unidades Territoriales
                    objIndMeta.lstUndsTerritoriales = new Array();
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        objIndMeta.lstUndsTerritoriales.push( lstTmpUT[x] );
                    }

                    //  Actualizo informacion de Rangos de Gestion
                    objIndMeta.lstRangos = new Array();
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        objIndMeta.lstRangos.push( lstTmpRG[x] );
                    }

                    //  Actualizo informacion de Dimensiones
                    objIndMeta.lstDimensiones = new Array();
                    for (var x = 0; x < lstTmpDim.length; x++) {
                        objIndMeta.lstDimensiones.push( lstTmpDim[x] );
                    }

                    //  Actualizo informacion de Variables
                    objIndMeta.lstVariables = new Array();
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        objIndMeta.lstVariables.push( lstTmpVar[x] );
                    }

                    //  Actualizo informacion de Planificacion
                    objIndMeta.lstPlanificacion = new Array();
                    //  Agrego Lista editada de Planificacion
                    for (var x = 0; x < lstTmpPln.length; x++) {
                        objIndMeta.lstPlanificacion.push(lstTmpPln[x]);
                    }

                    switch( tipoIndicador ){

                        //  POA por Funcionario
                        case 'programa':
                            if( dtaIndMeta == false ){
                                parent.window.objLstObjetivo.lstObjetivos[idRegObjetivo].lstIndicadores.push( objIndMeta );
                            }else{
                                parent.window.objLstObjetivo.lstObjetivos[idRegObjetivo].lstIndicadores[idRegIndMeta] = objIndMeta;
                            }
                        break;

                        default: 
                            //  En caso que el indicador meta no este registrado 
                            //  lo agrego a la lista de indicadores asociados al Objetivo
                            if( dtaIndMeta == false ){
                                 //  Agrego Indicador Meta a la lista indicadores
                                window.parent.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores.push( objIndMeta );
                            }else{
                                //  Caso contrario lo actualizo
                                window.parent.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores[idRegIndMeta] = objIndMeta;
                            }
                        break;
                    }

                    semaforoIndMetaObj( objIndMeta.semaforoImagen() );

                    lstTmpLB = new Array();
                    lstTmpUT = new Array();
                    lstTmpRG = new Array();
                    lstTmpVar = new Array();
                    lstTmpDim = new Array();

                    limpiarFrmIndicadorMeta();

                    //  Cierro la ventana modal( popup )
                    window.parent.SqueezeBox.close();
                }

            break;
            
            default:
                //  Cierro la ventana modal( popup )
                window.parent.SqueezeBox.close();
            break;
        }
        
    }
    
    
    
    
    function validarFrmIndMeta()
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
            
            jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
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
    
    
    /**
     * 
     * @param {type} val
     * @returns {undefined}
     */
    function semaforoIndMetaObj( val ) {
        var id = '#IM' + idObjetivo;

        jQuery( id, window.parent.document ).attr( 'src', val["imgAtributo"] );
        jQuery( id, window.parent.document ).attr( 'title', val["msgAtributo"] );
        jQuery( id, window.parent.document ).attr( 'style', val["msgStyle"] );
    }
    
    //  Actualizo formulario con informacion del indicador Meta
    function updFrmIndicador( dtaIndMeta )
    {
        //  Actualizo valor de Umbral
        jQuery('#jform_umbralIndicador').attr( 'value', dtaIndMeta.setDataUmbral( dtaIndMeta.umbral ) );

        //  Actualizo nombre del indicador
        jQuery('#jform_nombreIndicador').attr('value', dtaIndMeta.nombreIndicador );

        //  Actualizo descripcion
        jQuery('#jform_descripcionIndicador').attr( 'value', dtaIndMeta.descripcion );

        //  Actualizo formula
        jQuery('#formulaDescripcion').attr( 'value', dtaIndMeta.formula );

        //  Actualizo Horizonte fchFin
        jQuery('#jform_hzFchInicio').attr( 'value', dtaIndMeta.fchHorzMimimo );
        jQuery('#jform_hzFchFin').attr( 'value', dtaIndMeta.fchHorzMaximo );
        jQuery('#jform_valMinimo').attr( 'value', dtaIndMeta.umbMinimo );
        jQuery('#jform_valMaximo').attr( 'value', dtaIndMeta.umbMaximo );
        jQuery('#jform_fchInicioPeriodoUG').attr( 'value', dtaIndMeta.fchInicioUG );
        jQuery('#jform_fchInicioPeriodoFuncionario').attr( 'value', dtaIndMeta.fchInicioFuncionario );

        //  informacion complementaria del indicador
        jQuery('#jform_metodologia').attr( 'value', dtaIndMeta.metodologia );
        jQuery('#jform_limitacion').attr( 'value', dtaIndMeta.limitaciones );
        jQuery('#jform_interpretacion').attr( 'value', dtaIndMeta.interpretacion );
        jQuery('#jform_disponibilidad').attr( 'value', dtaIndMeta.disponibilidad );

        if( parseInt( dtaIndMeta.senplades ) === 1 ){
            jQuery('#jform_intSenplades_indEnt0').attr( 'checked', 'checked' );
        }else{
            jQuery('#jform_intSenplades_indEnt1').attr( 'checked', 'checked' );
        }

        //  Actualizo combo de Tipo de Indicador
        recorrerCombo( jQuery('#jform_idTpoIndicador option'), 1 );

        //  Actualizo combo de tendencia
        recorrerCombo( jQuery('#jform_idTendencia option'), dtaIndMeta.tendencia );

        //  Actualizo combo de Unidad de Analisis
        recorrerCombo( jQuery('#jform_intIdUndAnalisis option'), dtaIndMeta.idUndAnalisis );
        
        //  Actualizo combo de Tipo unidad de medida
        recorrerCombo( jQuery('#jform_intIdTpoUndMedida option'), dtaIndMeta.idTpoUndMedida );
        
        //  Actualizo combo de frecuencia de monitoreo
        recorrerCombo( jQuery('#jform_idFrcMonitoreo option'), dtaIndMeta.idFrcMonitoreo );
        
        //  Actualizo combo de Horizonte
        recorrerCombo( jQuery('#jform_IdHorizonte option'), dtaIndMeta.idHorizonte );

        //  Actualizo combo de unidad de Gestion
        recorrerCombo( jQuery('#jform_intIdUndGestion option'), dtaIndMeta.idUGResponsable );

        //  Actualizo combo de Unidad de Gestion del Funcionario un Responsable
        recorrerCombo( jQuery('#jform_intIdUGResponsable option'), dtaIndMeta.idResponsableUG );

        //  Actualizo combo Clase del Indicador
        recorrerCombo(jQuery('#jform_idClaseIndicador option'), dtaIndMeta.idClaseIndicador );

        //  Actualizo combo del grupo del indicador por Dimension
        recorrerCombo(jQuery('#jform_idGpoDimension option'), dtaIndMeta.idGpoDimension );

        //  Actualizo combo del grupo del indicador por Decision
        recorrerCombo(jQuery('#jform_idGpoDecisiones option'), dtaIndMeta.idGpoDecision );
        
        //  Simulo seleccion de Tipo de Unidad de medida
        jQuery( '#jform_intIdTpoUndMedida' ).trigger( 'change', dtaIndMeta.idUndMedida );
        
        //  Actualizo informacion de responsable de acuerdo a la unidad de gestion del mismo
        jQuery('#jform_intIdUGResponsable').trigger( 'change', dtaIndMeta.idResponsable );

        //  Actualizo informacion de Lineas Base
        setDtaLineasBase( dtaIndMeta.lstLineaBase );

        //  Actualizo informacion de Unidades Territoriales
        setDtaUT( dtaIndMeta.lstUndsTerritoriales );

        //  Actualizo informacion de Rangos de Gestion
        setDtaRangos( dtaIndMeta.lstRangos, dtaIndMeta.idTpoUndMedida, dtaIndMeta.idUndMedida );

        //  Actualizo informacion de Dimensiones
        setDtaDimensiones( dtaIndMeta.lstDimensiones );

        //  Actualizo informacion de Variables
        setDtaVariables( dtaIndMeta.lstVariables, dtaIndMeta.idTpoUndMedida, dtaIndMeta.idUndMedida );

        //  Actualizo informacion de la planificacion
        setDtaPlanificacion( dtaIndMeta.lstPlanificacion );

        if( typeOf( dtaIndMeta.idIndEntidad ) === "null" ){
            jQuery( '#tabsAttrIndicador' ).tabs( { disabled: [ 8 ] } );
        }else{
            jQuery( '#tabsAttrIndicador' ).tabs( { active: [ 8 ] } );

            //  Actualizo combo de seguimiento de variables
            updCBSeguimiento( dtaIndMeta.lstVariables );
        }

    }
    
    
    /**
     * 
     * Actualizo ComboBox con la lista de variables a dar seguimiento
     * 
     * @param {type} lstVariables   Lista de variables
     * @returns {undefined}
     */
    function updCBSeguimiento( lstVariables )
    {
        var dataInfo = eval( lstVariables );
        var numRegistros = dataInfo.length;
        var items = [];

        if( numRegistros > 0 ){
            items.push('<option value="0">SELECCIONE VARIABLE</option>');
            for( var x = 0; x < numRegistros; x++ ){
                
                if( parseInt( dataInfo[x].idTpoElemento ) == 1 ){
                    items.push('<option value="' + dataInfo[x].idIndVariable + '">' + dataInfo[x].nombre + '</option>');
                }

            }
        } else{
            items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
        }

        jQuery('#jform_idVariableIndicador').html( items.join('') );
    } 
    
    /**
     * 
     * Limpio de Informacion el Formulario de registro de indicador
     * 
     * @returns {undefined}
     */
    function limpiarFrmIndicadorMeta()
    {
        recorrerCombo( jQuery('#jform_idTpoIndicador option'), 3 );
        recorrerCombo( jQuery('#jform_idClaseIndicador option'), 0 );
        recorrerCombo( jQuery('#jform_intIdUndAnalisis option'), 0 );
        recorrerCombo( jQuery('#jform_intIdTpoUndMedida option'), 0 );
        recorrerCombo( jQuery('#jform_idUndMedida option'), 0 );
        recorrerCombo( jQuery('#jform_intIdFrcMonitoreo option'), 0 );
        recorrerCombo( jQuery('#jform_idTendencia option'), 0 );
        recorrerCombo( jQuery('#jform_intIdUndGestion option'), 0 );
        recorrerCombo( jQuery('#jform_intIdUGResponsable option'), 0 );
        recorrerCombo( jQuery('#jform_idResponsable option'), 0 );
        recorrerCombo( jQuery('#jform_IdHorizonte option'), 0 );
        
        jQuery( '#jform_nombreIndicador' ).attr( 'value', '' );
        jQuery( '#jform_umbralIndicador' ).attr( 'value', '' );
        jQuery( '#jform_descripcionIndicador' ).attr( 'value', '' );
        jQuery( '#jform_strFormulaIndicador' ).attr( 'value', '' );
        jQuery( '#jform_hzFchInicio' ).attr( 'value', '' );
        jQuery( '#jform_hzFchFin' ).attr( 'value', '' );
        jQuery( '#jform_valMinimo' ).attr( 'value', '' );
        jQuery( '#jform_valMaximo' ).attr( 'value', '' );
        
        //  informacion complementaria del indicador
        jQuery('#jform_metodologia').attr( 'value', '' );
        jQuery('#jform_limitacion').attr( 'value', '' );
        jQuery('#jform_interpretacion').attr( 'value', '' );
        jQuery('#jform_disponibilidad').attr( 'value', '' );
        
        jQuery( '#lstLineasBase > tbody' ).empty();
        jQuery( '#lstUndTerritorialesInd > tbody' ).empty();
        jQuery( '#lstRangos > tbody' ).empty();
        jQuery( '#lstVarIndicadores > tbody' ).empty();
        jQuery( '#lstDimensiones > tbody' ).empty();
        
        jQuery('#jform_intSenplades_indEnt1').attr( 'checked', 'checked' );
    }
    
})