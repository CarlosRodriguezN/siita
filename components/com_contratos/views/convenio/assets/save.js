jQuery(document).ready(function() {
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'convenio.save':
            case 'convenio.saveExit':
                contratos["contratoGen"] = getDataGeneral();
                // la información del anticipo en el array;
                getDataAnticipo();

                var data = {
                    dataGeneral: contratos,
                    lstIndicadores: objGestionIndicador,
                    lstObjetivos: objLstObjetivo
                };

                var data2Save = JSON.stringify(list2Object(data));

                saveData(data2Save, task);
            break;

            case 'convenio.delete':
                jConfirm("¿Seguro desea eliminar el Convenio?", "SIITA - ECORAE", function(r) {
                    if (r) {
                        if (validateDelete()) {
                            Joomla.submitform(task);
                        } else {
                            var cadMess = '';
                            cadMess += '<table>';
                            cadMess += '    <tr align=center>';
                            cadMess += '        <td>Existen relaciones con el Contrato, que no le permiten ser eliminado.</td>';
                            cadMess += '    </tr>';
                            cadMess += '    <tr align=center>';
                            cadMess += '        <td>Por favor consulte con el Administrador</td>';
                            cadMess += '    </tr>';
                            cadMess += '</table>';

                            jAlert(cadMess, 'SIITA - ECORAE');
                        }
                    } else {

                    }
                });
                break;
            case 'convenio.organigrama':
                SqueezeBox.fromElement('index.php?option=com_reporte&view=organigrama&layout=edit&tmpl=component&task=preview', {size: {x: 1024, y: 500}, handler: "iframe"});
                break;
            case 'convenio.cancel':
                event.preventDefault();
                history.back();
                break;
            default:
                Joomla.submitform(task);
                break;
        }
    };
});

{ // preparando la data para guardar.

    /**
     * Forma un JSON
     * @param {type} list
     * @returns {unresolved}
     */
    function list2Object(list) {
        var obj = {};
        for (key in list) {
            if (typeof (list[key]) == 'object') {
                obj[key] = list2Object(list[key]);
            } else {
                obj[key] = list[key];
            }
        }
        return obj;
    }

    /**
     * Retoran aun array con la informacion general
     * @returns {Array}
     */
    function getDataGeneral() {
        var contratoGen = new Array();
        contratoGen["intIdContrato_ctr"] = jQuery('#jform_intIdContrato_ctr').val();
        contratoGen["intCodigo_pry"] = jQuery('#jform_intCodigo_pry').val();
        contratoGen["intIdTipoContrato_tc"] = jQuery('#jform_intIdTipoContrato_tc').val();
        contratoGen["intIdPartida_pda"] = jQuery('#jform_intIdPartida_pda').val();
        contratoGen["intIdFiscalizador_fc"] = jQuery('#jform_intIdFiscalizador_fc').val();
        contratoGen["strCodigoContrato_ctr"] = jQuery('#jform_strCodigoContrato_ctr').val();
        contratoGen["strCUR_ctr"] = jQuery('#jform_strCUR_ctr').val();
        contratoGen["dcmMonto_ctr"] = jQuery('#jform_dcmMonto_ctr').val();
        contratoGen["intNumContrato_ctr"] = jQuery('#jform_intNumContrato_ctr').val();
        contratoGen["strDescripcion_ctr"] = jQuery('#jform_strDescripcion_ctr').val();
        contratoGen["strObservacion_ctr"] = jQuery('#jform_strObservacion_ctr').val();
        contratoGen["intIdentidad_ent"] = jQuery('#jform_intIdentidad_ent').val();
        contratoGen["dteFechaInicio_ctr"] = jQuery('#jform_dteFechaInicio_ctr').val();
        contratoGen["dteFechaFin_ctr"] = jQuery('#jform_dteFechaFin_ctr').val();
        contratoGen["intPlazo_ctr"] = jQuery('#jform_intPlazo_ctr').val();
        contratoGen["intcodigo_unimed"] = jQuery('#jform_intcodigo_unimed').val();
        // CAMPOS DE LA UNIDAD DE GESTION RESPONSABLE
        contratoGen["idUGR"] = jQuery('#jform_intIdUndGestion').val();
        contratoGen["fchIniciUGR"] = jQuery('#jform_fchInicioPeriodoUG').val();

        // CAMPOS DEL FUNCIONARIO RESPONSABLE
        contratoGen["idResponsable"] = jQuery('#jform_idResponsable').val();
        contratoGen["fchIniciRes"] = jQuery('#jform_fchInicioPeriodoFuncionario').val();
        // CAMPO URL TABLEU
        contratoGen["urlTableU"] = jQuery('#jform_strURLtableU').val();

        contratoGen["published"] = jQuery('#jform_published').val();

        return contratoGen;
    }

    /**
     * Agrega el Anticipo al contratrato.
     * @returns {undefined}
     */
    function getDataAnticipo() {
        var dataAnticipo = new Array();
        dataAnticipo["codPago"] = jQuery("#jform_intCodPago_pgo_adl").val();
        dataAnticipo["cur"] = jQuery("#jform_strCUR_pgo_adl").val();
        dataAnticipo["monto"] = jQuery("#jform_dcmMonto_pgo_adl").val();
        dataAnticipo["idTipoPago"] = 1;
        dataAnticipo["documento"] = jQuery("#jform_strDocumento_pgo_adl").val();
        dataAnticipo["idPago"] = contratos.anticipo.idPago;
        dataAnticipo["factura"] = contratos.anticipo.factura;
        var anticipo = new Pago(dataAnticipo);
        anticipo.idFacturaPago = contratos.anticipo.idFacturaPago;
        anticipo.factura = contratos.anticipo.factura;
        contratos.anticipo = anticipo;
    }

    /**
     * valida si el programa tiene elementos relacionados.
     * @returns {Boolean}
     */
    function validateDelete() {

        var flag = true;
        for (var j = 0; j < contratos.lstAtributos.length; j++) {
            if (contratos.lstAtributos[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstContratistas.length; j++) {
            if (contratos.lstContratistas[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstFacturas.length; j++) {
            if (contratos.lstFacturas[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstFiscalizadores.length; j++) {
            if (contratos.lstFiscalizadores[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstGarantias.length; j++) {
            if (contratos.lstGarantias[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstGraficos.length; j++) {
            if (contratos.lstGraficos[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstMultas.length; j++) {
            if (contratos.lstMultas[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstPagos.length; j++) {
            if (contratos.lstPagos[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstPlanesPagos.length; j++) {
            if (contratos.lstPlanesPagos[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstProrrogas.length; j++) {
            if (contratos.lstProrrogas[j].published == 1) {
                flag = false;
            }
        }
        for (var j = 0; j < contratos.lstUnidadesTerritoriales.length; j++) {
            if (contratos.lstUnidadesTerritoriales[j].published == 1) {
                flag = false;
            }
        }

        return  flag;
    }

    function saveData( dataContrato, task ) {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaObjetivos = JSON.stringify(objLstObjetivo);
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        jQuery.ajax({
            type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'saveDataContrato',
                option: 'com_contratos',
                view: 'convenio',
                tmpl: 'component',
                format: 'json',
                dtaObjetivos: dtaObjetivos,
                dataSaveContrato: dataContrato,
                idEstadoEntidad: jQuery( '#jform_idEstadoEntidad' ).val()
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var idContrato = data.responseText;
            saveDocumentos( idContrato, task );
        });
    }
    
}// preparando la data para guardar.





