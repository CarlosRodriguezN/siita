var flagFile = false;

jQuery(document).ready(function() {
    jQuery('#jform_uploadFather').uploadifive({
        'auto': false,
        'buttonText': 'Documentos',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'multi': false,
        'fileTypeExts': '*.*',
        'uploadScript': 'index.php',
        'onSelect': function(queue) {
        },
        'onAddQueueItem': function(file) {
            flagFile = true;
        },
        'onUploadFile': function(file) {
        },
        'onUploadComplete': function(file, data) {
            location.href = 'http://' + window.location.host + '/index.php?option=com_contratos&view=contratos';
        }
    });
});


/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function saveDocumentos(data, task) {
    if (flagFile) {//flagFile cambia en el on complete
        var options = {
            option: "com_contratos",
            controller: "contrato",
            task: "contrato.saveFiles",
            tmpl: "component",
            typeFileUpl: "documents",
            fileObjName: "documents",
            idContrato: data,
            flag: flagFile
        };
        jQuery('#jform_uploadFather').data('uploadifive').settings.formData = options;
        jQuery('#jform_uploadFather').uploadifive('upload');
    } else {
        resdirecToCnt( data, task );
    }
}

function resdirecToCnt( id, task )
{
    switch (task){
        case 'contrato.save':
            location.href = 'http://' + window.location.host + '/index.php?option=com_contratos&view=contrato&layout=edit&intIdContrato_ctr=' + parseInt(id);
        break;
        case 'contrato.saveExit': 
            location.href = 'http://' + window.location.host + '/index.php?option=com_contratos&view=contratos';
        break;
    }
}