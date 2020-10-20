var tmpActividad = null;

jQuery(document).ready(function() {

    jQuery("#saveActiviadad").live("click", function() {
        /////asaqq:
        var data = getActividaForm();

        if (validarActividad(data)) {
            if (tmpActividad == null) {
                data.idActividad = 0;
                data.registroAct = lstTmpActividad.length;
                data.published = 1;
                data.lstArchivosActividad = new Array();
                addActividad(data);
                clcCmpActividad();
                lstTmpActividad.push(data);
            }
            else {
                data.registroAct = registroAct;
                actActividad(data);
                registroAct = 0;
                tmpActividad = null;
            }
            jQuery('#editActividadObjetivoForm').css("display", "none");
            jQuery('#imgActividadObjetivoForm').css("display", "block");
            jQuery('#editDocsActObjForm').css("display", "none");
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE)
            return;
        }
    }
    );
    //  Actualizar
    jQuery(".updActividad").live("click", function() {
        if (tmpActividad != null) {
            var newregistroAct = this.parentNode.parentNode.id;
            autoSaveActividadUpdate(newregistroAct, registroAct);
        } else {
            registroAct = this.parentNode.parentNode.id;
            loadActividadFronArray(registroAct);
            tmpActividad = getActividadByReg(registroAct);
        }
    });
    //  Eliminar una multa
    jQuery(".delActividad").live("click", function() {
        registroActDel = this.parentNode.parentNode.id;
        jConfirm(JSL_CONFIRM_DEL_ACTIVIDAD, JSL_ECORAE, function(r) {
            if (r) {
                elmActividad(registroActDel);
                reloadActividadesTable();
            } else {

            }
        });
    });
    //  Agregar dispositivos
    jQuery(".docActividad").live("click", function() {
        jQuery('#editActividadObjetivoForm').css("display", "none");
        jQuery('#editDocsActObjForm').css("display", "block");
        jQuery('#imgActividadObjetivoForm').css("display", "none");
        registroAct = this.parentNode.parentNode.id;
        reloadDocumentTable(registroAct);
    });
    //  Agregar actividad
    jQuery("#addActividadObjetivo").click(function() {
        jQuery('#imgActividadObjetivoForm').css("display", "none");
        jQuery('#editDocsActObjForm').css("display", "none");
        jQuery('#editActividadObjetivoForm').css("display", "block");
        clcCmpActividad();
        registroAct = 0;
        tmpActividad = null;
    });
    //  Cancelar
    jQuery("#cancelActividad").click(function() {
        jQuery('#editActividadObjetivoForm').css("display", "none");
        jQuery('#editDocsActObjForm').css("display", "none");
        jQuery('#imgActividadObjetivoForm').css("display", "block");
        clcCmpActividad();
        registroAct = 0;
        tmpActividad = null;
    });
    //  Cerrar carga de archivos
    jQuery("#cerrarlUpl").click(function() {
        jQuery('#editActividadObjetivoForm').css("display", "none");
        jQuery('#editDocsActObjForm').css("display", "none");
        jQuery('#imgActividadObjetivoForm').css("display", "block");
        registroAct = 0;
    });
    //  Cerrar carga de archivos
    jQuery(".delDocAct").live('click', function() {
        var registroDocDel = this.parentNode.parentNode.id;
        jConfirm(JSL_CONFIRM_DEL_DOCUMENTO, JSL_ECORAE, function(r) {
            if (r) {
                delArchivo(registroDocDel);
            } else {

            }
        });
    });


});


/**
 * Validadcon que los campos esten completos
 * @param {object} objetivo
 * @returns {Boolean}
 */
function validarActividad(objetivo) {
    var flag = false;
    if (
            objetivo.tipoGestion != 0 &&
            objetivo.idResponsable != 0 &&
            objetivo.descripcion != "" &&
            objetivo.observacion != "" &&
            objetivo.fchRegisto != "" &&
            objetivo.fchActividad != ""
            ) {
        flag = true;
    }
    return flag;
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addActividad(data) {

    var descAc = (data.descripcion) ? data.descripcion : "-----";
    var funNmb = (data.funNombres) ? data.funNombres : "-----";
    var fchAct = (data.fchActividad) ? data.fchActividad : "-----";

    var row = '';
    row += '<tr id="' + data.registroAct + '">';
    row += '    <td>' + descAc + ' </td>';
    row += '    <td>' + funNmb + ' </td>';
    row += '    <td>' + fchAct + ' </td>';
    row += '    <td align="center" width="15" > ';
    row += '        <a class="docActividad"> ' + JSL_DOCUMT + '</a> ';
    row += '    </td>';
    row += '    <td align="center" width="15" > ';
    row += '        <a class="updActividad"> ' + JSL_UPDATE + '</a> ';
    row += '    </td>';
    row += '    <td  width="15" > ';
    row += '        <a class="delActividad"> ' + JSL_DELETE + '</a>';
    row += '    </td>';
    row += '</tr>';
    jQuery('#lstActividadesTable > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clcCmpActividad() {
    jQuery("#jform_intIdResponsable").val("");
    jQuery("#jform_strDescripcion_act").val("");
    jQuery("#jform_strObservacion_tpg").val("");
    jQuery("#jform_fchActividad_tpg").val("");
    recorrerCombo(jQuery("#jform_intIdTipoGestion_tpg option"), 0);
}

/**
 * 
 * @param {type} registroActDel
 * @returns {undefined}
 */
function elmActividad(registroActDel) {
    for (var j = 0; j < lstTmpActividad.length; j++) {
        if (lstTmpActividad[j].registroAct == registroActDel) {
            lstTmpActividad[j].published = 0;
        }
    }
}

/**
 * Actualiza los valores de la actividad.
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actActividad(data) {
    for (var j = 0; j < lstTmpActividad.length; j++) {
        if (lstTmpActividad[j].registroAct == data.registroAct) {
            lstTmpActividad[j].tipoGestion = data.tipoGestion;
            lstTmpActividad[j].idResponsable = data.idResponsable;
            lstTmpActividad[j].descripcion = data.descripcion;
            lstTmpActividad[j].observacion = data.observacion;
            lstTmpActividad[j].fchActividad = data.fchActividad;
            lstTmpActividad[j].funNombres = data.funNombres;
        }
    }
    clcCmpActividad();
    reloadActividadesTable();
}

/**
 *  Lista de Actividades 
 * @returns {undefined}
 */
function reloadActividadesTable() {
    jQuery("#lstActividadesTable > tbody").empty();
    for (var j = 0; j < lstTmpActividad.length; j++) {
        if (lstTmpActividad[j].published == 1)
            addActividad(lstTmpActividad[j]);
    }
}

function setArchivoActividad(registroAct, data) {
    for (var j = 0; j < lstTmpActividad.length; j++) {
        if (lstTmpActividad[j].registroAct == registroAct) {
            lstTmpActividad[j].lstArchivosActividad.push(data);
        }
    }
}

/**
 *  Funcion que permite guardar automaticamente  
 * @param {type} newregistroAct
 * @param {type} lastregistroAct
 * @returns {undefined}
 */
function autoSaveActividadUpdate(newregistroAct, lastregistroAct) {
    var data = getActividaForm();
    if (data.tipoGestion != tmpActividad.tipoGestion ||
            data.idResponsable != tmpActividad.idResponsable ||
            data.descripcion != tmpActividad.descripcion ||
            data.observacion != tmpActividad.observacion ||
            data.fchActividad != tmpActividad.fchActividad
            ) {
        jConfirm(JSL_COM_POA_FIELD_OBJETIVO_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.registroAct = lastregistroAct;
                actActividad(data);
                loadActividadFronArray(newregistroAct);
            } else {
            }
        });
    } else {
        loadActividadFronArray(newregistroAct);
    }
    registroAct = newregistroAct;
}

function getActividadByReg(registroAct) {
    var actividad = false;
    for (var j = 0; j < lstTmpActividad.length; j++) {
        if (lstTmpActividad[j].registroAct == registroAct)
            actividad = lstTmpActividad[j];
    }
    return actividad;
}

function getActividaForm() {
    var data = {
        tipoGestion: jQuery("#jform_intIdTipoGestion_tpg").val(),
        idResponsable: jQuery("#jform_intIdResponsable").val(),
        funNombres: jQuery("#jform_intIdResponsable option:selected").text(),
        descripcion: jQuery("#jform_strDescripcion_act").val(),
        idObjetivo: idObjetivo,
        observacion: jQuery("#jform_strObservacion_tpg").val(),
        fchActividad: jQuery("#jform_fchActividad_tpg").val()
    };

    var oActividad = new Actividad();
    oActividad.setDtaActividad(data);

    return oActividad;
}


function loadActividadFronArray(registroAct) {
    jQuery('#editActividadObjetivoForm').css("display", "block");
    jQuery('#editDocsActObjForm').css("display", "none");
    jQuery('#imgActividadObjetivoForm').css("display", "none");

    var data = getActividadByReg(registroAct);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intIdTipoGestion_tpg option"), data.tipoGestion);
        recorrerCombo(jQuery("#jform_intIdUnidadGestion option"), parseInt(data.undGestion));
        jQuery("#jform_intIdUnidadGestion").trigger("change", data.idResponsable);
        recorrerCombo(jQuery("#jform_intIdResponsable option"), data.idResponsable);


        jQuery("#jform_strDescripcion_act").val(data.descripcion);
        jQuery("#jform_strObservacion_tpg").val(data.observacion);
        jQuery("#jform_fchActividad_tpg").val(data.fchActividad);
    }
    tmpActividad = getActividadByReg(registroAct);
}




function delArchivo(regArchivo) {

    var url = window.location.href;
    var path = url.split('?')[0];

    var archivo = getArchivoByIdReg(regArchivo);
    var dataArchivo = {
        tipo: 2, // indica que es un poa
        idObjetivo: archivo.idObjetivo,
        idActividad: archivo.idActividad,
        nameArchivo: archivo.nameArchivo,
        idPadre: jQuery("#jform_intId_pi", window.parent.document).val()// identificador del padre
    };
    jQuery.ajax({type: 'POST',
        url: path,
        dataType: 'JSON',
        data: {
            option: 'com_poa',
            view: 'poa',
            tmpl: 'component',
            format: 'json',
            action: 'delArchivo',
            infArchivo: dataArchivo
        },
        error: function(jqXHR, status, error) {
            alert('Plan Estrategico Istitucional - Gestión Objetivos: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function(data) {
        jQuery('#lstDocActividades tr').each(function() {
            if (jQuery(this).attr("id") == regArchivo)
                jQuery(this).remove();
        });
        setNullArchivoByIdReg(regArchivo);
    });

}

/**
 * Recupera un archivo dado su identificador de archivo 
 
 * @param {type} regArchivo
 * @returns {unresolved}
 **/
function getArchivoByIdReg(regArchivo) {
    var actividad = getActividadByReg(registroAct);
    var archivo = null;
    for (var j = 0; j < actividad.lstArchivosActividad.length; j++) {
        if (actividad.lstArchivosActividad[j] && actividad.lstArchivosActividad[j].regArchivo == regArchivo) {
            archivo = actividad.lstArchivosActividad[j];
        }
    }
    return archivo;
}

/**
 * Boora los acrivos de las listas
 * @param {type} regArchivo
 * @returns {undefined}
 */
function setNullArchivoByIdReg(regArchivo) {
    var actividad = getActividadByReg(registroAct);
    // eliminado de la lista general
    for (var j = 0; j < actividad.lstArchivosActividad.length; j++) {
        if (actividad.lstArchivosActividad[j] && actividad.lstArchivosActividad[j].regArchivo == regArchivo) {
            delete actividad.lstArchivosActividad[j];
            actividad.lstArchivosActividad.length = actividad.lstArchivosActividad.length - 1;
        }
    }
    //  eliminado de la lista de archiovos
    for (var j = 0; j < lstTmpArchivos.length; j++) {
        if (lstTmpArchivos[j].regArchivo == regArchivo) {
            delete lstTmpArchivos[j];
            lstTmpArchivos.length = lstTmpArchivos.length - 1;
        }
    }
}