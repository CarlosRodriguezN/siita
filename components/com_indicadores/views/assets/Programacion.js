var Programacion = function(){
    this.idRegPrg;
    this.idProgramacion;
    this.fecha;
    this.valor;
}

/**
 * 
 * Agrego una fila a la tabla de programacion
 * 
 * @param {type} ban
 * 
 * @returns {String}
 */
Programacion.prototype.addFilaProgramacion = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 )? "<tr id='" + this.idRegPrg + "'>" 
                            : "";

    fila += "       <td align='center'>" + this.fecha + "</td>"
            + "     <td align='center'>" + this.valor + "</td>";
    fila += "       <td align='center'> <a class='updPrg'> Editar </a> </td>";
    fila += "       <td align='center'> <a class='delPrg'> Eliminar </a> </td>";

    fila += ( ban === 0 )   ? "   </tr>" 
                            : "";

    return fila;
}