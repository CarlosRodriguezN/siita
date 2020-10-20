var DetalleAgd = function(){
    this.registroDtll;
    this.idDetalle;     
    this.idAgenda;     
    this.strCampoDtll; 
    this.strValorDtll;
    this.published = 1;
};


DetalleAgd.prototype.setDtaDetalleAgd = function( dtaDetalle )
{
    this.registroDtll   = dtaDetalle.registroDtll;
    this.idDetalle      = dtaDetalle.idDetalle;     
    this.idAgenda       = dtaDetalle.idAgenda;     
    this.strCampoDtll   = dtaDetalle.strCampoDtll; 
    this.strValorDtll   = dtaDetalle.strValorDtll;
    this.published      = dtaDetalle.published;
}