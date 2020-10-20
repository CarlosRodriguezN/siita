google.load('visualization', '1', {'packages': ['timeline']});
jQuery(document).ready(function() {

    //  Opciones para los acordiones
    var optionsAccordion = {collapsible : true,
                            heightStyle : "content",
                            autoHeight  : false,
                            clearStyle  : true,
                            header      : 'h3'};
                        
    var container = document.getElementById('graficoAct');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();
    
    //  Acordion de objetivos con sus indicadores
    jQuery("#indFnc").accordion(optionsAccordion);
    jQuery("#actFnc").accordion(optionsAccordion);
    jQuery("#accFnc").accordion(optionsAccordion);

    //  Pestañas General
    jQuery('#detallesFnc').tabs();

    // Carga las actividades del fucionario
    loadChartActividad();
    loadDataFuncionario();
    
    /**
     * 
     * @returns {undefined}
     */
    function loadChartActividad() 
    {

        if ( lstActividadesFnc.length > 0 ) {

            jQuery("#graficoAct").css("display", "block");
            jQuery("#grafSinReg").css("display", "none");

            dataTable.addColumn({type: 'string', id: 'Posicion'});
            dataTable.addColumn({type: 'string', id: 'Actividad'});
            dataTable.addColumn({type: 'date', id: 'Inicio'});
            dataTable.addColumn({type: 'date', id: 'Fin'});

            for (var j = 0; j < lstActividadesFnc.length; j++) {
                var arr = lstActividadesFnc[j].fchActividad.split('-');
                var ano = arr[0];
                var mes = arr[1];
                var dia = arr[2];
                var pos = j+1 ;
                pos = "" + pos;
                dataTable.addRow([pos, lstActividadesFnc[j].descripcion, new Date(ano, mes, dia, 7, 0, 0), new Date(ano, mes, dia, 16, 0, 0)]);
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
     *  Carga la data en el formulario de fucnionario
     * @returns {undefined}
     */
    function loadDataFuncionario()
    {
        jQuery("#jform_strCI_fnc").val(dtaFnc["strCI_fnc"]);
        jQuery("#jform_strApellido_fnc").val(dtaFnc["strApellido_fnc"]);
        jQuery("#jform_strNombre_fnc").val(dtaFnc["strNombre_fnc"]);
        jQuery("#jform_strCorreoElectronico_fnc").val(dtaFnc["strTelefono_fnc"]);
        jQuery("#jform_strTelefono_fnc").val(dtaFnc["strTelefono_fnc"]);
        jQuery("#jform_strCelular_fnc").val(dtaFnc["strCelular_fnc"]);
    }


});




