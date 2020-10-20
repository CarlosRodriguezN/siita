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
    var registroPoa = parseInt(jQuery("#registroPoa").val());
    var registroObj = parseInt(jQuery("#registroObj").val());

    var lstTmpPoas = window.parent.objLstPoas.lstPoas;
    
    if ( typeof(lstTmpPoas) !== "undefined" && lstTmpPoas.length > 0 && jQuery.isNumeric(registroPoa) && jQuery.isNumeric(registroObj) ) {
        window.parent.objLstPoas.lstPoas[registroPoa].lstObjetivos[registroObj].lstActividades = new Array();
        window.parent.objLstPoas.lstPoas[registroPoa].lstObjetivos[registroObj].lstActividades = tmpLstActividades;
        window.parent.semaforoActividades(registroObj, 2, registroPoa);
    }
}

/**
 * 
 * @returns {undefined}
 */
function setDocArray() {
    var registroPoa = parseInt(jQuery("#registroPoa").val());
    var lstDocByPoa = parent.parent.window.lstPoasDocs[registroPoa];
    
    if ( lstDocByPoa && typeof(lstDocByPoa) !== "undefined" ) {
        parent.parent.window.lstPoasDocs[registroPoa].lstArchivos = new Array();
    } else {
        var idPlan = parent.parent.window.objLstPoas.lstPoas[registroPoa].idPoa;
        parent.parent.window.lstPoasDocs[registroPoa] = { 
                                                            idPln: idPlan, 
                                                            regPln: registroPoa, 
                                                            lstArchivos: new Array() 
                                                        };
    }
    
    var lstArchivos = parent.parent.window.lstPoasDocs[registroPoa].lstArchivos;
    if (lstTmpArchivos.length > 0) {
        for (var j = 0; j < lstTmpArchivos.length; j++) {
            lstArchivos.push(lstTmpArchivos[j]);
        }
    }
}
