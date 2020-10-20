var Objetivo = function(){
    this.registroObj;
    this.idObjetivo;     
    this.idPadreObj;     
    this.idPrioridadObj; 
    this.idTpoObj;
    this.idIndEntidad;
    this.idPlnObjetivo;
    
    this.descObjetivo;
    this.descTpoObj;     
    this.fchRegistroObj; 
    this.nmbPrioridadObj;
    
    this.lstAcciones = new Array();
    this.lstActividades = new Array();
    this.lstIndicadores = new Array(); 
    this.lstAlineaciones = new Array(); 
    
    this.published = 1;
}


Objetivo.prototype.setDtaObjetivo = function( dtaObjetivo )
{
    this.registroObj    = dtaObjetivo.registroObj;
    this.idObjetivo     = dtaObjetivo.idObjetivo;     
    this.idPadreObj     = dtaObjetivo.idPadreObj;     
    this.idPrioridadObj = dtaObjetivo.idPrioridadObj; 
    this.idTpoObj       = dtaObjetivo.idTpoObj;
    this.idIndEntidad   = dtaObjetivo.idEntidad;
    this.idPlnObjetivo  = dtaObjetivo.idPlnObjetivo;
    this.descObjetivo   = dtaObjetivo.descObjetivo;
    this.descTpoObj     = dtaObjetivo.descTpoObj;     
    this.fchRegistroObj = dtaObjetivo.fchRegistroObj; 
    this.nmbPrioridadObj= dtaObjetivo.nmbPrioridadObj;
    
    this.lstAcciones = ( dtaObjetivo.lstAcciones === false )
                            ? new Array() 
                            : dtaObjetivo.lstAcciones ;

    this.lstActividades = ( dtaObjetivo.lstActividades === false )  
                            ? new Array() 
                            : dtaObjetivo.lstActividades;

    this.lstIndicadores = ( dtaObjetivo.lstIndicadores === false || dtaObjetivo.lstIndicadores === null || typeof( dtaObjetivo.lstIndicadores ) === undefined )         
                            ? new Array() 
                            : dtaObjetivo.lstIndicadores;

    this.lstAlineaciones= ( dtaObjetivo.lstAlineaciones === false || dtaObjetivo.lstAlineaciones === null || typeof( dtaObjetivo.lstAlineaciones ) === undefined )  
                            ? new Array() 
                            : dtaObjetivo.lstAlineaciones;
}


/**
 * 
 * Retorna el estado del indicador meta
 * 
 * @param {object} dtaObjetivo    Datos del objetivo
 * @returns {}
 * 
 */
Objetivo.prototype.semaforizacionIM = function()
{
    var ban = 0;
    var lstIM = this.lstIndicadores;

    for( var x = 0; x < lstIM.length; x++ ){
        if( lstIM[x].idTpoIndicador == 1 ){
            ban = lstIM[x].semaforizacion();
        }
    }

    return ban;
}

/**
 * 
 * Retorna el estado del indicador meta
 * 
 * @param {object} dtaObjetivo    Datos del objetivo
 * @returns {}
 * 
 */
Objetivo.prototype.semaforizacionInd = function()
{
    var dtaSemaforizacion;
    var lstIM = this.lstIndicadores;
    var nrInd = lstIM.length;

    if( nrInd > 0 ){
        var objInd = new Indicador();
        objInd.setDtaIndicador( lstIM[0] );
        dtaSemaforizacion = objInd.semaforoImagen();
    }else{
        var objInd = new Indicador();
        dtaSemaforizacion = objInd.semaforoImagen();
    }


    return dtaSemaforizacion;
}

/**
 * 
 * Retorna el estado del Alineacion
 * 
 * @param {type} dtaObjetivo
 * @returns {Number}
 */
Objetivo.prototype.semaforizacionAlineacion = function( dtaObjetivo )
{
    var lstAlineacion = this.lstAlineaciones;   
    var ban = ( lstAlineacion.length === 0 )? 0
                                            : 3;

    return ban;
}


/**
 * 
 * Retorna el estado de Acciones
 * 
 * @param {type} dtaObjetivo
 * @returns {Number}
 */
Objetivo.prototype.semaforizacionAccion = function( dtaObjetivo )
{
    var lstAcciones = this.lstAcciones;   
    var ban = ( lstAcciones.length === 0 )   ? 0
                                            : 3;

    return ban;
}