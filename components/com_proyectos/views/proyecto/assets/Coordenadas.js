// Variables globales para la gestion de Ubicacion Geografica
var lstCoordenadas = new Array();
var graficoObra = new Array();
var flagGrafico = 0;
var flagVertice = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    idRegGrafico = ( lstUbicacionesGeo !== undefined )  ? lstUbicacionesGeo.length 
                                                        : 0;
    /**
     *  Habilito el formulario para ingresar la información de un grafico
     */
    jQuery("#addGraficosObraTable").click(function() {
        //  Si se esta editando un grafico, flagGrafico tiene el id del grafico editandose.
        if ( flagGrafico != 0 || jQuery('#frmCoordenadasUG').is(":visible") ) {
            jConfirm("Se perdera la informaci&oacute;n ingresada, ¿Desea continuar?", JSL_ECORAE, function(result) {
                if (result) {
                    //  Cambio el texto del boton Editar
                    jQuery("#updGraf-" + flagGrafico).html("Editar");
                    flagGrafico = 0;
                    eliminarDataPuntos()
                    jQuery("#frmUbicacionGeografica").css("display", "block");
                    jQuery("#imgUbicacionGeografica").css("display", "none");
                }
            });
        } else {
            jQuery('#btnAddLstCoordenadas').attr('disabled', 'disabled');
            jQuery("#frmUbicacionGeografica").css("display", "block");
            jQuery("#imgUbicacionGeografica").css("display", "none");
        }
        
    });

    /**
     *  Controla el ingreso de los datos de tipo de grafico.
     */
    jQuery('#jform_idTipoGrafico').live('change', function() {
        if (jQuery("#jform_idTipoGrafico").val() == 0)
            jQuery('#btnAddLstCoordenadas').attr('disabled', 'disabled');
        else
            jQuery('#btnAddLstCoordenadas').removeAttr('disabled', '');
    });

    /**
     *  Habilita el formulario para el ingreso de coordenadas
     */
    jQuery('#btnAddLstCoordenadas').click(function() {
        //  Condicion para el tipo de grafico circulo, se muestra el formulario para su radio.
        if (jQuery('#jform_idTipoGrafico').val() == 4) {
            //  Mostramos el input para ingresar el radio.
            jQuery('#obras-radio').css("display", "block");
            //  Escondemos la tabla de puntos.
            jQuery("#puntos-table").css("display", "none");
            //  Mostramos la tabla de circulo.
            jQuery("#radio-table").css("display", "block");
        }
        //  Habilita el formulario de coordenadas y bloquea el textbox tipo de grafico.
        jQuery("#frmCoordenadasUG").css("display", "block");
        jQuery('#jform_idTipoGrafico').attr('disabled', 'disabled');
        //  Oculta los botones de tipo de grafico
        jQuery('#btnAddLstCoordenadas').attr('disabled', 'disabled');
        jQuery('#btnAddLstCoordenadas').css("display", "none");
        jQuery('#btnCancelarLstCoor').css("display", "none");
    });

    /**
     *  Resetea el formulario de tipo de grafico
     */
    jQuery("#btnCancelarLstCoor").click(function() {
        limpiarFrmTpoGrafico();
        cleanValidateForn ( "#formGraficoObraPry" );
    });

    /**
     *  Gestiona el registro de puntos de una determinada coordenada.
     */
    jQuery('#btnAddCoordenada').click(function() {
        //  Verifica si se ingresaron todos los campos
        if ( validDtaCoordenada() ) {
            var coordenada = [];
            var idRegCoordenada = lstCoordenadas.length;

            //  Para el tipo de grafico Circulo se gestionan los elementos del circulo.
            if (jQuery('#jform_idTipoGrafico').val() == 4) {
                jQuery('#btnAddCoordenada').attr('disabled', 'disabled');
            }

            //  Si se esta editando un registro se recupera su Id, si no se asigna uno nuevo. 
            if (flagVertice == 0) {
                //  Se obtiene la data del vertice
                coordenada["idRegCoordenada"] = ++idRegCoordenada;
                coordenada["idCoordenada"] = 0;
                coordenada["latitud"] = jQuery('#jform_latitud').val();
                coordenada["longitud"] = jQuery('#jform_longitud').val();
                coordenada["published"] = 1;
                //  Agrega la data al array de coordenadas
                lstCoordenadas.push(coordenada);

                //  Para dibujar la tabla.
                if (jQuery('#jform_idTipoGrafico').val() != 4) {
                    //  Si no es tipo de grafico Circulo se dibuja la tabla.
                    addFilaPuntos(coordenada);
                } else if (jQuery('#jform_idTipoGrafico').val() == 4) {
                    //  Si es de tipo grafico, calcula el nuevo punto de la circunferencia.
                    calPtnCirculo(coordenada);
                }
            } else {
                //  Recorre la lista de coredenadas
                for (var j = 0; j < lstCoordenadas.length; j++) {
                    if (lstCoordenadas[j].idRegCoordenada == flagVertice) {
                        //  Actualiza la data
                        lstCoordenadas[j].latitud = jQuery('#jform_latitud').val();
                        lstCoordenadas[j].longitud = jQuery('#jform_longitud').val();
                        //  Actuliza la fila de la tabla dependiendo del tipo de grafico.
                        if (jQuery('#jform_idTipoGrafico').val() != 4) {
                            updFilaCoor(flagVertice);
                        } else {
                            calPtnCirculo(lstCoordenadas[j]);
                        }
                        // cambiando el texto del boton
                        jQuery("#updCoor-" + flagVertice).html("Editar");
                    }
                }
                flagVertice = 0;
                //  Cambia el Value del boton a Agregar Coordenada y se libera la bandera vertice 
                jQuery("#btnAddCoordenada").attr("value", "Agregar Coordenada");
                jQuery("#btnLimpiarGrafico").attr("value", "Limpiar");
            }
            //  Limpio los formularios
            linpiarFrmVertice();
        } else {
            jAlert('Campos con asterisco, SON OBLIGATORIOS', 'SIITA - ECORAE');
        }
    });

    /**
     *   Limpia el formulario de vertices de una obra
     */ 
    jQuery('#btnLimpiarGrafico').click(function() {
        linpiarFrmVertice();
        if (flagVertice != 0) {
            if (jQuery('#jform_idTipoGrafico').val() == 4)
                jQuery("#btnAddCoordenada").attr("disabled", "disabled");
            //  Cambia el Value del boton a Agregar Coordenada
            jQuery("#btnAddCoordenada").attr("value", "Agregar Coordenada");
            jQuery("#btnLimpiarGrafico").attr("value", "Limpiar");
            jQuery("#updCoor-" + flagVertice).html("Editar");
            flagVertice = 0;
        }
    });

    /**
     *  Agrega un nuevo grafico de una obra al vector de obras
     */
    jQuery("#btnAddGrafico").click(function() {
        //  Controlo si se esta editando una coordenada del gráfico
        if (flagVertice == 0) {
            if (countRegistros() > 0) {
                //  controlo si se esta editando un gráfico
                if (flagGrafico == 0) {
                    //  Obtengo la data del gráfico
                    var idRegGraf = ++idRegGrafico;
                    graficoObra["idRegGrafico"] = idRegGraf;
                    graficoObra["idGrafico"] = 0;
                    graficoObra["tpoGrafico"] = jQuery('#jform_idTipoGrafico').val();
                    graficoObra["infoTpoGrafico"] = jQuery('#jform_idTipoGrafico :selected').text();
                    graficoObra["descGrafico"] = jQuery('#jform_strDescripcionObra').val();
                    graficoObra["published"] = 1;
                    graficoObra["lstCoordenadas"] = lstCoordenadas;

                    //  Construyo la Fila
                    var fila = '<tr id="' + idRegGraf + '">'
                            + '     <td align="center">' + graficoObra["descGrafico"] + '</td>'
                            + '     <td align="center">' + graficoObra["infoTpoGrafico"] + '</td>'
                            + '     <td align="center" width="15" > <a id="showGraf-' + idRegGraf + '" class="showGrafico">Ver</a> </td>'
                    
                    if( roles["core.create"] === true || roles["core.edit"] === true ){
                        fila += '   <td align="center" width="15" > <a id="updGraf-' + idRegGraf + '" class="updGrafico" >Editar</a> </td > '
                             + '    <td align="center" width="15" > <a id="delGraf-' + idRegGraf + '" class="delGrafico" >Eliminar</a> </td>'
                    }else{
                        fila += '   <td align="center" width="15" > Editar </td > '
                             + '    <td align="center" width="15" > Eliminar </td>';
                    }
                    
                    fila += ' </tr>';

                    //  Agrego la fila creada a la tabla
                    jQuery('#tbLstGraficos > tbody:last').append(fila);
                    lstUbicacionesGeo.push(graficoObra);
                    //  Se borran los gráficos que se estan mostrando
                    hidePointsCoordenadas();
                } else {
                    //  Obtengo la data del gráfico que se esta editando
                    var data = [];
                    for (var j = 0; j < lstUbicacionesGeo.length; j++) {
                        if (lstUbicacionesGeo[j].idRegGrafico == flagGrafico) {
                            lstUbicacionesGeo[j].descGrafico = jQuery('#jform_strDescripcionObra').val();
                            lstUbicacionesGeo[j].lstCoordenadas = lstCoordenadas;
                            data = lstUbicacionesGeo[j];
                            insertarFilaUpd(data);
                        }
                    }
                    //  Cabio el texto del botón de agregar
                    jQuery("#btnAddGrafico").attr("value", "Agregar Gráfico");
                    //  Oculto los graficos que se estan mostrando en el mapa y libero la bandera de edición 
                    hidePointsCoordenadas();
                    flagGrafico = 0;
                }
                //  Limpio los formularios y resetea las variables
                eliminarDataPuntos();
            } else {
                jAlert('Ingrese almenos una coordenada', 'SIITA - ECORAE');
            }
        } else {
            jAlert("Se está editando una coordenada, guarde los cambios.", "SIITA - ECORAE");
        }
    });

    /**
     *  Limpiar los puntos del grafico a registrar y reinicia los formularios
     */
    jQuery('#btnLimpiarPuntos').click(function() {
        jConfirm("¿Est&aacute; seguro que desea cancelar?", "SIITA - ECORAE", function(result) {
            if (result) {
                //  Si se esta editando un grafico cambio el texto del boton
                if (flagGrafico != 0)
                    jQuery("#updGraf-" + flagGrafico).html("Editar");
                //  Oculto los graficos del mapa, limpio los formularios y reseteo las variables
                hidePointsCoordenadas();
                eliminarDataPuntos();
                jQuery('#btnCancelarLstCoor').css("display", "block");

            }
        });
    });

    /**
     *  Dibuja un tipo de grafico en el mapa 
     */
    jQuery('.showGrafico').live('click', function() {
        //  Recupero el Id del registro para dibujarlo en el mapa
        var idRegGrafico = (jQuery(this).parent().parent()).attr('id');
        drawHideGrafic(idRegGrafico);
    });

    /**
     *  Carga los datos de un determinado grafico a ser editado.
     */
    jQuery('.updGrafico').live('click', function() {
        //recupero el Id del registro
        var dtaGrafico = jQuery(this).parent().parent();
        var idRegGrafico = dtaGrafico.attr('id');

        //  Si se esta editando un grafico, flagGrafico tiene el id del grafico editandose.
        if (flagGrafico != 0 || jQuery("#frmCoordenadasUG").css("display") == "block") {
            if ( flagGrafico != idRegGrafico) {
                jConfirm("Se está editando un gráfico, se perderán las modificaciones", "SIITA - ECORAE", function(result) {
                    if (result) {
                        // cambiando el texto del boton y recupero la data
                        jQuery("#updGraf-" + flagGrafico).html("Editar");
                        cargarDataUbcGeo(idRegGrafico);
                    }
                });
            }
        } else {
            //  recupero la data y cargo en los formularios
            cargarDataUbcGeo(idRegGrafico);
        }
    });

    /**
     *  Dibuja un coordenada en el mapa
     */
    jQuery('.drawCoordenada').live('click', function() {
        // recupero el Id del registro y lo dibujo en el mapa
        var idRegCoordenada = (jQuery(this).parent().parent()).attr('id');
        drawHidePoint(idRegCoordenada);
    });

    /**
     *  Recupera la data de una coordenada y carga los formularios
     */
    jQuery('.editCoordenada').live('click', function() {
        //  Recupero el Id del registro
        var idRegCoordenada = (jQuery(this).parent().parent()).attr('id');
        //  Verifico si no se esta eidtando una coordenadana
        if (flagVertice == 0) {
            for (var j = 0; j < lstCoordenadas.length; j++) {
                // cambiando el texto del boton
                jQuery("#updCoor-" + idRegCoordenada).html("Editando...");
                jQuery("#btnLimpiarGrafico").val("Cancelar");
                if (lstCoordenadas[j].idRegCoordenada == idRegCoordenada) {
                    flagVertice = idRegCoordenada;
                    //cargo los text box
                    jQuery("#jform_latitud").val(lstCoordenadas[j].latitud);
                    jQuery("#jform_longitud").val(lstCoordenadas[j].longitud);

                    if (jQuery("#jform_idTipoGrafico").val() == 4) {
                        var centro = getGLatlng(lstCoordenadas[0].latitud, lstCoordenadas[0].longitud);
                        var borde = getGLatlng(lstCoordenadas[1].latitud, lstCoordenadas[1].longitud);
                        var radio = getRadioRevese(centro, borde);
                        // pongo el valor del radio.
                        jQuery('#jform_Radio').val(radio);
                        jQuery('#btnAddCoordenada').removeAttr('disabled', '');
                    }
                    //  Borra el punto del mapa.
                    if (lstCoordenadas[j].glPointMarket != null) {
                        drawHidePoint(idRegCoordenada);
                    }
                    //  Cambia el Value del boton a Guardar Cambions
                    jQuery("#btnAddCoordenada").attr("value", "Guardar Cambios");
                }
            }
        } else {
            jAlert('Se está editando una coordenada, guarde los cambios', 'SIITA - ECORAE');
        }
    });

    /**
     *  elimina una coordenada de la lista de coordenad as de un determinado grafico.
     */
    jQuery('.deleteCoordenada').live('click', function() {
        var idCoor = (jQuery(this).parent().parent()).attr('id');
        if (flagVertice != idCoor) {
            jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
                if (result) {
                    if (jQuery('#jform_idTipoGrafico').val() != 4) {
                        for (var j = 0; j < lstCoordenadas.length; j++) {
                            if (lstCoordenadas[j].idRegCoordenada == idCoor) {
                                //  borro del array
                                lstCoordenadas[j].published = 0;
                                //quito la fila.
                                delFilaCoordenada(idCoor);
                            }
                        }
                    } else if (jQuery('#jform_idTipoGrafico').val() == 4) {
                        lstCoordenadas = [];
                        eliminarFilas(jQuery('#tbLstCirculo tr'));
                        linpiarFrmVertice();
                        jQuery('#btnAddCoordenada').attr('value', 'Agregar Coordenada');
                        jQuery('#btnAddCoordenada').removeAttr('disabled', '');
                    }
                }
            });
        } else {
            jAlert('El elemento se está editando, no se puede eliminar', 'SITTA - ECORAE');
        }
    });

    /**
     *  Elimina un registro en la lista graficos
     */
    jQuery('.delGrafico').live('click', function() {
        var idRegGrafico = (jQuery(this).parent().parent()).attr('id');
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
            if (result) {
                for (var j = 0; j < lstUbicacionesGeo.length; j++) {
                    if (lstUbicacionesGeo[j].idRegGrafico == idRegGrafico) {
                        //  borro del array
                        lstUbicacionesGeo[j].published = 0;
                        //quito la fila.
                        delFilaGrafico(idRegGrafico);
                        if (flagGrafico != 0) {
                            flagGrafico == 0;
                            eliminarDataPuntos();
                        }
                    }
                }
            }
        });
    });
});

/**
 *  resetea el formulario de tipo de grafico
 * @returns {undefined}
 */
function limpiarFrmTpoGrafico()
{
    jQuery("#frmUbicacionGeografica").css("display", "none");
    jQuery("#imgUbicacionGeografica").css("display", "block");
    jQuery('#jform_strDescripcionObra').val('');
    jQuery('#jform_idTipoGrafico').val(0);
    jQuery('#jform_idTipoGrafico').removeAttr('disabled', '');
    jQuery('#btnAddLstCoordenadas').attr('disabled', 'disabled');
    jQuery('#btnAddLstCoordenadas').css("display", "block");
    jQuery('#btnCancelarLstCoor').css("display", "block");
}

/**
 *  Controla el ingreso de los campos obligatorios en en formulario de coordenadas
 * @returns {Boolean}
 */
function validDtaCoordenada()
{
    var result = true;
    if ( jQuery('#jform_idTipoGrafico').val() == 4 && jQuery('#jform_latitud').val() == '' ) {
        result = false;
    }

    result = ( result && jQuery('#jform_latitud').val() != '' && jQuery('#jform_longitud').val() != '' ) ? true : false;
    return result;
}

/**
 * Elimina una fila de la tabla de coordenadas
 * @param {type} idCoor     Id del reistro a eliminar
 * @returns {undefined}
 */
function delFilaCoordenada(idCoor) {
    //  Elimino fila de la tabla lista de Coordenadas
    jQuery('#tbLstPuntos tr').each(function() {
        if (jQuery(this).attr('id') == idCoor) {
            jQuery(this).remove();
        }
    });
}

/**
 *  Elimina una fila de la tabla lista de graficos 
 * @param {type} idRegGrafico
 * @returns {undefined}
 */
function delFilaGrafico(idRegGrafico) {
    //  Elimino fila de la tabla lista de Coordenadas
    jQuery('#tbLstGraficos tr').each(function() {
        if (jQuery(this).attr('id') == idRegGrafico) {
            jQuery(this).remove();
        }
    });
}

/**
 *  Actualiza un afiala de la tabala de graficos
 * @param {type} data
 * @returns {undefined}
 */
function insertarFilaUpd(data) {
    jQuery('#tbLstGraficos tr').each(function() {
        if (jQuery(this).attr('id') == flagGrafico) {
            //  Agrego color a la fila actualizada
            jQuery(this).attr('style', 'border-color: black;background-color: bisque;');
            
            // cambiando el texto del boton
            jQuery("#updGraf-" + flagGrafico).html("Editar");
            
            //  Construyo la Fila
            var fila = "<td align='center'>" + data["descGrafico"] + "</td>"
                    + " <td align='center'>" + data["infoTpoGrafico"] + "</td>"
                    + " <td align='center' width='15' > <a id='showGraf-" + data["idRegGrafico"] + "' class='showGrafico'>Ver</a> </td>"

            if( roles["core.create"] === true || roles["core.edit"] === true ){
                fila +="<td align='center' width='15'> <a id='updGraf-" + data["idRegGrafico"] + "' class='updGrafico'>Editar</a> </td>"
                     + "<td align='center' width='15'> <a id='delGraf-" + data["idRegGrafico"] + "' class='delGrafico'>Eliminar</a> </td>";    
            }else{
                fila +="<td align='center' width='15'> Editar </td>"
                     + "<td align='center' width='15'> Eliminar </td>";
            }
            
            jQuery(this).html(fila);
        }
    });
}

/**
 *  Calcula la coordenada para obtener el radio del un grafico de tipo circulo
 * @param {type} coordenada
 * @returns {undefined}
 */
function calPtnCirculo(coordenada) {
    // Cuando es tipo de grafico circulo es necesario calcular el otro punto.
    var pntCirculo = [];
    var latLngPunt2 = getCirclePoint2(coordenada.latitud, coordenada.longitud, jQuery('#jform_Radio').val());

    pntCirculo["idCoordenada"] = jQuery('#jform_intId_cgcp').val();
    pntCirculo["latitud"] = latLngPunt2.lat();
    pntCirculo["longitud"] = latLngPunt2.lng();
    pntCirculo["published"] = 1;

    if (flagVertice == 0) {
        //  Se obtiene la data del nuevo vertice.
        var idCoor = lstCoordenadas.length;
        pntCirculo["idRegCoordenada"] = ++idCoor;
        //  Agregamos las vestices en al vector de vectices.
        lstCoordenadas.push(pntCirculo);
    } else {
        lstCoordenadas[1].latitud = pntCirculo.latitud;
        lstCoordenadas[1].longitud = pntCirculo.longitud;
    }
    //  Agregamos la nueva fila a la tabla.
    addCircleRow(coordenada, pntCirculo);
}

/**
 * 
 * Resetea formularios y tabla de un nuevo grafico.
 * 
 * @returns {undefined}
 */
function eliminarDataPuntos() {
    //  Limpia las tablas de puntos
    eliminarFilas(jQuery('#tbLstPuntos tr'));
    eliminarFilas(jQuery('#tbLstCirculo tr'));
    //  Limpia el formulario de vertices
    linpiarFrmVertice();
    //  Se oculta la tabla de circulo y se muestra la tabla de puntos.
    jQuery("#puntos-table").css("display", "block");
    jQuery("#radio-table").css("display", "none");
    jQuery('#obras-radio').css("display", "none");
    jQuery('#frmCoordenadasUG').css("display", "none");
    //  Limpa el formulario de tipo de grafico
    limpiarFrmTpoGrafico();
    
    if (flagGrafico != 0) {
        jQuery("#updGraf-" + flagGrafico).html("Editar");
        flagGrafico = 0;
        flagVertice = 0;
    }
    
    lstCoordenadas = [];
    graficoObra = [];
}

/**
 *  Limpia el formulario para ingresar un vertice
 * @returns {undefined}
 */
function linpiarFrmVertice() {
    jQuery('#jform_latitud').attr('value', '');
    jQuery('#jform_longitud').attr('value', '');
    if (jQuery('#jform_idTipoGrafico').val() == 4)
        jQuery('#jform_Radio').attr('value', '');
    
    jQuery("#frmCoordenadasUG").css("display", "none");
    cleanValidateForn ( "#formGraficoCoordenada" );
    jQuery("#frmCoordenadasUG").css("display", "block");
}

/**
 * 
 *  Agrega la latitud y longitud de un punto de una determinada coordenada
 * 
 * @param {type} newVertice     Data de la coordenada
 * @returns {undefined}
 */
function addFilaPuntos(newVertice) {
    var idVert = newVertice.idRegCoordenada;
    var fila = "<tr id='" + idVert + "'>"
            + "     <td align='center'>" + newVertice.latitud + "</td>"
            + "     <td align='center'>" + newVertice.longitud + "</td>"
            + "     <td align='center' width='15' > <a id='grafCoor-" + idVert + "' class='drawCoordenada' >Ver</a> </td>"
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        fila +="   <td align='center' width='15' > <a id='updCoor-" + idVert + "' class='editCoordenada' >Editar</a> </td>"
             + "   <td align='center' width='15' > <a id='delCoor-" + idVert + "' class='deleteCoordenada' >Eliminar</a> </td>"
    }else{
        fila += "   <td align='center' width='15' > Editar </td>"
             + "    <td align='center' width='15' > Eliminar </td>"
    }
    
    fila += " </tr>";

    //  Agrego la fila creada a la tabla
    jQuery('#tbLstPuntos > tbody:last').append(fila);
}

/**
 * 
 *  Agrega una nueva fila a la tabla de tipo de grafico circulo.
 * 
 * @param {type} vertice    coordenada del centro del circulo
 * @param {type} pntCirculo     coordenada del borde del circulo
 * @returns {undefined}
 */
function addCircleRow(vertice, pntCirculo) {
    if (flagVertice != 0) {
        eliminarFilas(jQuery('#tbLstCirculo tr'));
    }
    var idVert = vertice["idRegCoordenada"];
    var centro = getGLatlng(vertice.latitud, vertice.longitud);
    var borde = getGLatlng(pntCirculo.latitud, pntCirculo.longitud);
    
    var circleRow = "<tr id='" + idVert + "'>"
            + " <td>" + vertice["latitud"] + "</td>"
            + " <td>" + vertice["longitud"] + "</td>"
            + " <td>" + getRadioRevese(centro, borde) + "</td>"
            + "     <td align='center' width='15' > <a id='grafCoor-" + idVert + "' class='drawCoordenada' >Ver</a> </td>"
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        circleRow +="<td align='center' width='15' > <a id='updCoor-" + idVert + "' class='editCoordenada' >Editar</a> </td>"
                  + "<td align='center' width='15' > <a id='delCoor-" + idVert + "' class='deleteCoordenada' >Eliminar</a> </td>"
    }else{
        circleRow += "<td align='center' width='15' > Editar </td>"
                  + "<td align='center' width='15' > Eliminar </td>"
    }
    
    circleRow += "</tr>";

    //  Agrego la fila creada a la tabla
    jQuery('#tbLstCirculo > tbody:last').append(circleRow);

}

/**
 *  Limpia las filas de un adeterminada tabla
 * @param {type} tabla
 * @returns {undefined}
 */
function eliminarFilas(tabla) {
    var n = 0;
    jQuery(tabla).each(function() {
        if (n > 0) {
            jQuery(this).remove();
        }
        n++;
    });
}

//
function getCirclePoint2(lat, lng, radio) {
    var centro = getGLatlng(lat, lng);
    var punto_final = google.maps.geometry.spherical.computeOffset(centro, radio, 90);
    return punto_final;
}

/**
 *  Calcula el radio de la circunferencia 
 * @param {type} center
 * @param {type} border
 * @returns {unresolved}
 */
function getRadioRevese(center, border) {
    var distancia = google.maps.geometry.spherical.computeDistanceBetween(center, border);
    return parseFloat(distancia.toFixed(2));
}

/**
 *  Actualiza una fila en la tabla de coordenadas
 * @param {type} idFilaCoor
 * @returns {undefined}
 */
function updFilaCoor(idFilaCoor) {
    jQuery('#tbLstPuntos tr').each(function() {
        if (jQuery(this).attr('id') == idFilaCoor) {
            //  Agrego color a la fila actualizada
            jQuery(this).attr('style', 'border-color: black;background-color: bisque;');

            var latitud = jQuery('#jform_latitud').val();
            var longitud = jQuery('#jform_longitud').val();

            //  Construyo la Fila
            var fila = "    <td align='center'>" + latitud + "</td>"
                    + "     <td align='center'>" + longitud + "</td>"
                    + "     <td align='center' width='15' > <a id='grafCoor-" + idFilaCoor + "' class='drawCoordenada' >Ver</a> </td>"
            
            if( roles["core.create"] === true || roles["core.edit"] === true ){
                fila+= "    <td align='center' width='15' > <a id='updCoor-" + idFilaCoor + "' class='editCoordenada' >Editar</a> </td>"
                    + "     <td align='center' width='15' > <a id='delCoor-" + idFilaCoor + "' class='deleteCoordenada' >Eliminar</a> </td>";
            }else{
                fila += "   <td align='center' width='15' > Editar </td>"
                     +  "   <td align='center' width='15' > Eliminar </td>";
            }             
            
            jQuery(this).html(fila);
        }
    });
}

/**
 * 
 * @returns {undefined}
 */
function hidePointsCoordenadas() {
    for (var j = 0; j < lstCoordenadas.length; j++) {
        if (lstCoordenadas[j].glPointMarket != null) {
            lstCoordenadas[j].glPointMarket.setMap(null);
            //  Pongo en nulo el objeto
            lstCoordenadas[j].glPointMarket = null;
            //  Cambio el texto del ancla
            jQuery("#gl-" + lstCoordenadas[j].idRegCoordenada).html("Ver");
        }
    }
}

/**
 * 
 * @param {type} idRegGrafico
 * @returns {undefined}
 */
function cargarDataUbcGeo(idRegGrafico) {
    flagGrafico = idRegGrafico;
    // cambiando el texto del boton
    jQuery("#updGraf-" + idRegGrafico).html("Editando...");
    jQuery("#frmUbicacionGeografica").css("display", "block");
    jQuery("#imgUbicacionGeografica").css("display", "none");
    jQuery("#frmCoordenadasUG").css("display", "block");
    jQuery('#jform_idTipoGrafico').attr('disabled', 'disabled');
    jQuery('#btnAddLstCoordenadas').attr('disabled', 'disabled');
    jQuery('#btnCancelarLstCoor').css("display", "none");

    //  Borra el grafico del mapa.
    if (proyGraficos && proyGraficos[idRegGrafico]) {
        drawHideGrafic(idRegGrafico);
    }
    //  Encuentro el id de Grafico para recuperara la data.
    for (var j = 0; j < lstUbicacionesGeo.length; j++) {
        if (lstUbicacionesGeo[j].idRegGrafico == idRegGrafico)
            var indexArray = j;
    }
    //  Selecciono en el select
    jQuery("#jform_idTipoGrafico option[value=" + lstUbicacionesGeo[indexArray].tpoGrafico + "]").attr("selected", true);
    //  Asigno el valor de la descripcion.
    jQuery("#jform_strDescripcionObra").val(lstUbicacionesGeo[indexArray].descGrafico);
    //
    lstCoordenadas = lstUbicacionesGeo[indexArray].lstCoordenadas;
    if (lstUbicacionesGeo[indexArray].tpoGrafico != 4) {
        //  Se oculta la tabla de circulo y se muestra la tabla de puntos.
        jQuery("#puntos-table").css("display", "block");
        jQuery("#radio-table").css("display", "none");

        //  Limpio la tabla y la lleno con las coordenadas del grafico a ser editado.
        eliminarFilas(jQuery('#tbLstPuntos tr'));

        for (var j = 0; j < lstCoordenadas.length; j++) {
            if (lstCoordenadas[j].published == 1)
                addFilaPuntos(lstCoordenadas[j]);
        }
    } else {
        // Se oculta la tabla de puntos y se muestra la tabla de circulo.
        jQuery("#puntos-table").css("display", "none");
        jQuery("#radio-table").css("display", "block");
        jQuery('#obras-radio').css("display", "block");
        jQuery('#btnAddCoordenada').attr('disabled', 'disabled');

        eliminarFilas(jQuery('#tbLstCirculo tr'));
        addCircleRow(lstCoordenadas[0], lstCoordenadas[1]);
    }
    jQuery("#btnAddGrafico").attr("value", "Guardar Cambios");
}

/**
 * 
 * @returns {Number}
 */
function countRegistros() {
    var reg = 0;
    for (var j = 0; j < lstCoordenadas.length; j++) {
        if (lstCoordenadas[j].published == 1)
            reg++;
    }
    return reg;
}

