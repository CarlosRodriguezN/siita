var PlanObjetivo = function(){
    this.idObjetivo     = 0;
    this.idPlnObjetivo  = 0;
    this.idEntidadObjetivo;
    this.nombre;
    
    this.lstAlineaciones= new Array();
    this.lstAcciones    = new Array();
    this.lstIndicadores = new Array();
}

/**
 * 
 * Setea los valores del objetivo
 * 
 * @param {type} dtaObjetivo    Datos del objetivo
 * 
 * @returns {undefined}
 */
PlanObjetivo.prototype.setDataObjetivo = function( dtaObjetivo )
{
    this.idObjetivo         = dtaObjetivo.idObjetivo;
    this.idPlnObjetivo      = dtaObjetivo.idPlnObjetivo;
    this.idEntidadObjetivo  = dtaObjetivo.idEntidadObjetivo;
    this.nombre             = dtaObjetivo.descObjetivo;

    this.setDataIndicadores( dtaObjetivo.lstIndicadores );
    this.setDataAcciones( dtaObjetivo.lstAcciones );
    this.setDataAlineaciones( dtaObjetivo.lstAlineacion );
}

PlanObjetivo.prototype.setDataAlineaciones = function( dtaAlineaciones )
{
    if( typeOf( dtaAlineaciones ) !== "null" && dtaAlineaciones.length ){
        for( var x = 0; x < dtaAlineaciones.length; x++ ){
            this.lstAlineaciones.push( dtaAlineaciones[x] );
        }
    }
}

PlanObjetivo.prototype.setDataAcciones = function( dtaAcciones )
{
    if( typeOf( dtaAcciones ) !== "null" && dtaAcciones.length ){
        for( var x = 0; x < dtaAcciones.length; x++ ){
            this.lstAcciones.push( dtaAcciones[x] );
        }
    }
}

PlanObjetivo.prototype.setDataIndicadores = function ( dtaIndicadores )
{
    if( typeOf( dtaIndicadores ) !== "null" && dtaIndicadores.length ){
        for( var x = 0; x < dtaIndicadores.length; x++ ){
            var objInd = new Indicador();
            objInd.setDtaIndicador( dtaIndicadores[x] );

            this.lstIndicadores.push( objInd );
        }
    }
}