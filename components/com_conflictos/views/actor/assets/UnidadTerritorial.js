function UnidadTerritorial() {
    this.idProvincia = 0;
    this.provincia = '-----';
    this.idCanton = 0;
    this.canton = "---";
    this.idParroquia = 0;
    this.parroquia = "---";
    this.published = 1;

    this.setData = function(data) {
        this.idProvincia = (data.idProvincia) ? data.idProvincia : 0;
        this.provincia = (data.provincia) ? data.provincia : "---";
        this.idCanton = (data.idCanton) ? data.idCanton : 0;
        this.canton = (data.canton) ? data.canton : "---";
        this.idParroquia = (data.idParroquia) ? data.idParroquia : 0;
        this.parroquia = (data.parroquia) ? data.parroquia : "---";
        this.published = 1;
    };
}
