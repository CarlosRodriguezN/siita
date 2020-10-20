cIncidenciaFuente = function() {

    this.idFuIncidencia = 0;
    this.idIndidencia = "";
    this.nmbIncidencia = 0;
    this.fecha = 0;
    this.published = 1;
    this.regFuIncidencia = -1;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.idFuIncidencia = data.idFuIncidencia;
        this.idIndidencia = data.idIndidencia;
        this.nmbIncidencia = data.nmbIncidencia;
        this.fecha = data.fecha;
        this.published = data.published;
        this.regFuIncidencia = data.regFuIncidencia;
    };

};

var regFuIncidencia = -1;
var tmpIncFuen = null;

jQuery(document).ready(function() {
    
    if ( oFuente.lstIncidencias.length > 0 ) {
        reloadIncidenciasFntTb();
    }
    

    jQuery('#saveIncidenciaFuente').click(function(event) {
        var data = getIncFuenForm();
        if (validarIncFuen(data)) {
            if (tmpIncFuen == null) {
                data.regFuIncidencia = oFuente.lstIncidencias.length + 1;
                data.published = 1;

                var oIncFuen = new cIncidenciaFuente();
                oIncFuen.setData(data);
                addIncFuen(oIncFuen);
                clCmpIncFuen();
                oFuente.lstIncidencias.push(oIncFuen);
            }
            else {
                data.regFuIncidencia = regFuIncidencia;
                actIncFuen(data);
            }
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    /**
     *  EDICION de una INCIDENCIA
     */
    jQuery('.updIncidenaciaFuente').live("click", function() {
        if (tmpIncFuen != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoIncFuenUpdate(newRegObjt, regFuIncidencia);
        } else {
            regFuIncidencia = this.parentNode.parentNode.id;
            loadIncFuenFronArray(regFuIncidencia);
            tmpIncFuen = getIncFuenByReg(regFuIncidencia);
        }
        cleanValidateForn ( '#formIncidenciaFnt' )
    });
    
    /**
     *  ELIMINAR una INCIDENCIA
     */
    jQuery('.delIncidenaciaFuente').live("click", function() {
        regFuIncidenciaDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_FUENTE_REG, JSL_ECORAE, function(r) {
            if (r) {
                elmIncFuen(regFuIncidenciaDel);
                getIncFuentByReg();
            } else {

            }
        });
    });
    
    // addObe
    jQuery("#addIncidenciaFuente").click(function() {
        if ( regFuIncidencia != -1 ){
            clCmpIncFuen();
        }
        jQuery('#imgeIncidenaciaFuente').css("display", "none");
        jQuery('#formIncidenaciaFuente').css("display", "block")
    });
    
    // cancelar
    jQuery("#cancelIncidenciaFuente").click(function() {
        jQuery('#cancelInc').trigger("click");
        clCmpIncFuen();
    });
    
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del incidencia que se va a editar
 * @param {int} anterior    Registro del incidencia que se esta editando
 * @returns {int}                  Registro del incidencia que se va a editar
 */
function autoIncFuenUpdate(nuevo, anterior) {
    var data = getIncFuenForm();
    if (
            data.idIndidencia != tmpIncFuen.idIndidencia ||
            data.nmbIncidencia != tmpIncFuen.nmbIncidencia ||
            data.fecha != tmpIncFuen.fecha
            ) {
        jConfirm(JSL_COM_CONFLICTOS_INCFUENTE_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regFuIncidencia = anterior;
                actIncFuen(data);
                loadIncFuenFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadIncFuenFronArray(nuevo);
        if (anterior == nuevo) {
            loadIncFuenFronArray(nuevo);
        }
    }
    regFuIncidencia = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarIncFuen(data) {
    var flag = false;
    if (
            data.idIndidencia != 0 &&
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
function reloadIncidenciasFntTb(){
    jQuery("#tbLstIncidencasFuete > tbody").empty();
    for (var i = 0; i < oFuente.lstIncidencias.length; i++) {
        if ( oFuente.lstIncidencias[i].published == 1) {
            addIncFuen( oFuente.lstIncidencias[i] );
        }
    }
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addIncFuen(data) {
    var regFuIncidencia = data.regFuIncidencia;
    var nmbIncidencia = (data.nmbIncidencia) ? data.nmbIncidencia : "-----";
    var fecha = (data.fecha) ? data.fecha : "-----";
    var fila = '';
    fila += '<tr id="' + regFuIncidencia + '">';
    fila += '    <td>' + nmbIncidencia + ' </td>';
    fila += '    <td>' + fecha + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updIncidenaciaFuente"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delIncidenaciaFuente"> ' + JSL_DEL_LABEL + '</a>';
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
function clCmpIncFuen() {
    jQuery('#formIncidenaciaFuente').css("display", "none");
    jQuery('#imgeIncidenaciaFuente').css("display", "block");

    regFuIncidencia = -1;
    tmpIncFuen = null;
    
    cleanValidateForn ( '#formIncidenciaFnt' );
    
    recorrerCombo(jQuery("#jform_intId_inc option"), 0);
    jQuery('#jform_intId_inc').trigger( 'change' );
    jQuery("#jform_dtefecha_fi").val("");
}

/**
 *  Elimina los incidencias.
 * @param {type} regFuIncidenciaDel
 * @returns {undefined}
 */
function elmIncFuen(regFuIncidenciaDel) {
    for (var j = 0; j < oFuente.lstIncidencias.length; j++) {
        if (oFuente.lstIncidencias[j].regFuIncidencia == regFuIncidenciaDel) {
            oFuente.lstIncidencias[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del incidencia
 * @param {object} data
 * @returns {undefined}
 */
function actIncFuen(data) {
    for (var j = 0; j < oFuente.lstIncidencias.length; j++) {
        if (oFuente.lstIncidencias[j].regFuIncidencia == data.regFuIncidencia) {
            oFuente.lstIncidencias[j].idIndidencia = data.idIndidencia;
            oFuente.lstIncidencias[j].nmbIncidencia = data.nmbIncidencia;
            oFuente.lstIncidencias[j].fecha = data.fecha;
        }
    }
    clCmpIncFuen();
    getIncFuentByReg();
}

/**
 *  Borra y buelce a escribir en la tabla incidencias
 * @returns {undefined}
 */
function getIncFuentByReg() {
    jQuery("#tbLstIncidencasFuete > tbody").empty();
    for (var j = 0; j < oFuente.lstIncidencias.length; j++) {
        if (oFuente.lstIncidencias[j].published == 1)
            addIncFuen(oFuente.lstIncidencias[j]);
    }
}

/**
 *  Recupera el incidencia dado el registro del incidencia
 * @param {int} regFuIncidencia
 * @returns {unresolved}
 */
function getIncFuenByReg(regFuIncidencia) {
    var data = null;
    for (var j = 0; j < oFuente.lstIncidencias.length; j++) {
        if (oFuente.lstIncidencias[j].regFuIncidencia == regFuIncidencia) {
            data = oFuente.lstIncidencias[j];
        }
    }
    return data;
}

/**
 * 
 * @returns {getIncFuenForm.data}
 */
function getIncFuenForm() {
    var data = {
        idIndidencia: jQuery("#jform_intId_inc").val(),
        nmbIncidencia: jQuery("#jform_intId_inc option:selected").text(),
        fecha: jQuery("#jform_dtefecha_fi").val()
    };
    return data;
}

/**
 * Carga los datos del incidencia recuperando los datos desde el array.
 * @param {int} regFuIncidencia     Identificador del incidencia
 * @returns {undefined}
 */
function loadIncFuenFronArray(regFuIncidencia) {
    jQuery('#imgeIncidenaciaFuente').css("display", "none");
    jQuery('#formIncidenaciaFuente').css("display", "block");

    var data = getIncFuenByReg(regFuIncidencia);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intId_inc option"), data.idIndidencia);
        jQuery("#jform_dtefecha_fi").val(data.fecha);
    }
    tmpIncFuen = getIncFuenByReg(regFuIncidencia);
}



