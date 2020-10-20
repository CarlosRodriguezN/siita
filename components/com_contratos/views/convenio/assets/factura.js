// clase factura.
function Factura(data) {
    if (data == null) {
        var data = {"idFactura  ": 0,
            "codFactura": "",
            "fchFactura": "",
            "numFactura": "",
            "monto": 0,
            "pagoFactura": null,
            "planilla": null,
            "published": "1"};
    }
    this.idFactura = (data.idFactura) ? data.idFactura : 0;
    this.codFactura = (data.codFactura) ? data.codFactura : "";
    this.fchFactura = (data.fchFactura) ? data.fchFactura : "";
    this.numFactura = (data.numFactura) ? data.numFactura : "";
    this.monto = ((parseFloat(data.monto))).toFixed(2) ? (parseFloat(data.monto)).toFixed(2) : "";
    this.pagoFactura = new PagoFactura((data.pagoFactura) ? data.pagoFactura : null);
    this.planilla = new Planilla((data.planilla) ? data.planilla : null);
    this.published = (data.published) ? data.published : 1;
}

var regFactura = 0;
jQuery(document).ready(function() {
    /**
     * evento guardar una nueva factura
     */
    jQuery('#addFactura').click(function() {
        var data = new Array();
        data["codFactura"] = jQuery("#jform_intCodFactura_fac").val();
        data["fchFactura"] = jQuery("#jform_dteFechaFactura_fac").val();
        data["monto"] = (parseFloat(jQuery("#jform_dcmMonto_fac").val())).toFixed(2);
        data["numFactura"] = jQuery("#jform_strNumero_fac").val();
        if (validarCampFactura(data)) {
            if (regFactura == 0) {
                data["idFactura"] = 0;
                data["published"] = 1;
                clCmpFactura();
                var oFactura = new Factura(data);
                oFactura.regFactura = contratos.lstFacturas.length + 1;
                addFactura(oFactura);
                contratos.lstFacturas.push(oFactura);
            }
            else {
                data.regFactura = regFactura;
                actFactura(data);
                regFactura = 0;
            }
            jQuery('#editFacturaForm').css("display", "none");
            jQuery('#ieavFactura').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }
    });
    /**
     * evento editar una factura
     */
    jQuery('.editFactura').live("click", function() {
        hideForms();
        jQuery('#ieavFactura').css("display", "none");
        jQuery('#editFacturaForm').css("display", "block");
        jQuery('#addPlanillaFacturaFactura').css("display", "block");
        regFactura = parseInt(this.parentNode.parentNode.id);
        //  recupero la data del array
        var data = getFacturaByRegistro(regFactura);

        jQuery("#jform_intCodFactura_fac").val(data.codFactura);
        jQuery("#jform_dteFechaFactura_fac").val(data.fchFactura);
        jQuery("#jform_dcmMonto_fac").val(data.monto);
        jQuery("#jform_strNumero_fac").val(data.numFactura);

    });
    /*
     * evento eliminar una factura
     */
    jQuery('.delFactura').live("click", function() {
        var regFacturaDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmFactura(regFacturaDel);
                reloadFacturaTable();
            } else {

            }
        });
    });
    /**
     * avento agregar una nueva factura
     */
    jQuery("#addFacturaTable").click(function() {
        hideForms();
        regFactura = 0;
        jQuery('#ieavFactura').css("display", "none");
        jQuery('#editFacturaForm').css("display", "block");
    });
    /**
     * evento cancelar el ingreso de una factura
     */
    jQuery("#cancelarFactura").click(function() {
        clCmpFactura();
        hideForms();
    });
    /**
     * evento cancelar el ingreso de un pago de una factura.
     */
    jQuery("#cancelarPagoFactura").click(function() {
        clCmpFactura();
        hideForms();
    });
});
/**
 * calidad los campos de una factura
 * @param {type} data array con los campos del formulario
 * @returns {Boolean} true, si todos los campos han sido ingresados
 */
function validarCampFactura(data) {
    if (data["codFactura"] != "" &&
            data["fchFactura"] != "" &&
            data["monto"] != "" &&
            data["numFactura"] != ""
            ) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * @description Agrega una fila a la tabla de facturas
 * @param {array} data
 * @returns {undefined}
 */
function addFactura(data) {
    var cadPago = "Pagar";
    //  var classPago = "pagarFactura"
    if (data.pagoFactura.cur) {
        if (data.pagoFactura.monto == data.monto) {
            cadPago = "Cancelada";
            //    classPago = "facturaPagada";
        }
    }
    var row = '';
    row += '<tr id="' + data.regFactura + '">';
    row += ' <td>' + data.numFactura + ' </td>';
    row += ' <td>' + data.codFactura + ' </td>';
    row += ' <td> $ ' + data.monto + ' </td>';
    row += ' <td>' + data.fchFactura + ' </td>';
    row += ' <td style="width: 15px"><a class="pagarFactura" >' + cadPago + '</a></td>';
    row += ' <td style="width: 15px"><a class="editFactura" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delFactura" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLtsFacturasContrato > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpFactura() {
    jQuery("#jform_intCodFactura_fac").val("");
    jQuery("#jform_dteFechaFactura_fac").val("");
    jQuery("#jform_dcmMonto_fac").val("");
    jQuery("#jform_strNumero_fac").val("");
}
/**
 * Elimina logiacamente una factura.
 * @param {type} idRegFactura
 * @returns {undefined}
 */
function elmFactura(idRegFactura) {
    for (var j = 0; j < contratos.lstFacturas.length; j++) {
        if (contratos.lstFacturas[j].regFactura == idRegFactura) {
            contratos.lstFacturas[j].published = 0;
        }
    }
}
/**
 * acturliza una factura
 * @param {type} data
 * @returns {undefined}
 */
function actFactura(data) {
    for (var j = 0; j < contratos.lstFacturas.length; j++) {
        if (contratos.lstFacturas[j].regFactura == data.regFactura) {
            contratos.lstFacturas[j].monto = data.monto;
            contratos.lstFacturas[j].codFactura = data.codFactura;
            contratos.lstFacturas[j].fchFactura = data.fchFactura;
            contratos.lstFacturas[j].numFactura = data.numFactura;
            contratos.lstFacturas[j].codFactura = data.codFactura;
            contratos.lstFacturas[j].published = 1;
        }
    }
    clCmpFactura();
    reloadFacturaTable();
}
/**
 * carga la tabla de facturas.
 * @returns {undefined}
 */
function reloadFacturaTable() {
    jQuery("#tbLtsFacturasContrato > tbody").empty();
    for (var j = 0; j < contratos.lstFacturas.length; j++) {
        if (contratos.lstFacturas[j].published == 1)
            addFactura(contratos.lstFacturas[j]);
    }
}
/**
 * oculta los formularios.
 * @returns {undefined}
 */
function hideForms() {
    clCmpFactura();
    clCmpFacturaPago();
    jQuery('#ieavFactura').css("display", "block");
    jQuery('#pagarFacturaForm').css("display", "none");
    jQuery('#editFacturaForm').css("display", "none");
    jQuery('#pagarPlanillaFacturaForm').css("display", "none");
}
/**
 * 
 * @param {type} numRegistro
 * @returns {undefined}
 */
function getFacturaByRegistro(numRegistro) {
    var factura = null;
    for (var j = 0; j < contratos.lstFacturas.length; j++) {
        if (contratos.lstFacturas[j].regFactura == numRegistro) {
            factura = contratos.lstFacturas[j];
        }
    }
    return factura;
}
