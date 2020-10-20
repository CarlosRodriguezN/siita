
cTema = function() {

    this.idTema = 0;
    this.idTipoTema = 0;
    this.idNivelImpacto = 0;
    this.titulo = '';
    this.resumen = '';
    this.observaciones = '';
    this.sugerenncias = '';

    // listas de elementos asocciados
    this.lstActDeta = new Array();
    this.lstFuentes = new Array();
    this.lstEstados = new Array();
    this.lstArchivo = new Array();
    this.lstUnidadesTerritoriales = new Array();

    this.published = 1;

    /**
     * set la informacion General del proyecto
     * @param {object} data
     * @returns {undefined}
     */
    this.setDataGeneral = function(data) {
        this.idTipoTema = (data.idtipoTema) ? data.idtipoTema : 0;
        this.idTema = (data.idTema) ? data.idTema : 0;
        this.idNivelImpacto = (data.idNivelImpacto) ? data.idNivelImpacto : 0;
        this.idFuente = (data.idFuente) ? data.idFuente : 0;
        this.titulo = (data.titulo) ? data.titulo : '';
        this.resumen = (data.resumen) ? data.resumen : '';
        this.observaciones = (data.observaciones) ? data.observaciones : '';
        this.sugerenncias = (data.sugerenncias) ? data.sugerenncias : '';
    };
    /**
     * Setea la lista de actores
     * @param {array} data  Array con la lista de actores.
     * @returns {undefined}
     */
    this.setLstActores = function(data) {
        if (data)
            this.lstActDeta = data;
    };
    /**
     * Setea la lista de los estados
     * @param {type} data   Array con la lista de estados.
     * @returns {undefined}
     */
    this.setLstEstados = function(data) {
        if (data)
            this.lstEstados = data;
    };
    /**
     * Setea la lista de las fuentes de un tema
     * @param {type} data   Array con la lista de fuentes del tema
     * @returns {undefined}
     */
    this.setLstFuentes = function(data) {
        if (data)
            this.lstFuentes = data;
    };
    /**
     * Setea la lista de las unidad terrritoriales
     * @param {type} data   Array con la lista de unidades territoriales.
     * @returns {undefined}
     */
    this.setlstUnidadesTerritoriales = function(data) {
        if (data)
            this.lstUnidadesTerritoriales = data;
    };
    /**
     * Setea la lista de archivos del tema
     * @param {type} data   Array con la lista de archivos del tema.
     * @returns {undefined}
     */
    this.setlstArchivo = function(data) {
        if (data)
            this.lstArchivo = data;
    };


};
