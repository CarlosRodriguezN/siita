var updFila = 0;            // Id de una fila para eliminar o edita
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {
    var ut = lstUndTerritorial.length;

    //  habilita los elementos del formulario de unidades territriales para 
    //  agregar una nueva UT
    jQuery("#addUnidadTerritorialTable").click(function() {
        if (updFila != 0) {
            jConfirm( JSL_ALERT_UPD_NOW, JSL_ECORAE, function(result) {
                if (result) {
                    clearUtForm();
                    jQuery("#frmUnidadTerritorial").css("display", "block");
                    jQuery("#imgUnidadTerritorial").css("display", "none");
                }
            });
        }
        jQuery("#frmUnidadTerritorial").css("display", "block");
        jQuery("#imgUnidadTerritorial").css("display", "none");
    });

    // Gestiono las Unidades territoriales 
    jQuery("#btnAddUndTerritorial").click(function() {

        // obtengo las valores de la unidad territorial
        var dpa = [];
        var dtaUndTrr = {
                    idProvincia : jQuery('#jform_idProvincia :selected').val(),
                    provincia   : jQuery('#jform_idProvincia :selected').text(),
                    idCanton    : jQuery('#jform_idCanton :selected').val(),
                    canton      : jQuery('#jform_idCanton :selected').text(),
                    idParroquia : jQuery('#jform_idParroquia :selected').val(),
                    parroquia   : jQuery('#jform_idParroquia :selected').text()
        }

        // Creo un array con la data de UT
        if (dtaUndTrr.idProvincia != 0) {
            dpa["idRegistro"]   = ++ut;
            dpa["published"]    = 1;
            dpa["idProvincia"]  = dtaUndTrr.idProvincia;
            dpa["provincia"]    = dtaUndTrr.provincia;
            dpa["idCanton"]     = dtaUndTrr.idCanton;
            dpa["canton"]       = (dtaUndTrr.idCanton != 0) ? dtaUndTrr.canton : '---';
            dpa["idParroquia"]  = dtaUndTrr.idParroquia;
            dpa["parroquia"]    = (dtaUndTrr.idParroquia != 0) ? dtaUndTrr.parroquia : '---';

            var regExiste = existeUT(dtaUndTrr.idProvincia, dtaUndTrr.idCanton, dtaUndTrr.idParroquia);
            if (regExiste == false) {
                // controlo si es un nuevo registro o una actualizacion
                if (updFila == 0) {
                    lstUndTerritorial.push(dpa);
                    addRow(dpa);
                } else {
                    dpa["idRegistro"] = updFila;
                    updUt(dpa);
                    updFilaTable(dpa);
                    // cambiando el texto del boton
                    jQuery("#updUT-" + updFila).html( JSL_ACTUALIZAR );
                    updFila = 0;
                }
                // Limpio el formulario de UT
                clearUtForm();
                jQuery("#frmUnidadTerritorial").css("display", "none");
                jQuery("#imgUnidadTerritorial").css("display", "block");
            } else {
                jAlert( JSL_ALERT_EXIT_UND_TRR, JSL_ECORAE);
            }
        } else {
            jQuery("#frmDtaUnidadTerritorial").submit();
            jAlert( JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }
    });

    // Limpio la lista de unidates territoriales 
    jQuery('#btnLimpiarUndTerritorial').click(function() {
        clearUtForm();
    });

    // Elimino un registro de la lista de UT
    jQuery('.delUndTerritorial').live('click', function() {
        var delFila = (jQuery(this).parent().parent()).attr('id');
        // Emito un mensaje de confirmacion
        if (updFila == delFila) {
            jConfirm( JSL_ALERT_UPD_NOW_DEL, JSL_ECORAE, function(result) {
                if (result) {
                    delDataUT(delFila);
                    clearUtForm();
                }
            });
        } else {
            jConfirm( JSL_CONFIRM_DELETE, JSL_ECORAE, function(result) {
                if (result) {
                    delDataUT(delFila);
                }
            });
        }
    });

    // Edito una unidad territorial
    jQuery('.updUndTerritorial').live('click', function() {
        // Obtengo el Id del registro
        var idReg = (jQuery(this).parent().parent()).attr('id');
        //controlo que otra unidad se esta editando
        if (updFila != 0) {
            jConfirm( JSL_ALERT_UPD_NOW, JSL_ECORAE, function(result) {
                if (result) {
                    // cambiando el texto del boton
                    jQuery("#updUT-" + updFila).html(JSL_ACTUALIZAR );
                    cargarDataUT(idReg);
                }
            });
        } else {
            cargarDataUT(idReg);
        }
    });
});

//  Eliminado logico de un registro del array de unidades territoriales
function delDataUT(delFila) {
    for (var x = 0; x < lstUndTerritorial.length; x++) {
        if (lstUndTerritorial[x]["idRegistro"] == delFila) {
            lstUndTerritorial[x]["published"] = 0;
            delFilaUT(delFila);
        }
    }
}

//  Carga la data en los formularios de una Unidad territorial para ser editada
function cargarDataUT(idReg) {
    updFila = idReg;
    // Obtengo la data del registro
    var data = getDataUndTerritorial(updFila);
    // Cargo la data en los combos
    if (data) {
        //  Recorro el combo de Provincias
        recorrerCombo(jQuery('#jform_idProvincia option'), data["idProvincia"]);

        //  Ejecuto ajax para Cantones
        jQuery('#jform_idProvincia ').trigger('change', data["idCanton"]);

        //  Ejecuto ajax para Parroquias
        jQuery('#jform_idCanton').trigger('change', [data["idCanton"], data["idParroquia"]]);

        // cambiando el texto del boton
        jQuery("#updUT-" + updFila).html( JSL_EDITANDO );
        jQuery("#frmUnidadTerritorial").css("display", "block");
        jQuery("#imgUnidadTerritorial").css("display", "none");
    }
}

//  Retorna True en caso que la Unidad Territorial esxita
//  y False si no existe 
function existeUT(idProvincia, idCanton, idParroquia)
{
    var numReg = lstUndTerritorial.length;
    for (var x = 0; x < numReg; x++) {
        if (lstUndTerritorial[x]["idProvincia"] == idProvincia && lstUndTerritorial[x]["idCanton"] == idCanton && lstUndTerritorial[x]["idParroquia"] == idParroquia && lstUndTerritorial[x]["idRegistro"] != updFila)
            return true;
    }

    return false;
}

// Actualizo un registro en el arreglo de unidades territoriales 
function updUt(data) {
    for (var x = 0; x < lstUndTerritorial.length; x++) {
        if (lstUndTerritorial[x]["idRegistro"] == data['idRegistro']) {
            lstUndTerritorial[x] = data;
        }
    }
}

// Agrego una fila a la tabla de UT
function addRow(data)
{
    //  Construyo la Fila
    var fila = "    <tr id='" + data["idRegistro"] + "'>"
            + "         <td align='center'>" + data["provincia"] + "</td>"
            + "         <td align='center'>" + data["canton"] + "</td>"
            + "         <td align='center'>" + data["parroquia"] + "</td>";

    if( roles["core.create"] === true || roles["core.edit"] === true ){
        fila+= "        <td align='center' width='15' > <a id='updUT-" + data["idRegistro"] + "' class='updUndTerritorial'>" + JSL_ACTUALIZAR  + "</a> </td>"
            + "         <td align='center' width='15' > <a id='delUT-" + data["idRegistro"] + "' class='delUndTerritorial'>" + JSL_ELIMINAR + "</a> </td>";        
    }else{
        fila+= "        <td align='center' width='15' > " + JSL_ACTUALIZAR  + " </td>"
            + "         <td align='center' width='15' > " + JSL_ELIMINAR + "</a> </td>";
    }
    
    fila += "</tr>";

    //  Agrego la fila creada a la tabla
    jQuery('#lstUndTerritoriales > tbody:last').append(fila);
}

// Limpio el formulario de UT
function clearUtForm() {
    //  Recorro el combo de Provincias
    recorrerCombo(jQuery('#jform_idProvincia option'), 0);

    //  Limpia contenido del combo de Cantones
    enCerarCombo(jQuery('#jform_idCanton option'));

    //  Limpia contenido del combo de Parroquias
    enCerarCombo(jQuery('#jform_idParroquia option'));

    jQuery("#frmUnidadTerritorial").css("display", "none");
    jQuery("#imgUnidadTerritorial").css("display", "block");

    if (updFila != 0) {
        // cambiando el texto del boton
        jQuery("#updUT-" + updFila).html( JSL_ACTUALIZAR );
        updFila = 0;
    }
    
    jQuery("#frmDtaUnidadTerritorial").validate().resetForm();
}

//  Elimino fila de la tabla lista de unidades territoriales
function delFilaUT(idFila)
{
    jQuery('#lstUndTerritoriales tr').each(function() {
        if (jQuery(this).attr('id') == idFila) {
            jQuery(this).remove();
        }
    });
}

//  Actualizo la fila en la tabla de unidades territoriales
function updFilaTable(data) {
    jQuery('#lstUndTerritoriales tr').each(function() {
        if (jQuery(this).attr('id') == data["idRegistro"]) {
            //  Agrego color a la fila actualizada
            jQuery(this).attr('style', 'border-color: black;background-color: bisque;');
            //  Construyo la Fila
            var fila = "     <td align='center'>" + data["provincia"] + "</td>"
                    + "     <td align='center'>" + data["canton"] + "</td>"
                    + "     <td align='center'>" + data["parroquia"] + "</td>"
                    + "     <td align='center' width='15' > <a id='updUT-" + data["idRegistro"] + "' class='updUndTerritorial'>" + JSL_ACTUALIZAR  + "</a> </td>"
                    + "     <td align='center' width='15' > <a id='delUT-" + data["idRegistro"] + "' class='delUndTerritorial'>" + JSL_ELIMINAR + "</a> </td>";
            jQuery(this).html(fila);
        }
    })
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

//  Resetea los valores de un combo determinado
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

// Obtengo la data de un registro determinado
function getDataUndTerritorial(idRegistro)
{
    var numReg = lstUndTerritorial.length;
    //  Recorre el array de unidades territoriales registradas
    for (var x = 0; x < numReg; x++) {
        if (lstUndTerritorial[x]["idRegistro"] == idRegistro) {
            return lstUndTerritorial[x];
        }
    }
    return false;
}