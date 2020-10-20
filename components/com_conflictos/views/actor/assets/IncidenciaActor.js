cIncidenciaActor = function() {

    this.idActInci = 0;
    this.idIncidencia = 0;
    this.nmbIncidencia = "";
    this.fecha = 0;
    this.published = 1;
    this.regActIncidencia = 0;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.idActInci = (data.idActInci) ? data.idActInci : 0;
        this.idIncidencia = (data.idIncidencia) ? data.idIncidencia : 0;
        this.nmbIncidencia = (data.nmbIncidencia) ? data.nmbIncidencia : "";
        this.fecha = (data.fecha) ? data.fecha : "";
        this.published = (data.published) ? data.published : "";
        this.regActIncidencia = (data.regActIncidencia) ? data.regActIncidencia : 0;
    };

};

var regActIncidencia = -1;
var tmpIncActo = null;

jQuery(document).ready(function() {

    if ( oActor.lstIncidencias.length > 0 ) {
        reloadIncidenciasActTb();
    }

    jQuery('#saveIncidenciaActor').click(function(event) {
        var data = getIncActoForm();
        if (validarIncActo(data)) {
            if (tmpIncActo == null) {
                data.regActIncidencia = oActor.lstIncidencias.length + 1;
                data.published = 1;

                var oIncActo = new cIncidenciaActor();
                oIncActo.setData(data);
                addIncActo(oIncActo);
                clCmpIncActo();
                oActor.lstIncidencias.push(oIncActo);
            }
            else {
                data.regActIncidencia = regActIncidencia;
                actIncActo(data);
                regActIncidencia = -1;
                tmpIncActo = null;
            }
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    
    // EDICION de una INCIDENCIA
    jQuery('.updIncidenaciaActor').live("click", function() {
        if (tmpIncActo != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoIncActoUpdate(newRegObjt, regActIncidencia);
        } else {
            regActIncidencia = this.parentNode.parentNode.id;
            loadIncActoFronArray(regActIncidencia);
            tmpIncActo = getIncActoByReg(regActIncidencia);
        }
        // valida el forulario
        cleanValidateForn ( '#formIncidenciaAct' );
    });
    
    /// ELIMINAR una INCIDENCIA
    jQuery('.delIncidenaciaActor').live("click", function() {
        regActIncidenciaDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_REGISTRO, JSL_ECORAE, function(r) {
            if (r) {
                elmIncActo(regActIncidenciaDel);
                getIncActotByReg();
            } else {

            }
        });
    });
    
    // addObe
    jQuery("#addIncidenciaActor").click(function() {
        if ( regActIncidencia != -1 ) {
            clCmpIncActo();
        }
        jQuery('#imgeIncidenaciaActor').css("display", "none");
        jQuery('#formIncidenaciaActor').css("display", "block")
    });
    // cancelar
    jQuery("#cancelIncidenciaActor").click(function() {
        jQuery('#cancelInc').trigger("click");
        clCmpIncActo();
    });
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del incidencia que se va a editar
 * @param {int} anterior    Registro del incidencia que se esta editando
 * @returns {int}                  Registro del incidencia que se va a editar
 */
function autoIncActoUpdate(nuevo, anterior) {
    var data = getIncActoForm();
    if (
            data.idIncidencia != tmpIncActo.idIncidencia ||
            data.nmbIncidencia != tmpIncActo.nmbIncidencia ||
            data.fecha != tmpIncActo.fecha
            ) {
        jConfirm(JSL_COM_CONFLICTOS_INCFUENTE_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regActIncidencia = anterior;
                actIncActo(data);
                loadIncActoFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadIncActoFronArray(nuevo);
        if (anterior == nuevo) {
            loadIncActoFronArray(nuevo);
        }
    }
    regActIncidencia = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarIncActo(data) {
    var flag = false;
    if (
            data.idIncidencia != 0 &&
            data.nmbIncidencia != "" &&
            data.fecha != ""
            ) {
        flag = true;
    }
    return flag;
}

/**
 *  Carga la lista de incidencias en la tabla
 * @returns {undefined}
 */
function reloadIncidenciasActTb(){
    jQuery("#tbLstIncidencasFuete > tbody").empty();
    for (var i = 0; i < oActor.lstIncidencias.length; i++) {
        if ( oActor.lstIncidencias[i].published == 1) {
            addIncActo(oActor.lstIncidencias[i]);
        }
    }
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addIncActo(data) {
    var regActIncidencia = data.regActIncidencia;
    var nmbIncidencia = (data.nmbIncidencia) ? data.nmbIncidencia : "-----";
    var fecha = (data.fecha) ? data.fecha : "-----";
    var fila = '';
    fila += '<tr id="' + regActIncidencia + '">';
    fila += '    <td>' + nmbIncidencia + ' </td>';
    fila += '    <td>' + fecha + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updIncidenaciaActor"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delIncidenaciaActor"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstIncidencasFuete > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpIncActo() {
    jQuery('#formIncidenaciaActor').css("display", "none");
    jQuery('#imgeIncidenaciaActor').css("display", "block");
    
    regActIncidencia = -1;
    tmpIncActo = null;
    
    cleanValidateForn ( '#formIncidenciaAct' );
    
    recorrerCombo(jQuery("#jform_intId_inc option"), 0);
    jQuery('#jform_intId_inc').trigger( 'change', 0 );
    jQuery("#jform_dtefecha_fi").val("");
}

/**
 *  Elimina los incidencias.
 * @param {type} regActIncidenciaDel
 * @returns {undefined}
 */
function elmIncActo(regActIncidenciaDel) {
    for (var j = 0; j < oActor.lstIncidencias.length; j++) {
        if (oActor.lstIncidencias[j].regActIncidencia == regActIncidenciaDel) {
            oActor.lstIncidencias[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del incidencia
 * @param {object} data
 * @returns {undefined}
 */
function actIncActo(data) {
    for (var j = 0; j < oActor.lstIncidencias.length; j++) {
        if (oActor.lstIncidencias[j].regActIncidencia == data.regActIncidencia) {
            oActor.lstIncidencias[j].idIncidencia = data.idIncidencia;
            oActor.lstIncidencias[j].nmbIncidencia = data.nmbIncidencia;
            oActor.lstIncidencias[j].fecha = data.fecha;
        }
    }
    clCmpIncActo();
    getIncActotByReg();
}

/**
 *  Borra y buelce a escribir en la tabla incidencias
 * @returns {undefined}
 */
function getIncActotByReg() {
    jQuery("#tbLstIncidencasFuete > tbody").empty();
    for (var j = 0; j < oActor.lstIncidencias.length; j++) {
        if (oActor.lstIncidencias[j].published == 1)
            addIncActo(oActor.lstIncidencias[j]);
    }
}

/**
 *  Recupera el incidencia dado el registro del incidencia
 * @param {int} regActIncidencia
 * @returns {unresolved}
 */
function getIncActoByReg(regActIncidencia) {
    var data = null;
    for (var j = 0; j < oActor.lstIncidencias.length; j++) {
        if (oActor.lstIncidencias[j].regActIncidencia == regActIncidencia) {
            data = oActor.lstIncidencias[j];
        }
    }
    return data;
}

/**
 * 
 * @returns {getIncActoForm.data}
 */
function getIncActoForm() {
    var data = {
        idIncidencia: jQuery("#jform_intId_inc").val(),
        nmbIncidencia: jQuery("#jform_intId_inc option:selected").text(),
        fecha: jQuery("#jform_dtefecha_fi").val()
    };
    return data;
}

/**
 * Carga los datos del incidencia recuperando los datos desde el array.
 * @param {int} regActIncidencia     Identificador del incidencia
 * @returns {undefined}
 */
function loadIncActoFronArray(regActIncidencia) {
    jQuery('#imgeIncidenaciaActor').css("display", "none");
    jQuery('#formIncidenaciaActor').css("display", "block");

    var data = getIncActoByReg(regActIncidencia);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intId_inc option"), data.idIncidencia);
        jQuery("#jform_dtefecha_fi").val(data.fecha);
    }
    tmpIncActo = getIncActoByReg(regActIncidencia);
}



