var EstructuraAgd = function(){
    this.registroEtr;
    this.idEstructura;     
    this.idAgenda;     
    this.idPadreEtr; 
    this.descPadreEtr; 
    this.descripcionEtr;
    this.nivelEtr;
    this.avalibleDel;
    this.published = 1;
};


EstructuraAgd.prototype.setDtaEstructuraAgd = function( dtaEstructura )
{
    this.registroEtr    = dtaEstructura.registroEtr;
    this.idEstructura   = dtaEstructura.idEstructura;     
    this.idAgenda       = dtaEstructura.idAgenda;     
    this.idPadreEtr     = dtaEstructura.idPadreEtr; 
    this.descPadreEtr   = dtaEstructura.descPadreEtr; 
    this.descripcionEtr = dtaEstructura.descripcionEtr;
    this.nivelEtr       = dtaEstructura.nivelEtr;
    this.avalibleDel    = dtaEstructura.avalibleDel;
    this.published      = dtaEstructura.published;
}