var Enfoque = function( idRegistro, idEnfoque, enfoque, idDimension, dimension )
{
    this.idRegEnfoque = idRegistro;
    this.idEnfoque = idEnfoque;
    this.enfoque = enfoque;
    this.idDimension = idDimension;
    this.dimension = dimension;
    this.published = 1;
}


Enfoque.prototype.toString = function()
{
    return this.idDimension;
}