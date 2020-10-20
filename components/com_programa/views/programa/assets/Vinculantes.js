jQuery(document).ready(function() {

    //
    //  Gestiona Tipo de Unidad de Medida - Unidad de Medida
    //
    jQuery('#jform_mtaTipoUndMedida').change(function() {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_mtaUndMedida').html('<option value="0">CARGANDO...</option>');

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_proyectos',
                view: 'proyecto',
                tmpl: 'component',
                format: 'json',
                action: 'getUnidadMedida',
                idTpoUM: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert('Proyectos - Gestion Unidad Medida: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">SELECCIONE UNIDAD DE MEDIDA</option>');
                for (x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_mtaUndMedida').html(items.join(''));
        });
    });

    //
    //  Gestiona Enfoque de Igualdad
    //
    jQuery('#jform_cbEnfoqueIgualdad').change(function(event, idTpoEnfoque, idEnfoque) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_idEnfoqueIgualdad').html('<option value="0">CARGANDO...</option>');

        var dataIdTpoEnf = (typeof(idTpoEnfoque) != "undefined") ? idTpoEnfoque
                : jQuery('#jform_cbEnfoqueIgualdad').val();

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: { option: 'com_proyectos',
                    view: 'proyecto',
                    tmpl: 'component',
                    format: 'json',
                    action: 'getTiposEnfoqueIgualdad',
                    idTipoEnfoque: dataIdTpoEnf
            },
            error: function(jqXHR, status, error) {
                alert('Proyectos - Gestion Enfoque Igualdad: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">SELECCIONE ENFOQUE DE IGUALDAD</option>');
                for (x = 0; x < numRegistros; x++) {
                    var selected = (dataInfo[x].id == idEnfoque) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_idEnfoqueIgualdad').html(items.join(''));
        });
    });


    //
    //  Gestiona unidades de medida de un tipo de unidad de medida en otros indicadores
    //
    jQuery('#jform_idTpoUndMedida').change(function(event, idTpoUM, umInd) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_idUndMedidaMetaNewInd').html('<option value="0">CARGANDO...</option>');

        var dataTpoUM = (typeof(idTpoUM) != "undefined") ? idTpoUM
                : jQuery('#jform_idTpoUndMedida').val();

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_proyectos',
                view: 'proyecto',
                tmpl: 'component',
                format: 'json',
                action: 'getUnidadMedidaTipo',
                idTpoUndMedida: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert('Proyectos - Gestion Unidad de Medida: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">Seleccione Unidad de Medida</option>');
                for (x = 0; x < numRegistros; x++) {

                    var selected = (dataInfo[x].id == umInd) ? 'selected'
                            : '';

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + ' >' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_idUndMedidaMetaNewInd').html(items.join(''));
        });
    });

});

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

/**
 *  
 * @param {type} idForm
 * @returns {undefined}
 */
function resetValidateForm( idForm )
{
    jQuery("#programa-form").submit();
    
    jQuery( idForm + " select").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( idForm + " input[type=text]").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( idForm + " textarea").each(function () {
        jQuery( this ).removeClass( "error" );
    });
}
