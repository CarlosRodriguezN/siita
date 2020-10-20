/**
 * 
 * Gestion de informacion de una variable
 * 
 * @param {type} idReg          Identificador de Registro de Variables
 * @param {type} idIndVariable  Identificador de asociacion Indicador Variable
 * @param {type} idIndicador    Identificador de Indicador
 * @param {type} idVariable     Identificador de Variable
 * @param {type} nombre         Nombre de Variable
 * @param {type} descripcion    Descripcion de Variable
 * @param {type} idTpoUM        Identificador de Tipo de Unidad de Medida
 * @param {type} idUndMedida    Identificador de Unidad de Medida
 * @param {type} undMedida      Nombre de Unidad de Medida
 * @param {type} idUndAnalisis  Identificador de Unidad de Analisis
 * @param {type} undAnalisis    Nombre de Unidad de Analisis
 * 
 * @param {type} ban            Bandera con la finalidad de diferenciar una variable nueva de una ya registrada
 *                              0:  Registrada
                                1:  Nueva
 * 
 * @returns {Variable}
 */
var Variable = function( idReg, idIndVariable, idIndicador, idVariable, nombre, descripcion, idTpoUM, idUndMedida, undMedida, idUndAnalisis, undAnalisis, ban ){
    this.idRegVar = idReg;
    this.idIndVariable = idIndVariable
    this.idIndicador = idIndicador;
    this.idVariable = idVariable;    
    this.nombre = nombre;
    this.descripcion = descripcion;
    this.idTpoUM = idTpoUM;
    this.idUndMedida = idUndMedida;
    this.undMedida = undMedida;
    this.idUndAnalisis = idUndAnalisis;
    this.undAnalisis = undAnalisis;
    this.lstSeguimientos = new Array();
    this.published = 1;
    
    this.ban = ban;
}

Variable.prototype.toString = function()
{
    return this.nombre;
}