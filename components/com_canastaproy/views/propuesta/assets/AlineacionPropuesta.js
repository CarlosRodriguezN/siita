var banMN = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {
    var nmn = lstAlineacionPropuesta.length;
    //  Habilita el formulario para agregar una nueva alineacion de PNBV
    jQuery("#addAlineacionesTable").click(function() {
        if (banMN != 0) {
            jConfirm("Se esta editando una Alineación, se eliminarán los cambios", "SIITA - ECORAE", function(result) {
                if (result) {
                    limpiarFrmAlineacion();
                    jQuery("#frmAlineacionPNBV").css("display", "block");
                    jQuery("#imgAlineacionPNBV").css("display", "none");

                    jQuery("#updPNBV-" + banMN).html("Editar");
                    banMN = 0;
                }
            });
        } else {
            jQuery("#frmAlineacionPNBV").css("display", "block");
            jQuery("#imgAlineacionPNBV").css("display", "none");
        }
    });

    //  Gestiona el agregar  una alineación
    jQuery('#btnAddRelacion').click(function() {
        var objetivo = jQuery('#jform_intCodigo_on');
        var politica = jQuery('#jform_intCodigo_pn');
        var meta = jQuery('#jform_idCodigo_mn');
        var idAlineacionPropuesta = jQuery('#jform_dataAliniacionProyectoCP');

        var banExiste = existeAlcance(objetivo, politica, meta, banMN);
        var banValido = validaAlcance(objetivo, politica, meta);

        //  verifico si el alcance no esta ya registrado 
        //  y la informacion de objetivos, politicas y metas 
        //  hayan sido seleccionadas
        if (banExiste == false && banValido == true) {
            var objetivoTxt = jQuery('#jform_intCodigo_on :selected');
            var politicaTxt = jQuery('#jform_intCodigo_pn :selected');
            var metaTxt = jQuery('#jform_idCodigo_mn :selected');
            //  controlo si se esta editando un registro o asi es uno nuevo
            if (banMN == 0) {
                //  Se construye el objeto de la alineación
                var lstRelacion = [];
                lstRelacion["idRegistro"] = ++nmn;
                lstRelacion["idMetaNacional"] = meta.val();
                lstRelacion["idPoliticaNacional"] = politica.val();
                lstRelacion["idObjNacional"] = objetivo.val();
                lstRelacion["idAlnPropPNBV"] = idAlineacionPropuesta.val();
                lstRelacion["published"] = 1;

                //  Agrego fila en la tabla alineacion
                addFilaAlineacion(nmn, objetivoTxt, politicaTxt, metaTxt);

                //  Agrego la data a la lista de alineaciones
                lstAlineacionPropuesta.push(lstRelacion);

                //  Regreso a su posicion inicial los combos del formulario
                limpiarFrmAlineacion();
            } else {
                //  Actualizo informacion editada
                updAlineacion(politica, meta);
            }
        } else {
            switch (true) {
                case(banExiste == true && banValido == true):
                    jAlert('Alineaci&oacute;n Existente', 'SIITA - ECORAE');
                    break;

                case(banExiste == false && banValido == false):
                    jAlert('Campos con asterisco, SON OBLIGATORIOS');
                    break;

                default:
                    jAlert(banExiste + '' + banValido);
                    break;
            }
        }
    });

    //  Limpio el formulario de alineaciones
    jQuery('#btnLimpiarRelacion').click(function() {
        if (banMN != 0) {
            jQuery("#updPNBV-" + banMN).html("Editar");
            limpiarFrmAlineacion();
            restaurarFrmAlineacion();
        } else {
            limpiarFrmAlineacion();
        }
    })

    //  Gestiono la alineacion de un proyecto
    jQuery('.updAlineacion').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        if (banMN != 0) {
            jConfirm("Se está editando una Alineación, se eliminara los cambios", "SIITA - ECORAE", function(result) {
                if (result) {
                    jQuery("#updPNBV-" + banMN).html("Editar");
                    updDataPNBV(idFila);
                }
            });
        } else {
            updDataPNBV(idFila);
        }
    });

    //  Gestiono la eliminacion de un registro
    jQuery('.delAlineacion').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        if (banMN != idFila) {
            //  Emito un mensaje de confirmacion antes de eliminar registro
            jConfirm("¿Est&aacute seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
                if (result) {
                    delPNBV(idFila);
                }
            });
        } else {
            jConfirm("La alineaci&oacute;n está siendo editada, desea eliminar", "SIITA - ECORAE", function(result) {
                if (result) {
                    delPNBV(idFila);
                    limpiarFrmAlineacion();
                    restaurarFrmAlineacion()
                }
            });
        }

    });
});

//
function delPNBV(idFila) {
    var numReg = lstAlineacionPropuesta.length;
    for (var x = 0; x < numReg; x++) {
        if (lstAlineacionPropuesta[x]["idRegistro"] == idFila) {

            //  Actualizo el estado del registro a cero
            lstAlineacionPropuesta[x]["published"] = 0;

            //  Elimino la fila de la tabla
            delFilaAlineacion(idFila);
        }
    }
}

//
function updDataPNBV(idFila) {

    jQuery("#frmAlineacionPNBV").css("display", "block");
    jQuery("#imgAlineacionPNBV").css("display", "none");
    jQuery("#updPNBV-" + idFila).html("Editando...");

    //  Actualizo bandera de edicion a 1
    banMN = idFila;

    //  Obtengo datos de alineacion de un proyecto
    var data = getDataAlineacion(idFila);

    if (data) {
        //  Recorro el combo de Objetivos
        recorrerCombo(jQuery('#jform_intCodigo_on option'), [data["idObjNacional"]]);

        //  Ejecuto ajax para Politicas y Metas
        jQuery('#jform_intCodigo_on').trigger('change', data["idPoliticaNacional"]);
        jQuery('#jform_intCodigo_pn').trigger('change', [data["idObjNacional"], data["idPoliticaNacional"], data["idMetaNacional"]]);

        //  Desabilito combo de Objetivos Nacionales  
        jQuery('#jform_intCodigo_on').attr('disabled', 'disabled');

        //  Registro en una variable temporal el identificador de la fila a editar
        jQuery('#idAlineacion').attr('value', idFila);
    }
}

//
function existeAlcance(objetivo, politica, meta, banMN)
{
    var numReg = lstAlineacionPropuesta.length;
    for (var x = 0; x < numReg; x++) {
        if (lstAlineacionPropuesta[x]["idObjNacional"] == objetivo.val() && lstAlineacionPropuesta[x]["idPoliticaNacional"] == politica.val() && lstAlineacionPropuesta[x]["idMetaNacional"] == meta.val() && lstAlineacionPropuesta[x]["idRegistro"] != banMN)
            return true;
    }

    return false;
}

function validaAlcance(objetivo, politica, meta)
{
    var dtaObj = objetivo.val();
    var dtaPolitica = politica.val();
    var dtaMeta = meta.val();

    if (dtaObj != '0' && dtaPolitica != '0' && dtaMeta != '0') {
        return true;
    }

    return false;
}

//  Agrega una fila a la tabla de alineaciones
function addFilaAlineacion(idRegistro, objetivo, politica, meta)
{
    //  Construyo la Fila
    var filaAlineacion = "  <tr id='" + idRegistro + "'>"
            + "         <td align='center'>" + objetivo.text() + "</td>"
            + "         <td align='center'>" + politica.text() + "</td>"
            + "         <td align='center'>" + meta.text() + "</td>";

    if( roles["core.create"] === true || roles["core.edit"] === true ){
        filaAlineacion  +="<td align='center' width='15' > <a class='updAlineacion'> Editar </a> </td>"
                        + "<td align='center' width='15' > <a class='delAlineacion'> Eliminar </a> </td>";
    }else{
        filaAlineacion  +="<td align='center' width='15' > Editar </td>"
                        + "<td align='center' width='15' > Eliminar </td>";
    }
    
    filaAlineacion += "</tr>";

    //  Agrego la fila creada a la tabla
    jQuery('#tbLstAlineacion > tbody:last').append(filaAlineacion);
}

//  Limpia formulario de alineacion de un proyecto
function limpiarFrmAlineacion()
{
    //  Recorro a la posicion inicial el combo Objetivos Nacionales
    recorrerCombo(jQuery('#jform_intCodigo_on option'), 0);

    //  Limpia contenido de los comboBox Politica  y Objetivo Nacional
    enCerarCombo(jQuery('#jform_intCodigo_pn option'));
    enCerarCombo(jQuery('#jform_idCodigo_mn option'));

    //  Ajusto el tamaño de los comboBox
    jQuery('#jform_intCodigo_pn').css('width', '400px');
    jQuery('#jform_idCodigo_mn').css('width', '400px');

    jQuery("#frmAlineacionPNBV").css("display", "none");
    jQuery("#imgAlineacionPNBV").css("display", "block");

}

function restaurarFrmAlineacion() {
    //  Desabilito combo de Objetivos Nacionales  
    jQuery('#jform_intCodigo_on').removeAttr('disabled');

    //  Cambio la bandera de edicion
    banMN = 0;
}

// Recorro el combo de provincias a una determinada posicion
function recorrerCombo(combo, posicion)
{
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}

//   Limpio el comboBox de la data anterior
function enCerarCombo(combo)
{
    //  Recorro contenido del combo
    jQuery(combo).each(function() {
        if (jQuery(this).val() > 0) {
            //  Actualizo contenido del combo
            jQuery(this).remove();
        }
    });
}

//  Obtego la data de una alineacion
function getDataAlineacion(idFila)
{
    var numReg = lstAlineacionPropuesta.length;

    for (var x = 0; x < numReg; x++) {
        if (lstAlineacionPropuesta[x]["idRegistro"] == idFila) {
            return lstAlineacionPropuesta[x];
        }
    }

    return false;
}

//  Actualiza la data en la lista de alineaciones
function updAlineacion(politica, meta)
{
    var numReg = lstAlineacionPropuesta.length;
    var idFilaAlineacion = jQuery('#idAlineacion').val();
    for (var x = 0; x < numReg; x++) {
        if (lstAlineacionPropuesta[x]["idRegistro"] == idFilaAlineacion) {

            lstAlineacionPropuesta[x]["idPoliticaNacional"] = politica.val();
            lstAlineacionPropuesta[x]["idMetaNacional"] = meta.val();

            //  Actualizo Fila Editada de la lista de alineaciones
            updFilaAlineacion(idFilaAlineacion);

            jQuery('#idAlineacion').attr('value', '');
        }
    }
    //  Limpo el formulario
    limpiarFrmAlineacion();
    restaurarFrmAlineacion();
}

//  Actualiza la tabla de alineaciones
function updFilaAlineacion(idFilaAlineacion)
{
    jQuery('#tbLstAlineacion tr').each(function() {
        if (jQuery(this).attr('id') == idFilaAlineacion) {

            //  Agrego color a la fila actualizada
            jQuery(this).attr('style', 'border-color: black;background-color: bisque;');

            var objetivoTxt = jQuery('#jform_intCodigo_on :selected');
            var politicaTxt = jQuery('#jform_intCodigo_pn :selected');
            var metaTxt = jQuery('#jform_idCodigo_mn :selected');

            //  Construyo la Fila
            var filaAlineacion = "  <td align='center'>" + objetivoTxt.text() + "</td>"
                    + " <td align='center'>" + politicaTxt.text() + "</td>"
                    + " <td align='center'>" + metaTxt.text() + "</td>"
                    + " <td align='center' width='15' > <a class='updAlineacion'> Editar </a> </td>"
                    + " <td align='center' width='15' > <a class='delAlineacion'> Eliminar </a> </td>";

            jQuery(this).html(filaAlineacion);
        }
    });
}

//  Elimina una fila de la tabla de alineaciones
function delFilaAlineacion(idFila)
{
    //  Elimino fila de la tabla lista de GAP
    jQuery('#tbLstAlineacion tr').each(function() {
        if (jQuery(this).attr('id') == idFila) {
            jQuery(this).remove();
        }
    });
}
