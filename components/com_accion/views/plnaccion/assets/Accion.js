var Accion = function(){
    this.registroAcc;
    this.idAccion;
    this.idAccionUGR;
    
    this.idUniGes   = 0;
    this.oldIdUniGes= 0;

    this.idFunResp      = 0;
    this.oldIdFunResp   = 0;

    this.idAccionFR;
    this.idTipoAccion;
    this.idPlnObjetivo;
    this.idPlnObjAccion;
    this.idEntObjetivo;
    
    this.descripcionAccion;
    this.descTipoActividad;
    this.observacionAccion;
    this.fechaInicioAccion;
    this.fechaFinAccion;
    this.fechaInicioUGR;
    this.fechaInicioFR;
    this.presupuestoAccion;
    this.unidadGestionFun;
    
    this.published = 1;
}


Accion.prototype.setDtaAccion = function( dtaAccion ){
    this.registroAcc    = dtaAccion.registroAcc;
    this.idAccion       = dtaAccion.idAccion;
    this.idAccionUGR    = dtaAccion.idAccionUGR;
    
    this.idUniGes       = dtaAccion.idUniGes;
    this.oldIdUniGes    = dtaAccion.idUniGes;

    this.idFunResp      = dtaAccion.idFunResp;
    this.oldIdFunResp   = dtaAccion.idFunResp;

    this.idAccionFR     = dtaAccion.idAccionFR;
    this.idTipoAccion   = dtaAccion.idTipoAccion;
    this.idPlnObjetivo  = dtaAccion.idPlnObjetivo;
    this.idPlnObjAccion = dtaAccion.idPlnObjAccion;
    this.idEntObjetivo  = dtaAccion.idEntObjetivo;
    
    this.descripcionAccion  = dtaAccion.descripcionAccion;
    this.descTipoActividad  = dtaAccion.descTipoActividad;
    this.observacionAccion  = dtaAccion.observacionAccion;
    this.fechaInicioAccion  = dtaAccion.fechaInicioAccion;
    this.fechaFinAccion     = dtaAccion.fechaFinAccion;
    
    this.fechaInicioUGR     = dtaAccion.fechaInicioUGR;
    this.fechaInicioFR      = dtaAccion.fechaInicioFR;
    
    this.presupuestoAccion  = dtaAccion.presupuestoAccion;
    this.unidadGestionFun   = dtaAccion.unidadGestionFun;
}
