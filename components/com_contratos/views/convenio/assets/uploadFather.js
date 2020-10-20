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
            location.href = 'http://' + window.location.host + '/index.php?option=com_contratos&view=convenios';
        }
    });
});


/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function saveDocumentos( data, task ) {
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
        if (task == "convenio.save") {
            location.href = 'http://' + window.location.host + '/index.php?option=com_contratos&view=convenio&layout=edit&intIdContrato_ctr='+ data.trim();
        } else {
            location.href = 'http://' + window.location.host + '/index.php?option=com_contratos&view=convenios';
        }
    }
}
/**
 *  Busca si existen archivos para subir.
 *  
 * @returns {Boolean}
 */
function existFileToUpload() {

}