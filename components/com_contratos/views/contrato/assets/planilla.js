function Planilla(data) {
    if (data == null) {
        data = {"idPlanilla": 0,
            //    "idFactura": 0,
            "codPlanilla": 0,
            "monto": 0,
            "mes": 0,
            "fchEntrega": 0
        };
    }
    this.idPlanilla = (data.idPlanilla) ? data.idPlanilla : 0;
    //  this.idFactura = (data.idFactura) ? data.idFactura : 0;
    this.codPlanilla = (data.codPlanilla) ? data.codPlanilla : 0;
    this.monto = ((parseFloat(data.monto))).toFixed(2) ? (parseFloat(data.monto)).toFixed(2) : "";
    this.mes = (data.mes) ? data.mes : 0;
    this.fchEntrega = (data.fchEntrega) ? data.fchEntrega : 0;
}


jQuery(document).ready(function() {
    jQuery("#addPlanillaFacturaFactura").click(function() {
//        hideForms();
//        jQuery('#ieavFactura').css("display", "none");
//        jQuery('#pagarFacturaForm').css("display", "block");
        var data = getFacturaByRegistro(regFactura);
        jQuery("#jform_intCodPlantilla_ptlla").val(data.planilla.codPlanilla);
        jQuery("#jform_dcmMonto_ptlla").val(data.planilla.monto);
        recorrerCombo(jQuery("#jform_inpMes_ptlla option"), data.planilla.mes);
        jQuery("#jform_dteFechaEntrega_ptlla").val(data.planilla.fchEntrega);
    });


    jQuery("#addPlanillaFactura").click(function() {
        var data = new Array();
        data["codPlanilla"] = jQuery("#jform_intCodPlantilla_ptlla").val();
        data["monto"] = jQuery("#jform_dcmMonto_ptlla").val();
        data["mes"] = jQuery("#jform_inpMes_ptlla").val();
        data["fchEntrega"] = jQuery("#jform_dteFechaEntrega_ptlla").val();
        if (validarCampFacturaPlanilla(data)) {
            if (regFactura == 0) {
//                data["codPlanilla"] = 0;
//                clCmpPlanillaPago();
//                var oPagoFactura = new PagoFactura(data);
//                contratos.lstFacturas[regFactura].planilla = oPagoFactura;
            }
            else {
                actPlanillaPago(regFactura, data);
                clCmpPlanillaPago();
                jQuery('#pagarPlanillaFacturaForm').css("display", "none");
            }

        } else {
            jAlert("Todo los campos son necesarios", "SIITA - ECORAE");
        }
    });

    jQuery("#addPlanillaFacturaFactura").click(function() {
        jQuery('#pagarPlanillaFacturaForm').css("display", "block");
    });
    jQuery("#cancelarPlanillaFactura").click(function() {
        jQuery('#pagarPlanillaFacturaForm').css("display", "none");
    });

});
/**
 * 
 * @param {type} data
 * @returns {Boolean}
 */
function validarCampFacturaPlanilla(data) {
    if (
            data["codPlanilla"] != "" &&
            data["monto"] != "" &&
            data["mes"] != 0 &&
            data["fchEntrega"] != ""
            ) {
        return true;
    }
    else {
        return false;
    }
}
/**
 * 
 * @returns {undefined}
 */
function clCmpPlanillaPago() {
    jQuery("#jform_intCodPlantilla_ptlla").val();
    jQuery("#jform_dcmMonto_ptlla").val();
    jQuery("#jform_inpMes_ptlla").val();
    jQuery("#jform_dteFechaEntrega_ptlla").val();
}
/**
 * 
 * @param {type} regFactura
 * @param {type} data
 * @returns {undefined}
 */
function actPlanillaPago(regFactura, data) {
    for (var j = 0; j < contratos.lstFacturas.length; j++) {
        if (contratos.lstFacturas[j].regFactura == regFactura) {
            contratos.lstFacturas[j].planilla.codPlanilla = data.codPlanilla;
            contratos.lstFacturas[j].planilla.fchEntrega = data.fchEntrega;
            contratos.lstFacturas[j].planilla.mes = data.mes;
            //contratos.lstFacturas[j].planilla.idPlanilla = data.idPlanilla;
            contratos.lstFacturas[j].planilla.monto = ((parseFloat(data.monto))).toFixed(2) ? (parseFloat(data.monto)).toFixed(2) : 0;
        }
    }
    clCmpPlanillaPago();
}