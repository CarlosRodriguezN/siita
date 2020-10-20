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
var Indicador = function(idIndEntidad, idIndicador, nombreInd, modeloIndicador, umbral) {
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
Indicador.prototype.setDtaIndicador = function(dtaIndicador)
{
    this.idRegIndicador = dtaIndicador.idRegIndicador;
    this.idIndEntidad = dtaIndicador.idIndEntidad;
    this.idIndicador = dtaIndicador.idIndicador;
    this.nombreIndicador = dtaIndicador.nombreIndicador;
    this.modeloIndicador = dtaIndicador.modeloIndicador;
    this.umbral = dtaIndicador.umbral;

    this.idTpoIndicador = dtaIndicador.idTpoIndicador;
    this.idFrcMonitoreo = dtaIndicador.idFrcMonitoreo;
    this.idUndAnalisis = dtaIndicador.idUndAnalisis;
    this.idTpoUndMedida = dtaIndicador.idTpoUndMedida;
    this.idUndMedida = dtaIndicador.idUndMedida;
    this.idUGResponsable = dtaIndicador.idUGResponsable;
    this.idResponsableUG = dtaIndicador.idResponsableUG;
    this.idResponsable = dtaIndicador.idResponsable;
    this.idClaseIndicador = dtaIndicador.idClaseIndicador;
    this.idEnfoque = dtaIndicador.idEnfoque;
    this.idDimension = dtaIndicador.idDimension;
    this.idCategoria = dtaIndicador.idCategoria;

    this.descripcion = dtaIndicador.descripcion;
    this.tpoIndicador = dtaIndicador.tpoIndicador;
    this.fchHorzMimimo = dtaIndicador.fchHorzMimimo;
    this.fchHorzMaximo = dtaIndicador.fchHorzMaximo;
    this.umbMaximo = dtaIndicador.umbMaximo;
    this.umbMinimo = dtaIndicador.umbMinimo;

    this.tendencia = dtaIndicador.tendencia;
    this.formula = dtaIndicador.formula;
    this.enfoque = dtaIndicador.enfoque;

    this.lstLineaBase = (dtaIndicador.lstLineaBase == false || dtaIndicador.lstLineaBase === undefined) ? new Array()
            : dtaIndicador.lstLineaBase;

    this.lstUndsTerritoriales = (dtaIndicador.lstUndsTerritoriales == false || dtaIndicador.lstUndsTerritoriales === undefined) ? new Array()
            : dtaIndicador.lstUndsTerritoriales;

    this.lstRangos = (dtaIndicador.lstRangos == false || dtaIndicador.lstRangos === undefined) ? new Array()
            : dtaIndicador.lstRangos;

    this.lstGpoIndicador = (dtaIndicador.lstGpoIndicador == false || dtaIndicador.lstGpoIndicador === undefined) ? new Array()
            : dtaIndicador.lstGpoIndicador;

    this.lstPlanificacion = (dtaIndicador.lstPlanificacion == false || dtaIndicador.lstPlanificacion === undefined) ? new Array()
            : dtaIndicador.lstPlanificacion;

    this.lstVariables = (dtaIndicador.lstVariables == false || dtaIndicador.lstVariables === undefined) ? new Array()
            : dtaIndicador.lstVariables;

    this.lstDimensiones = (dtaIndicador.lstDimensiones == false || dtaIndicador.lstDimensiones === undefined) ? new Array()
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

Indicador.prototype.addFilaIndicador = function(ban)
{
    //  Construyo la Fila
    var fila = (ban == 0) ? "<tr id='" + this.idRegIndicador + "'>"
            : "";

    fila += "       <td align='center'>" + this.nombreIndicador + "</td>"
            + "     <td align='center'>" + this.descripcion + "</td>"
            + "     <td align='center'>" + this.umbral + ' / ' + this.undMedida + "</td>"
            + "     <td align='center'>" + this.formula + "</td>";

    fila += "       <td align='center'>";
    fila += "           <a class='updOI'> Editar </a>";
    fila += "       </td>";

    fila += "       <td align='center'> <a class='delOI'> Eliminar </a> </td>";

    fila += (ban == 0) ? "   </tr>"
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

    if (nrlb > 0) {
        var lstTmpLB = new Array();

        for (var x = 0; x < nrlb; x++) {
            lstTmpLB.push(this.lstLineaBase[x].valor);
        }

        rst = parseInt(lstTmpLB.sort()[0]).toFixed(2);
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
Indicador.prototype.getValorAnio = function(fchInicio, fchFin)
{
    var aInicio = parseInt(fchInicio.toString().split('-')[0]);
    var aFin = parseInt(fchFin.toString().split('-')[0]);
    var numAnios = (aFin - aInicio) + 1;
    var rst = ((this.umbral - this.getLineaBase()) / numAnios).toFixed(2);
    var result = [numAnios, rst];
    return result;
};


Indicador.prototype.generaProgramacion = function(fInicio, fFin)
{
    var result = true;
    var valorAnio = this.getValorAnio(fInicio, fFin);
    var lstPrgm = this.makeProgramacion(valorAnio, fInicio, fFin);
    if (lstPrgm) {
        this.lstProgramacion = lstPrgm;
    } else {
        result = false;
    }

    return result;

};


Indicador.prototype.makeProgramacion = function(valorAnio, inicio, fin)
{

    var programacion = new Array();
    var pppp = this.programacionPlurianual(valorAnio, inicio, fin);
    programacion.push(pppp);

    var lstPapp = this.programacionAnual(pppp, inicio, fin);

    for (var i = 0; i < lstPapp.length; i++) {
        programacion.push(lstPapp[i]);
    }




    return programacion;
};

Indicador.prototype.programacionPlurianual = function(valorAnio, inicio, fin)
{
    var fecha = parseInt(inicio.toString().split('-')[0]);
    var pppp = new Array();
    var numA = parseInt(valorAnio[0]);
    var incremento = parseFloat(valorAnio[1]);
    var valFin = parseInt(this.getLineaBase());
    var objProgramacion = new Programacion();

    valFin = valFin + incremento;
    if (numA > 1) {
        for (var i = 0; i < numA; i++) {
            var objMetaProgramacion = new MetaProgramacion();
            objMetaProgramacion.idReg = i;
            objMetaProgramacion.idPrgDetalle = 0;
            objMetaProgramacion.fecha = fecha + "-12-31";
            objMetaProgramacion.valor = valFin.toFixed(2);
            if (i == (numA - 1)) {
                objMetaProgramacion.fecha = fin;
            }
            valFin = valFin + incremento;
            fecha = fecha + 1;
            pppp.push(objMetaProgramacion);
        }
    } else if (numA == 1) {
        var objMetaProgramacion = new MetaProgramacion();
        objMetaProgramacion.idReg = 0;
        objMetaProgramacion.idPrgDetalle = 0;
        objMetaProgramacion.fecha = fin;
        objMetaProgramacion.valor = valFin.toFixed(2);
        pppp.push(objMetaProgramacion);
    }

    objProgramacion.idReg = 0;
    objProgramacion.idProgramacion = 0;
    objProgramacion.idPrgInd = null;
    objProgramacion.idTipo = 3;
    objProgramacion.descripcion = "Programación Plurianual de la Politica Publica" + inicio + fin;
    objProgramacion.alias = "PPPP";
    objProgramacion.fechaInicio = inicio;
    objProgramacion.fechaFin = fin;
    objProgramacion.padre = null;
    objProgramacion.lstMetasProgramacion = pppp;

    return objProgramacion;
};


Indicador.prototype.programacionAnual = function(pppp, inicio, fin)
{
    var lstPapp = new Array();

    var objPrg = new Object();

    objPrg.idReg = 1;
    objPrg.idProgramacion = 0;
    objPrg.idPrgInd = null;
    objPrg.idTipo = 4;
    objPrg.descripcion = "Programación Anual de la Politica Publica";
    objPrg.alias = "PAPP";
    objPrg.padre = null;

    var count = pppp.lstMetasProgramacion.length;
    if (count == 1) {
        objPrg.fechaInicio = inicio;
        objPrg.fechaFin = fin;
        objPrg.lstMetasProgramacion = this.getMetasAnuales();
        var objProgramacion = new Programacion();
        objProgramacion.setDataProgramacion(objPrg);
        lstPapp.push(objProgramacion);
    } else if (count > 1) {
        for (var i = 0; i < count; i++) {
            objPrg.idReg = i + 1;
            switch (i) {
                case 0:
                    objPrg.fechaInicio = inicio;
                    objPrg.fechaFin = pppp.lstMetasProgramacion[i].fecha;
                    var valI = this.getLineaBase();
                    var valF = pppp.lstMetasProgramacion[i].valor;
                    var lstMetas = this.getMetasAnuales(objPrg, valI, valF);
                    objPrg.lstMetasProgramacion = lstMetas

                    break;
                case (i == count - 1):
                    var dtaFecha = parseInt(fin.toString().split('-')[0]);
                    objPrg.fechaInicio = dtaFecha + "-01-01";
                    objPrg.fechaFin = fin;
                    var valI = pppp.lstMetasProgramacion[i - 1].valor;
                    var valF = pppp.lstMetasProgramacion[i].valor;
                    var lstMetas = this.getMetasAnuales(objPrg, valI, valF);
                    objPrg.lstMetasProgramacion = lstMetas;
                    break;
                default:
                    var valI = pppp.lstMetasProgramacion[i - 1].valor;
                    var valF = pppp.lstMetasProgramacion[i].valor;
                    var fecha = pppp.lstMetasProgramacion[i].fecha;
                    var dtaFecha = parseInt(fecha.toString().split('-')[0]);
                    objPrg.fechaInicio = dtaFecha + "-01-01";
                    objPrg.fechaFin = fecha;
                    var lstMetas = this.getMetasAnuales(objPrg, valI, valF);
                    objPrg.lstMetasProgramacion = lstMetas;
                    break;
            }
            var objProgramacion = new Programacion();
            objProgramacion.setDataProgramacion(objPrg);
            lstPapp.push(objProgramacion);
        }

    }

    return lstPapp;

};



Indicador.prototype.getMetasAnuales = function(objPrg, valI, valF)
{
    var lstMetas = new Array();
    var valor = parseFloat((valF - valI) / 2);
    var valor = valor + parseFloat(valI);
    var fecha = this.getDateMitad( objPrg.fechaInicio, objPrg.fechaFin );
    
    for (var j = 0; j < 2; j++) {
        var objMetaProgramacion = new MetaProgramacion();
        objMetaProgramacion.idReg = j;
        objMetaProgramacion.idPrgDetalle = 0;
        if (j == 0) {
            objMetaProgramacion.fecha = fecha;
            objMetaProgramacion.valor = valor.toFixed(2);
        } else {
            objMetaProgramacion.fecha = objPrg.fechaFin;
            objMetaProgramacion.valor = valF;
        }
        lstMetas.push(objMetaProgramacion);
    }
    return lstMetas;
};


Indicador.prototype.getDateMitad = function(fIni, fFin) 
{
    var fecha = null;
    var mI = parseInt(fIni.toString().split('-')[1]);
    var dI = parseInt(fIni.toString().split('-')[2]);
    var mF = parseInt(fFin.toString().split('-')[1]);
    var dF = parseInt(fFin.toString().split('-')[2]);
    var anioRes = parseInt(fIni.toString().split('-')[0]);
    if (mI == 1 && dI == 1 && mF == 12 && (dF == 31 || dF == 30)) {
        fecha = (anioRes + "-06-30");
    } else {
        var meses = ((mF - mI) + 1);
        var diasRestantes = dI + (31 - dF);
        var diaTotal = parseInt(((meses * 30) - diasRestantes) / 2);
        var diaRes =  diaTotal - ( parseInt( diaTotal / 30 ) * 30 );
        diaRes = (diaRes > dI ) ? diaRes - dI : diaRes;
        diaRes = (diaRes < 10 ) ? "0" + diaRes : diaRes;
        var mesRes = parseInt((meses / 2) + mI);
        mesRes = (mesRes < 10 ) ? "0" + mesRes : mesRes;
        fecha = (anioRes + "-" + mesRes + "-" + diaRes).toString();
    }
    
    return fecha;
};

/**
 *  Retorna la caracteristica para el color del semaforo de indicadores
 * @returns {Number}
 */
Indicador.prototype.semaforoImagen = function()
{
    var flag = 0;
    
    if (this.idTpoIndicador != 0 &&
        this.idClaseIndicador != 0 &&
        this.idUndAnalisis != 0 &&
        this.idTpoUndMedida != 0 &&
        this.idUndMedida != 0 &&
        this.idHorizonte != 0 &&
        this.idFrcMonitoreo != 0 &&
        this.idGpoDimension != 0 &&
        this.idGpoDecision != 0 &&
        this.idResponsable != 0 &&
        this.idResponsableUG != 0 &&
        this.idUGResponsable != 0 &&
        this.nombreIndicador != '' &&
        this.umbral != '' &&
        this.descripcion != '' &&
        this.fchHorzMaximo != '' &&
        this.fchHorzMimimo != '' &&
        this.fchInicioFuncionario != '' &&
        this.fchInicioUG != '' &&
        this.formula != '' &&
        this.lstLineaBase.length > 0 &&
        this.lstUndsTerritoriales.length > 0 &&
        this.lstRangos.length > 0 &&
        this.lstDimensiones.length > 0 &&
        this.lstVariables .length > 0 ) {
        flag = 3;
    } else if ( (typeof this.idTpoIndicador == 'undefined'       || this.idTpoIndicador == 0 ) &&
                (typeof this.idClaseIndicador == 'undefined'     || this.idClaseIndicador == 0 ) &&
                (typeof this.idUndAnalisis == 'undefined'        || this.idUndAnalisis == 0 ) &&
                (typeof this.idTpoUndMedida == 'undefined'       || this.idTpoUndMedida == 0 ) &&
                (typeof this.idUndMedida == 'undefined'          || this.idUndMedida == 0 ) &&
                (typeof this.idHorizonte == 'undefined'          || this.idHorizonte == 0 ) &&
                (typeof this.idFrcMonitoreo == 'undefined'       || this.idFrcMonitoreo == 0 ) &&
                (typeof this.idGpoDimension == 'undefined'       || this.idGpoDimension == 0 ) &&
                (typeof this.idGpoDecision == 'undefined'        || this.idGpoDecision == 0 ) &&
                (typeof this.idResponsable == 'undefined'        || this.idResponsable == 0 ) &&
                (typeof this.idResponsableUG == 'undefined'      || this.idResponsableUG == 0 ) &&
                (typeof this.idUGResponsable == 'undefined'      || this.idUGResponsable == 0 ) &&
                (typeof this.nombreIndicador == 'undefined'      || this.nombreIndicador == '' ) &&
                (typeof this.umbral == 'undefined'               || this.umbral == '' ) &&
                (typeof this.descripcion == 'undefined'          || this.descripcion == '' ) &&
                (typeof this.fchHorzMaximo == 'undefined'        || this.fchHorzMaximo == '' ) &&
                (typeof this.fchHorzMimimo == 'undefined'        || this.fchHorzMimimo == '' ) &&
                (typeof this.fchInicioFuncionario == 'undefined' || this.fchInicioFuncionario == '' ) &&
                (typeof this.fchInicioUG == 'undefined'          || this.fchInicioUG == '' ) &&
                (typeof this.formula == 'undefined'              || this.formula == '' ) &&
                (typeof this.lstLineaBase == 'undefined'         || this.avalibleRegList('lstLineaBase') == 0 ) &&
                (typeof this.lstUndsTerritoriales == 'undefined' || this.avalibleRegList('lstUndsTerritoriales') == 0 ) &&
                (typeof this.lstRangos == 'undefined'            || this.avalibleRegList('lstRangos') == 0 ) &&
                (typeof this.lstDimensiones == 'undefined'       || this.avalibleRegList('lstDimensiones') == 0 ) &&
                (typeof this.lstVariables == 'undefined'         || this.avalibleRegList('lstVariables')  == 0 )) {
        flag = 2;
    }
    
    return flag;
};

/**
 *  Controla la existencia de registros validos en las diferentes listas de un indicador
 * @param {type} idLista
 * @returns {Indicador.prototype.avalibleRegList.numReg}
 */
Indicador.prototype.avalibleRegList = function ( idLista )
{
    var numReg = 0;
    switch (idLista)
    {
        case 'lstLineaBase':
            if (typeof this.lstLineaBase != 'undefined' && this.lstLineaBase.length > 0){
                for ( var i=0; i< this.lstLineaBase.length; i++){
                    if (this.lstLineaBase[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstUndsTerritoriales':
            if (typeof this.lstUndsTerritoriales != 'undefined' && this.lstUndsTerritoriales.length > 0){
                for ( var i=0; i< this.lstUndsTerritoriales.length; i++){
                    if (this.lstUndsTerritoriales[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstRangos':
            if (typeof this.lstRangos != 'undefined' && this.lstRangos.length > 0){
                for ( var i=0; i< this.lstRangos.length; i++){
                    if (this.lstRangos[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstDimensiones':
            if (typeof this.lstDimensiones != 'undefined' && this.lstDimensiones.length > 0){
                for ( var i=0; i< this.lstDimensiones.length; i++){
                    if (this.lstDimensiones[i].published == 1){
                        numReg = ++numReg;
                    }
                }
            }
            break;
            
        case 'lstVariables':
            if (typeof this.lstVariables != 'undefined' && this.lstVariables.length > 0){
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
