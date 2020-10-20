cLegitimidadActor = function() {

    this.idActLegi = 0;
    this.idLegitimidad = "";
    this.nmbLegitimidad = 0;
    this.descripcion = 0;
    this.published = 1;
    this.regLegitimidad = 0;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.idActLegi = (data.idActLegi) ? data.idActLegi : 0;
        this.idLegitimidad = (data.idLegitimidad) ? data.idLegitimidad : 0;
        this.nmbLegitimidad = (data.nmbLegitimidad) ? data.nmbLegitimidad : "";
        this.descripcion = (data.descripcion) ? data.descripcion : "";
        this.published = (data.published) ? data.published : 1;
        this.regLegitimidad = (data.regLegitimidad) ? data.regLegitimidad : 0;
    };

};

var regLegitimidad = -1;
var tmpLegActo = null;

jQuery(document).ready(function() {

    if ( oActor.lstLegitimidad.length > 0 ) {
        reloadLegitimidadesActTb();
    }

    jQuery('#saveLegitimidadActor').click(function(event) {
        var data = getLegActoForm();
        if (validarLegActo(data)) {
            if (tmpLegActo == null) {
                data.regLegitimidad = oActor.lstLegitimidad.length + 1;
                data.idActLegi = 0;
                data.published = 1;

                var oIncActo = new cLegitimidadActor();
                oIncActo.setData(data);
                addLegActo(oIncActo);
                clCmpLegActo();
                oActor.lstLegitimidad.push(oIncActo);
            }
            else {
                data.regLegitimidad = regLegitimidad;
                actLegActo(data);
            }
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    
    // EDICION de una INCIDENCIA
    jQuery('.updLegitimidadActor').live("click", function() {
        if (tmpLegActo != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoLegActoUpdate(newRegObjt, regLegitimidad);
        } else {
            regLegitimidad = this.parentNode.parentNode.id;
            loadLegActoFronArray(regLegitimidad);
            tmpLegActo = getLegActoByReg(regLegitimidad);
        }
        cleanValidateForn ( '#formLegitimidadAct' );
    });
    
    /// ELIMINAR una INCIDENCIA
    jQuery('.delLegitimidadActor').live("click", function() {
        regLegitimidadDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_REGISTRO, JSL_ECORAE, function(r) {
            if (r) {
                elmLegActo(regLegitimidadDel);
                getLegActotByReg();
            } else {

            }
        });
    });
    
    // addObe
    jQuery("#addLegitimidadActor").click(function() {
        if (regLegitimidad != -1) {
            clCmpLegActo();
        }
        jQuery('#imgeLegitimidadActor').css("display", "none");
        jQuery('#formLegitimidadActor').css("display", "block")
    });
    
    // cancelar
    jQuery("#cancelLegitimidadActor").click(function() {
        jQuery('#cancelLeg').trigger("click");
        clCmpLegActo();
    });
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del incidencia que se va a editar
 * @param {int} anterior    Registro del incidencia que se esta editando
 * @returns {int}                  Registro del incidencia que se va a editar
 */
function autoLegActoUpdate(nuevo, anterior) {
    var data = getLegActoForm();
    if (
            data.idLegitimidad != tmpLegActo.idLegitimidad ||
            data.nmbLegitimidad != tmpLegActo.nmbLegitimidad ||
            data.descripcion != tmpLegActo.descripcion 
            ) {
        jConfirm(JSL_COM_CONFLICTOS_LEGFUENTE_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regLegitimidad = anterior;
                actLegActo(data);
                loadLegActoFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadLegActoFronArray(nuevo);
        if (anterior == nuevo) {
            loadLegActoFronArray(nuevo);
        }
    }
    regLegitimidad = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarLegActo(data) {
    var flag = false;
    if (
            data.idLegitimidad != 0 &&
            data.nmbLegitimidad != "" &&
            data.descripcion != "" 
            ) {
        flag = true;
    }
    return flag;
}

/**
 *  Carga la lista de incidencias en la tabla
 * @returns {undefined}
 */
function reloadLegitimidadesActTb(){
    jQuery("#tbLstlegitimidadesActor > tbody").empty();
    for (var i = 0; i < oActor.lstLegitimidad.length; i++) {
        if ( oActor.lstLegitimidad[i].published == 1) {
            addLegActo(oActor.lstLegitimidad[i]);
        }
    }
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addLegActo(data) {
    var regLegitimidad = data.regLegitimidad;
    var nmbLegitimidad = (data.nmbLegitimidad) ? data.nmbLegitimidad : "-----";
    var descripcion = (data.descripcion) ? data.descripcion : "-----";
    var fila = '';
    fila += '<tr id="' + regLegitimidad + '">';
    fila += '    <td>' + nmbLegitimidad + ' </td>';
    fila += '    <td>' + descripcion + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updLegitimidadActor"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delLegitimidadActor"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstlegitimidadesActor > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpLegActo() {
    jQuery('#formLegitimidadActor').css("display", "none");
    jQuery('#imgeLegitimidadActor').css("display", "block");

    regLegitimidad = -1;
    tmpLegActo = null;
    
    cleanValidateForn ( '#formLegitimidadAct' );
    
    recorrerCombo(jQuery("#jform_intId_leg option"), 0);
    jQuery('#jform_intId_leg').trigger( 'change', 0 );
    jQuery("#jform_dteFecha_fl").val("");
    jQuery("#jform_strDescripcion_fl").val("");
}

/**
 *  Elimina los incidencias.
 * @param {type} regLegitimidadDel
 * @returns {undefined}
 */
function elmLegActo(regLegitimidadDel) {
    for (var j = 0; j < oActor.lstLegitimidad.length; j++) {
        if (oActor.lstLegitimidad[j].regLegitimidad == regLegitimidadDel) {
            oActor.lstLegitimidad[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del incidencia
 * @param {object} data
 * @returns {undefined}
 */
function actLegActo(data) {
    for (var j = 0; j < oActor.lstLegitimidad.length; j++) {
        if (oActor.lstLegitimidad[j].regLegitimidad == data.regLegitimidad) {
            oActor.lstLegitimidad[j].idLegitimidad = data.idLegitimidad;
            oActor.lstLegitimidad[j].nmbLegitimidad = data.nmbLegitimidad;
            oActor.lstLegitimidad[j].descripcion = data.descripcion;
        }
    }
    clCmpLegActo();
    getLegActotByReg();
}

/**
 *  Borra y buelce a escribir en la tabla incidencias
 * @returns {undefined}
 */
function getLegActotByReg() {
    jQuery("#tbLstlegitimidadesActor > tbody").empty();
    for (var j = 0; j < oActor.lstLegitimidad.length; j++) {
        if (oActor.lstLegitimidad[j].published == 1)
            addLegActo(oActor.lstLegitimidad[j]);
    }
}

/**
 *  Recupera el incidencia dado el registro del incidencia
 * @param {int} regLegitimidad
 * @returns {unresolved}
 */
function getLegActoByReg(regLegitimidad) {
    var data = null;
    for (var j = 0; j < oActor.lstLegitimidad.length; j++) {
        if (oActor.lstLegitimidad[j].regLegitimidad == regLegitimidad) {
            data = oActor.lstLegitimidad[j];
        }
    }
    return data;
}

/**
 * 
 * @returns {getLegActoForm.data}
 */
function getLegActoForm() {
    var data = {
        idLegitimidad: jQuery("#jform_intId_leg").val(),
        nmbLegitimidad: jQuery("#jform_intId_leg option:selected").text(),
        descripcion: jQuery("#jform_strDescripcion_fl").val()
    };
    return data;
}

/**
 * Carga los datos del incidencia recuperando los datos desde el array.
 * @param {int} regLegitimidad     Identificador del incidencia
 * @returns {undefined}
 */
function loadLegActoFronArray(regLegitimidad) {
    jQuery('#imgeLegitimidadActor').css("display", "none");
    jQuery('#formLegitimidadActor').css("display", "block");

    var data = getLegActoByReg(regLegitimidad);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_intId_leg option"), data.idLegitimidad);
        jQuery("#jform_strDescripcion_fl").val(data.descripcion);
    }
    tmpLegActo = getLegActoByReg(regLegitimidad);
}



