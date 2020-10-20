var registroObj = 0;
var tmpObjetivo = null;

jQuery(document).ready(function() {

    jQuery('#saveObjetivoPoa').click(function(event) {
        var data = getObjetivoForm();
        if (validarObjetivo(data)) {
            if (tmpObjetivo == null) {
                data.idObjetivo = 0;
                data.idEntidad = 0;
                data.registroObj = lstObjetivos.length + 1;
                data.published = 1;
                data.lstActividades = new Array();
                data.lstAcciones = new Array();
                addObjetivo(data);
                clCmpObjetivo();
                lstObjetivos.push(data);
            }
            else {
                data.registroObj = registroObj;
                actObjetivo(data);
                registroObj = 0;
                tmpObjetivo = null;
            }
            jQuery('#editObjetivoForm').css("display", "none");
            jQuery('#imgObjetivoForm').css("display", "block");
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    // edicion de un objetivo
    jQuery('.updObjetivo').live("click", function() {
        if (tmpObjetivo != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoSaveObjetivoUpdate(newRegObjt, registroObj);
        } else {
            registroObj = this.parentNode.parentNode.id;
            loadObjetivoFronArray(registroObj);
            tmpObjetivo = getObjetivoByReg(registroObj);
        }

    });
    /// eliminar una multa
    jQuery('.delObjPoa').live("click", function() {
        registroObjDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_OBJETIVO, JSL_ECORAE, function(r) {
            if (r) {
                elmObjetivo(registroObjDel);
                reloadObjetivosTable();
            } else {

            }
        });
    });
    // addObe
    jQuery("#addObjetivoPoa").click(function() {
        jQuery('#imgObjetivoForm').css("display", "none");
        jQuery('#editObjetivoForm').css("display", "block")
        clCmpObjetivo();
        registroObj = 0;
        tmpObjetivo = null;
    });
    // cancelar
    jQuery("#cancelObjetivoPoa").click(function() {
        jQuery('#editObjetivoForm').css("display", "none");
        jQuery('#imgObjetivoForm').css("display", "block");
        clCmpObjetivo();
        registroObj = 0;
        tmpObjetivo = null;
    });
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} newRegObjt         Registro del objetivo que se va a editar
 * @param {int} lastRegObjetivo    Registro del objetivo que se esta editando
 * @returns {int}                  Registro del objetivo que se va a editar
 */
function autoSaveObjetivoUpdate(newRegObjt, lastRegObjetivo) {
    var data = getObjetivoForm();
    if (data.idTpoObj != tmpObjetivo.idTpoObj ||
            data.idPadreObj != tmpObjetivo.idPadreObj ||
            data.idPrioridadObj != tmpObjetivo.idPrioridadObj ||
            data.nmbPrioridadObj != tmpObjetivo.nmbPrioridadObj ||
            data.descObjetivo != tmpObjetivo.descObjetivo             //data.fchRegistroObj != tmpObjetivo.fchRegistroObj
            ) {
        jConfirm(JSL_COM_PEI_FIELD_OBJETIVO_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.registroObj = lastRegObjetivo;
                actObjetivo(data);
                loadObjetivoFronArray(newRegObjt);
            } else {
            }
        });
    } else {
        loadObjetivoFronArray(newRegObjt);
    }
    registroObj = newRegObjt;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} objetivo
 * @returns {Boolean}
 */
function validarObjetivo(objetivo) {
    var flag = false;
    if (
            objetivo.idTpoObj != 0 &&
            objetivo.idPadreObj != 0 &&
            objetivo.idPrioridadObj != 0 &&
            objetivo.descObjetivo != ""
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
function addObjetivo(data) {
    var actividades = "onclick='SqueezeBox.fromElement( \"index.php?option=com_poa&view=actividad&layout=edit&intId_ob=" + data.idObjetivo + "&registroObj=" + data.registroObj + "&tmpl=component&task=preview\", {size:{x:1024,y:600}, handler:\"iframe\"} );'"

    var regsObj = data.registroObj;
    var descObj = (data.descObjetivo) ? data.descObjetivo : "-----";
    var nmbPrio = (data.nmbPrioridadObj) ? data.nmbPrioridadObj : "-----";
    var fchRegi = (data.fchRegistroObj) ? data.fchRegistroObj : "-----";
    var fila = '';
    fila += '<tr id="' + regsObj + '">';
    fila += '    <td>' + descObj + ' </td>';
    fila += '    <td>' + nmbPrio + ' </td>';
    fila += '    <td>' + fchRegi + ' </td>';
    fila += '     <td align="center" width="15" >';
    fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_poa&view=plnaccion&layout=edit&registroObj=' + regsObj + '&tmpl=component&task=preview\', {size:{x:1024,y:600}, handler:\'iframe\'} );">';
    fila += '            <img src="/media/com_pei/images/btnObjetivos/PA/pa_rojo_small.png"';
    fila += '        </a>';
    fila += '     </td>';
    fila += '    <td align="center" width="15" > ';
    fila += '        <a  id = "lstActi-' + regsObj + '" class="lstActividades"> ';
    fila += '            <img src="/media/com_pei/images/btnObjetivos/PA/pa_rojo_small.png">';
    fila += '        </a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15" > ';
    fila += '        <a ' + actividades + ' id = "lstIndi-' + regsObj + '" class="lstIndicadores"> ';
    fila += '            <img src="/media/com_pei/images/btnObjetivos/PA/pa_rojo_small.png">';
    fila += '         </a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15" > ';
    fila += '        <a class="updObjetivo"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td  width="15" > ';
    fila += '        <a id = "delObj-' + regsObj + '" class="delObjPoa"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#lstObjetivos > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpObjetivo() {
    recorrerCombo(jQuery("#jform_intIdPadre_ob option"), 0);
    recorrerCombo(jQuery("#jform_intPrioridad_ob option"), 0);
    recorrerCombo(jQuery("#jform_intId_tpoObj option"), 0);
    jQuery("#jform_strDescripcion_ob").val('');
}

/**
 *  Elimina los objetivos.
 * @param {type} registroObjDel
 * @returns {undefined}
 */
function elmObjetivo(registroObjDel) {
    for (var j = 0; j < lstObjetivos.length; j++) {
        if (lstObjetivos[j].registroObj == registroObjDel) {
            lstObjetivos[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del objetivo
 * @param {object} data
 * @returns {undefined}
 */
function actObjetivo(data) {
    for (var j = 0; j < lstObjetivos.length; j++) {
        if (lstObjetivos[j].registroObj == data.registroObj) {
            lstObjetivos[j].idTpoObj = data.idTpoObj;
            lstObjetivos[j].idPadreObj = data.idPadreObj;
            lstObjetivos[j].descObjetivo = data.descObjetivo;
            lstObjetivos[j].idPrioridadObj = data.idPrioridadObj;
            lstObjetivos[j].nmbPrioridadObj = data.nmbPrioridadObj;
        }
    }
    clCmpObjetivo();
    reloadObjetivosTable();
}

/**
 *  Borra y buelce a escribir en la tabla objetivos
 * @returns {undefined}
 */
function reloadObjetivosTable() {
    jQuery("#lstObjetivos > tbody").empty();
    for (var j = 0; j < lstObjetivos.length; j++) {
        if (lstObjetivos[j].published == 1)
            addObjetivo(lstObjetivos[j]);
    }
}

/**
 *  Recupera el objetivo dado el registro del objetivo
 * @param {int} registroObj
 * @returns {unresolved}
 */
function getObjetivoByReg(registroObj) {
    var data = null;
    for (var j = 0; j < lstObjetivos.length; j++) {
        if (lstObjetivos[j].registroObj == registroObj) {
            data = lstObjetivos[j];
        }
    }
    return data;
}

/**
 *  Recupera los datos del objetivo
 * @returns {getObjetivoForm.data}
 */
function getObjetivoForm() {
    var data = {
        "descObjetivo": jQuery("#jform_strDescripcion_ob").val(),
        "descTpoObj": jQuery("#jform_intId_tpoObj option:selected").text(),
        "idPadreObj": jQuery("#jform_intIdPadre_ob").val(),
        "idPrioridadObj": jQuery("#jform_intPrioridad_ob").val(),
        "idTpoObj": jQuery("#jform_intId_tpoObj").val(),
        "nmbPrioridadObj": jQuery("#jform_intPrioridad_ob option:selected").text(),
    };
    return data;
}

/**
 * Carga los datos del objetivo recuperando los datos desde el array.
 * @param {int} registroObj     Identificador del objetivo
 * @returns {undefined}
 */
function loadObjetivoFronArray(registroObj) {
    jQuery('#imgObjetivoForm').css("display", "none");
    jQuery('#editObjetivoForm').css("display", "block");

    var data = getObjetivoByReg(registroObj);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intIdPadre_ob option"), data.idPadreObj);
        recorrerCombo(jQuery("#jform_intId_tpoObj option"), data.idTpoObj);
        recorrerCombo(jQuery("#jform_intPrioridad_ob option"), data.idPrioridadObj);
        jQuery("#jform_strDescripcion_ob").val(data.descObjetivo);
    }
    tmpObjetivo = getObjetivoByReg(registroObj);
}