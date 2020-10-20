/**
 * 
 *  Clase Seguimiento de variables
 * 
 * @param {int} idRegSeg    Identificador de Registro
 * @param {int} idSeg       Identificador de Registro de Seguimiento
 * @param {int} idIV        Identificador de Indicador Variable
 * @param {int} fecha       Fecha 
 * @param {float} valor     Valor
 * 
 * @returns {Seguimiento}
 * 
 */
var Seguimiento = function( idRegSeg, idSeg, idIV, fecha, valor ){
    this.idRegSeg   = idRegSeg;
    this.idSeg      = idSeg;
    this.idIV       = idIV;
    this.fecha      = fecha;
    this.valor      = valor;
    
    this.idTpoUndMedida;
    this.idUndMedida;

    this.published  = 1;
}

/**
 * 
 * Retorna fecha y valor de seguimiento
 * 
 * @returns {String}
 */
Seguimiento.prototype.toString = function()
{
    return this.fecha;
}


/**
 * 
 * Seteo informacion general de Seguimiento de una determinada variable
 * 
 * @param {Object} objSeguimiento       Datos de Seguimiento de una determinada variable
 * 
 * @returns {undefined}
 * 
 */
Seguimiento.prototype.setDtaSeguimiento = function( objSeguimiento, tum, um )
{
    this.idSeg          = objSeguimiento.idSeg;
    this.idIV           = objSeguimiento.idIV;
    this.fecha          = objSeguimiento.fecha;
    this.idTpoUndMedida = tum;
    this.idUndMedida    = um;
    this.valor          = unformatNumber( objSeguimiento.valor );

    this.published  = objSeguimiento.published;
}


Seguimiento.prototype.setDataUmbral = function()
{
    var dtaUmbral;

    switch( true ){
        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 2 && parseInt( this.idUndMedida ) === 6 ):
            dtaUmbral = parseInt( this.valor );
        break;

        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 5 && parseInt( this.idUndMedida ) === 17 ):
            var valUmbral = parseFloat( this.valor );
            dtaUmbral = formatNumber( valUmbral.toFixed( 2 ), '$' );
        break;
        
        default:
            dtaUmbral = parseInt( this.valor );
        break;
    }
    
    return dtaUmbral;
}

/**
 * 
 * Agrego una fila en la table de Seguimiento
 * 
 * @param {int} ban     Bandera de control
 * @returns {String}
 */
Seguimiento.prototype.addFilaSg = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 ) ? "<tr id='"+ this.idRegSeg +"'>" 
                            : "";

    fila += "   <td align='center'>"+ this.fecha +"</td>"
            + " <td align='right'>"+ this.setDataUmbral() +"</td>"
            + " <td align='center'> <a class='updSg'> Editar </a> </td>"
            + " <td align='center'> <a class='delSg'> Eliminar </a> </td>";

    fila += ( ban === 0 )   ?  "</tr>" 
                            : "";

    return fila;
}

Seguimiento.prototype.addFilaSinRegistros = function()
{
    //  Construyo la Fila
    var fila = "<tr id='-1'>";
    fila += "       <td align='center' colspan='3'>"+ COM_INDICADORES_SIN_REGISTROS +"</td>";
    fila += "   </tr>";

    return fila;
}


/**
 * 
 * Agrego una fila en la table de Seguimiento
 * 
 * @param {int} ban     Bandera de control
 * @returns {String}
 */
Seguimiento.prototype.addFilaTotalSg = function( valor )
{
    var valorSeguimiento = parseFloat( valor );
    
    //  Construyo la Fila
    var fila = "<tr id='"+ this.idRegSeg +"'>";
    fila += "       <td align='center'><b>Total Seguimiento</b></td>"
            + "     <td align='right'><b>"+ valorSeguimiento +"</b></td>"
            + "     <td colspan='2'></td>";
    fila += "   </tr>";

    return fila;
}


/**
 * 
 * Valido si una fecha de planificacion se encuentra dentro de un determinado periodo de fechas
 * 
 * @param {String} fInicio      fecha de inicio de periodo de fechas
 * @param {String} fFin         fecha de fin de un periodo de fechas
 * 
 * @returns {Boolean}           True: si se encuentra dentro del periodo, 
 *                              FALSE caso contrario
 * 
 */
Seguimiento.prototype.validarFecha = function( fInicio, fFin )
{
    var ban = false;

    var fecha       = this.getFecha2date( this.fecha );
    var fchInicio   = this.getFecha2date( fInicio );
    var fchFin      = this.getFecha2date( fFin );

    if( fecha >= fchInicio && fecha <= fchFin ){
        ban = true;
    }

    return ban;
}


/**
 * 
 * Convierte una fecha en un Objeto DATE
 * 
 * @returns {Date}
 */
Seguimiento.prototype.getFecha2date = function( fecha )
{
    var objFecha = false;
    
    if( typeOf( fecha ) !== "null" ){
        var fp = fecha.split( '-' );
        objFecha = new Date( parseInt( fp[0] ), parseInt( fp[1] ) - 1, parseInt( fp[2] ) );
    }
    
    return objFecha;
}