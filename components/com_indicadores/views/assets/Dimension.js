/**
 * 
 * Gestiona informacion de Dimension
 * 
 * @param {type} idRegistro     Identificador de Registro
 * @param {type} idEnfoque      Identificador de Enfoque
 * @param {type} enfoque        Nombre del Enfoque
 * @param {type} idDimension    Identificador de Dimension
 * @param {type} dimension      Nombre de Dimension
 * 
 * @returns {Dimension}
 * 
 */
var Dimension = function( idRegistro, idDimIndicador, idEnfoque, enfoque, idDimension, dimension )
{
    this.idRegDimension = ( typeOf( idRegistro ) === "null" )   ? 0
                                                                : idRegistro;

    this.idDimIndicador = idDimIndicador;
    this.idEnfoque      = idEnfoque;
    this.enfoque        = enfoque;
    this.idDimension    = idDimension;
    this.dimension      = dimension;
    this.published      = 1;
    
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}

Dimension.prototype.toString = function()
{
    return this.idDimension;
}

/**
 * 
 * Seteo Informacion de Datos de la Dimension
 * 
 * @param {Objecto} objDimension    Objetos de una dimension
 * @returns {undefined}
 * 
 */
Dimension.prototype.setDtaDimension = function( objDimension )
{
    this.idRegDimension = objDimension.idRegDimension;
    this.idDimIndicador = objDimension.idDimIndicador;
    this.idEnfoque      = objDimension.idEnfoque;
    this.enfoque        = objDimension.enfoque;
    this.idDimension    = objDimension.idDimension;
    this.dimension      = objDimension.dimension;
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}


/**
 * 
 * Retono informacion con una fila
 * 
 * @param {type} ban    
 * 
 * @returns {String}
 */
Dimension.prototype.addFilaDimension = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 ) ? "<tr id='"+ this.idRegDimension +"'>"
                            : "";

    fila += "   <td align='center'>" + this.dimension + "</td>"
            + " <td align='center'>" + this.enfoque + "</td>";
    
    if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
        fila += "   <td align='center'> <a class='updDim'> Editar </a> </td>"
             + "    <td align='center'> <a class='delDim'> Eliminar </a> </td>";
    }else{
        fila += "   <td align='center'> Editar </td>"
             + "    <td align='center'> Eliminar </td>";
    }

    fila += ( ban === 0 )? "  </tr>"
                        : "";

    return fila;
}

Dimension.prototype.addFilaSinRegistros = function()
{
    //  Construyo la Fila
    var fila = "<tr id='-1'>";
    fila += "       <td align='center' colspan='3'>" + COM_INDICADORES_SIN_REGISTROS + "</td>";
    fila += "   </tr>";

    return fila;
}