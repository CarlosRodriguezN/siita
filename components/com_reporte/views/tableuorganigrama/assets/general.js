
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
    chart.draw(table, {allowHtml: true, size: 'medium', allowCollapse: false});

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
                addTableUnidadPlan(table, data, 0)
                break;

            case 7:
                data.tipoNodo = 'ug';
                addTableUnidadGestion(table, data, 0)
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
function addTableUnidadPlan(table, data, idPadre) {

    addRowTable(table, data, idPadre);

    if (data.lstUndGest.length > 0) {
        for (var j = 0; j < data.lstUndGest.length; j++) {
            data.lstUndGest[j].tipoNodo = 'ug';
            addTableUnidadGestion(table, data.lstUndGest[j], data.idEntidad)
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
    if (data.lstFuncionarios) {
        if (data.lstFuncionarios.length > 0) {
            for (var j = 0; j < data.lstFuncionarios.length; j++) {
                data.lstFuncionarios[j].tipoNodo = 'fu';
                addRowTable(table, data.lstFuncionarios[j], data.intCodigo_ug);
            }
        }
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
    var cadF = '';
    var nodeAdd = false;
    var alias = '';
    var idTable = '';
    switch (node.tipoNodo) {
        case 'ug':
            nodeAdd = {v: 'ug-' + node.intCodigo_ug, f: cadUG(node)};
            alias = node.strAlias_ug;
            idTable = 'in-' + idPadre;
            break;
        case 'fu':
            nodeAdd = {v: 'fu-' + node.idFuncionario, f: cadFU(node)};
            alias = node.nombre;
            idTable = 'ug-' + idPadre;
            break;
        case 'in':
            nodeAdd = {v: 'in-' + node.idEntidad, f: cadIN(node)};
            alias = node.nombre;
            break;
    }


    var row = [nodeAdd, idTable, alias];
    if (idPadre === 0) {
        row = [nodeAdd, , alias];
    }

    table.addRow(row);
}

/**
 * 
 * @param {type} node
 * @returns {String}
 */
function cadUG(node) {

    var url = (node.strUrlTableU_ent !== "") ? node.strUrlTableU_ent : 'Sin Valor';
    var cad = '';
    cad += '<b> ' + node.strNombre_ug + '</b></br>';
    cad += 'URL: </br><a id="' + node.intIdentidad_ent + '" class="edit" >' + url + '</a>';

    return cad;
}

/**
 * 
 * @param {type} node
 * @returns {String}
 */
function cadFU(node) {
    var url = (node.strUrlTableU_ent !== "") ? node.strUrlTableU_ent : 'Sin Valor';
    var cad = '';
    cad += '<b> ' + node.nombre + '</b></br>';
    cad += 'URL:</br> <a id="' + node.idEntidad + '" class="edit" >' + url + '</a>';
    cad += '';

    return cad;

}

/**
 * 
 * @param {type} node
 * @returns {String}
 */
function cadIN(node) {
    var cad = '';
    cad += '<b> ' + node.nombre + '</b>';
    cad += '<br>';
    return cad;
}

