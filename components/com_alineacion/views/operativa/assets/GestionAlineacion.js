function GestionAlineacion() {
    this.lstAlineaciones = new Array();
}

 jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
/**
 * 
 * @returns {undefined}
 */
GestionAlineacion.prototype.addAlineacion = function(oAlineacion) {
    this.lstAlineaciones.push(oAlineacion);
};



GestionAlineacion.prototype.updAlineacion = function(regAlineacion, oAlineacion) {
    for (var j = 0; j < this.lstAlineaciones.length; j++) {
        if (this.lstAlineaciones[j].regAlineacion == regAlineacion) {
            this.lstAlineaciones[j] = oAlineacion;
        }
    }
}
/**
 * 
 * @param {type} regAlineacion
 * @returns {undefined}
 */
GestionAlineacion.prototype.delAlineacion = function(regAlineacion) {
    for (var j = 0; j < this.lstAlineaciones.length; j++) {
        if (this.lstAlineaciones[j].regAlineacion == regAlineacion) {
            this.lstAlineaciones[j].published = 0;
        }
    }
}
;

GestionAlineacion.prototype.getAlineacionByReg = function(regAlineacion) {
    for (var j = 0; j < this.lstAlineaciones.length; j++) {
        if (this.lstAlineaciones[j].regAlineacion == regAlineacion) {
            oAlineacion = this.lstAlineaciones[j];
        }
    }
    return oAlineacion;
};

/**
 * 
 * @returns {undefined}
 */
GestionAlineacion.prototype.reloadLstAlineacion = function() {
    jQuery("#lstAlineacion > tbody").empty();
    for (var j = 0; j < this.lstAlineaciones.length; j++) {
        if (this.lstAlineaciones[j].published == 1) {
            this.lstAlineaciones[j].addTrAlineacion();
        }
    }
};
