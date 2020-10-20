jQuery( document ).ready( function(){
    
    var banIndObjetivo = -1;
    
    //  Identificador del Objetivo
    var idObjetivo = jQuery( '#idRegObjetivo' ).val();
    
    //  Lista temporal de indicadores
    lstTmpIndicadores = new Array();

    lstTmpLB = new Array();
    lstTmpUT = new Array();
    lstTmpRG = new Array();
    lstTmpVar = new Array();
    lstTmpDim = new Array();
    
    //  Accedo a la lista de Indicadores Asociados a un determinado Objetivo
    var lstIndObjetivos = parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores;

    
    if( lstIndObjetivos.length > 0 ){
        //  Actualizo Tabla con informacion de indicadores Objetivo
        for( var x = 0; x < lstIndObjetivos.length; x++ ){
            //  Creo un Objeto Indicador
            var objIndicador = new Indicador();

            //  Seteo Informacion de Indicadores
            objIndicador.setDtaIndicador( lstIndObjetivos[x] );
            objIndicador.idRegIndicador = x;

            //  Registro informacion de Indicadores en una lista temporal
            lstTmpIndicadores.push( objIndicador );

            //  Agrego la fila creada a la tabla
            jQuery( '#lstObjetivosInd > tbody:last' ).append( objIndicador.addFilaIndicador( 0 ) );
        }
    }else{
        recorrerCombo( jQuery('#jform_IdHorizonte option'), 8 );
        recorrerCombo( jQuery('#jform_idFrcMonitoreo option'), 7 );
    }
    
    
    
    //  Muestro Formulario de indicadores
    jQuery( '#addIndObjetivo' ).live( 'click', function(){
        banIndObjetivo = -1;
        mostrarFrmIndicador();
    })
    
    
    /**
     * Gestiono la actualizacion de un determinado Indicador
     */
    jQuery( '.updOI' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIndObjetivo = idFila;

        //  Obtengo datos GAP a actualizar
        var dataOI = getDataOI( idFila );
        
        if( typeOf( dataOI ) == "object" ){
            //  Actualizo informacion del Formulario OI
            updInfFrmOI( dataOI );
            
            //  Muestro el formulario OI
            mostrarFrmIndicador();
        }
    })
    
    /**
     * 
     * Retorno informacion de un determinado indicador asociado a un Objetivo
     * 
     * @param {type} idFila     Identificador de Fila
     * 
     * @returns {Boolean}
     */
    function getDataOI( idFila )
    {
        var nroi = lstTmpIndicadores.length;
        var dtaOI = false;
        
        for( var x = 0; x < nroi; x++ ){
            if( lstTmpIndicadores[x].idRegIndicador == idFila ){
                dtaOI = lstTmpIndicadores[x];
                break;
            }
        }
        
        return dtaOI;
    }
    
    /**
     * 
     * @param {type} dataOI
     * @returns {unresolved}
     */
    function updInfFrmOI( dataOI )
    {
        recorrerCombo( jQuery('#jform_idTpoIndicador option'), 3 );
        recorrerCombo( jQuery('#jform_idClaseIndicador option'), dataOI.idClaseIndicador );
        recorrerCombo( jQuery('#jform_intIdUndAnalisis option'), dataOI.idUndAnalisis );

        recorrerCombo( jQuery('#jform_intIdTpoUndMedida option'), dataOI.idTpoUndMedida );
        jQuery( '#jform_intIdTpoUndMedida' ).trigger( 'change', dataOI.idUndMedida );

        recorrerCombo( jQuery('#jform_intIdFrcMonitoreo option'), dataOI.idFrcMonitoreo );
        recorrerCombo( jQuery('#jform_idTendencia option'), dataOI.idTendencia );
        recorrerCombo( jQuery('#jform_intIdUndGestion option'), dataOI.idResponsableUG );
        
        recorrerCombo( jQuery('#jform_intIdUGResponsable option'), dataOI.idUGResponsable );
        jQuery('#jform_intIdUGResponsable').trigger( 'change', dataOI.idResponsable );
        
        jQuery( '#jform_nombreIndicador' ).attr( 'value', dataOI.nombreIndicador );
        jQuery( '#jform_umbralIndicador' ).attr( 'value', dataOI.umbral );
        jQuery( '#jform_descripcionIndicador' ).attr( 'value', dataOI.descripcion );
        jQuery( '#jform_strFormulaIndicador' ).attr( 'value', dataOI.formula );

        recorrerCombo( jQuery('#jform_IdHorizonte option'), dataOI.idHorizonte );

        jQuery( '#jform_hzFchInicio' ).attr( 'value', dataOI.fchHorzMimimo );
        jQuery( '#jform_hzFchFin' ).attr( 'value', dataOI.fchHorzMaximo );
        jQuery( '#jform_valMinimo' ).attr( 'value', dataOI.umbMinimo );
        jQuery( '#jform_valMaximo' ).attr( 'value', dataOI.umbMaximo );

        //  informacion complementaria del indicador
        jQuery('#jform_metodologia').attr( 'value', dataOI.metodologia );
        jQuery('#jform_limitacion').attr( 'value', dataOI.limitaciones );
        jQuery('#jform_interpretacion').attr( 'value', dataOI.interpretacion );
        jQuery('#jform_disponibilidad').attr( 'value', dataOI.disponibilidad );

        if( parseInt( dataOI.senplades ) === 1 ){
            jQuery('#jform_intSenplades_indEnt0').attr( 'checked', 'checked' );
        }else{
            jQuery('#jform_intSenplades_indEnt1').attr( 'checked', 'checked' );
        }

        setDtaLineasBase( dataOI.lstLineaBase );
        setDtaVariables( dataOI.lstVariables, dataOI.idTpoUndMedida, dataOI.idUndMedida );
        setDtaUT( dataOI.lstUndsTerritoriales );
        setDtaRangos( dataOI.lstRangos, dataOI.idTpoUndMedida, dataOI.idUndMedida );
        setDtaDimensiones( dataOI.lstDimensiones );
        setDtaPlanificacion(dataOI.lstPlanificacion, dataOI.idTpoUndMedida, dataOI.idUndMedida);

        if( typeOf( dataOI.idIndEntidad ) === "null" ){
            jQuery( '#tabsAttrIndicador' ).tabs( { disabled: [ 8 ] } );
        }else{
            jQuery( '#tabsAttrIndicador' ).tabs( { active: [ 8 ] } );

            //  Actualizo combo de seguimiento de variables
            updCBSeguimiento( dataOI.lstVariables );
        }

        return;
    }
    
    
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
        var nrLB = lstLB.length;
        var objLB;
        
        if( nrLB > 0 ){
            
            for( var x = 0; x < nrLB; x++ ){
                objLB = new LineaBase();
                objLB.setDtaLineaBase( lstLB[x] );

                //  Agrego una fila a la tabla de lineas base
                jQuery( '#lstLineasBase > tbody:last' ).append( objLB.getFilaLineaBase( 0 ) );
                lstTmpLB.push( objLB );
            }

        }else{
            objLB = new LineaBase();
            jQuery('#lstLineasBase > tbody:last').append( objLB.getFilaSinRegistros() );
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
        var nrUT = lstUT.length;
        
        if( nrUT > 0 ){
            for( var x = 0; x < lstUT.length; x++ ){
                objUT = new UnidadTerritorial();
                objUT.setDtaUT( lstUT[x] );

                //  Agrego una fila a la tabla de lineas base
                jQuery( '#lstUndTerritorialesInd > tbody:last' ).append( objUT.addFilaUT( 0 ) );
                lstTmpUT.push( objUT );
            }
        }else{
            objUT = new UnidadTerritorial();
            jQuery('#lstUndTerritorialesInd > tbody:last').append( objUT.addFilaSinRegistros() );
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
    function setDtaRangos( lstRg, idTpoUndMedida, idUndMedida )
    {
        lstTmpRG = new Array();
        var objRg;
        var nrR = lstRg.length;
        
        if( nrR > 0 ){
            
            for( var x = 0; x < lstRg.length; x++ ){
                objRg = new Rango();
                objRg.setDtaRango( lstRg[x], idTpoUndMedida, idUndMedida );
                objRg.idRegRG = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery( '#lstRangos > tbody:last' ).append( objRg.addFilaRG( 0 ) );
                lstTmpRG.push( objRg );
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
    function setDtaDimensiones( lstDim )
    {
        lstTmpDim = new Array();
        var objDim;
        var nrDim = lstDim.length;

        if( nrDim > 0 ){
            for( var x = 0; x < nrDim; x++ ){
                objDim = new Dimension();
                objDim.setDtaDimension( lstDim[x] );

                //  Agrego una fila a la tabla de lineas base
                jQuery( '#lstDimensiones > tbody:last' ).append( objDim.addFilaDimension( 0 ) );
                lstTmpDim.push( objDim );
            }
        }else{
            objDim = new Dimension();

            //  Agrego una fila a la tabla de lineas base
            jQuery('#lstDimensiones > tbody:last').append(objDim.addFilaSinRegistros());
        }


        return;
    }
    
    
    /**
     * 
     * Seteo informacion de Planificacion de un Indicador
     * 
     * @param {array} lstPlanificacion      Lista de planificacion
     * 
     * @returns {unresolved}
     * 
     */
    function setDtaPlanificacion(lstPlanificacion, idTpoUndMedida, idUndMedida, undMedida)
    {
        lstTmpPln = new Array();
        var objPlanificacion;
        var nrPln = lstPlanificacion.length;

        if( nrPln > 0 ){
            for (var x = 0; x < lstPlanificacion.length; x++) {
                objPlanificacion = new Planificacion();
                lstPlanificacion[x].idRegPln = x;
                objPlanificacion.setDtaPlanificacion(   lstPlanificacion[x],
                                                        idTpoUndMedida,
                                                        idUndMedida);

                //  Agrego una fila a la tabla de Variables
                jQuery('#lstPlanificacionIndicadores > tbody:last').append( objPlanificacion.addFilaPln(0) );
                lstTmpPln.push(objPlanificacion);
            }    
        }else{
            objPlanificacion = new Planificacion();
            jQuery('#lstPlanificacionIndicadores > tbody:last').append( objPlanificacion.addFilaSinRegistros() );
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
        var nrV = lstVar.length;

        if( nrV > 0 ){
            for( var x = 0; x < nrV; x++ ){
                objVar = new Variable();
                lstVar[x].idRegVar = x;
                objVar.setDtaVariable( lstVar[x], idTpoUndMedida, idUndMedida );

                //  Agrego una fila a la tabla de Variables
                jQuery( '#lstVarIndicadores > tbody:last' ).append( objVar.addFilaVar( 0 ) );
                lstTmpVar.push( objVar );
            }
        }else{
            objVar = new Variable();
            jQuery('#lstVarIndicadores > tbody:last').append( objVar.addFilaSinRegistros() );
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
     * 
     * Limpio de Informacion el Formulario de registro de indicador
     * 
     * @returns {undefined}
     */
    function limpiarFrmIndicador()
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
    
    /**
     * 
     * Muestro Formulario de Registro de Indicador
     * 
     * @returns {undefined}
     */
    function mostrarFrmIndicador()
    {
        //  Muestro toolBar de lista de Indicadores
        jQuery("#tbIndicadoresObjetivo").css("display", "none");

        //  Muestro  Lista de Indicadores
        jQuery("#lstIndicadoresObjetivo").css("display", "none");

        //  Oculto toolBar de formulario de indicadores
        jQuery("#tbFrmIndicador").css("display", "block");

        //  Oculto formulario de lista de indicadores
        jQuery("#frmIndicador").css("display", "block");
    }
    
    /**
     * 
     * Oculto formulario de registro de indicadores
     * 
     * @returns {undefined}
     */
    function mostrarLstIndicadores()
    {
        //  Muestro toolBar de lista de Indicadores
        jQuery("#tbIndicadoresObjetivo").css("display", "block");

        //  Muestro  Lista de Indicadores
        jQuery("#lstIndicadoresObjetivo").css("display", "block");

        //  Oculto toolBar de formulario de indicadores
        jQuery("#tbFrmIndicador").css("display", "none");

        //  Oculto formulario de lista de indicadores
        jQuery("#frmIndicador").css("display", "none");
    }
    
    /**
     * 
     * Recorro un determinado CombBox a un posicion determinada
     * 
     * @param {Objeto} combo    ComboBox
     * @param {int} posicion    Posicion a recorrer
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
    
    
    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaIndObjetivo( fila )
    {
        jQuery( '#lstObjetivosInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIndObjetivo ){
                jQuery( this ).html( fila );
            }
        })
    }
    
    
    //  Ejecuto
    Joomla.submitbutton = function( task )
    {
        switch( task ){

            //  Asigno el objetivo creado a lista de indicadores asociados a un objetivo
            case 'indicadorObjetivo.asignar':
                var objIndicador;

                if( banIndObjetivo === -1 ){
                    objIndicador = new Indicador();
                    objIndicador.idRegIndicador = lstTmpIndicadores.length;
                }else{
                    objIndicador = lstTmpIndicadores[banIndObjetivo];
                }
                
                objIndicador.idTpoIndicador     = jQuery( '#jform_idTpoIndicador' ).val();
                objIndicador.idClaseIndicador   = jQuery( '#jform_idClaseIndicador' ).val();
                objIndicador.idUndAnalisis      = jQuery( '#jform_intIdUndAnalisis' ).val();
                objIndicador.idTpoUndMedida     = jQuery( '#jform_intIdTpoUndMedida' ).val()
                objIndicador.idUndMedida        = jQuery( '#jform_idUndMedida' ).val();
                objIndicador.idTendencia        = jQuery( '#jform_idTendencia' ).val();
                objIndicador.idUGResponsable    = jQuery( '#jform_intIdUndGestion' ).val();
                objIndicador.idResponsableUG    = jQuery( '#jform_intIdUGResponsable' ).val();
                objIndicador.idResponsable      = jQuery( '#jform_idResponsable' ).val();

                objIndicador.nombreIndicador    = jQuery( '#jform_nombreIndicador' ).val();
                objIndicador.umbral             = unformatNumber( jQuery( '#jform_umbralIndicador' ).val() );
                objIndicador.undMedida          = jQuery( '#jform_idUndMedida :selected' ).text();
                
                objIndicador.descripcion        = jQuery( '#jform_descripcionIndicador' ).val();
                objIndicador.formula            = jQuery( '#jform_strFormulaIndicador' ).val();
                objIndicador.idFrcMonitoreo     = jQuery( '#jform_intIdFrcMonitoreo' ).val();
                objIndicador.idHorizonte        = jQuery( '#jform_IdHorizonte option' );
                objIndicador.fchHorzMimimo      = jQuery( '#jform_hzFchInicio' ).val();
                objIndicador.fchHorzMaximo      = jQuery( '#jform_hzFchFin' ).val();
                objIndicador.umbMinimo          = jQuery( '#jform_valMinimo' ).val();
                objIndicador.umbMaximo          = jQuery( '#jform_valMaximo' ).val();
                
                //  Informacion complementaria
                objIndicador.metodologia    = jQuery('#jform_metodologia').val();
                objIndicador.limitaciones   = jQuery('#jform_limitacion').val();
                objIndicador.interpretacion = jQuery('#jform_interpretacion').val();
                objIndicador.disponibilidad = jQuery('#jform_disponibilidad').val();

                //  Actualizo informacion de Lineas Base
                objIndicador.lstLineaBase = new Array();
                for (var x = 0; x < lstTmpLB.length; x++) {
                    objIndicador.lstLineaBase.push( lstTmpLB[x] );
                }

                //  Actualizo informacion de Unidades Territoriales
                objIndicador.lstUndsTerritoriales = new Array();
                for (var x = 0; x < lstTmpUT.length; x++) {
                    objIndicador.lstUndsTerritoriales.push( lstTmpUT[x] );
                }

                //  Actualizo informacion de Rangos de Gestion
                objIndicador.lstRangos = new Array();
                for (var x = 0; x < lstTmpRG.length; x++) {
                    objIndicador.lstRangos.push( lstTmpRG[x] );
                }

                //  Actualizo informacion de Dimensiones
                objIndicador.lstDimensiones = new Array();
                for (var x = 0; x < lstTmpDim.length; x++) {
                    objIndicador.lstDimensiones.push( lstTmpDim[x] );
                }

                //  Actualizo informacion de Variables
                objIndicador.lstVariables = new Array();
                for (var x = 0; x < lstTmpVar.length; x++) {
                    objIndicador.lstVariables.push( lstTmpVar[x] );
                }

                if( banIndObjetivo === -1 ){
                    //  Agrego Indicador a la lista temporal de indicadores
                    lstTmpIndicadores.push( objIndicador );

                    //  Agrego una fila a la tabla que Lista los Indicadores Asociados a un determinado Objetivo
                    jQuery('#lstObjetivosInd > tbody:last').append( objIndicador.addFilaIndicador( 0 ) );
                }else{
                    //  Actualizo contenido de elemento de tabla temporal de Indicadores - Objetivo
                    lstTmpIndicadores[banIndObjetivo] = objIndicador;
                    
                    //  Actualizo fila de la tabla indicador
                    updFilaIndObjetivo( objIndicador.addFilaIndicador( 1 ) );
                }

                //  Limpio el formulario de registro de indicadores
                limpiarFrmIndicador();
                
                //  Oculto el Formulario y muestro la lista de indicadores
                mostrarLstIndicadores();

                lstTmpLB = new Array();
                lstTmpUT = new Array();
                lstTmpRG = new Array();
                lstTmpVar = new Array();
                lstTmpDim = new Array();
            break;

            //  Asigno indicadores al objetivo seleccionado
            case 'atributosindicador.asignar':

                //  Vacio lista de indicadores 
                parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores = new Array();

                for( var x = 0; x < lstTmpIndicadores.length; x++ ){
                    parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores.push( lstTmpIndicadores[x] );
                }
                
                //  Vacio lista temporal de Indicadores
                lstTmpIndicadores = new Array();
                
                //  Actualiza la bamdera para la programacion de indicadores
                parent.window.flagUpdObj = true;
                
                //  Cierro la ventana modal( popup )
                window.parent.SqueezeBox.close();
            break;

            //  Cancelo la creacion del indicador y regreso a la lista de Indicadores Inicial
            case 'indicadorObjetivo.cancel': 
                //  Limpio el formulario de registro de indicadores
                limpiarFrmIndicador();
                
                //  Oculto el Formulario y muestro la lista de indicadores
                mostrarLstIndicadores();
            break;

            //  Cancelo al Gestion de Indicador
            case 'atributosindicador.cancel': 
                
                //  Limpio el formulario de registro de indicadores
                limpiarFrmIndicador();

                //  Cierro la ventana modal( popup )
                window.parent.SqueezeBox.close();
            break;
        }
    }
})