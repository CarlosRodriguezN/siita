/**
 * 
 * Gestiona la planificacion de un indicador
 * 
 * @param {int} idReg   Identificador del registro de planificacion
 * @param {int} idPln   Identificador de planificacion
 * @param {date} fecha  Fecha
 * @param {int} valor   Valor
 * 
 * @returns {Planificacion}
 * 
 */
var Planificacion = function( idReg, idPln, fecha, valor, tum, um, dum ){
    this.idRegPln       = idReg;
    this.idPln          = idPln;
    this.fecha          = fecha;
    this.valor          = valor;    
    this.idTpoUndMedida = tum;
    this.idUndMedida    = um;
    this.dum            = dum;

    this.published      = 1;
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}

Planificacion.prototype.toString = function()
{
    return this.fecha;
}

/**
 * 
 * Convierte una fecha en un Objeto DATE
 * 
 * @returns {Date}
 */
Planificacion.prototype.getFecha2date = function( fecha )
{
    var objFecha = false;
    
    if( typeOf( fecha ) !== "null" ){
        var fp = fecha.split( '-' );
        objFecha = new Date( parseInt( fp[0] ), parseInt( fp[1] ) - 1, parseInt( fp[2] ) );
    }
    
    return objFecha;
}


Planificacion.prototype.getDate2Fecha = function( fecha )
{
    var year = fecha.getFullYear();
    var m = parseInt( fecha.getMonth() ) + 1;
    
    var month = ( m < 10 )  ? '0' + m
                            : m;
    
    var day = fecha.getDate();
    
    return year +'-'+ month +'-'+ day;
}


Planificacion.prototype.setDtaPlanificacion = function( objPln, tum, um )
{
    this.idRegPln       = parseInt(objPln.idRegPln);
    this.idPln          = objPln.idPln;
    this.fecha          = objPln.fecha;
    this.idTpoUndMedida = tum;
    this.idUndMedida    = um;

    this.valor          = unformatNumber( objPln.valor );
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}


Planificacion.prototype.setDataUmbral = function( valor )
{
    var dtaUmbral;

    switch( true ){
        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 2 && parseInt( this.idUndMedida ) === 6 ):
            dtaUmbral = parseInt( valor );
        break;

        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 5 && parseInt( this.idUndMedida ) === 17 ):
            var valUmbral = parseFloat( valor );
            dtaUmbral = formatNumber( valUmbral.toFixed( 2 ), '$' );
        break;
        
        default:
            dtaUmbral = parseInt( valor );
        break;
    }
    
    return dtaUmbral;
}

/**
 * 
 * Agrego una fila en la tabla de Planificacion
 * 
 * @param {int} ban     bandera de gestion si es un registro nuevo o uno ya existente
 * 
 * @returns {String}
 * 
 */
Planificacion.prototype.addFilaPln = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 )? "<tr id='"+ this.idRegPln +"'>" 
                            : "";

    fila += "   <td align='center'>"+ this.fecha +"</td>"
            + " <td align='right'>"+ this.valor +"</td>";
    
    if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
        fila += "   <td align='center'> <a class='updPln'> Editar </a> </td>"
                + " <td align='center'> <a class='delPln'> Eliminar </a> </td>";
    }else{
        fila += "   <td align='center'> Editar </td>"
                + " <td align='center'> Eliminar </td>";
    }

    fila += ( ban === 0 )   ?  "</tr>" 
                            : "";

    return fila;
}



Planificacion.prototype.addFilaSinRegistros = function( ban )
{
    //  Construyo la Fila
    var fila = "<tr id='-1'>";
    fila += "       <td align='center' colspan='3'>"+ COM_INDICADORES_SIN_REGISTROS +"</td>";
    fila += "   </tr>";

    return fila;
}


Planificacion.prototype.addFilaTotalPln = function( valor )
{
    //  Construyo la Fila
    var fila = "<tr>";
    fila += "       <td align='center'><b>Total Programado<b></td>"
            + "     <td align='right'><b>"+ valor +"</b></td>"
            + "     <td align='center' colspan='2'> </td>";
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
Planificacion.prototype.validarFecha = function( fInicio, fFin )
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