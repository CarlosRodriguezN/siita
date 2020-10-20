/**
 * 
 * Clase que gestiona Informacion de un Indicador
 * 
 * @param {int} idIndEntidad        Identificador del Indicador Entidad
 * @param {int} idIndicador         Identificador Entidad
 * @param {String} nombreInd        Nombre del indicador
 * @param {String} modeloIndicador  Modelo de Indicador ( Economico, Financiero, etc. )
 * @param {float} umbral            Valor Meta a alcanzar el Indicador
 * 
 * @returns {Indicador}
 * 
 */
var Indicador = function( idIndEntidad, idIndicador, nombreInd, modeloIndicador, umbral ){
    this.idRegIndicador;
    this.idIndEntidad = idIndEntidad;
    this.idIndicador = idIndicador;
    this.nombreInd = nombreInd;
    this.modeloIndicador = modeloIndicador;
    this.umbral = umbral;

    this.idTpoIndicador;
    this.idFrcMonitoreo;
    this.idUndAnalisis;
    this.idTpoUndMedida;
    this.idUndMedida;
    this.idUGResponsable;
    this.idResponsableUG;
    this.idResponsable;
    this.idClaseIndicador;
    this.idEnfoque;
    this.idDimension;
    this.idCategoria;
    
    this.descripcion;
    this.tpoIndicador;
    this.fchHorzMimimo;
    this.fchHorzMaximo;
    this.umbMaximo;
    this.umbMinimo;
    
    this.tendencia = 1;
    this.formula;
    this.enfoque;
    
    this.lstUndsTerritoriales = new Array();
    this.lstLineaBase = new Array();
    this.lstRangos = new Array();
    
    this.lstGpoIndicador = new Array();
    this.lstPlanificacion = new Array();
    this.lstVariables = new Array();
    this.lstSegVariables = new Array();
    this.lstDimensiones = new Array();

    this.published = 1;
};


Indicador.prototype.toString = function()
{
    return  this.nombreInd;
};

/**
 * 
 * Seteo informacion de un Indicador
 * 
 * @param {Object} dtaIndicador     Objeto con informacion de un indicador
 * 
 * @returns {undefined}
 * 
 */
Indicador.prototype.setDtaIndicador = function( dtaIndicador )
{
    this.idRegIndicador     = dtaIndicador.idRegIndicador;
    this.idIndEntidad       = dtaIndicador.idIndEntidad;
    this.idIndicador        = dtaIndicador.idIndicador;
    this.nombreIndicador    = dtaIndicador.nombreIndicador;
    this.modeloIndicador    = dtaIndicador.modeloIndicador;
    this.umbral             = dtaIndicador.umbral;

    this.idTpoIndicador     = dtaIndicador.idTpoIndicador;
    this.idFrcMonitoreo     = dtaIndicador.idFrcMonitoreo;
    this.idUndAnalisis      = dtaIndicador.idUndAnalisis;
    this.idTpoUndMedida     = dtaIndicador.idTpoUndMedida;
    this.idUndMedida        = dtaIndicador.idUndMedida;
    this.idUGResponsable    = dtaIndicador.idUGResponsable;
    this.idResponsableUG    = dtaIndicador.idResponsableUG;
    this.idResponsable      = dtaIndicador.idResponsable;
    this.idClaseIndicador   = dtaIndicador.idClaseIndicador;
    this.idEnfoque          = dtaIndicador.idEnfoque;
    this.idDimension        = dtaIndicador.idDimension;
    this.idCategoria        = dtaIndicador.idCategoria;
    
    this.idGpoDimension     = dtaIndicador.idGpoDimension;
    this.idGpoDecision      = dtaIndicador.idGpoDecision;
    
    this.descripcion        = dtaIndicador.descripcion;
    this.tpoIndicador       = dtaIndicador.tpoIndicador;
    this.fchHorzMimimo      = dtaIndicador.fchHorzMimimo;
    this.fchHorzMaximo      = dtaIndicador.fchHorzMaximo;
    this.umbMaximo          = dtaIndicador.umbMaximo;
    this.umbMinimo          = dtaIndicador.umbMinimo;
    
    this.tendencia          = dtaIndicador.tendencia;
    this.formula            = dtaIndicador.formula;
    this.enfoque            = dtaIndicador.enfoque;
    
    this.fchInicioUG        = dtaIndicador.fchInicioUG;
    this.fchInicioFuncionario = dtaIndicador.fchInicioFuncionario;
    
    this.lstLineaBase = ( dtaIndicador.lstLineaBase == false || dtaIndicador.lstLineaBase === undefined )   ? new Array() 
                                                                                                            : dtaIndicador.lstLineaBase;
    
    this.lstUndsTerritoriales = ( dtaIndicador.lstUndsTerritoriales == false || dtaIndicador.lstUndsTerritoriales === undefined ) ? new Array() 
                                                                                                                            : dtaIndicador.lstUndsTerritoriales;

    this.lstRangos = ( dtaIndicador.lstRangos == false || dtaIndicador.lstRangos === undefined )? new Array() 
                                                                                                : dtaIndicador.lstRangos;
    
    this.lstGpoIndicador = ( dtaIndicador.lstGpoIndicador == false || dtaIndicador.lstGpoIndicador === undefined )  ? new Array() 
                                                                                                                    : dtaIndicador.lstGpoIndicador;

    this.lstPlanificacion = ( dtaIndicador.lstPlanificacion == false || dtaIndicador.lstPlanificacion === undefined )   ? new Array() 
                                                                                                                        : dtaIndicador.lstPlanificacion;

    this.lstVariables = ( dtaIndicador.lstVariables == false || dtaIndicador.lstVariables === undefined )   ? new Array() 
                                                                                                            : dtaIndicador.lstVariables;

    this.lstDimensiones = ( dtaIndicador.lstDimensiones == false || dtaIndicador.lstDimensiones === undefined ) ? new Array() 
                                                                                                                : dtaIndicador.lstDimensiones;
}



/**
 * 
 * @returns {Number}
 */
Indicador.prototype.semaforoImagen = function()
{
    var flag = 0;
    if (       this.descripcion != ""
            && this.fchHorzMaximo != ""
            && this.fchHorzMimimo != ""
            && this.fchInicioFuncionario != ""
            && this.fchInicioUG != ""
//            && this.formula != ""
//            && this.idCategoria  && this.idCategoria != 0
            && this.idClaseIndicador != 0
            && this.idFrcMonitoreo != 0
//            && this.idGpoDecision != 0
            && this.idGpoDimension != 0
//            && this.idHorizonte != 0
//            && this.idIndEntidad != 0
//            && this.idIndicador != 0
            && this.idResponsable != 0
            && this.idResponsableUG != 0
//            && this.idTendencia != 0
            && this.idTpoIndicador != 0
            && this.idTpoUndMedida != 0
            && this.idUGResponsable != 0
            && this.idUndAnalisis != 0
//            && this.idUndMedida != 0
            && this.lstDimensiones.length > 0
//            && this.lstGpoIndicador.length > 0
            && this.lstLineaBase.length > 0
//            && this.lstPlanificacion.length > 0
//            && this.lstProgramacion.length > 0
            && this.lstRangos.length > 0
//            && this.lstSegVariables.length > 0
            && this.lstUndsTerritoriales.length > 0
//            && this.lstVariables.length > 0
//            && this.modeloIndicador
            && this.nombreIndicador != ""
            && this.published == 1
//            && this.tendencia != 0
//            && this.umbMaximo
//            && this.umbMinimo
            && this.umbral != ""
            && this.undMedida != "") 
    {
        flag = 3;}
    return flag;
};
