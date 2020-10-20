// Lista de ARCHIVOS
lstArchivos = new Array();


jQuery(document).ready(function() {
    setLstArchivos();

    jQuery('#uploadFather').uploadifive({
        'auto': false,
        'buttonText'    : 'Documentos',
        'dnd'           : false,
        'fileSizeLimit' : 2048,
        'width'         : 150,
        'multi'         : true,
        'fileTypeExts'  : '*.*',
        'uploadScript'  : 'index.php',

        'onSelect': function(queue) {
        },
        'onAddQueueItem': function(file) {
        },
        'onUploadFile': function(file) {
        },
        'onUploadComplete': function(file, data) {
            var res = eval("(" + data + ")");
            if (res.flag2 == "true") {
                if (res.redirecTo == "pei.registroPei") {
                    location.href = 'http://' + window.location.host + '/index.php?option=com_pei&view=peis';
                } else {
                    var idPei = jQuery('#jform_intId_pi').val();
                    
                    alert( 'http://' + window.location.host + '/index.php?option=com_poa&view=poa&layout=edit&intId_pi=' + data.redirecTo + '&idPadre=' + idPei );
                    
                    //  location.href = 'http://' + window.location.host + '/index.php?option=com_poa&view=poa&layout=edit&intId_pi=' + data.redirecTo + '&idPadre=' + idPei;
                }
            }
        }
    });
});

/**
 * Set la nueva inforacion a los Archivos. 
 * @returns {undefined}
 */
function setLstArchivos() {
    if (objLstObjetivo.lstObjetivos.length > 0) {
        var lstObj = objLstObjetivo.lstObjetivos;
        for (var j = 0; j < lstObj.length; j++) {
            var objetivo = lstObj[j];
            if (objetivo.lstActividades != null) {
                for (var k = 0; k < objetivo.lstActividades.length; k++) {
                    var actividad = objetivo.lstActividades[k];
                    if (actividad.lstArchivosActividad != null) {
                        for (var l = 0; l < actividad.lstArchivosActividad.length; l++) {
                            var archivo = actividad.lstArchivosActividad[l];
                            lstArchivos.push(archivo);
                        }
                    }
                }
            }
        }
    }
}