/**
 * archivos encargado de las llamadas ajax
 */

//  Obtengo URL completa del sitio
var url = window.location.href;
var path = url.split('?')[0];

jQuery(document).ready(function() {
    /**
     * Gestion del combo de AGENDAS
     */
    jQuery('#jform_idAgenda').change(function() {
        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_alineacion',
                view: 'estrategica',
                tmpl: 'component',
                format: 'json',
                action: 'getEstructura',
                idAgenda: jQuery('#jform_idAgenda').val()
            },
            error: function(jqXHR, status, error) {
                alert('Proyectos - Gestion Politica Nacional: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            if (data.responseText) {
                var jsonE = eval(data.responseText);

                oAlineacion = getObjAlineacion(jsonE);
                oAlineacion.loadNivel();

                // carga del primer nivel
                var itmData = {
                    idPadre: 0,
                    idAgenda: parseInt(jQuery('#jform_idAgenda').val())
                };
                loadItems(itmData);
            }
        });
    });

    /**
     * Carga de los items
     */
    jQuery('.itmAgenda').live('change', function() {
        var itmData = {
            idPadre: parseInt(jQuery(this).val()),
            idAgenda: parseInt(jQuery('#jform_idAgenda').val())
        };
        loadItems(itmData);
    });

});

/**
 * OBTIENE el OBJETO alineacion del formulario
 * @param {type} data
 * @returns {Alineacion|getObjAlineacion.oAlineacion}
 */
function getObjAlineacion(data) {
    var oAlineacion = new Alineacion();
    var attr = {
        idRegistro: regAlineacion,
        idAgenda: parseInt(jQuery("#jform_idAgenda").val()),
        nombre: jQuery("#jform_idAgenda option:selected").text(),
        published: 1
    };

    oAlineacion.setData(attr);
    for (var j = 0; j < data.length; j++) {
        oAlineacion.addNivel(data[j]);
    }
    return oAlineacion;
}


/**
 * Recupera los itmes de una entidad de la estructura
 * @param {type} data
 * @param {type} idSelect
 * @returns {undefined}
 */
function loadItems(data, idSelect) {
    jQuery.ajax({type: 'GET',
        url: path,
        dataType: 'JSON',
        data: {option: 'com_alineacion',
            view: 'estrategica',
            tmpl: 'component',
            format: 'json',
            action: 'getItems',
            idPadre: data.idPadre,
            idAgenda: data.idAgenda
        },
        error: function(jqXHR, status, error) {
            alert('Proyectos - Gestion Politica Nacional: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function(data) {
        var lstItems = eval(data.responseText);
        addItemsEstructura(lstItems, idSelect);
    });
}

/**
 * AGREGA items a la estructura
 * @param {JSON} data   información del item
 * @param {INT} idSelect    idetificador del select a seleccionar
 * @returns {undefined}
 */
function addItemsEstructura(data, idSelect) {
    if (data.length > 0) {
        borrarItemsNivel(jQuery("#jform_" + data[0].idEstructura + ' option'));
        for (var j = 0; j < data.length; j++) {
            var id = data[j].idItem;
            var nombre = '<b>' + data[j].nivel + '</b> ' + data[j].descripcion;
            var select = (idSelect == id) ? 'selected' : '';

            var cad = '<option value="' + id + '" ' + select + '>' + nombre + '</option>';
            jQuery("#jform_" + data[j].idEstructura).append(cad);
        }
    } 
}

/**
 *  BORRA los items de un combo (todos menos el primero)
 * @param {type} combo
 * @returns {undefined}
 */
function borrarItemsNivel(combo) {
    jQuery(combo).each(function() {
        if (jQuery(this).val() != 0) {
            jQuery(this).remove();
        }
    });
}

/**
 * Recorre un combo 
 * @param {type} combo
 * @param {type} posicion
 * @returns {undefined}
 */
function recorrerCombo(combo, posicion) {
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}