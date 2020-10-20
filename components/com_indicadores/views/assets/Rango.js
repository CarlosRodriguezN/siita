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
    this.idRegRG    = idReg;
    this.idRango    = idRango;
    this.valMinimo  = minimo;
    this.valMaximo  = maximo;
    this.color      = color;

    this.idTpoUndMedida = jQuery( '#jform_intIdTpoUndMedida' ).val();
    this.idUndMedida    = jQuery( '#jform_idUndMedida' ).val();
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

    this.published = 1;
}

Rango.prototype.toString = function()
{
    return this.valMaximo+ '' +this.valMinimo + '' + this.color;
}


Rango.prototype.setDtaRango = function( objRango, tum, um )
{
    this.idRegRG        = parseInt(objRango.idRegRG);
    this.idRango        = objRango.idRango;
    this.valMinimo      = objRango.valMinimo;
    this.valMaximo      = objRango.valMaximo;
    
    this.idTpoUndMedida = tum;
    this.idUndMedida    = um;
    
    this.color          = objRango.color;
    this.roles          = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
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
    var fila = ( ban === 0 ) ? "<tr id='"+ this.idRegRG +"'>" 
                            : "";

    fila += "   <td align='center'>"
            + "     <div style='background-color: "+ this.color +"' > &nbsp; </div>"
            + " </td>"
            + " <td align='center'>"+ this.setDataValor( this.valMinimo ) +"</td>"
            + " <td align='center'>"+ this.setDataValor( this.valMaximo ) +"</td>";
    
    if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
        fila +="    <td align='center'> <a class='updRG'> Editar </a> </td>"
             + "    <td align='center'> <a class='delRG'> Eliminar </a> </td>";
    }else{
        fila +="    <td align='center'> Editar </td>"
             + "    <td align='center'> Eliminar </td>";
    }

    fila += ( ban === 0 )?  "</tr>" 
                        : "";

    return fila;
}


Rango.prototype.addFilaSinRegistros = function()
{
    //  Construyo la Fila
    var fila = "<tr id='-1'>";

    fila += "   <td align='center' colspan='4'>"+ COM_INDICADORES_SIN_REGISTROS +"</td>";

    fila += "</tr>";

    return fila;
}



Rango.prototype.setDataValor = function( valor )
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

        //  Tipo de Unidad de Medida - Cantidad // Unidad de Medida - Unidad
        case( parseInt( this.idTpoUndMedida ) === 2 && parseInt( this.idUndMedida ) === 7 ):
            dtaUmbral = parseInt( valor ) + ' %';
        break;

        default:
            dtaUmbral = parseInt( valor );
        break;
    }
    
    return dtaUmbral;
}