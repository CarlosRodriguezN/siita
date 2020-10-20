/**
 * 
 * Gestion de Informacion de rangos de Gestion
 * 
 * @param {type} idReg      Identificador del registro de rangos de Gestion
 * @param {type} minimo     Valor Minimo
 * @param {type} maximo     Valor Maximo
 * @param {type} color      Color
 * 
 * @returns {Rango}
 */
var Rango = function( idReg, idRango, minimo, maximo, color ){
    this.idRegRG = idReg;
    this.idRango = idRango;
    this.valMinimo = minimo;
    this.valMaximo = maximo;
    this.color = color;

    this.published = 1;
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}

Rango.prototype.toString = function()
{
    return this.valMaximo+' '+this.valMinimo;
}


Rango.prototype.setDtaRango = function( objRango )
{
    this.idRegRG = objRango.idRegRG;
    this.idRango = objRango.idRango;
    this.valMinimo = objRango.valMinimo;
    this.valMaximo = objRango.valMaximo;
    this.color = objRango.color;
}


/**
* 
* Agrego una fila en la table de Rangos
* 
* @param {type} rango 
* @returns {undefined}
*/
Rango.prototype.addFilaRG = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='"+ this.idRegRG +"'>" 
                            : "";

    fila += "   <td align='center'>"
            + "     <div style='background-color: "+ this.color +"' > &nbsp; </div>"
            + " </td>"
            + " <td align='center'>"+ this.valMinimo +"</td>"
            + " <td align='center'>"+ this.valMaximo +"</td>"
    
    if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
        fila += "   <td align='center'> <a class='updRG'> Editar </a> </td>"
                + " <td align='center'> <a class='delRG'> Eliminar </a> </td>";
    }else{
        fila += "   <td align='center'> Editar </td>"
                + " <td align='center'> Eliminar </td>";
    }
    
    fila += ( ban == 0 )?  "</tr>" 
                        : "";

    return fila;
}