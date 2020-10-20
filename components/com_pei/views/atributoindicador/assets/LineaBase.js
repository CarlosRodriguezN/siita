
/**
 * 
 * Gestion de Informacion de Linea Base
 * 
 * @param {type} idReg          Identificador del registro de Linea Base
 * @param {type} idLineaBase    Identificador de Linea Base
 * @param {type} nombre         Nombre de Linea Base
 * @param {type} valor          Valor
 * @param {type} idFuente       Identificador de la Fuente
 * @param {type} fuente         Fuente
 * 
 * @returns {LineaBase}
 */
var LineaBase = function( idReg, idLineaBase, nombre, valor, idFuente, fuente ){
    this.idRegLB = idReg;
    this.idLineaBase = idLineaBase;
    this.nombre = nombre;
    this.valor = valor;
    this.idFuente = idFuente;
    this.fuente = fuente;
}

LineaBase.prototype.toString = function()
{
    return this.idLineaBase;
}

/**
 * 
 * Seteo Informacion de Lineas Base 
 * 
 * @param {Object} objLB  Objeto con informacion de Lineas Base
 * 
 * @returns {undefined}
 * 
 */
LineaBase.prototype.setDtaLineaBase = function( objLB )
{
    this.idRegLB    = objLB.idReg;
    this.idLineaBase= objLB.idLineaBase;
    this.nombre     = objLB.nombre;
    this.valor      = objLB.valor;
    this.idFuente   = objLB.idFuente;
    this.fuente     = objLB.fuente;
}

/**
 * 
 * Retorno Fila de una tabla con informacion de Linea Base 
 * 
 * @param {type} ban
 * @returns {String}
 */
LineaBase.prototype.getFilaLineaBase = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='"+ this.idRegLB +"'>" 
                            : "";

    fila += "   <td align='center'>"+ this.nombre +"</td>"
            + " <td align='center'>"+ this.valor +"</td>"
            + " <td align='center'>"+ this.fuente +"</td>"
            + " <td align='center'> <a class='updLB'> Editar </a> </td>"
            + " <td align='center'> <a class='delLB'> Eliminar </a> </td>";

    fila += ( ban == 0 )?  "</tr>" 
                        : "";

    return fila;
}