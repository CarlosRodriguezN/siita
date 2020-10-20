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
    this.idRegDimension = idRegistro;
    this.idDimIndicador = idDimIndicador;
    this.idEnfoque = idEnfoque;
    this.enfoque = enfoque;
    this.idDimension = idDimension;
    this.dimension = dimension;
    this.published = 1;
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
    this.idEnfoque = objDimension.idEnfoque;
    this.enfoque = objDimension.enfoque;
    this.idDimension = objDimension.idDimension;
    this.dimension = objDimension.dimension;
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
    var fila = ( ban == 0 ) 
            ? "<tr id='"+ this.idRegDimension +"'>"
            : "";

    fila += "       <td align='center'>"+ this.dimension +"</td>"
                + " <td align='center'>"+ this.enfoque +"</td>"
                + " <td align='center'> <a class='updDim'> Editar </a> </td>"
                + " <td align='center'> <a class='delDim'> Eliminar </a> </td>";

    fila += ( ban == 0 )
            ? "  </tr>"
            : "";

    return fila;
}