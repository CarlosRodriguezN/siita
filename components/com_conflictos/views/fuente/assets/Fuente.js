

cFuente = function() {

    this.idFuente = null;
    this.idTipoFuente = null;
    this.nmbTipoFuente = null;
    this.descripcion = null;
    this.observacion = null;
    this.vigencia = null;
    this.idUnidadTerritorial = null;
    this.published = 1;



    // set de la informacion general de una fuente
    this.setDataGeneral = function(data) {

        this.idFuente = data.idFuente;
        this.idTipoFuente = data.idTipoFuente;
        this.nmbTipoFuente = data.nmbTipoFuente;
        this.descripcion = data.descripcion;
        this.observacion = data.observacion;
        this.vigencia = data.vigencia;
        this.idUnidadTerritorial = data.idUnidadTerritorial;
        this.published = data.published;
    };

    this.lstIncidencias = new Array();
    this.lstLegitimidad = new Array();
    this.unidadTerritorial = new UnidadTerritorial();


    /**
     * Setea la lista de las incidencias
     * @param {type} data   Array con la lista de incidencias
     * @returns {undefined}
     */
    this.setlstIncidencia = function(data) {
        if (data)
            this.lstIncidencias = data;
    };
    /**
     * Setea la lista de las legitimidad de la fuente
     * @param {type} data   Array con la lista de legitimidades.
     * @returns {undefined}
     */
    this.setlstLegitimidad = function(data) {
        if (data)
            this.lstLegitimidad = data;
    };

    /**
     * Setea la lista de las unidad terrritoriales
     * @param {type} data   Array con la lista de unidades territoriales.
     * @returns {undefined}
     */
    this.setUnidadTerritorial = function(data) {
        if (data)
            this.unidadTerritorial.setData(data);
    };
};
