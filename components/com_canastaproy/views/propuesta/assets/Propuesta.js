jQuery(document).ready(function() {

    //
    //  Ejecuta la opcion guardar de un formulario
    //
 jQuery.alerts.okButton = "SI";
 jQuery.alerts.cancelButton = "NO";
    Joomla.submitbutton = function(task)
    {
        switch( task ) {
            case 'propuesta.registrar':
            case 'propuesta.registrarSalir':
                if ( validarDatos() ) {
                    guardarPropuesta( task );
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_ALERT_DTA_GENERAL_NEED + '<br>';
                    msg += JSL_ALERT_ALL_NEED;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
            break;
            case 'propuesta.deletePropuesta':
                if ( availableDelPrp() ) {
                    jConfirm( JSL_CONFIRM_DELETE, JSL_ECORAE, function(e) {
                        if (e) {
                            delPropuesta();
                        }
                    });
                } else {
                    jAlert( JSL_ALERT_CANT_DELETE_REG, JSL_ECORAE);
                }
            break;
            case 'propuesta.list':
                event.preventDefault();
                location.href = 'http://' + window.location.host + '/index.php?option=com_canastaproy';
            break;
            case 'propuesta.cancel':
                event.preventDefault();
                history.back();
            break;
        }
        return true;
    };
    
    function guardarPropuesta( task )
    {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaFormulario = JSON.stringify(list2Object(dataFormulario()));
        var dtaUbiTerritorial = JSON.stringify(list2Object(lstUndTerritorial));
        var dtaUbiGeografica = JSON.stringify(list2Object(lstUbicacionesGeo));
        var dtaAlineacion = JSON.stringify(list2Object(lstAlineacionPropuesta));
        var dtaIndicadores = JSON.stringify(list2Object( objGestionIndicador ) ) ;
        var newReg = (jQuery('#jform_intIdPropuesta_cp').val() == 0 )   ? true : false;
        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({type: 'POST',
                    url: path,
                    dataType: 'JSON',
            data: { method                  : "POST",
                    option                  : 'com_canastaproy',
                    view                    : 'propuesta',
                    tmpl                    : 'component',
                    format                  : 'json',
                    action                  : 'guardarPropuesta',
                    dtaFrm                  : dtaFormulario,
                    dtaUbicacionTerritorial : dtaUbiTerritorial, 
                    dtaUbicacionGeografica  : dtaUbiGeografica,
                    dtaAlineacion           : dtaAlineacion,
                    dtaIndicadores          : dtaIndicadores 
            },
            error: function(jqXHR, status, error) {
                alert('Canasta de Proyectos - Gestion Proyectos: ' + error + ' ' + jqXHR + ' ' + status);
                jQuery.unblockUI();
            }
        }).complete(function( data ) {
            jQuery.unblockUI();
            var saveData = eval("(" + data.responseText + ")");
            switch (task){
                case 'propuesta.registrar': 
                    if ( newReg ) {
                        location.href = 'http://' + window.location.host + '/index.php?option=com_canastaproy&view=propuesta&layout=edit&intIdPropuesta_cp=' + saveData;
                    } else {
                        location.reload();
                    }
                break;
                case 'propuesta.registrarSalir':
                    location.href = 'http://' + window.location.host + '/index.php?option=com_canastaproy';
                break;
            }
        });
    }

    /**
     *  Elimina un registro de la canasta de proyectos
     * @returns {undefined}
     */
    function delPropuesta()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var url = window.location.href;
        var path = url.split('?')[0];
        jQuery.ajax({
            type: 'POST',
            url: path,
            dataType: 'JSON',
            data: { 
                method  : "POST",
                option  : 'com_canastaproy',
                view    : 'propuesta',
                tmpl    : 'component',
                format  : 'json',
                action  : 'eliminarPropuesta',
                id      :jQuery('#jform_intIdPropuesta_cp').val()
            },
            error: function(jqXHR, status, error) {
                alert('Canasta de Proyectos - Gestion Proyectos: ' + error + ' ' + jqXHR + ' ' + status);
                jQuery.unblockUI();
            }
        }).complete(function( data ) {
            var saveData = eval("(" + data.responseText + ")");
            if (saveData) {
                location.href = 'http://' + window.location.host + '/index.php?option=com_canastaproy';
            } else {
                jQuery.unblockUI();
                jAlert( JSL_ALERT_CANT_DELETE, JSL_ECORAE );
            }
        });
    }

    /**
     *  Verifica si existe registros validos relacionados
     * @returns {undefined}
     */
    function availableDelPrp(){
        var result = true;
        if (  getRegPublishedLst(lstUbicacionesGeo) || 
                getRegPublishedLst(lstAlineacionPropuesta) || 
                getRegPublishedLst(lstUndTerritorial)) {
            result = false;
        }
        return result;
    }

    /**
     *  Transforma un Array en Objecto de manera Recursiva
     * @param {type} list
     * @returns {unresolved}
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

    function dataFormulario()
    {
        dtaFrm = new Array();

        dtaFrm["intIdPropuesta_cp"]         = jQuery('#jform_intIdPropuesta_cp').val();
        dtaFrm["intCodigo_ins"]             = jQuery('#jform_intCodigo_ins').val();
        dtaFrm["intIdtipoentidad_te"]       = jQuery('#jform_intIdtipoentidad_te').val();
        dtaFrm["intIdentidad_cp"]           = jQuery('#jform_intIdentidad_ent').val();
        dtaFrm["inpCodigo_estado"]          = jQuery('#jform_inpCodigo_estado').val();
        dtaFrm["strCodigoPropuesta_cp"]     = jQuery('#jform_strCodigoPropuesta_cp').val();
        dtaFrm["strNombre_cp"]              = jQuery('#jform_strNombre_cp').val();
        dtaFrm["dcmMonto_cp"]               = unformatNumber(jQuery('#jform_dcmMonto_cp').val());
        dtaFrm["intNumeroBeneficiarios"]    = jQuery('#jform_intNumeroBeneficiarios').val();
        dtaFrm["strDescripcion_cp"]         = jQuery('#jform_strDescripcion_cp').val();
        
        return dtaFrm;
    }
    
    /**
     *  Valida que los campos obligatorios del formulario estes llenos
     * @returns {Boolean}
     */
    function validarDatos() {
        var result = true;
        if ( jQuery('#jform_intCodigo_ins :selected').val() == 0 ||
             jQuery('#jform_inpCodigo_estado :selected').val() == 0 ||
             jQuery('#jform_strCodigoPropuesta_cp').val() == '' ||
             jQuery('#jform_strNombre_cp').val() == '' ||
             jQuery('#jform_dcmMonto_cp').val() == '' ||
             jQuery('#jform_intNumeroBeneficiarios').val() == '' ) {
            result = false;
            jQuery("#formDataProuesta").submit();
        }
        return result;
    }
    
    
    function getRegPublishedLst( list ) {
        var result = false;
        for (var i = 0; i < list.length; i++) {
            if ( list[i].published ==1 ) {
                result = true;
            }
        }
        return result;
    }
});