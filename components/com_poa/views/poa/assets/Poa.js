jQuery(document).ready(function() {

    //
    //  Ejecuta la opcion guardar de un formulario
    //
    Joomla.submitbutton = function(task)
    {
        if (task == 'poa.registroPoa') {
            var url = window.location.href;
            var path = url.split('?')[0];
            
            var dtaFormulario = JSON.stringify(list2Object(dataFormulario()));
            var lstObj = JSON.stringify(list2Object(objLstObjetivo.lstObjetivos));

            jQuery.ajax({type: 'POST',
                url: path,
                dataType: 'JSON',
                data: {method: "POST",
                    option: 'com_poa',
                    view: 'poa',
                    tmpl: 'component',
                    format: 'json',
                    action: 'guardarPoa',
                    dtaFrm: dtaFormulario,
                    lstObjetivos: lstObj
                },
                error: function(jqXHR, status, error) {
                    jAlert('Plan estratégico Institucional - Gestion POA: ' + error + ' ' + jqXHR + ' ' + status, 'SIITA -  ECORAE');
                }
            }).complete(function(data) {
                var res = eval("(" + data.responseText + ")");
                saveDocumentos(res);
                // 
            });
        } else {
            Joomla.submitform(task);
        }
        return false;
    };

    //  
    //  Transforma un Array en Objecto de manera Recursiva
    //
    function list2Object(list)
    {
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

    function dataFormulario()
    {
        var dtaFrm = new Array();

        dtaFrm["intId_pi"] = jQuery('#jform_intId_pi').val();
        dtaFrm["idPadrePei"] = jQuery('#idPadrePoa').val();
        dtaFrm["intId_tpoPlan"] = jQuery('#jform_intId_tpoPlan').val();
        dtaFrm["intCodigo_ins"] = jQuery('#jform_intCodigo_ins').val();
        dtaFrm["strDescripcion_pi"] = jQuery('#jform_strDescripcion_pi').val();
        dtaFrm["dteFechainicio_pi"] = jQuery('#jform_dteFechainicio_pi').val();
        dtaFrm["dteFechafin_pi"] = jQuery('#jform_dteFechafin_pi').val();
        dtaFrm["strAlias_pi"] = jQuery('#jform_strAlias_pi').val();
        dtaFrm["blnVigente_pi"] = jQuery('#jform_blnVigente_pi').val();
        dtaFrm["published"] = jQuery('#jform_published').val();

        return dtaFrm;
    }

});


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
 * 
 * @param {type} data
 * @returns {undefined}
 */
function saveDocumentos(data) {

    updateDocsData(data);

    if (existFileToUpload()) {
        for (var j = 0; j < lstArchivos.length; j++) {
            if (lstArchivos[j] && lstArchivos[j].flag)// 1 archivo nuevo 0 archivo recuperado
            {
                var flag2 = (j == (lstArchivos.length - 1)) ? true : false;
                var options = {
                    option: "com_poa",
                    controller: "poa",
                    task: "poa.saveFiles",
                    tmpl: "component",
                    typeFileUpl: "documents",
                    fileObjName: "documents",
                    idPoa: data.idPoa,
                    tipo: 2,
                    flag2: flag2,
                    idObjetivo: lstArchivos[j].idObjetivo,
                    idActividad: lstArchivos[j].idActividad
//                registroAct: lstArchivos[j].idActividad
                };
                jQuery('#uploadFather').data("uploadifive").addQueueItem(lstArchivos[j].file);
                jQuery('#uploadFather').data('uploadifive').settings.formData = options;
                jQuery('#uploadFather').uploadifive('upload');
            }
        }
    } else {
        location.href = 'http://' + window.location.host + '/index.php?option=com_pei&view=pei&layout=edit&intId_pi=' + jQuery('#idPadrePoa').val();
    }
}


/**
 * 
 * @param {type} data
 * @returns {Array}
 */
function updateDocsData(data) {
    var lstArchivos = new Array();
    if (data.lstObjetivos) {
        var lstObj = data.lstObjetivos;
        for (var j in lstObj) {
            var objetivo = lstObj[j];
            if (objetivo.lstActividades != null) {
                for (var k in objetivo.lstActividades) {
                    var actividad = objetivo.lstActividades[k];
                    if (actividad.lstArchivosActividad != null) {
                        for (var l in actividad.lstArchivosActividad) {
                            var archivo = actividad.lstArchivosActividad[l];
                            archivo.idObjetivo = objetivo.idObjetivo;
                            archivo.idActividad = actividad.idActividad;
                            setDataFile(archivo);
                        }
                    }
                }
            }
        }
    }
    return(lstArchivos);
}



function setDataFile(archivo) {
    var find = false;
    var i = 0;
    while (!find && i < lstArchivos.length) {
        var item = lstArchivos[i];
        if (item.regObjetivo == archivo.regObjetivo && item.registroAct == archivo.registroAct) {
            if (item.file) {
                item.file.idObjetivo = archivo.idObjetivo;
                item.file.idActividad = archivo.idActividad;
                find = true;
            }
        }
        i++;
    }
}



/**
 *  Busca si existen archivos para subir.
 *  
 * @returns {Boolean}
 */
function existFileToUpload() {
    var flag = false;
    for (var j = 0; j < lstArchivos.length; j++) {
        if (lstArchivos[j] && lstArchivos[j].flag) {
            flag = true;
        }
    }
    return flag;
}