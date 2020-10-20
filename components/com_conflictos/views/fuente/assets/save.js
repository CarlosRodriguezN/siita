jQuery(document).ready(function() {
    loadUnidadTerritorial();
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'fuente.save':
            case 'fuente.saveExit':
                if ( validateForm() ) {
                    var data = getDataGeneralForm();
                    oFuente.setDataGeneral(data);
                    var data2Save = JSON.stringify(list2Object(oFuente));
                    saveData(data2Save, task);
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_ALERT_DTA_GENERAL_NEED + '<br>';
                    msg += JSL_ALERT_ALL_NEED;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
                break;
            case 'fuente.cancel':
                event.preventDefault();
                history.back();
                break;
            case 'fuente.list':
                event.preventDefault();
                location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=fuentes';
                break;
            case 'fuente.delete':
                eliminarFuente();
                break;
            default:
                event.preventDefault();
                break;
                
        }
    };
    
    function validateForm() {
        var result = true;
        if ( jQuery("#jform_intId_tf :selected").val() == 0 ||
                jQuery("#jform_strDescripcion_fte").val() == '' ||
                jQuery("#jform_strObservaciones_fte").val() == '' ||
                jQuery('#jform_undTerr_provicia :selected').val() == 0 ) {
            result = false;
        }
        return result;
    }
    
});

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
            idFuente            : jQuery("#jform_intId_fte").val(),
            nmbFuente           : jQuery("#jform_strDescripcion_fte").val(),
            idTipoFuente        : jQuery("#jform_intId_tf").val(),
            nmbTipoFuente       : jQuery("#jform_intId_tf option:selected").text(),
            descripcion         : jQuery("#jform_strDescripcion_fte").val(),
            observacion         : jQuery("#jform_strObservaciones_fte").val(),
            vigencia            : jQuery("#jform_intVigencia_fte").val(),
            idUnidadTerritorial : oFuente.idUnidadTerritorial,
            published           : 1
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

    /**
     * 
     * @param {type} data2Save
     * @param {type} task
     * @returns {undefined}
     */
    function saveData(data2Save, task) {
        var url = window.location.href;
        var path = url.split('?')[0];
        
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        
        jQuery.ajax({
            type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'saveFuente',
                option: 'com_conflictos',
                view: 'fuente',
                tmpl: 'component',
                format: 'json',
                saveFuente: data2Save
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                jAlert( JSL_FUENTE_ERROR_GESTION_FUENTE + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var idFuente = data.responseText;
            var id = parseInt(idFuente);
            if ( id ) {
                switch (task){
                    case 'fuente.save':
                        location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=fuente&layout=edit&intId_fte=' + idFuente;
                    break;
                    case 'fuente.saveExit': 
                        location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=fuentes';
                    break;
                } 
            } else {
                jQuery.unblockUI();
                jAlert( JSL_FUENTE_ERROR_GUARDAR, JSL_ECORAE);
            }
            
        });
    }
    
    /**
     * 
     * @returns {undefined}
     */
    function eliminarFuente(){
        
        jConfirm( JSL_CONFIRM_DEL_FUENTE_REG, JSL_ECORAE, function(r) {
            if (r) {
                var url = window.location.href;
                var path = url.split('?')[0];
                var id = jQuery('#jform_intId_fte').val();
                
                jQuery.blockUI({ message: jQuery('#msgProgress') });
                jQuery.ajax({
                    type: 'POST',
                    url: path,
                    dataType: 'JSON',
                    data: {
                        action  : 'eliminarFuente',
                        option  : 'com_conflictos',
                        view    : 'fuente',
                        tmpl    : 'component',
                        format  : 'json',
                        id      : id
                    },
                    error: function(jqXHR, status, error) {
                        jAlert( JSL_FUENTE_ERROR_GESTION_FUENTE + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
                        jQuery.unblockUI();
                    }
                }).complete(function(data) {
                    jQuery.unblockUI();
                    var result = eval("(" + data.responseText + ")");
                    if ( !result ) {
                        jAlert( JSL_FUENTE_ERROR_DEL_FUENTE , JSL_ECORAE );
                    } 
                    location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=fuentes';
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
    if (oFuente.unidadTerritorial.idProvincia != 0) {
        recorrerCombo(jQuery("#jform_undTerr_provicia option"), oFuente.unidadTerritorial.idProvincia);
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
