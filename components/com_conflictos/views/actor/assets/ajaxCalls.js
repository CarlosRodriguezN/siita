var url = window.location.href;
var path = url.split('?')[0];

jQuery(document).ready(function() {


    /**
     * recuperando las fuentes de un tena segun el tipo
     */
    jQuery("#jform_fuente_intId_tf").change(function(event) {
        jQuery('#jform_fuente_intId_fte').html('Cargando...');
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getFuentes',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                idTipoFuente: jQuery('#jform_fuente_intId_tf').val()
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval("(" + data.responseText + ")");
            var items = [];
            var numRegistros = dataInfo.length;
            if (numRegistros > 0) {
                for (var x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].idFuente + '">' + dataInfo[x].descripcion + '</option>');
                }
            } else {
                items.push('<option value="0">Sin registros disponibles</option>');
            }
            jQuery('#jform_fuente_intId_fte').html(items.join(''));
        });
    });

    /**
     * Evento del cambion al seleccionar la provincia
     */
    jQuery("#jform_undTerr_provicia").change(function(event) {
        jQuery("#jform_undTerr_canton").val("Cargando...");
        jQuery("#jform_undTerr_parroquia").val("Cargando...");
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getCantonesContrato',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idProvincia: jQuery('#jform_undTerr_provicia').val()
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;
            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">Seleccione un Cant&oacute;n</option>');
                for (x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">Sin registros disponibles</option>');
            }
            jQuery('#jform_undTerr_canton').html(items.join(''));
            if (oFuente.unidadTerritorial.idCanton != 0) {
                recorrerCombo(jQuery("#jform_undTerr_canton option"), oFuente.unidadTerritorial.idCanton);
                jQuery("#jform_undTerr_canton option").trigger("change");
                if (oFuente.unidadTerritorial.idParroquia == 0) {
                    setUnidadTerririalData();
                }

            }
        });
    });


    /**
     * carga de cantones.
     */
    jQuery("#jform_undTerr_canton").change(function() {
        jQuery("#jform_undTerr_parroquia").val("Cargando...");
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getParroquias',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idCanton: jQuery('#jform_undTerr_canton').val()
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;
            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">Seleccione un Cant&oacute;n</option>');
                for (x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">Sin registros disponibles</option>');
            }
            jQuery('#jform_undTerr_parroquia').html(items.join(''));
            if (oFuente.unidadTerritorial.idParroquia != 0) {
                recorrerCombo(jQuery("#jform_undTerr_parroquia option"), oFuente.unidadTerritorial.idParroquia);
                setUnidadTerririalData();
            }
        });
    });
    /**
     * Cambio en la unidad territorial de la parroquia
     */
    jQuery("#jform_undTerr_parroquia").change(function() {
        setUnidadTerririalData();
    });

});



/**
 * Carga erl objeto con la unidad territorial
 * @returns {undefined}
 * */
function setUnidadTerririalData() {
    var data = getUndTerForm();
    oFuente.unidadTerritorial.setData(data);
    oFuente.idUnidadTerritorial = getIdUnidadTerritorial(data);
}
/**
 * Recupera la informacion del formulario
 * @returns {getUndTerForm.data}
 */
function getUndTerForm() {
    var data = {
        idProvincia: jQuery("#jform_undTerr_provicia").val(),
        provincia: jQuery("#jform_undTerr_provicia option:selected").text(),
        idCanton: jQuery("#jform_undTerr_canton").val(),
        canton: jQuery("#jform_undTerr_canton option:selected").text(),
        idParroquia: jQuery("#jform_undTerr_parroquia").val(),
        parroquia: jQuery("#jform_undTerr_parroquia option:selected").text()
    };
    return data;
}
/**
 * Recupera el identificador de la unidad territorial
 * @param {type} data
 * @returns {unresolved}
 */
function getIdUnidadTerritorial(data) {
    var idUnidadTerritorial = 0;
    if (data.idProvincia != 0) {
        idUnidadTerritorial = data.idProvincia;
    }
    if (data.idCanton != 0) {
        idUnidadTerritorial = data.idCanton;
    }
    if (data.idParroquia != 0) {
        idUnidadTerritorial = data.idParroquia;
    }
    return idUnidadTerritorial
}