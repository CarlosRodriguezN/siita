jQuery( document ).ready( function() {
    
    Joomla.submitbutton = function(task){
        switch (task){
            case "cargofnc.save":
            case "cargofnc.saveExit":
                saveAjax(task); 
                break;
            case "cargofnc.cancel":
                event.preventDefault();
                history.back();
                break;
            default: 
                Joomla.submitform(task);
            break;
        }
    };
    
    /**
     *  Retorna True si los compos obligatorios has sido llenados, si no retorna False
     * @returns {Boolean}
     */
    function confirmData()
    {
        var result = true;
        if ( jQuery('#jform_strNombre_cargo').val() == '' ||
                jQuery('#jform_strDescripcion_cargo').val() == '' ){
            result = false;
        }
        return result;
    }
    
    /**
     *  Realiza la llamada ajjax para guardar la informacion general de un cargo
     * @param {type} task
     * @returns {undefined}
     */
    function saveAjax( task )
    {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaLstCargos = JSON.stringify( list2Object( lstCargos ) );
        
        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option       : 'com_mantenimiento',
                view            : 'cargofnc',
                tmpl            : 'component',
                format          : 'json',
                action          : 'guardarCargos',
                lstCargos       : dtaLstCargos
                },
            error: function(jqXHR, status, error) {
                jAlert('Mantenimiento - Gesti&oacute;n Cargo: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            var newReg = (jQuery("#jform_inpCodigo_cargo").val() == 0 ) ? true : false;
            switch (task){
                case 'cargofnc.save': 
                    if ( newReg ) {
                        jQuery("#jform_inpCodigo_cargo").val(saveData);
                        location.href = 'http://' + window.location.host + '/index.php?option=com_mantenimiento&view=cargofnc&layout=edit&inpCodigo_cargo=' + saveData;
                    } else {
                        location.reload();
                    }
                break;
                case 'cargofnc.saveExit':
                    location.href = 'http://' + window.location.host + '/index.php?option=com_mantenimiento&view=cargosfnc';
                break;
            }

        });
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
});