jQuery(document).ready(function() {
    
    var flagCargo = -1;
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
    
    cargarCargos();
    
    /**
     *  Habilita el formulario de data general de un objetivo
     */
    jQuery('.addCargo').click(function() {
        //  Si se esta editando un grafico, flagGrafico tiene el id del grafico editandose.
        if ( flagCargo != -1) {
            var id = -1;
            validarCambios( id, true );
        } else {
            jQuery("#imgCargo").css("display", "none");
            jQuery("#frmCargo").css("display", "block");
        }
    });

    /**
     *  Guarda la data general de un objetivo de un Pei
     */
    jQuery(".btnAdd").click(function() {
        if ( objValido() ) {
            if (lstCargos.length == 0) {
                jQuery("#srCargos").css("display", "none");
            }
            if ( flagCargo == -1  ) {
                //  Creo el Objeto Objetivo
                var cargo = new Object();
                
                cargo.idReg         = lstCargos.length;
                cargo.idCargo       = 0;
                cargo.nombreCargo   = jQuery('#jform_strNombre_cargo').val();
                cargo.published     = 1;

                lstCargos.push(cargo);
                agregarFila( cargo );
                
            } else {
                var numReg = lstCargos.length;
                for (var i = 0; i < numReg; i++) {
                    if (lstCargos[i].idReg == flagCargo) {
                        lstCargos[i].nombreCargo = jQuery('#jform_strNombre_cargo').val();
                        actualizarFila( lstCargos[i] );
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
    jQuery(".btnCancel").click(function() {
        if ( flagCargo != -1 ) {
            jQuery("#upd-" + flagCargo).html("Editar");
            validarCambios( -1, false );
        } else {
            resetFrmObj();
        }
    });

    /**
     *  Clase que permite la edición de un objetivo de un Pei
     */
    jQuery('.updCargo').live('click', function() {
        var idFila = jQuery(this).attr('id');
        var regCargo = parseInt(idFila.toString().split('-')[1]);          //  Obtiene el id del Objetivo
        if (flagCargo == -1 ) {
            updDataObj(regCargo);
            flagCargo = regCargo;
        } else if (flagCargo != regCargo) {
            jQuery("#upd-" + flagCargo).html("Editar");
            validarCambios(regCargo, true)
        }
    });

    /**
     *  Verifica si el objetivo se puede o no eliminar 
     */
    jQuery(".delCargo").live('click', function() {
        var idFila = jQuery(this).attr('id');
        var idReg = parseInt(idFila.toString().split('-')[1]);
        var idCargo = lstCargos[idReg].idCargo;
        validateDeleteAjax( idReg,idCargo );
        
    });
    
    /**
     *  Clase que carga los objetivop de un POA del Funcionario
     */
    jQuery(".loadObjetivosPoa").live("click", function () {
        var idPoa = jQuery(this).attr('id');
        var idRegPoa = parseInt(idPoa.toString().split('-')[1]);
        jQuery("#poaObjs").attr('disabled', false);
        jQuery("#idRegPoa").val( idRegPoa );
        cargarCargos( idRegPoa );
        resetFrmObj();
    });
    
    /**
     * 
     * @param {type} reg
     * @param {type} cargo
     * @returns {undefined}
     */
    function validateDeleteAjax( reg, cargo )
    {
        var url = window.location.href;
        var path = url.split('?')[0];
        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method       : "POST",
                option          : 'com_mantenimiento',
                view            : 'cargofnc',
                tmpl            : 'component',
                format          : 'json',
                action          : 'validarDelCargo',
                idCrg           : cargo
            },
            error: function(jqXHR, status, error) {
                jAlert('Mantenimiento - Gesti&oacute;n Cargo: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            if ( saveData ){
                jConfirm(JSL_CONFIRM_DELETE, JSL_ECORAE, function(resutl) {
                    if (resutl) {
                        lstCargos[reg].published = 0;
                        delFila(reg, "#tbLstCargos");
                    }   
                });
            } else {
                jAlert( JSL_CONFIRM_NO_AVALIBLE_DEL, JSL_ECORAE );
            }
            
        });
    }
    
    /**
     *  Verifica que los campos obligatorios han sido ingresados
     * @returns {Boolean}
     */
    function objValido()
    {
        var result = true;
        if ( jQuery("#jform_strNombre_cargo") .val() == '') {
            result = false;
        }
        return result;
    }

    /**
     *  Agrega una fila en la tablas de cargos
     * @param {type} obj
     * @returns {undefined}
     */
    function agregarFila( obj )
    {
        //  Crea la fila a ser insertada en la tabla
        var fila = makeFila(obj, 0);
        //  Agrego la fila creada a la tabla
        jQuery('#tbLstCargos> tbody:last').append(fila);
    }

    /**
     *  Actuliza la informacion de una fila en la tabla de cargos
     * @param {type} obj       Array con los atributos del objetivo
     * @returns {undefined}
     */
    function actualizarFila( obj )
    {
        jQuery('#tbLstCargos tr').each(function() {
            if (jQuery(this).attr('id') == flagCargo) {
                //  Construyo la Fila
                var fila = makeFila(obj, 1);
                jQuery(this).html(fila);
            }
        });

    }
    
    /**
     *  Crea la fila de la tabla de un nuevo registro o de actulizacion de uno esxistente
     * @param {type} obj           Objtedo con la data del objetivo
     * @param {type} op                 Opcion para controlar si es un nuevo registro o una actualizacion
     * @returns {String}
     */
    function makeFila( obj, op )
    {
        var idReg = obj.idReg;
        
        var fila = '';
        fila += ( op == 0 ) ? '<tr id="' + idReg + '">' : '';
        fila += '     <td >' + obj.nombreCargo + '</td>';
        fila += '     <td align="center" width="15" > <a id="upd-' + idReg + '" class="updCargo" >Editar</a> </td > ';
        fila += '     <td align="center" width="15" > <a id="del-' + idReg + '" class="delCargo" >Eliminar</a> </td>';
        fila += ( op == 0 ) ? ' </tr>' : '';

        return fila;
    }

    /**
     *  Controla si se ha modificado la data de un objetivo para guardarlo o no
     * 
     * @param {type} idRegistro   Id de registro del objeto para el caso de una nueva edicion si no es -1  
     * @param {type} frm        Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function validarCambios( idRegistro, frm )
    {   
        if ( confirmUpdObj( flagCargo ) ) {
            autoSave( idRegistro, frm );
        } else {
            controlAutoSave( idRegistro, frm );
        }
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     * solo llama a la funcion "controlAutoSave" que reliza los controles de edicion.
     * 
     * @param {type} registro       Id del registro del onjeto en el caso de una nueva edicion, si no es -1
     * @param {type} frm            Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function autoSave( registro, frm )
    {
        jConfirm(JSL_CONFIRM_UPD_OBJETO, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnAdd').trigger('click');
                controlAutoSave( registro, frm);
            } else {
                controlAutoSave( registro, frm);
            }
        });
    }

    /**
     *  Realiza las tareas especificas cuando guarda cambios en un registro
     * 
     * @param {type} idReg       Id de registro de la Acción (-1  en el caso de ser un nuevo registro)
     * @param {type} op             opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function controlAutoSave( idReg, op)
    {
        if (idReg != -1 ) {
            jQuery("#upd-" + flagCargo).html("Editar");
            updDataObj( idReg );
            flagCargo = idReg;
        } else {
            resetFrmObj();
            if ( op ) {
                jQuery("#frmCargo").css("display", "block");
                jQuery("#imgCargo").css("display", "none");
            }
        }
    }


    /**
     *  Limpia y seta las variables utilisadas al momento de crear o editar
     * @returns {undefined}
     */
    function resetFrmObj()
    {
        jQuery("#frmCargo").css("display", "none");
        jQuery("#imgCargo").css("display", "block");
        jQuery("#jform_strNombre_cargo").attr('value', '');
        if (flagCargo != -1){
            jQuery("#upd-" + flagCargo).html("Editar");
            flagCargo = -1;
        }
    }

    /**
     *  Caraga la data de un cargo para ser modificada
     * 
     * @param {type}  idRegCrg 
     * @returns {undefined}
     */
    function updDataObj( idRegCrg )
    {
        var numReg = lstCargos.length;
        for (var i = 0; i < numReg; i++) {
            if (lstCargos[i].idReg ==  idRegCrg ) {
                jQuery("#upd-" +  idRegCrg ).html("Editando...");
                jQuery("#frmCargo").css("display", "block");
                jQuery("#imgCargo").css("display", "none");
                jQuery("#jform_strNombre_cargo").val(lstCargos[i].nombreCargo);
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
        if ( numReg > 0) {
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
     * @param {type} tabla
     * @returns {undefined}
     */
    function delFila(idFila, tabla)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery(tabla + ' tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }

    /**
     *  Retorna True en el caso de que los datos de un objetivo se modificaron
     * caso contrario devuelve False
     * 
     * @param {type} idReg      Id de registro del objeto
     * @returns {Boolean}
     */
    function confirmUpdObj( idReg )
    {
        var resultado = false;
        for (var i = 0; i < lstCargos.length; i++) {
            if ( lstCargos[i].idReg == idReg && jQuery("#jform_strNombre_cargo").val() != lstCargos[i].nombreCargo ) {
                    resultado = true;
            }
        }
        return resultado;
    }
    
    /**
     *  Carga los cargos de funcionarios para el sistema 
     * @returns {undefined}
     */
    function cargarCargos()
    {
        jQuery('#tbLstCargos > tbody').empty();
        if ( typeof (lstCargos) != "undefined" && lstCargos.length > 0 && !(avalibleDel(lstCargos))) {
            jQuery("#srCargos").css("display", "none");
            for ( var i = 0; i < lstCargos.length; i++ ) {
                if ( parseInt(lstCargos[i].published) == 1 ) {
                    agregarFila( lstCargos[i] );
                }
            }
        } else {
            jQuery("#srCargos").css("display", "block");
        }
    }
    
});

/**
 * cambia los colores de los semaforos segun el tipo
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function validateSemaforoObjetivo(reg, tpoPln, idRegPln){
    if ( typeof (tpoPln) != "undefined" && tpoPln != 2 ){
        semaforoIdicadorMeta(reg, tpoPln, idRegPln);
    } else if ( typeof (tpoPln) != "undefined" && tpoPln == 2){
        semaforoActividades(reg, tpoPln, idRegPln);
    }
    semaforoAlineacion(reg, tpoPln, idRegPln);
    semaforoPlanAnccion(reg, tpoPln, idRegPln);
    semaforoIndicadoresObj(reg, tpoPln, idRegPln);
};

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
            val = indicadorMeta.semaforoImagen();
        }
    }
    changeColorSemaforo( id, val );
}

/**
 *  Retorna el indicador meta del objetivo
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

    for(var i=0; i<lstAlin.length; i++){
       if (lstAlin[i].published == 1){
           numReg = ++numReg;
       } 
    }

    var val = (numReg > 0) ? 3 : 2;
    changeColorSemaforo( id, val );
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

    for(var i=0; i<plnAccion.length; i++){
       if (plnAccion[i].published == 1){
           numReg = ++numReg;
       } 
    }

    var val = (numReg > 0) ? 3 : 2;
    changeColorSemaforo( id, val );
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

    for(var i=0; i<lstInd.length; i++){
       if ( lstInd[i].published == 1 ){
           numInd = ++numInd;
       } 
    }

    var val = (numInd > 0) ? 3 : 2;

    changeColorSemaforo( id, val );
}

/**
 *  Cambia el semaforo de las actividades de un objetivo.
 * @param {type} reg
 * @param {type} tpoPln
 * @param {type} idRegPln
 * @returns {undefined}
 */
function semaforoActividades( reg, tpoPln, idRegPln )
{
    var objData = getObjetivos(reg, tpoPln, idRegPln, 'Act');
    var lstObjetivos = objData.lstObjetivos;
    var id = objData.id;

    var lstAct = lstObjetivos[reg].lstActividades;
    var numAct = 0;

    for(var i=0; i<lstAct.length; i++){
       if (lstAct[i].published == 1 && lstAct[i].idTpoIndicador != 1 ){
           numAct = ++numAct;
       } 
    }

    var val = (numAct > 0) ? 3 : 2;

    changeColorSemaforo( id, val );
}

/**
 *  Cambia el color de los semaforos de las caracteristicas del objetivo
 * @param {type} id         id del registro del objetivo
 * @param {type} color      color del semaforo
 * @returns {undefined}
 */
function changeColorSemaforo( id, color )
{
    switch (color) {
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


function getObjetivos(regObj, tpoPln, idRegPln, topLista)
{
    var objData = {lstObjetivos: '', id: ''};
    
    if ( typeof (tpoPln) != "undefined"){
        switch ( tpoPln ){
            case 2:
                objData.lstObjetivos = (objLstPoas.lstPoas[idRegPln].lstObjetivos.length > 0 ) ? objLstPoas.lstPoas[idRegPln].lstObjetivos 
                                                                                        : new Array();
                objData.id = '#smf' + topLista + '-' + regObj;
                break;
            case 3:
                objData.lstObjetivos = (oLstPPPPs.lstPppp[idRegPln].lstObjetivos.length > 0 ) ? oLstPPPPs.lstPppp[idRegPln].lstObjetivos 
                                                                                        : new Array();
                objData.id = '#sPppp' + topLista + '-' + regObj;
                break;
            case 4:
                objData.lstObjetivos = (oLstPAPPs.lstPapp[idRegPln].lstObjetivos.length > 0 ) ? oLstPAPPs.lstPapp[idRegPln].lstObjetivos 
                                                                                        : new Array();
                objData.id = '#sPppp' + topLista + '-' + regObj;
                break;
        }
    } else {
        objData.lstObjetivos = (objLstObjetivo.lstObjetivos.length > 0 ) ? objLstObjetivo.lstObjetivos 
                                                                : new Array();
        objData.id = '#smf' + topLista + '-' + regObj;
    }
    return objData;
}
