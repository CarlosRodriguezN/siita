google.load('visualization', '1', {'packages': ['timeline']});
 jQuery.alerts.okButton = JSL_OK;
 jQuery.alerts.cancelButton = JSL_CANCEL
jQuery(document).ready(function() {
    
    loadLstActividades();
    loadChartActividad();
    reloadActividadesTable();
    loadArchivosActividad();
    
});

/**
 *  Carga los nuevos archivos agregados a la actividad
 * @returns {undefined}
 */
function loadArchivosActividad()
{
    var registroPoa = parseInt(jQuery("#registroPoa").val());
    var objTmpPlan = parent.parent.window.lstPoasDocs[registroPoa];
    if ( objTmpPlan && typeof(objTmpPlan) != "undefined") {
        lstTmpArchivos = objTmpPlan.lstArchivos;
    } else {
        var idPlan = parent.parent.window.objLstPoas.lstPoas[registroPoa].idPoa;
        parent.parent.window.lstPoasDocs[registroPoa] = { 
                                                            idPln: idPlan, 
                                                            regPln: registroPoa, 
                                                            lstArchivos: new Array() 
                                                        };
    }
}

/**
 * 
 * @returns {undefined}
 */
function loadLstActividades() {
    var registroPoa = parseInt(jQuery("#registroPoa").val());
    var registroObj = parseInt(jQuery("#registroObj").val());
    var lstTmpPoas = window.parent.objLstPoas.lstPoas;
    var lstActividades = new Array();
    
    if ( typeof(lstTmpPoas) != "undefined" && lstTmpPoas.length > 0) {
        lstActividades = lstTmpPoas[registroPoa].lstObjetivos[registroObj].lstActividades;
        idPlan = lstTmpPoas[registroPoa].idPoa;
    }
    
    if ( lstActividades.length > 0 ){
        for( var x = 0; x < lstActividades.length; x++ ){
            var objActividad = new Actividad();
            objActividad.setDtaActividad( lstActividades[x] );
            //  Agrego informacion en la lista temporal
            tmpLstActividades.push( objActividad );
        }
    }
}

/**
 * 
 * @returns {undefined}
 */
function loadChartActividad() {
    var container = document.getElementById('graficoAct');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();

    if ( getRegValidos() > 0 ) {
        
        jQuery("#graficoAct").css("display", "block");
        jQuery("#grafSinReg").css("display", "none");
        
        dataTable.addColumn({type: 'string', id: 'Posicion'});
        dataTable.addColumn({type: 'string', id: 'Actividad'});
        dataTable.addColumn({type: 'date', id: 'Inicio'});
        dataTable.addColumn({type: 'date', id: 'Fin'});

        for (var j = 0; j < tmpLstActividades.length; j++) {
            if (parseInt(tmpLstActividades[j].published) === 1) {

                var arr = tmpLstActividades[j].fchActividad.split('-');
                var ano = arr[0];
                var mes = arr[1];
                var dia = arr[2];
                var pos = j+1 ;
                pos = "" + pos;
                dataTable.addRow([pos, tmpLstActividades[j].descripcion, new Date(ano, mes, dia, 7, 0, 0), new Date(ano, mes, dia, 16, 0, 0)]);
            }
        }
        
        var options = {
            colors: ['#cbb69d', '#603913', '#c69c6e']
        };

        chart.draw(dataTable, options);
    } else {
        jQuery("#grafSinReg").css("display", "block");
        jQuery("#graficoAct").css("display", "none");
    }
    
}

/**
 *  Retorna el numero de registros validos de un alista
 * @returns {getRegValidos.regValidos}
 */
function getRegValidos()
{
    var regValidos = 0;
    if ( tmpLstActividades.length > 0){
        for (var j=0; j<tmpLstActividades.length; j++) {
            if (tmpLstActividades[j].published == 1){
                regValidos = ++regValidos;
            }
        }
    }
    return regValidos;
}


   