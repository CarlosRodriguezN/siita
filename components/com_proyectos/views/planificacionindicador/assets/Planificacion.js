var Planificacion = function( idRegistro, idPlanificacion, fecha, valor )
{
    this.idRegPI = idRegistro ;
    this.idPlanificacion = idPlanificacion;
    this.fecha = fecha;
    this.valor = valor;
    this.published = 1;
}

Planificacion.prototype.toString = function()
{
    return this.fecha + ' ' + this.valor;
}