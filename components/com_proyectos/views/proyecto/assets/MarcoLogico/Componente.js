//
//  CLASE COMPONENTE
//
var Componente = function(){
    this.idRegComponente= 0;
    this.idMlComponente = 0;
    this.nombreCmp      = '';
    this.descripcionCmp = '';

    //  Lista de Actividades( objeto actividad )
    this.lstActividades  = new Array();
    
    this.lstMedVerificacion = new Array();
    this.lstSupuestos       = new Array();
    this.published;
}

/**
 * 
 * Retorna informacion del objeto componente
 * 
 * @returns {String}
 */
Componente.prototype.toString = function()
{
    return this.nombreCmp + this.descripcionCmp;
}

//  Agrego un componente a un determinado Marco Logico
Componente.prototype.addComponente = function( idReg, idComponente, nombre, descripcion ){
    this.idRegComponente= idReg;
    this.idMlComponente = idComponente;
    this.nombreCmp      = nombre;
    this.descripcionCmp = descripcion;
    this.published      = 1;
}

Componente.prototype.setDtaComponente = function( dtaComponente )
{
    this.idMlComponente = dtaComponente.idMLCmp;
    this.nombreCmp      = dtaComponente.nombre;
    this.descripcionCmp = dtaComponente.descripcion;

    this.lstMedVerificacion = dtaComponente.lstMediosVerificacion;
    this.lstSupuestos       = dtaComponente.lstSupuestos;

    //  Lista de Actividades( objeto actividad )
    this.lstActividades  = dtaComponente.lstActividad;
}


/**
 * 
 * Calculo el nuevo id de registro para una nueva actividad
 * 
 * @returns {integer}
 * 
 */
Componente.prototype.nuevoIdActividad = function()
{
    var nra = this.lstActividades.length;
    return ++nra;
}


/**
 * 
 * Actualizo contenido del componente
 * 
 * @param {type} nombre         Nombre del componente
 * @param {type} descripcion    Descripcion del componente
 * 
 * @returns {undefined}
 */
Componente.prototype.updDtaComponente = function( nombre, descripcion )
{
    this.nombreCmp = nombre;
    this.descripcionCmp = descripcion;
}

/**
 * 
 * Gestiono la eliminacion de un componente, actualizando la bandera a 0, 
 * la cual posteriormente indicara la eliminacion del registro
 * 
 * @returns {undefined}
 */
Componente.prototype.delComponente = function ()
{
    this.published = 0;
}