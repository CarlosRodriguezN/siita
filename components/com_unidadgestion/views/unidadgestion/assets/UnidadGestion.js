// lista de Archivos 
lstArchivos = new Array();

jQuery(document).ready(function() {

    if ( !jQuery("#editarData").val() ){
        bloquerEdicion();
    }

    //  Ejecuta la opcion guardar de un formulario
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            case 'unidadgestion.registro': 
            case 'unidadgestion.registrarSalir':
                if ( validarFormUG() ) {
                    llamadaAjax(task); 
                } else {
                    jAlert( JSL_ALERT_DTA_GENERAL + ', ' + JSL_ALERT_ALL_NEED, JSL_ECORAE );
                }
                break;
            case 'unidadgestion.eliminar':
                jConfirm (JSL_CONFIRM_DELETE, JSL_ECORAE, function(result) {
                    if (result){
                        eliminarUG();
                    }
                });
                break;
            case 'unidadgestion.cancel':
                event.preventDefault();
                history.back();
                break;
                
            case'unidadgestion.organigrama':
                SqueezeBox.fromElement('index.php?option=com_reporte&view=organigrama&layout=edit&tmpl=component&task=preview', {size: {x: 1024, y: 500}, handler: "iframe"});
                break;    
                
            case'unidadgestion.list':
                location.href = 'http://' + window.location.host + '/index.php?option=com_unidadgestion';
                break;    
                
            default: 
                Joomla.submitform(task);
            break;
        }
        return false;
    };

    /*
     * Llamada Ajax para guardar la data de un Plan Estrategico Institucional
     * 
     * @returns {undefined}
     */
    function llamadaAjax(task) {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaFormulario = JSON.stringify( list2Object( dataFormulario() ) );
        var dtaLstPoas = JSON.stringify(list2Object(objLstPoas.lstPoas));
        var dtaLstFuncionarios = JSON.stringify( list2Object( objLstFuncionarios.lstFuncionarios ) );

        var newReg = (jQuery('#jform_intCodigo_ug').val() == 0 ) ? true : false;
        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method: "POST",
                option  : 'com_unidadgestion',
                view    : 'unidadgestion',
                tmpl    : 'component',
                format  : 'json',
                action  : 'guardarUG',
                dtaFrm  : dtaFormulario,
                lstPoas : dtaLstPoas,
                lstFnci : dtaLstFuncionarios
            },
            error: function(jqXHR, status, error) {
                jAlert('Unidades de Gestion - Gestion POA: ' + error + ' ' + jqXHR + ' ' + status, 'SIITA -  ECORAE');
            }
        }).complete(function(data) {
            jQuery.unblockUI();
            var saveData = eval("(" + data.responseText + ")");
            switch (task){
                case 'unidadgestion.registro': 
                    if ( newReg ) {
                        location.href = 'http://' + window.location.host + '/index.php?option=com_unidadgestion&view=unidadgestion&layout=edit&intCodigo_ug=' + saveData.idUG;
                    } else {
                        location.reload();
                    }
                break;
                case 'unidadgestion.registrarSalir':
                    location.href = 'http://' + window.location.host + '/index.php?option=com_unidadgestion';
                break;
            }

        });
    }

    /**
     *  Elimina un unidad de gestion
     * @returns {undefined}
     */
    function eliminarUG()
    {
        var idUG = jQuery('#jform_intCodigo_ug').val();
        if ( idUG != 0 ){
            deleteUGAjax(idUG); 
        } else {
            location.href = 'http://' + window.location.host + '/index.php?option=com_unidadgestion';
        }
        return true;
    }

    /**
     *  Ejecuta la llamada Ajax para eliminar un funcionario
     * @param {type} id
     * @returns {undefined}
     */
    function deleteUGAjax( id ) {
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method: "POST",
                option  : 'com_unidadgestion',
                view    : 'unidadgestion',
                tmpl    : 'component',
                format  : 'json',
                action  : 'eliminarUnidadGestion',
                id      : id
            },
            error: function(jqXHR, status, error) {
                jAlert('Unidad de Gestion - Eliminar Unidad de Gestion: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            if ( saveData == 0) {
                jAlert(JSL_ERROR_DEL, JSL_ECORAE);
            }
            location.href = 'http://' + window.location.host + '/index.php?option=com_unidadgestion';
        });
    }

    /**
     *  Verifica que la informacion general obligatoria haya sido ingresada
     * @returns {Boolean}
     */
    function confirmData()
    {
        var result = true;
        if ( jQuery('#jform_strNombre_ug').val() == '' || jQuery('#jform_strAlias_ug').val() == '' ){
            result = false;
        }
        return result;
    }
    
    
    
    function validarFormUG()
    {
        var ban = false;
        
        var idTpoUG = jQuery( '#jform_intTpoUG_ug' );
        var idUG    = jQuery( '#jform_tb_intCodigo_ug' );
        var nombre  = jQuery( '#jform_strNombre_ug' );
        
        if( jQuery.isNumeric( idTpoUG.val() ) 
            && parseInt( idTpoUG.val() ) > 0
            && jQuery.isNumeric( idUG.val() ) 
            && parseInt( idUG.val() ) > 0
            && nombre.val() !== "" ){

            ban = true;
        }else{
            idTpoUG.validarElemento();
            idUG.validarElemento();
            nombre.validarElemento();
        }

        return ban;
    }

    /**
     *  Arma la data general de un PEI
     * @returns {Array}
     */
    function dataFormulario()
    {
        var dtaFrm = new Array();

        dtaFrm["intCodigo_ug"]      = jQuery('#jform_intCodigo_ug').val();
        dtaFrm["intIdentidad_ent"]  = jQuery('#jform_intIdentidad_ent').val();
        dtaFrm["tb_intCodigo_ug"]   = jQuery('#jform_tb_intCodigo_ug :selected').val();
        dtaFrm["intCodigo_ins"]     = jQuery('#jform_intCodigo_ins').val();
        dtaFrm["strNombre_ug"]      = jQuery('#jform_strNombre_ug').val();
        dtaFrm["strAlias_ug"]       = jQuery('#jform_strAlias_ug').val();
        dtaFrm["intTpoUG_ug"]       = jQuery('#jform_intTpoUG_ug').val();
        dtaFrm["intIdGrupo_ug"]     = jQuery('#jform_intIdGrupo_ug').val();
        dtaFrm["published"]         = 1;

        return dtaFrm;
    }

    /**
     *  Controla que el tipo de objetivo sea valido 
     *  y habilita o desabilita el boton para agregar
     */
    jQuery("#jform_published").change(function() {
        if (jQuery("#jform_published").val() == 0) {
            recorrerCombo(jQuery('#jform_blnVigente_pi option'), 0);
            jQuery('#jform_blnVigente_pi').attr('disabled', 'disabled');
        } else {
            recorrerCombo(jQuery('#jform_blnVigente_pi option'), 1);
            jQuery('#jform_blnVigente_pi').removeAttr('disabled', '');
        }
    });

    /**
     * 
     * @returns {Array}
     */
    function getOpAdicionales()
    {
        var lstOpsAdds = new Array();
        for ( var i=0; i<objLstOpsAdds.lstOpsAdds.length; i++){
            if ( objLstOpsAdds.lstOpsAdds[i].published == 1){
                var dtaOpAdd = new Array();
                dtaOpAdd["nombreOpAdd"]        = objLstOpsAdds.lstOpsAdds[i].nombreOpAdd;
                dtaOpAdd["urlOpAdd"]           = objLstOpsAdds.lstOpsAdds[i].urlOpAdd;
                dtaOpAdd["descripcionOpAdd"]   = objLstOpsAdds.lstOpsAdds[i].descripcionOpAdd;
                lstOpsAdds.push(dtaOpAdd);
            }
        }
        return lstOpsAdds;
    }

    /**
     *  Recorro el combo de provincias a una determinada posicion
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

    function bloquerEdicion()
    {
        jQuery( '#jform_intTpoUG_ug' ).attr('disabled','disabled'); 
        jQuery( '#jform_tb_intCodigo_ug' ).attr('disabled','disabled'); 
        jQuery( '#jform_strNombre_ug' ).attr( 'readonly', 'readonly' ); 
        jQuery( '#jform_strAlias_ug' ).attr( 'readonly', 'readonly' ); 
        jQuery( '#addFuncionarioTable' ).css( 'display', 'none' ); 
        jQuery( '#newPoaUG' ).css( 'display', 'none' ); 
        jQuery( '#poaObjs' ).css( 'display', 'none' ); 
    }


});

/**
 * 
 * @param {type} data
 * @param {type} task
 * @returns {undefined}
 */
function saveDocumentos(data, task) {
    // Actualiza la informacion de los archivo( actualiza los id para armarm el path )
    updateDocsData(data);

    if (existFileToUpload()) {
        for (var j = 0; j < lstArchivos.length; j++) {
            var flag2 = (j == (lstArchivos.length - 1)) ? true : false;
            if (lstArchivos[j] && lstArchivos[j].flag)// 1 archivo nuevo 0 archivo recuperado
            {
                var options = {
                    option: "com_pei",
                    controller: "pei",
                    task: "pei.saveFiles",
                    tmpl: "component",
                    typeFileUpl: "documents",
                    fileObjName: "documents",
                    idPoa: data.idPei,
                    tipo: 1,
                    flag2: flag2,
                    redirecTo: task, // variable que indica a donde redicreccionar luego de complatar la carga.
                    idObjetivo: lstArchivos[j].idObjetivo,
                    idActividad: lstArchivos[j].idActividad
                };
                jQuery('#uploadFather').data("uploadifive").addQueueItem(lstArchivos[j].file);
                jQuery('#uploadFather').data('uploadifive').settings.formData = options;
                jQuery('#uploadFather').uploadifive('upload');
            }
        }
    } else {
        if (task == "pei.registroPei") {
            //  location.href = 'http://' + window.location.host + '/index.php?option=com_pei&view=peis';
        } else {
            var idPei = jQuery('#jform_intId_pi').val();
            //  location.href = 'http://' + window.location.host + '/index.php?option=com_poa&view=poa&layout=edit&intId_pi=' + task + '&idPadre=' + idPei;
        }
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


/**
 *  Transforma un Array en Objecto de manera Recursiva
 * @param {type} list
 * @returns {list2Object.list}
 */
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
