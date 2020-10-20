cFunActor = function() {

    this.idActFunc = 0;
    this.idFuncion = 0;
    this.nmbFuncion = "";
    this.fechaDesde = 0;
    this.fechaHasta = 0;
    this.published = 1;
    this.regActFuncion = 0;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.idActFunc = (data.idActFunc) ? data.idActFunc : 0;
        this.idFuncion = (data.idFuncion) ? data.idFuncion : 0;
        this.nmbFuncion = (data.nmbFuncion) ? data.nmbFuncion : "";
        this.fechaDesde = (data.fechaDesde) ? data.fechaDesde : "";
        this.fechaHasta = (data.fechaHasta) ? data.fechaHasta : "";
        this.published = (data.published) ? data.published : "";
        this.regActFuncion = (data.regActFuncion) ? data.regActFuncion : 0;
    };

};

var regActFuncion = -1;
var tmpFunActo = null;

jQuery(document).ready(function() {

    if ( oActor.lstFunciones.length > 0 ) {
        reloadFuncionActTb();
    }

    jQuery('#saveFuncionActor').click(function(event) {
        var data = getFunActoForm();
        if (validarFunActo(data)) {
            if (tmpFunActo == null) {
                data.regActFuncion = oActor.lstFunciones.length + 1;
                data.published = 1;

                var oFunActo = new cFunActor();
                oFunActo.setData(data);
                addFunActo(oFunActo);
                clCmpFunActo();
                oActor.lstFunciones.push(oFunActo);
            }
            else {
                data.regActFuncion = regActFuncion;
                actFunActo(data);
            }
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    // EDICION de una INCIDENCIA
    jQuery('.updFuncionActor').live("click", function() {
        if (tmpFunActo != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoFunActoUpdate(newRegObjt, regActFuncion);
        } else {
            regActFuncion = this.parentNode.parentNode.id;
            loadFunActoFronArray(regActFuncion);
            tmpFunActo = getFunActoByReg(regActFuncion);
        }
        cleanValidateForn ( '#formFuncionAct' );
    });
    /// ELIMINAR una INCIDENCIA
    jQuery('.delFuncionActor').live("click", function() {
        regActFuncionDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_REGISTRO, JSL_ECORAE, function(r) {
            if (r) {
                elmFunActo(regActFuncionDel);
                getFunActotByReg();
            } else {

            }
        });
    });
    
    // addObe
    jQuery("#addFuncionActor").click(function() {
        if ( regActFuncion != -1 ) {
            clCmpFunActo();
        }
        jQuery('#imgeFuncionActor').css("display", "none");
        jQuery('#formFuncionActor').css("display", "block")
    });
    
    // cancelar
    jQuery("#cancelFuncionActor").click(function() {
        jQuery('#cancelFnc').trigger("click");
        clCmpFunActo();
    });
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del incidencia que se va a editar
 * @param {int} anterior    Registro del incidencia que se esta editando
 * @returns {int}                  Registro del incidencia que se va a editar
 */
function autoFunActoUpdate(nuevo, anterior) {
    var data = getFunActoForm();
    if (
            data.idFuncion != tmpFunActo.idFuncion ||
            data.nmbFuncion != tmpFunActo.nmbFuncion ||
            data.fechaDesde != tmpFunActo.fechaDesde ||
            data.fechaHasta != tmpFunActo.fechaHasta
            ) {
        jConfirm(JSL_COM_CONFLICTOS_FUNACTOR_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regActFuncion = anterior;
                actFunActo(data);
                loadFunActoFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadFunActoFronArray(nuevo);
        if (anterior == nuevo) {
            loadFunActoFronArray(nuevo);
        }
    }
    regActFuncion = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarFunActo(data) {
    var flag = false;
    if (
            data.idFuncion != 0 &&
            data.nmbFuncion != "" &&
            data.fechaHasta != "" &&
            data.fechaDesde != ""
            ) {
        flag = true;
    }
    return flag;
}

/**
 *  Carga la lista de funciones en la tabla
 * @returns {undefined}
 */
function reloadFuncionActTb(){
    jQuery("#tbLstFuncionesActor > tbody").empty();
    for (var i = 0; i < oActor.lstFunciones.length; i++) {
        if ( oActor.lstFunciones[i].published == 1) {
            addFunActo(oActor.lstFunciones[i]);
        }
    }
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addFunActo(data) {
    var regActFuncion = data.regActFuncion;
    var nmbFuncion = (data.nmbFuncion) ? data.nmbFuncion : "-----";
    var fechaHasta = (data.fechaHasta) ? data.fechaHasta : "-----";
    var fechaDesde = (data.fechaDesde) ? data.fechaDesde : "-----";
    var fila = '';
    fila += '<tr id="' + regActFuncion + '">';
    fila += '    <td>' + nmbFuncion + ' </td>';
    fila += '    <td>' + fechaDesde + ' </td>';
    fila += '    <td>' + fechaHasta + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updFuncionActor"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delFuncionActor"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstFuncionesActor > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpFunActo() {
    jQuery('#formFuncionActor').css("display", "none");
    jQuery('#imgeFuncionActor').css("display", "block");
    
    regActFuncion = -1;
    tmpFunActo = null;
    
    cleanValidateForn ( '#formFuncionAct' );
    
    recorrerCombo(jQuery("#jform_intId_fcn option"), 0);
    jQuery('#jform_intId_fcn').trigger( 'change', 0 );
    jQuery("#jform_dtefecha_ini_fi").val("");
    jQuery("#jform_dtefecha_fin_fi").val("");
}

/**
 *  Elimina los incidencias.
 * @param {type} regActFuncionDel
 * @returns {undefined}
 */
function elmFunActo(regActFuncionDel) {
    for (var j = 0; j < oActor.lstFunciones.length; j++) {
        if (oActor.lstFunciones[j].regActFuncion == regActFuncionDel) {
            oActor.lstFunciones[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del incidencia
 * @param {object} data
 * @returns {undefined}
 */
function actFunActo(data) {
    for (var j = 0; j < oActor.lstFunciones.length; j++) {
        if (oActor.lstFunciones[j].regActFuncion == data.regActFuncion) {
            oActor.lstFunciones[j].idFuncion = data.idFuncion;
            oActor.lstFunciones[j].nmbFuncion = data.nmbFuncion;
            oActor.lstFunciones[j].fechaDesde = data.fechaDesde;
            oActor.lstFunciones[j].fechaHasta = data.fechaHasta;
        }
    }
    clCmpFunActo();
    getFunActotByReg();
}

/**
 *  Borra y buelce a escribir en la tabla incidencias
 * @returns {undefined}
 */
function getFunActotByReg() {
    jQuery("#tbLstFuncionesActor > tbody").empty();
    for (var j = 0; j < oActor.lstFunciones.length; j++) {
        if (oActor.lstFunciones[j].published == 1)
            addFunActo(oActor.lstFunciones[j]);
    }
}

/**
 *  Recupera el incidencia dado el registro del incidencia
 * @param {int} regActFuncion
 * @returns {unresolved}
 */
function getFunActoByReg(regActFuncion) {
    var data = null;
    for (var j = 0; j < oActor.lstFunciones.length; j++) {
        if (oActor.lstFunciones[j].regActFuncion == regActFuncion) {
            data = oActor.lstFunciones[j];
        }
    }
    return data;
}

/**
 * 
 * @returns {getFunActoForm.data}
 */
function getFunActoForm() {
    var data = {
        idFuncion: jQuery("#jform_intId_fcn").val(),
        nmbFuncion: jQuery("#jform_intId_fcn option:selected").text(),
        fechaDesde: jQuery("#jform_dtefecha_ini_fi").val(),
        fechaHasta: jQuery("#jform_dtefecha_fin_fi").val()
    };
    return data;
}

/**
 * Carga los datos del incidencia recuperando los datos desde el array.
 * @param {int} regActFuncion     Identificador del incidencia
 * @returns {undefined}
 */
function loadFunActoFronArray(regActFuncion) {
    jQuery('#imgeFuncionActor').css("display", "none");
    jQuery('#formFuncionActor').css("display", "block");

    var data = getFunActoByReg(regActFuncion);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intId_fcn option"), data.idFuncion);
        jQuery("#jform_dtefecha_ini_fi").val(data.fechaDesde);
        jQuery("#jform_dtefecha_fin_fi").val(data.fechaHasta);
    }
    tmpFunActo = getFunActoByReg(regActFuncion);
}



