var GestionIndicador = function() {

    this.dtaInd = ( jQuery( '#jform_dataIndicadores' ).val() !== "" )   ? eval("(" + jQuery( '#jform_dataIndicadores' ).val() + ")")
                                                                        : {};

    //  Indicador Meta Economico
    this.ime;

    this.ecoTD = new Indicador();
    this.ecoVAN = new Indicador();
    this.ecoTIR = new Indicador();

    //  Seteo Informacion de Indicadores Fijos - Economicos
    this.setDtaIndEconomicos();
    this.indEconomico   = new Array( this.ecoTD, this.ecoVAN, this.ecoTIR );

    this.finTD = new Indicador();
    this.finVAN = new Indicador();
    this.finTIR = new Indicador();

    //  Seteo Informacion de Indicadores Fijos - Financieros
    this.setDtaIndFinancieros();
    this.indFinanciero  = new Array( this.finTD, this.finVAN, this.finTIR );

    this.bdh= new Indicador();
    this.bdm= new Indicador();
    this.bd = new Indicador();
    
    //  Seteo Informacion de Indicadores Fijos - Beneficiarios Directo
    this.setDtaBDirectos();
    this.indBDirecto    = new Array( this.bdh, this.bdm, this.bd );
    
    this.bih= new Indicador();
    this.bim= new Indicador();
    this.bi = new Indicador();

    //  Seteo Informacion de Indicadores Fijos - Beneficiarios Indirectos
    this.setDtaBIndirectos();
    this.indBIndirecto  = new Array( this.bih, this.bim, this.bi );

    this.lstGAP             = new Array();
    this.setDtaGAP();
    
    this.lstEnfIgualdad     = new Array();
    this.setDtaEnfIgualdad();
    
    this.lstEnfEcorae       = new Array();
    this.setDtaEnfEcorae();
    
    this.lstOtrosIndicadores= new Array();
    this.setDtaOtrosIndicadores();
    
    this.ds;
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
GestionIndicador.prototype.addIndEconomico = function( indTd, indVan, indTir )
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
    this.indEconomico["0"].umbral = ( isNaN( indTd ) )  ? 0 
                                                        : indTd;

    this.indEconomico["1"].umbral = ( isNaN( indVan ) ) ? 0 
                                                        : indVan;
                                                        
    this.indEconomico["2"].umbral = ( isNaN( indTir ) ) ? 0
                                                        : indTir;
}

/**
 * 
 * Seteo informacion de Indicadores  Fijos - Economicos 
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.setDtaIndEconomicos = function()
{
    if( typeOf( this.dtaInd.indEconomicos ) !== "null" ){
        var nrie = this.dtaInd.indEconomicos.length;
        
        for( var x = 0; x < nrie; x++ ){
            switch( this.dtaInd.indEconomicos[x].modeloIndicador ){
                case 'td': 
                    this.ecoTD.setDtaIndicador(  this.dtaInd.indEconomicos[x] );
                break;
                
                case 'van': 
                    this.ecoVAN.setDtaIndicador( this.dtaInd.indEconomicos[x] );
                break;
                
                case 'tir': 
                    this.ecoTIR.setDtaIndicador( this.dtaInd.indEconomicos[x] );
                break;                
            }
        }

    }
    
    return;
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
 * Seteo informacion de Indicadores Fijos - Financieros
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.setDtaIndFinancieros = function()
{
    if( typeOf( this.dtaInd.indFinancieros ) !== "null" ){

        var nrie = this.dtaInd.indFinancieros.length;
        
        for( var x = 0; x < nrie; x++ ){
            switch( this.dtaInd.indFinancieros[x].modeloIndicador ){
                case 'td': 
                    this.finTD.setDtaIndicador(  this.dtaInd.indFinancieros[x] );
                break;
                
                case 'van': 
                    this.finVAN.setDtaIndicador( this.dtaInd.indFinancieros[x] );
                break;
                
                case 'tir': 
                    this.finTIR.setDtaIndicador( this.dtaInd.indFinancieros[x] );
                break;                
            }
        }

    }
    
    return;
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
    this.indFinanciero["0"].umbral = ( isNaN( indTd ) ) ? 0 
                                                        : indTd;
                                                        
    this.indFinanciero["1"].umbral = ( isNaN( indVan ) )? 0
                                                        : indVan;
    
    this.indFinanciero["2"].umbral = ( isNaN( indTir ) )? 0
                                                        : indTir;
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
 * Seteo informacion de Indicadores  Fijos - Beneficiarios Directos 
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.setDtaBDirectos = function()
{
    if( typeOf( this.dtaInd.indBDirectos ) !== "null" ){
        var nrbi = this.dtaInd.indBDirectos.length;
        
        for( var x = 0; x < nrbi; x++ ){
            switch( this.dtaInd.indBDirectos[x].modeloIndicador ){
                case 'b': 
                    this.bd.setDtaIndicador( this.dtaInd.indBDirectos[x] );
                break;
                
                case 'bh': 
                    this.bdh.setDtaIndicador( this.dtaInd.indBDirectos[x] );
                break;
                
                case 'bm': 
                    this.bdm.setDtaIndicador( this.dtaInd.indBDirectos[x] );
                break;                
            }
        }
    }

    return;
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
    this.indBDirecto["0"].umbral = ( isNaN( valBDH ) )  ? 0 
                                                        : valBDH;

    this.indBDirecto["1"].umbral = ( isNaN( valBDM ) )  ? 0 
                                                        : valBDM;

    this.indBDirecto["2"].umbral = ( isNaN( valBDT ) )  ? 0 
                                                        : valBDT;
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
 * Seteo informacion de Indicadores  Fijos - Beneficiarios Indirectos 
 * 
 * @returns {undefined}
 * 
 */
GestionIndicador.prototype.setDtaBIndirectos = function()
{
    if( typeOf( this.dtaInd.indBIndirectos ) !== "null" ){
        var nrbi = this.dtaInd.indBIndirectos.length;
        
        for( var x = 0; x < nrbi; x++ ){
            switch( this.dtaInd.indBIndirectos[x].modeloIndicador ){
                case 'b': 
                    this.bi.setDtaIndicador( this.dtaInd.indBIndirectos[x] );
                break;
                
                case 'bh': 
                    this.bih.setDtaIndicador( this.dtaInd.indBIndirectos[x] );
                break;
                
                case 'bm': 
                    this.bim.setDtaIndicador( this.dtaInd.indBIndirectos[x] );
                break;                
            }
        }
    }

    return;
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
    this.indBIndirecto["0"].umbral = ( isNaN( valBIH ) )? 0
                                                        : valBIH;

    this.indBIndirecto["1"].umbral = ( isNaN( valBIM ) )? 0
                                                        : valBIM;

    this.indBIndirecto["2"].umbral = ( isNaN( valBIT ) )? 0
                                                        : valBIT;
}


/////////////////////////////////////////////
//  INDICADOR BENEFICIARIOS GAP
/////////////////////////////////////////////


GestionIndicador.prototype.setDtaGAP = function()
{
    var nrgap = this.dtaInd.lstGAP.length;
    
    if( nrgap ){
        for( var x = 0; x < nrgap; x++ ){
            var indGap = [];

            indGap["idRegGap"] = x;
                
            var idGAPM = new Indicador();
            idGAPM.setDtaIndicador( this.dtaInd.lstGAP[x].gapMasculino );

            var idGAPF = new Indicador();
            idGAPF.setDtaIndicador( this.dtaInd.lstGAP[x].gapFemenino );

            var idGAPT = new Indicador();
            idGAPT.setDtaIndicador( this.dtaInd.lstGAP[x].gapTotal );

            indGap["gapMasculino"]  = idGAPM;
            indGap["gapFemenino"]   = idGAPF;
            indGap["gapTotal"]      = idGAPT;

            this.lstGAP.push( indGap );            
        }
    }
    
    return false;
}



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
            + "         <a id='aim-gap-" + dtaIndGap.idRegGap + "' onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndGap.gapMasculino.idIndicador +"&tpo=m&tpoIndicador=gap&idRegIndicador="+ dtaIndGap.idRegGap +"&tmpl=component&task=preview\", {size:{x: "+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +" }, handler:\"iframe\"} );'>"
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >";
            + "         </a>"
            + "     </td>";
        
    /////////////////////////
    //  GAP - FEMENINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndGap.gapFemenino.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndGap.gapFemenino.umbral + "</td>";
    fila += "       <td align='center'>"
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndGap.gapFemenino.idIndicador +"&tpo=f&tpoIndicador=gap&idRegIndicador="+ dtaIndGap.idRegGap +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>"        
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >";
            + "         </a>"
            + "     </td>";
        
    /////////////////////////
    //  GAP - TOTAL
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndGap.gapTotal.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndGap.gapTotal.umbral + "</td>";
    fila += "       <td align='center'>"
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndGap.gapTotal.idIndicador +"&tpo=t&tpoIndicador=gap&idRegIndicador="+ dtaIndGap.idRegGap +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>"
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

GestionIndicador.prototype.setDtaEnfIgualdad = function()
{
    var nrlei = this.dtaInd.lstEnfIgualdad.length;
    
    if( nrlei ){
        for( var x = 0; x < nrlei; x++ ){
            var indEI = [];

            var idEIM = new Indicador();
            idEIM.setDtaIndicador( this.dtaInd.lstEnfIgualdad[x].eiMasculino );

            var idEIF = new Indicador();
            idEIF.setDtaIndicador( this.dtaInd.lstEnfIgualdad[x].eiFemenino );

            var idEIT = new Indicador();
            idEIT.setDtaIndicador( this.dtaInd.lstEnfIgualdad[x].eiTotal );

            indEI["idRegEI"] = x;
            indEI["eiMasculino"]= idEIM;
            indEI["eiFemenino"] = idEIF;
            indEI["eiTotal"]    = idEIT;

            this.lstEnfIgualdad.push( indEI );
        }
    }

    return;
}

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
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEIgualdad.eiMasculino.idIndicador +"&tpo=m&tpoIndicador=ei&idRegIndicador="+ dtaIndEIgualdad.idRegEI +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"'>"
            + "         </a>"
            + "     </td>";
    
    /////////////////////////
    //  EI - FEMENINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEIgualdad.eiFemenino.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEIgualdad.eiFemenino.umbral + "</td>";
    fila += "       <td align='center'>"
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEIgualdad.eiFemenino.idIndicador +"&tpo=f&tpoIndicador=ei&idRegIndicador="+ dtaIndEIgualdad.idRegEI +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
            + "         </a>"
            + "     </td>";

    /////////////////////////
    //  EI - TOTAL
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEIgualdad.eiTotal.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEIgualdad.eiTotal.umbral + "</td>";
    fila += "       <td align='center'>"
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEIgualdad.eiTotal.idIndicador +"&tpo=t&tpoIndicador=ei&idRegIndicador="+ dtaIndEIgualdad.idRegEI +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
            + "         </a>"
            + "     </td>";
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

GestionIndicador.prototype.setDtaEnfEcorae = function()
{
    for( var x = 0; x < this.dtaInd.lstEnfEcorae.length; x++ ){
        var indEE = [];
        
        var idEEM = new Indicador();
        idEEM.setDtaIndicador( this.dtaInd.lstEnfEcorae[x].eeMasculino );

        var idEEF = new Indicador();
        idEEF.setDtaIndicador( this.dtaInd.lstEnfEcorae[x].eeFemenino );

        var idEET = new Indicador();
        idEET.setDtaIndicador( this.dtaInd.lstEnfEcorae[x].eeTotal );

        indEE["idRegEE"]    = x;
        indEE["eeMasculino"]= idEEM;
        indEE["eeFemenino"] = idEEF;
        indEE["eeTotal"]    = idEET;

        this.lstEnfEcorae.push( indEE );
    }
    
    return;
}

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
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEEcorae.eeMasculino.idIndicador +"&tpo=m&tpoIndicador=ee&idRegIndicador="+ dtaIndEEcorae.idRegEE +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
            + "         </a>"
            + "     </td>";

    /////////////////////////
    //  EE - FEMENINO
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEEcorae.eeFemenino.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEEcorae.eeFemenino.umbral + "</td>";
    fila += "       <td align='center'>"
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEEcorae.eeFemenino.idIndicador +"&tpo=f&tpoIndicador=ee&idRegIndicador="+ dtaIndEEcorae.idRegEE +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
    fila += "               <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"' >"
            + "         </a>"
            + "     </td>";

    /////////////////////////
    //  EE - TOTAL
    /////////////////////////
    ds = this.getDtaSemaforizacion( dtaIndEEcorae.eeTotal.semaforoImagen() );
    fila += "       <td align='center'>" + dtaIndEEcorae.eeTotal.umbral + "</td>";
    fila += "       <td align='center'>"
            + "         <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador="+ dtaIndEEcorae.eeTotal.idIndicador +"&tpo=t&tpoIndicador=ee&idRegIndicador="+ dtaIndEEcorae.idRegEE +"&tmpl=component&task=preview\", {size:{x:"+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'>";
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


GestionIndicador.prototype.setDtaOtrosIndicadores = function()
{
    var nroi = this.dtaInd.lstOtrosIndicadores.length;
    
    if( nroi ){
        for( var x = 0; x < nroi; x++ ){
            //  Creo Objeto Indicador
            var indicador = new Indicador();
            this.dtaInd.lstOtrosIndicadores[x].idRegIndicador = x;

            //  Seteo los valores correspondientes al indicador
            indicador.setDtaIndicador( this.dtaInd.lstOtrosIndicadores[x] );

            //  Asigno a la lista de OTROS Indicadores
            this.dtaInd.lstOtrosIndicadores[x] = indicador;

            this.lstOtrosIndicadores.push( indicador );
        }
    }

    return;
}



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

    var ds = this.getDtaSemaforizacion( objOtroInd.semaforoImagen() );

    fila += "       <td align='center'> <img src='"+ ds["imgAtributo"] +"' title='"+ ds["msgAtributo"] +"' style='"+ ds["msgStyle"] +"'> </td>"
            + "     <td align='center'>" + objOtroInd.nombreIndicador + "</td>"
            + "     <td align='center'>" + objOtroInd.descripcion + "</td>"
            + "     <td align='center'>" + objOtroInd.umbral + ' / ' + objOtroInd.undMedida + "</td>"
            + "     <td align='center'>" + objOtroInd.formula + "</td>";
    
    fila += "       <td align='center'>";
    fila += "           <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=tableu&layout=edit&idIndicador="+ objOtroInd.idIndicador +"&tmpl=component&task=preview\", {size:{x: "+ COM_PROGRAMA_POPUP_TABLEU_ANCHO +", y: "+ COM_PROGRAMA_POPUP_TABLEU_ALTO +"}, handler:\"iframe\"} );'> "+ COM_PROGRAMA_POPUP_TABLEU +" </a>";
    fila += "       </td>";
    
    fila += "       <td align='center'>";
    fila += "           <a onclick='SqueezeBox.fromElement( \"index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=0&tpoIndicador=oi&idRegIndicador="+ objOtroInd.idRegIndicador +"&tmpl=component&task=preview\", {size:{x: "+ COM_PROGRAMA_POPUP_ANCHO +", y: "+ COM_PROGRAMA_POPUP_ALTO +"}, handler:\"iframe\"} );'> Editar </a>";
    fila += "       </td>";
    
    fila += "       <td align='center'> <a class='delOI'> Eliminar </a> </td>";
    
    fila += ( ban == 0 )? "   </tr>" 
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
            dtaSemaforizacion["imgAtributo"] = COM_PROGRAMA_RG_ATRIBUTO;
            dtaSemaforizacion["msgAtributo"] = COM_PROGRAMA_TITLE_RG_ATRIBUTO;
        break;

        //  Amarillo
        case 1: 
            dtaSemaforizacion["imgAtributo"]= COM_PROGRAMA_AM_ATRIBUTO;
            dtaSemaforizacion["msgAtributo"]= COM_PROGRAMA_TITLE_AM_ATRIBUTO;
            dtaSemaforizacion["msgStyle"]   = COM_PROGRAMA_STYLE_AM ;
        break;

        //  Verde
        case 2: 
            dtaSemaforizacion["imgAtributo"] = COM_PROGRAMA_VD_ATRIBUTO;
            dtaSemaforizacion["msgAtributo"] = COM_PROGRAMA_TITLE_VD_ATRIBUTO;
        break;
    }

    return dtaSemaforizacion;
}