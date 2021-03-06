jQuery( document ).ready( function(){
    var flagAccionObj = -1;
    
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
    
    var idObjetivo = jQuery( '#idObjetivo' ).val();
    var lstAcciones = window.parent.objLstObjetivo.lstObjetivos[idObjetivo].lstAcciones;
    lstTmpAcciones = new Array();
    
    for( var x = 0; x < lstAcciones.length; x++ ){
        var objAccion = new Accion();
        objAccion.setDtaAccion( lstAcciones[x] );
        
        //  Agrego informacion en tabla temporal
        lstTmpAcciones.push( objAccion );
        
        // agrego fila en tabla temporal
        agregarFila( objAccion );
    }
    
    /**
     *  Guarda la data general de una accion en el array de acciondes de un objetivo
     */
    jQuery("#btnAddAccion").click(function() {
        var accion = [];
        if (objValido()) {
            if (flagAccionObj == -1) {
                var objAccion = new Accion();
                    
                objAccion.registroAcc           = lstTmpAcciones.length;
                objAccion.idAccion              = 0;
                objAccion.descripcionAccion     = jQuery("#jform_strDescripcion_plnAccion").val();
                objAccion.idTipoAccion          = jQuery("#jform_intTpoActividad_plnAccion :selected").val();
                objAccion.descTipoActividad     = jQuery("#jform_intTpoActividad_plnAccion :selected").text();
                objAccion.obserbacionAccion     = jQuery("#jform_strObservacion_plnAccion").val();
                objAccion.fechaEjecucionAccion  = jQuery("#jform_dteFechaEjecucion_planAccion").val();
                objAccion.presupuestoAccion     = (parseFloat(jQuery("#jform_mnPresupuesto_plnAccion").val())).toFixed(2);
                objAccion.idUniGes              = jQuery("#jform_intCodigo_ug :selected").val();
                objAccion.idAccionUGR           = 0;
                objAccion.idFunResp             = jQuery("#jform_intId_ugf :selected").val();
                objAccion.idAccionFR            = 0;
                objAccion.unidadGestionFun      = jQuery("#jform_unidad_gestion :selected").val();
                
                lstTmpAcciones.push( objAccion );
                agregarFila(objAccion);

            } else {
                var numReg = lstTmpAcciones.length;
                for (var i = 0; i < numReg; i++) {
                    if (lstTmpAcciones[i].registroAcc == flagAccionObj) {
                        if (lstTmpAcciones[i].idUniGes != jQuery("#jform_intCodigo_ug :selected").val())
                            lstTmpAcciones[i].updUGR = true;
                        if (lstTmpAcciones[i].idFunResp != jQuery("#jform_intId_ugf :selected").val())
                            lstTmpAcciones[i].updFR = true;
                        lstTmpAcciones[i].descripcionAccion = jQuery("#jform_strDescripcion_plnAccion").val();
                        lstTmpAcciones[i].idTipoAccion = jQuery("#jform_intTpoActividad_plnAccion :selected").val();
                        lstTmpAcciones[i].descTipoActividad = jQuery("#jform_intTpoActividad_plnAccion :selected").text();
                        lstTmpAcciones[i].obserbacionAccion = jQuery("#jform_strObservacion_plnAccion").val();
                        lstTmpAcciones[i].fechaEjecucionAccion = jQuery("#jform_dteFechaEjecucion_planAccion").val();
                        lstTmpAcciones[i].presupuestoAccion = (parseFloat(jQuery("#jform_mnPresupuesto_plnAccion").val())).toFixed(2);
                        lstTmpAcciones[i].idUniGes = jQuery("#jform_intCodigo_ug :selected").val();
                        lstTmpAcciones[i].idFunResp = jQuery("#jform_intId_ugf :selected").val();
                        lstTmpAcciones[i].unidadGestionFun = jQuery("#jform_unidad_gestion :selected").val();
                        lstTmpAcciones[i].published = 1;

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
     *  Elimina un registro de accion de un objetivo
     */
    jQuery(".delAccionObj").live("click", function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        if (idFila == flagAccionObj) {
            jConfirm(JSL_CONFIRM_DEL_ACCION_EDIT, JSL_ECORAE, function(result) {
                if (result) {
                    controlAutoSave(-1, false);
                    delRegistro(idFila);
                }
            });
        } else {
            jConfirm(JSL_CONFIRM_DEL_ACCION, JSL_ECORAE, function(result) {
                if (result) {
                    delRegistro(idFila);
                }
            });
        }
    });
    
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
                jQuery('#btnAddActividad').trigger('click');
                controlAutoSave(idFila, op);
            } else {
                controlAutoSave(idFila);
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
        jQuery("#updAcc-" + flagAccionObj).html("Editar");
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
                        lstTmpAcciones[i].obserbacionAccion != jQuery("#jform_strObservacion_plnAccion").val() ||
                        lstTmpAcciones[i].fechaEjecucionAccion != jQuery("#jform_dteFechaEjecucion_planAccion").val() ||
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
    function updDataAccion(idFila) {
        for (var i = 0; i < lstTmpAcciones.length; i++) {
            if (lstTmpAcciones[i].registroAcc == idFila) {
                jQuery("#updAcc-" + idFila).html("Editando...");
                jQuery("#frmPlnAccion").css("display", "block");
                jQuery("#imgPlnAccion").css("display", "none");
                var ta = lstTmpAcciones[i].idTipoAccion;
                var ugr = lstTmpAcciones[i].idUniGes;
                var fr = lstTmpAcciones[i].idFunResp;
                var ugfun = lstTmpAcciones[i].unidadGestionFun;
                recorrerCombo(jQuery('#jform_intTpoActividad_plnAccion option'), ta);
                recorrerCombo(jQuery('#jform_unidad_gestion option'), ugfun);
                jQuery('#jform_unidad_gestion').trigger('change', fr);
                recorrerCombo(jQuery('#jform_intCodigo_ug option'), ugr);
                jQuery("#jform_strDescripcion_plnAccion").val(lstTmpAcciones[i].descripcionAccion);
                jQuery("#jform_strObservacion_plnAccion").val(lstTmpAcciones[i].obserbacionAccion);
                jQuery("#jform_dteFechaEjecucion_planAccion").val(lstTmpAcciones[i].fechaEjecucionAccion);
                jQuery("#jform_mnPresupuesto_plnAccion").val((parseFloat(lstTmpAcciones[i].presupuestoAccion)).toFixed(2));
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
            if (lstTmpAcciones[x].registroAcc == idFila) {
                //  Actualizo el estado del registro a cero
                lstTmpAcciones[x].published = 0;
                //  Elimino la fila de la tabla
                delFila(idFila);
            }
        }
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
                jQuery("#updAcc-" + idFila).html("Editando...");
                jQuery("#frmPlnAccion").css("display", "block");
                jQuery("#imgPlnAccion").css("display", "none");
                var ta = lstTmpAcciones[i].idTipoAccion;
                var ugr = lstTmpAcciones[i].idUniGes;
                var fr = lstTmpAcciones[i].idFunResp;
                var ugfun = lstTmpAcciones[i].unidadGestionFun;
                recorrerCombo(jQuery('#jform_intTpoActividad_plnAccion option'), ta);
                recorrerCombo(jQuery('#jform_unidad_gestion option'), ugfun);
                jQuery('#jform_unidad_gestion').trigger('change', fr);
                recorrerCombo(jQuery('#jform_intCodigo_ug option'), ugr);
                jQuery("#jform_strDescripcion_plnAccion").val(lstTmpAcciones[i].descripcionAccion);
                jQuery("#jform_strObservacion_plnAccion").val(lstTmpAcciones[i].obserbacionAccion);
                jQuery("#jform_dteFechaEjecucion_planAccion").val(lstTmpAcciones[i].fechaEjecucionAccion);
                jQuery("#jform_mnPresupuesto_plnAccion").val((parseFloat(lstTmpAcciones[i].presupuestoAccion)).toFixed(2));
            }
        }
    }
    
    /**
     *  Agrega una fila a la tabla de acciones de un objetivo
     * 
     * @param {type} accion
     * @returns {undefined}
     */
    function agregarFila(accion)
    {
        var idReg = accion.registroAcc;
        var descp = (accion.descripcionAccion) ? accion.descripcionAccion : "-----";
        var tpoAc = (accion.descTipoActividad) ? accion.descTipoActividad : "-----";
        var prsAc = (accion.presupuestoAccion) ? (parseFloat(accion.presupuestoAccion)).toFixed(2) : "-----";
        var fchEj = (accion.fechaEjecucionAccion) ? accion.fechaEjecucionAccion : "-----";

        var fila = '<tr id="' + idReg + '">'
                + '     <td align="center">' + descp + '</td>'
                + '     <td align="center">' + tpoAc + '</td>'
                + '     <td align="center">$ ' + prsAc + ' Usd</td>'
                + '     <td align="center">' + fchEj + '</td>'
                + '     <td align="center" width="15" > <a id="updAcc-' + idReg + '" class="updAccionObj" >Editar</a> </td > '
                + '     <td align="center" width="15" > <a id="delAcc-' + idReg + '" class="delAccionObj" >Eliminar</a> </td>'
                + ' </tr>';

        //  Agrego la fila creada a la tabla
        jQuery('#tbLstPlanAccion > tbody:last').append(fila);
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
        jQuery("#jform_dteFechaEjecucion_planAccion").attr('value', '');
        jQuery('#jform_unidad_gestion').trigger('change', 0);
        recorrerCombo(jQuery('#jform_intCodigo_ug option'), 0);
        jQuery("#jform_mnPresupuesto_plnAccion").attr('value', '');
        jQuery("#jform_strObservacion_plnAccion").attr('value', '');
    }
    
    /**
     *  Actuliza una fila de la tabla de acciones de un objetivo
     * 
     * @param {type} accion
     * @returns {undefined}
     */
    function actualizarFila(accion) 
    {

        var idReg = accion.registroAcc;
        var descp = (accion.descripcionAccion) ? accion.descripcionAccion : "-----";
        var tpoAc = (accion.descTipoActividad) ? accion.descTipoActividad : "-----";
        var prsAc = (accion.presupuestoAccion) ? (parseFloat(accion.presupuestoAccion)).toFixed(2) : "-----";
        var fchEj = (accion.fechaEjecucionAccion) ? accion.fechaEjecucionAccion : "-----";

        jQuery('#tbLstPlanAccion tr').each(function() {

            if (jQuery(this).attr('id') == flagAccionObj) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');
                //  Construyo la Fila
                var fila = '    <td align="center">' + descp + '</td>'
                        + '     <td align="center">' + tpoAc + '</td>'
                        + '     <td align="center">$ ' + prsAc + ' Usd</td>'
                        + '     <td align="center">' + fchEj + '</td>'
                        + '     <td align="center" width="15" > <a id="updAcc-' + idReg + '" class="updAccionObj" >Editar</a> </td > '
                        + '     <td align="center" width="15" > <a id="delAcc-' + idReg + '" class="delAccionObj" >Eliminar</a> </td>';
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
        var resultado = false;
        if (jQuery("#jform_strDescripcion_plnAccion").val() != "" &&
                jQuery("#jform_intTpoActividad_plnAccion :selected").val() != 0 &&
                jQuery("#jform_dteFechaEjecucion_planAccion").val() != "" &&
                jQuery("jform_mnPresupuesto_plnAccion").val() != "" &&
                jQuery("#jform_intId_ugf :selected").val() != 0 &&
                jQuery("#jform_intCodigo_ug :selected").val() != 0)
            resultado = true;

        return resultado;

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