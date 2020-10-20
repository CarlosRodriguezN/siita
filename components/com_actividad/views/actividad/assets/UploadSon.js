//lstTmpArchivos = new Array();

jQuery(document).ready(function() {
    jQuery('#uploadSon').uploadifive({
        height          : 15,
        'line-height'   : 15,
        width           : 55,
        auto            : false,
        buttonText      : JSL_UPLOAD_DOC,
        itemTemplate    : '<div></div>',
        dnd             : false,
        fileSizeLimit   : 2048,
        multi           : true,
        fileTypeExts    : '*.*',
        uploadScript    : 'index.php',
        
        onSelect: function(file) {
        },
        onAddQueueItem: function(file) {
            addDocument(file);
        },
        onCancel: function() {
        },
        onUploadFile: function(file) {

        },
        onUploadComplete: function(file, data) {
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
            idObjetivo: actividad.idObjetivo,
            regObjetivo: parseInt(jQuery("#registroObj").val()),
            idActividad: actividad.idActividad,
            registroAct: registroAct,
            regArchivo: actividad.lstArchivosActividad.length,
            nameArchivo: file.name,
            flag: true,
            published: 1,
            file: file
        };
        var dataToSave = {
            idObjetivo: actividad.idObjetivo,
            regObjetivo: parseInt(jQuery("#registroObj").val()),
            idActividad: actividad.idActividad,
            registroAct: registroAct,
            regArchivo: actividad.lstArchivosActividad.length,
            nameArchivo: file.name,
            flag: true,
            published: 1
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
    var name = (dtaFila.nameArchivo) ? dtaFila.nameArchivo : "-----";
    var ancla = "#";
    var row = '';
    row += '<tr id="' + dtaFila.regArchivo + '">';
    row += '    <td>' + name + ' </td>';
    row += '    <td  width="15" align="center" > ';
    if ( !dtaFila.flag && idPlan != 0 ) {
        ancla = 'http://' + window.location.host + '/media/ecorae/docs/poas/' + idPlan + '/objetivos/' + dtaFila.idObjetivo + '/actividades/' + dtaFila.idActividad + '/' + dtaFila.nameArchivo;
        row += '        <a rel="nofollow" href="' + ancla + '" class="downDoc"> ' + JSL_DOWNDOC + '</a>';
    } else {
        row += '        <a rel="nofollow" href="' + ancla + '" class="downDoc"> ' + JSL_NOUPLOAD + '</a>';
    }
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
    for (var j = 0; j < tmpLstActividades.length; j++) {
        if (tmpLstActividades[j].registroAct == registroAct) {
            var lstArchivosAct = tmpLstActividades[j].lstArchivosActividad;
            for (var h = 0; h < lstArchivosAct.length; h++) {
                if ( lstArchivosAct[h] && lstArchivosAct[h].published == 1 ) {
                    addDocumentRow(lstArchivosAct[h]);
                }
            }
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