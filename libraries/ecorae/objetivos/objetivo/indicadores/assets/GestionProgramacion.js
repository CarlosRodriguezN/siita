jQuery(document).ready(function() {

    loadDataTable();
    classAcordion();
    avaliblePrgAll();

    /**
     *  Caraga los datos de la programacion plurianual de un indicador en 
     *  la tabla para visualizarlos.
     */
    jQuery(".loadPrgInd").live("click", function() {
        var idInd = (jQuery(this).parent()).attr('id');
        var idObj = (jQuery(this).parent().parent().parent()).attr('id');
        var idRegObj = parseInt(idObj.toString().split('-')[1]);
        var idRegInd = parseInt(idInd.toString().split('-')[1]);
        var oIndicador = objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores[idRegInd];
        loadTableProgramacion(oIndicador, idRegObj, idRegInd);
    });

    /**
     *  Caraga los datos de la programacion anual de un indicador en 
     *  la tabla para visualizarlos.
     */
    jQuery(".loadPrgIndPapp").live("click", function() {
        var idInd = (jQuery(this).parent()).attr('id');
        var idObj = (jQuery(this).parent().parent().parent()).attr('id');
        var idAnio = (jQuery(this).parent().parent().parent().parent()).attr('id');
        var idRegObj = parseInt(idObj.toString().split('-')[1]);
        var idRegInd = parseInt(idInd.toString().split('-')[1]);
        var anio = parseInt(idAnio.toString().split('-')[1]);
        var oIndicador = objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores[idRegInd];
        loadTablePapp(oIndicador, anio);
    });

    /**
     *  Calcula y propone una programacion de los indicadores de un objetivo
     */
    jQuery(".prgIndsObj").live("click", function() {
        var idObj = (jQuery(this).parent()).attr('id');
        var idRegObj = parseInt(idObj.toString().split('-')[1]);
        jConfirm('Las programaciones existendes se eliminaran!', 'SIITA - ECORAE', function(result) {
            if (result) {
                programarIndicadores(idRegObj);
            }
        });
        return false;
    });

    /**
     *  Calcula y propone una programacion de un indicador en especifico.
     */
    jQuery(".prgInd").live("click", function() {
        var idInd = (jQuery(this).parent().parent()).attr('id');
        var idObj = (jQuery(this).parent().parent().parent().parent()).attr('id');
        var idRegObj = parseInt(idObj.toString().split('-')[1]);
        var idRegInd = parseInt(idInd.toString().split('-')[1]);
        var indicador = objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores[idRegInd];

        if (indicador.lstProgramacion.length > 0) {
            jConfirm('La programación existente se eliminara!', 'SIITA - ECORAE', function(result) {
                if (result) {
                    makeProgramacion(indicador, idRegInd, idRegObj);
                }
            });
        } else {
            makeProgramacion(indicador, idRegInd, idRegObj);
        }
        return false;
    });

    /**
     *  Controla si se realizaron cambios en los objetivos e indicadores para
     *  volver a generar los acordiones.
     */
    jQuery("#pPPPP").click(function() {
        controlUpdInd();
    });

    jQuery('#pPAPP').click(function() {
//        if (flagUpdAnioObj) {
////                var fInicio = jQuery("#jform_dteFechainicio_pi").val();
////                var fFin = jQuery("#jform_dteFechafin_pi").val();
////                reloadTabsPapp(fInicio, fFin);
//        }
        controlUpdInd();
    });

    jQuery('.updPrgInd').click(function() {
        var idRegObj = jQuery('#idObjetivoPrg').val();
        var idRegInd = jQuery('#idIndicadorPrg').val();
        var oIndicador = objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores[idRegInd];
        var table = 'tbPpppInd';
        loadFrmIndicador(oIndicador, table);
        jQuery('#tnsPrg').css('display', 'block');
        jQuery('#btnPrgInd').css('display', 'none');
    });

    jQuery("#prgAllInds").live("click", function() {
        if (objLstObjetivo.lstObjetivos.length > 0) {
            autoProgramacionInd(0);
            jQuery("#prgAllInd").css("display", "none");
        } else {
            jAlert("No existen objetivos registrados", "SIITA - ECORAE");
        }
        return false;
    });

    jQuery("#savePrgInd").live("click", function() {

        var idRegObj = jQuery('#idObjetivoPrg').val();
        var idRegInd = jQuery('#idIndicadorPrg').val();
        var idRegPrg = jQuery('#idRegPPPP').val();
        var table = 'tbPpppInd';

        updPrgIndicador(idRegObj, idRegInd, idRegPrg, table);

        jQuery('#tnsPrg').css('display', 'none');
        jQuery('#btnPrgInd').css('display', 'block');
    });

    function loadDataTable() {
        jQuery('#tbPpppInd').dataTable({
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false
        }).editable({
            sUpdateURL: "UpdateData.php",
            "aoColumns": [  {
                            },
                            {
                            }]

        });
    }

    function classAcordion()
    {
        jQuery(".prgIndsObj").button({
            icons: {
                primary: "ui-icon-play"
            }
        });

        jQuery(".prgInd").button({
            icons: {
                primary: "ui-icon-play"
            }
        });

        var options = {
            collapsible: true,
            heightStyle: "content",
            autoHeight: false,
            clearStyle: true,
            header: 'h3'
        };

        jQuery('#ppppInd').accordion(options);

        var fInicio = jQuery("#jform_dteFechainicio_pi").val();
        var fFin = jQuery("#jform_dteFechafin_pi").val();
        var aInicio = parseInt(fInicio.toString().split('-')[0]);
        var aFin = parseInt(fFin.toString().split('-')[0]);
        for (var i = aInicio; i <= aFin; i++) {
            jQuery('#pappInd-' + i).accordion(options);
        }
    }

    function avaliblePrgAll()
    {
        if (jQuery("#jform_intId_pi").val() == 0) {
            jQuery("#prgAllInd").css("display", "block");
        }
    }

    function makeProgramacion(indicador, idRegInd, idRegObj) {
        programarIndicador(indicador, idRegInd, idRegObj);
        indicador = objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores[idRegInd];
        loadTableProgramacion(indicador, idRegObj, idRegInd);
    }
    
    function updPrgIndicador(idRegObj, idRegInd, idRegPrg, table) {

    }

    function loadFrmIndicador(oInd, table)
    {
        jQuery('#' + table + ' > tbody').empty();
        if (oInd.lstProgramacion.length > 0) {
            var lstMetasProgramacion = oInd.lstProgramacion[0].lstMetasProgramacion;
            for (var i = 0; i < lstMetasProgramacion.length; i++) {
                var fila = addRowFrm(lstMetasProgramacion[i]);
                //  Agrego la fila creada a la tabla
                jQuery('#' + table + ' > tbody:last').append(fila);
            }
        }
    }

    function addRowFrm(lstMetasPrg)
    {
        var idReg = lstMetasPrg.idReg;
        var fila = '';
        fila += '<tr id="' + idReg + '">';
        fila += '   <td align="center">';
        fila += '      <input type="text" id="fecha-' + idReg + '" value="' + lstMetasPrg.fecha + '" style="width: 90%;">';
        fila += '   </td>';
        fila += '   <td align="center">';
        fila += '      <input type="text" id="valor-' + idReg + '" value="' + lstMetasPrg.valor + '" style="width: 90%;">';
        fila += '   </td>';
        fila += '</tr>';

        return fila;
    }

    /**
     *  Controla si se realizaron cambios en los objetivos e indicadores para
     *  volver a generar los acordiones.
     */
    function controlUpdInd()
    {
        if (flagUpdObj) {
            cargarAcordionPppp();
            autoProgramacionInd(1);
            flagUpdObj = false;
        }
    }

    function autoProgramacionInd(op)
    {
        var lstObjs = objLstObjetivo.lstObjetivos;
        for (var j = 0; j < lstObjs; j++) {
            switch (op) {
                case 0:
                    programarIndicadores(lstObjs[j].registroObj);
                    break;
                case 1:
                    var lstInds = lstObjs[j].lstIndicadores;
                    for (var i = 0; i < lstInds.length; i++) {
                        if (!lstInds[i].lstProgramacion) {
                            programarIndicador(lstInds[i], i, j);
                        }
                    }
                    break;
            }
        }
    }

    function programarIndicadores(idRegObj)
    {
        var lstIndsObj = objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores;
        for (var i = 0; i < lstIndsObj.length; i++) {
            programarIndicador(lstIndsObj[i], i, idRegObj);
        }
    }

    function cargarAcordionPppp()
    {
        var lstObjs = objLstObjetivo.lstObjetivos;

        var fila = '';
        fila += '   <div id="ppppInd">';
        for (var i = 0; i < lstObjs.length; i++) {
            fila += '   <h3>';
            fila += '       <a href="#"> ';
            fila += lstObjs[i].descObjetivo;
            fila += '       <div id="obj-' + lstObjs[i].registroObj + '" class="fltrt" >';
            fila += '           <button class="prgIndsObj" title="Programar indicadores"> </button>';
            fila += '       </div>';
            fila += '       </a>';
            fila += '    </h3>';
            var lstInds = lstObjs[i].lstIndicadores;
            fila += '  <div id="indsObj-' + lstObjs[i].registroObj + '">';
            fila += '      <ul>';
            if (lstInds.length > 0) {
                for (var j = 0; j < lstInds.length; j++) {
                    fila += '   <li id="ind-' + j + '" >';
                    fila += '       <a class="loadPrgInd">';
                    fila += (lstInds[j].nombreIndicador) ? lstInds[j].nombreIndicador : "-----";
                    fila += '       </a>';
                    fila += '   <div class="fltrt">';
                    fila += '       <button class="prgInd" title="Programar este indicador"></button>';
                    fila += '   </div>';
                    fila += '   </li>';
                }
            } else {
                fila += '      <li> Sin registros disponibles </li>';
            }
            fila += '       </ul>';
            fila += '   </div>';
        }
        fila += '   </div>';

        //  Agrego la fila creada a la tabla
        jQuery('#contenedor').html('');
        jQuery('#contenedor').html(fila);
        classAcordion();
    }

    function cargarAcordionPapp(anio)
    {
        var lstObjs = objLstObjetivo.lstObjetivos;

        var fila = '';
        fila += '   <div id="pappInd-' + anio + '">';
        for (var i = 0; i < lstObjs.length; i++) {
            fila += '   <h3>';
            fila += '       <a href="#"> ';
            fila += lstObjs[i].descObjetivo;
            fila += '       </a>';
            fila += '    </h3>';
            var lstInds = lstObjs[i].lstIndicadores;
            fila += '  <div id="indsObj-' + lstObjs[i].registroObj + '">';
            fila += '      <ul>';
            if (lstInds.length > 0) {
                for (var j = 0; j < lstInds.length; j++) {
                    fila += '   <li id="ind-' + j + '" >';
                    fila += '       <a class="loadPrgInd">';
                    fila += (lstInds[j].nombreIndicador) ? lstInds[j].nombreIndicador : "-----";
                    fila += '       </a>';
                    fila += '   </li>';
                }
            } else {
                fila += '      <li> Sin registros disponibles </li>';
            }
            fila += '       </ul>';
            fila += '   </div>';
        }
        fila += '   </div>';

        //  Agrego la fila creada a la tabla
        jQuery('#contenedor-'.anio).html('');
        jQuery('#contenedor-'.anio).html(fila);
        classAcordion();
    }

    function loadTableProgramacion(oInd, obj, ind)
    {
        jQuery('#tbPpppInd > tbody').empty();
        if (oInd.lstProgramacion.length > 0) {
            var lstMetasProgramacion = oInd.lstProgramacion[0].lstMetasProgramacion;
            var idReg = oInd.lstProgramacion[0].idReg;
            for (var i = 0; i < lstMetasProgramacion.length; i++) {
                var fila = addRowPrg(lstMetasProgramacion[i]);
                //  Agrego la fila creada a la tabla
                jQuery('#tbPpppInd > tbody:last').append(fila);
            }
            jQuery('#tbPpppInd').dataTable({
                "bRetrieve": true
            });
//            jQuery('#btnPrgInd').css('display', 'block');
//            jQuery('#tnsPrg').css('display', 'none');
            jQuery('#idObjetivoPrg').val(obj);
            jQuery('#idIndicadorPrg').val(ind);
            jQuery('#idRegPPPP').val(idReg);
        } else {
            jAlert("No existe programacion del indicador", "SIITA - ECORAE");
        }

    }

    function loadTablePapp(oInd, anio)
    {
        jQuery('#tbPappInd-' + anio + '> tbody').empty();
        var lstPrg = oInd.lstProgramacion;
        if (lstPrg.length > 0) {
            for (var i = 0; i < lstPrg.length; i++) {
                var numAnio = parseInt(lstPrg[i].fechaInicio.toString().split('-')[0]);
                //  "4": Id de tipo de plan (POA)
                if (lstPrg[i].idTipo == "4" && numAnio == anio) {
                    var lstMtaPrg = lstPrg[i].lstMetasProgramacion;
                    for (var j = 0; j < lstMtaPrg.length; j++) {
                        var fila = addRowPrg(lstMtaPrg[j]);
                        //  Agrego la fila creada a la tabla
                        jQuery('#tbPappInd-' + anio + '> tbody:last').append(fila);
                    }
                }
            }
        } else {
            jAlert("No existe programacion del indicador", "SIITA - ECORAE");
        }

    }

    function addRowPrg(programacion)
    {
        var idReg = programacion.idReg;
        var fila = '';
        fila += '<tr id="' + idReg + '">';
        fila += '     <td align="center">' + programacion.fecha + '</td>';
        fila += '     <td align="center">' + programacion.valor + '</td>';
        fila += ' </tr>';

        return fila;
    }

    function programarIndicador(indicador, idRegInd, idRegObj)
    {
        var result = true;
        var fInicio = jQuery("#jform_dteFechainicio_pi").val();
        var fFin = jQuery("#jform_dteFechafin_pi").val();

        var objInd = new Indicador();
        objInd.setDtaIndicador(indicador);
        var rst = objInd.generaProgramacion(fInicio, fFin);
        if (rst) {
            objLstObjetivo.lstObjetivos[idRegObj].lstIndicadores[idRegInd] = objInd;
        } else {
            result = false;
        }
        return result;
    }

});
