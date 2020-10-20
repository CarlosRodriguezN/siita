var UnidadTerritorial = function( idRegistro, idUndTerritorial, provincia, canton, parroquia )
{
    this.idRegUT            = idRegistro ;
    this.idUndTerritorial   = idUndTerritorial;
    this.provincia          = provincia;
    this.canton             = canton;
    this.parroquia          = parroquia;
    this.published          = 1;

    this.roles              = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}


UnidadTerritorial.prototype.toString = function()
{
    return this.idUndTerritorial;
}

/**
 * 
 * Seteo Informacion de Lineas Base 
 * 
 * @param {Object} objUT  Objeto con informacion de Lineas Base
 * 
 * @returns {undefined}
 * 
 */
UnidadTerritorial.prototype.setDtaUT = function( objUT )
{
    this.idRegUT            = objUT.idRegUT;
    this.idUndTerritorial   = objUT.idUndTerritorial;
    this.provincia          = objUT.provincia;
    this.canton             = objUT.canton;
    this.parroquia          = objUT.parroquia;
    
    this.roles              = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
}

/**
 * 
 * Agrego una fila en la table Unidad Territorial
 * 
 * @param {type} undTerritorial
 * @returns {undefined}
 * 
 */
UnidadTerritorial.prototype.addFilaUT = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='"+ this.idRegUT +"'>" 
                            : '';

    var dtaCanton = ( this.canton === "0" ) ? '-----' 
                                            : this.canton;

    var dtaParroquia = ( this.parroquia === "0" )   ? '-----' 
                                                    : this.parroquia;

    fila +=   " <td align='center'>"+ this.provincia +"</td>"
            + " <td align='center'>"+ dtaCanton +"</td>"
            + " <td align='center'>"+ dtaParroquia +"</td>";
    
    if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
        fila += "   <td align='center'> <a class='updIndUT'> Editar </a> </td>"
                + " <td align='center'> <a class='delIndUT'> Eliminar </a> </td>";
    }else{
        fila += "   <td align='center'> Editar </td>"
                + " <td align='center'> Eliminar </td>";
    }

    fila += ( ban === 0 )   ? "</tr>"
                            : "";

    return fila;
}


UnidadTerritorial.prototype.addFilaSinRegistros = function()
{
    //  Construyo la Fila
    var fila =  "<tr id='-1'>";
    fila += "       <td align='center' colspan='4'>"+ COM_INDICADORES_SIN_REGISTROS +"</td>";
    fila += "   </tr>";

    return fila;
}
