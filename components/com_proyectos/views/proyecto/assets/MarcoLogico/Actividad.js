//
//  CLASE ACTIVIDAD
//
var Actividad = function(){
    this.idRegActividad;
    this.idMlActividad;
    this.idRegComponente;
    this.nombreCmp;
    this.nombreAct;
    this.descripcionAct;
    this.published;
    
    this.lstMedVerificacion = new Array();
    this.lstSupuestos = new Array();
}

/**
 * 
 * Gestiono en registro de un nuevo componente
 * 
 * @param {type} idReg          Identificacion del registro
 * @param {type} idRegCmp       Identificacion de Registro de componentes
 * @param {type} Cmp            Nombre del componente
 * @param {type} idActividad    Identificacion de la actividad
 * @param {type} actividad      Nombre de la actividad
 * @param {type} descripcion    Descripcion de la actividad
 * 
 * @returns {undefined}
 * 
 */
Actividad.prototype.addActividad = function(    idReg, 
                                                idRegCmp, 
                                                cmp, 
                                                idActividad, 
                                                actividad, 
                                                descripcion, 
                                                lstMedVerificacion, 
                                                lstSupuestos )
{
    this.idRegActividad     = idReg;
    this.idRegComponente    = idRegCmp;
    this.nombreCmp          = cmp;
    this.idMlActividad      = idActividad;
    this.nombreAct          = actividad;
    this.descripcionAct     = descripcion;
    
    this.lstMedVerificacion = lstMedVerificacion;
    this.lstSupuestos       = lstSupuestos;
    this.published          = 1;
}

/**
 * 
 * Actualiza el campo published a 0
 * 
 * @returns {undefined}
 */
Actividad.prototype.delActividad = function()
{
    this.published = 0;
}