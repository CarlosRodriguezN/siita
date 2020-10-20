var Objetivo = function(){
    this.registroObj;
    this.idObjetivo;     
    this.idPadreObj;     
    this.idPrioridadObj; 
    this.idTpoObj;
    this.idEntOwn;
    this.idIndEntidad;
    this.idPlnObjetivo;
    
    this.idOIP;//Rolando
    
    this.descObjetivo;
    this.descTpoObj;     
    this.fchRegistroObj; 
    this.nmbPrioridadObj;
    
    this.alineacion;
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
    this.idEntOwn = dtaObjetivo.idEntOwn;
    this.idIndEntidad = dtaObjetivo.idEntidad;
    this.idPlnObjetivo = dtaObjetivo.idPlnObjetivo;
    this.descObjetivo = dtaObjetivo.descObjetivo;
    this.descTpoObj = dtaObjetivo.descTpoObj;     
    this.fchRegistroObj = dtaObjetivo.fchRegistroObj; 
    this.nmbPrioridadObj = dtaObjetivo.nmbPrioridadObj;
    this.alineacion = dtaObjetivo.alineacion;
    this.idOIP = (dtaObjetivo.idOIP)?dtaObjetivo.idOIP:0;
    
    this.lstAcciones = ( dtaObjetivo.lstAcciones == false ) ? new Array() 
                                                            : dtaObjetivo.lstAcciones ;
                                                            
    this.lstActividades = ( dtaObjetivo.lstActividades == false )   ? new Array() 
                                                                    : dtaObjetivo.lstActividades;
    
    this.lstAlineaciones = ( dtaObjetivo.lstAlineaciones == false )   ? new Array() 
                                                                    : dtaObjetivo.lstAlineaciones;

    this.setLstIndicadores(dtaObjetivo.lstIndicadores);
}

/**
 * Set el valor de la lista de indicadores
 * @param {type} lstIndicadores
 * @returns {undefined}
 */
Objetivo.prototype.setLstIndicadores = function(lstIndicadores) {
    if (lstIndicadores.length > 0) {
        for (var j = 0; j < lstIndicadores.length; j++) {
            var oIndicador = new Indicador();
            oIndicador.setDtaIndicador(lstIndicadores[j]);
            this.lstIndicadores.push(oIndicador);
        }
    }
}