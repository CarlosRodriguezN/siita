    
var MetaProgramacion = function(){
    this.idReg;
    this.idPrgDetalle;
    this.fecha;
    this.valor;
};

MetaProgramacion.prototype.setDataMetaProgramacion = function ( dtaProgramacion )
{
    this.idReg          = dtaProgramacion.idReg;
    this.idPrgDetalle   = dtaProgramacion.idPrgDetalle;
    this.fecha          = dtaProgramacion.fecha;
    this.valor          = dtaProgramacion.valor;
}

