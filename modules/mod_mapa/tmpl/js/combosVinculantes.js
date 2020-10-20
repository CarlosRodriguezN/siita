/*
 * @event ready
 * @description LLamada ajax al seleccionar una PROVINCIA
 */

var utActualCoor = null;

jQuery(document).ready(function() {
    jQuery("#accordion").accordion({collapsible: true, "active": 2,});
    jQuery("#accordionMapas").accordion({collapsible: true, "active": 2 });

    selectCbElelment('cbFiltro', 2);

    jQuery('#cbFiltro').change(function() {
        getZonaRegiones();
    });

    selectCbElelment('cbZona', 38);

    // seleccionado la region 
    jQuery('#cbZona').trigger('change');
    jQuery("#lblEntidad option:selected").html(jQuery('#cbFiltro').text());

});

/*
 *@name selectCbElelment
 *@description selecciona el elemnto de una cb derminado
 *@param idELement idetidicador de el elemento que se desea seleccinar.
 *@param index elemento que se desea seleccionar.
 */
function selectCbElelment(idELement, index)
{
    // recuperamos el elemeto que pueda ser seleccionado
    var flag = false;

    if (jQuery('#' + idELement)) {//precedemos a seleccionar el elemento de el index seleccionado
        jQuery("#" + idELement + " option[value=" + index + "]").attr("selected", true);
        flag = true;
    }

    return flag;
}


function getZonaRegiones()
{
    setEmptyCb('cbZona', 'Cargando...');
    var path = 'http://' + window.location.host + '/modules/mod_mapa/models/programas.php';
    jQuery.getJSON(path,
            {
                task: 'getFiltros',
                tut: jQuery('#cbFiltro').val(),
                id: 0
            },
    function(data) {
        construirFiltros(jQuery('#cbFiltro').val());

        var items = [];
        var numRegistros = data.length;
        if (numRegistros > 0) {
            items.push('<option value="0">' + jQuery('#cbFiltro option:selected').text() + '</option>');
            for (x = 0; x < numRegistros; x++) {
                items.push('<option value="' + data[x].id + '">' + data[x].nombre + '</option>');
            }
        } else {
            items.push('<option value="0">Sin registros disponibles</option>');
        }
        jQuery('#cbZona').html(items.join(''));
        emptyCbFiltros(jQuery('#cbFiltro').attr('id'));
        jQuery("#lblEntidad").html(jQuery('#cbFiltro option:selected').text());
    });
}

//  Gestiona las provincias por zona
function cbAddProvincias() {
    setEmptyCb('cbProvincias', 'Cargando...');
    var path = 'http://' + window.location.host + '/modules/mod_mapa/models/programas.php';
    jQuery.getJSON(path,
            {
                task: 'getProvinciasPorZona',
                tut: jQuery('#cbFiltro').val(),
                idZona: jQuery('#cbZona').val()
            },
    function(data) {
        var items = [];
        var numRegistros = data.length;
        if (numRegistros > 0) {
            items.push('<option value="0">Provincia</option>');
            for (x = 0; x < numRegistros; x++) {
                items.push('<option value="' + data[x].id + '">' + data[x].nombre + '</option>');
            }
        } else {
            items.push('<option value="0">Sin registros disponibles</option>');
        }
        jQuery('#cbProvincias').html(items.join(''));
        jQuery('#lblRegion').html(items.join(''));

        getTreeNodes( jQuery('#cbZona').val(), jQuery('#cbFiltro').val() );
        emptyCbFiltros(jQuery("#cbZona").attr('id'));

        if (utActualCoor){
            setMapCenterZonm(utActualCoor.utActualCoor, data.longi, 8);
        }
    });
    return false;
}

function cbAddCantones()
{
    setEmptyCb('cbCantones', 'Cargando...');
    var path = 'http://' + window.location.host + '/modules/mod_mapa/models/programas.php';
    jQuery.getJSON(path,
            {
                task: 'getCantonesPorProvincia',
                tut: '3',
                idZona: jQuery('#cbZona').val(),
                idProvincia: jQuery('#cbProvincias').val()
            },
    function(data) {
        var items = [];
        var numRegistros = data.length;
        if (numRegistros > 0) {
            items.push('<option value="0">Cantón</option>');
            for (x = 0; x < numRegistros; x++) {
                items.push('<option value="' + data[x].id + '">' + data[x].nombre + '</option>');
            }
        } else {
            items.push('<option value="0">Sin registros disponibles</option>');
        }
        jQuery('#cbCantones').html(items.join(''));

        getTreeNodes(jQuery('#cbProvincias').val(), 3);
        emptyCbFiltros(jQuery("#cbProvincias").attr('id'));
        if (utActualCoor)
            setMapCenterZonm(utActualCoor.lat, utActualCoor.longi, 6);
    });
    return false;
}

function cbAddParroquias()
{
    setEmptyCb('cbParroquias', 'Cargando...');
    var path = 'http://' + window.location.host + '/modules/mod_mapa/models/programas.php';
    jQuery.getJSON(path,
            {
                task: 'getParroquiasPorCanton',
                tut: '4',
                idZona: jQuery('#cbZona').val(),
                idCanton: jQuery('#cbCantones').val()
            },
    function(data) {
        var items = [];
        var numRegistros = data.length;
        if (numRegistros > 0) {
            items.push('<option value="0">Parroquia</option>');
            for (x = 0; x < numRegistros; x++) {
                items.push('<option value="' + data[x].id + '">' + data[x].nombre + '</option>');
            }
        } else {
            items.push('<option value="0">Sin registros disponibles</option>');
        }
        jQuery('#cbParroquias').html(items.join(''));
        getTreeNodes(jQuery('#cbCantones').val(), 4);
        if (utActualCoor)
            setMapCenterZonm(utActualCoor.lat, utActualCoor.longi, 4);
    });
    return false;
}

function construirFiltros(id) {
    jQuery('#filtros').html('');
    if (document.id('inicial'))
        jQuery('#inicial').html('');
    var cadZona = '<table id"inicial"><tr><td><div id="lblEntidad">Zona</div></td><td><SELECT id="cbZona" class="cbVinculantes" onchange="cbAddProvincias()" ></SELECT></td></tr>';
    var cadProvincia = '<tr><td>Provincia</td><td><SELECT id= "cbProvincias"  class="cbVinculantes" onchange="cbAddCantones()"  ></SELECT></td></tr>';
    var cadCanton = '<tr><td>Cantón</td><td><SELECT id= "cbCantones"  class="cbVinculantes" onchange="cbAddParroquias()"></SELECT></td></tr>';
    var cadParroquia = '<tr><td>Parroquia</td><td><SELECT id= "cbParroquias"  class="cbVinculantes" onchange="cbChangeParroquias()"></SELECT></td></tr></table>';
    var cadena = cadZona + cadProvincia + cadCanton + cadParroquia;
    jQuery('#inicial').html(cadena);
}

function cbChangeParroquias() {
    getTreeNodes(jQuery('#cbParroquias').val(), 5);
}

function getTreeNodes(idPadre, tut) {
    var path = 'http://' + window.location.host + '/modules/mod_mapa/models/programas.php';

    jQuery.getJSON(path,
            {
                task: 'getProgramas',
                tut: tut,
                id: idPadre
            }, function(proyJSON) {
        document.id('treeProyectos').set('html', '');
        hideAllShow();
        loadTreeProyectos(proyJSON);
    });
}

function setEmptyCb(id, camp) {
    if (jQuery('#' + id)) {
        var items = [];
        items.push('<option value="0">' + camp + '</option>');
        jQuery('#' + id).html(items.join(''));
    }
}

function emptyCbFiltros(id) {
    switch (id) {
        case'cbFiltro':

            setEmptyCb('cbProvincias', 'Provincia');
            setEmptyCb('cbCantones', 'Cantón');
            setEmptyCb('cbParroquias', 'Parroquia');
            break;
        case'cbZona':
            setEmptyCb('cbCantones', 'Cantón');
            setEmptyCb('cbParroquias', 'Parroquia');
            break;
        case'cbProvincia':
            setEmptyCb('cbParroquias', 'Parroquia');
            break;
    }
}