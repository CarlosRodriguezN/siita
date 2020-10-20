jQuery(document).ready(function() {
    
    var flagDetalle = -1;
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
    
    //  Caraga la tabla con la lista de detalles
    listarDetalles();

    /**
     *  Habilita el formulario para los detalles de una agenda
     */
    jQuery('#addDetalleTable').click(function() {
        //  Si se esta editando un detalle flagDetalle tiene el ID del registro editandose.
        if ( flagDetalle != -1) {
            var id = -1;
            validarCambios( id, true );
        } else {
            jQuery("#imgDetalleAgd").css("display", "none");
            jQuery("#frmDetalleAgd").css("display", "block");
        }
    });
    
    /**
     *  Guarda la data general de los detalles de una agenda
     */
    jQuery("#btnSaveDtll").click(function() {
        if ( objValido() ) {
            var numReg = objLstDetallesAgd.lstDetallesAgd.length;
            if ( numReg == 0 || !existRegAvalible( objLstDetallesAgd.lstDetallesAgd )) {
                jQuery("#srDtll").css("display", "none");
            }
            if ( flagDetalle == -1  ) {
                //  Creo el nuevo objeto Detalle
                var objDetalle = new DetalleAgd();

                objDetalle.registroDtll = numReg;
                objDetalle.idDetalle    = 0;
                objDetalle.idAgenda     = parseInt( jQuery("#jform_intIdAgenda_ag").val() );
                objDetalle.strCampoDtll = jQuery("#jform_strCampo_dt").val();
                objDetalle.strValorDtll = jQuery("#jform_strValorCampo_dt").val();
                objDetalle.published    = 1;

                //  Agrego un objetivo a la lista de Objetivos
                objLstDetallesAgd.lstDetallesAgd.push(objDetalle);
                agregarFila( objDetalle );
            } else {
                for (var i = 0; i < numReg; i++) {
                    if (objLstDetallesAgd.lstDetallesAgd[i].registroDtll == flagDetalle) {
                        objLstDetallesAgd.lstDetallesAgd[i].strCampoDtll = jQuery('#jform_strCampo_dt').val();
                        objLstDetallesAgd.lstDetallesAgd[i].strValorDtll = jQuery('#jform_strValorCampo_dt').val();

                        actualizarFila( objLstDetallesAgd.lstDetallesAgd[i] );
                    }
                }
            }
            //  limpio el formulario y reinicio la variables
            resetFrmObj();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }

    });
    
    /**
     *  Cancela la edicion de un registro
     */
    jQuery("#btnCancelDtll").click(function() {
        if ( flagDetalle != -1 ) {
            jQuery("#updDtll-" + flagDetalle).html("Editar");
            validarCambios( -1, false );
        } else {
            resetFrmObj();
        }
    });

    /**
     *  Clase que permite la edición de registro de la lista de detalles 
     */
    jQuery('.updDetalle').live('click', function() {
        //  Obtiene el ID del registro
        var idFila = jQuery(this).attr('id');
        var idRegDtll = parseInt(idFila.toString().split('-')[1]);
        if (flagDetalle == -1 ) {
            updDataObj(idRegDtll);
            flagDetalle = idRegDtll;
        } else if (flagDetalle != idRegDtll) {
            jQuery("#updDtll-" + flagDetalle).html("Editar");
            validarCambios(idRegDtll, true);
        }
    });

    /**
     *  Verifica si el objetivo se puede o no eliminar 
     */
    jQuery(".delDetalle").live('click', function() {
        var idFila = jQuery(this).attr('id');
        var idRegDtll = parseInt(idFila.toString().split('-')[1]);
        jConfirm(JSL_CONFIRM_DELETE, JSL_ECORAE, function(resutl) {
            if (resutl) {
                objLstDetallesAgd.lstDetallesAgd[idRegDtll].published = 0;
                if ( flagDetalle == idRegDtll){
                    resetFrmObj();
                }
                delFila(idRegDtll, "#tbLstDetalles");
                if ( !existRegAvalible( objLstDetallesAgd.lstDetallesAgd ) ){
                    jQuery("#srDtll").css("display", "block");
                }
            }   
        });
    });
    
    /**
     *  Lista los detalles de una agenda en la tabla de detalles
     * @returns {undefined}
     */
    function listarDetalles()
    {
        jQuery('#tbLstDetalles > tbody').empty();
        var numDtll = objLstDetallesAgd.lstDetallesAgd.length;
        if ( numDtll > 0 ){
            for (var i=0; i<numDtll; i++){
                agregarFila( objLstDetallesAgd.lstDetallesAgd[i] );
            }
        } else {
            jQuery("#srDtll").css("display", "block");
        }
    }
    
    /**
     *  Agrega una fila en la tabla de los detalles de la agenda
     * @param {type} detalle    Obj detalle
     * @returns {undefined}
     */
    function agregarFila( detalle )
    {
        //  Agrego la fila creada a la tabla
        var fila = makeFila( detalle, 1);
        jQuery('#tbLstDetalles> tbody:last').append(fila);
    }
    
    /**
     *  Limpia y seta las variables utilisadas al momento de crear o editar un registro
     * @returns {undefined}
     */
    function resetFrmObj()
    {
        jQuery("#frmDetalleAgd").css("display", "none");
        jQuery("#imgDetalleAgd").css("display", "block");
        jQuery("#jform_strCampo_dt").attr('value', '');
        jQuery("#jform_strValorCampo_dt").attr('value', '');
        flagDetalle = -1;
    }
    
    /**
     *  Verifica que los campos obligatorios han sido ingresados
     * @returns {Boolean}
     */
    function objValido()
    {
        var result = true;
        if ( jQuery("#jform_strCampo_dt").val() == '' ||
            jQuery("#jform_strValorCampo_dt") .val() == '') {
            result = false;
        }
        return result;
    }
    
    /**
     * Arma la una fila para la tabla de detalles de una agenda
     * @param {type} detalle        objeto con las parametros de un detalle
     * @param {type} op             opcion que controla si es un nuevo registro o no 
     *                              1:agrega 'tr' nueva fila, 0:no agrega nueva fila
     * @returns {String}
     */
    function makeFila( detalle, op)
    {
        var idReg = detalle.registroDtll;
        var fila = '';
        fila += ( op == 1) ? '<tr id="' + idReg + '">': '';
        fila += '     <td align="center">' + detalle.strCampoDtll + '</td>';
        fila += '     <td align="center">' + detalle.strValorDtll + '</td>';
        fila += '     <td align="center" width="15" > <a id="updDtll-' + idReg + '" class="updDetalle" >' + JSL_UPD + '</a> </td > ';
        fila += '     <td align="center" width="15" > <a id="delDtll-' + idReg + '" class="delDetalle" >' + JSL_DEL + '</a> </td>';
        fila += ( op == 1) ? '</tr>': '';
        return fila;
    }

    /**
     *  Actuliza la informacion de una fila en la tabla de detalles de una agenda
     * @param {type} objDetalle       Array con los atributos del objeto
     * @returns {undefined}
     */
    function actualizarFila( objDetalle )
    {
        jQuery('#tbLstDetalles tr').each(function() {
            if (jQuery(this).attr('id') == flagDetalle) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black; background-color: bisque;');
                //  Construyo la Fila
                var fila = makeFila( objDetalle, 0 )
                jQuery(this).html(fila);
            }
        });    
    }

    /**
     *  Caraga la data de un registro de la lista de detalles
     * @param {type} idUpdDtll      Id del registro a ser editado
     * @returns {undefined}
     */
    function updDataObj( idUpdDtll )
    {
        var numReg = objLstDetallesAgd.lstDetallesAgd.length;
        for (var i = 0; i < numReg; i++) {
            if (objLstDetallesAgd.lstDetallesAgd[i].registroDtll == idUpdDtll) {
                jQuery("#updDtll-" + idUpdDtll).html("Editando...");
                jQuery("#frmDetalleAgd").css("display", "block");
                jQuery("#imgDetalleAgd").css("display", "none");
                jQuery("#jform_strCampo_dt").val(objLstDetallesAgd.lstDetallesAgd[i].strCampoDtll);
                jQuery("#jform_strValorCampo_dt").val(objLstDetallesAgd.lstDetallesAgd[i].strValorDtll);
            }
        }
    }

    /**
     *  Controla si se ha modificado la data de un objetivo para guardarlo o no
     * @param {type} idRegDtll      Id del registro de un detalle a ser editado, -1 en el caso de un nuevo registro 
     * @param {type} frm            Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function validarCambios( idRegDtll, frm )
    {   
        if ( confirmUpdReg( flagDetalle ) ) {
            autoSave( idRegDtll, frm );
        } else {
            controlAutoSave( idRegDtll, frm );
        }
    }

    /**
     *  Retorna True en el caso de que los datos de un registro se modificaron
     * caso contrario devuelve False
     * @param {type} idRegDtall      Id de registro del objeto
     * @returns {Boolean}
     */
    function confirmUpdReg( idRegDtall )
    {
        var resultado = false;
        var lstDetalles = objLstDetallesAgd.lstDetallesAgd;
        for (var i = 0; i < lstDetalles.length; i++) {
            if (lstDetalles[i].registroDtll == idRegDtall) {
                resultado = (jQuery("#jform_strCampo_dt").val() != lstDetalles[i].strCampoDtll ||
                            jQuery("#jform_strValorCampo_dt").val() != lstDetalles[i].strValorDtll) ? true : false;
            }
        }
        return resultado;
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     * solo llama a la funcion "controlAutoSave" que reliza los controles de edicion.
     * @param {type} idRegDtll          Id de registro de detalle en el caso de una nueva edicion, si no es NULL
     * @param {type} frm                Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function autoSave( idRegDtll, frm )
    {
        jConfirm(JSL_CONFIRM_UPDATE, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnSaveDtll').trigger('click');
                controlAutoSave( idRegDtll, frm);
            } else {
                controlAutoSave( idRegDtll, frm);
            }
        });
    }

    /**
     *  Realiza las tareas especificas cuando guarda cambios en un registro
     * @param {type} idRegObj       Id de registro del objeto (-1 en el caso de ser un nuevo registro)
     * @param {type} op             opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function controlAutoSave( idRegObj, op)
    {
        if ( flagDetalle != -1) {
            jQuery("#updDtll-" + flagDetalle).html("Editar");
        }
        if (idRegObj != -1 ) {
            updDataObj( idRegObj );
            flagDetalle = idRegObj;
        } else {
            resetFrmObj();
            if ( op ) {
                jQuery("#frmDetalleAgd").css("display", "block");
                jQuery("#imgDetalleAgd").css("display", "none");
            }
        }
    }
    
});
