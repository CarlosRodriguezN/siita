var flagUpdObj = false;

jQuery(document).ready(function() {

    var flagObjetivo = -1;

    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;

    /**
     * 
     */

    if (jQuery('#jform_lstObjetivos').val() != '') {
        upsLstObjetivos(jQuery('#jform_lstObjetivos').val());
    } else {
        objLstObjetivo = new GestionObjetivos();
    }

    /**
     * 
     * Inicia con la gestion de los objetivos
     * @param {type} lstObjetivos
     * @returns {undefined}
     */
    function upsLstObjetivos(lstObjetivos)
    {
        objLstObjetivo = new GestionObjetivos();
        var dtaLstObjetivos = eval(lstObjetivos);
        for (var x = 0; x < dtaLstObjetivos.length; x++) {
            var oObjetivo = new Objetivo();
            oObjetivo.setDtaObjetivo(dtaLstObjetivos[x]);
            
            agregarFilaObjetivo(oObjetivo);
            objLstObjetivo.addObjetivo(oObjetivo);
            validateSemaforoObjetivo(x);
        }
    }


    /**
     *  Habilita el formulario de data general de un objetivo
     */
    jQuery('#addObjetivoTable').click(function() {
        //  Si se esta editando un grafico, flagGrafico tiene el id del grafico editandose.
        if (flagObjetivo != -1) {
            var id = -1;
            guardarCambios(id, true);
        } else {
            jQuery("#frmObjetivo").css("display", "block");
            jQuery("#imgObjetivo").css("display", "none");
        }
    });

    /**
     *  Guarda la data general de un objetivo de un Pei
     */
    jQuery("#btnAddObj").click(function() {
        if (objValido()) {
            if (flagObjetivo == -1) {
                //  Creo el Objeto Objetivo
                var objObjetivo = new Objetivo();

                objObjetivo.registroObj = objLstObjetivo.lstObjetivos.length;

                objObjetivo.descObjetivo = jQuery("#jform_strDescripcion_ob").val();
              //cm  objObjetivo.descTpoObj = jQuery("#jform_intId_tpoObj :selected").text();
                objObjetivo.idObjetivo = jQuery("#jform_intId_ob").val();
                objObjetivo.idPadreObj = 0;
              //cm  objObjetivo.idPrioridadObj = jQuery("#jform_intPrioridad_ob :selected").val();

                objObjetivo.published = 1;

                //  Agrego un objetivo a la lista de Objetivos
                objLstObjetivo.addObjetivo(objObjetivo);

                agregarFilaObjetivo(objObjetivo);
            } else {
                var numReg = objLstObjetivo.lstObjetivos.length;
                for (var i = 0; i < numReg; i++) {
                    if (objLstObjetivo.lstObjetivos[i].registroObj == flagObjetivo) {
                        objLstObjetivo.lstObjetivos[i].descObjetivo = jQuery("#jform_strDescripcion_ob").val();
                        actualizarFila(objLstObjetivo.lstObjetivos[i]);
                        flagObjetivo = -1;
                    }
                }
            }
            //  Bandera para actualizacion para PPPP y PAPP
            flagUpdObj = true;
            //  limpio el formulario y reinicio la variables
            resetFrmObj();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }
    });

    /**
     *  Cancela la edicion de un registro de objetivo
     */
    jQuery("#btnCancel").click(function() {
        var id = -1;
        if (flagObjetivo != -1) {
            guardarCambios(id, false);
        } else {
            resetFrmObj();
        }
    });

    /**
     *  Clase que permite la edición de un objetivo de un Pei
     */
    jQuery('.updObjetivo').live('click', function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        if (flagObjetivo != -1 && flagObjetivo != idFila) {
            guardarCambios(idFila, true)
        } else {
            jQuery("#updObj-" + flagObjetivo).html("Editar");
            flagObjetivo = idFila;
            updDataObj(idFila);
        }
    });

    /**
     *  Verifica si el objetivo se puede o no eliminar 
     */
    jQuery(".delObjetivo").live('click', function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        var planAccionObj = objLstObjetivo.lstObjetivos[idFila].planAccion;
//        var actividadesObj = objLstObjetivo.lstObjetivos[idFila].lstActividades;
//        var indicadoresObj = objLstObjetivo.lstObjetivos[idFila].lstIndicadores;
//        if (avalibleDel(planAccionObj) && avalibleDel(actividadesObj) && avalibleDel(indicadoresObj)) {
        if (avalibleDel(planAccionObj)) {
            jConfirm(JSL_CONFIRM_DEL_OBJETIVO, JSL_ECORAE, function(resutl) {
                if (resutl) {
                    objLstObjetivo.lstObjetivos[idFila].published = 0;
                    //  Bandera para actualizacion para PPPP y PAPP
                    flagUpdObj = true;
                    delFila(idFila);
                }
            });
        } else {
            jAlert("El objetivo no se puede eliminar porque tiene relaciones existentes", JSL_ECORAE);
        }
    });

    /**
     *  Verifica que los campos obligatorios han sido ingresados
     *  
     * @returns {Boolean}
     */
    function objValido()
    {
        var result = false;
        var desObj = jQuery("#jform_strDescripcion_ob").val();
        if (desObj != "" )
            result = true;
        return result;
    }


    /**
     *  Agrega una fila en la tablas de objetivos de un Pei
     *  
     * @param {type} objetivo     Array con los atributos del objetivo
     * @returns {undefined}
     */
    function agregarFilaObjetivo(objetivo)
    {
        var idReg = objetivo.registroObj;
        var fila = '';
        fila += '<tr id="' + idReg + '">';
        fila += '     <td align="center">' + objetivo.descObjetivo + '</td>';

        //  Alineacion de un Objetivo
        fila += '     <td align="center" width="15" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_alineacion&view=alineacion&layout=edit&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
        fila += '          <img class="sprite-alineacion"  id="smfPla-' + idReg + '" title="' + COM_CONTRATOS_TITLE_ALINEACION + '">';
        fila += '        </a>';
        fila += '     </td>';

        //  Indicador Meta del Objetivo
        fila += '     <td align="center" width="15">';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicador&layout=edit&idRegObjetivo=' + idReg + '&tpo=meta&tpoIndicador=programa&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
        fila += '          <img class="sprite-meta "  id="smfIndMeta-' + idReg + '" title="' + COM_CONTRATOS_TITLE_META + '">';
        fila += '        </a>';
        fila += '     </td>';

        //  Accion del Objetivo
        fila += '     <td align="center" width="15" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_accion&view=plnaccion&layout=edit&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
        fila += '           <img class="sprite-acciones"  id="smfAcc-' + idReg + '" title="' + COM_CONTRATOS_TITLE_ACCION + '">';
        fila += '        </a>';
        fila += '     </td>';

        //  Indicadores del Objetivo
        fila += '     <td align="center" width="15" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicadores&layout=edit&tpoIndicador=pei&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
        fila += '           <img class="sprite-indicadores"  id="smfInd-' + idReg + '" title="' + COM_CONTRATOS_TITLE_INDICADOR + '">';
        fila += '        </a>';
        fila += '     </td>';

        fila += '     <td align="center" width="15" > <a id="updObj-' + idReg + '" class="updObjetivo" >Editar</a> </td > ';
        fila += '     <td align="center" width="15" > <a id="delObj-' + idReg + '" class="delObjetivo" >Eliminar</a> </td>';
        fila += ' </tr>';

        //  Agrego la fila creada a la tabla
        jQuery('#tbLstObjetivos > tbody:last').append(fila);
        validateSemaforoObjetivo(idReg);
    }
    
    /**
     *  Actulida la informacion de una fila en la tabla de objetivos de un Pei
     *  
     * @param {type} objetivo     Array con los atributos del objetivo
     * @returns {undefined}
     */
    function actualizarFila(objetivo)
    {
        var idReg = objetivo["registroObj"];
        jQuery('#tbLstObjetivos tr').each(function() {
            if (jQuery(this).attr('id') == flagObjetivo) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');
                //  Construyo la Fila
                var fila = '';
                fila += '     <td align="center">' + objetivo["descObjetivo"] + '</td>';

                //  Alineacion de un Objetivo
                fila += '     <td align="center" width="15" >';
                fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_alineacion&view=alineacion&layout=edit&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
                fila += '          <img class="sprite-alineacion"  id="smfPla-' + idReg + '" title="' + COM_CONTRATOS_TITLE_ALINEACION + '">';
                fila += '        </a>';
                fila += '     </td>';

                //  Indicador Meta del Objetivo
                fila += '     <td align="center" width="15">';
                fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicador&layout=edit&registroObj=' + idReg + '&tpoIndicador=meta&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
                fila += '          <img class="sprite-meta"  id="smfIndMeta-' + idReg + '" title="' + COM_CONTRATOS_TITLE_META + '">';
                fila += '        </a>';
                fila += '     </td>';

                //  Accion del Objetivo
                fila += '     <td align="center" width="15" >';
                fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_pei&view=plnaccion&layout=edit&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
                fila += '           <img class="sprite-acciones"  id="smfAcc-' + idReg + '" title="' + COM_CONTRATOS_TITLE_ACCION + '">';
                fila += '        </a>';
                fila += '     </td>';

                //  Indicadores del Objetivo
                fila += '     <td align="center" width="15" >';
                fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicadores&layout=edit&tpoIndicador=pei&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:1024,y:500}, handler:\'iframe\'} );">';
                fila += '           <img class="sprite-indicadores"  id="smfInd-' + idReg + '" title="' + COM_CONTRATOS_TITLE_INDICADOR + '">';
                fila += '        </a>';
                fila += '     </td>';

                fila += '     <td align="center" width="15" > <a id="updObj-' + idReg + '" class="updObjetivo" >Editar</a> </td > ';
                fila += '     <td align="center" width="15" > <a id="delObj-' + idReg + '" class="delObjetivo" >Eliminar</a> </td>';

                jQuery(this).html(fila);
                validateSemaforoObjetivo(idReg);
            }
        });

    }

    /**
     *  Controla si de a modificado la data de un objetivo para guardarlo o no.
     * 
     * @param {type} idReg      Id de registro del objetivo (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function guardarCambios(idReg, op)
    {
        if (confirmUpdObj(flagObjetivo)) {
            autoSave(idReg, op);
        } else {
            controlAutoSave(idReg, op);
        }
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     *  solo llama a la funcion "controlAutoSave" que reliza lops controles de edicion.
     * 
     * @param {type} idFila     Id de registro del objetivo (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function autoSave(idFila, op)
    {
        jConfirm(JSL_CONFIRM_UPD_OBJETIVO, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnAddObj').trigger('click');
                controlAutoSave(idFila, op);
            } else {
                controlAutoSave(idFila, op);
            }
        });
    }

    /**
     *  Realiza las tareas especificas cuando guarda cambios en un registro
     * 
     * @param {type} idFila     Id de registro de la Acción (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function controlAutoSave(idFila, op)
    {
        jQuery("#updObj-" + flagObjetivo).html("Editar");
        if (idFila != -1) {
            flagObjetivo = idFila;
            updDataObj(idFila);
        } else {
            flagObjetivo = -1;
            resetFrmObj();
            if (op == true) {
                jQuery("#frmObjetivo").css("display", "block");
                jQuery("#imgObjetivo").css("display", "none");
            }
        }
    }


    /**
     *  Limpia y seta las variables utilisadas al momento de crear o editar un Objetivo
     * 
     * @returns {undefined}
     */
    function resetFrmObj()
    {
        jQuery("#frmObjetivo").css("display", "none");
        jQuery("#imgObjetivo").css("display", "block");
   //cm     recorrerCombo(jQuery('#jform_intId_tpoObj option'), 0);
   //cm     recorrerCombo(jQuery('#jform_intPrioridad_ob option'), 0);
        jQuery("#jform_strDescripcion_ob").attr('value', '');
    }

    /**
     *  Caraga la data de un objetivo para ser modificada
     * 
     * @param {type} idFila
     * @returns {undefined}
     */
    function updDataObj(idFila)
    {
        var numReg = objLstObjetivo.lstObjetivos.length;
        for (var i = 0; i < numReg; i++) {
            if (objLstObjetivo.lstObjetivos[i].registroObj == idFila) {
                jQuery("#updObj-" + idFila).html("Editando...");
                jQuery("#frmObjetivo").css("display", "block");
                jQuery("#imgObjetivo").css("display", "none");
                jQuery("#btnAddObj").removeAttr('disabled', '');
//cm                var to = objLstObjetivo.lstObjetivos[i].idTpoObj;
//cm                var pr = objLstObjetivo.lstObjetivos[i].idPrioridadObj;
//cm                recorrerCombo(jQuery('#jform_intId_tpoObj option'), to);
//cm                recorrerCombo(jQuery('#jform_intPrioridad_ob option'), pr);
                jQuery("#jform_strDescripcion_ob").val(objLstObjetivo.lstObjetivos[i].descObjetivo);
            }
        }
    }

    /**
     *  Verifica si existe algun elemento valido del array para que 
     *  no se pueda eliminar el objetivo al que pertenece
     * 
     * @param {type} data
     * @returns {Boolean}
     */
    function avalibleDel(data)
    {
        var result = true;
        if (data) {
            var numReg = data.leng;
            for (var i = 0; i < numReg; i++) {
                if (data[i].published == 1)
                    result = false;
                break;
            }
        }
        return result;
    }

    /**
     *  Recorro el combo de provincias a una determinada posicion
     * 
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
        });
    }

    /**
     *  Elimina una fila de la tabla de bjetivos de un pei
     *  
     * @param {type} idFila
     * @returns {undefined}
     */
    function delFila(idFila)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#tbLstObjetivos tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }

    /**
     *  Retorna True en el caso de que los datos de un objetivo se modificaron
     *  caso contrario devuelve False
     * 
     * @param {type} idFila     Id de registro del objetivo
     * @returns {Boolean}
     */
    function confirmUpdObj(idFila)
    {
        var resultado = false;
        for (var i = 0; i < objLstObjetivo.lstObjetivos.length; i++) {
            if (objLstObjetivo.lstObjetivos[i].registroObj == idFila) {
                if (objLstObjetivo.lstObjetivos[i].descObjetivo != jQuery("#jform_strDescripcion_ob").val()
                        //||objLstObjetivo.lstObjetivos[i].idTpoObj != jQuery("#jform_intId_tpoObj :selected").val() ||
                        //cm                      objLstObjetivo.lstObjetivos[i].idPrioridadObj != jQuery("#jform_intPrioridad_ob :selected").val() 
                        )
                    resultado = true;
            }
        }
        return resultado;
    }

    /**
     * cambia los colores de los semaforos segun el tipo
     * @param {type} reg
     * @returns {undefined}
     */
    function validateSemaforoObjetivo(reg){
        semaforoIdicadorMeta(reg);
    };
    
    /**
     * Cambia el semaforo del indicador meta.
     * @param {type} reg
     * @returns {undefined}
     */
    function semaforoIdicadorMeta(reg) {
        var id = '#smfIndMeta-' + reg;
        var val = 2;
       if (objLstObjetivo.lstObjetivos[reg] && objLstObjetivo.lstObjetivos[reg].lstIndicadores) {
            var lstIndicadoreTmp = objLstObjetivo.lstObjetivos[reg].lstIndicadores;
            var indicadorMeta = getIndMeta(lstIndicadoreTmp);
            if (indicadorMeta) {
                val = indicadorMeta.semaforoImagen();
            }
        }
        switch (val) {
            case 0:// amarillo
                jQuery(id).css('background-position', '0 0px');
                break;
            case 1:// blanco
                jQuery(id).css('background-position', '0 -66px');
                break;
            case 2:// rojo
                jQuery(id).css('background-position', '0 -132px');
                break;
            case 3:// verde
                jQuery(id).css('background-position', '0 -198px');
                break;
        }
    }
    
    /**
     * RECUPERA el INDICADOR META de un OBJETIVO
     * @param {type} lstIndObjetivos    lista de indicadores meta
     * @returns {Indicador|Boolean}
     */
    function getIndMeta( lstIndObjetivos )
    {
        var dtaIndMeta = false;
        for( var x = 0; x < lstIndObjetivos.length; x++ ){
            if( parseInt( lstIndObjetivos[x].idTpoIndicador ) == 1 ){
                var objIndMeta = new Indicador();
                objIndMeta.setDtaIndicador( lstIndObjetivos[x] );
                dtaIndMeta = objIndMeta;
                idRegIndMeta = x;

                break;
            }
        }
        
        return dtaIndMeta;
    }
});