
/**
 * 
 * Gestion de Informacion de Linea Base
 * 
 * @param {type} idReg          Identificador del registro de Linea Base
 * @param {type} idLineaBase    Identificador de Linea Base
 * @param {type} nombre         Nombre de Linea Base
 * @param {type} valor          Valor
 * @param {type} idFuente       Identificador de la Fuente
 * @param {type} fuente         Fuente
 * 
 * @returns {LineaBase}
 */
var LineaBase = function( idReg, idLineaBase, nombre, valor, idFuente, fuente ){
    this.idRegLB = idReg;
    this.idLineaBase = idLineaBase;
    this.nombre = nombre;
    this.valor = valor;
    this.idFuente = idFuente;
    this.fuente = fuente;
}

LineaBase.prototype.toString = function()
{
    return this.idLineaBase;
}