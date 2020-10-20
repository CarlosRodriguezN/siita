jQuery(document).ready(function() {

    var flagOpAdd = -1;
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;

    loadOpsAdds();
    loadOpAddFnc( objLstOpsAdds.lstOpsAdds );
    loadSunMenuOpAdd(objLstOpsAdds.lstOpsAdds);

    /**
     *  Habilita el formulario de una opcion adicional
     */
    jQuery('#newOpAdd').click(function() {
        //  Si se esta editando un registro, flagOpAdd tiene el id del registro editandose.
        if (flagOpAdd != -1) {
            var id = -1;
            guardarCambios(id, true);
        } else {
            jQuery("#frmOpAdd").css("display", "block");
            jQuery("#imgOpAdd").css("display", "none");
        }
    });

    /**
     *  Guarda la data general de una opcion adicional para la unidad de gestion
     */
    jQuery("#btnAddOpAdd").click(function() {
         if ( objValido() ) {
            var numReg = objLstOpsAdds.lstOpsAdds.length;
            if ( flagOpAdd == -1 ) {
                //  Creo el Objeto Funcionario
                var objOpAdd = new OpcionAdicional();
                objOpAdd.registroOpAdd      = numReg;
                objOpAdd.idGrupo            = 0;
                objOpAdd.nombreOpAdd        = jQuery("#jform_nombreOp").val();
                objOpAdd.urlOpAdd           = jQuery("#jform_urlOp").val();
                objOpAdd.descripcionOpAdd   = jQuery("#jform_descripcionOp").val();
                objOpAdd.published          = 1;

                //  Agrego el objeto a la lista
                objLstOpsAdds.addOpAdd( objOpAdd );
            } else {
                //  Actualiza un registro de la lista
                for (var i = 0; i < numReg; i++) {
                    if (objLstOpsAdds.lstOpsAdds[i].registroOpAdd == flagOpAdd) {
                        objLstOpsAdds.lstOpsAdds[i].nombreOpAdd         = jQuery("#jform_nombreOp").val();
                        objLstOpsAdds.lstOpsAdds[i].urlOpAdd            = jQuery("#jform_urlOp").val();
                        objLstOpsAdds.lstOpsAdds[i].descripcionOpAdd    = jQuery("#jform_descripcionOp").val();
                    }
                }
                flagOpAdd = -1;
            }
            
            saveOpAddAjax( objLstOpsAdds.lstOpsAdds, 0, numReg );
            resetFrmFnci();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }
    });

    /**
     *  Cancela la edicion de un registro de funcionario
     */
    jQuery("#btnCancelOpAdd").click(function() {
        var id = -1;
        if (flagOpAdd != -1) {
            guardarCambios(id, false);
        } else {
            resetFrmFnci();
        }
    });

    /**
     *  Clase que permite la edición de un registro
     */
    jQuery('.updOpAdd').live('click', function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        if (flagOpAdd != -1 && flagOpAdd != idFila) {
            guardarCambios(idFila, true)
        } else {
            jQuery("#updObj-" + flagOpAdd).html("Editar");
            flagOpAdd = idFila;
            updDataRegistro(idFila);
        }
    });

    /**
     *  Verifica si el funcionario se puede o no eliminar 
     */
    jQuery(".delOpAdd").live('click', function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        jConfirm(JSL_CONFIRM_DELETE_FNC, JSL_ECORAE, function(resutl) {
            if (resutl) {
                objLstOpsAdds.lstOpsAdds[idFila].published = 0;
                saveOpAddAjax( objLstOpsAdds.lstOpsAdds, -1, idFila );
                if (flagOpAdd == idFila) {
                    flagOpAdd = -1;
                }
            }
        });
    });

    function saveOpAddAjax( lstOpsAdds, op, idReg )
    {
        var url = window.location.href;
        var path = url.split('?')[0];

        var dtaUG = JSON.stringify( list2Object( dataUG() ) );
        var jsonLstOpsAdds = JSON.stringify( list2Object( lstOpsAdds ) );

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method   : "POST",
                option      : 'com_unidadgestion',
                view        : 'unidadgestion',
                tmpl        : 'component',
                format      : 'json',
                action      : 'guardarOpAdd',
                dtaUG       : dtaUG,
                task        : op,
                idReg       : idReg,
                lstOpsAdds  : jsonLstOpsAdds
            },
            error: function(jqXHR, status, error) {
                jAlert('Unidad de Gestion - Gestion Opciones Adicionales: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            
            //  Asigno la nueva lista de opciones adicionales de la unidad de gestion
            objLstOpsAdds.lstOpsAdds = saveData.lstOpsAdds;
            reloadTableOpAdd();
            
            //  actualizar las opciones adicionales en funcionarios
            loadOpAddFnc(objLstOpsAdds.lstOpsAdds);
            loadSunMenuOpAdd(objLstOpsAdds.lstOpsAdds);
            
            //  Carga los funcionarios de la unidad de gestion
            reloadFuncionarios( saveData.lstFuncionarios );
            
        });
    }

    /**
     *  Actualiza las opciones adicionales en la gestion de funcionarios de la unidad de gestion
     * @param {type} lstOpAdd
     * @returns {undefined}
     */
    function loadOpAddFnc( lstOpAdd )
    {
        jQuery("#cheksFncOpsAdds > tbody").empty();
        if ( lstOpAdd.length > 0 ) {
            for( var j=0; j<lstOpAdd.length; j++){
                var fila = '';
                fila += '<tr>';
                fila += '   <td>';
                fila += '   <input id="op-' + lstOpAdd[j].idGrupo + '" type="checkbox" value="' + lstOpAdd[j].idGrupo + '"> ' + lstOpAdd[j].nombreOpAdd;
                fila += '   </td>';
                fila += '</tr>';
                //  Agrego la fila creada a la tabla
                jQuery('#cheksFncOpsAdds > tbody:last').append(fila);
            }
        }
    }
    
    function loadSunMenuOpAdd( lstOpAdd )
    {
        if ( lstOpAdd.length > 0 ) {
            jQuery("#submenu").html("");
            var html = '';
            for( var j=0; j<lstOpAdd.length; j++){
                if( lstOpAdd[j].disponibleUsr == 1) {
                    html += '<li>';
                    html += '   <a href="' + lstOpAdd[j].urlOpAdd + '" title="' + lstOpAdd[j].descripcionOpAdd + '"> ' + lstOpAdd[j].nombreOpAdd + ' </a>';
                    html += '</li>';
                }
            }
            jQuery('#submenu').html(html);
            
            if( jQuery('#submenu-box').is(":hidden") && html != '' ){
                jQuery('#submenu-box').show();
            } else if ( html == '') {
                jQuery('#submenu-box').hide();
            }
        }
    }

    /**
     *  Recarga la tabla de la lista de opciones adicionales
     * @returns {undefined}
     */
    function reloadTableOpAdd()
    {
        jQuery("#tbLstOpsAdds > tbody").empty();
        jQuery("#numOpAdd").html(JSL_TAB_OP_ADD + " (" + objLstOpsAdds.lstOpsAdds.length + ")");
        loadOpsAdds();
    }
    
    /**
     *  Carga la lista actualisad ade funcionarios de la unida de gestion
     * @param {type} funcionarios
     * @returns {undefined}
     */
    function reloadFuncionarios( funcionarios )
    {
        var lstFuncionarios = new Array();
        var rows = funcionarios.length;
        if ( rows > 0 ){
            for ( var i=0; i<rows; i++){
                var oFnc = new Funcionario();
                oFnc.setDtaFuncionario(funcionarios[i]);
                lstFuncionarios.push(oFnc);
                objLstFuncionarios.lstFuncionarios = lstFuncionarios;
            }
        }
        jQuery('#tbLstFuncionarios > tbody').empty();
        cargarFuncionarios( objLstFuncionarios.lstFuncionarios );
    }

    /**
     * Retorna la informacion para la gestion de las opciones adicionales
     */
    function dataUG()
    {
        var dtaFrm = new Array();

        dtaFrm["idUG"]      = jQuery('#jform_intCodigo_ug').val();
        dtaFrm["grupoUG"]     = jQuery('#jform_intIdGrupo_ug').val();

        return dtaFrm;
    }


    /**
     *  Carga las opciones adicionales para la unidad de gestion
     * @returns {undefined}
     */
    function loadOpsAdds()
    {
        if ( objLstOpsAdds.lstOpsAdds.length > 0 ){
            for( var j=0; j<objLstOpsAdds.lstOpsAdds.length; j++ ) {
                agregarFila( objLstOpsAdds.lstOpsAdds[j] );
            }
        }
    }

    /**
     *  Verifica que los campos obligatorios han sido ingresados
     * @returns {Boolean}
     */
    function objValido()
    {
        var result = false;
        if ( jQuery("#jform_nombreOp").val() != "" && jQuery("#jform_urlOp").val() != "" ){
            result = true;
        }
        return result;
    }
    
    /**
     *  Agrega una fila en la tabla opciones adicionales
     * @param {type}  objOP     objeto opcion adicional
     * @returns {undefined}
     */
    function agregarFila( objOP )
    {
        var fila = mekeFila(  objOP, 0 );
        //  Agrego la fila creada a la tabla
        jQuery('#tbLstOpsAdds > tbody:last').append(fila);
    }

    /**
     *  Crea una fila para la tabla de opciones adicionales
     * @param {type} obj
     * @param {type} task
     * @returns {String}
     */
    function mekeFila ( obj, task )
    {
        var idReg = obj["registroOpAdd"];
        var fila = '';
        fila += ( task == 0 ) ? '<tr id="' + idReg + '">' : '';
        fila += '     <td align="center">' + obj["nombreOpAdd"] + '</td>';
        fila += '     <td align="center">' + obj["urlOpAdd"] + '</td>';
        fila += '     <td align="center">' + obj["descripcionOpAdd"] + '</td>';
        fila += '     <td align="center" width="15" > <a id="updOpAdd-' + idReg + '" class="updOpAdd" >Editar</a> </td > ';
        fila += '     <td align="center" width="15" > <a id="delOpAdd-' + idReg + '" class="delOpAdd" >Eliminar</a> </td>';
        fila += ( task == 0 ) ? '</tr>' : '';
        return fila;

    }

    /**
     *  Controla si de a modificado la data de un funcionario para guardarlo o no.
     * 
     * @param {type} idReg      Id de registro del funcionario (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function guardarCambios(idReg, op)
    {
        if (confirmUpdFnci(flagOpAdd)) {
            autoSave(idReg, op);
        } else {
            controlAutoSave(idReg, op);
        }
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     *  solo llama a la funcion "controlAutoSave" que reliza lops controles de edicion.
     * 
     * @param {type} idFila     Id de registro  (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function autoSave(idFila, op)
    {
        jConfirm(JSL_CONFIRM_UPD_OBJETIVO, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnAddOpAdd').trigger('click');
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
        jQuery("#updOpAdd-" + flagOpAdd).html("Editar");
        if (idFila != -1) {
            flagOpAdd = idFila;
            updDataRegistro(idFila);
        } else {
            flagOpAdd = -1;
            resetFrmFnci();
            if (op == true) {
                jQuery("#frmOpAdd").css("display", "block");
                jQuery("#imgOpAdd").css("display", "none");
            }
        }
    }


    /**
     *  Limpia y seta las variables utilisadas al momento de crear o editar un Objetivo
     * @returns {undefined}
     */
    function resetFrmFnci()
    {
        jQuery("#frmOpAdd").css("display", "none");
        jQuery("#imgOpAdd").css("display", "block");
        
        jQuery("#jform_nombreOp").val('');
        jQuery("#jform_urlOp").val('');
        jQuery("#jform_descripcionOp").val('');
    }

    /**
     *  Caraga la data de un registro para ser modificada
     * @param {type} idFila
     * @returns {undefined}
     */
    function updDataRegistro( idFila )
    {
        var numReg = objLstOpsAdds.lstOpsAdds.length;
        for (var i = 0; i < numReg; i++) {
            if (objLstOpsAdds.lstOpsAdds[i].registroOpAdd == idFila) {
                jQuery("#updOpAdd-" + idFila).html("Editando...");
                jQuery("#frmOpAdd").css("display", "block");
                jQuery("#imgOpAdd").css("display", "none");
                jQuery('#jform_nombreOp').val(objLstOpsAdds.lstOpsAdds[i].nombreOpAdd);
                jQuery("#jform_urlOp").val(objLstOpsAdds.lstOpsAdds[i].urlOpAdd);
                jQuery("#jform_descripcionOp").val(objLstOpsAdds.lstOpsAdds[i].descripcionOpAdd);
            }
        }
    }

    /**
     *  Retorna True en el caso de que los datos de un funcionario se modificaron
     *  caso contrario devuelve False
     * 
     * @param {type} idFila     Id de registro del funcionario
     * @returns {Boolean}
     */
    function confirmUpdFnci(idFila)
    {
        var resultado = false;
        for (var i = 0; i < objLstOpsAdds.lstOpsAdds.length; i++) {
            if (objLstOpsAdds.lstOpsAdds[i].registroOpAdd == idFila) {
                if (checkUpd(objLstOpsAdds.lstOpsAdds[i]))
                    resultado = true;
            }
        }
        return resultado;
    }
    
    /**
     *  Verifica se se a modificado la data del objeto
     * @param {type} obj
     * @returns {Boolean}
     */
    function checkUpd( obj ) {
        var resp = false;
        if (obj.nombreOpAdd != jQuery("#jform_nombreOp").val() ||
            obj.urlOpAdd != jQuery("#jform_urlOp").val() ||
            obj.descripcionOpAdd != jQuery("#jform_descripcionOp").val() ) {
            resp = true;
        }
            return resp;
    }

});
