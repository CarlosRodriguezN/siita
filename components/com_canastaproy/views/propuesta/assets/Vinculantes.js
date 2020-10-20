jQuery(document).ready(function() {

    //
    //  Gestiona el retorno de los cantones de una determinada provincia
    //
    jQuery('#jform_idProvincia').change(function(event, idCanton) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_idCanton').html('<option value="0">' + JSL_CARGANDO + '</option>');
        
        //  EnceroCombo Parroquias
        enCerarCombo(jQuery('#jform_idParroquia option'));

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_canastaproy',
                view: 'propuesta',
                tmpl: 'component',
                format: 'json',
                action: 'getCantones',
                idProvincia: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert( JSL_ERROR_CANASTAPROY_PRV + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_LIST_CANTON + '</option>');
                for (x = 0; x < numRegistros; x++) {

                    var selected = (dataInfo[x].id == idCanton) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }

            jQuery('#jform_idCanton').html(items.join(''));
        });
    });

    //
    //  Gestiona Combo Parroquias
    //
    jQuery('#jform_idCanton').change(function(event, idCanton, idParroquia) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_idParroquia').html('<option value="0">' + JSL_CARGANDO + '</option>');

        var dataIdCanton = (typeof(idCanton) != "undefined") ? idCanton
                : jQuery(this).val();

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_canastaproy',
                view: 'propuesta',
                tmpl: 'component',
                format: 'json',
                action: 'getParroquias',
                idCanton: dataIdCanton
            },
            error: function(jqXHR, status, error) {
                alert( JSL_ERROR_CANASTAPROY_CNT + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_LIST_PARROQUIA + '</option>');
                for (x = 0; x < numRegistros; x++) {

                    var selected = (dataInfo[x].id == idParroquia) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                if (jQuery('#jform_idCanton').val() == 0) {
                    items.push('<option value="0">' + JSL_LIST_PARROQUIA + '</option>');
                } else {
                    items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
                }
            }

            jQuery('#jform_idParroquia').html(items.join(''));
        });
    });

    //
    //Gestiona las politicas nacionales de un objetivo
    //
    jQuery('#jform_intCodigo_on').change(function(event, idPolitica) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_intCodigo_pn').html('<option value="0">' + JSL_CARGANDO + '</option>');
        jQuery('#jform_idCodigo_mn').html('<option value="0">' + JSL_LIST_MTA_NAC + '</option>');

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_canastaproy',
                view: 'propuesta',
                tmpl: 'component',
                format: 'json',
                action: 'getPoliticaNacional',
                idObjNac: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert( JSL_ERROR_CANASTAPROY_PN + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_LIST_PLT_NAC + '</option>');
                for (var x = 0; x < numRegistros; x++) {

                    var selected = (dataInfo[x].id == idPolitica) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }

            jQuery('#jform_intCodigo_pn').html(items.join(''));

            //  Ajusto el tamaño del comboBox
            jQuery('#jform_intCodigo_pn').css('width', '400px');
        });
    });

    //
    //  Gestiona Combo Politica Nacional
    //
    jQuery('#jform_intCodigo_pn').change(function(event, idObjetivo, idPolitica, idMeta) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        var dataIdObjetivo = (typeof(idObjetivo) != "undefined") ? idObjetivo
                : jQuery('#jform_intCodigo_on').val();

        var dataIdPolitica = (typeof(idPolitica) != "undefined") ? idPolitica
                : jQuery('#jform_intCodigo_pn').val();

        jQuery('#jform_idCodigo_mn').html('<option value="0">' + JSL_CARGANDO + '</option>');

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_canastaproy',
                view: 'propuesta',
                tmpl: 'component',
                format: 'json',
                action: 'getMetaNacional',
                idObjNac: dataIdObjetivo,
                idPolNac: dataIdPolitica
            },
            error: function(jqXHR, status, error) {
                alert( JSL_ERROR_CANASTAPROY_MTA_NAC + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_LIST_MTA_NAC + '</option>');
                for (x = 0; x < numRegistros; x++) {

                    var selected = (dataInfo[x].id == idMeta) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }

            jQuery('#jform_idCodigo_mn').html(items.join(''));

            //  Ajusto el tamaño del comboBox
            jQuery('#jform_idCodigo_mn').css('width', '400px');
        });
    });

    /**
     *  Controla que el tipo de grafico para la Ubicación Geografica sea valido 
     *  y habilita o desabilita el boton para agregar
     */
    jQuery("#jform_idTipoGrafico").change(function() {
        if (jQuery("#jform_idTipoGrafico").val() == 0)
            jQuery('#btnAddLstCoordenadas').attr('disabled', 'disabled');
        else 
            jQuery('#btnAddLstCoordenadas').removeAttr('disabled', '');
    });
    
    //  Elimina valores de un combo determinado
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
});


