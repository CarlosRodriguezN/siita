var UnidadTerritorial = function( idRegistro, idUndTerritorial, provincia, canton, parroquia )
{
    this.idRegUT = idRegistro ;
    this.idUndTerritorial = idUndTerritorial;
    this.provincia = provincia;
    this.canton = canton;
    this.parroquia = parroquia;
    this.published = 1;
}


UnidadTerritorial.prototype.toString = function()
{
    return this.idUndTerritorial;
}