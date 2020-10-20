/**
 * 
 * Gestiona informacion de Dimension
 * 
 * @param {type} idRegistro     Identificador de Registro
 * @param {type} idEnfoque      Identificador de Enfoque
 * @param {type} enfoque        Nombre del Enfoque
 * @param {type} idDimension    Identificador de Dimension
 * @param {type} dimension      Nombre de Dimension
 * 
 * @returns {Dimension}
 * 
 */
var Dimension = function( idRegistro, idDimIndicador, idEnfoque, enfoque, idDimension, dimension )
{
    this.idRegDimension = idRegistro;
    this.idDimIndicador = idDimIndicador;
    this.idEnfoque = idEnfoque;
    this.enfoque = enfoque;
    this.idDimension = idDimension;
    this.dimension = dimension;
    this.published = 1;
}

Dimension.prototype.toString = function()
{
    return this.idDimension;
}