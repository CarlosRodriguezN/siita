var Accion = function(){
    this.registroAcc;
    this.idAccion;
    this.idUniGes;
    this.idAccionUGR;
    this.idFunResp;
    this.idAccionFR;
    this.idTipoAccion;
    
    this.descripcionAccion;
    this.descTipoActividad;
    this.obserbacionAccion;
    this.fechaEjecucionAccion;
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
    
    this.descripcionAccion = dtaAccion.descripcionAccion;
    this.descTipoActividad = dtaAccion.descTipoActividad;
    this.obserbacionAccion = dtaAccion.obserbacionAccion;
    this.fechaEjecucionAccion = dtaAccion.fechaEjecucionAccion;
    this.presupuestoAccion = dtaAccion.presupuestoAccion;
    this.unidadGestionFun = dtaAccion.unidadGestionFun;
}
