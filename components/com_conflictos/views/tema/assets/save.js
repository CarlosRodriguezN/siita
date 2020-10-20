jQuery(document).ready(function() {

    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'tema.save':
            case 'tema.saveExit':
                if (confirmData()) {
                    var data = getDataGeneralForm();
                    oTema.setDataGeneral(data);
                    var data2Save = JSON.stringify(list2Object(oTema));
                    saveData(data2Save, task);
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_ALERT_DTA_GENERAL_NEED + '<br>';
                    msg += JSL_ALERT_ALL_NEED;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
                break;
            case 'tema.cancel':
                event.preventDefault();
                history.back();
                break;
            case 'tema.list':
                event.preventDefault();
                location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos';
                break;
            case 'tema.delete':
                jConfirm( JSL_TEMA_CONFIRM_DEL_TEMA, JSL_ECORAE, function(e) {
                    if (e){
                        deleteTema();
                    } 
                });
                break;
        }
    };
});

//<editor-fold defaultstate="collapsed" desc="preparando la data para guardar">

/**
 * Forma un JSON
 * @param {type} list
 * @returns {unresolved}
 */
function list2Object(list) {
    var obj = {};
    for (key in list) {
        if (typeof (list[key]) == 'object') {
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
        idtipoTema: jQuery("#jform_intId_tt").val(),
        idTema: jQuery("#jform_intId_tma").val(),
        idNivelImpacto: jQuery("#jform_intId_ni").val(),
        titulo: jQuery("#jform_strTitulo_tma").val(),
        resumen: jQuery("#jform_strResumen_tma").val(),
        observaciones: jQuery("#jform_strObservaciones_tma").val(),
        sugerenncias: jQuery("#jform_strSugerencias_tma").val()
    };
    return data;
}

function confirmData() {
    var result = true;
    if (jQuery("#jform_intId_tt :selected").val() == 0 ||
            jQuery("#jform_intId_ni :selected").val() == 0 ||
            jQuery("#jform_strTitulo_tma").val() == '' ||
            jQuery("#jform_strResumen_tma").val() == '') {
        result = false;
    }
    return result;
}

//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="funciones con llamadas ajax">
function saveData(data2Save, task) {
    var url = window.location.href;
    var path = url.split('?')[0];
    jQuery.blockUI({message: jQuery('#msgProgress')});

    jQuery.ajax({
        type: 'POST',
        url: path,
        dataType: 'JSON',
        data: {
            action: 'saveTema',
            option: 'com_conflictos',
            view: 'tema',
            tmpl: 'component',
            format: 'json',
            saveTema: data2Save
        },
        error: function(jqXHR, status, error) {
            jQuery.unblockUI();
            alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
        var dtaTema = eval('(' + data.responseText + ')');
        
        switch (true) {
            case (flagFiles > 0):
                saveFiles(dtaTema.idTema, task);
                break;
            case (flagFilesActor > 0):
                dataReturn = dtaTema;
                uploadFilesActor(dtaTema.idTema, task);
                break;
            default:
                var dtaRedired = { id: dtaTema.idTema, redirecTo: task};
                resdirecTo( dtaRedired );
                break;
        }

    });
}

function deleteTema(){
    var url = window.location.href;
    var path = url.split('?')[0];
    jQuery.blockUI({message: jQuery('#msgProgress')});

    jQuery.ajax({
        type: 'POST',
        url: path,
        dataType: 'JSON',
        data: {
            action: 'deleteTema',
            option: 'com_conflictos',
            view: 'tema',
            tmpl: 'component',
            format: 'json',
            id: oTema.idTema
        },
        error: function(jqXHR, status, error) {
            jQuery.unblockUI();
            alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
        var dtaTema = eval('(' + data.responseText + ')');
        if (dtaTema) {
            location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos';
        } else {
            jQuery.unblockUI();
            jAlert( JSL_TEMA_ERROR_DEL_REG, JSL_ECORAE );
        }
    });
}

//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="gestion de achivos del tema y actores">

/**
 * 
 * @param {type} data
 * @param {type} task
 * @returns {undefined}
 */
function saveFiles(id, task) {
    var opFilesTema = {
        method: "POST",
        option: "com_conflictos",
        controller: "tema",
        task: "tema.registroArchivos",
        tmpl: "component",
        id: id,
        reditecTo: task,
        tpo: 1
    };
    jQuery('#cargaArchivos').data('uploadifive').settings.formData = opFilesTema;
    jQuery('#cargaArchivos').uploadifive('upload');
}

/**
 * 
 * @returns {undefined}
 */
function uploadFilesActor(id, task) {
    var lstActores = dataReturn.lstActDeta;
    for (var i = 0; i < lstActores.length; i++) {
        if (Object.keys(lstActores[i].lstArchivosActor).length > 0) {
            saveFileActor( id, task, lstActores[i] );
        }
    }
}

/**
 * 
 * @param {type} idOwner
 * @param {type} task
 * @param {type} list
 * @returns {undefined}
 */
function saveFileActor(idOwner, task, objActor) {
    
    var list = objActor.lstArchivosActor;
    var opFilesTemaAtc = {
        method: "POST",
        option: "com_conflictos",
        controller: "tema",
        task: "tema.registroArchivos",
        tmpl: "component",
        id: idOwner,
        reditecTo: task,
        tpo: 2
    };
    
    for (var i = 0; i < Object.keys(list).length; i++) {
        if (list[i].flagUp == true){
            opFilesTemaAtc.idActTma = objActor.idActorDetalle;
            var fileAct = lstArchivosAct[ list[i].regFilesActor ].file;
            jQuery('#cargaArchivosActor').data('uploadifive').settings.formData = opFilesTemaAtc;
            jQuery('#cargaArchivosActor').uploadifive( 'upload', fileAct );
        }
    }
    
}

/**
 *  Redirecciona la pagina despues de haber terninado el proceso
 * @param {type} dtaRedirec
 * @param {type} data
 * @returns {undefined}
 */
function resdirecTo(data) {
    if (data.id) {
        switch (data.redirecTo) {
            case 'tema.save':
                location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos&view=tema&layout=edit&intId_tma=' + data.id;
                break;
            case 'tema.saveExit':
                location.href = 'http://' + window.location.host + '/index.php?option=com_conflictos';
                break;
        }

    }
}

//</editor-fold>


/**
 *  Resetea los sms del validate en los input de una determinada parte del formulario 
 * @param {type} form
 * @returns {undefined}
 */
function cleanValidateForn(form)
{
    jQuery("#conflictos-form").submit();

    jQuery(form + " select").each(function() {
        jQuery(this).removeClass("error");
    });

    jQuery(form + " input[type=text]").each(function() {
        jQuery(this).removeClass("error");
    });

    jQuery(form + " textarea").each(function() {
        jQuery(this).removeClass("error");
    });
}

/**
 * 
 * @param {type} combo
 * @param {type} posicion
 * @returns {undefined}
 */
function recorrerCombo(combo, posicion)
{
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}

/**
 *  Elimina valores de un combo determinado
 * @param {type} combo
 * @returns {undefined}
 */
function enCerarCombo(combo)
{
    //  Recorro contenido del combo
    jQuery(combo).each(function() {
        if (jQuery(this).val() > 0) {
            //  Actualizo contenido del combo
            jQuery(this).remove();
        }
    });
}
