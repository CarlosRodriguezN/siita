function PagoFactura(data) {

    if (data == null) {
        data = {
            "idFacturaPago": 0,
            "idPago": 0,
            "idTipoPago": 0,
            "codPago": 0,
            "cur": 0,
            "monto": 0,
            "documento": ""
        }
    }
    this.idFacturaPago = (data.idFacturaPago) ? data.idFacturaPago : "0";
    this.idPago = (data.idPago) ? data.idPago : "0";
    this.idTipoPago = (data.idTipoPago) ? data.idTipoPago : "0";
    this.codPago = (data.codPago) ? data.codPago : "0";
    this.cur = (data.cur) ? data.cur : "";
    this.monto = (data.monto) ? data.monto : "0.00";
    this.documento = (data.documento) ? data.documento : "";
}


jQuery(document).ready(function() {
    jQuery(".pagarFactura").live("click", function() {
        hideForms();
        jQuery('#ieavFactura').css("display", "none");
        jQuery('#pagarFacturaForm').css("display", "block");
        regFactura = parseInt(this.parentNode.parentNode.id);
        var data = getFacturaByRegistro(regFactura);
        jQuery("#jform_numFacturaPago").val(data.numFactura);
        recorrerCombo(jQuery("#jform_inpCodigo_tp option"), data.pagoFactura.idTipoPago);
        jQuery("#jform_intCodPago_pgo").val(data.pagoFactura.codPago);
        jQuery("#jform_strCUR_pgo").val(data.pagoFactura.cur);
        jQuery('#jform_dcmMonto_pgo' ).attr( 'value', formatNumber( data.pagoFactura.monto, '$' ) );
        jQuery("#jform_strDocumento_pgo").val(data.pagoFactura.documento);
    });



    jQuery("#addPagoFactura").click(function() {
        var data = new Array();

        data["idTipoPago"] = jQuery("#jform_inpCodigo_tp").val();
        data["codPago"] = jQuery("#jform_intCodPago_pgo").val();
        data["cur"] = jQuery("#jform_strCUR_pgo").val();
        data["monto"] = parseFloat(jQuery("#jform_dcmMonto_pgo").val()).toFixed(2);
        data["documento"] = jQuery("#jform_strDocumento_pgo").val();
        if (validarCampFacturaPago(data)) {
            if (regFactura == 0) {
                data["idFacturaPago"] = 0;
                data["idPago"] = 0;
                clCmpFacturaPago();
                var oPagoFactura = new PagoFactura(data);
                contratos.lstFacturas[regFactura].pagoFactura = oPagoFactura;
            }
            else {
                data.regFactura = regFactura;
                actFacturaPago(regFactura, data);
                reloadFacturaTable();
                regFactura = 0;
            }
            hideForms();
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }
    });
});
/**
 * 
 * @param {type} data
 * @returns {Boolean}
 */
function validarCampFacturaPago(data) {
    if (data["idTipoPago"] != 0 &&
            data["codPago"] != "" &&
            data["cur"] != "" &&
            data["documento"] != "" &&
            data["monto"] != ""
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
function clCmpFacturaPago() {
    resetValidateForm( "#pagarFacturaForm" );
    recorrerCombo(jQuery("#jform_inpCodigo_tp option"), 0);
    jQuery("#jform_intCodPago_pgo").val("");
    jQuery("#jform_strCUR_pgo").val("");
    jQuery("#jform_dcmMonto_pgo").val("");
    jQuery("#documento").val("");
}
/**
 * 
 * @param {type} regFactura
 * @param {type} data
 * @returns {undefined}
 */
function actFacturaPago(regFactura, data) {
    for (var j = 0; j < contratos.lstFacturas.length; j++) {
        if (contratos.lstFacturas[j].regFactura == regFactura) {
            contratos.lstFacturas[j].pagoFactura.codPago = data.codPago;
            contratos.lstFacturas[j].pagoFactura.monto = data.monto;
            contratos.lstFacturas[j].pagoFactura.documento = data.documento;
            contratos.lstFacturas[j].pagoFactura.idTipoPago = data.idTipoPago;
            contratos.lstFacturas[j].pagoFactura.cur = data.cur;
        }
    }
    clCmpFacturaPago();
}