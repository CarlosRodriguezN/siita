//
//  CLASE PROPOSITO
//
var mlProposito = function()
{
    this.idMlProposito = 0;
    this.nombrePto = "";
    this.descripcionPto = "";
    this.lstMedVerificacion = new Array();
    this.lstSupuestos = new Array();
}

/**
 * 
 * Gestiona el registro de un proposito
 * 
 * @param {type} id             Identificador de ML de tipo proposito
 * @param {type} descripcion    Descripcion de ML de tipo proposito
 * 
 * @returns {undefined}
 */
mlProposito.prototype.addProposito = function( id, nombre, descripcion )
{
    this.idMlProposito  = id;
    this.nombrePto = nombre;
    this.descripcionPto = descripcion;
}

/**
 * 
 * Actualizo informacion de Proposito
 * 
 * @param {string} descripcion  Informacion a actualizar
 * @returns {undefined}
 */
mlProposito.prototype.updProposito = function( nombre, descripcion )
{
    this.nombrePto = nombre;
    this.descripcionPto = descripcion;
}