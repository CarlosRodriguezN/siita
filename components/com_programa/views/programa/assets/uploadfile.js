var banCargaImagen = false;
var banCargaLogo = false;
var banCargaIcono = false;
var url = window.location.href;
var path = url.split('?')[0];

jQuery(document).ready(function() {

    var banImagen = false;
    var banLogo = false;
    var banIcono = false;
    var idPrograma = 0;
    var dataPrograma = new Array();
    var nameFile = null;

    /**
     * Gestiona los evento de los botones de la vista
     * @param {type} task
     * @returns {undefined}
     */
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'programa.save':
            case 'programa.saveSalir':
                if (confirmData()) {
                    guardarDtaFrm(task);
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_ALERT_DTA_GENERAL_NEED + '<br>';
                    msg += JSL_ALERT_ALL_NEED;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
                break;
            case 'programa.delete':
                eliminarPrograma();
                break;
            case 'programa.cancel':
                event.preventDefault();
                history.back();
                break;
            case 'programa.organigrama':
                SqueezeBox.fromElement('index.php?option=com_reporte&view=organigrama&layout=edit&tmpl=component&task=preview', {size: {x: 1024, y: 500}, handler: "iframe"});
                break;
            case 'programa.list':
                location.href = 'http://' + window.location.host + '/index.php?option=com_programa';
                break;
            default:
                Joomla.submitform(task);
                break
        }
        ;

    };

    /**  Inicializando el botón IMAGEN **/
    jQuery('#imgImgUpLoad').uploadifive({
        'auto': false,
        'buttonText': 'Imagen',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'fileType': ['image\/gif', 'image\/jpeg', 'image\/png'],
        'uploadScript': 'index.php',
        'onSelect': function(file) {
            banImagen = true;
        },
        'onUploadFile': function(file) {
            alert('El archivo ' + file.name + ' is being uploaded.');
        },
        'onUploadComplete': function(file, data) {
            banCargaImagen = true;
            if (cargaComplete()) {
                location.href = 'http://' + window.location.host + '/index.php?option=com_programa&view=programas';
            }
        }

    });

    /**  Inicializando el botón LOGO **/
    jQuery('#imgLogoUpload').uploadifive({
        'auto': false,
        'buttonText': 'Logo',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'fileType': ['image\/png'],
        'uploadScript': 'index.php',
        'onSelect': function(file) {
            banLogo = true;
        },
        'onUploadFile': function(file) {
            alert('El archivo ' + file.name + ' is being uploaded.');
        },
        'onUploadComplete': function(file, data) {
            banCargaLogo = true;
            if (cargaComplete())
                location.href = 'http://' + window.location.host + '/index.php?option=com_programa&view=programas';
        }

    });

    /**  Inicializando el botón ICONO **/
    jQuery('#imgIconoUpLoad').uploadifive({
        'auto': false,
        'buttonText': 'Icono',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'fileType': ['image\/png'],
        'uploadScript': 'index.php',
        'onSelect': function(file) {
            banIcono = true;
        },
        'onUploadFile': function(file) {
            alert('El archivo ' + file.name + ' is being uploaded.');
        },
        'onUploadComplete': function(file, data) {
            banCargaIcono = true;
            if (cargaComplete()) {
                location.href = 'http://' + window.location.host + '/index.php?option=com_programa&view=programas';
            }
        }
    });

    /**
     * 
     */
    jQuery('#jform_intIdUGResponsable').live('change', function(event, idResponsable) {
        jQuery('#jform_idResponsable').html('<option value="0">' + COM_LIST_LOAD + '</option>');

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: { option: 'com_programa',
                    view: 'programa',
                    tmpl: 'component',
                    format: 'json',
                    action: 'getResponsablesUG',
                    idUndGestion: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert(COM_PROGRAMA_FUNCIONARIOS_RESPONSABLES + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            if (numRegistros > 0) {
                var items = [];

                var selected = '';

                for (var x = 0; x < numRegistros; x++) {
                    if (typeof (idEntidad) != 'undefined') {
                        selected = (dataInfo[x].id == idEntidad) 
                                    ? 'selected'
                                    : '';
                    } else {
                        selected = (dataInfo[x].id == 0) 
                                    ? 'selected'
                                    : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + ' >' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idResponsable').html(items.join(''));
        });
    });

    /**
     *  Transforma un Array en Objecto de manera Recursiva
     * @param {type} list
     * @returns {unresolved}
     */
    function list2Object(list)
    {
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
     * Retorna la información de responsables
     * @returns {Array}
     */
    function getResponsableData() {
        var jsonResponsable = {
            // CAMPOS DE LA UNIDAD DE GESTION RESPONSABLE
            idUGR: jQuery('#jform_intIdUndGestion').val(),
            fchIniciUGR: jQuery('#jform_fchInicioPeriodoUG').val(),
            // CAMPOS DEL FUNCIONARIO RESPONSABLE
            idResponsable: jQuery('#jform_idResponsable').val(),
            fchIniciRes: jQuery('#jform_fchInicioPeriodoFuncionario').val()}
        return jsonResponsable;
    }

    /**
     * 
     * @returns {Boolean}
     */
    function cargaComplete() {
        var flag = false;
        switch (true) {
            case(banImagen == 0 && banLogo == 0 && banIcono == 0):
                flag = true;
                break;
            case(banImagen == 0 && banLogo == 0 && banIcono == 1):
                if (banCargaIcono)
                    flag = true;
                break;
            case(banImagen == 0 && banLogo == 1 && banIcono == 0):
                if (banCargaLogo)
                    flag = true;
                break;
            case(banImagen == 0 && banLogo == 1 && banIcono == 1):
                if (banCargaIcono && banCargaLogo)
                    flag = true;
                break;
            case(banImagen == 1 && banLogo == 0 && banIcono == 0):
                if (banCargaImagen)
                    flag = true;
                break;
            case(banImagen == 1 && banLogo == 0 && banIcono == 1):
                if (banCargaImagen && banCargaIcono)
                    flag = true;
                break;
            case(banImagen == 1 && banLogo == 1 && banIcono == 0):
                if (banCargaImagen && banCargaLogo)
                    flag = true;
                break;
            case(banImagen == 1 && banLogo == 1 && banIcono == 1):
                if (banCargaImagen && banCargaLogo && banCargaIcono)
                    flag = true;
                break;
        }
        return flag;
    }

    //<editor-fold defaultstate="collapsed" desc="Gestion la data de programa">

    /**
     * 
     * @param {type} task
     * @returns {undefined}
     */
    function guardarDtaFrm(task) {
        jQuery.blockUI({message: jQuery('#msgProgress')});

        oPrograma.idPrg = jQuery('#jform_intCodigo_prg').val();
        oPrograma.nombrePrg = jQuery('#jform_strNombre_prg').val();
        oPrograma.alias = jQuery('#jform_strAlias_prg').val();
        oPrograma.descripcionPrg = jQuery('#jform_strDescripcion_prg').val();
        oPrograma.responsable = getResponsableData();
        oPrograma.lstObjetivos = objLstObjetivo;
        oPrograma.urlTableU = jQuery("#jform_strURLtableU").val();
        oPrograma.articlePrg = jQuery("#articlePrg").val();
        oPrograma.estadoPrg = 1;

        //  Cambio array Beneficiarios a Notacion JSON
        var dtaIndicadores = JSON.stringify(list2Object(objGestionIndicador));

        //  Obtengo URL completa del sitio
        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_programa',
                view: 'programa',
                tmpl: 'component',
                format: 'json',
                action: 'saveData',
                dataFrm: JSON.stringify(oPrograma),
                dtaIndicadores: dtaIndicadores,
                idEstadoEntidad: jQuery('#jform_idEstadoEntidad').val()
            },
            error: function(jqXHR, status, error) {
                alert('Programa - Registro de Programas: ' + error + ' ' + jqXHR + ' ' + status);
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            idPrograma = data.responseText;

            if (!isNaN(parseInt(idPrograma))) {

                if (banImagen || banLogo || banIcono) {
                    switch (true) {
                        case banImagen:
                            //  Carga Imagen
                            var optionsImagen = {method: "POST",
                                option: "com_programa",
                                controller: "programa",
                                task: "programa.saveFiles",
                                tmpl: "component",
                                typeFileUpl: "imagen",
                                fileObjName: "imagen",
                                idPrograma: idPrograma
                            };
                            jQuery('#imgImgUpLoad').data('uploadifive').settings.formData = optionsImagen;
                            jQuery('#imgImgUpLoad').uploadifive('upload');
                        break;

                        case banLogo:
                            var optionslogo = {option: "com_programa",
                                controller: "programa",
                                task: "programa.saveFiles",
                                tmpl: "component",
                                typeFileUpl: "logo",
                                fileObjName: "logo",
                                idPrograma: idPrograma
                            };

                            jQuery('#imgLogoUpload').data('uploadifive').settings.formData = optionslogo;
                            jQuery('#imgLogoUpload').uploadifive('upload');
                        break;

                        case banIcono:
                            var optionsIcono = {option: "com_programa",
                                controller: "programa",
                                task: "programa.saveFiles",
                                tmpl: "component",
                                typeFileUpl: "icono",
                                fileObjName: "icono",
                                idPrograma: idPrograma
                            };

                            jQuery('#imgIconoUpLoad').data('uploadifive').settings.formData = optionsIcono;
                            jQuery('#imgIconoUpLoad').uploadifive('upload');
                        break;

                    }
                } else {
                    switch (task) {
                        case 'programa.save':
                            location.href = 'http://' + window.location.host + '/index.php?option=com_programa&view=programa&layout=edit&intCodigo_prg=' + idPrograma;
                            break;
                        case 'programa.saveSalir':
                            location.href = 'http://' + window.location.host + '/index.php?option=com_programa';
                            break;
                    }
                }

            }

        });
    }

    /**
     *  Gestiona la eliminacion de un programa
     * @returns {undefined}
     */
    function eliminarPrograma() {
        jConfirm("¿Seguro desea eliminar el Programa?", "SIITA - ECORAE", function(r) {
            if (r) {
                if (validateDelete()) {
                    calldelPrg();
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_PROGRAMA_CAN_NO_DEL;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
            }
        });
    }

    /**
     *  Realiza la llamada ajax para eliminar un registro
     * @returns {undefined}
     */
    function calldelPrg() {
        jQuery.blockUI({message: jQuery('#msgProgress')});
        var id = jQuery("#jform_intCodigo_prg").val();
        jQuery.ajax({
            type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {
                option: 'com_programa',
                view: 'programa',
                tmpl: 'component',
                format: 'json',
                action: 'deletePrg',
                id: id
            },
            error: function(jqXHR, status, error) {
                alert('Programa - Eliminar Programa: ' + error + ' ' + jqXHR + ' ' + status);
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var dtaResult = eval("(" + data.responseText + ")");
            if (dtaResult) {
                location.href = 'http://' + window.location.host + '/index.php?option=com_programa';
            } else {
                jQuery.unblockUI();
                jAlert(JSL_PROGRAMA_ERROR_DEL_REG, JSL_ECORAE);
            }
        });
    }

    /**
     * valida si el programa tiene elementos relacionados.
     * @returns {Boolean}
     */
    function validateDelete() {
        var result = true;
        if (lstSubProgramas.length > 0 ||
                objLstObjetivo.lstObjetivos.length > 0 ||
                objGestionIndicador.lstEnfEcorae.length > 0 ||
                objGestionIndicador.lstEnfIgualdad.length > 0 ||
                objGestionIndicador.lstGAP.length > 0 ||
                objGestionIndicador.lstOtrosIndicadores.length > 0) {
            result = false;
        }
        return  result;
    }

    function _confirmData() {
        var result = true;
        if (jQuery("#jform_strNombre_prg").val() == '' ||
                jQuery("#jform_strAlias_prg").val() == '' ||
                jQuery("#jform_idEstadoEntidad :selected").val() == 0 ||
                jQuery("#jform_intIdUndGestion :selected").val() == 0 ||
                jQuery("#jform_fchInicioPeriodoUG").val() == '' ||
                jQuery("#jform_idResponsable :selected").val() == 0 ||
                jQuery("#jform_fchInicioPeriodoFuncionario").val() == '') {
            result = false;
        }
        
        return result;
    }
    
    
    function confirmData() {
        var result  = false;
        
        var nombre      = jQuery( '#jform_strNombre_prg' );
        var idUG        = jQuery( '#jform_intIdUndGestion' );
        var fchInicioUG = jQuery( '#jform_fchInicioPeriodoUG' );
        
        var idUGF       = jQuery( '#jform_intIdUGResponsable' );
        var idFR        = jQuery( '#jform_idResponsable' );
        var fchInicioFR = jQuery( '#jform_fchInicioPeriodoFuncionario' );
        
        if( nombre.val() !== ''
            && idUG.val() !== ''
            && fchInicioUG.val() !== ''
            && parseInt( idUGF.val() ) !== 0
            && parseInt( idFR.val() ) !== 0
            && fchInicioFR.val() !== '' ){
            result = true;
        }else{
            nombre.validarElemento();
            idUG.validarElemento();
            fchInicioUG.validarElemento();
            idUGF.validarElemento();
            idFR.validarElemento();
            fchInicioFR.validarElemento();
        }
        
        return result;
    }
    
    
    
    

    //</editor-fold>

}); // FIN DEL FUNCTION ON READY









