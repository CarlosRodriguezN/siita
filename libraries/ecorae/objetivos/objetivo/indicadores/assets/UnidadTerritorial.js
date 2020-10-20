var UnidadTerritorial = function( idRegistro, idUndTerritorial, provincia, canton, parroquia )
{
    this.idRegUT = idRegistro ;
    this.idUndTerritorial = idUndTerritorial;
    this.provincia = provincia;
    this.canton = canton;
    this.parroquia = parroquia;
    this.published = 1;
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
    
    fila +=   " <td align='center'>"+ this.provincia +"</td>"
            + " <td align='center'>"+ this.canton +"</td>"
            + " <td align='center'>"+ this.parroquia +"</td>"
            + " <td align='center'> <a class='updIndUT'> Editar </a> </td>"
            + " <td align='center'> <a class='delIndUT'> Eliminar </a> </td>";
        
    fila += ( ban == 0 )? "</tr>"
                        : "";

    return fila;
}
