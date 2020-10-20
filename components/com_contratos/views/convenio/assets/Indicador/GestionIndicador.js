var GestionIndicador = function() {
    this.indEconomico = new Array();
    this.indFinanciero = new Array();
    this.indBDirecto = new Array();
    this.indBIndirecto = new Array();

    this.lstGAP = new Array();
    this.lstEnfIgualdad = new Array();
    this.lstEnfEcorae = new Array();
    this.lstOtrosIndicadores = new Array();
}

//////////////////////////////////////////
//  INDICADORES ECONOMICOS
//////////////////////////////////////////
/**
 * 
 * Agrego un indicador Economico
 * 
 * @param {Object} indEconomico     Objeto de tipo indicador Economico
 * @returns {undefined}
 */
GestionIndicador.prototype.addIndEconomico = function(indTd, indVan, indTir)
{
    this.indEconomico["0"] = indTd;
    this.indEconomico["1"] = indVan;
    this.indEconomico["2"] = indTir;
}

/**
 * 
 * Verifico la existencia de indicadores economicos registrados, 
 * retornando la cantidad de registros existentes en la lista de indicadores economicos
 * 
 * @returns {int}
 */
GestionIndicador.prototype.existenIndsEconomicos = function()
{
    return this.indEconomico.length;
}

/**
 * 
 * Actualizo el umbral de los indicadores
 * 
 * @param {type} indTd  umbral de indicador Tasa de Descuento
 * @param {type} indVan umbral de indicador VAN
 * @param {type} indTir umbral de indicador TIR
 * 
 * @returns {undefined}
 */
GestionIndicador.prototype.updIndEconomico = function(indTd, indVan, indTir)
{
    this.indEconomico["0"].umbral = indTd;
    this.indEconomico["1"].umbral = indVan;
    this.indEconomico["2"].umbral = indTir;
}


///////////////////////////////////////
//  INDICADORES FINANCIEROS
///////////////////////////////////////

/**
 * 
 * Agrego un indicador Financiero
 * 
 * @param {Object} indFinanciero    Objeto de tipo indicador Financiero
 * @returns {undefined}
 */
GestionIndicador.prototype.addIndFinanciero = function(indTd, indVan, indTir)
{
    this.indFinanciero["0"] = indTd;
    this.indFinanciero["1"] = indVan;
    this.indFinanciero["2"] = indTir;
}

/**
 * 
 * Verifico la existencia de indicadores Financieros registrados, 
 * retornando la cantidad de registros existentes en la lista de indicadores financieros
 * 
 * @returns {int}
 */
GestionIndicador.prototype.existenIndsFinancieros = function()
{
    return this.indFinanciero.length;
}


/**
 * 
 * Actualizo el umbral de los indicadores
 * 
 * @param {type} indTd  umbral de indicador Tasa de Descuento
 * @param {type} indVan umbral de indicador VAN
 * @param {type} indTir umbral de indicador TIR
 * 
 * @returns {undefined}
 */
GestionIndicador.prototype.updIndFinanciero = function(indTd, indVan, indTir)
{
    this.indFinanciero["0"].umbral = indTd;
    this.indFinanciero["1"].umbral = indVan;
    this.indFinanciero["2"].umbral = indTir;
}


/////////////////////////////////////////////
//  INDICADOR BENEFICIARIOS DIRECTOS
/////////////////////////////////////////////

/**
 * 
 * Agrego un indicador Economico
 * 
 * @param {Object} indFinanciero    Objeto de tipo indicador Financiero
 * @returns {undefined}
 */
GestionIndicador.prototype.addIndBeneficiariosDirecto = function(objBDH, objBDM, objBDT)
{
    this.indBDirecto["0"] = objBDH;
    this.indBDirecto["1"] = objBDM;
    this.indBDirecto["2"] = objBDT;
}

/**
 * 
 * Verifico la existencia de indicadores Financieros registrados, 
 * retornando la cantidad de registros existentes en la lista de indicadores financieros
 * 
 * @returns {int}
 */
GestionIndicador.prototype.existenIndsBDirectos = function()
{
    return this.indBDirecto.length;
}


/**
 * 
 * Actualizo el umbral de los indicadores
 * 
 * @param {type} indTd  umbral de indicador Tasa de Descuento
 * @param {type} indVan umbral de indicador VAN
 * @param {type} indTir umbral de indicador TIR
 * 
 * @returns {undefined}
 */
GestionIndicador.prototype.updIndBDirecto = function(valBDH, valBDM, valBDT)
{
    this.indBDirecto["0"].umbral = valBDH;
    this.indBDirecto["1"].umbral = valBDM;
    this.indBDirecto["2"].umbral = valBDT;
}



/////////////////////////////////////////////
//  INDICADOR BENEFICIARIOS IN - DIRECTOS
/////////////////////////////////////////////

/**
 * 
 * Agrego un indicador Economico
 * 
 * @param {Object} indFinanciero    Objeto de tipo indicador Financiero
 * @returns {undefined}
 */
GestionIndicador.prototype.addIndBeneficiariosInDirecto = function(objBIH, objBIM, objBIT)
{
    this.indBIndirecto["0"] = objBIH;
    this.indBIndirecto["1"] = objBIM;
    this.indBIndirecto["2"] = objBIT;
}

/**
 * 
 * Verifico la existencia de indicadores Financieros registrados, 
 * retornando la cantidad de registros existentes en la lista de indicadores financieros
 * 
 * @returns {int}
 */
GestionIndicador.prototype.existenIndsBIndirectos = function()
{
    return this.indBIndirecto.length;
}


/**
 * 
 * Actualizo el umbral de los indicadores
 * 
 * @param {type} indTd  umbral de indicador Tasa de Descuento
 * @param {type} indVan umbral de indicador VAN
 * @param {type} indTir umbral de indicador TIR
 * 
 * @returns {undefined}
 */
GestionIndicador.prototype.updIndBIndirecto = function(valBIH, valBIM, valBIT)
{
    this.indBIndirecto["0"].umbral = valBIH;
    this.indBIndirecto["1"].umbral = valBIM;
    this.indBIndirecto["2"].umbral = valBIT;
}


/////////////////////////////////////////////
//  INDICADOR BENEFICIARIOS GAP
/////////////////////////////////////////////

/**
 * 
 * Gestiona el registro de un NUEVO indicador GAP
 * 
 * @param {objeto} masculino  informacion del indicador Masculino
 * @param {objeto} femenino   informacion del indicador femenino
 * @param {objeto} total      informacion del indicador total
 * 
 * @returns {undefined}
 */
GestionIndicador.prototype.addIndGAP = function( masculino, femenino, total)
{
    var indGap = [];

    indGap["idRegGap"] = this.lstGAP.length;
    indGap["gapMasculino"] = masculino;
    indGap["gapFemenino"] = femenino;
    indGap["gapTotal"] = total;

    this.lstGAP.push( indGap );

    return indGap;
}

/**
 * 
 * Verifico la existencia de un determinado tipo de indicador GAP
 * 
 * @param {int} idTpoGap    Identificador de tipo GAP
 * 
 * @returns {Boolean}
 * 
 */
GestionIndicador.prototype.existeIndGap = function(idTpoGap)
{
    var nrg = this.lstGAP.length;
    var ban = false;

    for (var x = 0; x < nrg; x++) {
        if (this.lstGAP[x].idTpoGap == idTpoGap) {
            ban = true;
        }
    }

    return ban;
}

/**
 * 
 * Gestino la eliminacion de un indicador GAP
 * 
 * @param {int} idRegGap   Identificador del registro GAP
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.delGap = function(idRegGap)
{
    var nrg = this.lstGAP.length;

    for (var x = 0; x < nrg; x++) {
        if (this.lstGAP[x].idRegGap == idRegGap) {
            //  En caso que sea un nuevo indicador, lo elimino fisicamente
            if (this.lstGAP[x].eiMasculino.idIndicador == 0) {
                this.lstGAP.splice( x, 1 );
                return false;
            } else {
                //  En caso que se un indicador ya existente lo eliminamos fisicamente
                //  actualizando el umbral de published a cero "0"
                this.lstGAP[x].gapMasculino.published = 0;
                this.lstGAP[x].gapFemenino.published = 0;
                this.lstGAP[x].gapTotal.published = 0;
            }
        }
    }
}

/**
* 
* Retorna una fila con informacion del indicador GAP
* 
* @param {String} tpoGap        Nombre del GAP
* @param {Object} dtaIndGap     Objeto con informacion del indicador registrado
* @param {int} ban              Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
*                                       1: Fila Editada -   NO incluye atributo TR de la tabla
* 
* @returns {undefined}
* 
*/
GestionIndicador.prototype.addFilaIndicadorGAP = function( dtaIndGap, ban )
{
        var ds;
        //  Construyo la Fila
        var fila = ( ban == 0 ) ? "<tr id='" + dtaIndGap.idRegGap + "'>" 
                                : "";
                                
        /////////////////////////
        //  GAP - MASCULINO
        /////////////////////////
        ds = this.getDtaSemaforizacion( dtaIndGap.gapMasculino.semaforoImagen() );
        fila += "       <td align='center'>" + dtaIndGap.gapMasculino.nombreIndicador + "</td>";
        fila += "       <td align='center'>" + dtaIndGap.gapMasculino.umbral + "</td>";
        fila += "       <td align='center'>"
             + "         <a id='aim-gap-" + dtaIndGap.idRegGap + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=" + dtaIndGap.gapMasculino.idIndicador + "&tpo=m&tpoIndicador=gap&idRegIndicador=" + dtaIndGap.idRegGap + "&tmpl=component&task=preview\", {size:{x: " + COM_CONVENIOS_POPUP_ANCHO + ", y: " + COM_CONVENIOS_POPUP_ALTO + " }, handler:\"iframe\"} );'>";
        fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >";
             + "         </a>"
             + "       </td>";
               
        /////////////////////////
        //  GAP - FEMENINO
        /////////////////////////
        ds = this.getDtaSemaforizacion( dtaIndGap.gapMasculino.semaforoImagen() );
        fila += "       <td align='center'>" + dtaIndGap.gapFemenino.umbral + "</td>";
        fila += "       <td align='center'>"
             + "            <a id='aif-gap-" + dtaIndGap.idRegGap + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=" + dtaIndGap.gapFemenino.idIndicador + "&tpo=f&tpoIndicador=gap&idRegIndicador=" + dtaIndGap.idRegGap + "&tmpl=component&task=preview\", {size:{x:" + COM_CONVENIOS_POPUP_ANCHO + ", y: " + COM_CONVENIOS_POPUP_ALTO + "}, handler:\"iframe\"} );'>";
        fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >";
             + "            </a>"
             + "     </td>"; 

        /////////////////////////
        //  GAP - TOTAL
        /////////////////////////
        ds = this.getDtaSemaforizacion( dtaIndGap.gapTotal.semaforoImagen() );
        fila += "       <td align='center'>" + dtaIndGap.gapTotal.umbral + "</td>";
        fila += "       <td align='center'>"
             + "         <a id='ait-gap-" + dtaIndGap.idRegGap + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndGap.gapTotal.idIndicador +"&tpo=t&tpoIndicador=gap&idRegIndicador="+ dtaIndGap.idRegGap +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
        fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >";
             + "         </a>"
             + "     </td>";

        fila += "       <td align='center'> <a class='updGAP'> Editar </a> </td>"
                + "     <td align='center'> <a class='delGAP'> Eliminar </a> </td>";
        
        fila += ( ban == 0 )? " </tr>"
                            : "";

        return fila;
    }




/////////////////////////////////////////////
//  INDICADOR ENFOQUE DE IGUALDAD
/////////////////////////////////////////////

/**
 * 
 * Gestiona el registro de un indicador de Enfoque de Igualdad
 * 
 * @param {int} idTpoEnfoque   Identificador del tipo de Enfoque
 * @param {int} idEIgualdad    Identificador del Enfoque de Igualdad Seleccionado
 * @param {Object} masculino      Informacion del indicador Masculino
 * @param {Object} femenino       Informacion del indicador Femenino
 * @param {object} total          informacion del indicador Total
 * 
 * @returns {Array}
 */
GestionIndicador.prototype.addIndEIgualdad = function( masculino, femenino, total )
{
    var indEIgualdad = [];

    indEIgualdad["idRegEI"] = this.lstEnfIgualdad.length;
    indEIgualdad["eiMasculino"] = masculino;
    indEIgualdad["eiFemenino"] = femenino;
    indEIgualdad["eiTotal"] = total;

    this.lstEnfIgualdad.push( indEIgualdad );

    return indEIgualdad;
}

/**
 * 
 * Verifico la existencia de un Indicador de enfoque de Igualdad
 * 
 * @param {Int} idEIgualdad    Identificador del Tipo de Enfoque de Igualdad
 * 
 * @returns {Boolean}
 */
GestionIndicador.prototype.existeIndEIgualdad = function( idEIgualdad )
{
    var nrei = this.lstEnfIgualdad.length;
    var ban = false;

    for (var x = 0; x < nrei; x++) {
        if ( this.lstEnfIgualdad[x].idEI == idEIgualdad ) {
            ban = true;
        }
    }

    return ban;
}

/**
* 
* Retorna una fila con informacion del indicador GAP
* 
* @param {String} tpoGap       Nombre del GAP
* @param {Object} dtaIndGap    Objeto con informacion del indicador registrado
* @param {int} ban              Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
*                                       1: Fila Editada -   NO incluye atributo TR de la tabla
*
* @returns {undefined}
* 
*/
GestionIndicador.prototype.addFilaIndEIgualdad = function( tpoEnfoque, EIgualdad, dtaIndEIgualdad, ban )
{
    var ds;
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='" + dtaIndEIgualdad.idRegEI + "'>"
                            : "";

    fila += "       <td align='center'>" + EIgualdad + "</td>"
            + "     <td align='center'>" + tpoEnfoque + "</td>";
    
    /////////////////////////
    //  EI - MASCULINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEIgualdad.eiMasculino.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEIgualdad.eiMasculino.umbral + "</td>";
    fila += "       <td align='center'>"
         + "         <a id='aim-ei-" + dtaIndEIgualdad.idRegEI + "' onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEIgualdad.eiMasculino.idIndicador +"&tpo=m&tpoIndicador=ei&idRegIndicador="+ dtaIndEIgualdad.idRegEI +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"'>"
         + "         </a>"
         + "     </td>";
    
    /////////////////////////
    //  EI - FEMENINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEIgualdad.eiFemenino.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEIgualdad.eiFemenino.umbral + "</td>";
    fila += "       <td align='center'>"
         + "         <a id='aif-ei-" + dtaIndEIgualdad.idRegEI + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEIgualdad.eiFemenino.idIndicador +"&tpo=f&tpoIndicador=ei&idRegIndicador="+ dtaIndEIgualdad.idRegEI +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
         + "         </a>"
         + "     </td>";

    /////////////////////////
    //  EI - TOTAL
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEIgualdad.eiTotal.semaforoImagen() );    
    fila += "       <td align='center'>" + dtaIndEIgualdad.eiTotal.umbral + "</td>";
    fila += "       <td align='center'>"
         + "       <a id='ait-ei-" + dtaIndEIgualdad.idRegEI + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEIgualdad.eiTotal.idIndicador +"&tpo=t&tpoIndicador=ei&idRegIndicador="+ dtaIndEIgualdad.idRegEI +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
         + "           </a>"
    fila += "       </td>";
    
    fila += "       <td align='center'> <a class='updEI'> Editar </a> </td>"
            + "     <td align='center'> <a class='delEI'> Eliminar </a> </td>";
    
    fila += ( ban == 0 )? "</tr>" 
                        : "";

    return fila;
}

/**
 * 
 * Gestino la eliminacion de un indicador Enfoque de Igualdad
 * 
 * @param {int} idRegGap   Identificador del registro GAP
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.delEIgualdad = function( idRegEI )
{
    var nrg = this.lstEnfIgualdad.length;

    for (var x = 0; x < nrg; x++) {
        if ( this.lstEnfIgualdad[x].idRegEI == idRegEI ) {
            //  En caso que sea un nuevo indicador, lo elimino fisicamente
            if ( this.lstEnfIgualdad[x].eiMasculino.idIndicador == 0 ){
                this.lstEnfIgualdad.splice( x, 1 );
                return false;
            } else {
                //  En caso que se un indicador ya existente lo eliminamos fisicamente
                //  actualizando el umbral de published a cero "0"
                this.lstEnfIgualdad[x].eiMasculino.published = 0;
                this.lstEnfIgualdad[x].eiFemenino.published = 0;
                this.lstEnfIgualdad[x].eiTotal.published = 0;
            }
        }
    }
}


/////////////////////////////////////////////
//  INDICADOR BENEFICIARIOS ENFOQUE ECORAE
/////////////////////////////////////////////

/**
 * 
 * Gestiona el registro de un NUEVO indicador Enfoque Ecorae
 * 
 * @param {objeto} masculino  informacion del indicador Masculino
 * @param {objeto} femenino   informacion del indicador femenino
 * @param {objeto} total      informacion del indicador total
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.addIndEEcorae = function( masculino, femenino, total )
{
    var indEE = [];

    indEE["idRegEE"] = this.lstEnfEcorae.length;
    indEE["eeMasculino"] = masculino;
    indEE["eeFemenino"] = femenino;
    indEE["eeTotal"] = total;

    this.lstEnfEcorae.push( indEE );

    return indEE;
}

/**
 * 
 * Verifico la existencia de un determinado tipo de indicador GAP
 * 
 * @param {int} idTpoGap    Identificador de tipo GAP
 * 
 * @returns {Boolean}
 * 
 */
GestionIndicador.prototype.existeIndEEcorae = function( EE )
{
    var nree = this.lstEnfEcorae.length;
    var ban = false;

    for (var x = 0; x < nree; x++) {
        if ( this.lstEnfEcorae[x].eeMasculino.toString() == EE.toString() ) {
            ban = true;
        }
    }

    return ban;
}

/**
 * 
 * Retorna una Fila con informacion del indicador Enfoque Ecorae
 * 
 * @param {String} EEcorae          Nombre del Enfoque Ecorae
 * @param {Object} dtaIndEEcorae    Objeto con informacion de la fila a registrar
 * @param {int} ban                 Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
 *                                          1: Fila Editada -   NO incluye atributo TR de la tabla
 *                                       
 * @returns {String}
 * 
 */
GestionIndicador.prototype.addFilaIndEEcorae = function( EEcorae, dtaIndEEcorae, ban )
{
    var ds;
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='" + dtaIndEEcorae.idRegEE + "'>" 
                            : "";

    fila += "       <td align='center'>" + EEcorae + "</td>";
    
    /////////////////////////
    //  EE - MASCULINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEEcorae.eeMasculino.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEEcorae.eeMasculino.umbral + "</td>";
    fila += "       <td align='center'>"
         + "            <a id='aim-ee-" + dtaIndEEcorae.idRegEE + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEEcorae.eeMasculino.idIndicador +"&tpo=m&tpoIndicador=ee&idRegIndicador="+ dtaIndEEcorae.idRegEE +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
         + "            </a>"
         + "        </td>";

    /////////////////////////
    //  EE - FEMENINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEEcorae.eeFemenino.semaforoImagen() );
    fila += "     <td align='center'>" + dtaIndEEcorae.eeFemenino.umbral + "</td>";
    fila += "       <td align='center'>"
         + "         <a id='aif-ee-" + dtaIndEEcorae.idRegEE + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEEcorae.eeFemenino.idIndicador +"&tpo=f&tpoIndicador=ee&idRegIndicador="+ dtaIndEEcorae.idRegEE +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
         + "         </a>"
         + "     </td>";

    /////////////////////////
    //  EE - TOTAL
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEEcorae.eeTotal.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEEcorae.eeTotal.umbral + "</td>";
    fila += "       <td align='center'>"
         + "         <a id='ait-ee-" + dtaIndEEcorae.idRegEE + "'onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEEcorae.eeTotal.idIndicador +"&tpo=t&tpoIndicador=ee&idRegIndicador="+ dtaIndEEcorae.idRegEE +"&tmpl=component&task=preview\", {size:{x:"+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
         + "         </a>"
         + "     </td>";
    
    fila += "       <td align='center'> <a class='updEE'> Editar </a> </td>"
            + "     <td align='center'> <a class='delEE'> Eliminar </a> </td>";

    fila += ( ban == 0 )? "</tr>" 
                        : "";

    return fila;
}

/**
 * 
 * Gestino la eliminacion de un indicador Enfoque de Igualdad
 * 
 * @param {int} idRegGap   Identificador del registro GAP
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.delIndEEcorae = function( idRegEE )
{
    var nrg = this.lstEnfEcorae.length;

    for (var x = 0; x < nrg; x++) {
        if ( this.lstEnfEcorae[x].idRegEE == idRegEE ) {
            //  En caso que sea un nuevo indicador, lo elimino fisicamente
            if ( this.lstEnfEcorae[x].eeMasculino.idIndicador == 0 ){
                this.lstEnfEcorae.splice( x, 1 );
                break;
            } else {
                //  En caso que se un indicador ya existente lo eliminamos fisicamente
                //  actualizando el umbral de published a cero "0"
                this.lstEnfEcorae[x].eeMasculino.published = 0;
                this.lstEnfEcorae[x].eeFemenino.published = 0;
                this.lstEnfEcorae[x].eeTotal.published = 0;
                
                break;
            }
        }
    }
}


/////////////////////////////////////////////
//  GESTION OTROS INDICADORES
/////////////////////////////////////////////

GestionIndicador.prototype.addOtroIndicador = function( objOtroInd )
{
    this.lstOtrosIndicadores.push( objOtroInd );
}


GestionIndicador.prototype.existeOtroIndicador = function( objOtroInd )
{
    var nroi = this.lstOtrosIndicadores.length;
    var ban = 0;
    
    for( var x = 0; x < nroi; x++ ){
        if( this.lstOtrosIndicadores[x].toString() == objOtroInd.toString() ){
            ban = 1;
        }
    }
    
    return ban;
}

/**
 * 
 * Agrega una fila a la tabla Otros Indicadores
 * 
 * @param {Object} objOtroInd   Objeto con informacion de Otros Indicadores
 * @param {String} undMedida    Unidad de Medida
 * @param {int} ban             Bandera 0: Nueva Fila   -   Incluye atributo TR de la tabla
 *                                      1: Fila Editada -   NO incluye atributo TR de la tabla
 *                                          
 * @returns {String}
 */
GestionIndicador.prototype.addFilaOtroIndicador = function( objOtroInd, ban )
{
    //  Construyo la Fila
    var fila = ( ban == 0 ) ? "<tr id='" + objOtroInd.idRegIndicador + "'>" 
                            : "";

    fila += "       <td align='center'> <img class='sprite-indicadores' id='ai-oi-" + objOtroInd.idRegIndicador + "' title='" + COM_CONVENIOS_TITLE_ATRIBUTO + "' > </td>"
            + "     <td align='center'>" + objOtroInd.nombreIndicador + "</td>"
            + "     <td align='center'>" + objOtroInd.descripcion + "</td>"
            + "     <td align='center'>" + objOtroInd.umbral + ' / ' + objOtroInd.undMedida + "</td>"
            + "     <td align='center'>" + objOtroInd.formula + "</td>";
    
    fila += "       <td align='center'>";
    fila += "           <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=0&tpoIndicador=oi&idRegIndicador="+ objOtroInd.idRegIndicador +"&tmpl=component&task=preview\", {size:{x: "+ COM_CONVENIOS_POPUP_ANCHO +", y: "+ COM_CONVENIOS_POPUP_ALTO +"}, handler:\"iframe\"} );'> Editar </a>";
    fila += "       </td>";
    
    fila += "       <td align='center'> <a class='delOI'> Eliminar </a> </td>";
    
    fila += ( ban == 0 ) ? "   </tr>" 
                         : "";

    return fila;
}

//
//  Actualizo informacion de una determinada fila de la tabla GAP
//
GestionIndicador.prototype.updInfoFilaOI = function( idFila, dataUpd )
{
    jQuery( '#lstOtrosInd tr' ).each( function(){
        if( jQuery( this ).attr( 'id' ) == idFila ){
            jQuery( this ).html( dataUpd );
        }
    })
}


GestionIndicador.prototype.getDtaSemaforizacion = function( banIndicador ){
    var dtaSemaforizacion = new Array();

        switch( banIndicador ){
            //  Rojo
            case 0: 
                dtaSemaforizacion["imgAtributo"] = COM_CONVENIOS_RG_ATRIBUTO;
                dtaSemaforizacion["msgAtributo"] = COM_CONVENIOS_TITLE_RG_ATRIBUTO;
            break;

            //  Amarillo
            case 1: 
                dtaSemaforizacion["imgAtributo"]= COM_CONVENIOS_AM_ATRIBUTO;
                dtaSemaforizacion["msgAtributo"]= COM_CONVENIOS_TITLE_AM_ATRIBUTO;
                dtaSemaforizacion["msgStyle"]   = COM_CONVENIOS_STYLE_AM;
                
            break;

            //  Verde
            case 2: 
                dtaSemaforizacion["imgAtributo"]= COM_CONVENIOS_VD_ATRIBUTO;
                dtaSemaforizacion["msgAtributo"]= COM_CONVENIOS_TITLE_VD_ATRIBUTO;
            break;
        }
        
        return dtaSemaforizacion;
};