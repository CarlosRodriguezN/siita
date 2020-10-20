jQuery(document).ready(function () {
    var banIndObjetivo = -1;
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var tpoPln = jQuery('#tpoPlan').val();
    var idPlan = jQuery('#idPlan').val();
    var idPoa = jQuery('#idPoa').val();
    var isNew = 0;

    var dtaLstIndicadores;

    //  Identificador del Objetivo
    var idObjetivo = jQuery('#idRegObjetivo').val();

    var fchInicioPlan = jQuery('#jform_dteFechainicio_pi', window.parent.document).val();
    var fchFinPlan = jQuery('#jform_dteFechafin_pi', window.parent.document).val();

    jQuery('#jform_hzFchInicio').attr('value', fchInicioPlan);
    jQuery('#jform_hzFchFin').attr('value', fchFinPlan);

    jQuery('#jform_fchInicioPeriodoUG').attr('value', fchInicioPlan);
    jQuery('#jform_fchInicioPeriodoFuncionario').attr('value', fchInicioPlan);

    jQuery('#jform_IdHorizonte option').recorrerCombo(8);
    jQuery('#jform_idFrcMonitoreo option').recorrerCombo(7);

    //  Lista temporal de indicadores
    lstTmpIndicadores = new Array();

    lstTmpLB = new Array();
    lstTmpUT = new Array();
    lstTmpRG = new Array();
    lstTmpVar = new Array();
    lstTmpDim = new Array();
    lstTmpPln = new Array();
    lstTmpSg = new Array();

    switch (tpoIndicador)
    {
        case 'pei':
            dtaLstIndicadores = parent.window.objLstObjetivo.lstObjetivos[idObjetivo];
            break;

            //  PPPP
        case 'pppp':
            dtaLstIndicadores = parent.window.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo];
            break;

            //  PAPP
        case 'papp':
            dtaLstIndicadores = parent.window.oLstPAPPs.lstPapp[idPlan].lstObjetivos[idObjetivo];
            break;

            //  POA por Unidad de Gestion
        case 'ug':
            dtaLstIndicadores = parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo];
            break;

            //  POA por Funcionario
        case 'fun':
            dtaLstIndicadores = parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo];
            break;

            //  POA por Programa - Proyecto - Contrato
        case 'poao':
            dtaLstIndicadores = parent.window.dtaPlanOperativo[idPlan].planObjetivo;
            break;

            //  Indicadores operativos
        default:
            dtaLstIndicadores = parent.window.objLstObjetivo.lstObjetivos[idObjetivo];
            break;
    }

    if (typeOf(dtaLstIndicadores)) {
        updLstIndicadores(dtaLstIndicadores.lstIndicadores);
    }

    /**
     * 
     * Actualiza tabla de indicadores con informacion de indicadores de un tipo diferente al META
     * 
     * @param {type} indicadores
     * @returns {undefined}
     * 
     */
    function updLstIndicadores(indicadores)
    {
        var nrli = indicadores.length;

        if (nrli > 0) {
            for (var x = 0; x < nrli; x++) {
                //  Creo un Objeto Indicador
                var objIndicador = new Indicador();

                //  Seteo Informacion de Indicadores
                objIndicador.setDtaIndicador( indicadores[x] );
                objIndicador.idRegIndicador = x;

                //  Registro informacion de Indicadores en una lista temporal
                lstTmpIndicadores.push(objIndicador);

                if (parseInt(indicadores[x].published) === 1) {
                    //  Agrego la fila creada a la tabla
                    jQuery('#lstObjetivosInd > tbody:last').append( objIndicador.addFilaIndicador( 0, tpoIndicador ) );
                }
            }

        } else {
            //  Creo un Objeto Indicador
            var objIndicador = new Indicador();
            jQuery('#lstObjetivosInd > tbody:last').append( objIndicador.addFilaSinRegistros() );

            limpiarFrmIndicador();
        }
    }


    jQuery('#jform_idTpoIndicador').on('change', function () {
        if (parseInt(jQuery(this).val()) === 1) {
            jQuery('#tabsAttrIndicador').tabs({disabled: [3, 4, 5]});

            jQuery('#jform_umbralIndicador-lbl').html(COM_INDICADOR_VALOR_META_LABEL + '<span class="star">&nbsp;*</span>');
            jQuery('#jform_umbralIndicador-lbl').attr('title', COM_INDICADOR_VALOR_META_DESC);

        } else {
            jQuery('#tabsAttrIndicador').tabs({disabled: false});
            jQuery('#tabsAttrIndicador').tabs({disabled: [9]});

            jQuery('#jform_umbralIndicador-lbl').html(COM_INDICADOR_VALOR_META_LABEL + '<span class="star">&nbsp;*</span>');
            jQuery('#jform_umbralIndicador-lbl').attr('title', COM_INDICADOR_VALOR_META_DESC);
        }
    })

    //  Muestro Formulario de indicadores
    jQuery('#addIndObjetivo').live('click', function () {
        limpiarFrmIndicador()
        banIndObjetivo = -1;
        mostrarFrmIndicador();
        
        setDtaLineasBase( lstTmpLB );
        setDtaVariables( lstTmpVar );
        setDtaUT( lstTmpUT );
        setDtaRangos( lstTmpRG );
        setDtaDimensiones( lstTmpDim );
        setDtaPlanificacion( lstTmpPln );

        jQuery('#tabsAttrIndicador').tabs( {disabled: [8,9]} );
    })


    /**
     * Gestiono la actualizacion de un determinado Indicador
     */
    jQuery('.updOI').live('click', function () {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        banIndObjetivo = idFila;

        //  Obtengo datos GAP a actualizar
        var dataOI = getDataOI(idFila);

        if (typeOf(dataOI) === "object") {
            //  Actualizo informacion del Formulario OI
            updInfFrmOI(dataOI);

            //  Muestro el formulario OI
            mostrarFrmIndicador();
        }
    })


    jQuery('.delOI').live('click', function () {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function (result) {
            if (result) {
                eliminarIndicador(lstTmpIndicadores[idFila], idFila);

                //  Elimino Fila de la tabla de Rangos
                delFilaIndicador(idFila);
            }
        });

    })



    jQuery('.cierreOI').live('click', function () {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("¿Este proceso acualizara informaci&oacute;n de lineas base a partir del periodo vigente hacia adelante, Desea continuar?", "SIITA - ECORAE", function (result) {
            if (result) {

                //  Obtengo URL completa del sitio
                var url = window.location.href;
                var path = url.split( '?' )[0];

                jQuery.ajax({type: 'POST',
                    url: path,
                    dataType: 'JSON',
                    data: { option      : 'com_indicadores',
                            view        : 'indicadores',
                            tmpl        : 'component',
                            format      : 'json',
                            action      : 'cierreIndicador',
                            idIndEntidad: lstTmpIndicadores[idFila].idIndEntidad,
                            idIndicador : lstTmpIndicadores[idFila].idIndicador
                    },
                    error: function (jqXHR, status, error) {
                        alert('Indicadores - Cierre Periodos: ' + error + ' ' + jqXHR + ' ' + status);
                    }
                }).complete(function (data) {
                    alert( 'enTeoria' );
                })

            }
        });

    })



    function eliminarIndicador(dtaIndicador, idFila)
    {
        if (isNaN(parseInt(dtaIndicador.idIndEntidad)) === false) {
            lstTmpIndicadores[idFila].published = 0;
        } else {
            //  Elimino Rango del la tabla Temporal de Rangos
            lstTmpIndicadores.splice(idFila, 1);
        }
    }


    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaIndicador(idFila) {
        //  Elimino fila de la tabla lista de Rangos
        jQuery('#lstObjetivosInd tr').each(function () {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        })
    }


    /**
     * Gestiono la actualizacion de un determinado Indicador
     */
    jQuery('.segOI').live('click', function () {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        banIndObjetivo = idFila;

        //  Obtengo datos GAP a actualizar
        var dataOI = getDataOI(idFila);

        if (typeOf(dataOI) == "object") {
            //  Actualizo informacion del Formulario OI
            updInfFrmOI(dataOI);

            //  Muestro el formulario OI
            mostrarFrmIndicador();

            //  Deshabilito elementos del formulario
            deshabilitoElementos(jQuery('#generales :input'));
            deshabilitoElementos(jQuery('#responsables :input'));
            deshabilitoElementos(jQuery('#lineaBase :input'));
            deshabilitoElementos(jQuery('#unidadTerritorial :input'));
            deshabilitoElementos(jQuery('#rango :input'));
            deshabilitoElementos(jQuery('#formula :input'));
            deshabilitoElementos(jQuery('#dimensiones :input'));
            deshabilitoElementos(jQuery('#planificacion :input'));
        }
    })

    /**
     * 
     * Deshabilito los elementos que forman parte de un div
     * 
     * @param object elemento   Elementos que forman parte de un determinado DIV
     * @returns {undefined}
     * 
     */
    function deshabilitoElementos(elemento)
    {
        jQuery(elemento).each(function () {
            jQuery(this).attr('disabled', 'disabled');
        })
    }

    /**
     * 
     * Habilito los elementos que forman parte de un div
     * 
     * @param object elemento   Elementos que forman parte de un determinado DIV
     * @returns {undefined}
     * 
     */
    function habilitoElementos(elemento)
    {
        jQuery(elemento).each(function () {
            jQuery(this).removeAttr('disabled', 'disabled');
        })
    }

    /**
     * 
     * Retorno informacion de un determinado indicador asociado a un Objetivo
     * 
     * @param {type} idFila     Identificador de Fila
     * 
     * @returns {Boolean}
     */
    function getDataOI(idFila)
    {
        var nroi = lstTmpIndicadores.length;
        var dtaOI = false;

        for (var x = 0; x < nroi; x++) {
            if (lstTmpIndicadores[x].idRegIndicador == idFila) {
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
    function updInfFrmOI(dataOI)
    {
        var fchInicio;
        var fchFin;
        
        var fchInicioUG;
        var fchInicioFuncionario;
        
        switch( tpoIndicador ){
            case 'pei':
            case 'poao':
            case 'ug': 
            case 'fun':
                fchInicio = ( typeOf( dataOI.fchHorzMimimo ) === "null" ) 
                                ? jQuery( '#jform_dteFechaInicio_ctr', parent.window.document ).val()
                                : dataOI.fchHorzMimimo;

                fchFin = ( typeOf( dataOI.fchHorzMimimo ) === "null" )
                                ? jQuery( '#jform_dteFechaInicio_ctr', parent.window.document ).val()
                                : dataOI.fchHorzMimimo;

                fchInicioUG         = ( typeOf( dataOI.fchInicioUG ) === "null" || dataOI.fchInicioUG === "0000-00-00" )
                                        ? fchInicio 
                                        : dataOI.fchInicioUG;

                fchInicioFuncionario= ( typeOf( dataOI.fchInicioFuncionario ) === "null" || dataOI.fchInicioFuncionario === "0000-00-00" )
                                            ? fchInicio
                                            : dataOI.fchInicioFuncionario;
            break;
        }

        jQuery('#jform_idTpoIndicador option').recorrerCombo(dataOI.idTpoIndicador);
        jQuery('#jform_idClaseIndicador option').recorrerCombo(dataOI.idClaseIndicador);
        jQuery('#jform_intIdUndAnalisis option').recorrerCombo(dataOI.idUndAnalisis);
        jQuery('#jform_intIdTpoUndMedida option').recorrerCombo(dataOI.idTpoUndMedida);
        jQuery('#jform_idTendencia option').recorrerCombo(dataOI.idTendencia);
        jQuery('#jform_IdHorizonte option').recorrerCombo(dataOI.idHorizonte);
        jQuery('#jform_idFrcMonitoreo option').recorrerCombo(dataOI.idFrcMonitoreo);
        jQuery('#jform_intIdUndGestion option').recorrerCombo(dataOI.idUGResponsable);
        jQuery('#jform_intIdUGResponsable option').recorrerCombo(dataOI.idResponsableUG);
        jQuery('#jform_idGpoDimension option').recorrerCombo(dataOI.idGpoDimension);
        jQuery('#jform_idGpoDecisiones option').recorrerCombo(dataOI.idGpoDecision);

        jQuery('#jform_intIdUGResponsable').trigger('change', dataOI.idResponsable);
        jQuery('#jform_intIdTpoUndMedida').trigger('change', dataOI.idUndMedida);

        jQuery('#jform_nombreIndicador').attr('value', dataOI.nombreIndicador);
        jQuery('#jform_umbralIndicador').attr('value', dataOI.umbral);
        jQuery('#jform_descripcionIndicador').attr('value', dataOI.descripcion);
        jQuery('#formulaDescripcion').attr('value', dataOI.formula);
        
        jQuery('#jform_hzFchInicio').attr('value', dataOI.fchHorzMimimo);
        jQuery('#jform_hzFchFin').attr('value', dataOI.fchHorzMaximo);
        
        jQuery('#jform_valMinimo').attr('value', dataOI.umbMinimo);
        jQuery('#jform_valMaximo').attr('value', dataOI.umbMaximo);
        
        jQuery('#jform_fchInicioPeriodoUG').attr('value', fchInicioUG );
        jQuery('#jform_fchInicioPeriodoFuncionario').attr('value', fchInicioFuncionario);

        //  informacion complementaria del indicador
        jQuery('#jform_metodologia').attr('value', dataOI.metodologia);
        jQuery('#jform_limitacion').attr('value', dataOI.limitaciones);
        jQuery('#jform_interpretacion').attr('value', dataOI.interpretacion);
        jQuery('#jform_disponibilidad').attr('value', dataOI.disponibilidad);

        if (parseInt(dataOI.senplades) === 1) {
            jQuery('#jform_intSenplades_indEnt0').attr('checked', 'checked');
        } else {
            jQuery('#jform_intSenplades_indEnt1').attr('checked', 'checked');
        }

        setDtaLineasBase(dataOI.lstLineaBase);
        setDtaVariables(dataOI.lstVariables, dataOI.idTpoUndMedida, dataOI.idUndMedida);
        setDtaUT(dataOI.lstUndsTerritoriales);
        setDtaRangos(dataOI.lstRangos, dataOI.idTpoUndMedida, dataOI.idUndMedida);
        setDtaDimensiones(dataOI.lstDimensiones);
        setDtaPlanificacion(dataOI.lstPlanificacion, dataOI.idTpoUndMedida, dataOI.idUndMedida);

        if (typeOf(dataOI.idIndEntidad) === "null") {
            jQuery('#tabsAttrIndicador').tabs({disabled: [8]});
        } else {
            jQuery('#tabsAttrIndicador').tabs({active: [8]});

            //  Actualizo combo de seguimiento de variables
            updCBSeguimiento(dataOI.lstVariables);
        }

        if (parseInt(dataOI.idTpoIndicador) === 1) {
            jQuery('#tabsAttrIndicador').tabs({disabled: [3, 4, 5]});
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
    function setDtaLineasBase(lstLB)
    {
        var nrLB = lstLB.length;
        lstTmpLB = new Array();

        var objLB;
        
        if( nrLB > 0 ){
            
            for (var x = 0; x < nrLB; x++) {
                objLB = new LineaBase();
                objLB.setDtaLineaBase(lstLB[x]);
                objLB.idRegLB = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstLineasBase > tbody:last').append( objLB.getFilaLineaBase( 0 ) );
                lstTmpLB.push(objLB);
            }
            
        }else{
            objLB = new LineaBase();
            jQuery('#lstLineasBase > tbody:last').append( objLB.getFilaSinRegistros() );
        }

        return false;
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
            for (var x = 0; x < nrUT; x++) {
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
     * 
     * Seteo Informacion de Unidades Territoriales
     * 
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * 
     * @returns {undefined}
     * 
     */
    function setDtaRangos(lstRg, idTpoUndMedida, idUndMedida)
    {
        lstTmpRG = new Array();
        var objRg;
        var nrR = lstRg.length;

        if( nrR > 0 ){
            for (var x = 0; x < nrR; x++) {
                objRg = new Rango();
                objRg.setDtaRango(lstRg[x], idTpoUndMedida, idUndMedida);
                objRg.idRegRG = x;

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstRangos > tbody:last').append(objRg.addFilaRG(0));
                lstTmpRG.push(objRg);
            }

            if (lstTmpRG.length === 3) {
                jQuery('#addLnRangoTable').attr('disabled', 'disabled');
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
            for (var x = 0; x < lstDim.length; x++) {
                objDim = new Dimension();
                objDim.setDtaDimension(lstDim[x]);

                //  Agrego una fila a la tabla de lineas base
                jQuery('#lstDimensiones > tbody:last').append(objDim.addFilaDimension(0));
                lstTmpDim.push(objDim);
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
     * Seteo Informacion de Unidades Territoriales
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

        if( nrV > 0 ){
            for (var x = 0; x < nrV; x++) {
                objVar = new Variable();
                lstVar[x].idRegVar = x;
                objVar.setDtaVariable(lstVar[x], idTpoUndMedida, idUndMedida);

                lstTmpVar.push(objVar);

                //  Verifico si la variable a mostrar es publica
                if (parseInt(objVar.published) === 1) {
                    //  Agrego una fila a la tabla de Variables
                    jQuery('#lstVarIndicadores > tbody:last').append(objVar.addFilaVar(0));
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
     * Limpio de Informacion el Formulario de registro de indicador
     * 
     * @returns {undefined}
     */
    function limpiarFrmIndicador()
    {
        var fchInicioPlan;
        var fchFinPlan;

        var idUndGestion = jQuery('#jform_intIdUndGestion', window.parent.document).val();
        var idUGR = jQuery('#jform_intIdUGResponsable', window.parent.document).val();

        var idF = jQuery('#jform_intCodigo_fnc', window.parent.document).val();
        var idFuncionario = (typeOf(idF) !== "null") ? jQuery('#jform_intCodigo_fnc', window.parent.document).val()
                : jQuery('#jform_idResponsable', window.parent.document).val();

        jQuery('#jform_idTpoIndicador option').recorrerCombo(0);
        jQuery('#jform_idClaseIndicador option').recorrerCombo(0);
        jQuery('#jform_intIdUndAnalisis option').recorrerCombo(0);
        jQuery('#jform_intIdTpoUndMedida option').recorrerCombo(0);
        jQuery('#jform_idUndMedida option').recorrerCombo(0);
        jQuery('#jform_idTendencia option').recorrerCombo(0);

        jQuery('#jform_intIdUndGestion option').recorrerCombo(idUndGestion);
        jQuery('#jform_intIdUGResponsable option').recorrerCombo(idUGR);
        jQuery('#jform_intIdUGResponsable').trigger('change', idFuncionario);

        jQuery('#jform_idResponsable option').recorrerCombo(0);

        jQuery('#jform_nombreIndicador').attr('value', '');
        jQuery('#jform_umbralIndicador').attr('value', '');
        jQuery('#jform_descripcionIndicador').attr('value', '');
        jQuery('#formulaDescripcion').attr('value', '');
        jQuery('#jform_valMinimo').attr('value', '');
        jQuery('#jform_valMaximo').attr('value', '');

        //  Informacion complementaria del indicador
        jQuery('#jform_metodologia').attr('value', '');
        jQuery('#jform_limitacion').attr('value', '');
        jQuery('#jform_interpretacion').attr('value', '');
        jQuery('#jform_disponibilidad').attr('value', '');

        jQuery('#jform_intSenplades_indEnt1').attr('checked', 'checked');

        switch (tpoIndicador) {
            //  POA por Unidad de Gestion
            case 'ug':
                dtaLstIndicadores = parent.window.objLstPoas.lstPoas[idPlan];

                jQuery('#jform_IdHorizonte option').recorrerCombo(7);
                jQuery('#jform_idFrcMonitoreo option').recorrerCombo(4);

                jQuery('#jform_intIdUndGestion').attr("disabled", "disabled");
                jQuery('#jform_intIdUGResponsable').attr("disabled", "disabled");

                fchInicioPlan   = dtaLstIndicadores.fechaInicioPoa;
                fchFinPlan      = dtaLstIndicadores.fechaFinPoa;
            break;

                //  POA por Funcionario
            case 'fun':
                var dtaPoaF = parent.window.objLstPoas.lstPoas[idPlan];

                jQuery('#jform_IdHorizonte option').recorrerCombo(7);
                jQuery('#jform_idFrcMonitoreo option').recorrerCombo(4);
                jQuery('#jform_intIdUGResponsable').trigger('change', idFuncionario);

                fchInicioPlan = (typeOf(dtaPoaF.fechaInicioPoa) === "null")
                        ? jQuery('#jform_dteFechaInicio_ugf').val()
                        : dtaPoaF.fechaInicioPoa;

                fchFinPlan = (typeOf(dtaPoaF.fechaFinPoa) === "null")
                        ? jQuery('#jform_dteFechaFin_ugf').val()
                        : dtaPoaF.fechaFinPoa;
            break;

            case "programa":
                fchInicioPlan = jQuery('#jform_dteFechaInicio_stmdoPry', window.parent.document).val();
                fchFinPlan = jQuery('#jform_dteFechaFin_stmdoPry', window.parent.document).val();
            break;

            case "contrato":
                fchInicioPlan = jQuery('#jform_dteFechaInicio_ctr', window.parent.document).val();
                fchFinPlan = jQuery('#jform_dteFechaFin_ctr', window.parent.document).val();
            break;

            default:
                jQuery('#jform_IdHorizonte option').recorrerCombo(8);
                jQuery('#jform_idFrcMonitoreo option').recorrerCombo(7);

                fchInicioPlan = jQuery('#jform_dteFechainicio_pi', window.parent.document).val();
                fchFinPlan = jQuery('#jform_dteFechafin_pi', window.parent.document).val();
            break;
        }

        jQuery('#jform_hzFchInicio').attr('value', fchInicioPlan);
        jQuery('#jform_hzFchFin').attr('value', fchFinPlan);

        jQuery('#jform_fchInicioPeriodoUG').attr('value', fchInicioPlan);
        jQuery('#jform_fchInicioPeriodoFuncionario').attr('value', fchInicioPlan);

        jQuery('#lstLineasBase > tbody').empty();
        jQuery('#lstUndTerritorialesInd > tbody').empty();
        jQuery('#lstRangos > tbody').empty();
        jQuery('#lstVarIndicadores > tbody').empty();
        jQuery('#lstDimensiones > tbody').empty();
        jQuery('#lstPlanificacionIndicadores > tbody').empty();
        jQuery('#lstSeguimiento > tbody').empty();

        //  Activa todas las pestañas del formulario
        jQuery('#tabsAttrIndicador').tabs({disabled: false});

        lstTmpLB = new Array();
        lstTmpUT = new Array();
        lstTmpRG    = new Array();
        lstTmpVar   = new Array();
        lstTmpDim   = new Array();
        lstTmpPln = new Array();
        lstTmpSg = new Array();

        limpiarValidaciones();
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
        //  Limpio el formulario de registro de indicadores
        limpiarFrmIndicador();

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
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaIndObjetivo(fila)
    {
        jQuery('#lstObjetivosInd tr').each(function () {
            if (jQuery(this).attr('id') == banIndObjetivo) {
                jQuery(this).html(fila);
            }
        })
    }

    /**
     * 
     * GESTIONO EVENTOS 
     * 
     * @param {type} task
     * @returns {undefined}
     */
    Joomla.submitbutton = function (task)
    {
        switch (task) {

            //  Asigno el objetivo creado a lista de indicadores asociados a un objetivo
            case 'indicadorObjetivo.asignar':
                if (validarFrmInd()) {
                    
                    delFilaIndicador( -1 );
                    
                    var objIndicador;

                    if (banIndObjetivo === -1) {
                        objIndicador = new Indicador();
                        objIndicador.idRegIndicador = lstTmpIndicadores.length;
                    } else {
                        objIndicador = lstTmpIndicadores[banIndObjetivo];
                    }

                    objIndicador.idTpoIndicador = jQuery('#jform_idTpoIndicador').val();
                    objIndicador.tpoIndicador = jQuery('#jform_idTpoIndicador :selected').text();

                    objIndicador.idClaseIndicador = jQuery('#jform_idClaseIndicador').val();
                    objIndicador.idUndAnalisis = jQuery('#jform_intIdUndAnalisis').val();
                    objIndicador.idTpoUndMedida = jQuery('#jform_intIdTpoUndMedida').val()
                    objIndicador.idUndMedida = jQuery('#jform_idUndMedida').val();
                    objIndicador.idTendencia = jQuery('#jform_idTendencia').val();
                    objIndicador.idUGResponsable = jQuery('#jform_intIdUndGestion').val();
                    objIndicador.idResponsableUG = jQuery('#jform_intIdUGResponsable').val();
                    objIndicador.idResponsable = jQuery('#jform_idResponsable').val();
                    objIndicador.idHorizonte = jQuery('#jform_IdHorizonte').val();

                    objIndicador.nombreIndicador = jQuery('#jform_nombreIndicador').val();
                    objIndicador.umbral = jQuery('#jform_umbralIndicador').val();
                    objIndicador.undMedida = jQuery('#jform_idUndMedida :selected').text();

                    objIndicador.descripcion = jQuery('#jform_descripcionIndicador').val();
                    objIndicador.formula = jQuery('#formulaDescripcion').val();
                    objIndicador.idFrcMonitoreo = jQuery('#jform_idFrcMonitoreo').val();
                    objIndicador.fchHorzMimimo = jQuery('#jform_hzFchInicio').val();
                    objIndicador.fchHorzMaximo = jQuery('#jform_hzFchFin').val();
                    objIndicador.umbMinimo = jQuery('#jform_valMinimo').val();
                    objIndicador.umbMaximo = jQuery('#jform_valMaximo').val();
                    objIndicador.fchInicioUG = jQuery('#jform_fchInicioPeriodoUG').val();
                    objIndicador.fchInicioFuncionario = jQuery('#jform_fchInicioPeriodoFuncionario').val();
                    objIndicador.idGpoDimension = jQuery('#jform_idGpoDimension').val();
                    objIndicador.idGpoDecision = jQuery('#jform_idGpoDecisiones').val();
                    objIndicador.senplades = jQuery("input[name='jform[intSenplades_indEnt]']:checked").val();

                    //  Informacion complementaria
                    objIndicador.metodologia = jQuery('#jform_metodologia').val();
                    objIndicador.limitaciones = jQuery('#jform_limitacion').val();
                    objIndicador.interpretacion = jQuery('#jform_interpretacion').val();
                    objIndicador.disponibilidad = jQuery('#jform_disponibilidad').val();

                    //  Actualizo informacion de Lineas Base
                    objIndicador.lstLineaBase = new Array();
                    for (var x = 0; x < lstTmpLB.length; x++) {
                        objIndicador.lstLineaBase.push(lstTmpLB[x]);
                    }

                    //  Actualizo informacion de Unidades Territoriales
                    objIndicador.lstUndsTerritoriales = new Array();
                    for (var x = 0; x < lstTmpUT.length; x++) {
                        objIndicador.lstUndsTerritoriales.push(lstTmpUT[x]);
                    }

                    //  Actualizo informacion de Rangos de Gestion
                    objIndicador.lstRangos = new Array();
                    for (var x = 0; x < lstTmpRG.length; x++) {
                        objIndicador.lstRangos.push(lstTmpRG[x]);
                    }

                    //  Actualizo informacion de Dimensiones
                    objIndicador.lstDimensiones = new Array();
                    for (var x = 0; x < lstTmpDim.length; x++) {
                        objIndicador.lstDimensiones.push(lstTmpDim[x]);
                    }

                    //  Actualizo informacion de Variables
                    objIndicador.lstVariables = new Array();
                    for (var x = 0; x < lstTmpVar.length; x++) {
                        objIndicador.lstVariables.push(lstTmpVar[x]);
                    }

                    //  Actualizo informacion de Planificacion de Indicador
                    objIndicador.lstPlanificacion = new Array();
                    for (var x = 0; x < lstTmpPln.length; x++) {
                        objIndicador.lstPlanificacion.push(lstTmpPln[x]);
                    }

                    if (banIndObjetivo === -1) {
                        //  Agrego Indicador a la lista temporal de indicadores
                        lstTmpIndicadores.push(objIndicador);

                        //  Agrego una fila a la tabla que Lista los Indicadores Asociados a un determinado Objetivo
                        jQuery('#lstObjetivosInd > tbody:last').append( objIndicador.addFilaIndicador( 0 ) );
                    } else {
                        //  Actualizo contenido de elemento de tabla temporal de Indicadores - Objetivo
                        lstTmpIndicadores[banIndObjetivo] = objIndicador;

                        //  Actualizo fila de la tabla indicador
                        updFilaIndObjetivo(objIndicador.addFilaIndicador(1));
                    }

                    lstTmpLB = new Array();
                    lstTmpUT = new Array();
                    lstTmpRG = new Array();
                    lstTmpVar = new Array();
                    lstTmpDim = new Array();

                    //  Oculto el Formulario y muestro la lista de indicadores
                    mostrarLstIndicadores();
                }

                break;

                //  Asigno indicadores al objetivo seleccionado
            case 'atributosindicador.asignar':
                switch (tpoIndicador) {
                    //  PEI
                    case 'pei':
                        //  Accedo a la lista de Indicadores Asociados a un determinado Objetivo
                        parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores = new Array();

                        for (var x = 0; x < lstTmpIndicadores.length; x++) {
                            semaforoIndicador(lstTmpIndicadores[x].semaforoImagen());
                            parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores.push(lstTmpIndicadores[x]);
                        }
                        break;

                        //  PPPP
                    case 'pppp':
                        parent.window.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstIndicadores = new Array();

                        for (var x = 0; x < lstTmpIndicadores.length; x++) {
                            parent.window.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstIndicadores.push(lstTmpIndicadores[x]);
                        }

                        break;

                    case 'poa':
                        parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstIndicadores = new Array();

                        for (var x = 0; x < lstTmpIndicadores.length; x++) {
                            parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstIndicadores.push(lstTmpIndicadores[x]);
                        }
                        break;

                    case 'poao':
                        parent.window.dtaPlanOperativo[idPlan].planObjetivo.lstIndicadores = new Array();

                        for (var x = 0; x < lstTmpIndicadores.length; x++) {
                            parent.window.dtaPlanOperativo[idPlan].planObjetivo.lstIndicadores.push( lstTmpIndicadores[x] );
                        }
                        break;

                        //  Registro informacion de indicadores POA - Funcionarios
                    case 'fun':
                        parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstIndicadores = new Array();

                        for (var x = 0; x < lstTmpIndicadores.length; x++) {
                            parent.window.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstIndicadores.push(lstTmpIndicadores[x]);
                        }

                        if (lstTmpIndicadores.length > 0) {
                            semaforoIndicador(lstTmpIndicadores[0].semaforoImagen());
                        } else {
                            var objIndicador = new Indicador();
                            semaforoIndicador(objIndicador.semaforoImagen());
                        }

                        break;

                    default:
                        //  Accedo a la lista de Indicadores Asociados a un determinado Objetivo
                        parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores = new Array();

                        for (var x = 0; x < lstTmpIndicadores.length; x++) {
                            semaforoIndicador(lstTmpIndicadores[x].semaforoImagen());
                            parent.window.objLstObjetivo.lstObjetivos[idObjetivo].lstIndicadores.push(lstTmpIndicadores[x]);
                        }
                        break;
                }

                //  Vacio lista temporal de Indicadores
                lstTmpIndicadores = new Array();

                //  Actualiza la bandera para la programacion de indicadores
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
                //  Cierro la ventana modal( popup )
                window.parent.SqueezeBox.close();
                break;
        }
    }



    function validarFrmInd()
    {
        var ban = false;

        var idTpoIndicador = jQuery('#jform_idTpoIndicador');
        var nombreIndicador = jQuery('#jform_nombreIndicador');
        var umbral = jQuery('#jform_umbralIndicador');
        var idClase = jQuery('#jform_idClaseIndicador');
        var idUndAnalisis = jQuery('#jform_intIdUndAnalisis');
        var idTpoUM = jQuery('#jform_intIdTpoUndMedida');
        var idUM = jQuery('#jform_idUndMedida');

        var idHorizonte = jQuery('#jform_IdHorizonte');
        var fchInicioHz = jQuery('#jform_hzFchInicio');
        var fchFinHz = jQuery('#jform_hzFchFin');
        var idFrcMonitoreo = jQuery('#jform_idFrcMonitoreo');

        var idUG = jQuery('#jform_intIdUndGestion');
        var fchInicioUG = jQuery('#jform_fchInicioPeriodoUG');
        var idUGF = jQuery('#jform_intIdUGResponsable');
        var idResponsable = jQuery('#jform_idResponsable');
        var fchInicioF = jQuery('#jform_fchInicioPeriodoFuncionario');

        if (idTpoIndicador.val() !== ""
                && nombreIndicador.val() !== ""
                && unformatNumber(umbral.val()) !== 0
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
                && fchInicioF.val() !== "") {
            ban = true;
        } else {

            idTpoIndicador.validarElemento();
            nombreIndicador.validarElemento();
            umbral.validarElemento();
            idClase.validarElemento();
            idUndAnalisis.validarElemento();
            idTpoUM.validarElemento();
            idUM.validarElemento();
            idHorizonte.validarElemento();
            fchInicioHz.validarElemento();
            fchFinHz.validarElemento();
            idUG.validarElemento();
            idFrcMonitoreo.validarElemento();
            fchInicioUG.validarElemento();
            idUGF.validarElemento();
            idResponsable.validarElemento();
            fchInicioF.validarElemento();

            jAlert(JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE);
        }

        return ban;
    }


    function limpiarValidaciones()
    {
        jQuery('#jform_idTpoIndicador').delValidaciones();
        jQuery('#jform_nombreIndicador').delValidaciones();
        jQuery('#jform_umbralIndicador').delValidaciones();
        jQuery('#jform_idClaseIndicador').delValidaciones();
        jQuery('#jform_intIdUndAnalisis').delValidaciones();
        jQuery('#jform_intIdTpoUndMedida').delValidaciones();
        jQuery('#jform_idUndMedida').delValidaciones();

        jQuery('#jform_IdHorizonte').delValidaciones();
        jQuery('#jform_hzFchInicio').delValidaciones();
        jQuery('#jform_hzFchFin').delValidaciones();
        jQuery('#jform_idFrcMonitoreo').delValidaciones();

        jQuery('#jform_intIdUndGestion').delValidaciones();
        jQuery('#jform_fchInicioPeriodoUG').delValidaciones();
        jQuery('#jform_intIdUGResponsable').delValidaciones();
        jQuery('#jform_idResponsable').delValidaciones();
        jQuery('#jform_fchInicioPeriodoFuncionario').delValidaciones();

        jQuery('#jform_intSenplades_indEnt1').attr('checked', 'checked');

        return;
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

                if (parseInt(dataInfo[x].idTpoElemento) == 1) {
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
     * Realiza la actualizacion del icono asociado al semaforo
     * 
     * @param {type} dtaSemaforo    Data del semaforo
     * @returns {undefined}
     */
    function semaforoIndicador(dtaSemaforo)
    {
        var id = '#IO' + idObjetivo;

        jQuery(id, window.parent.document).attr('src', dtaSemaforo["imgAtributo"]);
        jQuery(id, window.parent.document).attr('title', dtaSemaforo["msgAtributo"]);
        jQuery(id, window.parent.document).attr('style', dtaSemaforo["msgStyle"]);
    }


    jQuery('#jform_intIdUndAnalisis').change(function () {
        jQuery('#jform_idUndAnalisisNV option').recorrerCombo(jQuery(this).val());
    });

    jQuery('#jform_intIdTpoUndMedida').change(function () {
        jQuery('#jform_idTpoUndMedidaNV option').recorrerCombo(jQuery(this).val());
        jQuery('#jform_idTpoUndMedidaNV').trigger('change', []);
    });

    jQuery('#jform_idUndMedida').change(function () {
        jQuery('#jform_idVarUndMedidaNV option').recorrerCombo(jQuery(this).val());
    });

    jQuery('#jform_intIdUndGestion').change(function () {
        jQuery('#jform_idUGResponsableVar option').recorrerCombo(jQuery(this).val());
    });

    jQuery('#jform_intIdUGResponsable').change(function () {
        jQuery('#jform_idUGFuncionarioVar option').recorrerCombo(jQuery(this).val());
        jQuery('#jform_idUGFuncionarioVar').trigger('change', []);
    });

    jQuery('#jform_idResponsable').change(function () {
        jQuery('#jform_idFunResponsableVar option').recorrerCombo(jQuery(this).val());
    });

});