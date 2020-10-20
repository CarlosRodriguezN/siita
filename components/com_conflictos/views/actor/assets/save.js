jQuery(document).ready(function() {
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'actor.save':
            case 'actor.saveExit':
                var data = getDataGeneralForm();
                if ( validateData(data) ) {
                    oActor.setDataGeneral(data);
                    var data2Save = JSON.stringify(list2Object(oActor));
                    saveData( data2Save, task );
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_ALERT_DTA_GENERAL_NEED + '<br>';
                    msg += JSL_ALERT_ALL_NEED;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
                break;
            case 'actor.delete':
                eliminarActor();
                break;
            case 'actor.list':
                event.preventDefault();
                location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=actores';
                break;
            case 'actor.cancel':
                event.preventDefault();
                history.back();
                break;
            default:
                Joomla.submitform(task);
                break;
                
        }
    };
});
   
/**.
 *  Valida ue a data general haya sido ingresada
 * @param {type} dta
 * @returns {Boolean}
 */
function validateData(dta){
    var flag = false;
    if (dta.actNombre != "" &&
            dta.actApellido != "" &&
            dta.correo != "" ) {
        flag = true;
    }
    return flag;
}
   
//<editor-fold defaultstate="collapsed" desc="preparando la data para guardar.">
    
    /**
     * Forma un JSON
     * @param {type} list
     * @returns {unresolved}
     */
    function list2Object(list) {
        var obj = {};
        for (key in list) {
            if (typeof(list[key]) == 'object') {
                obj[key] = list2Object(list[key]);
            } else {
                obj[key] = list[key];
            }
        }
        return obj;
    }

    /**
     * Retoran aun array con la informacion general
     * @returns {Array}
     */

    function getDataGeneralForm() {
        var data = {
            idActor: jQuery("#jform_intId_act").val(),
            actNombre: jQuery("#jform_strNombre_act").val(),
            actApellido: jQuery("#jform_strApellido_act").val(),
            correo: jQuery("#jform_strCorreo_act").val(),
            published: 1
        };
        return data;
    }


    /**
     * valida si el programa tiene elementos relacionados.
     * @returns {Boolean}
     */
    function validateDelete() {

        var flag = true;
        for (var j = 0; j < contratos.lstUnidadesTerritoriales.length; j++) {
            if (contratos.lstUnidadesTerritoriales[j].published == 1) {
                flag = false;
            }
        }
        return  flag;
    }
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="funciones con llamadas ajax">

    function saveData(data2Save, task ) {
        var url = window.location.href;
        var path = url.split('?')[0];
        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({
            type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'saveActor',
                option: 'com_conflictos',
                view: 'actor',
                tmpl: 'component',
                format: 'json',
                data: data2Save
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                jAlert( JSL_FUENTE_ERROR_GESTION_ACTOR + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var result = parseInt(data.responseText);
            if ( result ) {
                switch (task){
                    case 'actor.save':
                        location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=actor&layout=edit&intId_act=' + result;
                    break;
                    case 'actor.saveExit': 
                        location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=actores';
                    break;
                } 
            } else {
                jQuery.unblockUI();
                jAlert( JSL_FUENTE_ERROR_GUARDAR, JSL_ECORAE);
            }
        });
    }
    
    function eliminarActor(){
        
        jConfirm( JSL_CONFIRM_DEL_REGISTRO, JSL_ECORAE, function(r) {
            if (r) {
                var url = window.location.href;
                var path = url.split('?')[0];
                var id = jQuery('#jform_intId_act').val();
                
                jQuery.blockUI({ message: jQuery('#msgProgress') });
                jQuery.ajax({
                    type: 'POST',
                    url: path,
                    dataType: 'JSON',
                    data: {
                        action  : 'eliminarActor',
                        option  : 'com_conflictos',
                        view    : 'actor',
                        tmpl    : 'component',
                        format  : 'json',
                        id      : id
                    },
                    error: function(jqXHR, status, error) {
                        jAlert( JSL_FUENTE_ERROR_GESTION_ACTOR + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
                        jQuery.unblockUI();
                    }
                }).complete(function(data) {
                    jQuery.unblockUI();
                    var result = eval("(" + data.responseText + ")");
                    if ( !result ) {
                        jAlert( JSL_FUENTE_ERROR_DEL_ACTOR , JSL_ECORAE );
                    } else {
                        location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=actores';
                    }
                });
            } 
        });
    }
    
//</editor-fold>

/**
 * 
 * @returns {undefined}
 */
function loadUnidadTerritorial() {
    if (oActor.unidadTerritorial.idProvincia != 0) {
        recorrerCombo(jQuery("#jform_undTerr_provicia option"), oActor.unidadTerritorial.idProvincia);
        jQuery("#jform_undTerr_provicia option").trigger("change");
    }

}

/**
 * 
 * @param {type} op
 * @returns {undefined}
 */
function habilitarBtns( op, idInput ){
    if ( op == 1 ){
        jQuery(idInput).removeAttr("disabled");
    } else {
        jQuery(idInput).attr("disabled", "disabled");
    }
}
