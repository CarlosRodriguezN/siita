ActorDetalle = function() {
    this.descripcion = "";
    this.fecha = "";
    this.idActor = 0;
    this.idActorDetalle = 0;
    this.nmbActor = "";
    this.published = 1;
    this.regActDeta = 0;
    this.lstArchivosActor = new Array();


    /**
     * Setea la informacion de un detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    this.setData = function(data) {
        this.descripcion = data.descripcion;
        this.fecha = data.fecha;
        this.idActor = data.idActor;
        this.idActorDetalle = data.idActorDetalle;
        this.nmbActor = data.nmbActor;
        this.published = data.published;
        this.lstArchivosActor = (data.lstArchivosActor) ? data.lstArchivosActor : new Array();
        this.regActDeta = data.regActDeta;
    };


};

var regActDeta = 0;
var tmpActDeta = null;

jQuery(document).ready(function() {

    jQuery('#saveActorTema').click(function(event) {
        var data = getActDetaForm();
        if (validarActDeta(data)) {
            if (tmpActDeta == null) {
                data.idActorDetalle = 0;
                data.regActDeta = oTema.lstActDeta.length;
                data.published = 1;

                var oActDeta = new ActorDetalle();
                oActDeta.setData(data);
                addActDeta(oActDeta);
                oTema.lstActDeta.push(oActDeta);
            }
            else {
                data.regActDeta = regActDeta;
                actActDeta(data);
            }
            clCmpActDeta();
        } else {
            jAlert(JSL_ALERT_ALL_NEED, JSL_ECORAE);
            return;
        }

    });

    // edicion de un objetivo
    jQuery('.updActDeta').live("click", function() {
        if (tmpActDeta != null) {
            var newRegObjt = this.parentNode.parentNode.id;
            autoSaveActDetaUpdate(newRegObjt, regActDeta);
        } else {
            regActDeta = this.parentNode.parentNode.id;
            loadActDetaFronArray(regActDeta);
            tmpActDeta = getActDetaByReg(regActDeta);
        }
        cleanValidateForn("#formTemaActor");
        if (jQuery('#documActDeta').is(":visible")) {
            jQuery('#documActDeta').css("display", "none");
        }
    });

    /// eliminar una multa
    jQuery('.delActDeta').live("click", function() {
        regActDetaDel = this.parentNode.parentNode.id;
        jQuery.alerts.okButton = JSL_SI;
        jQuery.alerts.cancelButton = JSL_NO;
        jConfirm(JSL_CONFIRM_DEL_ACTDETA, JSL_ECORAE, function(r) {
            if (r) {
                elmActDeta(regActDetaDel);
                reloadActDetaTable();
            } else {

            }
        });
    });

    /**
     *  Add un nuevo actor
     */
    jQuery("#addActorTema").click(function() {
        if (tmpActDeta != null || jQuery('#documActDeta').is(":visible")) {
            clCmpActDeta();
        }

        jQuery('#imgeActDeta').css("display", "none");
        jQuery('#documActDeta').css("display", "none");
        jQuery('#formActDeta').css("display", "block");
    });

    /**
     *  cancelar
     */
    jQuery("#cancelActorTema").click(function() {
        clCmpActDeta();
    });

    /**
     *  cerrar la opcion de carga de documentos
     */
    jQuery("#cerrarDocActorTema").click(function() {
        clCmpActDeta();
    });

    // cancelar
    jQuery(".docActDeta").live("click", function() {
        jQuery('#formActDeta').css("display", "none");
        jQuery('#documActDeta').css("display", "block");
        jQuery('#imgeActDeta').css("display", "none");
        regActDeta = this.parentNode.parentNode.id;
        reloadDocActorDetalle();
    });

});

/**
 *  Funcion que permite guardar automaticamente  
 * @param {int} nuevo         Registro del objetivo que se va a editar
 * @param {int} anterior    Registro del objetivo que se esta editando
 * @returns {int}                  Registro del objetivo que se va a editar
 */
function autoSaveActDetaUpdate(nuevo, anterior) {
    var data = getActDetaForm();
    if (data.descripcion != tmpActDeta.descripcion ||
            data.fecha != tmpActDeta.fecha ||
            data.idActor != tmpActDeta.idActor ||
            data.nmbActor != tmpActDeta.nmbActor
            ) {
        jConfirm(JSL_COM_CONFLICTOS_ACTDETA_CNFIRNCHANGES, JSL_ECORAE, function(r) {
            if (r) {
                data.regActDeta = anterior;
                actActDeta(data);
                loadActDetaFronArray(nuevo);
            } else {
            }
        });
    } else {
        loadActDetaFronArray(nuevo);
    }
    regActDeta = nuevo;
}


/**
 * Validadcon que los campos esten completos
 * @param {object} data
 * @returns {Boolean}
 */
function validarActDeta(data) {
    var flag = false;
    if (
            data.descripcion != "" &&
            data.fecha != "" &&
            data.idActor != 0 &&
            data.nmbActor != ""
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
function addActDeta(data) {
    var regActDeta = data.regActDeta;
    var descripcion = (data.descripcion) ? data.descripcion : "-----";
    var fecha = (data.fecha) ? data.fecha : "-----";
    var nmbActor = (data.nmbActor) ? data.nmbActor : "-----";
    var fila = '';
    fila += '<tr id="' + regActDeta + '">';
    fila += '    <td>' + nmbActor + ' </td>';
    fila += '    <td>' + descripcion + ' </td>';
    fila += '    <td>' + fecha + ' </td>';
    fila += '    <td align="center" width="15" > ';
    fila += '        <a href="#" class="updActDeta"> ' + JSL_UPD_LABEL + '</a> ';
    fila += '    </td>';
    fila += '    <td align="center" width="15" > ';
    fila += '        <a href="#" class="docActDeta"> ' + JSL_DOC_LABEL + '</a>';
    fila += '    </td>';
    fila += '    <td align="center" width="15" > ';
    fila += '        <a href="#" class="delActDeta"> ' + JSL_DEL_LABEL + '</a>';
    fila += '    </td>';
    fila += '</tr>';
    jQuery('#tbLstActores > tbody:last').append(fila);
}

/**
 *  Limpia los campos del formulario
 * 
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpActDeta() {
    jQuery('#formActDeta').css("display", "none");
    jQuery('#documActDeta').css("display", "none");
    jQuery('#imgeActDeta').css("display", "block");

    regActDeta = 0;
    tmpActDeta = null;

    cleanValidateForn("#formTemaActor");

    recorrerCombo(jQuery("#jform_actor_intId_ad option"), 0);
    jQuery("#jform_actor_strDescripcion_ad").val('');
    jQuery("#jform_actor_dteFecha_ad").val('');
}

/**
 *  Elimina los objetivos.
 * @param {type} regActDetaDel
 * @returns {undefined}
 */
function elmActDeta(regActDetaDel) {
    for (var j = 0; j < oTema.lstActDeta.length; j++) {
        if (oTema.lstActDeta[j].regActDeta == regActDetaDel) {
            oTema.lstActDeta[j].published = 0;
        }
    }
}

/**
 *  Actualiza los datos del objetivo
 * @param {object} data
 * @returns {undefined}
 */
function actActDeta(data) {
    for (var j = 0; j < oTema.lstActDeta.length; j++) {
        if (oTema.lstActDeta[j].regActDeta == data.regActDeta) {
            oTema.lstActDeta[j].descripcion = data.descripcion;
            oTema.lstActDeta[j].fecha = data.fecha;
            oTema.lstActDeta[j].idActor = data.idActor;
            oTema.lstActDeta[j].nmbActor = data.nmbActor;
        }
    }
    clCmpActDeta();
    reloadActDetaTable();
}

/**
 *  Borra y buelce a escribir en la tabla objetivos
 * @returns {undefined}
 */
function reloadActDetaTable() {
    jQuery("#tbLstActores > tbody").empty();
    for (var j = 0; j < oTema.lstActDeta.length; j++) {
        if (oTema.lstActDeta[j].published == 1)
            addActDeta(oTema.lstActDeta[j]);
    }
}

/**
 *  Recupera el objetivo dado el registro del objetivo
 * @param {int} regActDeta
 * @returns {unresolved}
 */
function getActDetaByReg(regActDeta) {
    var data = null;
    for (var j = 0; j < oTema.lstActDeta.length; j++) {
        if (oTema.lstActDeta[j].regActDeta == regActDeta) {
            data = oTema.lstActDeta[j];
        }
    }
    return data;
}

/**
 *  Recupera los datos del objetivo
 * @returns {getActDetaForm.data}
 */
function getActDetaForm() {
    var data = {
        descripcion: jQuery("#jform_actor_strDescripcion_ad").val(),
        fecha: jQuery("#jform_actor_dteFecha_ad").val(),
        idActor: jQuery("#jform_actor_intId_ad").val(),
        nmbActor: jQuery("#jform_actor_intId_ad option:selected").text()
    };
    return data;
}

/**
 * Carga los datos del objetivo recuperando los datos desde el array.
 * @param {int} regActDeta     Identificador del objetivo
 * @returns {undefined}
 */
function loadActDetaFronArray(regActDeta) {
//    jQuery('#documActDeta').css("display", "none");
    jQuery('#imgeActDeta').css("display", "none");
    jQuery('#formActDeta').css("display", "block");

    var data = getActDetaByReg(regActDeta);
    //  muestro en el formulario
    if (data) {
        recorrerCombo(jQuery("#jform_actor_intId_ad option"), data.idActor);
        jQuery("#jform_actor_dteFecha_ad").val(data.fecha);
        jQuery("#jform_actor_strDescripcion_ad").val(data.descripcion);
    }
    tmpActDeta = getActDetaByReg(regActDeta);
}

//<editor-fold defaultstate="collapsed" desc="funciones para la carga de archivos">
    
    /**
     * Agrega un documento a la lista de actores
     * @param {type} data
     * @returns {undefined}
     */
    function addDocActor(data, idLAA) {
        for (var j = 0; j < oTema.lstActDeta.length; j++) {
            if (oTema.lstActDeta[j].regActDeta == regActDeta) {
                data.regFilesActor = idLAA;
                data.idActTema = oTema.lstActDeta[j].idActorDetalle;
                oTema.lstActDeta[j].lstArchivosActor.push(data);
            }
        }
    }

    /**
     * Dibuja el lista de detalle de un actor
     * @param {type} data
     * @returns {undefined}
     */
    function addDocActorDetalle(data) {
        var regArchivoActor = data.regFile;
        var nameArchivoActor = (data.nameFile) ? data.nameFile : "-----";
        var fila = '';
        fila += '<tr id="' + regArchivoActor + '">';
        fila += '    <td>' + nameArchivoActor + ' </td>';
        fila += '    <td align="center" width="20" > ';
        fila += (data.flagUp == false) ?
                '<a href="/libraries/donwloadFile.php?src=' + getSourceFileActor(regArchivoActor) + '">' + JSL_DONWDOC_LABEL + '</a>' :
                '<a href="#" >' + JSL_NO_OPT_LABEL + '</a>';
        fila += '   </td>';
        fila += '    <td align="center" width="20" > ';
        fila += '        <a href="#" class="deleteDocAA"> ' + JSL_DEL_LABEL + '</a>';
        fila += '    </td>';
        fila += '</tr>';
        jQuery('#tbDocsActorDetalle > tbody:last').append(fila);

    }

    /**
     * Clase que gestion la eliminacion de archivos de un actor 
     */
    jQuery(".deleteDocAA").live("click", function() {
        var regFile = this.parentNode.parentNode.id;
        jConfirm(JSL_COM_CONFLICTOS_DEL_ARCHIVO_ACTOR, JSL_ECORAE, function(e) {
            if (e) {
                if (!oTema.lstActDeta[regActDeta].lstArchivosActor[regFile].flagUp) {
                    eliminarArchivoActor(regFile);
                } else {
                    delArchivoActorList(regFile);
                }
            }
        });
    });

    /**
     *  Retorna la informacion la para la descarga de archivos
     * @param {type} reg
     * @returns {String|Window.btoa.output|undefined}
     */
    function getSourceFileActor(reg) {

        var regActTema = oTema.lstActDeta[regActDeta].idActorDetalle;
        var file = oTema.lstActDeta[regActDeta].lstArchivosActor[reg].nameFile;
        var data = {tpo: 'AAT', idTema: oTema.idTema, idActor: regActTema, name: file};
        var result = window.btoa(JSON.stringify(data));
        return result;

    }

    /**
     * Redibuja la tabla del actor detalle
     * @returns {undefined}
     */
    function reloadDocActorDetalle() {
        jQuery("#tbDocsActorDetalle > tbody").empty();
        for (var j = 0; j < oTema.lstActDeta.length; j++) {
            if (oTema.lstActDeta[j].regActDeta == regActDeta) {
                if (oTema.lstActDeta[j].lstArchivosActor.length > 0) {
                    for (var k = 0; k < oTema.lstActDeta[j].lstArchivosActor.length; k++) {
                        if (oTema.lstActDeta[j].lstArchivosActor[k].published == 1) {
                            var data = oTema.lstActDeta[j].lstArchivosActor[k];
                            addDocActorDetalle(data);
                        }
                    }
                }
            }
        }
    }

    /**
     * Elimina archivos del servidor
     * @param {type} reg
     * @returns {undefined}
     */
    function eliminarArchivoActor(reg) {
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'delArchivoActor',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                owner: oTema.idTema,
                actor: oTema.lstActDeta[regActDeta].idActorDetalle,
                name: oTema.lstActDeta[regActDeta].lstArchivosActor[reg].nameFile
            },
            error: function(jqXHR, status, error) {
                alert('Gestión de conflictos - Eliminar archivo: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval("(" + data.responseText + ")");
            if (dataInfo) {
                oTema.lstActDeta[regActDeta].lstArchivosActor[reg].published = 0;
            }
            reloadDocActorDetalle();
        });
    }

    /**
     *  Elimina archivos de una actor que aun no ha sido subidos al server
     * @param {type} regFile
     * @returns {undefined}
     */
    function delArchivoActorList(regFile) {
        oTema.lstActDeta[regActDeta].lstArchivosActor[regFile].published = 0;
        var regFAL = oTema.lstActDeta[regActDeta].lstArchivosActor[regFile].regFilesActor;
        if (lstArchivosAct.length > 0) {
            var file = null;
            for (var i = 0; i < lstArchivosAct.length; i++) {
                if (lstArchivosAct[i].regFilesActor == regFAL) {
                    file = lstArchivosAct[i].file;
                }
            }
            if (file != null) {
                jQuery('#cargaArchivosActor').uploadifive('cancel', file);
            }
        }
        reloadDocActorDetalle();
    }

//</editor-fold>
