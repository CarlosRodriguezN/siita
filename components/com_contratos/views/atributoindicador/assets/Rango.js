/**
 * 
 * Gestion de Informacion de rangos de Gestion
 * 
 * @param {type} idReg      Identificador del registro de rangos de Gestion
 * @param {type} minimo     Valor Minimo
 * @param {type} maximo     Valor Maximo
 * @param {type} color      Color
 * 
 * @returns {Rango}
 */
var Rango = function( idReg, idRango, minimo, maximo, color ){
    this.idRegRG = idReg;
    this.idRango = idRango;
    this.valMinimo = minimo;
    this.valMaximo = maximo;
    this.color = color;
    
    this.published = 1;
}

Rango.prototype.toString = function()
{
    return this.valMaximo+' '+this.valMinimo;
}