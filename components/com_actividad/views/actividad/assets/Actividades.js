var tmpActividad = null;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    jQuery("#saveActiviadad").live("click", function() {

        var data = getActividaForm();

        if (validarActividad(data)) {
            if ( jQuery("#tbSinReg").is(":visible") ){
                jQuery("#tbSinReg").css("display", "none");
            }
            if (tmpActividad == null) {
                data.idActividad = 0;
                data.registroAct = tmpLstActividades.length;
                data.published = 1;
                data.lstArchivosActividad = new Array();
                addActividad(data);
                clcCmpActividad();
                tmpLstActividades.push(data);
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
            flagUpdActs = true;
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }
    });

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
                flagUpdActs = true;
            } 
        });
    });

    //  Agregar Archivos
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
                var actividad = getActividadByReg(registroAct);
                var fileAct = getArchivoByIdReg(registroDocDel);
                if ( fileAct != null && typeof (fileAct) != "undefined" ) {
                    if ( fileAct.flag ){
                        removeFileTable( registroDocDel );
                        removeArchivoLst( actividad, registroDocDel);
                    } else {
                        delArchivo(registroDocDel);
                    }
                }
                
//                tmpLstActividades[fileAct.registroAct].lstArchivosActividad[registroDocDel].published = 0 ;
//                for ( var i=0; i<lstTmpArchivos.length; i++){
//                    if (lstTmpArchivos[i].regArchivo == registroDocDel){
//                        lstTmpArchivos[i].published = 0;
//                    }
//                }
//                if ( !fileAct.flag ){
//                    delArchivo(registroDocDel);
//                }

            }
        });
    });
    
    /**
     * Actualiza la grafica de las actividades si es que a exixtido alguna modificacion
     */
    jQuery("#controlUpcAtc").click(function () {
        if ( flagUpdActs ){
            loadChartActividad();
        }
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
            objetivo.descripcion != "" &&
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
    var tpoAct = (data.desTpoGestion) ? data.desTpoGestion : "-----";
    var fchAct = (data.fchActividad) ? data.fchActividad : "-----";

    var row = '';
    row += '<tr id="' + data.registroAct + '">';
    row += '    <td>' + descAc + ' </td>';
    row += '    <td>' + tpoAct + ' </td>';
    row += '    <td>' + fchAct + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += '    <td align="center" width="15" > ';
        row += '        <a class="docActividad"> ' + JSL_DOCUMT + '</a> ';
        row += '    </td>';
        row += '    <td align="center" width="15" > ';
        row += '        <a class="updActividad"> ' + JSL_UPDATE + '</a> ';
        row += '    </td>';
        row += '    <td align="center" width="15" > ';
        row += '        <a class="delActividad"> ' + JSL_DELETE + '</a>';
        row += '    </td>';
        
    }else{
        row += '    <td align="center" width="15" > ';
        row += '        ' + JSL_DOCUMT + '';
        row += '    </td>';
        row += '    <td align="center" width="15" > ';
        row += '        ' + JSL_UPDATE + '';
        row += '    </td>';
        row += '    <td align="center" width="15" > ';
        row += '        ' + JSL_DELETE + '';
        row += '    </td>';
    }
    
    row += '</tr>';

    jQuery('#lstActividadesTable > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clcCmpActividad() {
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
    for (var j = 0; j < tmpLstActividades.length; j++) {
        if (tmpLstActividades[j].registroAct == registroActDel) {
            tmpLstActividades[j].published = 0;
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
    for (var j = 0; j < tmpLstActividades.length; j++) {
        if (tmpLstActividades[j].registroAct == data.registroAct) {
            tmpLstActividades[j].tipoGestion = data.tipoGestion;
            tmpLstActividades[j].descripcion = data.descripcion;
            tmpLstActividades[j].observacion = data.observacion;
            tmpLstActividades[j].fchActividad = data.fchActividad;
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
    if ( getRegValidos() > 0 ){
        jQuery("#tbSinReg").css("display", "none");
        for (var j = 0; j < tmpLstActividades.length; j++) {
            if (tmpLstActividades[j].published == 1)
                addActividad(tmpLstActividades[j]);
        }
    } else {
        jQuery("#tbSinReg").css("display", "block");
    }
    loadChartActividad();
}

/**
 * 
 * @param {type} registroAct
 * @param {type} data
 * @returns {undefined}
 */
function setArchivoActividad(registroAct, data) {
    for (var j = 0; j < tmpLstActividades.length; j++) {
        if (tmpLstActividades[j].registroAct == registroAct) {
            tmpLstActividades[j].lstArchivosActividad.push(data);
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
            data.descripcion != tmpActividad.descripcion ||
            data.observacion != tmpActividad.observacion ||
            data.fchActividad != tmpActividad.fchActividad
            ) {
        jConfirm(JSL_COM_PEI_FIELD_OBJETIVO_CNFIRNCHANGES, JSL_ECORAE, function(r) {
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
    for (var j = 0; j < tmpLstActividades.length; j++) {
        if (tmpLstActividades[j].registroAct == registroAct)
            actividad = tmpLstActividades[j];
    }
    return actividad;
}

function getActividaForm() {
    //  Id del funcionario responsable relacionado a una unidad de gestion
    var idFncUG = jQuery("#jform_intId_ugf", window.parent.document).val();
    var nombre = jQuery("#jform_strApellido_fnc", window.parent.document).val() + " " + jQuery("#jform_strNombre_fnc", window.parent.document).val();
    var data = {
        tipoGestion: jQuery("#jform_intIdTipoGestion_tpg :selected").val(),
        desTpoGestion: jQuery("#jform_intIdTipoGestion_tpg :selected").text(),
        idResponsable: idFncUG,
        funNombres: nombre,
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
        tipo: 2, // indica que es un Plan de tipo POA
        idObjetivo: archivo.idObjetivo,
        idActividad: archivo.idActividad,
        nameArchivo: archivo.nameArchivo,
        idPadre: idPlan// identificador del padre
    };
    jQuery.ajax({type: 'POST',
        url: path,
        dataType: 'JSON',
        data: {
            option: 'com_funcionarios',
            view: 'funcionario',
            tmpl: 'component',
            format: 'json',
            action: 'delArchivo',
            infArchivo: dataArchivo
        },
        error: function(jqXHR, status, error) {
            alert('Eliminar documento - Gestión de actividades: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function(data) {
        var saveData = eval("(" + data.responseText + ")");
        if ( saveData ) {
            var actividad = getActividadByReg(registroAct);
            removeArchivoLst( actividad, regArchivo);
            removeFileTable( regArchivo );
            setNullArchivoByIdReg(regArchivo);
        }
    });

}

/**
 *  elimina la fila de la tabla de archivos dada un registro de archivo 
 * @param {type} regArchivo
 * @returns {undefined}
 */
function removeFileTable( regArchivo ) 
{
    jQuery('#lstDocActividades tr').each(function() {
            if (jQuery(this).attr("id") == regArchivo)
                jQuery(this).remove();
        });
}

/**
 *  Elimina un archivo de la lista de archivos d euna actividad
 * @param {type} actividad
 * @param {type} regArchivo
 * @returns {undefined}
 */
function removeArchivoLst( actividad, regArchivo)
{
    for (var j = 0; j < actividad.lstArchivosActividad.length; j++) {
        if (actividad.lstArchivosActividad[j] && actividad.lstArchivosActividad[j].regArchivo == regArchivo) {
            actividad.lstArchivosActividad.splice(j,1); 
        }
    }
}

/**
 * Recupera un archivo dado su identificador de archivo 
 *
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
 *  Borra el archivo de la lista de archivos para ses guardados
 * @param {type} regArchivo
 * @returns {undefined}
 */
function setNullArchivoByIdReg(regArchivo) {
//    var actividad = getActividadByReg(registroAct);
//    // eliminado de la lista general
//    for (var j = 0; j < actividad.lstArchivosActividad.length; j++) {
//        if (actividad.lstArchivosActividad[j] && actividad.lstArchivosActividad[j].regArchivo == regArchivo) {
//            delete actividad.lstArchivosActividad[j];
//            actividad.lstArchivosActividad.length = actividad.lstArchivosActividad.length - 1;
//        }
//    }
    //  eliminado de la lista de archiovos
    for (var j = 0; j < lstTmpArchivos.length; j++) {
        if (lstTmpArchivos[j].regArchivo == regArchivo) {
            delete lstTmpArchivos[j];
            lstTmpArchivos.length = lstTmpArchivos.length - 1;
        }
    }
}