var GestionPoas = function(){
    this.lstPoas = new Array();
    this.roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
};


GestionPoas.prototype.addPoa = function( objPoa )
{
    this.lstPoas.push( objPoa );
};

/**
 * 
 * Agrega una fila a la tabla Otros Indicadores
 * 
 * @param {Object} objPOA       Objeto con informacion de POA
 * @param {int} ban             Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
 *                                      1: Fila Editada -   NO incluye atributo TR de la tabla
 *                                          
 * @returns {String}
 */
GestionPoas.prototype.makeFilaPOA = function( objPOA, ban )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='" + objPOA.idRegPoa + "' class = 'row" + objPOA.idRegPoa % 2 + "'>" 
                            : "";
    fila += "   <td class='center' style = 'width: 10px;' >";
    fila += "       <div>";
    fila += ( objPOA.vigenciaPoa == 0 ) ? "<img src = 'media/system/images/siitaGestion/btnIndicadores/atributo/attrRojoSmall.png' title='Plan no vigente' >" 
                                        :"<img src = 'media/system/images/siitaGestion/btnIndicadores/atributo/attrVerdeSmall.png' title='Plan vigente' >";
    fila += "       </div>";
    fila += "   </td>";
    
    fila += "   <td>";
    fila += "   <a href='#' class='loadObjetivosPoa' id='regPoa-" + objPOA.idRegPoa + "'>";
    fila +=         ( objPOA.descripcionPoa != "" ) ? objPOA.descripcionPoa : "Sin descripción";
    fila += "   </a>";
    fila += "   </td>";
    
    if ( (typeof (objPOA.idPadrePoa) != "undefined" && objPOA.idPadrePoa != 0) || this.roles["core.create"] === true || this.roles["core.edit"] === true ) {
        fila += '   <td class="center" style="width: 12px;" > <a onclick="SqueezeBox.fromElement( \'index.php?option=com_funcionarios&view=poa&layout=edit&id=' + objPOA.idRegPoa + '&padre=' + objPOA.idPadrePoa + '&idEntidad' + objPOA.idEntidadPoa + '&op=1&tmpl=component&task=preview\', {size:{x:700 ,y:400}, handler:\'iframe\'} );"> ' + JSL_VER + ' </a> </td>';
        fila += "   <td class='center' style='width: 12px;' > <a id='noAccionPoa-" + objPOA.idRegPoa + "'> " + JSL_NO_HABILITADO + " </a> </td>";
    } else {
        fila += '   <td class="center" style="width: 12px;" > <a onclick="SqueezeBox.fromElement( \'index.php?option=com_funcionarios&view=poa&layout=edit&id=' + objPOA.idRegPoa + '&padre=' + objPOA.idPadrePoa + '&idEntidad' + objPOA.idEntidadPoa + '&op=1&tmpl=component&task=preview\', {size:{x:700 ,y:400}, handler:\'iframe\'} );"> ' + JSL_ACTUALIZAR + ' </a> </td>';
        fila += "   <td class='center' style='width: 12px;' > <a class='delPoa' id='del-" + objPOA.idRegPoa + "'> " + JSL_ELIMINAR + " </a> </td>";
    }
    
    fila += ( ban == 0 ) ? "   </tr>" 
                        : "";

    return fila;
};
