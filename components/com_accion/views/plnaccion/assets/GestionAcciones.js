jQuery( document ).ready( function(){
    var flagAccionObj = -1;
    
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

    var idObjetivo  = jQuery( '#idRegObjetivo' ).val();
    var idPlan      = jQuery( '#idRegPlan' ).val();
    var idTpoObj    = 0;
    var tpoPlan     = 0;
    var lstAcciones = new Array();
    
    if ( typeof (idPlan) != 'undefined' && idPlan != '' ) {
        tpoPlan = parseInt(jQuery( '#tpoPln' ).val());
        
        if ( tpoPlan == 2 ) {
            // Bloquea el combo de unidades de gestion responsables
            unableResponsables();
        } else if ( tpoPlan == 3 || tpoPlan == 4 ) {
            //  Bloquea la opcion de gestionar las acciones
            unableAddAccion();
        }
        
        switch (tpoPlan) {
            case 2:
                idTpoObj = window.parent.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].idTpoObj;
                if ( idTpoObj == 1 ){
                    //  Bloquea la opcion de gestionar las acciones
                    unableAddAccion();
                } 
                lstAcciones = window.parent.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstAcciones;
            break;

            case 3:
                lstAcciones = window.parent.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones;
            break;

            case 4:
                lstAcciones = window.parent.oLstPAPPs.lstPapp[idPlan].lstObjetivos[idObjetivo].lstAcciones;
            break;

            case 5:
                lstAcciones = window.parent.dtaPlanOperativo[idPlan].planObjetivo.lstAcciones;
            break;
        }
      
    } else {
        lstAcciones = window.parent.objLstObjetivo.lstObjetivos[idObjetivo].lstAcciones;
        tpoPlan = 1;
    }

    lstTmpAcciones = new Array();
    
    for( var x = 0; x < lstAcciones.length; x++ ){
        if (lstAcciones[x].published == 1 ){
            var objAccion = new Accion();
            objAccion.setDtaAccion( lstAcciones[x] );

            //  Agrego informacion en la lista temporal
            lstTmpAcciones.push( objAccion );

            // agrego fila en tabla temporal
            agregarFila( objAccion );
        }
    }
    
    /**
     *  Guarda la data general de una accion en el array de acciondes de un objetivo
     */
    jQuery("#btnAddAccion").click(function() {
        if (objValido()) {
            var monto = unformatNumber( jQuery("#jform_mnPresupuesto_plnAccion").val() );
            
            if (flagAccionObj == -1) {
                var objAccion = new Accion();
                

                objAccion.registroAcc           = lstTmpAcciones.length;
                objAccion.idAccion              = 0;
                objAccion.descripcionAccion     = jQuery("#jform_strDescripcion_plnAccion").val();
                objAccion.idTipoAccion          = jQuery("#jform_intTpoActividad_plnAccion :selected").val();
                objAccion.descTipoActividad     = jQuery("#jform_intTpoActividad_plnAccion :selected").text();
                objAccion.observacionAccion     = jQuery("#jform_strObservacion_plnAccion").val();
                objAccion.fechaInicioAccion     = jQuery("#jform_dteFechaInicio_planAccion").val();
                objAccion.fechaFinAccion        = jQuery("#jform_dteFechaFin_planAccion").val();
                objAccion.presupuestoAccion     = ( parseFloat( monto ) ).toFixed( 2 );
                objAccion.idUniGes              = jQuery("#jform_intCodigo_ug :selected").val();
                objAccion.idAccionUGR           = 0;
                objAccion.idFunResp             = jQuery("#jform_intId_ugf :selected").val();
                objAccion.idAccionFR            = 0;
                objAccion.idPlnObjetivo         = 0;
                objAccion.idPlnObjAccion        = 0;
                objAccion.unidadGestionFun      = jQuery("#jform_unidad_gestion :selected").val();
                objAccion.fechaInicioUGR        = jQuery("#jform_dteFechaInicio_plnUGR").val();
                objAccion.fechaInicioFR         = jQuery("#jform_dteFechaInicio_plnFR").val();
                
                lstTmpAcciones.push( objAccion );
                agregarFila(objAccion);

            } else {
                var numReg = lstTmpAcciones.length;
                for (var i = 0; i < numReg; i++) {
                    if (lstTmpAcciones[i].registroAcc == flagAccionObj) {
                        if ( lstTmpAcciones[i].idUniGes != jQuery("#jform_intCodigo_ug :selected").val() || lstTmpAcciones[i].fechaInicioUGR != jQuery("#jform_dteFechaInicio_plnUGR").val() ){
                            lstTmpAcciones[i].updUGR = true;
                        }
                        
                        if (lstTmpAcciones[i].idFunResp != jQuery("#jform_intId_ugf :selected").val() || lstTmpAcciones[i].fechaInicioFR != jQuery("#jform_dteFechaInicio_plnFR").val() ){
                            lstTmpAcciones[i].updFR = true;
                        }
                        
                        lstTmpAcciones[i].descripcionAccion = jQuery("#jform_strDescripcion_plnAccion").val();
                        lstTmpAcciones[i].idTipoAccion      = jQuery("#jform_intTpoActividad_plnAccion :selected").val();
                        lstTmpAcciones[i].descTipoActividad = jQuery("#jform_intTpoActividad_plnAccion :selected").text();
                        lstTmpAcciones[i].observacionAccion = jQuery("#jform_strObservacion_plnAccion").val();
                        lstTmpAcciones[i].fechaInicioAccion = jQuery("#jform_dteFechaInicio_planAccion").val();
                        lstTmpAcciones[i].fechaFinAccion    = jQuery("#jform_dteFechaFin_planAccion").val();
                        lstTmpAcciones[i].presupuestoAccion = ( parseFloat( monto ) ).toFixed( 2 );
                        lstTmpAcciones[i].idUniGes          = jQuery("#jform_intCodigo_ug :selected").val();
                        lstTmpAcciones[i].idFunResp         = jQuery("#jform_intId_ugf :selected").val();
                        lstTmpAcciones[i].unidadGestionFun  = jQuery("#jform_unidad_gestion :selected").val();
                        lstTmpAcciones[i].fechaInicioUGR    = jQuery("#jform_dteFechaInicio_plnUGR").val();
                        lstTmpAcciones[i].fechaInicioFR     = jQuery("#jform_dteFechaInicio_plnFR").val();
                        lstTmpAcciones[i].published         = 1;

                        actualizarFila(lstTmpAcciones[i]);
                        flagAccionObj = -1;
                    }
                }
            }
            //  limpio el formulario y reinicio la variables
            resetFrmAcc();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }
    });

    /**
     * Boton que habilita el formulario de data general de un objetivo
     * @returns {undefined}
     */
    jQuery('#addAccionPln').click(function() {
        //  Si se esta editando un accion, flagAccionObj tiene el id de la accion.
        if (flagAccionObj != -1) {
            var id = -1;
            guardarCambios(id, true);
        } else {
            valoresPorDefault();
            jQuery("#frmPlnAccion").css("display", "block");
            jQuery("#imgPlnAccion").css("display", "none");
        }
    });
    
    /**
     * cancela la edición de una actividad y limpia el formulario 
     */
    jQuery("#btnCancelAccion").click(function() {
        var id = -1;
        if (flagAccionObj != -1) {
            guardarCambios(id, false);
        } else {
            resetFrmAcc();
        }
    });
    
    /**
     * cancela la edición de una actividad y limpia el formulario 
     */
    jQuery("#btnCerrar").click(function() {
        resetFrmAcc();
    });
    
    /**
     *  clase que permite la edición de una acción de una objetivo
     */
    jQuery(".updAccionObj").live("click", function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        if (flagAccionObj != -1 && flagAccionObj != idFila) {
            guardarCambios(idFila, true)
        } else {
            flagAccionObj = idFila;
            updDataAccion(idFila);
        }
    });
    
    /**
     *  clase que permite ver la informacion de una acción de una objetivo
     */
    jQuery(".verAccionObj").live("click", function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        flagAccionObj = idFila;
        updDataAccion(idFila);
    });

    /**
     *  Elimina un registro de accion de un objetivo
     */
    jQuery(".delAccionObj").live("click", function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        
        jConfirm(JSL_CONFIRM_DEL_ACCION_EDIT, JSL_ECORAE, function(result) {
            if( result ){
                delRegistro( idFila );
                
                //  Elimino la fila de la tabla
                delFila( idFila );
            }
        });

    });
    
    
    /**
     * 
     * Setea valores por default al formulario de gestion de acciones
     * 
     * @returns {undefined}
     */
    function valoresPorDefault()
    {
        var fchInicio = jQuery( '#jform_dteFechainicio_pi', window.parent.document ).val();
        var fchFin = jQuery( '#jform_dteFechafin_pi', window.parent.document ).val();

        jQuery('#jform_dteFechaInicio_planAccion, #jform_dteFechaInicio_plnFR, #jform_dteFechaInicio_plnUGR').attr( 'value', fchInicio );
        jQuery('#jform_dteFechaFin_planAccion').attr( 'value', fchFin );
    }
    
    
    /**
     * Bloquea los combos de Fnc y UG responsables
     * @returns {undefined}
     */
    function unableResponsables()
    {
        var idUG = jQuery("#idUG").val();
        var idFnc = jQuery("#idFnc").val();
        
        recorrerCombo( jQuery("#jform_intCodigo_ug option" ), idUG );
        jQuery("#jform_intCodigo_ug").attr('disabled','disabled');
        recorrerCombo( jQuery("#jform_unidad_gestion option" ), idUG );
        jQuery("#jform_unidad_gestion").attr('disabled','disabled');
        jQuery('#jform_unidad_gestion').trigger('change', idFnc );
        
        if ( idFnc != 'undefined' && idFnc != '' ) {
            jQuery("#jform_intId_ugf").attr('disabled','disabled');
        }
    }
    
    /**
     *  Desabilita la opcion de gestionar las acciones
     * @returns {undefined}
     */
    function unableAddAccion()
    {
        jQuery( "#addAccionPln" ).css("display", "none");
        jQuery( "#btnCancelAccion" ).css("display", "none");
        jQuery( "#btnAddAccion" ).css("display", "none");
        jQuery( "#btnCerrar" ).css("display", "block");
    }
    /**
     *  Controla si de a modificado la data de una accion para guardarlo o no.
     * 
     * @param {type} idReg      Id de registro de la accion (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function guardarCambios(idReg, op)
    {
        if (confirmUpdAcc(flagAccionObj)) {
            autoSave(idReg, op);
        } else {
            controlAutoSave(idReg, op);
        }
    }
    
    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     *  solo llama a la funcion "controlAutoSave" que reliza los controles de edicion.
     * 
     * @param {type} idFila     Id de registro de la Acción (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function autoSave(idFila, op) 
    {
        jConfirm(JSL_CONFIRM_UPD_ACCION, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnAddAccion').trigger('click');
                controlAutoSave(idFila, op);
            } else {
                controlAutoSave(idFila, op);
            }
        });
    }
    
    /**
     *  Realiza las tareas especificas cuando guarda cambios en un registro
     * 
     * @param {type} idFila     Id de registro de la Acción (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function controlAutoSave(idFila, op) 
    {
        jQuery("#updAcc-" + flagAccionObj).html(JSL_EDITAR);
        if (idFila != -1) {
            flagAccionObj = idFila;
            updDataAccion(idFila);
        } else {
            flagAccionObj = -1;
            resetFrmAcc();
            if (op == true) {
                jQuery("#frmPlnAccion").css("display", "block");
                jQuery("#imgPlnAccion").css("display", "none");
            }
        }
    }


    function confirmUpdAcc(idFila) 
    {
        var resultado = false;
        for (var i = 0; i < lstTmpAcciones.length; i++) {
            if (lstTmpAcciones[i].registroAcc == idFila) {
                if (lstTmpAcciones[i].descripcionAccion != jQuery("#jform_strDescripcion_plnAccion").val() ||
                        lstTmpAcciones[i].idTipoAccion != jQuery("#jform_intTpoActividad_plnAccion :selected").val() ||
                        lstTmpAcciones[i].observacionAccion != jQuery("#jform_strObservacion_plnAccion").val() ||
                        lstTmpAcciones[i].fechaInicioAccion != jQuery("#jform_dteFechaInicio_planAccion").val() ||
                        lstTmpAcciones[i].fechaFinAccion != jQuery("#jform_dteFechaFin_planAccion").val() ||
                        lstTmpAcciones[i].fechaInicioUGR != jQuery("#jform_dteFechaInicio_plnUGR").val() ||
                        lstTmpAcciones[i].fechaInicioFR != jQuery("#jform_dteFechaInicio_plnFR").val() ||
                        (parseFloat(lstTmpAcciones[i].presupuestoAccion)).toFixed(2) != (parseFloat(jQuery("#jform_mnPresupuesto_plnAccion").val())).toFixed(2) ||
                        lstTmpAcciones[i].idUniGes != jQuery("#jform_intCodigo_ug :selected").val() ||
                        lstTmpAcciones[i].idFunResp != jQuery("#jform_intId_ugf :selected").val() ||
                        lstTmpAcciones[i].unidadGestionFun != jQuery("#jform_unidad_gestion :selected").val())
                    resultado = true;
            }
        }
        return resultado;
    }

    /**
     * 
     * @param {type} idFila
     * @returns {undefined}
     */
    function updDataAccion(idFila) 
    {
        for (var i = 0; i < lstTmpAcciones.length; i++) {
            if (lstTmpAcciones[i].registroAcc == idFila) {
                jQuery("#updAcc-" + idFila).html(JSL_EDITANDO);
                jQuery("#frmPlnAccion").css("display", "block");
                jQuery("#imgPlnAccion").css("display", "none");
                
                var ta      = lstTmpAcciones[i].idTipoAccion;
                var ugr     = lstTmpAcciones[i].idUniGes;
                var fr      = lstTmpAcciones[i].idFunResp;
                var ugfun   = lstTmpAcciones[i].unidadGestionFun;
                var monto   = ( parseFloat( lstTmpAcciones[i].presupuestoAccion ) ).toFixed( 2 );
                
                recorrerCombo(jQuery('#jform_intTpoActividad_plnAccion option'), ta);
                recorrerCombo(jQuery('#jform_unidad_gestion option'), ugfun);
                jQuery('#jform_unidad_gestion').trigger('change', fr);
                recorrerCombo(jQuery('#jform_intCodigo_ug option'), ugr);
                
                jQuery("#jform_strDescripcion_plnAccion").val(lstTmpAcciones[i].descripcionAccion);
                jQuery("#jform_strObservacion_plnAccion").val(lstTmpAcciones[i].observacionAccion);
                jQuery("#jform_dteFechaInicio_planAccion").val(lstTmpAcciones[i].fechaInicioAccion);
                jQuery("#jform_dteFechaFin_planAccion").val(lstTmpAcciones[i].fechaFinAccion);
                jQuery("#jform_mnPresupuesto_plnAccion").val( formatNumber( monto, '$' ) );
                
                jQuery("#jform_dteFechaInicio_plnUGR").val(lstTmpAcciones[i].fechaInicioUGR);
                jQuery("#jform_dteFechaInicio_plnFR").val(lstTmpAcciones[i].fechaInicioFR);
            }
        }
    }
    
    /**
     *  Elimina un registro del array de objetos accion de un objetivo
     * 
     * @param {type} idFila     Id de resgistro a ser eliminado
     * @returns {undefined}
     */
    function delRegistro(idFila) 
    {
        for (var x = 0; x < lstTmpAcciones.length; x++) {
            if ( lstTmpAcciones[x].registroAcc === parseInt( idFila ) ) {

                if( lstTmpAcciones[x].idAccion === 0 ){
                    //  Elimino Rango del la tabla Temporal de Rangos
                    lstTmpAcciones.splice( idFila, 1 );
                }else{
                    //  Actualizo el estado del registro a cero
                    lstTmpAcciones[x].published = 0;
                }

            }            
        }
    }
    
    /**
     *  Agrega una fila a la tabla de acciones de un objetivo
     * 
     * @param {type} accion
     * @returns {undefined}
     */
    function agregarFila( accion )
    {
        //  Crea la fila para la tabla de acciones
        var fila = makeFilaAcc( accion, 0 );
        //  Agrego la fila creada a la tabla
        jQuery('#tbLstPlanAccion > tbody:last').append(fila);
    }


    /**
     *  Crea una fila para la lista de acciones de un objetivo
     * @param {type} accion
     * @param {type} op
     * @returns {String}
     */
    function makeFilaAcc( accion, op )
    {
        var monto = parseFloat( accion.presupuestoAccion ).toFixed( 2 );
        
        var idReg = accion.registroAcc;
        var descp = (accion.descripcionAccion) ? accion.descripcionAccion : "-----";
        var tpoAc = (accion.descTipoActividad) ? accion.descTipoActividad : "-----";
        var prsAc = (accion.presupuestoAccion) ? ( formatNumber( monto, '$' ) )  : "-----";
        var fchInc = (accion.fechaInicioAccion) ? accion.fechaInicioAccion : "-----";
        var fchFin = (accion.fechaFinAccion) ? accion.fechaFinAccion : "-----";

        var fila = '';
        fila += (op == 0) ? '<tr id="' + idReg + '">' : '';
        
        fila += '     <td align="center" style="width: 150px; vertical-align: middle;" >' + descp + '</td>';
        fila += '     <td align="center" style="width: 50px; vertical-align: middle;">' + tpoAc + '</td>';
        fila += '     <td align="right" style="vertical-align: middle;">' + prsAc + '</td>';
        fila += '     <td align="center" style="vertical-align: middle;">' + fchInc + '</td>';
        fila += '     <td align="center" style="vertical-align: middle;">' + fchFin + '</td>';
        
        idTpoObj;
        tpoPlan;
        switch ( true ){
            case ( ( tpoPlan == 1 || ( tpoPlan == 2 && idTpoObj != 1 ) ) && roles["core.create"] === true || roles["core.edit"] === true ):
                fila += '     <td align="center" style="vertical-align: middle;"> <a id="updAcc-' + idReg + '" class="updAccionObj" >' + JSL_EDITAR + '</a> </td > ';
                fila += '     <td align="center" style="vertical-align: middle;"> <a id="delAcc-' + idReg + '" class="delAccionObj" >' + JSL_ELIMINAR + '</a> </td>';
                break;
            case ( ( ( tpoPlan == 2 && idTpoObj == 1 ) || ( tpoPlan == 3 || tpoPlan == 4 ) ) && roles["core.create"] === true || roles["core.edit"] === true ):
                fila += '     <td align="center" style="vertical-align: middle;"> <a id="verAcc-' + idReg + '" class="verAccionObj" >' + JSL_VER + '</a> </td > ';
                fila += '     <td align="center" style="vertical-align: middle;"> <a id="noAcctionAcc-' + idReg + '" >' + JSL_NO_HABILITADO + '</a> </td>';
                break;
        } 
        
        fila += (op == 0) ? '</tr>' : '';
        
        return fila;
    }
    
    /**
     *  Limpia el formulario de plan de accion de un objetivo
     * 
     * @returns {undefined}
     */
    function resetFrmAcc() 
    {
        jQuery("#frmPlnAccion").css("display", "none");
        jQuery("#imgPlnAccion").css("display", "block");
        
        recorrerCombo(jQuery('#jform_intTpoActividad_plnAccion option'), 0);
        jQuery("#jform_strDescripcion_plnAccion").attr('value', '');
        jQuery("#jform_dteFechaInicio_planAccion").attr('value', '');
        jQuery("#jform_dteFechaFin_planAccion").attr('value', '');
        jQuery("#jform_dteFechaInicio_plnUGR").attr('value', '');
        jQuery("#jform_dteFechaInicio_plnFR").attr('value', '');
        jQuery("#jform_mnPresupuesto_plnAccion").attr('value', '');
        jQuery("#jform_strObservacion_plnAccion").attr('value', '');
        
        switch (tpoPlan){
            case 2:
                var idUG = jQuery("#idUG").val();
                var idFnc = jQuery("#idFnc").val();
                recorrerCombo(jQuery('#jform_intCodigo_ug option'), idUG);
                recorrerCombo(jQuery('#jform_unidad_gestion option'), idUG);
                jQuery('#jform_unidad_gestion').trigger('change', idFnc);
                break;
            default:
                recorrerCombo(jQuery('#jform_unidad_gestion option'), 0);
                jQuery('#jform_unidad_gestion').trigger('change', 0);
                recorrerCombo(jQuery('#jform_intCodigo_ug option'), 0);
                break;
        }
        
        limpiarValidaciones();
    }
    
    /**
     *  Actuliza una fila de la tabla de acciones de un objetivo
     * 
     * @param {type} accion
     * @returns {undefined}
     */
    function actualizarFila(accion) 
    {
        jQuery('#tbLstPlanAccion tr').each(function() {
            if (jQuery(this).attr('id') == flagAccionObj) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');
                //  Construyo la Fila
                var fila = makeFilaAcc( accion, 1 );
                //  Crea la fila para la tabla de acciones
                jQuery(this).html(fila);
            }
        });
    }
    
    /**
     *  Verifica los campos obligatorios del formulario fueron ingresados
     *  devuelve true se es valido y false si no lo es.
     * 
     * @returns {Boolean}
     */
    function objValido() 
    {
        var ban = false;

        var idTpoAccion     = jQuery( '#jform_intTpoActividad_plnAccion' );
        var descAccion      = jQuery( '#jform_strDescripcion_plnAccion' );
        var fchInicioAccion = jQuery( '#jform_dteFechaInicio_planAccion' );
        var fchFinAccion    = jQuery( '#jform_dteFechaFin_planAccion' );
        var idUG            = jQuery( '#jform_unidad_gestion' );
        var fiUGR           = jQuery( '#jform_dteFechaInicio_plnUGR' );
        var fiFR            = jQuery( '#jform_dteFechaInicio_plnFR' );
        var presupuesto     = jQuery( '#jform_mnPresupuesto_plnAccion' );
        var idUGF           = jQuery( '#jform_intId_ugf' );
        var idIG            = jQuery( '#jform_intCodigo_ug' );

        if( jQuery.isNumeric( idTpoAccion.val() ) 
            && parseInt( idTpoAccion.val() ) > 0 
            && descAccion.val() !== ""
            && fchInicioAccion.val() !== ""
            && fchFinAccion.val() !== ""
            && jQuery.isNumeric( idUG.val() ) !== ""
            && fiUGR.val() !== ""
            && fiFR.val() !== ""
            && unformatNumber( presupuesto.val() ) !== 0
            && jQuery.isNumeric( idUGF.val() ) !== ""
            && jQuery.isNumeric( idIG.val() ) !== "" ){
            ban = true;
        }else{
            validarElemento( idTpoAccion );
            validarElemento( descAccion );
            validarElemento( fchInicioAccion );
            validarElemento( fchFinAccion );
            validarElemento( idUG );
            validarElemento( fiUGR );
            validarElemento( fiFR );
            validarElemento( presupuesto );
            validarElemento( idUGF );
            validarElemento( idIG );
            
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
    
    
    
    function limpiarValidaciones()
    {
        delValidaciones( jQuery('#jform_intTpoActividad_plnAccion') );
        delValidaciones( jQuery('#jform_strDescripcion_plnAccion') );
        delValidaciones( jQuery('#jform_dteFechaInicio_planAccion') );
        delValidaciones( jQuery('#jform_dteFechaFin_planAccion') );
        delValidaciones( jQuery('#jform_unidad_gestion') );
        delValidaciones( jQuery('#jform_dteFechaInicio_plnUGR') );
        delValidaciones( jQuery('#jform_dteFechaInicio_plnFR') );
        delValidaciones( jQuery('#jform_mnPresupuesto_plnAccion') );
        delValidaciones( jQuery('#jform_intId_ugf') );
        delValidaciones( jQuery('#jform_intCodigo_ug') );

        return;
    }
    
    
    function delValidaciones( obj )
    {
        obj.attr('class', '');
        var lbl = obj.selector + '-lbl';

        jQuery(lbl).attr('class', '');
        jQuery(lbl).attr('aria-invalid', '');
    }
    
    
    
    
    
    /**
     *  Elimina una fila de la tabla de alineaciones
     *  
     * @param {type} idFila
     * @returns {undefined}
     */
    function delFila(idFila)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#tbLstPlanAccion tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }
    
    /**
     *  Recorro un determinado combo a una posicion especifica
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
        });
    }
    
});