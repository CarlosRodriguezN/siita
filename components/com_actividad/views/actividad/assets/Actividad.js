var Actividad = function() {
    this.idActividad;
    this.registroAct;
    this.tipoGestion;
    this.desTpoGestion;
    this.idResponsable;
    this.funNombres;
    this.descripcion;
    this.idObjetivo;
    this.observacion;
    this.fchActividad;
    this.published = 1;
    
    this.lstArchivosActividad = new Array();
};

Actividad.prototype.setDtaActividad = function(data) {
    this.idActividad = data.idActividad;
    this.registroAct = data.registroAct;
    this.tipoGestion = data.tipoGestion;
    this.desTpoGestion = data.desTpoGestion;
    this.idResponsable = data.idResponsable;
    this.funNombres = data.funNombres;
    this.descripcion = data.descripcion;
    this.idObjetivo = data.idObjetivo;
    this.observacion = data.observacion;
    this.fchActividad = data.fchActividad;
    this.published = data.published;
    
    this.lstArchivosActividad = ( typeof(data.lstArchivosActividad) != "undefined" && data.lstArchivosActividad.length > 0 ) 
                                ? data.lstArchivosActividad
                                : new Array();
};