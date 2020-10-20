oGestionAlineacion = new GestionAlineacion();


function GestionAlineacion() {
    this.lstAlineaciones = new Array();
}

/**
 * Agrega una alineacion a la lista de alineaciones
 * @param {type} data
 * @returns {undefined}
 */
GestionAlineacion.prototype.addAlineacion = function(data) {
    data.idRegistro = this.lstAlineaciones.length;
    data.addTrAlineacion();
    this.lstAlineaciones.push(data);
};

/**
 * ACTUALIZA la informacio de una alineacion
 * @param {type} data
 * @returns {undefined}
 */
GestionAlineacion.prototype.updAlineacion = function(idRegistro, data) {
    if (this.lstAlineaciones.length > 0) {
        for (var j = 0; j < this.lstAlineaciones.length; j++) {
            if (this.lstAlineaciones[j].idRegistro == idRegistro)
                this.lstAlineaciones[j] = data;
        }
        this.reloadLstAlineacion();
    }
};

/**
 * DIBUJA la tabla con las alineaciones
 * @returns {undefined}
 */
GestionAlineacion.prototype.reloadLstAlineacion = function() {
    jQuery("#lstAgnd > tbody").empty();
    if (this.lstAlineaciones.length > 0) {
        if (this.lstAlineaciones.length > 0) {
            for (var j = 0; j < this.lstAlineaciones.length; j++) {
                if (this.lstAlineaciones[j].published == 1)
                    this.lstAlineaciones[j].addTrAlineacion();
            }
        }
    }
}

/**
 * ELIMINACION LOGICA de una alineacion
 * @param {type} idRegistro
 * @returns {undefined}
 */
GestionAlineacion.prototype.delAlineacion = function(idRegistro) {
    if (this.lstAlineaciones.length > 0) {
        for (var j = 0; j < this.lstAlineaciones.length; j++) {
            if (this.lstAlineaciones[j].idRegistro == idRegistro)
                this.lstAlineaciones[j].published = 0;
        }
        this.reloadLstAlineacion();
    }
};

/**
 * RECUPERA una ALINEACION dado su identificador de IDREGISTO
 * @param {type} idRegistro
 * @returns {Boolean|GestionAlineacion.prototype.getAlineacionByReg@arr;lstAlineaciones}
 */
GestionAlineacion.prototype.getAlineacionByReg = function(idRegistro) {
    var oAlineacion = false;
    if (this.lstAlineaciones.length > 0) {
        for (var j = 0; j < this.lstAlineaciones.length; j++) {
            if (this.lstAlineaciones[j].idRegistro == idRegistro)
                oAlineacion = this.lstAlineaciones[j];
        }
    }
    return oAlineacion;
};