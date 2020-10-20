/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    Joomla.submitbutton = function(task)
    {
        if (task == 'actividad.registar') {
            setDocArray();
            setLstActividadesObjetivo();
            setDocArray();
            window.parent.SqueezeBox.close();
        }
        if (task == 'actividad.cancelar') {
            window.parent.SqueezeBox.close();
        }
    };

});

/**
 * 
 * @returns {undefined}
 */
function setLstActividadesObjetivo() {
    for (var j = 0; j < parent.parent.window.objLstObjetivo.lstObjetivos.length; j++) {
        if (parent.parent.window.objLstObjetivo.lstObjetivos[j].registroObj == parseInt(registroObj)) {
            parent.parent.window.objLstObjetivo.lstObjetivos[j].lstActividades = lstTmpActividad;
        }
    }
}

/**
 * 
 * @returns {undefined}
 */
function setDocArray() {
    parent.parent.window.lstArchivos = new Array();
    if (lstTmpArchivos.length > 0) {
        for (var j = 0; j < lstTmpArchivos.length; j++) {
            parent.parent.window.lstArchivos.push(lstTmpArchivos[j]);
        }
    }
}