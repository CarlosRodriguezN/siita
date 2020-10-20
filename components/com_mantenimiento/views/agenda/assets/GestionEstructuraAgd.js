jQuery(document).ready(function() {

    var flagEstructura = -1;
    
    //  Caraga la tabla con la lista de detalles
    listarEstructura();
    estructuraVigente();

    /**
     *  Habilita el formulario para los elementos de la estructura de una agenda
     */
    jQuery('#addEstructuraTable').click(function() {
        //  Si se esta editando un detalle flagEstructura tiene el ID del registro editandose.
        if ( flagEstructura != -1) {
            var id = -1;
            validarCambios( id, true );
        } else {
            jQuery("#imgEstructuraAgd").css("display", "none");
            jQuery("#frmEstructuraAgd").css("display", "block");
        }
    });
    
    /**
     *  Cancela la edicion de un registro de objetivo
     */
    jQuery("#btnCancelEtr").click(function() {
        if ( flagEstructura != -1 ) {
            jQuery("#updEtr-" + flagEstructura).html("Editar");
            validarCambios( -1, false );
        } else {
            resetFrmObj();
        }
    });

    /**
     *  Guarda la data general de la estructura de una agenda
     */
    jQuery("#btnSaveEtr").click(function() {
        if ( objValido() ) {
            var numReg = objLstEstructuraAgd.lstEstructurasAgd.length;
            if ( numReg == 0 || !existRegAvalible( objLstEstructuraAgd.lstEstructurasAgd )) {
                jQuery("#srEtr").css("display", "none");
            }
            if ( flagEstructura == -1  ) {
                //  Creo el nuevo objeto Estructura
                var objEstructura = new EstructuraAgd();

                objEstructura.registroEtr       = numReg;
                objEstructura.idEstructura      = 0;
                objEstructura.idAgenda          = parseInt( jQuery("#jform_intIdAgenda_ag").val() );
                objEstructura.idPadreEtr        = jQuery("#jform_intIdEstuctura_padre_es :selected").val();
                objEstructura.descPadreEtr      = jQuery("#jform_intIdEstuctura_padre_es :selected").text();
                objEstructura.descripcionEtr    = jQuery("#jform_strDescripcion_es").val();
                objEstructura.nivelEtr          = jQuery("#jform_intNivel").val();
                objEstructura.avalibleDel       = true;
                objEstructura.published         = 1;

                //  Agrego un objetivo a la lista de Objetivos
                objLstEstructuraAgd.lstEstructurasAgd.push(objEstructura);
                agregarFila( objEstructura );
                actualizarCombo();
            } else {
                for (var i = 0; i < numReg; i++) {
                    if (objLstEstructuraAgd.lstEstructurasAgd[i].registroEtr == flagEstructura) {
                        objLstEstructuraAgd.lstEstructurasAgd[i].idPadreEtr     = jQuery("#jform_intIdEstuctura_padre_es :selected").val();
                        objLstEstructuraAgd.lstEstructurasAgd[i].descPadreEtr   = jQuery("#jform_intIdEstuctura_padre_es :selected").text();
                        objLstEstructuraAgd.lstEstructurasAgd[i].descripcionEtr = jQuery("#jform_strDescripcion_es").val();
                        objLstEstructuraAgd.lstEstructurasAgd[i].nivelEtr       = jQuery("#jform_intNivel").val();

                        actualizarFila( objLstEstructuraAgd.lstEstructurasAgd[i] );
                        actualizarCombo();
                    }
                }
            }
            //  limpio el formulario y reinicio la variables
            resetFrmObj();
            //  Control de actualizacion de la estructura, para la gestion de items
            updEstructura = 1;
        } else {
            jAlert(JSL_ALERT_ALL_NEED_ETR, JSL_ECORAE);
        }

    });

    /**
     *  Clase que permite la edición de registro de la estructura de una agenda 
     */
    jQuery('.updEstructura').live('click', function() {
        //  Obtiene el ID del registro
        var idFila = jQuery(this).attr('id');
        var idRegEtr = parseInt(idFila.toString().split('-')[1]);
        if (flagEstructura == -1 ) {
            updDataObj(idRegEtr);
            flagEstructura = idRegEtr;
        } else if (flagEstructura != idRegEtr) {
            jQuery("#updEtr-" + flagEstructura).html("Editar");
            validarCambios(idRegEtr, true);
        }
        
        //control de edicion
        if( bjLstEstructuraAgd.lstEstructurasAgd[idRegEtr].avalibleDel == true || validateDelEst( idRegEtr ) ) {
            jQuery("#jform_intIdEstuctura_padre_es").attr("disabled", true);
            jQuery("#jform_intNivel").attr("disabled", true);
        } else {
            jQuery("#jform_intIdEstuctura_padre_es").attr("disabled", false);
            jQuery("#jform_intNivel").attr("disabled", flase);
        }
    });

    /**
     *  Verifica si el objetivo se puede o no eliminar 
     */
    jQuery(".delEstructura").live('click', function() {
        var idFila = jQuery(this).attr('id');
        var idRegEtr = parseInt(idFila.toString().split('-')[1]);
        if ( controlDelete(idRegEtr) ) {
            jConfirm(JSL_CONFIRM_DELETE, JSL_ECORAE, function(resutl) {
                if (resutl) {
                    objLstEstructuraAgd.lstEstructurasAgd[idRegEtr].published = 0;
                    if ( flagEstructura == idRegEtr){
                        resetFrmObj();
                    }
                    delFila(idRegEtr, "#tbEstructura"); 
                    if ( !existRegAvalible( objLstEstructuraAgd.lstEstructurasAgd ) ){
                        jQuery("#srEtr").css("display", "block");
                    }
                    //  Actualizo el combo
                    jQuery('#jform_intIdEstuctura_padre_es option[value="reg-' + idRegEtr + '"]').remove();
                    
                    //  Control de actualizacion de la estructura, para la gestion de items
                    updEstructura = 1;
                }   
            });
        } else {
            jAlert( JSL_CONFIRM_NO_AVALIBLE_DEL, JSL_ECORAE );
        }
    });

    function controlDelete( regEtr )
    {
        var result = false;
        if ( objLstEstructuraAgd.lstEstructurasAgd[regEtr].avalibleDel == true && validateDelEst( regEtr ) ){
            result = true;
        }
        return result;
    }

    function validateDelEst( registroEtr )
    {
        var rows = true;
        if ( objLstItemsAgd.lstItemsAgd.length > 0 ){
            for (var i=0; i<objLstItemsAgd.lstItemsAgd.length; i++){
                if ( objLstItemsAgd.lstItemsAgd[i].registroEtr == registroEtr && objLstItemsAgd.lstItemsAgd[i].published == 1){
                    rows = false;
                    i = objLstItemsAgd.lstItemsAgd.length;
                }
            }
        }
        return rows;
    }

    /**
     *  Caraga la data de un registro de un elemnto de la estructura de la agenda
     * @param {type} idUpdEtr      Id del registro a ser editado
     * @returns {undefined}
     */
    function updDataObj( idUpdEtr )
    {
        var numReg = objLstEstructuraAgd.lstEstructurasAgd.length;
        for (var i = 0; i < numReg; i++) {
            if (objLstEstructuraAgd.lstEstructurasAgd[i].registroEtr == idUpdEtr) {
                var estructura = objLstEstructuraAgd.lstEstructurasAgd[i];
                jQuery("#updEtr-" + idUpdEtr).html("Editando...");
                jQuery("#frmEstructuraAgd").css("display", "block");
                jQuery("#imgEstructuraAgd").css("display", "none");
                recorrerCombo("#jform_intIdEstuctura_padre_es option", estructura.idPadreEtr);
                jQuery("#jform_strDescripcion_es").val(estructura.descripcionEtr);
                jQuery("#jform_intNivel").val(estructura.nivelEtr);
            }
        }
    }

    /**
     *  Actuliza la informacion de una fila en la tabla de estructura de una agenda
     * @param {type} objEstructura       Array con los atributos del objeto
     * @returns {undefined}
     */
    function actualizarFila( objEstructura )
    {
        jQuery('#tbEstructura tr').each(function() {
            if (jQuery(this).attr('id') == flagEstructura) {
                //  Agrego color a la fila actualizada
                jQuery(this).attr('style', 'border-color: black; background-color: bisque;');
                //  Construyo la Fila
                var fila = makeFila( objEstructura, 0 )
                jQuery(this).html(fila);
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
        if ( jQuery("#jform_strDescripcion_es").val() == '' ||
            jQuery("#jform_intNivel").val() == '' ||
            validarNivel()){
            result = false;
        }
        return result;
    }
    
    /**
     *  Lista los detalles de una agenda en la tabla de detalles
     * @returns {undefined}
     */
    function listarEstructura()
    {
        jQuery('#tbEstructura > tbody').empty();
        var numDtll = objLstEstructuraAgd.lstEstructurasAgd.length;
        if ( numDtll > 0 ){
            for (var i=0; i<numDtll; i++){
                agregarFila( objLstEstructuraAgd.lstEstructurasAgd[i] );
            }
        } else {
            jQuery("#srEtr").css("display", "block");
        }
    }

    /**
     *  Agrega una fila en la tabla de la estructura de la agenda
     * @param {type} objEstructura    Obj estructura
     * @returns {undefined}
     */
    function agregarFila( objEstructura )
    {
        //  Agrego la fila creada a la tabla
        var fila = makeFila( objEstructura, 1);
        jQuery('#tbEstructura> tbody:last').append(fila);
    }
    
    /**
     * Arma la una fila para la tabla de la estructura de una agenda
     * @param {type} objEtr        objeto con las parametros de un lelemto de la estructura
     * @param {type} op             opcion que controla si es un nuevo registro o no 
     *                              1:agrega 'tr' nueva fila, 0:no agrega nueva fila
     * @returns {String}
     */
    function makeFila( objEtr, op)
    {
        var idReg = objEtr.registroEtr;
        var fila = '';
        fila += ( op == 1) ? '<tr id="' + idReg + '">': '';
        fila += '     <td align="center">' + objEtr.descripcionEtr + '</td>';
        fila += '     <td align="center">';
        fila += (objEtr.idPadreEtr != 0) ? objEtr.descPadreEtr : '-----'; 
        fila += '     </td>';
        fila += '     <td align="center">' + objEtr.nivelEtr + '</td>';
        fila += '     <td align="center" width="30" > <a id="updEtr-' + idReg + '" class="updEstructura" >' + JSL_UPD + '</a> </td > ';
        if ( objEtr.avalibleDel ) {
            fila += '     <td align="center" width="30" > <a id="delEtr-' + idReg + '" class="delEstructura" >' + JSL_DEL + '</a> </td>';
        } else {
            fila += '     <td align="center" width="30" > <a id="delEtr-' + idReg + '" >' + JSL_NONE + '</a> </td>';
        }
        fila += ( op == 1) ? '</tr>': '';
        return fila;
    }
    
    /**
     *  Limpia y seta las variables utilisadas al momento de crear o editar un registro
     * @returns {undefined}
     */
    function resetFrmObj()
    {
        jQuery("#frmEstructuraAgd").css("display", "none");
        jQuery("#imgEstructuraAgd").css("display", "block");
        recorrerCombo("#jform_intIdEstuctura_padre_es option", 0);
        jQuery("#jform_strDescripcion_es").attr('value', '');
        jQuery("#jform_intNivel").attr('value', '');
        flagEstructura = -1;
    }
    
    /**
     * Retorna True en el caso que ya exista un elemento de la estructura 
     * con el nivel del nuevo elementto caso contratio retorna False
     * @returns {Boolean}
     */
    function validarNivel()
    {
        var result = false;
        var numReg = objLstEstructuraAgd.lstEstructurasAgd.length
        for (var i = 0; i < numReg; i++) {
            if (objLstEstructuraAgd.lstEstructurasAgd[i].nivelEtr == jQuery("#jform_intNivel").val() &&
                objLstEstructuraAgd.lstEstructurasAgd[i].registroEtr != flagEstructura &&
                objLstEstructuraAgd.lstEstructurasAgd[i].published == 1) {
                result = true;
            }
        }
        return result;
    }
    
    /**
     *  Controla si se ha modificado la data de un objetivo para guardarlo o no
     * @param {type} idRegEtr       Id del registro de un elemento de la estructura 
     *                              a ser editado, -1 en el caso de un nuevo registro 
     * @param {type} frm            Opcion de tarea, True para habilitar el formulario 
     *                              y false para deshabilitarlo
     * @returns {undefined}
     */
    function validarCambios( idRegEtr, frm )
    {   
        if ( confirmUpdReg( flagEstructura ) ) {
            autoSave( idRegEtr, frm );
        } else {
            controlAutoSave( idRegEtr, frm );
        }
    }

    /**
     *  Retorna True en el caso de que los datos de un registro se modificaron
     * caso contrario devuelve False
     * @param {type} idReg      Id de registro del objeto
     * @returns {Boolean}
     */
    function confirmUpdReg( idReg )
    {
        var resultado = false;
        var estructura = objLstEstructuraAgd.lstEstructurasAgd;
        for (var i = 0; i < estructura.length; i++) {
            if (estructura[i].registroEtr == idReg) {
                resultado = (jQuery("#jform_intIdEstuctura_padre_es :selected").val() != estructura[i].idPadreEtr ||
                            jQuery("#jform_strDescripcion_es").val() != estructura[i].descripcionEtr ||
                            jQuery("#jform_intNivel").val() != estructura[i].nivelEtr) ? true : false;
            }
        }
        return resultado;
    }

    /**
     *  Pregunta si se desea guardar las modificaciones, si es que SI las guarda y si es que NO
     * solo llama a la funcion "controlAutoSave" que reliza los controles de edicion.
     * @param {type} idRegEtr          Id de registro de detalle en el caso de una nueva edicion, si no es NULL
     * @param {type} frm                Opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function autoSave( idRegEtr, frm )
    {
        jConfirm(JSL_CONFIRM_UPDATE, JSL_ECORAE, function(result) {
            if (result) {
                jQuery('#btnSaveEtr').trigger('click');
                controlAutoSave( idRegEtr, frm);
            } else {
                controlAutoSave( idRegEtr, frm);
            }
        });
    }

    /**
     *  Realiza las tareas especificas cuando guarda cambios en un registro
     * @param {type} idRegObj       Id de registro del objeto (-1 en el caso de ser un nuevo registro)
     * @param {type} op             opcion de tarea, True para habilitar el formulario y false para deshabilitarlo
     * @returns {undefined}
     */
    function controlAutoSave( idRegObj, op)
    {
        if ( flagEstructura != -1) {
            jQuery("#updEtr-" + flagEstructura).html("Editar");
        }
        if (idRegObj != -1 ) {
            updDataObj( idRegObj );
            flagEstructura = idRegObj;
        } else {
            resetFrmObj();
            if ( op ) {
                jQuery("#frmEstructuraAgd").css("display", "block");
                jQuery("#imgEstructuraAgd").css("display", "none");
            }
        }
    }
    
    function actualizarCombo()
    {
        var estructura = objLstEstructuraAgd.lstEstructurasAgd;
        var options = [];
        jQuery('#jform_intIdEstuctura_padre_es').html(options.join(''));
        options.push('<option value="0"> ' + JSL_LIST_ESTRUCTURA_OWNER + ' </option>');
        for (var j = 0; j < estructura.length; j++) {
            if ( estructura[j].published == 1 ) {
                var idReg = ( estructura[j].idEstructura != 0 ) ? estructura[j].idEstructura : "reg-" + estructura[j].registroEtr;
                options.push('<option value="' + idReg + '">' + estructura[j].descripcionEtr + '</option>');
            }
        }
        jQuery('#jform_intIdEstuctura_padre_es').html(options.join(''));
    }
});