var Actividad = function() {
    this.tipoGestion;
    this.idResponsable;
    this.funNombres;
    this.descripcion;
    this.idObjetivo;
    this.observacion;
    this.fchActividad;
};

Actividad.prototype.setDtaActividad = function(data) {
    this.tipoGestion = data.tipoGestion;
    this.idResponsable = data.idResponsable;
    this.funNombres = data.funNombres;
    this.descripcion = data.descripcion;
    this.idObjetivo = data.idObjetivo;
    this.observacion = data.observacion;
    this.fchActividad = data.fchActividad;
};