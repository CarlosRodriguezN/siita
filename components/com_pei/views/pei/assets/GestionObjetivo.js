var flagUpdObj  = false;
var flagObjetivo= -1;
var roles       = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;

    if (objLstObjetivo.lstObjetivos.length) {
        for (var i = 0; i < objLstObjetivo.lstObjetivos.length; i++) {
            if( parseInt( objLstObjetivo.lstObjetivos[i].published ) === 1 ) {
                agregarFila( objLstObjetivo.lstObjetivos[i], "#tbLstObjetivos" );
            }
        }
    }

    /**
     *  Habilita el formulario de data general de un objetivo
     */
    jQuery('#addObjetivoTable').click(function() {
        //  Si se esta editando un objetivo, flagGrafico tiene el id del grafico editandose.
        if (flagObjetivo !== -1) {
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
        if (validarFrmObj()) {
            if (flagObjetivo == -1) {
                //  Creo el Objeto Objetivo
                var objObjetivo = new Objetivo();

                objObjetivo.registroObj     = objLstObjetivo.lstObjetivos.length;
                objObjetivo.descObjetivo    = jQuery("#jform_strDescripcion_ob").val();
                objObjetivo.descTpoObj      = '';
                objObjetivo.idObjetivo      = jQuery("#jform_intId_ob").val();
                objObjetivo.idPadreObj      = 0;
                objObjetivo.idPrioridadObj  = jQuery("#jform_intPrioridad_ob :selected").val();
                objObjetivo.idTpoObj        = jQuery("#jform_intId_tpoObj").val();
                objObjetivo.nmbPrioridadObj = jQuery("#jform_intPrioridad_ob :selected").text();
                objObjetivo.published       = 1;

                //  Agrego un objetivo a la lista de Objetivos
                objLstObjetivo.addObjetivo(objObjetivo);

                agregarFila(objObjetivo, "#tbLstObjetivos");
                //  validateSemaforoObjetivo(objObjetivo.registroObj);
            } else {
                var numReg = objLstObjetivo.lstObjetivos.length;
                for (var i = 0; i < numReg; i++) {
                    if (objLstObjetivo.lstObjetivos[i].registroObj == flagObjetivo) {
                        objLstObjetivo.lstObjetivos[i].descObjetivo = jQuery("#jform_strDescripcion_ob").val();
                        objLstObjetivo.lstObjetivos[i].idTpoObj = jQuery("#jform_intId_tpoObj :selected").val();
                        objLstObjetivo.lstObjetivos[i].descTpoObj = jQuery("#jform_intId_tpoObj :selected").text();
                        objLstObjetivo.lstObjetivos[i].idPrioridadObj = jQuery("#jform_intPrioridad_ob :selected").val();
                        objLstObjetivo.lstObjetivos[i].nmbPrioridadObj = jQuery("#jform_intPrioridad_ob :selected").text();

                        actualizarFila(objLstObjetivo.lstObjetivos[i], '#tbLstObjetivos');
                        flagObjetivo = -1;
                        //  validateSemaforoObjetivo(objLstObjetivo.lstObjetivos[i].registroObj);
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



    function validarFrmObj()
    {
        var ban = false;

        var idPrioridad = jQuery('#jform_intPrioridad_ob');
        var descripcionObj = jQuery('#jform_strDescripcion_ob');

        if (descripcionObj.val() !== "" && jQuery.isNumeric(idPrioridad.val()) && parseInt(idPrioridad.val()) > 0) {
            ban = true;
        } else {
            validarElemento(idPrioridad);
            validarElemento(descripcionObj);
        }

        return ban;
    }


    function validarElemento(obj)
    {
        var ban = 1;

        if (obj.val() === "" || obj.val() === "0") {
            ban = 0;
            obj.attr('class', 'required invalid');

            var lbl = obj.selector + '-lbl';
            jQuery(lbl).attr('class', 'hasTip required invalid');
            jQuery(lbl).attr('aria-invalid', 'true');
        }

        return ban;
    }
    
    
    function delValidaciones( obj )
    {
        obj.attr('class', '');
        var lbl = obj.selector + '-lbl';

        jQuery(lbl).attr('class', '');
        jQuery(lbl).attr('aria-invalid', '');
    }
    


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
        var planAccionObj = objLstObjetivo.lstObjetivos[idFila].lstAcciones;
        var indicadoresObj = objLstObjetivo.lstObjetivos[idFila].lstIndicadores;

        if (avalibleDel(planAccionObj) && avalibleDel(indicadoresObj)) {
            jConfirm(JSL_CONFIRM_DEL_OBJETIVO, JSL_ECORAE, function(resutl) {
                if (resutl) {
                    eliminarObjetivo( objLstObjetivo.lstObjetivos[idFila] );

                    //  Bandera para actualizacion para PPPP y PAPP
                    flagUpdObj = true;
                    delFila(idFila);
                }
            });
        } else {
            jAlert("El objetivo no se puede eliminar porque tiene relaciones existentes", JSL_ECORAE);
        }
    });


    function eliminarObjetivo( dtaObjetivo, idFila )
    {
        if( isNaN( parseInt( dtaObjetivo.idObjetivo ) ) === false ){
            dtaObjetivo.published = 0
        }else{
            //  Elimino Rango del la tabla Temporal de Rangos
            objLstObjetivo.lstObjetivos.splice( idFila, 1 );
        }
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
        recorrerCombo(jQuery('#jform_intId_tpoObj option'), 0);
        recorrerCombo(jQuery('#jform_intPrioridad_ob option'), 0);
        jQuery("#jform_strDescripcion_ob").attr('value', '');
        
        delValidaciones( jQuery( '#jform_intPrioridad_ob' ) );
        delValidaciones( jQuery( '#jform_strDescripcion_ob' ) );
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
                var to = objLstObjetivo.lstObjetivos[i].idTpoObj;
                var pr = objLstObjetivo.lstObjetivos[i].idPrioridadObj;
                recorrerCombo(jQuery('#jform_intId_tpoObj option'), to);
                recorrerCombo(jQuery('#jform_intPrioridad_ob option'), pr);
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
                if (objLstObjetivo.lstObjetivos[i].idTpoObj != jQuery("#jform_intId_tpoObj").val() ||
                        objLstObjetivo.lstObjetivos[i].idPrioridadObj != jQuery("#jform_intPrioridad_ob :selected").val() ||
                        objLstObjetivo.lstObjetivos[i].descObjetivo != jQuery("#jform_strDescripcion_ob").val())
                    resultado = true;
            }
        }

        return resultado;
    }

});

/**
 *  Agrega una fila en la tablas de objetivos de un Pei
 *  
 * @param {type} objetivo       Array con los atributos del objetivo.
 * @param {type} tabla          Id de la tabla ha agregar una fila.
 * @returns {undefined}
 */
function agregarFila(objetivo, tabla)
{
    //  Agrego la fila creada a la tabla
    var fila = makeFila(objetivo, 0);
    jQuery(tabla + ' > tbody:last').append(fila);
}

/**
 *  Actulida la informacion de una fila en la tabla de objetivos de un Pei
 * @param {type} objetivo           Array con los atributos del objetivo
 * @param {type} tabla              Id de la tabla
 * @returns {undefined}
 */
function actualizarFila(objetivo, tabla)
{
    jQuery(tabla + ' tr').each(function() {
        if (jQuery(this).attr('id') === flagObjetivo) {
            //  Construyo la Fila
            var fila = makeFila(objetivo, 1);
            jQuery(this).html(fila);
        }
    });

}


/**
 *  
 *  Crea la fila de la tabla de un nuevo registro o de actulizacion de uno esxistente
 *  
 *  @param {type} obj            Objtedo con la data del objetivo
 *  @param {type} op             Opcion para controlar si es un nuevo registro o una actualizacion
 *  @returns {String}
 *  
 */
function makeFila(obj, op)
{
    var idReg = obj.registroObj;
    var fila = '';
    var ds;

    fila += ( op === 0 )? '<tr id="' + idReg + '">'
                        : '';

    fila += '     <td align="left" style="vertical-align: middle;" >' + obj.descObjetivo + '</td>';
    fila += '     <td align="center" style="vertical-align: middle;" width="15px">' + obj.nmbPrioridadObj + '</td>';

    //  Alineacion estrategica ( PNBV / Agendas Sectoriales ) de un Objetivo Estrategico
    ds = getDtaSemaforizacionAlineacion(obj.semaforizacionAlineacion());
    fila += '     <td align="center" width="15px" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
    fila += '       <a id="' + idReg + '" onclick="SqueezeBox.fromElement( \'index.php?option=com_alineacion&view=estrategica&layout=edit&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
    fila += '           <img id="AL' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '"  style="padding-left: 15px;">';
    fila += '       </a>';
    fila += '     </td>';

    //  Estrategia ( accion ) del Objetivo
    ds = getDtaSemaforizacionAccion(obj.semaforizacionAccion());
    fila += '     <td id="' + idReg + '" align="center" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
    fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_accion&view=plnaccion&layout=edit&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
    fila += '           <img id="ACC' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">';
    fila += '        </a>';
    fila += '     </td>';

    //  Indicadores del Objetivo ( Meta, Intermedios, Apoyo, etc )
    ds = obj.semaforizacionInd();
    fila += '     <td id="' + idReg + '" align="center" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
    fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicadores&layout=edit&tpoIndicador=pei&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_IND_ANCHO + ',y:' + POPUP_IND_ALTO + '}, handler:\'iframe\'} );">';
    fila += '           <img id="IO' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">';
    fila += '        </a>';
    fila += '     </td>';

    if( roles["core.create"] === true || roles["core.edit"] === true ){
        fila += '     <td align="center" width="15px" style="vertical-align: middle;"> <a id="updObj-' + idReg + '" class="updObjetivo" >' + COM_PEI_UPDATE + '</a> </td > ';
        fila += '     <td align="center" width="15px" style="vertical-align: middle;"> <a id="delObj-' + idReg + '" class="delObjetivo" >' + COM_PEI_DELETE + '</a> </td>';
    }else{
        fila += '     <td align="center" width="15px" style="vertical-align: middle;">' + COM_PEI_UPDATE + '</td > ';
        fila += '     <td align="center" width="15px" style="vertical-align: middle;">' + COM_PEI_DELETE + '</td>';
    }

    fila += ( op === 0 )? ' </tr>' 
                        : '';

    return fila;
}



function getDtaSemaforizacionAlineacion(banIndicador)
{
    var dtaSemaforizacion = new Array();

    switch (banIndicador) {
        //  Rojo
        case 0:
            dtaSemaforizacion["imgAtributo"] = COM_PEI_RG_SIN_ALINEACION;
            dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_RG_SIN_ALINEACION;
            break;

            //  Verde
        case 3:
            dtaSemaforizacion["imgAtributo"] = COM_PEI_VD_ALINEACION;
            dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_VD_ALINEACION;
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
            dtaSemaforizacion["imgAtributo"] = COM_PEI_RG_SIN_ACCION;
            dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_RG_SIN_ACCION;
            break;

            //  Verde
        case 3:
            dtaSemaforizacion["imgAtributo"] = COM_PEI_RG_ACCION;
            dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_RG_ACCION;
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
//function validateSemaforoObjetivo( reg, tpoPln, idRegPln ){
//    semaforoAlineacion( reg, tpoPln, idRegPln );
//    semaforoPlanAnccion( reg, tpoPln, idRegPln );
//};

/**
 *  Retorna el indicador meta del objetivo
 * @param {type} lstIndObjetivos    lista de indicadores meta
 * @returns {Indicador|Boolean}
 */
function getIndMeta(lstIndObjetivos)
{
    var dtaIndMeta = false;
    for (var x = 0; x < lstIndObjetivos.length; x++) {
        if (parseInt(lstIndObjetivos[x].idTpoIndicador) == 1) {
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
function semaforoAlineacion(reg, tpoPln, idRegPln)
{
    var objData = getObjetivos(reg, tpoPln, idRegPln, 'Aln');
    var lstObjetivos = objData.lstObjetivos;
    var id = objData.id;

    var lstAlin = lstObjetivos[reg].lstAlineaciones;
    var numReg = 0;

    for (var i = 0; i < lstAlin.length; i++) {
        if (lstAlin[i].published == 1) {
            numReg = ++numReg;
        }
    }

    var val = (numReg > 0) ? 3
            : 2;

    changeColorSemaforoAlineacion('#AL' + reg, val);
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
    var objData = getObjetivos(reg, tpoPln, idRegPln, 'Acc');
    var lstObjetivos = objData.lstObjetivos;
    var id = objData.id;

    var plnAccion = lstObjetivos[reg].lstAcciones;
    var numReg = 0;

    for (var i = 0; i < plnAccion.length; i++) {
        if (plnAccion[i].published == 1) {
            numReg = ++numReg;
        }
    }

    var val = (numReg > 0) ? 3
            : 0;

    changeColorSemaforoAccion('#ACC' + reg, val);
}



/**
 *  Cambia el color de los semaforos de las caracteristicas del objetivo
 * @param {type} id         id del registro del objetivo
 * @param {type} color      color del semaforo
 * @returns {undefined}
 */
function changeColorSemaforoAlineacion(id, color)
{
    var dtaSemaforo = getDtaSemaforizacionAlineacion(color)

    jQuery(id).attr('src', dtaSemaforo["imgAtributo"]);
    jQuery(id).attr('title', dtaSemaforo["msgAtributo"]);
}

/**
 *  Cambia el color de los semaforos de las caracteristicas del objetivo
 * @param {type} id         id del registro del objetivo
 * @param {type} color      color del semaforo
 * @returns {undefined}
 */
function changeColorSemaforoAccion(id, color)
{
    var dtaSemaforo = getDtaSemaforizacionAccion(color)

    jQuery(id).attr('src', dtaSemaforo["imgAtributo"]);
    jQuery(id).attr('title', dtaSemaforo["msgAtributo"]);
}


function getObjetivos(regObj, tpoPln, idRegPln, topLista)
{
    var objData = {lstObjetivos: '', id: ''};

    if (typeof (tpoPln) != "undefined") {
        switch (tpoPln) {
            case 3:
                objData.lstObjetivos = (oLstPPPPs.lstPppp[idRegPln].lstObjetivos.length > 0) ? oLstPPPPs.lstPppp[idRegPln].lstObjetivos
                        : new Array();
                objData.id = getIdImgSmf(regObj, topLista, 3);
                break;
            case 4:
                objData.lstObjetivos = (oLstPAPPs.lstPapp[idRegPln].lstObjetivos.length > 0) ? oLstPAPPs.lstPapp[idRegPln].lstObjetivos
                        : new Array();
                objData.id = getIdImgSmf(regObj, topLista, 4);
                break;
        }
    } else {
        objData.lstObjetivos = (objLstObjetivo.lstObjetivos.length > 0) ? objLstObjetivo.lstObjetivos
                : new Array();
        objData.id = getIdImgSmf(regObj, topLista, 1);
    }
    return objData;
}


function getIdImgSmf(regObj, tpoLista, tpoPln)
{
    var id = '';
    switch (tpoPln)
    {
        case 1:
            id = '#smf' + tpoLista + '-' + regObj;
            break;
        case 3:
            id = '#sPppp' + tpoLista + '-' + regObj;
            break;
        case 4:
            id = '#sPapp' + tpoLista + '-' + regObj;
            break;
    }

    return id;
}
