var Objetivo = function(){
    this.registroObj;
    this.idObjetivo;     
    this.idPadreObj;     
    this.idPrioridadObj; 
    this.idTpoObj;
    
    this.descObjetivo;
    this.descTpoObj;     
    this.fchRegistroObj; 
    this.nmbPrioridadObj;
    
    this.lstAcciones = new Array();
    this.lstActividades = new Array();
    this.lstIndicadores = new Array(); 
    
    this.published = 1;
}


Objetivo.prototype.setDtaObjetivo = function( dtaObjetivo )
{
    this.registroObj = dtaObjetivo.registroObj;
    this.idObjetivo = dtaObjetivo.idObjetivo;     
    this.idPadreObj = dtaObjetivo.idPadreObj;     
    this.idPrioridadObj = dtaObjetivo.idPrioridadObj; 
    this.idTpoObj = dtaObjetivo.idTpoObj;
    
    this.descObjetivo = dtaObjetivo.descObjetivo;
    this.descTpoObj = dtaObjetivo.descTpoObj;     
    this.fchRegistroObj = dtaObjetivo.fchRegistroObj; 
    this.nmbPrioridadObj = dtaObjetivo.nmbPrioridadObj;
    
    this.lstAcciones = ( dtaObjetivo.lstAcciones == false ) ? new Array() 
                                                            : dtaObjetivo.lstAcciones ;
                                                            
    this.lstActividades = ( dtaObjetivo.lstActividades == false )   ? new Array() 
                                                                    : dtaObjetivo.lstActividades;

    this.lstIndicadores = ( dtaObjetivo.lstIndicadores == false || dtaObjetivo.lstIndicadores === null || typeof( dtaObjetivo.lstIndicadores ) === undefined ) ? new Array() 
                                                                                                                        : dtaObjetivo.lstIndicadores;
}