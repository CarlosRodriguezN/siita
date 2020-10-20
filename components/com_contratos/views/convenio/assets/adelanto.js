/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function() {
    if (contratos && contratos.anticipo) {
        jQuery("#jform_intCodPago_pgo_adl").val(contratos.anticipo.codPago);
        jQuery("#jform_strCUR_pgo_adl").val(contratos.anticipo.cur);
        jQuery("#jform_strDocumento_pgo_adl").val(contratos.anticipo.documento);
        var monto=(parseFloat(contratos.anticipo.monto)).toFixed(2);
        jQuery("#jform_dcmMonto_pgo_adl").val(monto);
        recorrerCombo(jQuery("#jform_inpCodigo_tp_adl"), 1);
    }
    jQuery("#addFacturaAdelantoContrato").click(function() {
        var data = contratos.anticipo.factura;
        jQuery("#jform_strNumero_fac_adl").val(data.numFactura);
        jQuery("#jform_intCodFactura_fac_adl").val(data.codFactura);
        var monto=(parseFloat(contratos.anticipo.monto)).toFixed(2);
        jQuery("#jform_dcmMonto_fac_adl").val(monto);
        jQuery("#jform_dteFechaFactura_fac_adl").val(data.fchFactura);
        jQuery("#editPlanPagoForm").css("display", "block");
        jQuery("#ieavAdelantoFactura").css("display", "none ");
    });


    jQuery("#addFacturaAdelanto").click(function() {
        var dataFacAdel = new Array();
        dataFacAdel["numFactura"] = jQuery("#jform_strNumero_fac_adl").val();
        dataFacAdel["codFactura"] = jQuery("#jform_intCodFactura_fac_adl").val();
        dataFacAdel["monto"] = jQuery("#jform_dcmMonto_fac_adl").val();
        dataFacAdel["fchFactura"] = jQuery("#jform_dteFechaFactura_fac_adl").val();
        dataFacAdel["idFactura"] = contratos.anticipo.factura.idFactura;
        var factura = new Factura(dataFacAdel);
        contratos.anticipo.factura = factura;
        clsFacturaPagoForm();
        jQuery("#editPlanPagoForm").css("display", "none");
        jQuery("#ieavAdelantoFactura").css("display", "block");

    });

    jQuery("#cancelFacturaAdelanto").click(function() {
        clsFacturaPagoForm();
        jQuery("#editPlanPagoForm").css("display", "none");
        jQuery("#ieavAdelantoFactura").css("display", "block");
    });

});

function clsFacturaPagoForm() {
    jQuery("#jform_strNumero_fac_adl").val("");
    jQuery("#jform_intCodFactura_fac_adl").val("");
    jQuery("#jform_dcmMonto_fac_adl").val("");
    jQuery("#jform_dteFechaFactura_fac_adl").val("");
}