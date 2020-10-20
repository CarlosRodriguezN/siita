google.load('visualization', '1', {'packages': ['timeline']});
jQuery(document).ready(function() {
    
    var container = document.getElementById('graficoAct');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();
    var options = {
        colors: ['#cbb69d', '#603913', '#c69c6e']
    };
    
    loadActividadesByFnc();
    
    /**
     * 
     * @param {type} lstActividades
     * @param {type} nameResp
     * @returns {undefined}
     */
    function loadChartActividad( lstActividades, nameResp ) 
    {
        if ( getRegValidos(lstActividades) > 0 ) {
            for (var j = 0; j < lstActividades.length; j++) {
                if (parseInt(lstActividades[j].published) === 1) {
                    var arr = lstActividades[j].fchActividad.split('-');
                    var ano = arr[0];
                    var mes = parseInt(arr[1])-1;
                    var dia = arr[2];
                    dataTable.addRow([nameResp, lstActividades[j].descripcion, new Date(ano, mes, dia, 7, 0, 0), new Date(ano, mes, dia, 16, 0, 0)]);
                }
            }
        } else {
            var arr = new Date();
            var ano = arr.getFullYear();
            var mes = arr.getMonth();
            var dia = arr.getDate();
            dataTable.addRow([nameResp, "SIN ACTIVIDADES", new Date(ano, mes, dia, 7, 0, 0), new Date(ano, mes, dia, 16, 0, 0)]);
        }
    }

    function loadActividadesByFnc()
    {
        var lstFnc = objLstActFnc.lstFuncionarios;
        if ( lstFnc.length > 0 ) {
            jQuery("#graficoAct").css("display", "block");
            jQuery("#grafSinReg").css("display", "none");

            dataTable.addColumn({type: 'string', id: 'Funcionario'});
            dataTable.addColumn({type: 'string', id: 'Actividad'});
            dataTable.addColumn({type: 'date', id: 'Inicio'});
            dataTable.addColumn({type: 'date', id: 'Fin'});

            for (var j = 0; j < lstFnc.length; j++) {
                loadChartActividad( lstFnc[j].lstActividades, lstFnc[j].nombreResponsable);
            }

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
    function getRegValidos(lstActividades)
    {
        var regValidos = 0;
        if ( lstActividades.length > 0){
            for (var j=0; j<lstActividades.length; j++) {
                if (lstActividades[j].published == 1){
                    regValidos = ++regValidos;
                }
            }
        }
        return regValidos;
    }
});



   