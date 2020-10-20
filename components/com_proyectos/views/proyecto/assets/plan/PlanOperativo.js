var PlanOperativo = function(){
    this.idRegPlan;
    this.idPlan = 0;
    this.descripcionPln;
    this.fchInicio;
    this.fchFin;
    this.idUGResponsable;
    
    this.planObjetivo = new Array();
}


PlanOperativo.prototype.toString = function()
{
    return this.descripcionPln;
}

PlanOperativo.prototype.setDtaPlan = function( dtaPlan )
{
    this.idRegPlan      = dtaPlan.idRegPlan;
    this.idPlan         = dtaPlan.idPlan;
    this.descripcionPln = dtaPlan.descripcionPln;
    this.fchInicio      = dtaPlan.fchInicio;
    this.fchFin         = dtaPlan.fchFin;
    this.idUGResponsable= dtaPlan.idUGResponsable;

    this.planObjetivo   = this.setDtaObjetivo( dtaPlan.lstObjetivos );
}


PlanOperativo.prototype.addFilaPlan = function( ban )
{
    //  Construyo la Fila
    var fila = ( ban === 0 )? "<tr id='"+ this.idRegPlan +"'>" 
                            : "";

    fila += "   <td align='left' style='vertical-align: middle;'>"+ this.descripcionPln +"</td>"
            + " <td align='center' style='width: 20px; vertical-align: middle;'>"+ this.getAlineacion() +"</td>"
            + " <td align='center' style='width: 20px; vertical-align: middle;'>"+ this.getAcciones() +"</td>"
            + " <td align='center' style='width: 20px; vertical-align: middle;'>"+ this.getIndicadores() +"</td>";

    fila += ( ban === 0 )   ? "</tr>" 
                            : "";

    return fila;
}



PlanOperativo.prototype.setDtaObjetivo = function( dtaObjetivo )
{
    var objPlanObjetivo = new PlanObjetivo();
    
    if( typeOf( dtaObjetivo ) === "object" ){
        objPlanObjetivo.setDataObjetivo( dtaObjetivo );
    }

    return objPlanObjetivo;
}



PlanOperativo.prototype.getAlineacion = function()
{
    var ds;
    var retval  = ' <a onclick="SqueezeBox.fromElement( \'index.php?option=com_alineacion&view=operativa&tpoEntidad=13&layout=edit&registroPoa=' + this.idRegPlan + '&registroObj=' + this.idRegPlan + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
    
    if( this.planObjetivo.lstAlineaciones.length ){
        ds = this.getDtaSemaforizacionAlineacion( 1 );
    }else{
        ds = this.getDtaSemaforizacionAlineacion( 0 );
    }

    retval += '     <img src="'+ ds["imgAtributo"] +'" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">';
    retval += ' </a>';
    
    return retval;
}


PlanOperativo.prototype.getDtaSemaforizacionAlineacion = function( banIndicador )
{
    var dtaSemaforizacion = new Array();

    switch( banIndicador ){
        //  Rojo
        case 0: 
            dtaSemaforizacion["imgAtributo"] = ALINEACION_RG_SIN_ACCION;
            dtaSemaforizacion["msgAtributo"] = ALINEACION_TITLE_RG_SIN_ACCION;
        break;

        //  Verde
        case 1:
            dtaSemaforizacion["imgAtributo"] = ALINEACION_VD_ACCION;
            dtaSemaforizacion["msgAtributo"] = ALINEACION_TITLE_VD_ACCION;
        break;
    }

    return dtaSemaforizacion;
}



PlanOperativo.prototype.getAcciones = function()
{
    var ds;
    var retval  = ' <a onclick="SqueezeBox.fromElement( \'index.php?option=com_accion&view=plnaccion&layout=edit&registroPln=' + this.idRegPlan + '&tpoPln=5&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
    
    if( this.planObjetivo.lstAcciones.length ){
        ds = this.getDtaSemaforizacionAccion( 1 );
    }else{
        ds = this.getDtaSemaforizacionAccion( 0 );
    }
    
    retval += '     <img src="'+ ds["imgAtributo"] +'" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">';
    retval += ' </a>';
    
    return retval;
}



PlanOperativo.prototype.getDtaSemaforizacionAccion = function( banIndicador )
{
    var dtaSemaforizacion = new Array();

    switch( banIndicador ){
        //  Rojo
        case 0: 
            dtaSemaforizacion["imgAtributo"] = ACCION_RG_SIN_ACCION;
            dtaSemaforizacion["msgAtributo"] = ACCION_TITLE_RG_SIN_ACCION;
        break;

        //  Verde
        case 1: 
            dtaSemaforizacion["imgAtributo"] = ACCION_VD_ACCION;
            dtaSemaforizacion["msgAtributo"] = ACCION_TITLE_VD_ACCION;
        break;
    }

    return dtaSemaforizacion;
}



PlanOperativo.prototype.getIndicadores = function()
{
    var ds;
    var retval  = ' <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicadores&layout=edit&tpoIndicador=poao&idPlan='+ this.idRegPlan +'&tmpl=component&task=preview\', {size:{x:' + POPUP_IND_ANCHO + ',y:' + POPUP_IND_ALTO + '}, handler:\'iframe\'} );">';    
    
    if( this.planObjetivo.lstIndicadores.length ){
        ds = this.getDtaSemaforizacionIndicador( 2 );
    }else{
        ds = this.getDtaSemaforizacionIndicador( 0 );
    }
    
    retval += '     <img src="'+ ds["imgAtributo"] +'" title="'+ ds["msgAtributo"] +'" style="'+ ds["msgStyle"] +'">';
    retval += ' </a>';
    
    return retval;
}


PlanOperativo.prototype.getDtaSemaforizacionIndicador = function( banIndicador )
{
    var dtaSemaforizacion = new Array();

    switch( banIndicador ){
        //  Rojo
        case 0: 
            dtaSemaforizacion["imgAtributo"] = INDICADOR_RG_ATRIBUTO;
            dtaSemaforizacion["msgAtributo"] = INDICADOR_TITLE_RG_ATRIBUTO;
        break;

        //  Amarillo
        case 1: 
            dtaSemaforizacion["imgAtributo"]= INDICADOR_AM_ATRIBUTO;
            dtaSemaforizacion["msgAtributo"]= INDICADOR_TITLE_AM_ATRIBUTO;
            dtaSemaforizacion["msgStyle"]   = INDICADOR_STYLE_AM ;
        break;

        //  Verde
        case 2: 
            dtaSemaforizacion["imgAtributo"] = INDICADOR_VD_ATRIBUTO;
            dtaSemaforizacion["msgAtributo"] = INDICADOR_TITLE_VD_ATRIBUTO;
        break;
    }

    return dtaSemaforizacion;
}