jQuery(document).ready(function() {

    //  Bandera para la gestion deun componente
    var idCmpUpd = -1;

    //  Bandera para la gestion de una actividad
    var idActUpd = -1;

    //  Deshabilito input del formulario del componentes
    disabledInputCmp();

    //  Deshabilito input de formulario de actividades
    disabledInputActividades();

    //  Creo el objeto marco logico de tipo global
    objMarcoLogico = new MarcoLogico();

    //  Verifico si el objeto marco logico tiene informacion
    if (jQuery('#jform_dtaMarcoLogico').val().length > 0) {
        updObjMarcoLogico(jQuery('#jform_dtaMarcoLogico').val());
    }

    jQuery("#tabCmpML").on("click", function() {
        if (jQuery("#jform_txtNombreFin").val() == '' || jQuery("#jform_txtNombreProposito").val() == '') {
            jQuery("#btnAddMLComponente").css("display", "none");
            jQuery("#smsAddCmpML").css("display", "block");
            jQuery('#btnLimpiarFrmComponente').trigger('click');
        } else {
            jQuery("#btnAddMLComponente").css("display", "block");
            jQuery("#smsAddCmpML").css("display", "none");
        }
    });


    jQuery("#tabActML").on("click", function() {
        if (objMarcoLogico.lstComponentes.length > 0) {
            jQuery("#btnAddMLActividad").css("display", "block");
            jQuery("#smsAddActML").css("display", "none");
        } else {
            jQuery("#btnAddMLActividad").css("display", "none");
            jQuery("#smsAddActML").css("display", "block");
            jQuery('#btnLimpiarFrmActividad').trigger('click');
        }
    });


/////////////////////
//  FIN
/////////////////////

    //  Registro Informacion de marco logico de tipo "Fin"
    jQuery('#jform_txtNombreFin, #jform_strMLFin').live('change', function() {
        var nombreFin = jQuery('#jform_txtNombreFin').val();
        var descFin = jQuery('#jform_strMLFin').val();

        //  Actualizo informacion de nombre de Fin en la pestaña componente
        jQuery('#nombreFin').attr('value', nombreFin);

        if (typeof (objMarcoLogico.fin) == "undefined") {
            //  Creo el objeto marco logico
            var objFin = new mlFin();

            //  Registro informacion de un NUEVO, Marco Logico de tipo Fin
            objFin.addMlFin(0, nombreFin, descFin);

            //  Registro ObjFin
            objMarcoLogico.addFin(objFin);
        } else {
            //  Si existe actualizo informacion
            objMarcoLogico.fin.updFin(nombreFin, descFin);
        }
    })

/////////////////////
//  PROPOSITO
/////////////////////

    //  Registro Informacion de marco logico de tipo "Proposito"
    jQuery('#jform_txtNombreProposito, #jform_strMLProposito').change(function() {
        var nombreProposito = jQuery('#jform_txtNombreProposito').val();
        var descProposito = jQuery('#jform_strMLProposito').val();

        //  Actualizo informacion de nombre de Proposito en la pestaña componente
        jQuery('#nombreProposito').attr('value', nombreProposito);

        if (typeof (objMarcoLogico.proposito) == "undefined") {
            var objProposito = new mlProposito();
            objProposito.addProposito(0, nombreProposito, descProposito);
            objMarcoLogico.addProposito(objProposito);
        } else {
            objMarcoLogico.proposito.updProposito(nombreProposito, descProposito);
        }
    })

/////////////////////
//  COMPONENTE
/////////////////////

    /**
     * Deshabilito elementos del formulario para el registro de un componente
     * @returns {undefined}
     */
    function disabledInputCmp()
    {
        var infoFin = jQuery('#jform_txtNombreFin').val();
        var infoProposito = jQuery('#jform_txtNombreProposito').val();

        jQuery('#nombreFin').attr('value', infoFin);
        jQuery('#nombreProposito').attr('value', infoProposito);

        jQuery('#jform_txtNombreComponente').attr('value', '');
        jQuery('#jform_strMLComponente').attr('value', '');

        jQuery('#jform_txtNombreComponente').attr('disabled', 'disabled');
        jQuery('#jform_strMLComponente').attr('disabled', 'disabled');
        jQuery('#btnSaveMLComponente').attr('disabled', 'disabled');
    }

    /**
     * 
     * Habilita elementos del formulario para el regisro de un componente
     * 
     * @returns {undefined}
     * 
     */
    function enabledInputCmp()
    {
        var txtNomCmp = jQuery('#jform_txtNombreComponente');
        var txtDescCmp = jQuery('#jform_strMLComponente');

        //  Activo elementos de un formulario
        txtNomCmp.removeAttr('disabled');
        txtDescCmp.removeAttr('disabled');
        jQuery('#btnSaveMLComponente').removeAttr('disabled');
    }

    //<editor-fold defaultstate="collapsed" desc="eventos sobre el componente">
    
    /**
     *  Solicito el registro de un nuevo componente
     */
    jQuery('#btnAddMLComponente').live('click', function() {
        if (idCmpUpd != -1) {
            resetFrmCmp();
        }
        
        jQuery('#imgCompoente').css("display", "none");
        jQuery('#frmCompoente').css("display", "block");
        enabledInputCmp();
    });

    //  Registro un nuevo componente
    jQuery('#btnSaveMLComponente').live('click', function() {

        if (validDtaCmp()) {
            var txtNomCmp = jQuery('#jform_txtNombreComponente');
            var txtDescCmp = jQuery('#jform_strMLComponente');

            var objCmp = new Componente();

            var idReg = (idCmpUpd == -1)? objMarcoLogico.lstComponentes.length
                                        : idCmpUpd;

            objCmp.addComponente(idReg, 0, txtNomCmp.val(), txtDescCmp.val());

            if ( !objMarcoLogico.existeRegComponente(objCmp) || (objMarcoLogico.existeRegComponente(objCmp) && objCmp.idRegComponente == idCmpUpd ) ) {

                if( idCmpUpd === -1 ){
                    //  Agrego un nuevo componente a la lista de componentes
                    objMarcoLogico.lstComponentes.push(objCmp);

                    //  Agrego una nueva fila con informacion del componente
                    addFilaComponente(objCmp);

                    //  Actualizo lista con informacion de componentes
                    updLstComponentes();

                    //  Desahabilito elementos del formulario
                    disabledInputCmp();
                } else {
                    //  Actualizo contenido del componente
                    objMarcoLogico.updComponente(idCmpUpd, objCmp);

                    //  Actualizo informacion de al fila en el componente
                    updFilaComponente(objCmp);

                    //  Regreso a false la bandera de actualizacion del componente
                    idCmpUpd = -1;
                }

                resetFrmCmp();
                
            } else {
                jAlert('Componente ya registrado', 'SIITA - ECORAE');
            }

        } else {
            jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
        }

    });

    //  Actualizo informacion de un determinado componente
    jQuery('.updMLCmp').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Obtengo informacion de un determinado componente
        var dtaCmp = objMarcoLogico.getInfoComponente(idFila);

        if (dtaCmp != false) {
            //  habilito elementos del formulario
            enabledInputCmp();
            jQuery('#imgCompoente').css("display", "none");
            jQuery('#frmCompoente').css("display", "block");
            jQuery('#jform_txtNombreComponente').attr('value', dtaCmp.nombreCmp);
            jQuery('#jform_strMLComponente').attr('value', dtaCmp.descripcionCmp);

            idCmpUpd = idFila;
        }
    });

    //  Elimino un componente de un determinado marco logico
    jQuery('.delMLCmp').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
            if (result) {
                if (objMarcoLogico.delComponente(idFila)) {
                    //  Elimino una fila de la tabla de la lista GAP
                    delFilaTabla(jQuery('#lstMLComponentes tr'), idFila);
                    resetFrmCmp();

                }
            }
        });


    });


    jQuery("#btnLimpiarFrmComponente").live("click", function() {
        resetFrmCmp();
    });

    /**
     *  Retorna TRUE si la data esta correcta, caso contrario retorna FALSE
     * @returns {Boolean}
     */
    function validDtaCmp()
    {
        var result = true;
        if (jQuery('#jform_txtNombreComponente').val() == "" 
                || jQuery('#nombreFin').val() == "" 
                || jQuery("#nombreProposito").val() == "") {
            result = false;
        }
        
        return result;
    }
    
    function resetFrmCmp()
    {
        jQuery('#imgCompoente').css("display", "block");
        jQuery('#frmCompoente').css("display", "none");
        
        jQuery('#jform_txtNombreComponente').val("");
        jQuery('#jform_strMLComponente').val("");
        idCmpUpd = -1;
        
        cleanValidateForn ( "#frmCmpML" );
        
    }

    //</editor-fold>

    /**
     * 
     * Agrego una nueva fila a la tabla de componentes
     * 
     * @param {Objeto} dtaComponente  Datos de un componente
     * 
     * @returns {undefined}
     * 
     */
    function addFilaComponente(dtaComponente)
    {
        //  Construyo la Fila
        var fila = "<tr id='"+ dtaComponente.idRegComponente +"'>"
                + " <td align='center'>" + dtaComponente.nombreCmp + "</td>"
                + " <td align='center'>" + dtaComponente.descripcionCmp + "</td>"
                + " <td align='center' width='20px'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=" + jQuery('#idProyecto').val() + "&idTipoML=3&idRegML=" + dtaComponente.idRegComponente + "&idML=" + dtaComponente.idMlComponente + "&layout=edit&tmpl=component&task=preview\", {size:{x:" + COM_PROYECTOS_POPUP_ANCHO + ", y: " + COM_PROYECTOS_POPUP_ALTO + "}, handler:\"iframe\"} );'> " + COM_PROYECTOS_MEDIO_VRF_TITLE + " </a> </td>"
                + " <td align='center' width='20px'> <a class='updMLCmp'> Editar </a> </td>"
                + " <td align='center' width='20px'> <a class='delMLCmp'> Eliminar </a> </td>"
                + "  </tr>";

        //  Agrego la fila creada a la tabla
        jQuery('#lstMLComponentes > tbody:last').append(fila);
    }

    /**
     * 
     * Actualizo informacion en a fila actualizada
     * 
     * @param {objeto} dataUpd  Informacion de un componente
     * 
     * @returns {undefined}
     */
    function updFilaComponente(dataUpd)
    {
        jQuery('#lstMLComponentes tr').each(function() {
            if (jQuery(this).attr('id') == parseInt(dataUpd.idRegComponente)) {

                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');

                //  Construyo la Fila
                var fila = "    <td align='center'>" + dataUpd.nombreCmp + "</td>"
                        + "     <td align='center'>" + dataUpd.descripcionCmp + "</td>"
                        + "     <td align='center' width='20px'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=" + jQuery('#idProyecto').val() + "&idTipoML=3&idRegML=" + dataUpd.idRegComponente + "&idML=" + dataUpd.idMlComponente + "&layout=edit&tmpl=component&task=preview\", {size:{x:" + COM_PROYECTOS_POPUP_ANCHO + ", y: " + COM_PROYECTOS_POPUP_ALTO + "}, handler:\"iframe\"} );'> " + COM_PROYECTOS_MEDIO_VRF_TITLE + " </a> </td>"
                        + "     <td align='center' width='20px'> <a class='updMLCmp'> Editar </a> </td>"
                        + "     <td align='center' width='20px'> <a class='delMLCmp'> Eliminar </a> </td>";

                jQuery(this).html(fila);
            }
        })
    }

    /**
     * 
     * Elimino una determinada fila de la tabla
     * 
     * @param {type} idFila     Identificador de la fila a eliminar
     * @returns {undefined}
     */
    function delFilaTabla(tabla, idFila)
    {
        //  Elimino fila de la tabla lista de GAP
        tabla.each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        })
    }

    /**
     * 
     * Actualizo lista de componentes
     * 
     * @returns {undefined}
     */
    function updLstComponentes()
    {
        var lstCmp = objMarcoLogico.lstComponentes;
        var nrlc = lstCmp.length;
        var items = [];

        if (nrlc > 0) {
            items.push('<option value="0">SELECCIONE UN COMPONENTE</option>');

            for (var x = 0; x < nrlc; x++) {
                items.push('<option value="rc-' + lstCmp[x].idRegComponente + '">' + lstCmp[x].nombreCmp + '</option>');
            }
        } else {
            items.push('<option value="0">Sin Registros Disponibles</option>');
        }

        jQuery('#jform_cbMLComponente').html(items.join(''));
    }

    //<editor-fold defaultstate="collapsed" desc="ACTIVIDADES">
    
    /**
     * Deshabilito elementos del formulario para el registro de un componente
     * @returns {undefined}
     */
    function disabledInputActividades()
    {
        //  Vacio contenido de nombre de una determinada actividad
        jQuery('#jform_txtNombreActividad').attr('value', '');

        //  Vacio contenido de un 
        jQuery('#jform_strMLActividad').attr('value', '');

        jQuery('#jform_cbMLComponente').attr('disabled', 'disabled');
        jQuery('#jform_txtNombreActividad').attr('disabled', 'disabled');
        jQuery('#jform_strMLActividad').attr('disabled', 'disabled');
        jQuery('#btnSaveMLActividad').attr('disabled', 'disabled');
    }


    function enabledInputActividades()
    {
        //  Vacio contenido de nombre de una determinada actividad
        jQuery('#jform_txtNombreActividad').attr('value', '');

        //  Vacio contenido de la descripcion de una actividad
        jQuery('#jform_strMLActividad').attr('value', '');

        jQuery('#jform_cbMLComponente').removeAttr('disabled', 'disabled');
        jQuery('#jform_txtNombreActividad').removeAttr('disabled', 'disabled');
        jQuery('#jform_strMLActividad').removeAttr('disabled', 'disabled');
        jQuery('#btnSaveMLActividad').removeAttr('disabled', 'disabled');
    }
    
    /**
     * Agrego una nueva fila a la tabla de actividades
     * @param {Objeto} dtaActividad  Datos de un componente
     * @returns {undefined}
     */
    function addFilaActividad(dtaActividad)
    {
        //  Construyo la Fila
        var fila = "<tr id='" + dtaActividad.idRegComponente + '|' + dtaActividad.idRegActividad + "'>"
                + " <td align='center'>" + dtaActividad.nombreCmp + "</td>"
                + " <td align='center'>" + dtaActividad.nombreAct + "</td>"
                + " <td align='center'>" + dtaActividad.descripcionAct + "</td>"
                + " <td align='center' width='20px'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=" + jQuery('#idProyecto').val() + "&idTipoML=4&idRegCmp=" + dtaActividad.idRegComponente + "&idRegAct=" + dtaActividad.idRegActividad + "&layout=edit&tmpl=component&task=preview\", {size:{x:" + COM_PROYECTOS_POPUP_ANCHO + ", y: " + COM_PROYECTOS_POPUP_ALTO + "}, handler:\"iframe\"} );'> " + COM_PROYECTOS_MEDIO_VRF_TITLE + "</a> </td>"
                + " <td align='center' width='20px'> <a class='updMLAct'> Editar </a> </td>"
                + " <td align='center' width='20px'> <a class='delMLAct'> Eliminar </a> </td>"
                + "  </tr>";

        //  Agrego la fila creada a la tabla
        jQuery('#lstMLActividades > tbody:last').append(fila);
    }

    /**
     * Actualizo informacion en a fila actualizada
     * @param {objeto} dataUpd  Informacion de un componente
     * @returns {undefined}
     */
    function updFilaActividad(idCmp, idAct, dataUpd)
    {
        var idFila = idCmp + '|' + idAct;

        jQuery('#lstMLActividades tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {

                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');

                //  Construyo la Fila
                var fila = "<td align='center'>" + dataUpd.nombreCmp + "</td>"
                        + " <td align='center'>" + dataUpd.nombreAct + "</td>"
                        + " <td align='center'>" + dataUpd.descripcionAct + "</td>"
                        + " <td align='center' width='20px'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=" + jQuery('#idProyecto').val() + "&idTipoML=4&idRegML=" + dataUpd.idRegActividad + "&idRegML=" + dataUpd.idRegActividad + "&layout=edit&tmpl=component&task=preview\", {size:{x:" + COM_PROYECTOS_POPUP_ANCHO + ", y: " + COM_PROYECTOS_POPUP_ALTO + "}, handler:\"iframe\"} );'> " + COM_PROYECTOS_MEDIO_VRF_TITLE + "</a> </td>"
                        + " <td align='center' width='20px'> <a class='updMLAct'> Editar </a> </td>"
                        + " <td align='center' width='20px'> <a class='delMLAct'> Eliminar </a> </td>";

                jQuery(this).html(fila);
            }
        })
    }


    //  Elimino un componente de un determinado marco logico
    jQuery('.delMLAct').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        var infoAct = idFila.split('|');

        var idCmp = infoAct[0];
        var idAct = infoAct[1];

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
            if (result) {
                if (objMarcoLogico.delActividadComponente(idCmp, idAct)) {
                    //  Elimino una fila de la tabla de la lista de Actividades pertenecientes a un determinado objeto
                    delFilaTabla(jQuery('#lstMLActividades tr'), idFila);
                    if ( idActUpd != -1 && idAct == idActUpd ) {
                        resetFrmActividad();
                    }
                }
            }
        });
    })
    
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de los eventos de actividad">
    //  Agrego informacion de una nueva Actividad
    jQuery('#btnAddMLActividad').live('click', function() {
        //  Activo formulario de actividades
        jQuery('#imgActividad').css("display", "none");
        jQuery('#frmActividad').css("display", "block");
        enabledInputActividades();
    });

    //  Agregar una actividad a una lista de actividades de un determinado componente
    jQuery('#btnSaveMLActividad').live('click', function() {
        
        if ( validDtaActividad() ) {
            var regCmp = jQuery('#jform_cbMLComponente :selected').val().split("-");
            var idRegCmp = regCmp[1];
            var cmp = jQuery('#jform_cbMLComponente :selected').text();
            var nomAct = jQuery('#jform_txtNombreActividad').val();
            var descAct = jQuery('#jform_strMLActividad').val();

            var idRegAct = objMarcoLogico.lstComponentes[idRegCmp].lstActividades.length;

            var objActividad = new Actividad();
            objActividad.addActividad(idRegAct, idRegCmp, cmp, 0, nomAct, descAct);

            if (idActUpd == -1) {
                //  Agrego actividades a un determinado componente
                objMarcoLogico.lstComponentes[idRegCmp].lstActividades.push(objActividad);

                //  Agrego una nueva fila a la lista de componentes
                addFilaActividad(objActividad);
            } else if (idActUpd != -1) {
                objMarcoLogico.lstComponentes[idCmpUpd].lstActividades[idActUpd].nombreAct = nomAct;
                objMarcoLogico.lstComponentes[idCmpUpd].lstActividades[idActUpd].descripcionAct = descAct;

                //  Actualizo fila de la actividad
                updFilaActividad(idCmpUpd, idActUpd, objActividad);

                idCmpUpd = -1;
                idActUpd = -1;
            }

            //  Deshabilito el formulario de actividades
            disabledInputActividades();
            resetFrmActividad();
            
        } else {
            jAlert( JSL_SMS_ALL_OBLIGATORY, JSL_ECORAE );
        }
        
        
    });

    //  Gestiono la edicion de una Actividad en un determinado MarcoLogico
    jQuery('.updMLAct').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        var infoAct = idFila.split('|');
        var regCmp = "rc-" + infoAct[0];

        recorrerCombo(jQuery('#jform_cbMLComponente option'), regCmp);

        jQuery('#jform_cbMLComponente').attr('disabled', 'disabled');

        idCmpUpd = infoAct[0];
        idActUpd = infoAct[1];

        var dtaInfoLstCmp = objMarcoLogico.lstComponentes[idCmpUpd].lstActividades[idActUpd];

        jQuery('#jform_txtNombreActividad').attr('value', dtaInfoLstCmp.nombreAct);
        jQuery('#jform_strMLActividad').attr('value', dtaInfoLstCmp.descripcionAct);

        jQuery('#jform_txtNombreActividad').removeAttr('disabled');
        jQuery('#jform_strMLActividad').removeAttr('disabled');
        jQuery('#btnSaveMLActividad').removeAttr('disabled');
        jQuery("#frmActividad").css("display", "block");
        jQuery("#imgActividad").css("display", "none");
    });

    //  Gestiono el evento click del boton limpiar
    jQuery('#btnLimpiarFrmActividad').live('click', function() {
        resetFrmActividad();
        disabledInputActividades();
    });
    
    /**
     * 
     * @returns {Boolean}
     */
    function validDtaActividad()
    {
        var result = true;
        if ( jQuery('#jform_cbMLComponente :selected').val() == 0 
                || jQuery('#jform_txtNombreActividad').val() == "") {
            result = false;
        }
        
        return result;
    }
    
    /**
     * 
     * @returns {undefined}
     */
    function resetFrmActividad()
    {
        jQuery('#imgActividad').css("display", "block");
        jQuery('#frmActividad').css("display", "none");
        
        jQuery('#jform_cbMLComponente').val(0);
        jQuery('#jform_txtNombreActividad').val("");
        jQuery('#jform_strMLActividad').val("");
        
        idActUpd = -1;
        
        cleanValidateForn( "#frmActML" );
        
    }
    
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="CREACION DE OBJETO">
    
    /**
     *  Recorre los comboBox del Formulario a la posicion inicial
     * @param {type} combo
     * @param {type} posicion
     * @returns {undefined}
     */
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }


    /**
     * Gestiono la creacion del objeto marco logico
     * @param {JSon} dtaML  Informacion del Marco Logico de un determinado proyecto
     * @returns {undefined}
     */
    function updObjMarcoLogico(dtaML)
    {
        var ml = eval("(" + dtaML + ")");

        //  FIN
        var objFin = new mlFin();
        if (ml.Fin) {
            objFin.addMlFin(ml.Fin.idMLFin, ml.Fin.nombre, ml.Fin.descripcion);
            if (ml.Fin.lstMediosVerificacion != false) {
                for (var x = 0; x < ml.Fin.lstMediosVerificacion.length; x++) {
                    objFin.lstMedVerificacion.push(ml.Fin.lstMediosVerificacion[x]);
                }
            }

            if (ml.Fin.lstSupuestos != false) {
                for (var x = 0; x < ml.Fin.lstSupuestos.length; x++) {
                    objFin.lstSupuestos.push(ml.Fin.lstSupuestos[x]);
                }
            }
        }

        //  Agrego el objeto Fin al Objeto Marco Logico
        objMarcoLogico.addFin(objFin);

        //  PROPOSITO
        var objProposito = new mlProposito();
        if (ml.Proposito) {
            objProposito.addProposito(ml.Proposito.idMLProposito, ml.Proposito.nombre, ml.Proposito.descripcion);

            if (ml.Proposito.lstMediosVerificacion != false) {
                for (var x = 0; x < ml.Proposito.lstMediosVerificacion.length; x++) {
                    objProposito.lstMedVerificacion.push(ml.Proposito.lstMediosVerificacion[x]);
                }
            }

            if (ml.Proposito.lstSupuestos != false) {
                for (var x = 0; x < ml.Proposito.lstSupuestos.length; x++) {
                    objProposito.lstSupuestos.push(ml.Proposito.lstSupuestos[x]);
                }
            }
        }

        //  Agrego el objeto Proposito al Objeto Marco Logico
        objMarcoLogico.addProposito(objProposito);

        //  LISTA DE COMPONENTES
        if ( typeof( ml.lstComponentes ) !== 'undefined' ) {
            if( ml.lstComponentes.length > 0 ){
                var nrlc = ml.lstComponentes.length;
                for( var x = 0; x < nrlc; x++ ){
                    var cmp = new Componente();
                    cmp.addComponente(  x, 
                                        ml.lstComponentes[x].idMLCmp, 
                                        ml.lstComponentes[x].nombre, 
                                        ml.lstComponentes[x].descripcion );

                    //  Agrego medios de verificacion de un determinado componente
                    if ( ml.lstComponentes[x].lstMediosVerificacion.length > 0 ) {
                        for (var y = 0; y < ml.lstComponentes[x].lstMediosVerificacion.length; y++) {
                            cmp.lstMedVerificacion.push(ml.lstComponentes[x].lstMediosVerificacion[y]);
                        }
                    }

                    //  Agrego medios de verficacion de un determinado supuesto
                    if ( ml.lstComponentes[x].lstSupuestos.length > 0 ) {
                        for (var y = 0; y < ml.lstComponentes[x].lstSupuestos.length; y++) {
                            cmp.lstSupuestos.push(ml.lstComponentes[x].lstSupuestos[y]);
                        }
                    }

                    if ( typeof (ml.lstComponentes[x].lstActividad) !== 'undefined' ) {
                        var nrla = ml.lstComponentes[x].lstActividad.length;
                        for (var z = 0; z < nrla; z++) {
                            var objAct = new Actividad();
                            objAct.addActividad(    z,
                                                    ml.lstComponentes[x].idMLCmp,
                                                    ml.lstComponentes[x].nombre,
                                                    ml.lstComponentes[x].lstActividad[z].idMLActividad,
                                                    ml.lstComponentes[x].lstActividad[z].nombre,
                                                    ml.lstComponentes[x].lstActividad[z].descripcion,
                                                    ml.lstComponentes[x].lstActividad[z].lstMedioVerificacion,
                                                    ml.lstComponentes[x].lstActividad[z].lstSupuesto );

                            cmp.lstActividades.push(objAct);
                        }
                    }

                    objMarcoLogico.lstComponentes.push(cmp);
                }

                //  Actualizo contenido de componentes
                updLstComponentes();
            }
        }
    }
        
    //</editor-fold>

});