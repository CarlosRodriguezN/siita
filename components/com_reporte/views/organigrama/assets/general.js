
google.load('visualization', '1.0', {'packages': ['orgchart']});

jQuery(document).ready(function() {
    var dataJson = jQuery("#jform_organigrama", window.parent.document).val();

    var organigramaJSON = eval('(' + dataJson + ')');
    organigrama(organigramaJSON);
});


/**
 *  Funcion centro del Organigrama
 * @param {JSON} organigramaJSON    Organigrama en formato JSON
 * @returns {undefined}
 */
function organigrama(organigramaJSON) {

    var table = getGoogleTable(organigramaJSON);

    var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
    chart.draw(table, {allowHtml: true, size: 'medium', allowCollapse: 'true'});

}

/**
 * Arma la tabla necesaria para el organigrama
 * @param {JSON}    data Organigrama en formato JSON
 * @returns {getGoogleTable.table|google.visualization.DataTable}
 */
function getGoogleTable(data) {
    var table = new google.visualization.DataTable();
    table.addColumn('string', 'Name');
    table.addColumn('string', 'Manager');
    table.addColumn('string', 'ToolTip');
    if (data) {
        switch (data.tpoEntidad) {
            case 8:
                data.tipoNodo = 'in';
                addTableInstitucion(table, data, 0);
                break;

            case 7:
                data.tipoNodo = 'ug';
                addTableUnidadGestion(table, data, 0);
                break;
        }
    }
    return table;
}

/**
 * 
 * @param {type} table
 * @param {type} data
 * @param {type} idPadre
 * @returns {undefined}
 */
function addTableInstitucion(table, data, idPadre) {

    addRowTable(table, data, idPadre);

    if (data.lstUndGest.length > 0) {
        for (var j = 0; j < data.lstUndGest.length; j++) {
            data.lstUndGest[j].tipoNodo = 'ug';
            var owner = "in-" + data.idEntidad;
            addRowTable( table, data.lstUndGest[j], owner );
            if ( data.lstUndGest[j].lstHijos ){
                if ( data.lstUndGest[j].lstHijos.length > 0 ){
                    var oUGHijos = "ug-" + data.lstUndGest[j].idEntidad;
                    addTableUGHijos(table, data.lstUndGest[j].lstHijos, oUGHijos);
                }
            }
        }
    }
}

/**
 * 
 * @param {type} table
 * @param {type} data
 * @param {type} idPadre
 * @returns {undefined}
 */
function addTableUnidadGestion(table, data, idPadre) {
    addRowTable(table, data, idPadre);
    var owner = 'ug-' + data.idEntidad;
    if (data.lstHijos) {
        if (data.lstHijos.length > 0) {
            addTableUGHijos(table, data.lstHijos, owner);
        }
    }
    if (data.lstFuncionarios) {
        if (data.lstFuncionarios.length > 0) {
            for (var j = 0; j < data.lstFuncionarios.length; j++) {
                addTableUGFucnionarios(table, data.lstFuncionarios, owner);
            }
        }
    }
}

/**
 * 
 * @param {type} table
 * @param {type} data
 * @param {type} idPadre
 * @returns {undefined}
 */
function addTableUGHijos(table, hijos, idPadre) {
    for (var j = 0; j < hijos.length; j++) {
        hijos[j].tipoNodo = 'ug';
        addRowTable(table, hijos[j], idPadre);
        var owner = 'ug-' + hijos[j].idEntidad;
        if( hijos[j].lstHijos ){
            if( hijos[j].lstHijos.length > 0 ){
                addTableUGHijos(table, hijos[j].lstHijos, owner);
            }
        }
        if( hijos[j].lstFuncionarios ){
            if( hijos[j].lstFuncionarios.length > 0 ){
                addTableUGFucnionarios(table, hijos[j].lstFuncionarios, owner);
            }
        }
    }
}

/**
 * 
 * @param {type} table
 * @param {type} funcionarios
 * @param {type} idPadre
 * @returns {undefined}
 */
function addTableUGFucnionarios(table, funcionarios, idPadre) {
    for (var j = 0; j < funcionarios.length; j++) {
        funcionarios[j].tipoNodo = 'fu';
        addRowTable(table, funcionarios[j], idPadre);
    }
}

/**
 * 
 * @param {type} table
 * @param {type} node
 * @param {type} idPadre
 * @returns {addRowTable}
 */
function  addRowTable(table, node, idPadre) {
    var nodeAdd = false;
    var title = '';
    var owner = '';
    switch (node.tipoNodo) {
        case 'ug':
            nodeAdd = {v: 'ug-' + node.idEntidad, f: cadUG(node)};
            title = node.strAlias_ug;
            owner = ( idPadre != 0 ) ? idPadre : '';
            break;
        case 'fu':
            nodeAdd = {v: 'fu-' + node.idFuncionario, f: cadFU(node)};
            title = node.nombre;
            owner = idPadre;
            break;
        case 'in':
            nodeAdd = {v: 'in-' + node.idEntidad, f: cadIN(node)};
            title = node.nombre;
            break;
    }


    var row = [nodeAdd, owner, title];

    table.addRow(row);
}


function cadUG(node) {
    var cad = '';
    cad += '<b> ' + node.strNombre_ug + '</b>';
    cad += '<br>';
    cad += '<a onclick="javascript: goURL(' + node.intCodigo_ug + ',1);">Ir...</a>';
    return cad;
}

function cadFU(node) {
    var cad = '';
    cad += '<b> ' + node.nombre + '</b>';
    cad += '<br>';
    cad += '<a onclick="javascript: goURL(' + node.idFuncionario + ',);">Ir...</a>';
    return cad;
}

function cadIN(node) {
    var cad = '';
    cad += '<b> ' + node.nombre + '</b>';
    cad += '<br>';
    cad += '<a onclick="javascript: goURL(' + node.idInstitucion + ',1);">Ir...</a>';
    return cad;
}

function goURL(id, tipo) {
    //window.parent.SqueezeBox.close()
    var pathBase = 'http://' + window.location.host + '/index.php?option=';
    var pathLast = '';
    switch (tipo) {
        case 1:
            pathLast = pathBase + 'com_unidadgestion&view=unidadgestion&layout=edit&intCodigo_ug=' + id;
            break;
        case 2:
            pathLast = pathBase + 'com_funcionarios&view=funcionario&layout=edit&intCodigo_fnc=' + id;
            break;
        case 3:
            alert('a la institucion');
            pathLast = pathBase + 'com_funcionarios&view=funcionario&layout=edit&intCodigo_fnc=' + id;
            break;
    }
    window.parent.location.href = pathLast;
}