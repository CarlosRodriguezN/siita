

cActor = function() {

    this.idActor = 0;
    this.actNombre = null;
    this.actApellido = null;
    this.correo = null;
    this.published = 1;



    // set de la informacion general de una fuente
    this.setDataGeneral = function(data) {
        this.idActor = (data.idActor) ? data.idActor : 0;
        this.actNombre = (data.actNombre) ? data.actNombre : "-----";
        this.actApellido = (data.actApellido) ? data.actApellido : "-----";
        this.correo = (data.correo) ? data.correo : "-----";
        this.published = (data.published) ? data.published : 0;
    };

    this.lstIncidencias = new Array();
    this.lstLegitimidad = new Array();
    this.lstFunciones = new Array();

    /**
     * Set a la lista de incidenacias de un actor
     * @param {array}       data    lista de incidencias de un actor
     * @returns {undefined}
     */
    this.setlstIncidencia = function(data) {
        this.lstIncidencias = (data) ? data : new Array();
    };
    /**
     * Set a la lista de l,egitimidad de un actor
     * @param {array}       data    lista de legitimidad de un actor
     * @returns {undefined}
     */
    this.setlstLegitimidad = function(data) {
        this.lstLegitimidad = (data) ? data : new Array();
    };
    /**
     * Set a la lista de funciones de un actor
     * @param {array}       data    lista de funciones de un actor
     * @returns {undefined}
     */
    this.setlstFunciones = function(data) {
        this.lstFunciones = (data) ? data : new Array();
    };
};
