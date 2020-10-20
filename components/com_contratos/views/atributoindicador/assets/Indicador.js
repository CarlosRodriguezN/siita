/**
 * 
 * Clase que gestiona Informacion de un Indicador
 * 
 * @param {int} idIndEntidad        Identificador del Indicador Entidad
 * @param {int} idIndicador         Identificador del Indicador
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
    this.nombreIndicador = nombreInd;
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
    this.undMedida;
    
    this.lstUndsTerritoriales = new Array();
    this.lstLineaBase = new Array();
    this.lstRangos = new Array();
    
    this.lstGpoIndicador = new Array();
    this.lstPlanificacion = new Array();
    this.lstVariables = new Array();
    this.lstSegVariables = new Array();
    this.lstDimensiones = new Array();
    
    //  Gestiona la Programacion del indicador ( PPPP / PAPP )
    this.lstProgramacion = new Array();

    this.published = 1;
}


Indicador.prototype.toString = function()
{
    return  this.nombreIndicador;
}

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
    
    this.descripcion        = dtaIndicador.descripcion;
    this.tpoIndicador       = dtaIndicador.tpoIndicador;
    this.fchHorzMimimo      = dtaIndicador.fchHorzMimimo;
    this.fchHorzMaximo      = dtaIndicador.fchHorzMaximo;
    this.umbMaximo          = dtaIndicador.umbMaximo;
    this.umbMinimo          = dtaIndicador.umbMinimo;
    
    this.tendencia          = dtaIndicador.tendencia;
    this.formula            = dtaIndicador.formula;
    this.enfoque            = dtaIndicador.enfoque;
    
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
* Creo una fila con informacion general del indicador
* 
* @param {type} objIndicador   Informacion de un indicador
* @param {type} ban            Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
*                                      1: Fila Editada -   NO incluye atributo TR de la tabla
* 
* @returns {String}
* 
*/

Indicador.prototype.addFilaIndicador = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='" + this.idRegIndicador + "'>" 
                            : "";

    fila += "       <td align='center'>" + this.nombreIndicador + "</td>"
            + "     <td align='center'>" + this.descripcion + "</td>"
            + "     <td align='center'>" + this.umbral + ' / ' + this.undMedida + "</td>"
            + "     <td align='center'>" + this.formula + "</td>";

    fila += "       <td align='center'>";
    fila += "           <a class='updOI'> Editar </a>";
    fila += "       </td>";

    fila += "       <td align='center'> <a class='delOI'> Eliminar </a> </td>";

    fila += ( ban == 0 )? "   </tr>" 
                        : "";

    return fila;
}



/**
 * 
 * Gestiona el retorno del menor Valor de Lineas Base Asociados al Indicador,
 * en caso de no tener un Lineas Base retorna Cero ( 0 )
 * 
 * @returns {Number}
 * 
 */
Indicador.prototype.getLineaBase = function()
{
    var nrlb = this.lstLineaBase.length;
    var rst = 0;
    
    if( nrlb > 0 ){
        var lstTmpLB = new Array();
    
        for( var x = 0; x < nrlb; x++ ){
            lstTmpLB.push( this.lstLineaBase[x].valor );
        }

        rst = lstTmpLB.sort()[0];
    }
             
    return rst;
}


/**
 * 
 * Retorna Incremento Anual Neto, 
 * 
 * @param {type} umbral     Valor Meta
 * @param {type} fchInicio  Fecha de Inicio
 * @param {type} fchFin     Fecha Fin
 * 
 * @returns {undefined}
 */
Indicador.prototype.programacion = function( fchInicio, fchFin )
{
    var aInicio = parseInt( fchInicio.toString().split( '-' )[0] );
    var aFin = parseInt( fchFin.toString().split( '-' )[0] );
    var numAnios = (aInicio == aFin)? 1 : aFin - aInicio;
    var rst = (( this.umbral - this.getLineaBase() ) / numAnios).toFixed(2);
    var result = [numAnios, rst];
    return result;
};


Indicador.prototype.generaProgramacion = function()
{
    var objProgramacion = new Programacion();
    this.lstProgramacion.push( objProgramacion );
}