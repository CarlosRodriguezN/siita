var OtroIndicador = function( idIndEntidad, idIndicador, nombre, descripcion, undAnalisis, tpoUndMedida, undMedida, formula, valor ){
    this.idRegOtroIndicador;
    this.idIndEntidad = idIndEntidad;
    this.idIndicador = idIndicador;
    this.nombre = nombre;
    this.descripcion = descripcion;
    this.idUndAnalisis = undAnalisis;
    this.idTpoUndMedida = tpoUndMedida;
    this.idUndMedida = undMedida;
    this.formula = formula;
    this.valor = valor;
    
    this.lstPlanificacion = new Array();
    this.lstLineaBase = new Array();
    this.lstUndsTerritoriales = new Array();
    this.lstEnfoque = new Array();
    this.lstVariables = new Array();

    this.mLogico;
}

OtroIndicador.prototype.toString = function()
{    
    return this.nombre;
}