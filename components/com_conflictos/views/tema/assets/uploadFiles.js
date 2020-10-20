// Lista de ARCHIVOS
lstArchivos = new Array();
lstArchivosAct = new Array();
flagFilesActor = 0;
flagFiles = 0;
jQuery.alerts.okButton = JSL_SI;
jQuery.alerts.cancelButton = JSL_NO;

jQuery(document).ready(function() {
    //  Carga los lista de archivos de un tema
    setLstArchivos();

    jQuery('#cargaArchivos').uploadifive({
        height: 20,
        'line-height': 20,
        width: 100,
        'auto': false,
        'buttonText': 'Documentos',
        'dnd': false,
        'fileSizeLimit': 2048,
        'multi': true,
        'fileTypeExts': '*.*',
        'fileObjName': "filesTema",
        'uploadScript': 'index.php',
        'onSelect': function(queue) {
            flagFiles = queue.count;
        },
        'onAddQueueItem': function(file) {
            var dtaFile = getDataFile(file);
            var oFile = getDataFile(file, 1);
            oTema.lstArchivo.push(dtaFile);
            lstArchivos.push(oFile);
            setLstArchivos();
            if ( jQuery('#uploadifive-cargaArchivos-queue').is(":visible") ){
                jQuery('#uploadifive-cargaArchivos-queue').css("display", "none");
            }
        },
        'onUploadFile': function(file) {
        },
        'onUploadComplete': function(file, data) {
            var res = eval("(" + data + ")");
            flagFiles--;
            switch (true) {
                case (flagFiles == 0 && flagFilesActor == 0):
                    resdirecTo(res);
                    break;
                case (flagFiles == 0 && flagFilesActor > 0):
                    uploadFilesActor(res.id, res.redirecTo);
                    break;
            }
        },
        'onCancel': function() {
            flagFiles--;
        }
    });

    // carga de documentos de un actor
    jQuery('#cargaArchivosActor').uploadifive({
        height: 20,
        'line-height': 20,
        width: 100,
        auto: false,
        buttonText: 'Documentos',
        dnd: false,
        fileSizeLimit: 2048,
        multi: true,
        'fileObjName': "filesActorTema",
        fileTypeExts: '*.*',
        uploadScript: 'index.php',
        'onSelect': function(queue) {
            flagFilesActor = queue.count;
        },
        'onAddQueueItem': function(file) {
            var dtaFile = getDataFileAct(file, 1);
            lstArchivosAct.push(dtaFile);
            addDocActor(getDataFileAct(file), dtaFile.regFilesActor);
            reloadDocActorDetalle();
            if ( jQuery('#uploadifive-cargaArchivosActor-queue').is(":visible") ){
                jQuery('#uploadifive-cargaArchivosActor-queue').css("display", "none");
            }
        },
        'onUploadFile': function(file) {
        },
        'onUploadComplete': function(file, data) {
            var res = eval("(" + data + ")");
            flagFilesActor--;
            if (flagFilesActor == 0) {
                resdirecTo(res);
            }
        },
        'onCancel': function() {
            flagFilesActor--;
        }
    });

    /**
     * 
     * @param {type} file
     * @param {type} op
     * @returns {_L4.getDataFile.data}
     */
    function getDataFile(file, op) {
        var data = {
            flagUp      : true,
            regArchivo  : oTema.lstArchivo.length,
            idTema      : oTema.idTema,
            nameArchivo : file.name,
            published   : 1
        };
        if (typeof (op) != "undefined" && op == 1) {
            data.file = file;
        }
        return data;
    }

    /**
     * 
     * @param {type} file
     * @param {type} op
     * @returns {_L4.getDataFileAct.data}
     */
    function getDataFileAct(file, op) {
        var data = {
            regActDet: regActDeta,
            idActTema: oTema.lstActDeta[regActDeta].idActorDetalle,
            regFile: oTema.lstActDeta[regActDeta].lstArchivosActor.length,
            nameFile: file.name,
            flagUp: true,
            published: 1
        };
        if (typeof (op) != "undefined" && op == 1) {
            data.file = file;
            data.regFilesActor = lstArchivosAct.length;
        }
        return data;
    }

});

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function addRowFileTema(data)
{
    //  Construyo la Fila
    var fila = '';
    fila += '<tr id="' + data.regArchivo + '">';
    fila += '   <td>' + data.nameArchivo + "</td>";
    fila += '   <td align="center" width="20" >';
    fila += (data.flagUp == false) ? 
            '<a href="/libraries/donwloadFile.php?src=' + getDtaSrcFileTema(data.regArchivo) + '">' + JSL_DONWDOC_LABEL + '</a>' :
            '<a href="#" >' + JSL_NO_OPT_LABEL + '</a>';
    fila += '   </td>';
    fila += '   <td align="center" width="20" >';
    fila += '       <a href="#" class="delDocTema">' + JSL_DEL_LABEL + '</a>';
    fila += '   </td>';
    fila += '</tr>';
    //  Agrego la fila creada a la tabla
    jQuery("#tbListFilesTema > tbody").append(fila);
}

/**
 * 
 * @param {type} reg
 * @returns {undefined}
 */
function getDtaSrcFileTema( reg ){
    var data = {tpo: 'ATC', idTema: oTema.idTema, name: oTema.lstArchivo[reg].nameArchivo};
    var result = window.btoa(JSON.stringify(data));
    return result;
}

/**
 * Set la nueva inforacion a los Archivos. 
 * @returns {undefined}
 */
function setLstArchivos() {
    var lista = oTema.lstArchivo;
    jQuery("#tbListFilesTema > tbody").empty();
    if (lista.length > 0) {
        for (var j = 0; j < lista.length; j++) {
            if (lista[j].published == 1) {
                addRowFileTema(lista[j]);
            }
        }
    }
}

/**
 *  Clase que gestiona la eliminacion de archivos del tema
 * @param {type} param1
 * @param {type} param2
 */
jQuery(".delDocTema").live('click', function () {
    var regFile = this.parentNode.parentNode.id;
    jConfirm( JSL_COM_CONFLICTOS_DEL_ARCHIVO_ACTOR, JSL_ECORAE, function(e){
        if (e) {
            if ( !oTema.lstArchivo[regFile].flagUp ) {
                eliminarDocTema(regFile);
            } else {
                delDocTemaList(regFile);
            }
        }
    });
});

/**
 * elimina un archivo del server
 * @param {type} reg
 * @returns {undefined}
 */
function eliminarDocTema( reg ){
    jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action  : 'delArchivoTema',
                option  : 'com_conflictos',
                view    : 'tema',
                tmpl    : 'component',
                format  : 'json',
                owner    : oTema.idTema,
                name    : oTema.lstArchivo[reg].nameArchivo
            },
            error: function(jqXHR, status, error) {
                alert('Gestión de conflictos - Eliminar archivo: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval("(" + data.responseText + ")");
            if (dataInfo) {
                oTema.lstArchivo[reg].published = 0;
            } else {
                jAlert( JSL_TEMA_ERROR_DEL_REG ,JSL_ECORAE );
            }
            setLstArchivos();
        });
}

/**
 *  Eliman un archivo que no esta en el server
 * @param {type} regFile
 * @returns {undefined}
 */
function delDocTemaList( regFile ){
    oTema.lstArchivo[regFile].published = 0;
    if ( lstArchivos.length > 0){
        var file = null;
        for (var i = 0;  i < lstArchivos.length;  i++) {
            if ( lstArchivos[i].regArchivo == regFile ) {
                file = lstArchivos[i].file;
            }
        }
        if (file != null){
            jQuery('#cargaArchivos').uploadifive('cancel', file);
        }
    } 
    setLstArchivos();
}