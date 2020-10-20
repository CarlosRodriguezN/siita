jQuery(document).ready(function () {

    var flagObjetivo = -1;
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
    var idPoa = jQuery("#idRegPoa").val();
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

    if ( typeof( idPoa ) !== "undefined" && idPoa >= 0 ) {
        cargarObjetivos(idPoa);
    } else {
        jQuery("#srObj").css("display", "block");
    }

    /**
     *  Desabilita la opcion de agregar objetivos en el caso de que no haya un un plan selecionado
     */
    if (objLstPoas.lstPoas.length === 0 || jQuery("#idRegPoa").val() === '' || jQuery("#idRegPoa").val() === -1) {
        jQuery("#poaObjs").attr('disabled', true);
    }

    /**
     *  Habilita el formulario de data general de un objetivo
     */
    jQuery('.addObjetivoPoa').click(function () {
        //  Si se esta editando un grafico, flagGrafico tiene el id del grafico editandose.
        if (flagObjetivo !== -1) {
            var id = -1;
            validarCambios(id, true);
        } else {
            jQuery("#imgObjetivo").css("display", "none");
            jQuery("#frmObjetivo").css("display", "block");
        }
    });

    /**
     *  Guarda la data general de un objetivo de un Pei
     */
    jQuery(".btnAddObj").click(function () {
        var idRegPoa = jQuery("#idRegPoa").val();
        if (objValido()) {
            if (objLstPoas.lstPoas[idRegPoa].lstObjetivos.length === 0) {
                jQuery("#srObj").css("display", "none");
            }
            if (flagObjetivo === -1) {
                //  Creo el Objeto Objetivo
                var objObjetivo = new Objetivo();

                objObjetivo.registroObj = objLstPoas.lstPoas[idRegPoa].lstObjetivos.length;
                objObjetivo.idObjetivo = 0;
                objObjetivo.idPadreObj = 0;
                objObjetivo.idTpoObj = jQuery("#jform_intId_tpoObj").val();
                objObjetivo.descTpoObj = "PLAN OPERATIVO ANUAL";
                objObjetivo.idPrioridadObj = jQuery("#jform_intPrioridad_ob :selected").val();
                objObjetivo.nmbPrioridadObj = jQuery("#jform_intPrioridad_ob :selected").text();
                objObjetivo.descObjetivo = jQuery("#jform_strDescripcion_ob").val();
                objObjetivo.published = 1;

                //  Agrego un objetivo a la lista de Objetivos
                objLstPoas.lstPoas[idRegPoa].lstObjetivos.push(objObjetivo);
                agregarFila(objObjetivo, idRegPoa);

            } else {
                var numReg = objLstPoas.lstPoas[idRegPoa].lstObjetivos.length;
                for (var i = 0; i < numReg; i++) {
                    if (objLstPoas.lstPoas[idRegPoa].lstObjetivos[i].registroObj == flagObjetivo) {
                        objLstPoas.lstPoas[idRegPoa].lstObjetivos[i].descObjetivo = jQuery('#jform_strDescripcion_ob').val();
                        objLstPoas.lstPoas[idRegPoa].lstObjetivos[i].idPrioridadObj = jQuery('#jform_intPrioridad_ob :selected').val();
                        objLstPoas.lstPoas[idRegPoa].lstObjetivos[i].nmbPrioridadObj = jQuery('#jform_intPrioridad_ob :selected').text();

                        //  Creo el Objeto Objetivo
                        var objObjetivo = new Objetivo();
                        objObjetivo.setDtaObjetivo( objLstPoas.lstPoas[idRegPoa].lstObjetivos[i] );

                        actualizarFila( objObjetivo, idRegPoa );
                    }
                }
            }
            //  limpio el formulario y reinicio la variables
            resetFrmObj();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }

    });

    /**
     *  Cancela la edicion de un registro de objetivo
     */
    jQuery(".btnCancel").click(function () {
        if (flagObjetivo != -1) {
            jQuery("#updObj-" + flagObjetivo).html("Editar");
            validarCambios(-1, false);
        } else {
            resetFrmObj();
        }
    });

    /**
     *  Clase que permite la edición de un objetivo de un Pei
     */
    jQuery('.updObjetivo').live('click', function () {
        var idFila = jQuery(this).attr('id');
        var idPoaObj = parseInt(idFila.toString().split('-')[1]);          //  Obtiene el id del Objetivo
        if (flagObjetivo == -1) {
            updDataObj(idPoaObj);
            flagObjetivo = idPoaObj;
        } else if (flagObjetivo != idPoaObj) {
            jQuery("#updObj-" + flagObjetivo).html("Editar");
            validarCambios(idPoaObj, true)
        }
    });

    /**
     *  Verifica si el objetivo se puede o no eliminar 
     */
    jQuery(".delObjetivo").live('click', function () {
        var idFila = jQuery(this).attr('id');
        var idRegObj = parseInt(idFila.toString().split('-')[1]);
        var planAccionObj = objLstPoas.lstPoas[jQuery("#idRegPoa").val()].lstObjetivos[idRegObj].lstAcciones;

        if (avalibleDel(planAccionObj)) {
            jConfirm(JSL_CONFIRM_DELETE, JSL_ECORAE, function (resutl) {
                if (resutl) {
                    objLstPoas.lstPoas[jQuery("#idRegPoa").val()].lstObjetivos[idRegObj].published = 0;
                    delFila(idRegObj, "#tbLstObjetivos");
                }
            });
        } else {
            jAlert(JSL_CONFIRM_NO_AVALIBLE_DEL, JSL_ECORAE);
        }
    });

    /**
     *  Verifica que los campos obligatorios han sido ingresados
     * 
     * @returns {Boolean}
     */
    function objValido()
    {
        var result = true;
        if (jQuery("#jform_intPrioridad_ob").val() == 0 ||
                jQuery("#jform_strDescripcion_ob").val() == '') {
            result = false;
        }
        return result;
    }

    /**
     *  Agrega una fila en la tablas de objetivos de un Plan
     * 
     * @param {type} objetivo
     * @param {type} idPoa
     * @returns {undefined}
     */
    function agregarFila(objetivo, idPoa)
    {
        //  Crea la fila a ser insertada en la tabla
        var fila = makeFila(objetivo, idPoa, 0);

        //  Agrego la fila creada a la tabla
        jQuery('#tbLstObjetivos> tbody:last').append(fila);
    }

    /**
     *  Actuliza la informacion de una fila en la tabla de objetivos de un POA
     * 
     * @param {type} objetivo       Array con los atributos del objetivo
     * @param {type} idPoa       Id de registro de un POA
     * @returns {undefined}
     */
    function actualizarFila(objetivo, idPoa)
    {
        jQuery('#tbLstObjetivos tr').each(function () {
            if (jQuery(this).attr('id') == flagObjetivo) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');

                //  Construyo la Fila
                var fila = makeFila(objetivo, idPoa, 1);
                jQuery(this).html(fila);
            }
        });
    }

    /**
     *  Crea la fila de la tabla de un nuevo registro o de actulizacion de uno esxistente
     *  @param {type} objetivo           Objtedo con la data del objetivo
     *  @param {type} idPoa              Id del plan a lque pertenece el objetivo
     *  @param {type} op                 Opcion para controlar si es un nuevo registro o una actualizacion
     *  
     *  @returns {String}
     */
    function makeFila(objetivo, idPoa, op)
    {
        var idUG = jQuery("#jform_intCodigo_ug").val();
        var idReg = objetivo.registroObj;
        var ds;

        var fila = '';

        fila += ( op === 0 )? '<tr id="' + idReg + '">'
                            : '';

        fila += '     <td align="left" style="vertical-align: middle;" >' + objetivo.descObjetivo + '</td>';
        fila += '     <td align="center" style="vertical-align: middle;" width="15px">' + objetivo.nmbPrioridadObj + '</td>';

        //  Alineacion estrategica ( PNBV / Agendas Sectoriales ) de un Objetivo Estrategico
        ds = getDtaSemaforizacionAlineacion( objetivo.semaforizacionAlineacion() );
        fila += '     <td align="center" width="15px" style="padding-left: 8px; vertical-align: middle;" >';
        fila += '       <a id="' + idReg + '" onclick="SqueezeBox.fromElement( \'index.php?option=com_alineacion&view=operativa&tpoEntidad=7&layout=edit&registroPoa=' + idPoa + '&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
        fila += '           <img id="AL' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '"  style="padding-left: 8px;">';
        fila += '       </a>';
        fila += '     </td>';

        //  Estrategia ( accion ) del Objetivo
        ds = getDtaSemaforizacionAccion(objetivo.semaforizacionAccion());
        fila += '     <td id="' + idReg + '" align="center" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_accion&view=plnaccion&layout=edit&idUG=' + idUG + '&registroPln=' + idPoa + '&tpoPln=2&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
        fila += '           <img id="ACC' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">';
        fila += '        </a>';
        fila += '     </td>';

        //  Indicadores del Objetivo ( Meta, Intermedios, Apoyo, etc )
        ds = objetivo.semaforizacionInd();
        fila += '     <td id="' + idReg + '" align="center" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicadores&layout=edit&tpoPlan=poaUG&tpo='+ objetivo.idTpoObj +'&tpoIndicador=ug&idPlan=' + idPoa + '&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_IND_ANCHO + ',y:' + POPUP_IND_ALTO + '}, handler:\'iframe\'} );">';
        fila += '           <img id="IO' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">';
        fila += '        </a>';
        fila += '     </td>';

        if ( roles["core.create"] === true || roles["core.edit"] === true ) {
            fila += '     <td align="center" width="15" style="vertical-align: middle;" > <a>' + JSL_NO_HABILITADO + '</a> </td > ';
            fila += '     <td align="center" width="15" style="vertical-align: middle;"> <a>' + JSL_NO_HABILITADO + '</a> </td>';
        } else {
            fila += '     <td align="center" width="15" style="vertical-align: middle;"> <a id="updObj-' + idReg + '" class="updObjetivo" >Editar</a> </td > ';
            fila += '     <td align="center" width="15" style="vertical-align: middle;"> <a id="delObj-' + idReg + '" class="delObjetivo" >Eliminar</a> </td>';
        }

        fila += (op === 0) ? ' </tr>'
                : '';

        return fila;
    }


    function getDtaSemaforizacionAlineacion(banIndicador)
    {
        var dtaSemaforizacion = new Array();

        switch (banIndicador) {
            //  Rojo
            case 0:
                dtaSemaforizacion["imgAtributo"] = COM_UG_RG_SIN_ALINEACION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_RG_SIN_ALINEACION;
                break;

                //  Verde
            case 3:
                dtaSemaforizacion["imgAtributo"] = COM_UG_VD_ALINEACION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_VD_ALINEACION;
                break;
        }

        return dtaSemaforizacion;
    }

    function getDtaSemaforizacionAccion(banIndicador)
    {
        var dtaSemaforizacion = new Array();

        switch (banIndicador) {
            //  Rojo
            case 0:
                dtaSemaforizacion["imgAtributo"] = COM_UG_RG_SIN_ACCION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_RG_SIN_ACCION;
                break;

                //  Verde
            case 3:
                dtaSemaforizacion["imgAtributo"] = COM_UG_RG_ACCION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_RG_ACCION;
                break;
        }

        return dtaSemaforizacion;
    }

    /**
     *  Controla si se ha modificado la data de un objetivo para guardarlo o no
     * 
     * @param {type} idPoaObj   Id del Poa Objetivo para el caso de una nueva edicion si no es -1 
     * @param {type} frm        Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function validarCambios(idPoaObj, frm)
    {
        if (confirmUpdObj(jQuery("#idRegPoa").val(), flagObjetivo)) {
            autoSave(idPoaObj, frm);
        } else {
            controlAutoSave(idPoaObj, frm);
        }
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     * solo llama a la funcion "controlAutoSave" que reliza los controles de edicion.
     * 
     * @param {type} idPoaObj       Id del Poa-Objetivo en el caso de una nueva edicion, si no es NULL
     * @param {type} frm            Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function autoSave(idPoaObj, frm)
    {
        jConfirm(JSL_CONFIRM_UPD_OBJETIVO, JSL_ECORAE, function (result) {
            if (result) {
                jQuery('#btnAddObj').trigger('click');
                controlAutoSave(idPoaObj, frm);
            } else {
                controlAutoSave(idPoaObj, frm);
            }
        });
    }

    /**
     *  Realiza las tareas especificas cuando guarda cambios en un registro
     * 
     * @param {type} idPoaObj       Id de registro de la Acción (-1 en el caso de ser un nuevo registro)
     * @param {type} op             opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function controlAutoSave(idPoaObj, op)
    {
        if (flagObjetivo != -1) {
            jQuery("#updObj-" + flagObjetivo).html("Editar");
        }
        if (idPoaObj != -1) {
            updDataObj(idPoaObj);
            flagObjetivo = idPoaObj;
        } else {
            resetFrmObj();
            if (op) {
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
        jQuery("#jform_intPrioridad_ob option[value=0]").attr("selected", true);
        jQuery("#jform_strDescripcion_ob").attr('value', '');
        flagObjetivo = -1;
    }

    /**
     *  Caraga la data de un objetivo para ser modificada
     * 
     * @param {type} idObjPoa
     * @returns {undefined}
     */
    function updDataObj(idObjPoa)
    {
        var idPoa = jQuery("#idRegPoa").val();
        var lstObjsPoa = objLstPoas.lstPoas[idPoa].lstObjetivos;
        var numReg = lstObjsPoa.length;
        for (var i = 0; i < numReg; i++) {
            if (lstObjsPoa[i].registroObj == idObjPoa) {
                jQuery("#updObj-" + idObjPoa).html("Editando...");
                jQuery("#frmObjetivo").css("display", "block");
                jQuery("#imgObjetivo").css("display", "none");

                var pr = lstObjsPoa[i].idPrioridadObj;
                recorrerCombo(jQuery("#jform_intPrioridad_ob option"), pr);
                jQuery("#jform_strDescripcion_ob").val(lstObjsPoa[i].descObjetivo);
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
        var numReg = data.length;
        if (numReg > 0) {
            for (var i = 0; i < numReg; i++) {
                if (data[i].published == 1)
                    result = false;
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
        jQuery(combo).each(function () {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        });
    }

    /**
     *  Elimina una fila de la tabla de bjetivos de un pei
     *  
     * @param {type} idFila
     * @param {type} tabla
     * @returns {undefined}
     */
    function delFila(idFila, tabla)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery(tabla + ' tr').each(function () {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }

    /**
     *  Retorna True en el caso de que los datos de un objetivo se modificaron
     * caso contrario devuelve False
     * 
     * @param {type} idPoa      Id de registro del Poa
     * @param {type} idObj      Id de registro del objetivo
     * @returns {Boolean}
     */
    function confirmUpdObj(idPoa, idObj)
    {
        var resultado = false;
        var lstObjsPoa = objLstPoas.lstPoas[idPoa].lstObjetivos;
        for (var i = 0; i < lstObjsPoa.length; i++) {
            if (lstObjsPoa[i].registroObj == idObj &&
                    (jQuery("#jform_intPrioridad_ob :selected").val() != lstObjsPoa[i].idPrioridadObj ||
                            jQuery("#jform_strDescripcion_ob").val() != lstObjsPoa[i].descObjetivo)) {
                resultado = true;
            }
        }
        return resultado;
    }

    /**
     *  Clase que carga los objetivop de un POA del Funcionario
     */
    jQuery(".loadObjetivosPoa").live("click", function () {
        var idPoa = jQuery(this).attr('id');
        var idRegPoa = parseInt(idPoa.toString().split('-')[1]);
        jQuery("#poaObjs").attr('disabled', false);
        jQuery("#idRegPoa").val(idRegPoa);
        cargarObjetivos(idRegPoa);
        resetFrmObj();
    });

    /**
     *  Carga los objetivos de un POA en la tabla de Objetivos
     * 
     * @param {int} idRegPoa
     * @returns {undefined}
     */
    function cargarObjetivos(idRegPoa)
    {
        jQuery('#tbLstObjetivos > tbody').empty();
        var lstObjetivosPoa = objLstPoas.lstPoas[idRegPoa].lstObjetivos;
        if (lstObjetivosPoa.length > 0 && !(avalibleDel(lstObjetivosPoa))) {
            jQuery("#srObj").css("display", "none");
            for (var i = 0; i < lstObjetivosPoa.length; i++) {
                if (parseInt( lstObjetivosPoa[i].published ) === 1) {
 
                    jQuery( '#idLstPOAs' ).html( getAnioVigencia( lstObjetivosPoa[i].fchInicioPlan ) );
                    
                    //  Creo el Objeto Objetivo
                    var objObjetivo = new Objetivo();
                    objObjetivo.setDtaObjetivo( lstObjetivosPoa[i] );

                    agregarFila(objObjetivo, idRegPoa);
                }
            }
        } else {
            jQuery("#srObj").css("display", "block");
        }
    }

});


function getAnioVigencia( fchInicioPlan )
{
    var msgAnio = false;

    if( typeOf( fchInicioPlan ) !== "null" ){
        var fp = fchInicioPlan.split( '-' );
        var objFecha = new Date( parseInt( fp[0] ), parseInt( fp[1] ) - 1, parseInt( fp[2] ) );

        msgAnio = COM_UNIDAD_GESTION_FIELD_OBJETIVOS_UG_LABEL + objFecha.getFullYear() + '&nbsp;';
    }

    return msgAnio;
}


function getDtaSemaforizacionAlineacion(banIndicador)
    {
        var dtaSemaforizacion = new Array();

        switch (banIndicador) {
            //  Rojo
            case 0:
                dtaSemaforizacion["imgAtributo"] = COM_UG_RG_SIN_ALINEACION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_RG_SIN_ALINEACION;
                break;

                //  Verde
            case 3:
                dtaSemaforizacion["imgAtributo"] = COM_UG_VD_ALINEACION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_VD_ALINEACION;
                break;
        }

        return dtaSemaforizacion;
    }

    function getDtaSemaforizacionAccion(banIndicador)
    {
        var dtaSemaforizacion = new Array();

        switch (banIndicador) {
            //  Rojo
            case 0:
                dtaSemaforizacion["imgAtributo"] = COM_UG_RG_SIN_ACCION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_RG_SIN_ACCION;
                break;

                //  Verde
            case 3:
                dtaSemaforizacion["imgAtributo"] = COM_UG_RG_ACCION;
                dtaSemaforizacion["msgAtributo"] = COM_UG_TITLE_RG_ACCION;
                break;
        }

        return dtaSemaforizacion;
    }



/**
 * cambia los colores de los semaforos segun el tipo
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function validateSemaforoObjetivo(reg, tpoPln, idRegPln)
{
    if ( typeof( tpoPln ) !== "undefined" && tpoPln !== 2) {
        semaforoIdicadorMeta(reg, tpoPln, idRegPln);
    } else if (typeof (tpoPln) !== "undefined" && tpoPln === 2) {
        semaforoActividades(reg, tpoPln, idRegPln);
    }
    
    semaforoAlineacion(reg, tpoPln, idRegPln);
    semaforoPlanAnccion(reg, tpoPln, idRegPln);
    semaforoIndicadoresObj(reg, tpoPln, idRegPln);
}

/**
 * Cambia el semaforo del indicador meta.
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function semaforoIdicadorMeta(reg, tpoPln, idRegPln) {
    var val = 2;
    var objData = getObjetivos(reg, tpoPln, idRegPln, 'IndMeta');
    var lstObjetivos = objData.lstObjetivos;    
    var id = objData.id;

    if (lstObjetivos[reg] && lstObjetivos[reg].lstIndicadores) {
        var lstIndicadoreTmp = lstObjetivos[reg].lstIndicadores;
        var indicadorMeta = getIndMeta(lstIndicadoreTmp);
        if (indicadorMeta) {
            val = indicadorMeta.semaforoValor();
        }
    }
    //  changeColorSemaforo(id, val);
}

/**
 *  Retorna el indicador meta del objetivo
 * @param {type} lstIndObjetivos    lista de indicadores meta
 * @returns {Indicador|Boolean}
 */
function getIndMeta(lstIndObjetivos)
{
    var dtaIndMeta = false;
    for (var x = 0; x < lstIndObjetivos.length; x++) {
        if (parseInt(lstIndObjetivos[x].idTpoIndicador) === 1) {
            var objIndMeta = new Indicador();
            objIndMeta.setDtaIndicador(lstIndObjetivos[x]);
            dtaIndMeta = objIndMeta;
            break;
        }
    }

    return dtaIndMeta;
}

/**
 *  Cambia el semaforo de la alineacion del objetivo.
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function semaforoAlineacion( reg, tpoPln, idRegPln )
{
    var objData     = getObjetivos(reg, tpoPln, idRegPln, 'Aln');
    var lstObjetivos= objData.lstObjetivos;

    var lstAlin = lstObjetivos[reg].lstAlineaciones;
    var numReg = 0;

    for( var i = 0; i < lstAlin.length; i++ ){
        if( lstAlin[i].published === 1 ){
            numReg = ++numReg;
        }
    }

    var val = ( numReg > 0 )? 3 
                            : 2;

    changeColorAlineacion( '#AL' + reg, val );
}



/**
 *  Cambia el color de los semaforos de las caracteristicas del objetivo
 * @param {type} id         id del registro del objetivo
 * @param {type} color      color del semaforo
 * @returns {undefined}
 */
function changeColorAlineacion( id, idColor )
{
    var ds = getDtaSemaforizacionAlineacion( idColor );
    
    jQuery( id ).attr( 'src', ds["imgAtributo"] );
    jQuery( id ).attr( 'title', ds["msgAtributo"] );
}

/**
 *  Cambia el semaforo del plan de accion del objetivo.
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function semaforoPlanAnccion(reg, tpoPln, idRegPln)
{
    var objData     = getObjetivos(reg, tpoPln, idRegPln, 'Acc');
    var lstObjetivos= objData.lstObjetivos;

    var plnAccion = lstObjetivos[reg].lstAcciones;
    var numReg = 0;

    for (var i = 0; i < plnAccion.length; i++) {
        if( parseInt( plnAccion[i].published ) === 1 ){
            numReg = ++numReg;
        }
    }

    var val = (numReg > 0)  ? 3 
                            : 2;

    changeColorAccion( '#ACC' + reg , val );
}



/**
 *  Cambia el color de los semaforos de las caracteristicas del objetivo
 * @param {type} id         id del registro del objetivo
 * @param {type} color      color del semaforo
 * @returns {undefined}
 */
function changeColorAccion( id, idColor )
{
    var ds = getDtaSemaforizacionAccion( idColor );
    
    jQuery( id ).attr( 'src', ds["imgAtributo"] );
    jQuery( id ).attr( 'title', ds["msgAtributo"] );
}


/**
 *  Cambia el semaforo de los indicadores de un objetivo.
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function semaforoIndicadoresObj(reg, tpoPln, idRegPln)
{
    var objData = getObjetivos(reg, tpoPln, idRegPln, 'Ind');
    var lstObjetivos = objData.lstObjetivos;
    var id = objData.id;

    var lstInd = lstObjetivos[reg].lstIndicadores;
    var numInd = 0;

    for (var i = 0; i < lstInd.length; i++) {
        if ( parseInt( lstInd[i].published ) === 1 ){
            numInd = ++numInd;
        }
    }

    var val = (numInd > 0)  ? 3
                            : 2;

    changeColorIndicador( '#IO' + reg, val );
}




/**
 *  Cambia el color de los semaforos de las caracteristicas del objetivo
 * @param {type} id         id del registro del objetivo
 * @param {type} color      color del semaforo
 * @returns {undefined}
 */
function changeColorIndicador( id, idColor )
{
    var ds = getDtaSemaforizacionAlineacion( idColor );
    
    jQuery( id ).attr( 'src', ds["imgAtributo"] );
    jQuery( id ).attr( 'title', ds["msgAtributo"] );
}




/**
 *  Cambia el semaforo de las actividades de un objetivo.
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function semaforoActividades(reg, tpoPln, idRegPln)
{
    var objData = getObjetivos(reg, tpoPln, idRegPln, 'Act');
    var lstObjetivos = objData.lstObjetivos;
    var id = objData.id;

    var lstAct = lstObjetivos[reg].lstActividades;
    var numAct = 0;

    for (var i = 0; i < lstAct.length; i++) {
        if (lstAct[i].published == 1 && lstAct[i].idTpoIndicador != 1) {
            numAct = ++numAct;
        }
    }

    var val = (numAct > 0)  ? 3
                            : 2;

    //  changeColorSemaforo(id, val);
}




function getObjetivos(regObj, tpoPln, idRegPln, topLista)
{
    var objData = {lstObjetivos: '', id: ''};

    if (typeof (tpoPln) !== "undefined") {
        switch (tpoPln) {
            case 2:
                objData.lstObjetivos = (objLstPoas.lstPoas[idRegPln].lstObjetivos.length > 0)
                        ? objLstPoas.lstPoas[idRegPln].lstObjetivos
                        : new Array();

                objData.id = '#smf' + topLista + '-' + regObj;
                break;

            case 3:
                objData.lstObjetivos = (oLstPPPPs.lstPppp[idRegPln].lstObjetivos.length > 0)
                        ? oLstPPPPs.lstPppp[idRegPln].lstObjetivos
                        : new Array();

                objData.id = '#sPppp' + topLista + '-' + regObj;
                break;

            case 4:
                objData.lstObjetivos = (oLstPAPPs.lstPapp[idRegPln].lstObjetivos.length > 0)
                        ? oLstPAPPs.lstPapp[idRegPln].lstObjetivos
                        : new Array();

                objData.id = '#sPppp' + topLista + '-' + regObj;
                break;
        }
    } else {
        objData.lstObjetivos = (objLstObjetivo.lstObjetivos.length > 0)
                ? objLstObjetivo.lstObjetivos
                : new Array();

        objData.id = '#smf' + topLista + '-' + regObj;
    }

    return objData;
}