var Programacion = function(){
    this.idReg;
    this.idProgramacion;
    this.idPrgInd;
    this.idTipo;
    this.descripcion;
    this.alias;
    this.fechaInicio;
    this.fechaFin;
    this.padre;
    this.lstMetasProgramacion = new Array();
    
};

Programacion.prototype.setDataProgramacion = function ( dtaProgramacion )
{
    this.idReg              = dtaProgramacion.idReg;
    this.idProgramacion     = dtaProgramacion.idProgramacion;
    this.idPrgInd           = dtaProgramacion.idPrgInd;
    this.idTipo             = dtaProgramacion.idTipo;
    this.descripcion        = dtaProgramacion.descripcion;
    this.alias              = dtaProgramacion.alias;
    this.fechaInicio        = dtaProgramacion.fechaInicio;
    this.fechaFin           = dtaProgramacion.fechaFin;
    this.padre              = dtaProgramacion.padre;
    this.lstMetasProgramacion = dtaProgramacion.lstMetasProgramacion;
}