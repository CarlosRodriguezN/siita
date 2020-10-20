var idAtributo = 0;

jQuery(document).ready(function() {
    jQuery("#jform_intCodigo_pry").css({width: "280px"});

    /**
     * @description agregar un atributo
     */
    jQuery('#addAritbuto').click(function() {
        var data = new Array();

        data["codAtributo"] = jQuery("#jform_attrCodigo").val();
        data["nombre"] = jQuery("#jform_attrNombre").val();
        data["valor"] = jQuery("#jform_attrValor").val();

        if (data["nombre"] != '' && data["codAtributo "] != "" && data["valor"] != "") {
            if (idAtributo == 0) {
                data["idAtributo"] = 'n-' + contratos.lstAtributos.length;
                data["published"] = 1;
                addAttributo(data);
                clCmpAtributo();
                contratos.lstAtributos.push(data);
            }
            else {
                data["idAtributo"] = idAtributo;
                actAtributo(data);
                idAtributo = 0;
            }
            jQuery('#editAtributosForm').css("display", "none");
            jQuery('#imagenEcuAmaVidaAtributos').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }

    });

    jQuery('.editAttributo').live("click", function() {
        idAtributo = this.parentNode.parentNode.id;
        //  recupero la data del array
        jQuery('#imagenEcuAmaVidaAtributos').css("display", "none");
        jQuery('#editAtributosForm').css("display", "block");
        var data = false;
        for (var j = 0; j < contratos.lstAtributos.length; j++) {
            if (contratos.lstAtributos[j].idAtributo == idAtributo) {
                data = contratos.lstAtributos[j];
            }
        }
        //  muestro en el formulario
        if (data) {
            jQuery("#jform_attrCodigo").val(data.codAtributo);
            jQuery("#jform_attrNombre").val(data.nombre);
            jQuery("#jform_attrValor").val(parseFloat(data.valor).toFixed(2));
        }
    });

    jQuery('.delAttributo').live("click", function() {
        idAtributo = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmAtributo(idAtributo);
                reloadAtributosTable();
            } else {

            }
        });
    });

    /**
     * click en agregar de la tabla
     */
    jQuery('#addAritbutoTable').click(function() {
        jQuery('#imagenEcuAmaVidaAtributos').css("display", "none");
        jQuery('#editAtributosForm').css("display", "block");
        idAtributo = 0;
        clCmpAtributo();
    });
    /**
     * click cancelar los cambios
     */
    jQuery('#cancelarAtributo').click(function() {
        jQuery('#editAtributosForm').css("display", "none");
        jQuery('#imagenEcuAmaVidaAtributos').css("display", "block");
        idAtributo = 0;
        clCmpAtributo();
    });


});




/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addAttributo(data) {
    var row = '';
    row += '<tr id="' + data.idAtributo + '">';
    row += ' <td>' + data.codAtributo + ' </td>';
    row += ' <td>' + data.nombre + ' </td>';
    row += ' <td> $ ' + parseFloat(data.valor).toFixed(2) + ' </td>';
    row += ' <td style="width: 15px"><a class="editAttributo" >Editar</a></td>';
    row += ' <td style="width: 15px"><a class="delAttributo" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLtsAtribsContrato > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpAtributo() {
    jQuery("#jform_attrCodigo").val("");
    jQuery("#jform_attrNombre").val("");
    jQuery("#jform_attrValor").val("");
}

/**
 * elimina d ela lista un Artibuto del contrato
 * @param {type} idAtributo
 * @returns {undefined}
 */
function elmAtributo(idAtributo) {
    for (var j = 0; j < contratos.lstAtributos.length; j++) {
        if (contratos.lstAtributos[j].idAtributo == idAtributo) {
            contratos.lstAtributos[j].published = 0;
        }
    }
}
function actAtributo(data) {
    for (var j = 0; j < contratos.lstAtributos.length; j++) {
        if (contratos.lstAtributos[j].idAtributo == data.idAtributo) {
            contratos.lstAtributos[j].codAtributo = data.codAtributo;
            contratos.lstAtributos[j].nombre = data.nombre;
            contratos.lstAtributos[j].valor = data.valor;
        }
    }
    clCmpAtributo();
    reloadAtributosTable();
}
function reloadAtributosTable() {
    jQuery("#tbLtsAtribsContrato > tbody").empty();
    for (var j = 0; j < contratos.lstAtributos.length; j++) {
        if (contratos.lstAtributos[j].published == 1)
            addAttributo(contratos.lstAtributos[j]);
    }
}

/**
 * carga el array como un objeto
 * @param {type} data
 * @returns {Contratos}
 */
function Contratos(data) {
    if (data) {
        this.lstAtributos = (data.lstAtributos) ? data.lstAtributos : new Array();
        this.lstContratistas = (data.lstContratistas) ? data.lstContratistas : new Array();
        this.lstFiscalizadores = (data.lstFiscalizadores) ? data.lstFiscalizadores : new Array();
        this.lstGarantias = (data.lstGarantias) ? data.lstGarantias : new Array();
        this.lstMultas = (data.lstMultas) ? data.lstMultas : new Array();
        this.lstProrrogas = (data.lstProrrogas) ? data.lstProrrogas : new Array();
        this.lstFacturas = (data.lstFacturas) ? data.lstFacturas : new Array();
        this.lstPagos = (data.lstPagos) ? data.lstPagos : new Array();
        this.lstPlanesPagos = (data.lstPlanesPagos) ? data.lstPlanesPagos : new Array();
        this.anticipo = (data.anticipo) ? data.anticipo : new Object();
        this.lstGraficos = (data.lstGraficos) ? data.lstGraficos : new Array();
        this.lstUnidadesTerritoriales = (data.lstUnidadesTerritoriales) ? data.lstUnidadesTerritoriales : new Array();
    }
}
