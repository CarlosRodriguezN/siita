jQuery(document).ready(function() {

    jQuery(".deleteDoc").live('click', function() {
        //  Emito un mensaje de confirmacion antes de eliminar registro
        var regDocDel = this.parentNode.parentNode.id;
        var url = window.location.href;
        var path = url.split('?')[0];
        jConfirm(ELIMINAR_DOC, SIITA_ECORAE, function(result) {
            if (result) {
                jQuery.ajax({
                    type: 'GET',
                    url: path,
                    dataType: 'JSON',
                    data: {
                        action: 'delDocumento',
                        option: 'com_contratos',
                        view: 'contrato',
                        tmpl: 'component',
                        format: 'json',
                        idContrato: jQuery('#jform_intIdContrato_ctr').val(),
                        nameArchivo: getNameDocumento(regDocDel)
                    },
                    error: function(jqXHR, status, error) {
                        alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
                    }
                }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
                    delDocument(regDocDel);
                });
            }
        });

    });



});
/**
 * 
 * @param {type} regDocumento
 * @returns {String} */
function getNameDocumento(regDocumento) {
    var name = '';
    for (var j = 0; j < lstDocumentos.length; j++) {
        if (lstDocumentos[j].regArchivo == regDocumento)
            name = lstDocumentos[j].nameArchivo;
    }
    return name;
}
/**
 * 
 * @returns {undefined}
 */
function reloadDocumentTable() {
    jQuery("#docsTable > tbody").empty();
    for (var j = 0; j < lstDocumentos.length; j++) {
        if (lstDocumentos[j].published == 1)
            addFilaDocuments(lstDocumentos[j]);
    }
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function addFilaDocuments(data) {
    var idContrato = jQuery('#jform_intIdContrato_ctr').val();
    var aHref = window.location.href + 'media/ecorae/docs/contratos/' + idContrato + '/' + data.nameArchivo;
    var row = '';
    row += '<tr id="' + data.regArchivo + '">';
    row += ' <td align="center">' + data.nameArchivo + '</td>';
    row += ' <td align="center"><a class="modal" href="' + aHref + '">' + JES_ANY_ACCION_VER + ' </a></td>';
    row += ' <td align="center"><a href="' + aHref + '">' + JES_ANY_ACCION_DOWN_LOAD + ' </a></td>';
    row += ' <td align="center" style="width: 15px"><a href="#" class="deleteDoc" >' + JES_ANY_ACCION_DELETE + '</a></td>';
    row += '</tr>';
    jQuery('#docsTable > tbody:last').append(row);
}
/**
 * 
 * @param {int} regDocumento
 * @returns {undefined}
 */
function delDocument(regDocumento) {
    jQuery("#docsTable > tbody").empty();
    for (var j = 0; j < lstDocumentos.length; j++) {
        if (lstDocumentos[j].regArchivo == regDocumento) {
            lstDocumentos[j].published = 0;
        }
    }
    reloadDocumentTable();
}