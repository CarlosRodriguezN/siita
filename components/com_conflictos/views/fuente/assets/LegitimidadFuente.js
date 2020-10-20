cLegitimidadFuente = function() {

    this.idFutLegi = 0;
    this.idLegitimidad = "";
    this.nmbLegitimidad = 0;
    this.descripcion = 0;
    this.fecha = 0;
    this.published = 1;
    this.regLegitimidad = -1;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.idFutLegi = data.idFutLegi;
        this.idLegitimidad = data.idLegitimidad;
        this.nmbLegitimidad = data.nmbLegitimidad;
        this.descripcion = data.descripcion;
        this.fecha = data.fecha;
        this.published = data.published;
        this.regLegitimidad = data.regLegitimidad;
    };

};

var regLegitimidad = -1;
var tmpLegFuen = null;

jQuery(document).ready(function() {
    
    if ( oFuente.lstLegitimidad.length > 0 ) {
        reloadLegitimidadFntTb();
    }

    jQuery('#saveLegitimidadFuente').click(function(event) {
        var data = getLegFuenForm();
        if (validarLegFuen(data)) {
            if (tmpLegFuen == null) {
                data.regLegitimidad = oFuente.lstLegitimidad.length + 1;
                data.idFutLegi = 0;
                data.published = 1;

                var oIncFuen = new cLegitimidadFuente();
                oIncFuen.setData(data);
                addLegFuen(oIncFuen);
                clCmpLegFuen();
                oFuente.lstLegitimidad.push(oIncFuen);
            }
            else {
                data.regLegitimidad = regLegitimidad;
                actLegFuen(data);
            }
            
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    // EDICION de una INCIDENCIA
    jQuery('.updLegitimidadFuente').live("click", function() {
        if (tmpLegFuen != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoLegFuenUpdate(newRegObjt, regLegitimidad);
        } else {
            regLegitimidad = this.parentNode.parentNode.id;
            loadLegFuenFronArray(regLegitimidad);
            tmpLegFuen = getLegFuenByReg(regLegitimidad);
        }
        cleanValidateForn ( '#formLegitimidadFnt' );
    });
    /// ELIMINAR una INCIDENCIA
    jQuery('.delLegitimidadFuente').live("click", function() {
        regLegitimidadDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_FUENTE_REG, JSL_ECORAE, function(r) {
            if (r) {
                elmLegFuen(regLegitimidadDel);
                getLegFuentByReg();
            } else {

            }
        });
    });
    
    // addObe
    jQuery("#addLegitimidadFuente").click(function() {
        if ( regLegitimidad != -1 ) {
            clCmpLegFuen();
        }
        
        jQuery('#imgeLegitimidadFuente').css("display", "none");
        jQuery('#formLegitimidadFuente').css("display", "block")
    });
    
    // cancelar
    jQuery("#cancelLegitimidadFuente").click(function() {
        jQuery('#cancelLeg').trigger("click");
        clCmpLegFuen();
    });
    
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del incidencia que se va a editar
 * @param {int} anterior    Registro del incidencia que se esta editando
 * @returns {int}                  Registro del incidencia que se va a editar
 */
function autoLegFuenUpdate(nuevo, anterior) {
    var data = getLegFuenForm();
    if (
            data.idLegitimidad != tmpLegFuen.idLegitimidad ||
            data.nmbLegitimidad != tmpLegFuen.nmbLegitimidad ||
            data.descripcion != tmpLegFuen.descripcion ||
            data.fecha != tmpLegFuen.fecha
            ) {
        jConfirm(JSL_COM_CONFLICTOS_LEGFUENTE_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regLegitimidad = anterior;
                actLegFuen(data);
                loadLegFuenFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadLegFuenFronArray(nuevo);
        if (anterior == nuevo) {
            loadLegFuenFronArray(nuevo);
        }
    }
    regLegitimidad = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarLegFuen(data) {
    var flag = false;
    if (
            data.idLegitimidad != 0 &&
            data.nmbLegitimidad != "" &&
            data.descripcion != "" &&
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
function reloadLegitimidadFntTb(){
    jQuery("#tbLstlegitimidadesFuente > tbody").empty();
    for (var i = 0; i < oFuente.lstLegitimidad.length; i++) {
        if ( oFuente.lstLegitimidad[i].published == 1) {
            addLegFuen( oFuente.lstLegitimidad[i] );
        }
    }
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addLegFuen(data) {
    var regLegitimidad = data.regLegitimidad;
    var nmbLegitimidad = (data.nmbLegitimidad) ? data.nmbLegitimidad : "-----";
    var descripcion = (data.descripcion) ? data.descripcion : "-----";
    var fecha = (data.fecha) ? data.fecha : "-----";
    var fila = '';
    fila += '<tr id="' + regLegitimidad + '">';
    fila += '    <td>' + nmbLegitimidad + ' </td>';
    fila += '    <td>' + descripcion + ' </td>';
    fila += '    <td>' + fecha + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updLegitimidadFuente"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delLegitimidadFuente"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstlegitimidadesFuente > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpLegFuen() {
    jQuery('#formLegitimidadFuente').css("display", "none");
    jQuery('#imgeLegitimidadFuente').css("display", "block");
    regLegitimidad = -1;
    tmpLegFuen = null;
    
    cleanValidateForn ( '#formLegitimidadFnt' );
    
    recorrerCombo(jQuery("#jform_intId_leg option"), 0);
    jQuery("#jform_dteFecha_fl").val("");
    jQuery("#jform_strDescripcion_fl").val("");
}

/**
 *  Elimina los incidencias.
 * @param {type} regLegitimidadDel
 * @returns {undefined}
 */
function elmLegFuen(regLegitimidadDel) {
    for (var j = 0; j < oFuente.lstLegitimidad.length; j++) {
        if (oFuente.lstLegitimidad[j].regLegitimidad == regLegitimidadDel) {
            oFuente.lstLegitimidad[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del incidencia
 * @param {object} data
 * @returns {undefined}
 */
function actLegFuen(data) {
    for (var j = 0; j < oFuente.lstLegitimidad.length; j++) {
        if (oFuente.lstLegitimidad[j].regLegitimidad == data.regLegitimidad) {
            oFuente.lstLegitimidad[j].idLegitimidad = data.idLegitimidad;
            oFuente.lstLegitimidad[j].nmbLegitimidad = data.nmbLegitimidad;
            oFuente.lstLegitimidad[j].descripcion = data.descripcion;
            oFuente.lstLegitimidad[j].fecha = data.fecha;
        }
    }
    clCmpLegFuen();
    getLegFuentByReg();
}

/**
 *  Borra y buelce a escribir en la tabla incidencias
 * @returns {undefined}
 */
function getLegFuentByReg() {
    jQuery("#tbLstlegitimidadesFuente > tbody").empty();
    for (var j = 0; j < oFuente.lstLegitimidad.length; j++) {
        if (oFuente.lstLegitimidad[j].published == 1)
            addLegFuen(oFuente.lstLegitimidad[j]);
    }
}

/**
 *  Recupera el incidencia dado el registro del incidencia
 * @param {int} regLegitimidad
 * @returns {unresolved}
 */
function getLegFuenByReg(regLegitimidad) {
    var data = null;
    for (var j = 0; j < oFuente.lstLegitimidad.length; j++) {
        if (oFuente.lstLegitimidad[j].regLegitimidad == regLegitimidad) {
            data = oFuente.lstLegitimidad[j];
        }
    }
    return data;
}

/**
 * 
 * @returns {getLegFuenForm.data}
 */
function getLegFuenForm() {
    var data = {
        idLegitimidad: jQuery("#jform_intId_leg").val(),
        nmbLegitimidad: jQuery("#jform_intId_leg option:selected").text(),
        descripcion: jQuery("#jform_strDescripcion_fl").val(),
        fecha: jQuery("#jform_dteFecha_fl").val()
    };
    return data;
}

/**
 * Carga los datos del incidencia recuperando los datos desde el array.
 * @param {int} regLegitimidad     Identificador del incidencia
 * @returns {undefined}
 */
function loadLegFuenFronArray(regLegitimidad) {
    jQuery('#imgeLegitimidadFuente').css("display", "none");
    jQuery('#formLegitimidadFuente').css("display", "block");

    var data = getLegFuenByReg(regLegitimidad);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intId_leg option"), data.idLegitimidad);
        jQuery("#jform_dteFecha_fl").val(data.fecha);
        jQuery("#jform_strDescripcion_fl").val(data.descripcion);
    }
    tmpLegFuen = getLegFuenByReg(regLegitimidad);
}



