/**
 * 
 * Gestiona Informacion de seguimeiento de una variable
 * 
 * @param {type} idRegistro     Identificador del Registro (lista)
 * @param {type} idSegVariable  Identificador del Registro (Base de Datos)
 * @param {type} fecha          
 * @param {type} valor
 * 
 * @returns {SegVariable}
 * 
 */
var SegVariable = function( idRegistro, idVariable, nombre, fecha, valor )
{
    this.idRegSV = idRegistro;
    this.idVariable = idVariable;
    this.nombre = nombre;
    this.fecha = fecha;
    this.valor = valor;
    this.published = 1;
}

SegVariable.prototype.toString = function()
{
    return this.idVariable + ' ' + this.fecha + ' ' + this.valor;
}