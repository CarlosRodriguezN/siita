/**
 * GESTIONA INFORMACION DEL INDICADOR - CONTEXTO
 * 
 * 
 * @param {type} idReg          Identificador del registro
 * @param {type} nombre         Nombre del indicador
 * @param {type} descripcion    Descripcion del Indicador
 * 
 * @returns {Contexto}
 * 
 */
var Contexto = function(){
    this.idRegContexto;
    this.lstIndicadores = new Array();
}


Contexto.prototype.toString = function( idReg )
{
    return this.lstIndicadores[idReg].toString();
}

/**
 * 
 * @param {type} dtaContexto
 * @returns {undefined}
 */
Contexto.prototype.setContexto = function( dtaContexto )
{
    this.lstIndicadores.push( dtaContexto );
}

/**
 * 
 * Gestiona el registro de un nuevo Contexto
 * 
 * @param {type} ban    Bandera que indica si la informacion de un nuevo 
 *                      contexto es Nueva, o esta Siendo editada
 *                      
 *                      0: Nueva
 *                      1: Editada
 *                      
 * @returns {String}
 * 
 */
Contexto.prototype.addFilaContexto = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 )? "<tr id='"+ this.idRegContexto +"'>" 
                            : "";

    fila += "   <td align='left'>"+ this.lstIndicadores[this.idRegContexto].nombreIndicador +"</td>"
            + " <td align='left'>"+ this.lstIndicadores[this.idRegContexto].descripcion +"</td>"
            + " <td align='center' style='width: 60px; vertical-align: middle'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=tableu&layout=edit&idIndicador="+ this.lstIndicadores[this.idRegContexto].idIndicador +"&tmpl=component&task=preview\", {size:{x:"+ POPUP_IND_ANCHO +",y:"+ POPUP_IND_ALTO +"}, handler:\"iframe\"} );'> Ver Reporte </a> </td>"
            + " <td align='center' style='width: 50px; vertical-align: middle'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=contexto&idRegContexto="+ this.idRegContexto +"&layout=edit&tmpl=component&task=preview\", {size:{x:"+ POPUP_IND_ANCHO +",y:"+ POPUP_IND_ALTO +"}, handler:\"iframe\"} );'> Contexto </a> </td>"
            + " <td align='center' style='width: 50px; vertical-align: middle'> <a class='updCtxto'> Editar </a> </td>"
            + " <td align='center' style='width: 50px; vertical-align: middle'> <a class='delCtxto'> Eliminar </a> </td>";

    fila += ( ban === 0 )? "</tr>" 
                        : "";

    return fila;
}