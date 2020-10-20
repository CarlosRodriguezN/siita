///////////////////////
//  CLASE FIN
///////////////////////
var mlFin = function(){
    this.idMlFin = 0;
    this.nombreFin="";
    this.descripcionFin="";
    this.lstMedVerificacion = new Array();
    this.lstSupuestos = new Array();
}

/**
 * 
 * Gestiona el registro de un marco logico (ML) de tipo Fin
 * 
 * @param {int} id              Identificador de ML de tipo Fin
 * @param {string} nombre       Nombre del Objeto ML Fin 
 * @param {String} descripcion  Descripcion del ML de tipo Fin
 * 
 * @returns {undefined}
 * 
 */
mlFin.prototype.addMlFin = function( id, nombre, descripcion )
{
    this.idMlFin = id;
    this.nombreFin = nombre;
    this.descripcionFin= descripcion;
}

/**
 * 
 * Actualiza informacion de un ML de tipo Fin
 * 
 * @param {string} nombre         Nombre del Objeto ML Fin
 * @param {string} descripcion    Descripcion del Objeto ML Fin
 * 
 * @returns {undefined}
 * 
 */
mlFin.prototype.updFin = function( nombre, descripcion )
{
    this.nombreFin = nombre;
    this.descripcionFin = descripcion;
}

/**
 * 
 * Verifico la existencia de un determinado medio de verificacion 
 * en la lista de medios de verificacion
 * 
 * @param {type} param
 */
mlFin.prototype.existeMedVerificacion = function( objMv )
{
    var nrmv = this.lstMedVerificacion.length;
    
    for( var x = 0; x < nrmv; x++ ){
        if( this.lstMedVerificacion[x].toString() == objMv.toString() ){
            return true;
        }
    }
    
    return false;
}