
// clase pago
function PlanPago(data) {
    this.idPlanPago = (data.idPlanPago) ? data.idPlanPago : 0;
    this.codPlanPago = (data.codPlanPago) ? data.codPlanPago : "";
    this.producto = (data.producto) ? data.producto : "";
    this.monto = ((parseFloat(data.monto))).toFixed(2) ? (parseFloat(data.monto)).toFixed(2) : "";
    this.porcentaje = (data.porcentaje) ? data.porcentaje : "";
    this.fecha = (data.fecha) ? data.fecha : "";
    this.regPlanPago = (data.regPlanPago) ? data.regPlanPago : "";
    this.published = (data.published) ? data.published : "";
}


var regPlanPago = 0;
jQuery(document).ready(function() {
    jQuery('#addPlanPagos').click(function() {
        var data = new Array();
        data["codPlanPago"] = jQuery("#jform_intCodPlanPago_pgo").val();
        data["producto"] = jQuery("#jform_strPlanProducto_pgo").val();
        data["monto"] = jQuery("#jform_dcmPlanPagoMonto_pgo").val();
        data["porcentaje"] = jQuery("#jform_inpPorCiento_pgo").val();
        data["fecha"] = jQuery("#jform_dteFechaPago_pgo").val();

        if (validarCampPlanPago(data)) {
            if (regPlanPago == 0) {
                data["idPlanPago"] = 0;
                data["published"] = 1;
                data["regPlanPago"] = contratos.lstPlanesPagos.length + 1;
                clCmpPlanPago();
                var oPlanPago = new PlanPago(data);
                addPlanPago(data);
                contratos.lstPlanesPagos.push(oPlanPago);
            }
            else {
                data.regPlanPago = regPlanPago;
                actPlanPago(data);
                regPlanPago = 0;
            }
            jQuery('#editPlanPagosForm').css("display", "none");
            jQuery('#imgPlanPagosForm').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }

    });
    jQuery('.editPlanPago').live("click", function() {
        jQuery('#imgPlanPagosForm').css("display", "none");
        jQuery('#editPlanPagosForm').css("display", "block");
        regPlanPago = parseInt(this.parentNode.parentNode.id);
        var data;
        for (var j = 0; j < contratos.lstPlanesPagos.length; j++) {
            if (contratos.lstPlanesPagos[j].regPlanPago == regPlanPago) {
                data = contratos.lstPlanesPagos[j];
            }
        }
        jQuery("#jform_intCodPlanPago_pgo").val(data.codPlanPago);
        jQuery("#jform_strPlanProducto_pgo").val(data.producto);
        jQuery("#jform_dcmPlanPagoMonto_pgo").val(data.monto);
        jQuery("#jform_inpPorCiento_pgo").val(data.porcentaje);
        jQuery("#jform_dteFechaPago_pgo").val(data.fecha);

    });

    jQuery('.delPlanPago').live("click", function() {
        var regPlanPagoDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmPlanPago(regPlanPagoDel);
                reloadPlanPagoTable();
            } else {

            }
        });
    });
    jQuery("#addPlanPagosTable").click(function() {
        clCmpPlanPago();
        regPlanPago = 0;
        jQuery('#imgPlanPagosForm').css("display", "none");
        jQuery('#editPlanPagosForm').css("display", "block");
    });
    jQuery("#cancelPlanPagos").click(function() {
        clCmpPlanPago();
        jQuery('#editPlanPagosForm').css("display", "none");
        jQuery('#imgPlanPagosForm').css("display", "block");
    });
});

function validarCampPlanPago(data) {
    if (data["codPlanPago"] != "" &&
            data["producto"] != "" &&
            data["monto"] != "" &&
            data["porcentaje"] != "" &&
            data["fecha"] != ""
            ) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * @description Agrega una fila a la tabla de prorrogas
 * @param {array} data
 * @returns {undefined}
 */
function addPlanPago(data) {
    var row = '';
    row += '<tr id="' + data.regPlanPago + '">';
    row += ' <td>' + data.codPlanPago + ' </td>';
    row += ' <td>' + data.producto + ' </td>';
    row += ' <td>$ ' + ((parseFloat(data.monto))).toFixed(2) + ' </td>';
    row += ' <td>' + data.fecha + '</td>';
    row += ' <td>' + data.porcentaje + ' %</td>';
    row += ' <td style="width: 15px"><a class="editPlanPago" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delPlanPago" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tblstPlanPagos > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpPlanPago() {
    jQuery("#jform_intCodPlanPago_pgo").val("");
    jQuery("#jform_strPlanProducto_pgo").val("");
    jQuery("#jform_dcmPlanPagoMonto_pgo").val("");
    jQuery("#jform_inpPorCiento_pgo").val("");
    jQuery("#jform_dteFechaPago_pgo").val("");
}
/**
 * 
 * @param {type} idRegPlanPago
 * @returns {undefined}
 */
function elmPlanPago(regPlanPagoDel) {
    for (var j = 0; j < contratos.lstPlanesPagos.length; j++) {
        if (contratos.lstPlanesPagos[j].regPlanPago == regPlanPagoDel) {
            contratos.lstPlanesPagos[j].published = 0;
        }
    }
}

function actPlanPago(data) {
    for (var j = 0; j < contratos.lstPlanesPagos.length; j++) {
        if (contratos.lstPlanesPagos[j].regPlanPago == data.regPlanPago) {
            contratos.lstPlanesPagos[j].codPlanPago = data.codPlanPago;
            contratos.lstPlanesPagos[j].fecha = data.fecha;
            contratos.lstPlanesPagos[j].monto = data.monto;
            contratos.lstPlanesPagos[j].porcentaje = data.porcentaje;
            contratos.lstPlanesPagos[j].producto = data.producto;
            contratos.lstPlanesPagos[j].published = 1;
        }
    }
    clCmpPlanPago();
    reloadPlanPagoTable();
}

function reloadPlanPagoTable() {
    jQuery("#tblstPlanPagos > tbody").empty();
    for (var j = 0; j < contratos.lstPlanesPagos.length; j++) {
        if (contratos.lstPlanesPagos[j].published == 1)
            addPlanPago(contratos.lstPlanesPagos[j]);
    }
}

