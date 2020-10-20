lstTmpArchivos = new Array();

jQuery(document).ready(function() {
    jQuery('#uploadSon').uploadifive({
        'auto': false,
        'buttonText': JSL_UPLOAD_DOC,
        'itemTemplate': '<div></div>',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'multi': true,
        'fileTypeExts': '*.*',
        'uploadScript': 'index.php',
        'onSelect': function(file) {
        },
        'onAddQueueItem': function(file) {
            addDocument(file);
        },
        'onCancel': function() {
        },
        'onUploadFile': function(file) {

        },
        'onUploadComplete': function(file, data) {
        }
    });

});

/**
 *  Agrega un docuemtno a la lista de docuemntos
 * @param {type} file
 * @returns {undefined}
 */
function addDocument(file) {
    if (file) {
        var actividad = getActividadByReg(registroAct);
        var data = {
            idObjetivo: idObjetivo,
            regObjetivo: registroObj,
            idActividad: actividad.idActividad,
            registroAct: registroAct,
            regArchivo: lstTmpArchivos.length,
            flag: true,
            publisehd: 1,
            file: file
        };
        var dataToSave = {
            idObjetivo: idObjetivo,
            regObjetivo: registroObj,
            idActividad: actividad.idActividad,
            registroAct: registroAct,
            regArchivo: lstTmpArchivos.length,
            flag: true,
            publisehd: 1
        };

        lstTmpArchivos.push(data);
        setArchivoActividad(registroAct, dataToSave);
        addDocumentRow(data);
    }
}

/**
 *  Agrega una fila a la lista de documentos
 * @param {type} dtaFila
 * @returns {undefined}
 */
function addDocumentRow(dtaFila) {
    var name = (dtaFila.file) ? dtaFila.file.name : dtaFila.nameArchivo;
    var idEntidadPadre = jQuery("#jform_intId_pi", window.parent.document).val();
    var ancla = "#";
    if (dtaFila.nameArchivo!="undefined" && idEntidadPadre != 0) {
        ancla = 'http://' + window.location.host + '/media/ecorae/docs/poas/' + idEntidadPadre + '/objetivos/' + dtaFila.idObjetivo + '/actividades/' + dtaFila.idActividad + '/' + dtaFila.nameArchivo;
    }
    var row = '';
    row += '<tr id="' + dtaFila.regArchivo + '">';
    row += '    <td>' + name + ' </td>';
    row += '    <td  width="15" > ';
    row += '        <a href="' + ancla + '" class="downDoc"> ' + JSL_DOWNDOC + '</a>';
    row += '    </td>';
    row += '    <td  width="15" > ';
    row += '        <a  class="delDocAct"> ' + JSL_DELETE + '</a>';
    row += '    </td>';
    row += '</tr>';
    jQuery('#lstDocActividades > tbody:last').append(row);
}

/**
 * lista la tabla de documentos
 * @param {type} registroAct
 * @returns {undefined}
 */
function reloadDocumentTable(registroAct) {
    jQuery("#lstDocActividades > tbody").empty();
    for (var j = 0; j < lstTmpArchivos.length; j++) {
        if (lstTmpArchivos[j].registroAct == registroAct) {
            addDocumentRow(lstTmpArchivos[j]);
        }
    }
}

/**
 *  Retorna los ARchivos de una ACTIVIDAD
 * @param {type} idActividad
 * @returns {Array}
 */
function getArchivosActividad(idActividad) {
    var lstArchivosActividad = new Array();
    for (var j = 0; j < lstTmpArchivos.length; j++) {
        if (lstTmpArchivos[j].idActividad == idActividad) {
            lstArchivosActividad.push(lstTmpArchivos[j]);
        }
    }
    return lstArchivosActividad;
}