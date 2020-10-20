EstadoTema = function() {
    this.fechaInicio = "";
    this.fechaFin = "";
    this.idEstado = 0;
    this.idTemaEstado = 0;
    this.nmbEstado = "";
    this.published = 1;
    this.regEstTema = 0;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.fechaInicio = data.fechaInicio;
        this.fechaFin = data.fechaFin;
        this.idEstado = data.idEstado;
        this.idTemaEstado = data.idTemaEstado;
        this.nmbEstado = data.nmbEstado;
        this.published = data.published;
        this.regEstTema = data.regEstTema;
    };

};

var regEstTema = 0;
var tmpEstTema = null;

jQuery(document).ready(function() {

    jQuery('#saveEstadoTema').click(function(event) {
        var data = getEstTemaForm();
        if (validarEstTema(data)) {
            if (tmpEstTema == null) {
                data.idTemaEstado = 0;
                data.regEstTema = oTema.lstEstados.length + 1;
                data.published = 1;

                var oEstTema = new EstadoTema();
                oEstTema.setData(data);
                addEstTema(oEstTema);
                oTema.lstEstados.push(oEstTema);
            }
            else {
                data.regEstTema = regEstTema;
                actEstTema(data);
            }
            clCmpEstaTema();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }
    });
    
    // edicion de un objetivo
    jQuery('.updEstTema').live("click", function() {
        //  Caraga la data para la actualizacion
        if (tmpEstTema != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoEstTemaUpdate(newRegObjt, regEstTema);
        } else {
            regEstTema = this.parentNode.parentNode.id;
            loadEstTemaFronArray(regEstTema);
            tmpEstTema = getEstTemaByReg(regEstTema);
        }
        //  Limpio los sms de las validaciones
        cleanValidateForn ( "#formTemaEstado" );
    });
    
    /// eliminar una multa
    jQuery('.delEstTema').live("click", function() {
        regEstTemaDel = this.parentNode.parentNode.id;
        jConfirm(JSL_CONFIRM_DEL_ESTTEMA, JSL_ECORAE, function(r) {
            if (r) {
                elmEstTema(regEstTemaDel);
                reloadEstTemaTable();
            } 
        });
    });
    
    // addObe
    jQuery("#addEstadosTema").click(function() {
        if ( tmpEstTema != null ) {
            clCmpEstaTema();
        }
        jQuery('#imgeEstados').css("display", "none");
        jQuery('#formEstados').css("display", "block");
    });
    
    // cancelar
    jQuery("#cancelEstadosTema").click(function() {
        jQuery('#cancelEst').trigger("click");
        clCmpEstaTema();
    });
});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del objetivo que se va a editar
 * @param {int} anterior    Registro del objetivo que se esta editando
 * @returns {int}                  Registro del objetivo que se va a editar
 */
function autoEstTemaUpdate(nuevo, anterior) {
    var data = getEstTemaForm();
    if (data.fechaInicio != tmpEstTema.fechaInicio ||
            data.fechaFin != tmpEstTema.fechaFin ||
            data.idEstado != tmpEstTema.idEstado ||
            data.nmbEstado != tmpEstTema.nmbEstado
            ) {
        jConfirm(JSL_COM_CONFLICTOS_ESTATEMA_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regEstTema = anterior;
                actEstTema(data);
                loadEstTemaFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadEstTemaFronArray(nuevo);
        if (anterior == nuevo) {
            loadEstTemaFronArray(nuevo);
        }
    }
    regEstTema = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarEstTema(data) {
    var flag = false;
    if (
            data.fechaInicio != "" &&
            data.fechaFin != "" &&
            data.idEstado != 0 &&
            data.nmbEstado != ""
            ) {
        flag = true;
    }
    return flag;
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addEstTema(data) {
    var regEstTema = data.regEstTema;
    var fechaInicio = (data.fechaInicio) ? data.fechaInicio : "-----";
    var fechaFin = (data.fechaFin) ? data.fechaFin : "-----";
    var nmbEstado = (data.nmbEstado) ? data.nmbEstado : "-----";
    var fila = '';
    fila += '<tr id="' + regEstTema + '">';
    fila += '    <td>' + nmbEstado + ' </td>';
    fila += '    <td>' + fechaInicio + ' </td>';
    fila += '    <td>' + fechaFin + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updEstTema"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delEstTema"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstEstados > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpEstaTema() {
    jQuery('#formEstados').css("display", "none");
    jQuery('#imgeEstados').css("display", "block");
    
    regEstTema = 0;
    tmpEstTema = null;
    
    cleanValidateForn ( "#formTemaEstado" );
    
    recorrerCombo(jQuery("#jform_estado_intId_ec option"), 0);
    jQuery('#jform_estado_intId_ec').trigger("change");
    jQuery("#jform_estado_dteFechaInicio_te").val('');
    jQuery("#jform_estado_dteFechaFin_te").val('');
}

/**
 *  Elimina los objetivos.
 * @param {type} regEstTemaDel
 * @returns {undefined}
 */
function elmEstTema(regEstTemaDel) {
    for (var j = 0; j < oTema.lstEstados.length; j++) {
        if (oTema.lstEstados[j].regEstTema == regEstTemaDel) {
            oTema.lstEstados[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del objetivo
 * @param {object} data
 * @returns {undefined}
 */
function actEstTema(data) {
    for (var j = 0; j < oTema.lstEstados.length; j++) {
        if (oTema.lstEstados[j].regEstTema == data.regEstTema) {
            oTema.lstEstados[j].fechaInicio = data.fechaInicio;
            oTema.lstEstados[j].fechaFin = data.fechaFin;
            oTema.lstEstados[j].idEstado = data.idEstado;
            oTema.lstEstados[j].nmbEstado = data.nmbEstado;
        }
    }
    clCmpEstaTema();
    reloadEstTemaTable();
}

/**
 *  Borra y buelce a escribir en la tabla objetivos
 * @returns {undefined}
 */
function reloadEstTemaTable() {
    jQuery("#tbLstEstados > tbody").empty();
    for (var j = 0; j < oTema.lstEstados.length; j++) {
        if (oTema.lstEstados[j].published == 1)
            addEstTema(oTema.lstEstados[j]);
    }
}

/**
 *  Recupera el objetivo dado el registro del objetivo
 * @param {int} regEstTema
 * @returns {unresolved}
 */
function getEstTemaByReg(regEstTema) {
    var data = null;
    for (var j = 0; j < oTema.lstEstados.length; j++) {
        if (oTema.lstEstados[j].regEstTema == regEstTema) {
            data = oTema.lstEstados[j];
        }
    }
    return data;
}

/**
 *  Recupera los datos del objetivo
 * @returns {getEstTemaForm.data}
 */
function getEstTemaForm() {
    var data = {
        fechaInicio: jQuery("#jform_estado_dteFechaInicio_te").val(),
        fechaFin: jQuery("#jform_estado_dteFechaFin_te").val(),
        idEstado: jQuery("#jform_estado_intId_ec").val(),
        nmbEstado: jQuery("#jform_estado_intId_ec option:selected").text()
    };
    return data;
}

/**
 * Carga los datos del objetivo recuperando los datos desde el array.
 * @param {int} regEstTema     Identificador del objetivo
 * @returns {undefined}
 */
function loadEstTemaFronArray(regEstTema) {
    jQuery('#imgeEstados').css("display", "none");
    jQuery('#formEstados').css("display", "block");

    var data = getEstTemaByReg(regEstTema);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_estado_intId_ec option"), data.idEstado);
        jQuery("#jform_estado_dteFechaFin_te").val(data.fechaFin);
        jQuery("#jform_estado_dteFechaInicio_te").val(data.fechaInicio);
    }
    tmpEstTema = getEstTemaByReg(regEstTema);
}

