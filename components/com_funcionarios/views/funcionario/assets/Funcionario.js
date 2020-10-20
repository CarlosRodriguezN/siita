// lista de Archivos 
lstPoasDocs = new Array();

jQuery(document).ready(function () {

    loadOpAddUG(JSON.parse(jQuery("#jform_lstOpsAddsUG").val()));
    checkedOpcionesAdd();

    /**
     *  Ejecuta la opcion guardar de un formulario
     * @param {type} task
     * @returns {Boolean}
     */
    Joomla.submitbutton = function (task)
    {
        switch (task) {
            case 'funcionario.registrar':
            case 'funcionario.registrarSalir':
                if (confirmData()) {
                    llamadaAjax(task);
                } else {
                    jAlert(JSL_ALERT_DTA_GENERAL + ', ' + JSL_ALERT_ALL_NEED, JSL_ECORAE);
                }
                break;
            case 'funcionario.eliminar':
                jConfirm(JSL_CONFIRM_DELETE, JSL_ECORAE, function (result) {
                    if (result) {
                        eliminarFnc();
                    }
                });
                break;
            case 'funcionario.cancel':
                event.preventDefault();
                history.back();
                break;
            default:
                Joomla.submitform(task);
                break;
        }

        return false;
    };

    /**
     *  Verifica que la informacion general obligatoria haya sido ingresada
     * @returns {Boolean}
     */
    function _confirmData()
    {
        var result = true;

        var pass = (jQuery('#jform_password').val() != ""
                && (jQuery('#jform_password').val() == jQuery('#jform_passwordConfirm').val())
                ) ? true
                : false;

        if (jQuery('#jform_strCI_fnc').val() == '' ||
                jQuery('#jform_strApellido_fnc').val() == '' ||
                jQuery('#jform_strNombre_fnc').val() == '' ||
                jQuery('#jform_strCorreoElectronico_fnc').val() == '' ||
                jQuery('#jform_intCodigo_ug').val() == 0 ||
                jQuery('#id="jform_intId_ugc"').val() == 0 ||
                jQuery('#jform_dteFechaInicio_ugf').val() == '' ||
                jQuery('#jform_dteFechaFin_ugf').val() == '' ||
                (jQuery('#jform_intCodigo_fnc').val() == 0 && !pass)) {
            result = false;
        }

        return result;
    }



    function confirmData()
    {
        var result = false;
        var cedula = jQuery('#jform_strCI_fnc');
        var apellido = jQuery('#jform_strApellido_fnc');
        var nombre = jQuery('#jform_strNombre_fnc');
        var correo = jQuery('#jform_strCorreoElectronico_fnc');
        var idUnsGestion = jQuery('#jform_intCodigo_ug');
        var idCargo = jQuery('#jform_intId_ugc');
        var fchInicio = jQuery('#jform_dteFechaInicio_ugf');
        var fchFin = jQuery('#jform_dteFechaFin_ugf');
        var paswd1 = jQuery('#jform_password');
        var paswd2 = jQuery('#jform_passwordConfirm');

        var pass = (paswd1.val() !== ""
                && (paswd1.val() === paswd2.val())) ? true
                : false;

        if (cedula.val() !== ''
                && apellido.val() !== ''
                && nombre.val() !== ''
                && correo.val() !== ''
                && parseInt(idUnsGestion.val()) !== 0
                && parseInt(idCargo.val()) !== 0
                && fchInicio.val() !== ''
                && fchFin.val() !== ''
                && pass === true) {
            result = true;
        } else {
            cedula.validarElemento();
            apellido.validarElemento();
            nombre.validarElemento();
            correo.validarElemento();
            idUnsGestion.validarElemento();
            idCargo.validarElemento();
            fchInicio.validarElemento();
            fchFin.validarElemento();
            idFuncionario.validarElemento();
            paswd1.validarElemento();
        }

        return result;
    }


    /*
     * Llamada Ajax para guardar la data de un Plan Estrategico Institucional
     * 
     * @returns {undefined}
     */
    function llamadaAjax(task) {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaFormulario = JSON.stringify(list2Object(dataFormulario()));
        var dtaUG = JSON.stringify(list2Object(dataUnidadGestion()));
        var dtaLstPoas = JSON.stringify(list2Object(objLstPoas.lstPoas));

        var newReg = (jQuery('#jform_intCodigo_fnc').val() == 0) ? true : false;
        jQuery.blockUI({message: jQuery('#msgProgress')});

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method: "POST",
                option: 'com_funcionarios',
                view: 'funcionario',
                tmpl: 'component',
                format: 'json',
                action: 'guardarFuncionario',
                dtaFrm: dtaFormulario,
                dtaUG: dtaUG,
                lstPoas: dtaLstPoas
            },
            error: function (jqXHR, status, error) {
                jAlert('Funcionarios - Guardad funcionario: ' + error + ' ' + jqXHR + ' ' + status, 'SIITA -  ECORAE');
                jQuery.unblockUI();
            }
        }).complete(function (data) {
            var saveData = eval("(" + data.responseText + ")");
            saveDocumentos(saveData, task, newReg);

        });
    }

    /**
     *  Elimina un funcionario
     * @returns {undefined}
     */
    function eliminarFnc()
    {
        var idFnc = jQuery('#jform_intCodigo_fnc').val();
        if (idFnc != 0) {
            deleteFncAjax(idFnc);
        } else {
            location.href = 'http://' + window.location.host + '/index.php?option=com_funcionarios';
        }
        return true;
    }

    /**
     *  Ejecuta la llamada Ajax para eliminar un funcionario
     * @param {type} id
     * @returns {undefined}
     */
    function deleteFncAjax(id) {
        var funUG = jQuery("#jform_intId_ugf").val();
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery.blockUI({message: jQuery('#msgProgress')});

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method: "POST",
                option: 'com_funcionarios',
                view: 'funcionario',
                tmpl: 'component',
                format: 'json',
                action: 'eliminarFuncionario',
                id: id,
                funUG: funUG
            },
            error: function (jqXHR, status, error) {
                jAlert('Funcionarios - Eliminar funcionario: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE);
                jQuery.unblockUI();
            }
        }).complete(function (data) {
            var saveData = eval("(" + data.responseText + ")");
            if (saveData == 0) {
                jAlert(JSL_ERROR_DEL, JSL_ECORAE);
            }
            location.href = 'http://' + window.location.host + '/index.php?option=com_funcionarios';
        });
    }

    /**
     *  Arma la data general de un PEI
     * 
     * @returns {Array}
     */
    function dataFormulario()
    {
        dtaFrm = new Array();

        dtaFrm["intCodigo_fnc"] = jQuery('#jform_intCodigo_fnc').val();
        dtaFrm["intIdentidad_ent"] = jQuery('#jform_intIdentidad_ent').val();
        dtaFrm["intIdUser_fnc"] = jQuery('#jform_intIdUser_fnc').val();
        dtaFrm["strCI_fnc"] = jQuery('#jform_strCI_fnc').val();
        dtaFrm["strApellido_fnc"] = jQuery('#jform_strApellido_fnc').val();
        dtaFrm["strNombre_fnc"] = jQuery('#jform_strNombre_fnc').val();
        dtaFrm["strCorreoElectronico_fnc"] = jQuery('#jform_strCorreoElectronico_fnc').val();
        dtaFrm["strTelefono_fnc"] = jQuery('#jform_strTelefono_fnc').val();
        dtaFrm["strCelular_fnc"] = jQuery('#jform_strCelular_fnc').val();
        dtaFrm["password"] = (jQuery('#jform_password').val() == jQuery('#jform_passwordConfirm').val()) ? jQuery('#jform_password').val() : '';
        dtaFrm["published"] = 1;

        return dtaFrm;
    }

    /**
     *  Arma la data general de un PEI
     * 
     * @returns {Array}
     */
    function dataUnidadGestion()
    {
        dtaUnidadGestion = new Array();

        dtaUnidadGestion["intId_ugf"] = jQuery('#jform_intId_ugf').val();
        dtaUnidadGestion["intCodigo_ug"] = jQuery('#jform_intCodigo_ug').val();
        dtaUnidadGestion["intId_ugc"] = jQuery('#jform_intId_ugc').val();
        dtaUnidadGestion["dteFechaInicio_ugf"] = jQuery('#jform_dteFechaInicio_ugf').val();
        dtaUnidadGestion["dteFechaFin_ugf"] = jQuery('#jform_dteFechaFin_ugf').val();
        dtaUnidadGestion["lstOpcionesAdd"] = getOpcionesAdd();

        return dtaUnidadGestion;
    }

    /**
     *  Retorna las opciones adicionales seleccionadas para el funcionario
     * @returns {String}
     */
    function getOpcionesAdd()
    {
        var opSelected = '';
        jQuery('#cheksFncOpsAdds input[type=checkbox]').each(function () {
            if (this.checked) {
                opSelected += jQuery(this).val() + ',';
            }
        });
        opSelected = opSelected.substring(0, opSelected.length - 1);
        return opSelected;
    }

    /**
     *  Recorro un determinado combo a una determinada posicion
     * @param {type} combo
     * @param {type} posicion
     * @returns {undefined}
     */
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function () {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        });
    }

    /**
     * Carga los cheks para las opciones adicionales de la unidad de gestion
     */
    jQuery('#jform_intCodigo_ug').change(function () {

        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#cheksFncOpsAdds').remove;

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_funcionarios',
                view: 'funcionario',
                tmpl: 'component',
                format: 'json',
                action: 'getOpAdd',
                idUG: jQuery(this).val()
            },
            error: function (jqXHR, status, error) {
                alert('Funcionarios - Opciones adicionales unidad gesti&oacute;n: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function (data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            jQuery("#jform_lstOpsAddsUG").val(JSON.stringify(dataInfo));

            if (typeof (numRegistros) != "undefined" && numRegistros != null) {
                loadOpAddUG(dataInfo);
            }

            checkedOpcionesAdd();

        });
    });

    /**
     *  Seleciona los checks de las opciones a las que pertenece
     * @returns {undefined}
     */
    function checkedOpcionesAdd()
    {
        var grpFnc = JSON.parse(jQuery("#jform_lstGruposFnc").val());
        if (grpFnc.length > 0) {
            for (var i = 0; i < grpFnc.length; i++) {
                jQuery("#op-" + grpFnc[i].group_id).prop("checked", true);
            }
        }
    }

    /**
     *  Actualiza las opciones adicionales de una unidad de gestion
     * @param {type} lstOpAdd
     * @returns {undefined}
     */
    function loadOpAddUG(lstOpAdd)
    {
        jQuery("#cheksFncOpsAdds > tbody").empty();
        if (lstOpAdd.length > 0) {
            for (var j = 0; j < lstOpAdd.length; j++) {
                var fila = '';
                fila += '<tr>';
                fila += '   <td>';
                fila += '   <input id="op-' + lstOpAdd[j].idGrupo + '" type="checkbox" value="' + lstOpAdd[j].idGrupo + '"> ' + lstOpAdd[j].nombreOpAdd;
                fila += '   </td>';
                fila += '</tr>';
                //  Agrego la fila creada a la tabla
                jQuery('#cheksFncOpsAdds > tbody:last').append(fila);
            }
        } else {
            var fila = '<tr> <td>' + JSL_SIN_REGISTROS + '</td> </tr>';
            jQuery('#cheksFncOpsAdds > tbody:last').append(fila);
        }
    }

    /**
     *  Carga los cargos de un adeterminada unidad de gestion
     */
    jQuery('#jform_intCodigo_ug').change(function (event, idCargo) {

        //  Establese un valor por defecto si no lo tiene para la variable
        idCargo || (idCargo = jQuery("#jform_idCargoUG").val());

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_intId_ugc').html('<option value="0">Cargando...</option>');

        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_funcionarios',
                view: 'funcionario',
                tmpl: 'component',
                format: 'json',
                action: 'getCargosUG',
                idUG: jQuery(this).val()
            },
            error: function (jqXHR, status, error) {
                alert('Funcionarios - Cargo: ' + error + ' ' + jqXHR + ' ' + status);
            }

        }).complete(function (data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">SELECCIONE UN CARGO</option>');
                for (x = 0; x < numRegistros; x++) {

                    var selected = (dataInfo[x].id == idCargo) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_intId_ugc').html(items.join(''));
        });
    });

    /**
     * Dispara el la seleccion del la unidad de gestion y cargo
     */
    jQuery("#jform_intCodigo_ug").trigger("change", jQuery("#jform_idCargoUG").val());

});

/**
 *  Transforma un Array en Objecto de manera Recursiva
 * @param {type} list
 * @returns {list2Object.list}
 */
function list2Object(list)
{
    var obj = {};
    for (key in list) {
        if (typeof (list[key]) == 'object') {
            obj[key] = list2Object(list[key]);
        } else {
            obj[key] = list[key];
        }
    }
    return obj;
}


/**
 * 
 * @param {type} data
 * @param {type} task
 * @returns {undefined}
 */
function saveDocumentos(data, task, newReg) {

    var dtaRedirec = {flag2: true, redirecTo: task};
    //  Verifica si esxiste algun archivo para gestionar
    if (existFileUploadAll()) {
        for (var h = 0; h < lstPoasDocs.length; h++) {
            var flagPlan = (h == (lstPoasDocs.length - 1)) ? true : false;
            if (lstPoasDocs[h] != null && typeof (lstPoasDocs[h]) != "undefined") {
                var lstArchivos = lstPoasDocs[h].lstArchivos;
                var idPlan = data.lstPoas[h].idPoa;
                if (lstArchivos.length > 0) {
                    // Actualiza la informacion de los archivos ( actualiza los id para armar el path )
                    lstArchivos = updateDocsData(data.lstPoas[h], lstArchivos);

                    if (existFileToUpload(h)) {
                        for (var j = 0; j < lstArchivos.length; j++) {
                            var flagArchivo = (j == (lstArchivos.length - 1)) ? true : false;
                            var flag2 = (flagPlan && flagArchivo) ? true : false;
                            if (lstArchivos[j] && lstArchivos[j].flag && lstArchivos[j].published == 1)// 1 archivo nuevo 0 archivo recuperado
                            {
                                var options = {
                                    option: "com_funcionarios",
                                    controller: "funcionario",
                                    task: "funcionario.saveFiles",
                                    tmpl: "component",
                                    typeFileUpl: "documents",
                                    fileObjName: "documents",
                                    idPoa: idPlan,
                                    tipo: 2,
                                    flag2: flag2,
                                    redirecTo: task, // variable que indica a donde redicreccionar luego de completar la carga.
                                    idObjetivo: lstArchivos[j].idObjetivo,
                                    idActividad: lstArchivos[j].idActividad
                                };
                                jQuery('#uploadFather').data("uploadifive").addQueueItem(lstArchivos[j].file);
                                jQuery('#uploadFather').data('uploadifive').settings.formData = options;
                                jQuery('#uploadFather').uploadifive('upload');
                            }
                        }
                    } else if (flagPlan) {
                        resdirecTo(dtaRedirec, data, newReg);
                    }
                }
            }
        }
    } else {
        resdirecTo(dtaRedirec, data, newReg);
    }
}

/**
 * 
 * @param {type} objPlan
 * @param {type} lstFilesOld
 * @returns {undefined}
 */
function updateDocsData(objPlan, lstFilesOld)
{
    var lstObjs = objPlan.lstObjetivos;
    for (var i = 0; i < lstObjs.length; i++) {
        var objetivo = lstObjs[i];
        var lstActs = objetivo.lstActividades;
        for (var h = 0; h < lstActs.length; h++) {
            var actividad = lstActs[h];
            var lstFilesNew = actividad.lstArchivosActividad;
            if (lstFilesNew != null && typeof (lstFilesNew) != "undefined" && lstFilesNew.length > 0) {
                var archivo = lstFilesNew[0];
                for (var m = 0; m < lstFilesOld.length; m++) {
                    var item = lstFilesOld[m];
                    if (item != null && typeof (item) != "undefined") {
                        if (item.regObjetivo == archivo.regObjetivo && item.registroAct == archivo.registroAct) {
                            item.idObjetivo = archivo.idObjetivo;
                            item.idActividad = archivo.idActividad;
                            if (item.file) {
                                item.file.idObjetivo = archivo.idObjetivo;
                                item.file.idActividad = archivo.idActividad;
                            }
                        }
                    }
                }
            }
        }
    }

    return lstFilesOld;
}

/**
 *  Busca si existen archivos para subir de un determinado plan
 * @param {type} regPlan
 * @returns {Boolean}
 */
function existFileToUpload(regPlan) {
    var flag = false;
    var lstArchivos = lstPoasDocs[regPlan].lstArchivos;
    for (var j = 0; j < lstArchivos.length; j++) {
        if (lstArchivos[j] && lstArchivos[j].flag) {
            flag = true;
        }
    }
    return flag;
}

/**
 *  Busca si existen archivos para subir de un determinado plan
 * @returns {Boolean}
 */
function existFileUploadAll() {
    var flag = false;
    for( var j = 0; j < lstPoasDocs.length; j++ ){
        if( typeOf( lstPoasDocs[j] ) !== "null" ){
            var lstArchivos = lstPoasDocs[j].lstArchivos;
            for( var h = 0; h < lstArchivos.length; h++ ){
                if( lstArchivos[h] && lstArchivos[h].flag ) {
                    flag = true;
                    h = lstArchivos.length;
                    j = lstPoasDocs.length;
                }
            }
        }
    }

    return flag;
}


function resdirecTo(dataRedirec, dtaFnc, newReg)
{
    if (dataRedirec.flag2) {
        switch (dataRedirec.redirecTo) {
            case 'funcionario.registrar':
                if (newReg) {
                    location.href = 'http://' + window.location.host + '/index.php?option=com_funcionarios&view=funcionario&layout=edit&intCodigo_fnc=' + dtaFnc.idFnc;
                } else {
                    location.reload();
                }
                break;
            case 'funcionario.registrarSalir':
                location.href = 'http://' + window.location.host + '/index.php?option=com_funcionarios';
                break;
        }

    }
}