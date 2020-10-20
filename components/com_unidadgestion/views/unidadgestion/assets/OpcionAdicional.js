var OpcionAdicional = function(){
    this.registroOpAdd;
    this.nombreOpAdd;     
    this.urlOpAdd;     
    this.descripcionOpAdd; 
    this.idGrupo = 0; 
    this.published = 1; 
}


OpcionAdicional.prototype.setDtaOpAdd = function( dtaOpAdd )
{
    this.registroOpAdd      = dtaOpAdd.registroOpAdd;     
    this.nombreOpAdd        = dtaOpAdd.nombreOpAdd;     
    this.urlOpAdd           = dtaOpAdd.urlOpAdd;     
    this.descripcionOpAdd   = dtaOpAdd.descripcionOpAdd; 
    this.idGrupo            = dtaOpAdd.idGrupo; 
    this.disponibleUsr      = dtaOpAdd.disponibleUsr; 
    this.published          = dtaOpAdd.published; 
}