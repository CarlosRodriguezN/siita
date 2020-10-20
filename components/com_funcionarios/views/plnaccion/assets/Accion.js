var Accion = function(){
    this.registroAcc;
    this.idAccion;
    this.idUniGes;
    this.idAccionUGR;
    this.idFunResp;
    this.idAccionFR;
    this.idTipoAccion;
    this.idPlnObjetivo;
    
    this.descripcionAccion;
    this.descTipoActividad;
    this.obserbacionAccion;
    this.fechaInicioAccion;
    this.fechaFinAccion;
    this.presupuestoAccion;
    this.unidadGestionFun;
    
    this.published = 1;
}


Accion.prototype.setDtaAccion = function( dtaAccion ){
    this.registroAcc = dtaAccion.registroAcc;
    this.idAccion = dtaAccion.idAccion;
    this.idUniGes = dtaAccion.idUniGes;
    this.idAccionUGR = dtaAccion.idAccionUGR;
    this.idFunResp = dtaAccion.idFunResp;
    this.idAccionFR = dtaAccion.idAccionFR;
    this.idTipoAccion = dtaAccion.idTipoAccion;
    this.idPlnObjetivo = dtaAccion.idPlnObjetivo;
    
    this.descripcionAccion = dtaAccion.descripcionAccion;
    this.descTipoActividad = dtaAccion.descTipoActividad;
    this.obserbacionAccion = dtaAccion.obserbacionAccion;
    this.fechaInicioAccion = dtaAccion.fechaInicioAccion;
    this.fechaFinAccion = dtaAccion.fechaFinAccion;
    this.presupuestoAccion = dtaAccion.presupuestoAccion;
    this.unidadGestionFun = dtaAccion.unidadGestionFun;
}
