FuenteTema = function() {



    this.fecha = "";
    this.idFuente = 0;
    this.idFuenteTema = 0;
    this.idTipoFuente = 0;
    this.nmbFuente = "";
    this.nmbTipoFuente = "";
    this.observacion = 0;
    this.published = 1;
    this.regFueTema = 0;


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.fecha = data.fecha;
        this.idFuente = data.idFuente;
        this.idFuenteTema = data.idFuenteTema;
        this.idTipoFuente = data.idTipoFuente;
        this.nmbFuente = data.nmbFuente;
        this.nmbTipoFuente = data.nmbTipoFuente;
        this.observacion = data.observacion;
        this.published = data.published;
        this.regFueTema = data.regFueTema;
    };

};

var regFueTema = 0;
var tmpFuenteTema = null;

jQuery(document).ready(function() {

    jQuery('#saveFuenteTema').click(function(event) {
        var data = getFueTemaForm();
        if (validarFueTema(data)) {
            if (tmpFuenteTema == null) {
                data.idFuenteTema = 0;
                data.regFueTema = oTema.lstFuentes.length + 1;
                data.published = 1;

                var oFuenteTema = new FuenteTema();
                oFuenteTema.setData(data);
                addFueTema(oFuenteTema);
                clCmpFuenTema();
                oTema.lstFuentes.push(oFuenteTema);
            }
            else {
                data.regFueTema = regFueTema;
                //  Actuliza la informacion en el objeto tema y limpia los formularios
                actFuenTema(data);
                regFueTema = 0;
                tmpFuenteTema = null;
            }
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });
    // edicion de un objetivo
    jQuery('.updFuente').live("click", function() {
        if (tmpFuenteTema != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoFueTemaUpdate(newRegObjt, regFueTema);
        } else {
            regFueTema = this.parentNode.parentNode.id;
            loadFueTemaFronArray(regFueTema);
            tmpFuenteTema = getFueTemaByReg(regFueTema);
        }

    });
    /// eliminar una multa
    jQuery('.delFuente').live("click", function() {
        regFueTemaDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_FUENTE, JSL_ECORAE, function(r) {
            if (r) {
                elmFntTema(regFueTemaDel);
                reloadFueTemaTable();
            } else {

            }
        });
    });
    // addObe
    jQuery("#addFuenteTema").click(function() {
        if ( tmpFuenteTema != null ){
            clCmpFuenTema();
        }
        jQuery('#imgeFuentes').css("display", "none");
        jQuery('#formFuentes').css("display", "block");
    });
    
    // cancelar
    jQuery("#cancelFuenteTema").click(function() {
        clCmpFuenTema();
    });

});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del objetivo que se va a editar
 * @param {int} anterior    Registro del objetivo que se esta editando
 * @returns {int}                  Registro del objetivo que se va a editar
 */
function autoFueTemaUpdate(nuevo, anterior) {
    var data = getFueTemaForm();
    if (data.fecha != tmpFuenteTema.fecha ||
            data.observacion != tmpFuenteTema.observacion
            ) {
        jConfirm(JSL_COM_CONFLICTOS_ESTATEMA_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regFueTema = anterior;
                actFuenTema(data);
                loadFueTemaFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadFueTemaFronArray(nuevo);
        if (anterior == nuevo) {
            loadFueTemaFronArray(nuevo);
        }
    }
    regFueTema = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarFueTema(data) {
    var flag = false;
    if ( data.fecha != "" &&
            data.idFuente != 0 &&
            data.idTipoFuente != 0 &&
            data.observacion != "" ) {
        flag = true;
    }
    return flag;
}

/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addFueTema(data) {

    var regFueTema = data.regFueTema;
    var nmbFuente = (data.nmbFuente) ? data.nmbFuente : "-----";
    var observacion = (data.observacion) ? data.observacion : "-----";
    var nmbTipoFuente = (data.nmbTipoFuente) ? data.nmbTipoFuente : "-----";
    var fecha = (data.fecha) ? data.fecha : "-----";

    var fila = '';
    fila += '<tr id="' + regFueTema + '">';
    fila += '    <td>' + nmbFuente + ' </td>';
    fila += '    <td>' + observacion + ' </td>';
    fila += '    <td>' + nmbTipoFuente + ' </td>';
    fila += '    <td>' + fecha + ' </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="updFuente"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15">';
    fila += '        <a href="#" class="delFuente"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstFuentes > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpFuenTema() {
    
    jQuery('#formFuentes').css("display", "none");
    jQuery('#imgeFuentes').css("display", "block");
    
    regFueTema = 0;
    tmpFuenteTema = null;
    
    cleanValidateForn ( "#frmTemaFuente"  );
    
    recorrerCombo(jQuery("#jform_fuente_intId_tf option"), 0);
    jQuery("#jform_fuente_intId_tf").trigger("change", 0);
    jQuery("#jform_fuente_dteFecha_tf").val("");
    jQuery("#jform_strObservacion_tf").val("");
}

/**
 *  Elimina los objetivos.
 * @param {type} regFueTemaDel
 * @returns {undefined}
 */
function elmFntTema(regFueTemaDel) {
    for (var j = 0; j < oTema.lstFuentes.length; j++) {
        if (oTema.lstFuentes[j].regFueTema == regFueTemaDel) {
            oTema.lstFuentes[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del objetivo
 * @param {object} data
 * @returns {undefined}
 */
function actFuenTema(data) {
    for (var j = 0; j < oTema.lstFuentes.length; j++) {
        if (oTema.lstFuentes[j].regFueTema == data.regFueTema) {
            oTema.lstFuentes[j].fecha = data.fecha;
            oTema.lstFuentes[j].idFuente = data.idFuente;
            oTema.lstFuentes[j].idTipoFuente = data.idTipoFuente;
            oTema.lstFuentes[j].nmbFuente = data.nmbFuente;
            oTema.lstFuentes[j].nmbTipoFuente = data.nmbTipoFuente;
            oTema.lstFuentes[j].observacion = data.observacion;
        }
    }
    clCmpFuenTema();
    reloadFueTemaTable();
}

/**
 *  Borra y buelce a escribir en la tabla objetivos
 * @returns {undefined}
 */
function reloadFueTemaTable() {
    jQuery("#tbLstFuentes > tbody").empty();
    for (var j = 0; j < oTema.lstFuentes.length; j++) {
        if (oTema.lstFuentes[j].published == 1)
            addFueTema(oTema.lstFuentes[j]);
    }
}

/**
 *  Recupera el objetivo dado el registro del objetivo
 * @param {int} regFueTema
 * @returns {unresolved}
 */
function getFueTemaByReg(regFueTema) {
    var data = null;
    for (var j = 0; j < oTema.lstFuentes.length; j++) {
        if (oTema.lstFuentes[j].regFueTema == regFueTema) {
            data = oTema.lstFuentes[j];
        }
    }
    return data;
}

/**
 *  Recupera los datos del objetivo
 * @returns {getFueTemaForm.data}
 */
function getFueTemaForm() {
    var data = {
        fecha: jQuery("#jform_fuente_dteFecha_tf").val(),
        idFuente: jQuery("#jform_fuente_intId_fte").val(),
        idTipoFuente: jQuery("#jform_fuente_intId_tf").val(),
        nmbFuente: jQuery("#jform_fuente_intId_fte option:selected").text(),
        nmbTipoFuente: jQuery("#jform_fuente_intId_tf option:selected").text(),
        observacion: jQuery("#jform_strObservacion_tf").val()
    };
    return data;
}

/**
 * Carga los datos del objetivo recuperando los datos desde el array.
 * @param {int} regFueTema     Identificador del objetivo
 * @returns {undefined}
 */
function loadFueTemaFronArray(regFueTema) {
    jQuery('#imgeFuentes').css("display", "none");
    jQuery('#formFuentes').css("display", "block");

    var data = getFueTemaByReg(regFueTema);
    //  muestro en el formulario
    if (data) {
        jQuery("#jform_fuente_dteFecha_tf").val(data.fecha);
        recorrerCombo(jQuery("#jform_fuente_intId_tf option"), data.idTipoFuente);
        jQuery("#jform_fuente_intId_tf option").trigger('change', data.idFuente);
        jQuery("#jform_strObservacion_tf").val(data.observacion);
    }
    tmpFuenteTema = getFueTemaByReg(regFueTema);
}

