var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    var flagFuncionario = -1;
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;

    cargarFuncionarios( objLstFuncionarios.lstFuncionarios );

    /**
     *  Habilita el formulario de data general de un funcionario
     */
    jQuery('#addFuncionarioTable').click(function() {
        //  Si se esta editando un registro, flagFuncionario tiene el id del registro editandose.
        if (flagFuncionario != -1) {
            var id = -1;
            guardarCambios(id, true);
        } else {
            jQuery("#frmFuncionario").css("display", "block");
            jQuery("#imgFuncionario").css("display", "none");
        }
    });

    /**
     *  Guarda la data general de un funcionario de un Pei
     */
    jQuery("#btnAddFnci").click(function() {
        if ( objValido() ) {
            if (flagFuncionario == -1) {
                //  Verifica si este usuario existe como registro en la lista de fucionarios
                var fnc = existeFuncionarioUG();
                if ( fnc ) {
                    var numReg = objLstFuncionarios.lstFuncionarios.length;
                    for (var i = 0; i < numReg; i++) {
                        if (objLstFuncionarios.lstFuncionarios[i].idRegFnci == fnc.idRegFnci) {
                            objLstFuncionarios.lstFuncionarios[i].idCargoFnci      = jQuery("#jform_inpCodigo_cargo :selected").val();
                            objLstFuncionarios.lstFuncionarios[i].descCargoFnci    = jQuery("#jform_inpCodigo_cargo :selected").text();
                            objLstFuncionarios.lstFuncionarios[i].fechaInicio      = jQuery("#jform_dteFechaInicio_ugf").val();
                            objLstFuncionarios.lstFuncionarios[i].fechaFin         = jQuery("#jform_dteFechaFin_ugf").val();
                            objLstFuncionarios.lstFuncionarios[i].lstGrupos        = setOpcionesAdd(objLstFuncionarios.lstFuncionarios[i].lstGrupos);
                            objLstFuncionarios.lstFuncionarios[i].published        = 1;
                            agregarFila(objFuncionario);
                            updLstFuncionarios( objLstFuncionarios.lstFuncionarios[i], 1 )
                        }
                    }
                } else {
                    //  Funcion que agrega un nuevo registro
                    addRegFnc();
                }
            } else {
                //  Actualiza un registro de la lista de funcionarios
                updRegFnc();
            }
            //  limpio el formulario y reinicio la variables
            resetFrmFnci();
            jQuery("#fncs").html(JSL_TAB_FUNCIONARIOS + " (" + avalibleReg(objLstFuncionarios.lstFuncionarios) + ")");
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
        }
    });

    /**
     *  Cancela la edicion de un registro de funcionario
     */
    jQuery("#btnCancel").click(function() {
        var id = -1;
        if (flagFuncionario != -1) {
            guardarCambios(id, false);
        } else {
            resetFrmFnci();
        }
    });

    /**
     *  Clase que permite la edición de un funcionario de un Pei
     */
    jQuery('.updFuncionario').live('click', function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        if (flagFuncionario != -1 && flagFuncionario != idFila) {
            guardarCambios(idFila, true)
        } else {
            jQuery("#updObj-" + flagFuncionario).html("Editar");
            flagFuncionario = idFila;
            updDataFnci(idFila);
        }
    });

    /**
     *  Verifica si el funcionario se puede o no eliminar 
     */
    jQuery(".delFuncionario").live('click', function() {
        var idFila = (jQuery(this).parent().parent()).attr('id');
        var funcionario = objLstFuncionarios.lstFuncionarios[idFila];
        getValidoEliminar( funcionario, idFila );
    });

    /**
     * 
     * @param {type} lstGruposActual
     * @returns {_L1.setOpcionesAdd.lstGrupos|Array}
     */
    function setOpcionesAdd( lstGruposActual )
    {
        var lstGrupos = new Array();
        lstGrupos = actulaizarGruposFnc(lstGruposActual);
        
        jQuery('#cheksFncOpsAdds input[type=checkbox]').each(function(){
            if (this.checked) {
                var grupo = {idGrupo: jQuery(this).val()};
                lstGrupos.push(grupo);
            }
        }); 
        return lstGrupos;
        
    }

    /**
     * 
     * @param {type} lstGruposActual
     * @returns {Array}
     */
    function actulaizarGruposFnc( lstGruposActual )
    {
        var opsAdd = new Array();
        for ( var i=0; i<objLstOpsAdds.lstOpsAdds.length; i++){
            opsAdd.push(objLstOpsAdds.lstOpsAdds[i].idGrupo)
        }
        
        var newLstGrupos = new Array();  
        for ( var j=0; j<lstGruposActual.length; j++){
            if ( jQuery.inArray( parseInt(lstGruposActual[j].idGrupo), opsAdd) === -1){
                newLstGrupos.push(lstGruposActual[j]);
            }
        }
        
        return newLstGrupos;
    }


    function objValido()
    {
        var ban = false;
        
        var idFnc       = jQuery( '#jform_intCodigo_fnc' );
        var idCargo     = jQuery( '#jform_inpCodigo_cargo' );
        var fchInicio   = jQuery( '#jform_dteFechaInicio_ugf' );
        var fchFin      = jQuery( '#jform_dteFechaFin_ugf' );
        
        if( (   flagFuncionario === -1 
                && idFnc.val() !== 0 
                && idCargo.val() !== 0
                && fchInicio.val() !== ""
                && fchFin.val() !== "" ) ||

            (   flagFuncionario !== -1
                && idCargo.val() !== 0
                && fchInicio.val() !== ""
                && fchFin.val() !== "" ) ){
                result = true;
        } else{
            validarElemento( idFnc );
            validarElemento( idCargo );
            validarElemento( fchInicio );
            validarElemento( fchFin );
        }
        
        return ban;
    }


    function validarElemento( obj )
    {
        var ban = 1;
        
        if( obj.val() === "" || obj.val() === "0" ){
            ban = 0;
            obj.attr( 'class', 'required invalid' );
            
            var lbl = obj.selector + '-lbl';
            jQuery( lbl ).attr( 'class', 'hasTip required invalid' );
            jQuery( lbl ).attr( 'aria-invalid', 'true' );
        }
        
        return ban;
    }
    
    
    function limpiarValidaciones()
    {
        delValidaciones( jQuery('#jform_intCodigo_fnc') );
        delValidaciones( jQuery('#jform_inpCodigo_cargo') );
        delValidaciones( jQuery('#jform_dteFechaInicio_ugf') );
        delValidaciones( jQuery('#jform_dteFechaFin_ugf') );
        
        return;
    }
    
    
    function delValidaciones( obj )
    {
        obj.attr('class', '');
        var lbl = obj.selector + '-lbl';

        jQuery(lbl).attr('class', '');
        jQuery(lbl).attr('aria-invalid', '');
    }
    
    
    
    /**
     *  Verifica si existe registrado ese funcionario
     * @returns {objLstFuncionarios.lstFuncionarios|Boolean}
     */
    function existeFuncionarioUG(){
        var exist = false;
        for (var i = 0; i < objLstFuncionarios.lstFuncionarios.length; i++) {
            if (objLstFuncionarios.lstFuncionarios[i].idFuncionario == jQuery("#jform_intCodigo_fnc :selected").val() &&
                objLstFuncionarios.lstFuncionarios[i].idUgFnci != 0 ) {
                exist = objLstFuncionarios.lstFuncionarios[i];  
            }
        }
        return exist;
    }
    
    /**
     *  Agrega un nuevo funcionario a la lista de funcionarios 
     * @returns {undefined}
     */
    function addRegFnc(){
        //  Creo el Objeto Funcionario
        var objFuncionario = new Funcionario();

        objFuncionario.idRegFnci   = objLstFuncionarios.lstFuncionarios.length;
        objFuncionario.idUgFnci         = jQuery("#jform_intId_ugf").val();
        objFuncionario.idUg             = jQuery("#jform_intCodigo_ug").val();
        objFuncionario.nombreUg         = jQuery("#jform_strNombre_ug").val();
        objFuncionario.idCargoFnci      = jQuery("#jform_inpCodigo_cargo :selected").val();
        objFuncionario.descCargoFnci    = jQuery("#jform_inpCodigo_cargo :selected").text();
        objFuncionario.idFuncionario    = jQuery("#jform_intCodigo_fnc :selected").val();
        objFuncionario.nombreFnci       = jQuery("#jform_intCodigo_fnc :selected").text();
        objFuncionario.fechaInicio      = jQuery("#jform_dteFechaInicio_ugf").val();
        objFuncionario.fechaFin         = jQuery("#jform_dteFechaFin_ugf").val();
        objFuncionario.fechaUpd         = true;
        objFuncionario.lstGrupos        = setOpcionesAdd( [] );
        objFuncionario.published        = 1;

        //  Agrego un funcionario a la lista de Objetivos
        objLstFuncionarios.addFuncionario(objFuncionario);

        agregarFila(objFuncionario);
        
        updLstFuncionarios( objFuncionario, 1 );
    }
    
    /**
     *  Actualiza un registro de la lista de funcionarios
     * @returns {undefined}
     */
    function updRegFnc(){
        var numReg = objLstFuncionarios.lstFuncionarios.length;
        for (var i = 0; i < numReg; i++) {
            if (objLstFuncionarios.lstFuncionarios[i].idRegFnci == flagFuncionario) {
                if ( checkUpd(objLstFuncionarios.lstFuncionarios[i]) ) {
                    objLstFuncionarios.lstFuncionarios[i].fechaUpd = true;
                }
                objLstFuncionarios.lstFuncionarios[i].idCargoFnci      = jQuery("#jform_inpCodigo_cargo :selected").val();
                objLstFuncionarios.lstFuncionarios[i].descCargoFnci    = jQuery("#jform_inpCodigo_cargo :selected").text();
                objLstFuncionarios.lstFuncionarios[i].fechaInicio      = jQuery("#jform_dteFechaInicio_ugf").val();
                objLstFuncionarios.lstFuncionarios[i].fechaFin         = jQuery("#jform_dteFechaFin_ugf").val();
                objLstFuncionarios.lstFuncionarios[i].lstGrupos        = setOpcionesAdd( objLstFuncionarios.lstFuncionarios[i].lstGrupos );


                actualizarFila(objLstFuncionarios.lstFuncionarios[i]);
                flagFuncionario = -1;
            }
        }
    }
    
    /**
     *  Actualisa la lista del combo box de funcionarios
     * @param {type} objFnc
     * @param {type} op
     * @returns {undefined}
     */
    function updLstFuncionarios( objFnc, op )
    {
        switch (op){
            //  Agregar una opcion del select de funcionarios
            case 0:
                jQuery('#jform_intCodigo_fnc').append(new Option( objFnc.nombreFnci, objFnc.idFuncionario ));
                break;
            //  Eliminar una opcion del select de funcionarios
            case 1:
                jQuery("#jform_intCodigo_fnc option[value='" + objFnc.idFuncionario + "']").remove();
                break;
        }
    }

    /**
     *  Controla si de a modificado la data de un funcionario para guardarlo o no.
     * 
     * @param {type} idReg      Id de registro del funcionario (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function guardarCambios(idReg, op)
    {
        if (confirmUpdFnci(flagFuncionario)) {
            autoSave(idReg, op);
        } else {
            controlAutoSave(idReg, op);
        }
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     *  solo llama a la funcion "controlAutoSave" que reliza lops controles de edicion.
     * 
     * @param {type} idFila     Id de registro del funcionario (-1 en el caso de ser un nuevo registro)
     * @param {type} op         opcion de tarea, True para habilitar el formulario y false para deshabilitarlo.
     * @returns {undefined}
     */
    function autoSave(idFila, op)
    {
        jConfirm(JSL_CONFIRM_UPD_OBJETIVO, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnAddFnci').trigger('click');
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
        jQuery("#updFnci-" + flagFuncionario).html("Editar");
        if (idFila != -1) {
            flagFuncionario = idFila;
            updDataFnci(idFila);
        } else {
            flagFuncionario = -1;
            resetFrmFnci();
            if (op == true) {
                jQuery("#frmFuncionario").css("display", "block");
                jQuery("#imgFuncionario").css("display", "none");
            }
        }
    }


    /**
     *  Limpia y seta las variables utilisadas al momento de crear o editar un Objetivo
     * 
     * @returns {undefined}
     */
    function resetFrmFnci()
    {
        //  Reinicia los mensajes de la validacion a los valores por defecto 
        jQuery("#frmFuncionario").css("display", "none");
        jQuery("#imgFuncionario").css("display", "block");
        
        jQuery("#unidadgestion-form").submit();
        jQuery("#jform_inpCodigo_cargo").removeClass( "error" );
        jQuery("#jform_intCodigo_fnc").removeClass( "error" );
        
        recorrerCombo(jQuery('#jform_inpCodigo_cargo option'), 0);
        recorrerCombo(jQuery('#jform_intCodigo_fnc option'), 0);
        
        jQuery('#jform_intCodigo_fnc_aux').val('');
        jQuery('#li-jform_intCodigo_fnc').css("display", "block");
        jQuery('#li-jform_intCodigo_fnc_aux').css("display", "none");
        
        jQuery('#jform_intCodigo_fnc').css("diaplay", "block");
        jQuery("#jform_dteFechaInicio_ugf").attr('value', '');
        jQuery("#jform_dteFechaFin_ugf").attr('value', '');
        jQuery("#jform_dteFechaInicio_ugf").removeClass( "error" );
        jQuery("#jform_dteFechaFin_ugf").removeClass( "error" );
        
        jQuery('#cheksFncOpsAdds input[type=checkbox]').each(function(){
            jQuery(this).prop('checked', false);
        }); 
        
        limpiarValidaciones();
    }

    /**
     *  Agrega o elimina opciones del select de funcionarios
     * @param {type} objFnc
     * @param {type} op
     * @returns {undefined}
     */
    function updLstFuncionarios( objFnc, op )
    {
        switch (op){
            //  Agregar una opcion del select de funcionarios
            case 0:
                jQuery('#jform_intCodigo_fnc').append(new Option( objFnc.nombreFnci, objFnc.idFuncionario ));
                break;
            //  Eliminar una opcion del select de funcionarios
            case 1:
                jQuery("#jform_intCodigo_fnc option[value='" + objFnc.idFuncionario + "']").remove();
                break;
        }
    }


    /**
     *  Caraga la data de un funcionario para ser modificada
     * 
     * @param {type} idFila
     * @returns {undefined}
     */
    function updDataFnci(idFila)
    {
        var numReg = objLstFuncionarios.lstFuncionarios.length;
        for (var i = 0; i < numReg; i++) {
            if (objLstFuncionarios.lstFuncionarios[i].idRegFnci == idFila) {
                jQuery("#updFnci-" + idFila).html("Editando...");
                jQuery("#frmFuncionario").css("display", "block");
                jQuery("#imgFuncionario").css("display", "none");
                var crg = objLstFuncionarios.lstFuncionarios[i].idCargoFnci;
                recorrerCombo(jQuery('#jform_inpCodigo_cargo option'), crg);
                jQuery('#jform_intCodigo_fnc_aux').val(objLstFuncionarios.lstFuncionarios[i].nombreFnci);
                jQuery('#li-jform_intCodigo_fnc').css("display", "none");
                jQuery('#li-jform_intCodigo_fnc_aux').css("display", "block");
                jQuery("#jform_dteFechaInicio_ugf").val(objLstFuncionarios.lstFuncionarios[i].fechaInicio);
                jQuery("#jform_dteFechaFin_ugf").val(objLstFuncionarios.lstFuncionarios[i].fechaFin);
                checkedOpAdd( objLstFuncionarios.lstFuncionarios[i].lstGrupos);
            }
        }
    }

    /**
     * 
     * @param {type} lstGrupos
     * @returns {undefined}
     */
    function checkedOpAdd( lstGrupos )
    {
        if ( lstGrupos.length > 0 ){
            for( var i=0; i<lstGrupos.length; i++ ){
                jQuery("#op-" + lstGrupos[i].idGrupo).prop("checked", true);
            }
        }
    }

    /**
     *  Retorna el numero de registro validos de la data enviada
     * @param {type} data
     * @returns {Boolean}
     */
    function avalibleReg(data)
    {
        var result = 0;
        if (data) {
            var numReg = data.length;
            for (var i = 0; i < numReg; i++) {
                if (data[i].published == 1){
                    result = ++result;
                }
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
     *  Elimina una fila de la tabla de registros
     *  
     * @param {type} idFila
     * @returns {undefined}
     */
    function delFila(idFila)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#tbLstFuncionarios tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }

    /**
     *  Retorna True en el caso de que los datos de un funcionario se modificaron
     *  caso contrario devuelve False
     * 
     * @param {type} idFila     Id de registro del funcionario
     * @returns {Boolean}
     */
    function confirmUpdFnci(idFila)
    {
        var resultado = false;
        for (var i = 0; i < objLstFuncionarios.lstFuncionarios.length; i++) {
            if (objLstFuncionarios.lstFuncionarios[i].idRegFnci == idFila) {
                if (checkUpd(objLstFuncionarios.lstFuncionarios[i]))
                    resultado = true;
            }
        }
        return resultado;
    }
    
    
    function checkUpd(objFnciUg) {
        var resp = false;
        if (objFnciUg.idCargoFnci != jQuery("#jform_inpCodigo_cargo :selected").val() ||
            objFnciUg.fechaInicio != jQuery("#jform_dteFechaInicio_ugf").val() ||
            objFnciUg.fechaFin != jQuery("#jform_dteFechaFin_ugf").val() || 
            (flagFuncionario == -1 && objFnciUg.idFuncionario != jQuery("#jform_intCodigo_fnc :selected").val())) {
            resp = true;
        }
            return resp;
    }

    function getValidoEliminar( dataFnci, idFila ) 
    {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaFnci = JSON.stringify(list2Object(dataFnci));
        var flag = false;
        
        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method: "POST",
                option: 'com_unidadgestion',
                view: 'unidadgestion',
                tmpl: 'component',
                format: 'json',
                action: 'validarDeleteFuncionario',
                dtaFnci: dtaFnci
            },
            error: function(jqXHR, status, error) {
                jAlert('Unidades de Gestion - Gestion Funcionarios: ' + error + ' ' + jqXHR + ' ' + status, 'SIITA -  ECORAE');
            }
        }).complete(function(data) {
            delFuncionario( data.responseText, idFila );
        });
        
        return flag;
    }
    
    
    function delFuncionario( result, idFila ) 
    {
        if ( result ) {
            jConfirm(JSL_CONFIRM_DELETE_FNC, JSL_ECORAE, function(resutl) {
                if (resutl) {
                    objLstFuncionarios.lstFuncionarios[idFila].published = 0;
                    delFila(idFila);
                    if (flagFuncionario != -1) {
                        flagFuncionario = -1;
                        resetFrmFnci();
                    }
                    updLstFuncionarios( objLstFuncionarios.lstFuncionarios[idFila], 0 );
                    jQuery("#fncs").html(JSL_TAB_FUNCIONARIOS + "(" + avalibleReg(objLstFuncionarios.lstFuncionarios) + ")");
                }
            });
        } else {
            jAlert( JSL_CONFIRM_DEL_FNCI, JSL_ECORAE);
        }
    }
    
    /**
     *  Actulida la informacion de una fila en la tabla de funcionarios de un Pei
     *  
     * @param {type} funcionario     Array con los atributos del funcionario
     * @returns {undefined}
     */
    function actualizarFila(funcionario)
    {
        jQuery('#tbLstFuncionarios tr').each(function() {
            if (jQuery(this).attr('id') == flagFuncionario) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black;background-color: bisque;');
                //  Construyo la Fila
                var fila = makeFila( funcionario, 1 );
                jQuery(this).html(fila);
            }
        });
    }
});

/**
 *  Agrega una fila en la tablas de funcionarios de un Pei
 *  
 * @param {type} funcionario     Array con los atributos del funcionario
 * @returns {undefined}
 */
function agregarFila(funcionario)
{
    var fila = makeFila( funcionario, 0 );

    //  Agrego la fila creada a la tabla
    jQuery('#tbLstFuncionarios > tbody:last').append(fila);
}

/**
 *  Arma una fila para la tabla de la lista de funcionarios
 * @param {type} objFnc
 * @param {type} op
 * @returns {String}
 */
function makeFila( objFnc, op )
{
    var idReg = objFnc.idRegFnci;
    var fila = '';
    fila += ( op == 0) ? '<tr id="' + idReg + '">' : '';
    fila += '   <td align="center">' + objFnc.descCargoFnci + '</td>';
    fila += '   <td align="center">' + objFnc.nombreFnci    + '</td>';
    fila += '   <td align="center">' + objFnc.fechaInicio   + '</td>';
    fila += '   <td align="center">' + objFnc.fechaFin      + '</td>';
    
    if ( roles["core.create"] === true || roles["core.edit"] === true ) {
        fila += '   <td align="center" width="15" > <a id="updFnci-' + idReg + '" class="updFuncionario" >' + JSL_ACTUALIZAR + '</a> </td >';
        fila += '   <td align="center" width="15" > <a id="delFnci-' + idReg + '" class="delFuncionario" >' + JSL_ELIMINAR + '</a> </td>';
    } else {
        fila += '   <td align="center" width="15" > ' + JSL_NO_HABILITADO + ' </td > ';
        fila += '   <td align="center" width="15" > ' + JSL_NO_HABILITADO + ' </td > ';
    }
    
    fila += '   <td align="center" width="15" >';
    fila += '       <a onclick="SqueezeBox.fromElement( \'index.php?option=com_unidadgestion&view=funcionarios&layout=edit&plnVigente=' + 2010 + '&idFnc=' + 1 + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
    fila += '           <img style="margin: 0;" src="media/system/images/mantenimiento/detalles.png" title="&nbsp; &nbsp;">';
    fila += '       </a>';   
    fila += '   </td>';

    fila += ( op == 0) ? ' </tr>' : '';
    
    return fila;
}

/**
 * 
 * @param {type} lstFnc
 * @returns {undefined}
 */
function cargarFuncionarios( lstFnc )
{
    var rows = lstFnc.length;
    if ( rows > 0 ){
        for( var i=0; i<rows; i++){
            agregarFila( lstFnc[i] );
        }
    }
}
