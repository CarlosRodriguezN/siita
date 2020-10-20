var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function () {

    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;

    //  Carga la data de los planes PPPP y PAPP
    loadDtaPPPP();
    loadDtaPAPP();

    /**
     *  Clase que carga los objetivop de un Plan PPPP/PAPP
     */
    jQuery(".loadObjetivosPPPP").live("click", function () {
        var idPlan = jQuery(this).attr('id');
        var idRegPlan = parseInt(idPlan.toString().split('-')[1]);

        jQuery("#regPPPP").val(idRegPlan);
        cargarObjetivos(idRegPlan, 3);
    });

    /**
     *  Clase que carga los objetivop de un Plan PPPP/PAPP
     */
    jQuery(".loadObjetivosPAPP").live("click", function () {
        var idPlan = jQuery(this).attr('id');
        var idRegPlan = parseInt(idPlan.toString().split('-')[1]);
        jQuery("#regPAPP").val(idRegPlan);
        cargarObjetivos(idRegPlan, 4);
    });

    /**
     *  Carga la informacion en la tablas de un los PPPPs
     * @returns {undefined}
     */
    function loadDtaPPPP()
    {
        //  Carga la programacion pluriananual, 3 id de tipo de plan PPPP
        if (oLstPPPPs.lstPppp.length > 0) {
            loadProgramacionPln(3);
        } else {
            jQuery("#srPppp").css('display', 'block')
        }

        //  Carga los objetivos del PPPP vigente
        if (jQuery("#regPPPP").val() != '') {
            cargarObjetivos(jQuery("#regPPPP").val(), 3);
        } else {
            jQuery("#srObjPppp").css('display', 'block')
        }

    }

    /**
     *  Carga la informacion en la tablas de un los PAPPs
     * @returns {undefined}
     */
    function loadDtaPAPP()
    {
        //  Carga la programacion ananual, 4 id de tipo de plan PAPP
        if (oLstPAPPs.lstPapp.length > 0) {
            loadProgramacionPln(4);
        } else {
            jQuery("#srPapp").css('display', 'block')
        }

        //  Carga los objetivos del PAPP vigente
        if (jQuery("#regPAPP").val() != '') {
            cargarObjetivos(jQuery("#regPAPP").val(), 4);
        } else {
            jQuery("#srObjPapp").css('display', 'block');
        }
    }

    /**
     *  Carga la programacion plurianual y anual de la politica publica en la vista
     * @param {type} tpoPln
     * @returns {undefined}
     */
    function loadProgramacionPln(tpoPln)
    {
        var lstPlanes = new Array();
        var tabla = ''
        if (tpoPln == 3) {
            lstPlanes = oLstPPPPs.lstPppp;
            tabla = '#lstPPPPs';
        } else if (tpoPln == 4) {
            lstPlanes = oLstPAPPs.lstPapp;
            tabla = '#lstPAPPs';
        }

        for (var i = 0; i < lstPlanes.length; i++) {
            var fila = makeFilaPlnPrg(lstPlanes[i], tpoPln);
            //  Agrego la fila creada a la tabla
            jQuery(tabla + ' > tbody:last').append(fila);
        }

    }

    /**
     *  Carga los objetivos de un Plan PPPP/PAPP en la tabla de Objetivos
     * @param {type} idRegPln
     * @param {type} tabla
     * @returns {undefined}
     */
    function cargarObjetivos(idRegPln, tabla)
    {
        var idHidden = '';
        
        switch (tabla) {
            case 3:
                jQuery('#tbLstObjetivosPPPP > tbody').empty();
                var lstObjetivosPln = (oLstPPPPs.lstPppp[idRegPln] && typeof (oLstPPPPs.lstPppp[idRegPln].lstObjetivos) !== 'undefined')
                        ? oLstPPPPs.lstPppp[idRegPln].lstObjetivos
                        : [];
                idHidden = "#srObjPppp";
            break;

            case 4:
                jQuery('#tbLstObjetivosPAPP > tbody').empty();
                var lstObjetivosPln = (oLstPAPPs.lstPapp[idRegPln] && typeof (oLstPAPPs.lstPapp[idRegPln].lstObjetivos) !== 'undefined')
                        ? oLstPAPPs.lstPapp[idRegPln].lstObjetivos
                        : [];
                idHidden = "#srObjPapp";
            break;
        }
        
        if (lstObjetivosPln.length > 0) {

            jQuery(idHidden).css('display', 'none')
            for (var i = 0; i < lstObjetivosPln.length; i++) {
                jQuery( '#lyPPPP' ).html( getAnioVigencia( lstObjetivosPln[i].fchInicioPlan ) );

                //  Creo el Objeto Objetivo
                var objObjetivo = new Objetivo();
                objObjetivo.setDtaObjetivo( lstObjetivosPln[i] );

                agregarFilaPrg(idRegPln, objObjetivo, tabla);
                validateSemaforoObjetivo(i, tabla, idRegPln);
            }
        } else {
            jQuery(idHidden).css('display', 'block');
        }
    }

    function getAnioVigencia( fchInicioPlan )
    {
        var msgAnio = false;
    
        if( typeOf( fchInicioPlan ) !== "null" ){
            var fp = fchInicioPlan.split( '-' );
            var objFecha = new Date( parseInt( fp[0] ), parseInt( fp[1] ) - 1, parseInt( fp[2] ) );
            
            msgAnio = COM_PEI_FIELD_PLAN_OBJETIVOS_TITLE + objFecha.getFullYear() + '&nbsp;';
        }

        return msgAnio;
    }


    /**
     * cambia los colores de los semaforos segun el tipo
     * @param {type} reg
     * @param {type} tpoPln
     * @param {type} idRegPln
     * @returns {undefined}
     */
    function validateSemaforoObjetivo(reg, tpoPln, idRegPln)
    {
        semaforoIdicadorMeta(reg, tpoPln, idRegPln);
        semaforoAlineacion(reg, tpoPln, idRegPln);
        semaforoPlanAnccion(reg, tpoPln, idRegPln);
        semaforoIndicadoresObj(reg, tpoPln, idRegPln);
    }
    ;

    /**
     * Cambia el semaforo del indicador meta.
     * @param {type} reg
     * @param {type} tpoPln
     * @param {type} idRegPln
     * @returns {undefined}
     */
    function semaforoIdicadorMeta(reg, tpoPln, idRegPln)
    {
        var val = 2;
        var objData = getObjetivos(reg, tpoPln, idRegPln, 'IndMeta');
        var lstObjetivos = objData.lstObjetivos;
        var id = objData.id;

        if (lstObjetivos[reg] && lstObjetivos[reg].lstIndicadores) {
            var lstIndicadoreTmp = lstObjetivos[reg].lstIndicadores;
            var indicadorMeta = getIndMeta(lstIndicadoreTmp);
            if (indicadorMeta) {
                val = indicadorMeta.semaforoValor();
            }
        }

        changeColorSemaforo(id, val);
    }


    /**
     *  Cambia el semaforo de la alineacion del objetivo.
     * @param {type} reg
     * @param {type} tpoPln
     * @param {type} idRegPln
     * @returns {undefined}
     */
    function semaforoAlineacion(reg, tpoPln, idRegPln)
    {
        var objData = getObjetivos(reg, tpoPln, idRegPln, 'Aln');
        var lstObjetivos = objData.lstObjetivos;
        var id = objData.id;

        var lstAlin = lstObjetivos[reg].lstAlineaciones;
        var numReg = 0;

        for (var i = 0; i < lstAlin.length; i++) {
            if (lstAlin[i].published == 1) {
                numReg = ++numReg;
            }
        }

        var val = (numReg > 0) ? 3
                : 2;

        changeColorSemaforo(id, val);
    }

    /**
     *  Cambia el semaforo de los indicadores de un objetivo.
     * @param {type} reg
     * @param {type} tpoPln
     * @param {type} idRegPln
     * @returns {undefined}
     */
    function semaforoIndicadoresObj(reg, tpoPln, idRegPln)
    {
        var objData = getObjetivos(reg, tpoPln, idRegPln, 'Ind');
        var lstObjetivos = objData.lstObjetivos;
        var id = objData.id;

        var lstInd = lstObjetivos[reg].lstIndicadores;
        var numInd = 0;

        for (var i = 0; i < lstInd.length; i++) {
            if (lstInd[i].published === 1 && lstInd[i].idTpoIndicador !== 1) {
                numInd = ++numInd;
            }
        }

        var val = (numInd > 0) ? 3 : 2;

        changeColorSemaforo(id, val);
    }


    /**
     *  Cambia el semaforo del plan de accion del objetivo.
     * @param {type} reg
     * @param {type} tpoPln
     * @param {type} idRegPln
     * @returns {undefined}
     */
    function semaforoPlanAnccion(reg, tpoPln, idRegPln)
    {
        var objData     = getObjetivos(reg, tpoPln, idRegPln, 'Acc');
        var lstObjetivos= objData.lstObjetivos;
        var id          = objData.id;

        var plnAccion = lstObjetivos[reg].lstAcciones;
        var numReg = 0;

        for (var i = 0; i < plnAccion.length; i++) {
            if (plnAccion[i].published === 1) {
                numReg = ++numReg;
            }
        }

        var val = (numReg > 0) ? 3 : 2;
        changeColorSemaforo(id, val);
    }


    /**
     *  Cambia el color de los semaforos de las caracteristicas del objetivo
     * @param {type} id         id del registro del objetivo
     * @param {type} color      color del semaforo
     * @returns {undefined}
     */
    function changeColorSemaforo(id, color)
    {
        switch (color) {
            case 0:// amarillo
                jQuery(id).css('background-position', '0 0px');
                break;
            case 1:// blanco
                jQuery(id).css('background-position', '0 -66px');
                break;
            case 2:// rojo
                jQuery(id).css('background-position', '0 -132px');
                break;
            case 3:// verde
                jQuery(id).css('background-position', '0 -198px');
                break;
        }
    }

    /**
     *  Agrega una fila en la tablas de objetivos de un Pei
     * @param {int} idRegPln        Array con los atributos del objetivo.
     * @param {Object} obj     Array con los atributos del objetivo.
     * @param {int} tabla           Id de la tabla ha agregar una fila.
     * @returns {undefined}
     */
    function agregarFilaPrg(idRegPln, obj, tabla)
    {
        var topPln = tabla;
        var regPln = '';
        var tbObj = '';
        var tpoIndicador;
        var semaforoImg = '';
        var ds;

        if (topPln === 3) {
            regPln = jQuery('#regPPPP').val();
            tbObj = "#tbLstObjetivosPPPP";
            semaforoImg = 'sPppp';
            tpoIndicador= 'pppp';
        } else if (topPln === 4) {
            regPln = jQuery('#regPAPP').val();
            tbObj = "#tbLstObjetivosPAPP";
            semaforoImg = 'sPapp';

            tpoIndicador = 'papp';
        }

        var idReg = obj.registroObj;
        var fila = '';

        fila += '<tr id="' + idRegPln + '">';

        fila += '     <td align="left" style="vertical-align: middle;" >' + obj.descObjetivo + '</td>';
        fila += '     <td align="center" style="vertical-align: middle;" width="15px">' + obj.nmbPrioridadObj + '</td>';

        //  Alineacion estrategica ( PNBV / Agendas Sectoriales ) de un Objetivo Estrategico
        ds = getDtaSemaforizacionAlineacion(obj.semaforizacionAlineacion());
        fila += '     <td align="center" width="15px" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
        fila += '       <a id="' + idReg + '" onclick="SqueezeBox.fromElement( \'index.php?option=com_alineacion&view=estrategica&layout=edit&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
        fila += '           <img id="AL' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '"  style="padding-left: 15px;">';
        fila += '       </a>';
        fila += '     </td>';

        //  Estrategia ( accion ) del Objetivo
        ds = getDtaSemaforizacionAccion(obj.semaforizacionAccion());
        fila += '     <td id="' + idReg + '" align="center" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_accion&view=plnaccion&layout=edit&registroPln=' + regPln + '&tpoPln=' + topPln +'&registroObj=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_ANCHO + ',y:' + POPUP_ALTO + '}, handler:\'iframe\'} );">';
        fila += '           <img id="ACC' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">';
        fila += '        </a>';
        fila += '     </td>';

        //  Indicadores del Objetivo ( Meta, Intermedios, Apoyo, etc )
        ds = obj.semaforizacionInd();
        fila += '     <td id="' + idReg + '" align="center" style="padding-left: 10px;width: 20px; vertical-align: middle;" >';
        fila += '        <a onclick="SqueezeBox.fromElement( \'index.php?option=com_indicadores&view=indicadores&layout=edit&tpoIndicador='+ tpoIndicador +'&tpoPlan='+ topPln +'&idPlan='+ idRegPln +'&idRegObjetivo=' + idReg + '&tmpl=component&task=preview\', {size:{x:' + POPUP_IND_ANCHO + ',y:' + POPUP_IND_ALTO + '}, handler:\'iframe\'} );">';
        fila += '           <img id="IO' + idReg + '" src="' + ds["imgAtributo"] + '" title="' + ds["msgAtributo"] + '" style="' + ds["msgStyle"] + '">';
        fila += '        </a>';
        fila += '     </td>';

        fila += ' </tr>';

        //  Agrego la fila creada a la tabla
        jQuery(tbObj + ' > tbody:last').append(fila);
    }


    function getDtaSemaforizacionAlineacion(banIndicador)
    {
        var dtaSemaforizacion = new Array();

        switch (banIndicador) {
            //  Rojo
            case 0:
                dtaSemaforizacion["imgAtributo"] = COM_PEI_RG_SIN_ALINEACION;
                dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_RG_SIN_ALINEACION;
                break;

                //  Verde
            case 3:
                dtaSemaforizacion["imgAtributo"] = COM_PEI_VD_ALINEACION;
                dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_VD_ALINEACION;
                break;
        }

        return dtaSemaforizacion;
    }

    function getDtaSemaforizacionAccion(banIndicador)
    {
        var dtaSemaforizacion = new Array();

        switch (banIndicador) {
            //  Rojo
            case 0:
                dtaSemaforizacion["imgAtributo"] = COM_PEI_RG_SIN_ACCION;
                dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_RG_SIN_ACCION;
                break;

                //  Verde
            case 3:
                dtaSemaforizacion["imgAtributo"] = COM_PEI_RG_ACCION;
                dtaSemaforizacion["msgAtributo"] = COM_PEI_TITLE_RG_ACCION;
                break;
        }

        return dtaSemaforizacion;
    }


    /**
     *  Arma el html de una fila para los PPPP/PAPP
     * @param {type} PlnPrg
     * @param {type} tipo
     * @returns {String}
     */
    function makeFilaPlnPrg(PlnPrg, tipo)
    {
        var idReg = parseInt(PlnPrg.idRegPln);
        var fila = '';

        fila += '<tr class="row' + idReg % 2 + '">';
        fila += '   <td class="center" style = "width: 10px;">';
        fila += '       <div id="ingVigencia-' + PlnPrg.idPlan + '">';

        if (tipo == 3) {
            fila += '       <a href="javascript:vigenciaPppp(' + PlnPrg.idPlan + ', ' + PlnPrg.vigentePln + ')" >';
        } else if (tipo == 4) {
            fila += '       <a href="javascript:vigenciaPapp(' + PlnPrg.idPlan + ', ' + PlnPrg.vigentePln + ')" >';
        }

        fila += (PlnPrg.vigentePln == 0) ? '<img src = "media/system/images/siitaGestion/btnIndicadores/atributo/attrRojoSmall.png" title="PEI no vigente" >'
                : '<img src = "media/system/images/siitaGestion/btnIndicadores/atributo/attrVerdeSmall.png" title="PEI vigente" >';
        fila += '           </a>';
        fila += '       </div>';
        fila += '   </td>';
        fila += '   <td>';

        if (tipo == 3) {
            fila += '   <a href="#" class="loadObjetivosPPPP" id="regPPPP-' + idReg + '">';
        } else if (tipo == 4) {
            fila += '   <a href="#" class="loadObjetivosPAPP" id="regPAPP-' + idReg + '">';
        }

        fila += (PlnPrg.descripcionPln != '') ? PlnPrg.descripcionPln : 'Sin descripción';
        fila += '       </a>';
        fila += '   </td>';
        fila += '</tr>';

        return fila;
    }

});

