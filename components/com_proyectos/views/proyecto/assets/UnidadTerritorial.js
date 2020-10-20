jQuery(document).ready(function() {

    var ut = 0;
    idFila = -1;// identidicador de un registro que se esta editando.
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

    //  Gestiona el formulario y la imagen 
    jQuery("#addUbicTerriTablePry").click(function() {
        if (idFila != -1) {
            jConfirm("Se está editando una Unidad Territorial, se perderán los cambios", "SIITA - ECORAE", function(result) {
                if (result) {
                    limpiarFormulario();
                    jQuery("#frmUbiTerritorialPry").css("display", "block");
                    jQuery("#imgUbiTerritorialPry").css("display", "none");
                }
            });
        }
        jQuery("#frmUbiTerritorialPry").css("display", "block");
        jQuery("#imgUbiTerritorialPry").css("display", "none");
    });

    //
    //  Gestiono el registro de puntos de un determinada coordenada
    //
    jQuery('#btnAddUndTerritorial').click(function() {
        var dpa = [];
        var idProvincia = jQuery('#jform_idProvincia').val();
        var provincia = jQuery('#jform_idProvincia :selected').text();

        var idCanton = jQuery('#jform_idCanton').val();
        var canton = jQuery('#jform_idCanton :selected').text();

        var idParroquia = jQuery('#jform_idParroquia').val();
        var parroquia = jQuery('#jform_idParroquia :selected').text();

        if (idProvincia != 0) {
            dpa["idProvincia"] = idProvincia;
            dpa["provincia"] = provincia;
            dpa["idCanton"] = idCanton;

            dpa["canton"] = (idCanton != 0) ? canton
                    : '---';

            dpa["idParroquia"] = idParroquia;
            dpa["parroquia"] = (idParroquia != 0) ? parroquia
                    : '---';

            dpa["published"] = 1;

            if (existeUnidadTerritorial(idProvincia, idCanton, idParroquia) == false) {
                if (idFila == -1) {
                    dpa["idRegistro"] = ++ut;
                    //  Agrego una fila en la tabla
                    addUndTerritorial(dpa);
                    undTerritorial.push(dpa);
                } else {
                    dpa["idRegistro"] = idFila;
                    //  Actualizo contenido de Unidad Territorial
                    updUndTerritorial(dpa);

                    //  Actualizo Fila de Unidad Territorial
                    updFilaUndTerritorial(dpa);
                    
                    // cambiando el texto del boton
                    jQuery("#updUTProy-" + idFila).html("Editar");
                    idFila = -1;
                }
                limpiarFormulario();
            } else {
                jAlert('Unidad Territorial Registrada', 'SIITA - ECORAE');
            }
        } else {
            jAlert('Campos con asterisco, SON OBLIGATORIOS', 'ECORAE-SIITA');
        }
    });

    function existeUnidadTerritorial(idProv, idCnt, idParroquia)
    {
        var nrut = undTerritorial.length;
        var ut = undTerritorial;
        var ban = false;

        for (var x = 0; x < nrut; x++) {
            if (ut[x]["idProvincia"] == idProv && ut[x]["idCanton"] == idCnt && ut[x]["idParroquia"] == idParroquia && ut[x]["idRegistro"] != idFila) {
                ban = true;
            }
        }
        return ban;
    }


    //  Agrego una fila con informacion de una unidad territorial
    function addUndTerritorial(data)
    {
        //  Construyo la Fila
        var fila = "    <tr id='" + data["idRegistro"] + "'>"
                + "     <td align='center'>" + data["provincia"] + "</td>"
                + "     <td align='center'>" + data["canton"] + "</td>"
                + "     <td align='center'>" + data["parroquia"] + "</td>";
        
        if( roles["core.create"] === true || roles["core.edit"] === true ){
            fila+= "    <td align='center' width='15' > <a id='updUTPry-" + data["idRegistro"] + "' class='updUndTerritorial'>Editar</a> </td>"
                + "     <td align='center' width='15' > <a id='delUTPry-" + data["idRegistro"] + "' class='delUndTerritorial'>Eliminar</a> </td>"
        }else{
            fila+= "    <td align='center' width='15'>Editar </td>"
                + "     <td align='center' width='15'> Eliminar </td>"
        }
        
        fila += " </tr>";

        //  Agrego la fila creada a la tabla
        jQuery('#lstUndTerritoriales > tbody:last').append(fila);

        //  Regreso a su posicion inicial los combos del formulario
        limpiarFormulario();
    }

    /**
     * 
     * Actualizo contenido de un elemento de una lista de unidad territoriales
     * 
     * @param {type} dpa
     * @returns {unresolved}
     * 
     */
    function updUndTerritorial(dpa)
    {
        var nrut = undTerritorial.length;
        for (var x = 0; x < nrut; x++) {
            if (undTerritorial[x]["idRegistro"] == idFila) {
                undTerritorial[x] = dpa;
                return;
            }
        }
    }

    /**
     * 
     * Actualizo una determinada fila de Unidad Territorial
     * 
     * @param {type} dpa
     * @returns {undefined}
     */
    function updFilaUndTerritorial(dpa)
    {
        jQuery('#lstUndTerritoriales tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {

                //  Construyo la Fila
                var fila = "   <td align='center'>" + dpa["provincia"] + "</td>"
                        + " <td align='center'>" + dpa["canton"] + "</td>"
                        + " <td align='center'>" + dpa["parroquia"] + "</td>";
                
                if( roles["core.create"] === true || roles["core.edit"] === true ){
                    fila+= "<td align='center' width='15' > <a id='updUTPry-" + dpa["idRegistro"] + "' class='updUndTerritorial'>Editar</a> </td>"
                        + " <td align='center' width='15' > <a id='delUTPry-" + dpa["idRegistro"] + "' class='delUndTerritorial'>Eliminar</a> </td>";
                }else{
                    fila+= "<td align='center' width='15' > Editar </td>"
                        + " <td align='center' width='15' > Eliminar </td>";
                }

                jQuery(this).html(fila);
            }
        })
    }


    //
    //  Limpiar las unidades territoriales
    //
    jQuery('#btnLimpiarUndTerritorial').click(function() {
        limpiarFormulario();
    });


    function getDataUndTerritorial(idRegistro)
    {
        var numReg = undTerritorial.length;

        for (var x = 0; x < numReg; x++) {
            if (undTerritorial[x]["idRegistro"] == idRegistro) {
                return undTerritorial[x];
            }
        }

        return false;
    }

    //  Actualizo Fila de unidad territorial
    jQuery('.updUndTerritorial').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idReg = updFila.attr('id');

        //controlo que otra unidad se esta editando
        if (idFila != -1) {
            jConfirm("Otra Unidad Territorial está siendo editada, se perdenar la modificaciones", "SIITA - ECORAE", function(result) {
                if (result) {
                    // cambiando el texto del boton
                    jQuery("#updUTPry-" + idFila).html("Editar");
                    cargarDataUTPry(idReg);
                }
            });
        } else {
            cargarDataUTPry(idReg);
        }
    });

    //  Unidad Territoral
    jQuery('.delUndTerritorial').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        var numReg = undTerritorial.length;

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
            if (result) {
                for (var x = 0; x < numReg; x++) {
                    if (undTerritorial[x]["idRegistro"] == idFila) {
                        undTerritorial.splice(x, 1);
                        delFilaUndTerritorial(idFila);
                        return;
                    }
                }
            }
        });
    })

    //  Carga la data en los formularios de una Unidad territorial para ser editada
    function cargarDataUTPry(idReg) {
        idFila = idReg;

        // Obtengo la data del registro
        var data = getDataUndTerritorial(idFila);
        if (data) {
            //  Recorro el combo de Provincias
            recorrerCombo(jQuery('#jform_idProvincia option'), data["idProvincia"]);

            //  Ejecuto ajax para Cantones
            jQuery('#jform_idProvincia ').trigger('change', data["idCanton"]);

            //  Ejecuto ajax para Parroquias
            jQuery('#jform_idCanton').trigger('change', [data["idCanton"], data["idParroquia"]]);

            // cambiando el texto del boton
            jQuery("#updUTPry-" + idFila).html("Editando...");
            jQuery("#delUTPry-" + idFila).attr('disabled', 'disabled');
            jQuery("#frmUbiTerritorialPry").css("display", "block");
            jQuery("#imgUbiTerritorialPry").css("display", "none");
        }
    }


    //  Elimino la fila 
    function delFilaUndTerritorial(idFila)
    {
        //  Elimino fila de la tabla del registro eliminado
        jQuery('#lstUndTerritoriales tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        })
    }

    //
    //  Recorre los comboBox del Formulario a la posicion inicial
    //
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }

    //
    //  Regreso a la posicion inicial los elementos del formulario
    //
    function limpiarFormulario()
    {
        //  Desabilito el formulario y habilito al imagen
        jQuery("#frmUbiTerritorialPry").css("display", "none");
        jQuery("#imgUbiTerritorialPry").css("display", "block");

        //  Regreso a su posicion inicial al combo de Provincias
        recorrerCombo(jQuery('#jform_idProvincia option'), 0);

        //  Regreso a su posicion inicial al combo de Cantones
        recorrerCombo(jQuery('#jform_idCanton option'), 0);

        //  Regreso a su posicion inicial al combo de Parroquias
        recorrerCombo(jQuery('#jform_idParroquia option'), 0);
        
        if (idFila != -1) {
            // cambiando el texto del boton
            jQuery("#updUTPry-" + idFila).html("Editar");
            idFila = -1;
        }
        
        cleanValidateForn ( "#frmUbiTerritorialPry" );
    }

})//final del ready

