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
var Indicador = function( idIndEntidad, idIndicador, nombreInd, modeloIndicador, umbral, catInd ){
    this.idRegIndicador;
    this.idIndEntidad       = idIndEntidad;
    this.idIndicador        = idIndicador;
    this.idTpoPln           = 0;
    this.nombreIndicador    = nombreInd;
    this.nombreReporte;
    
    this.modeloIndicador    = modeloIndicador;
    this.umbral             = umbral;
    this.oldUmbral          = umbral;
    
    this.banUpdUmbral       = 0;
    this.banUpdFechas       = 0;

    this.idTpoIndicador;
    this.idFrcMonitoreo;
    this.idUndAnalisis;
    this.idTpoUndMedida;
    this.idUndMedida;
    
    this.idClaseIndicador;
    this.idEnfoque;
    this.idDimension;
    
    this.idCategoria = ( typeof( catInd ) === "undefined" ) 
                            ? 0
                            : catInd;
    
    this.idMetodoCalculo;
    this.idHorizonte;
    
    this.idGpoDimension;
    this.idGpoDecision;
    
    this.descripcion;
    this.tpoIndicador;
    
    //  Informacion complementaria
    this.metodologia;
    this.limitaciones;
    this.interpretacion;
    this.disponibilidad;
    
    this.fchHorzMimimo          = '0000-00-00';
    this.oldfchHorzMimimo       = '0000-00-00';
    
    this.fchHorzMaximo          = '0000-00-00';
    this.oldfchHorzMaximo       = '0000-00-00';
    
    
    this.umbMaximo;
    this.umbMinimo;

    //  Unidad de Gestion Responsable
    this.idRegUGR;
    this.idUGResponsable;
    this.oldIdUGResponsable     = 0;
    this.fchInicioUG;
    
    //  Funcionario Responsable
    this.idRegFR;
    this.idResponsableUG;
    this.oldIdResponsableUG;
    
    this.idResponsable;
    this.oldIdResponsable       = 0;
    this.fchInicioFuncionario   = '0000-00-00';
    
    this.idTendencia            = 1;
    this.formula;
    this.enfoque;
    this.undMedida;
    
    this.lstUndsTerritoriales   = new Array();
    this.lstLineaBase           = new Array();
    this.lstRangos              = new Array();
    
    this.lstGpoIndicador        = new Array();
    this.lstPlanificacion       = new Array();
    this.lstVariables           = new Array();
    this.lstSegVariables        = new Array();
    this.lstDimensiones         = new Array();
    
    //  Gestiona la Programacion del indicador ( PPPP / PAPP )
    this.lstProgramacion        = new Array();
    
    //  Gestiona la informacion de registro de HTML de acceso a Tableu
    this.strAccesoTableu        = '';

    this.senplades;
    this.published = 1;
}


Indicador.prototype.toString = function()
{
    return this.nombreIndicador;
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
    if( typeOf( dtaIndicador ) !== "null" ){

        this.idRegIndicador     = ( typeof( dtaIndicador.idRegIndicador ) !== "null" )  
                                        ? dtaIndicador.idRegIndicador 
                                        : 0;

        this.idIndEntidad       = dtaIndicador.idIndEntidad;
        this.idIndicador        = dtaIndicador.idIndicador;
        this.idTpoPln           = dtaIndicador.idTpoPln;

        this.nombreIndicador    = dtaIndicador.nombreIndicador;
        this.nombreReporte      = ( typeof( dtaIndicador.nombreReporte ) !== "null" ) 
                                        ? dtaIndicador.nombreReporte 
                                        : '';

        this.modeloIndicador    = dtaIndicador.modeloIndicador;
        this.umbral             = dtaIndicador.umbral;
        this.oldUmbral          = dtaIndicador.umbral;

        this.idTpoIndicador     = dtaIndicador.idTpoIndicador;
        this.tpoIndicador       = dtaIndicador.tpoIndicador;
        
        this.idFrcMonitoreo     = dtaIndicador.idFrcMonitoreo;
        this.idUndAnalisis      = dtaIndicador.idUndAnalisis;
        this.idTpoUndMedida     = dtaIndicador.idTpoUndMedida;
        this.idUndMedida        = dtaIndicador.idUndMedida;
        this.idUGResponsable    = dtaIndicador.idUGResponsable;

        //  Informacion complementaria de indicador
        this.metodologia    = dtaIndicador.metodologia;
        this.limitaciones   = dtaIndicador.limitaciones;
        this.interpretacion = dtaIndicador.interpretacion;
        this.disponibilidad = dtaIndicador.disponibilidad;

        //  Unidad de Gestion Responsable
        this.idRegUGR           = dtaIndicador.idRegUGR;
        this.oldIdUGResponsable = ( parseInt( this.idIndEntidad ) !== 0 )
                                        ? dtaIndicador.idUGResponsable 
                                        : 0;

        this.idResponsableUG    = dtaIndicador.idResponsableUG;
        this.oldIdResponsableUG = ( parseInt( this.idIndEntidad ) !== 0 )
                                        ? dtaIndicador.idResponsableUG
                                        : 0;

        //  Funcionario Responsable
        this.idRegFR            = dtaIndicador.idRegFR;
        this.idResponsable      = dtaIndicador.idResponsable;
        this.oldIdResponsable   = dtaIndicador.idResponsable;

        this.idClaseIndicador   = dtaIndicador.idClaseIndicador;
        this.idEnfoque          = dtaIndicador.idEnfoque;
        this.idDimension        = dtaIndicador.idDimension;
        this.idCategoria        = dtaIndicador.idCategoria;
        this.idHorizonte        = dtaIndicador.idHorizonte;

        this.idGpoDimension     = dtaIndicador.idGpoDimension;
        this.idGpoDecision      = dtaIndicador.idGpoDecision;

        this.descripcion        = dtaIndicador.descripcion;
        this.tpoIndicador       = dtaIndicador.tpoIndicador;
        
        this.fchHorzMimimo      = dtaIndicador.fchHorzMimimo;
        this.oldfchHorzMimimo   = dtaIndicador.fchHorzMimimo;

        this.fchHorzMaximo      = dtaIndicador.fchHorzMaximo;
        this.oldfchHorzMaximo   = dtaIndicador.fchHorzMaximo;

        this.umbMaximo          = dtaIndicador.umbMaximo;
        this.umbMinimo          = dtaIndicador.umbMinimo;
        this.undMedida          = dtaIndicador.undMedida;
        this.undAnalisis        = dtaIndicador.undAnalisis;

        this.idTendencia        = dtaIndicador.tendencia;
        this.formula            = dtaIndicador.formula;
        this.idMetodoCalculo    = dtaIndicador.idMetodoCalculo;
        this.enfoque            = dtaIndicador.enfoque;
        this.senplades          = dtaIndicador.senplades;

        this.fchInicioUG            = ( typeOf( dtaIndicador.fchInicioUG ) === "undefined" || typeOf( dtaIndicador.fchInicioUG ) === "null" ) 
                                            ? dtaIndicador.fchHorzMimimo
                                            : dtaIndicador.fchInicioUG;

        this.fchInicioFuncionario   = ( typeOf( dtaIndicador.fchInicioFuncionario ) === "undefined" || typeOf( dtaIndicador.fchInicioUG ) === "null" )
                                            ? dtaIndicador.fchHorzMimimo
                                            : dtaIndicador.fchInicioFuncionario;

        this.strAccesoTableu        = dtaIndicador.strAccesoTableu;

        this.lstLineaBase = ( dtaIndicador.lstLineaBase === false || dtaIndicador.lstLineaBase === undefined )  ? new Array() 
                                                                                                                : dtaIndicador.lstLineaBase;

        this.lstUndsTerritoriales = ( dtaIndicador.lstUndsTerritoriales === false || dtaIndicador.lstUndsTerritoriales === undefined )  ? new Array() 
                                                                                                                                        : dtaIndicador.lstUndsTerritoriales;

        this.lstRangos = ( dtaIndicador.lstRangos === false || dtaIndicador.lstRangos === undefined )   ? new Array() 
                                                                                                        : dtaIndicador.lstRangos;

        this.lstGpoIndicador = ( dtaIndicador.lstGpoIndicador === false || dtaIndicador.lstGpoIndicador === undefined ) ? new Array() 
                                                                                                                        : dtaIndicador.lstGpoIndicador;

        this.lstPlanificacion = ( dtaIndicador.lstPlanificacion === false || dtaIndicador.lstPlanificacion === undefined )  ? new Array() 
                                                                                                                            : dtaIndicador.lstPlanificacion;

        this.lstVariables = ( dtaIndicador.lstVariables === false || dtaIndicador.lstVariables === undefined )  ? new Array() 
                                                                                                                : dtaIndicador.lstVariables;

        this.lstDimensiones = ( dtaIndicador.lstDimensiones === false || dtaIndicador.lstDimensiones === undefined )? new Array() 
                                                                                                                    : dtaIndicador.lstDimensiones;

    }

    return;
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

Indicador.prototype.addFilaIndicador = function( ban, tpoGestion )
{
    //  Construyo la Fila
    var fila = ( ban === 0 )? "<tr id='" + this.idRegIndicador + "'>" 
                            : "";

    var ds = this.semaforoImagen();

    fila += "       <td align='center' style='vertical-align: middle;'>" + this.tpoIndicador + "</td>"
            + "     <td style='vertical-align: middle;'>" + this.nombreIndicador + "</td>"
            + "     <td style='vertical-align: middle;'>" + this.descripcion + "</td>"
            + "     <td align='center' style='vertical-align: middle;'>" + this.setDataUmbral( this.umbral ) + ' / ' + this.undMedida + "</td>"
            + "     <td align='center' style='vertical-align: middle;'>" + this.formula + "</td>";

    if( tpoGestion === "pppp" && this.lstVariables.length === 1 ){
        fila += "       <td align='center' style='vertical-align: middle;'>";
        fila += "           <a class='cierreOI'> Cierre </a>";
        fila += "       </td>";
    }else{
        fila += "       <td align='center' style='vertical-align: middle;width: 30px;'> &nbsp;-----&nbsp; </td>";
    }

    fila += "       <td align='center' style='vertical-align: middle;'>";
    fila += "           <a class='updOI'> Editar </a>";
    fila += "       </td>";
    
    fila += "       <td align='center' style='vertical-align: middle;'> <a class='delOI'> Eliminar </a> </td>";

    fila += ( ban == 0 )? "</tr>"
                        : "";

    return fila;
}

Indicador.prototype.addFilaSinRegistros = function()
{
    //  Construyo la Fila
    var fila  = "<tr id='-1'>";
    fila += "   <td colspan='9' align='center'>SIN REGISTROS DISPONIBLES</td>";
    fila += "</tr>";

    return fila;
}


/**
 * 
 * Retorna una bandera que indica el estado de informacion de un indicador
 * 
 * 0: Rojo      Sin Informacion
 * 1: Amarillo  Informacion parcial 
 * 2: Verde     Informacion completa
 * 
 * @returns {Number}
 */
Indicador.prototype.semaforoImagen = function()
{
    var flag = getDtaSemaforizacion( 0 );
    
    if (    parseInt( this.idTpoIndicador ) !== 0
            && parseInt( this.idClaseIndicador ) !== 0
            && parseInt( this.idUndAnalisis ) !== 0
            && parseInt( this.idTpoUndMedida ) !== 0
            && parseInt( this.idUndMedida ) !== 0
            && parseInt( this.idHorizonte ) !== 0
            && parseInt( this.idFrcMonitoreo ) !== 0
            && parseInt( this.idGpoDimension ) !== 0
            && parseInt( this.idGpoDecision ) !== 0
            && parseInt( this.idResponsable ) !== 0
            && parseInt( this.idResponsableUG ) !== 0
            && parseInt( this.idUGResponsable ) !== 0
            && this.nombreIndicador !== ''
            && parseFloat( this.umbral ) !== 0
            && this.descripcion !== ''
            && this.fchHorzMaximo !== ''
            && this.fchHorzMimimo !== ''
            && this.fchInicioFuncionario !== ''
            && this.fchInicioUG !== ''
            && this.formula !== ''
            && this.lstLineaBase.length > 0
            && this.lstUndsTerritoriales.length > 0
            && this.lstRangos.length > 0
            && this.lstDimensiones.length > 0
            && this.lstVariables.length > 0
            && parseInt( this.published ) === 1 ) {
        flag = getDtaSemaforizacion( 2 );
    } else if ( typeOf( this.umbral ) !== "null" 
                && parseFloat( this.umbral ) !== 0 
                && parseInt( this.published ) === 1 ){
        flag = getDtaSemaforizacion( 2 );
    }
    
    return flag;
};



/**
 * 
 * Retorna una bandera que indica el estado de informacion de un indicador
 * 
 * 0: Rojo      Sin Informacion
 * 1: Amarillo  Informacion parcial 
 * 2: Verde     Informacion completa
 * 
 * @returns {Number}
 */
Indicador.prototype.semaforoValor = function()
{
    var flag = 2;
    
    if (    parseInt( this.idTpoIndicador ) !== 0 &&
            parseInt( this.idClaseIndicador ) !== 0 &&
            parseInt( this.idUndAnalisis ) !== 0 &&
            parseInt( this.idTpoUndMedida ) !== 0 &&
            parseInt( this.idUndMedida ) !== 0 &&
            parseInt( this.idHorizonte ) !== 0 &&
            parseInt( this.idFrcMonitoreo ) !== 0 &&
            parseInt( this.idGpoDimension ) !== 0 &&
            parseInt( this.idGpoDecision ) !== 0 &&
            parseInt( this.idResponsable ) !== 0 &&
            parseInt( this.idResponsableUG ) !== 0 &&
            parseInt( this.idUGResponsable ) !== 0 &&
            this.nombreIndicador !== '' &&
            parseFloat( this.umbral ) !== 0 &&
            this.descripcion !== '' &&
            this.fchHorzMaximo !== '' &&
            this.fchHorzMimimo !== '' &&
            this.fchInicioFuncionario !== '' &&
            this.fchInicioUG !== '' &&
            this.formula !== '' &&
            this.lstLineaBase.length > 0 &&
            this.lstUndsTerritoriales.length > 0 &&
            this.lstRangos.length > 0 &&
            this.lstDimensiones.length > 0 &&
            this.lstVariables.length > 0 ) {
        flag = 3;
    } else if ( typeOf( this.umbral ) !== "null" && parseFloat( this.umbral ) !== 0 ){
        flag = 3;
    }
    
    return flag;
};


Indicador.prototype.avalibleRegList = function ( idLista )
{
    var numReg = 0;
    switch (idLista)
    {
        case 'lstLineaBase':
            if (typeof this.lstLineaBase !== 'undefined' && this.lstLineaBase.length > 0){
                for ( var i=0; i< this.lstLineaBase.length; i++){
                    if (this.lstLineaBase[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstUndsTerritoriales':
            if (typeof this.lstUndsTerritoriales !== 'undefined' && this.lstUndsTerritoriales.length > 0){
                for ( var i=0; i< this.lstUndsTerritoriales.length; i++){
                    if (this.lstUndsTerritoriales[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstRangos':
            if (typeof this.lstRangos !== 'undefined' && this.lstRangos.length > 0){
                for ( var i=0; i< this.lstRangos.length; i++){
                    if (this.lstRangos[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstDimensiones':
            if (typeof this.lstDimensiones !== 'undefined' && this.lstDimensiones.length > 0){
                for ( var i=0; i< this.lstDimensiones.length; i++){
                    if (this.lstDimensiones[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstVariables':
            if (typeof this.lstVariables !== 'undefined' && this.lstVariables.length > 0){
                for ( var i=0; i< this.lstVariables.length; i++){
                    if (this.lstVariables[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
    }
    return numReg;
};


/**
 * 
 * Retorna valor minimo de una lista de lineas base 
 * asociadas a un indicador
 * 
 * @returns {Number}
 * 
 */
Indicador.prototype.lineaBaseMinima = function()
{
    var nrLB = this.lstLineaBase.length;
    var valor = 0;
    var lstLB = new Array();
    
    if( nrLB > 0 ){
        for( var x = 0; x < nrLB; x++ ){
            lstLB.push( parseFloat( this.lstLineaBase[x].valor ) );
        }

        //  Ordeno el arreglo de valores de manera ascendente
        lstLB.sort( function(a,b){return a-b} );

        valor = lstLB[0];
    }
    
    return valor;
}


/**
 * 
 * @param {type} banIndicador
 * @returns {Array|getDtaSemaforizacion.dtaSemaforizacion}
 */
function getDtaSemaforizacion( banIndicador )
{
    var dtaSemaforizacion = new Array();

    switch( banIndicador ){
        //  Rojo
        case 0: 
            dtaSemaforizacion["imgAtributo"] = "media/system/images/ECORAE icons/Indicador/indicadores_rj.png";
            dtaSemaforizacion["msgAtributo"] = "Sin Informaci&oacute;n de Indicadores";
        break;

        //  Amarillo
        case 1: 
            dtaSemaforizacion["imgAtributo"]= "media/system/images/ECORAE icons/Indicador/indicadores_am.png";
            dtaSemaforizacion["msgAtributo"]= "Indicador con Atributos Limitados";
            dtaSemaforizacion["msgStyle"]   = "border: 1px solid #cdcdcd;";
        break;

        //  Verde
        case 2: 
            dtaSemaforizacion["imgAtributo"] = "media/system/images/ECORAE icons/Indicador/indicadores_vd.png";
            dtaSemaforizacion["msgAtributo"] = "Indicador(es)";
        break;
    }

    return dtaSemaforizacion;
}



Indicador.prototype.setDataUmbral = function( valor )
{
    var dtaUmbral;
    var valUmbral = 0;
    
    switch( true ){
        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 2 && parseInt( this.idUndMedida ) === 6 ):
            dtaUmbral = parseInt( valor );
        break;

        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 5 && parseInt( this.idUndMedida ) === 17 ):
            valUmbral = parseFloat( valor );
            dtaUmbral = formatNumber( valUmbral.toFixed( 2 ), '$' );
        break;

        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 2 && parseInt( this.idUndMedida ) === 7 ):
            valUmbral = parseFloat( valor );
            dtaUmbral = valUmbral + ' %'
        break;
        
        default:
            dtaUmbral = parseInt( valor );
        break;
        
    }
    
    return dtaUmbral;
}