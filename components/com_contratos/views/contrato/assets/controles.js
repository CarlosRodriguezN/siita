jQuery(document).ready(function() {
    //pestaña datos generales

    // CUR nuemros y letras.
    jQuery('#jform_strCUR_ctr').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });
    // CUR
    jQuery('#jform_intPlazo_ctr').keypress(function(e) {
        var tecla = e.which;
        if (tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // Monto numeros y "."
    jQuery('#jform_dcmMonto_ctr').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // NUMERO DE FACTURA numeros 
    jQuery('#jform_intNumContrato_ctr').keypress(function(e) {
        var tecla = e.which;
        if (tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    //codigo de la garantia solo numeros
    jQuery('#jform_intCodGarantia_gta').keypress(function(e) {
        var tecla = e.which;
        if (tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // Plazo solo numeros
    jQuery('#jform_intPlazo_ctr').keypress(function(e) {
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // monto de la garantia solo numeros.
    jQuery('#jform_dcmMonto_gta').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // CODIGO DE LA MULTA de la MUlta solo numeros.
    jQuery('#jform_intCodMulta_mta').keypress(function(e) {
        var tecla = e.which;
        if (tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // monto de la MUlta solo numeros.
    jQuery('#jform_dcmMonto_mta').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // monto de la MUlta solo numeros.
    jQuery('#jform_dcmPlanPagoMonto_pgo').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // Codigo de plan de pagos solo numeros.
    jQuery('#jform_intCodPlanPago_pgo').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // monto de la MUlta solo numeros.
    jQuery('#jform_inpPorCiento_pgo').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // NUmero de la factura de la MUlta solo numeros.
    jQuery('#jform_strNumero_fac').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    // Monto de la factura numeros y "."
    jQuery('#jform_dcmMonto_fac').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // Monto de la planilla numeros y "."
    jQuery('#jform_dcmMonto_ptlla').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // Monto de la adelanto numeros y "."
    jQuery('#jform_dcmMonto_pgo_adl').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
    // Monto de la adelanto numeros y "."
    jQuery('#jform_attrValor').keypress(function(e) {
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });
});

