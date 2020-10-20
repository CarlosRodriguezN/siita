var GestionCargosUG = function(){
    this.lstCargosUG = new Array();
};


GestionCargosUG.prototype.addCargosUG = function( objCargosUG )
{
    this.lstCargosUG.push( objCargosUG );
};

GestionCargosUG.prototype.loadTableCargosUG = function( objCargosUG ){
    jQuery("#ug-" + objCargosUG.idReg + " > tbody").empty();
    for ( var j=0; j<objCargosUG.lstCargosUG.length; j++ ){
        var fila = this.makeFilaCargoUG( objCargosUG.lstCargosUG[j], 0, objCargosUG.idUG );
        jQuery('#ug-' + objCargosUG.idReg + '> tbody:last').append(fila);
    }
};

/**
 * Agrega una fila a la tabla Otros Indicadores
 * @param {Object} objCargo       Objeto con informacion de POA
 * @param {int} ban             Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
 *                                      1: Fila Editada -   NO incluye atributo TR de la tabla
 *                                          
 * @returns {String}
 */
GestionCargosUG.prototype.makeFilaCargoUG = function( objCargo, ban, idUg )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='" + objCargo.idReg + "' class = 'row" + objCargo.idReg % 2 + "'>" : "";
    fila += "   <td class='center' style = 'width: 20px;' >";
    fila += "       <a class='jgrid' >";
    fila += '           <span class="state publish"><span class="text">Publicado</span></span>';
    fila += "       </a>";
    fila += "   </td>";
    fila += "   <td>";
    fila +=         ( objCargo.strDescripcion_cargo != "" ) ? objCargo.strDescripcion_cargo : JSL_SIN_DESCRIPCION;
    fila += "   </td>";
    fila += "   <td style='width: 20px;'>";
    fila += '       <a onclick="SqueezeBox.fromElement( \'index.php?option=com_mantenimiento&view=cargoug&layout=edit&id=' + idUg + '&grupoCrg=' + objCargo.intIdGrupo_cargo + '&cargoReg=' + objCargo.idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
    fila +=             JSL_ACTUALIZAR;
    fila += "       </a>";
    fila += "   </td>";
    fila += "   <td style='width: 20px;'>";
    fila += '       <a id="cugDel-' + objCargo.idReg + '" class="delCargo">' + JSL_ELIMINAR + '</a>';
    fila += "   </td>";
    fila += ( ban == 0 ) ? "   </tr>" : "";
    return fila;
};
