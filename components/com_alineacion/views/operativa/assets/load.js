oGestionAlineacion = new GestionAlineacion();

jQuery(document).ready(function() {
    itmesSelect();
    getLstObjetivosParent();
    
    //  Especifica el tamaño de los select
    jQuery("#jform_Alineacion").css({width: "385px"});
    jQuery("#jform_Objetivo").css({width: "385px"});

});

/**
 * carga el array segun el tipo
 * @returns {undefined}
 */
function itmesSelect()
{
    var tpoEntidad = parseInt(jQuery('#tpoEntidad').val());
    var items = new Array();

    switch (tpoEntidad) {
        case 7:// unidad Gestion
            items.push({id: 12, nombre: "ECORAE"});
        break;
        
        case 11:// funcionario
            items.push({id: 12, nombre: "ECORAE"});
            items.push({id: 7, nombre: "Unidad Gestion"});
        break;
        
        case 1:// Programa
            items.push({id: 12, nombre: "ECORAE"});
            items.push({id: 7, nombre: "Unidad Gestion"});
            items.push({id: 3, nombre: "Convenios"});
        break;
        
        case 2:// Proyecto
            items.push({id: 12, nombre: "ECORAE"});
            items.push({id: 7, nombre: "Unidad Gestion"});
            items.push({id: 1, nombre: "Programa"});
            items.push({id: 3, nombre: "Convenios"});
        break;
        
        case 3:// Proyecto
            items.push({id: 12, nombre: "ECORAE"});
            items.push({id: 7, nombre: "Unidad Gestion"});
            items.push({id: 1, nombre: "Programa"});
            items.push({id: 2, nombre: "Proyecto"});
            items.push({id: 3, nombre: "Convenios"});
        break;
    }

    var cad = '';
    cad += '';
    for (var j = 0; j < items.length; j++) {
        jQuery('#jform_Alineacion').append('<option value="' + items[j].id + '" >' + items[j].nombre + '</option>');
    }
}

/**
 * RECUPERA la lista desde la VISTA PADRE
 * @returns {undefined}
 */
function getLstObjetivosParent() {
    var tpoEntidad = parseInt(jQuery('#tpoEntidad').val());
    switch (tpoEntidad) {

        case 1:// programa
            getAlinFromPrograma();
        break;
            
        case 2:// contrato
            getAlinFromPrograma();
        break;
        
        case 3:// contrato
            getAlinFromPrograma();
        break;
        
        case 6:// convenio
        break;            
            
        case 7:// unidad Gestion
            getAlinFromUnidadGestion();
        break;

        case 11:// funcionario
            getAlinFromFuncionario();
        break;

        case 13:// funcionario
            getAlinFromPOAOperativo();
        break;
    }
}

{// 7 LOAD desde UNIDAD de GESTION
    /**
     * RECUPERA la lista de ALINEACIONES en la VENTANA POP POP
     * @returns {undefined}
     */
    function getAlinFromUnidadGestion() {
        var registroObj = parseInt(jQuery("#registroObj").val());
        var registroPoa = parseInt(jQuery("#registroPoa").val());

        var tmpLstAlin = getAlinUnidGestion(registroPoa, registroObj);
        loadLstObjASObjAln(tmpLstAlin);

    }
    /**
     * RECUPERO la lista del del objeto "objLstPoas" en la Unidad de Gestion
     * @param {int} registroPoa Identificador del poa
     * @param {int} registroObj Identificador del objetivo
     * @returns {Window.objLstPoas.lstObjetivos.lstAlineaciones|window.parent.objLstPoas.lstObjetivos.lstAlineaciones|Array}
     */

    function getAlinUnidGestion(registroPoa, registroObj) {
        var tmpLstAlin = new Array();
        for (var j = 0; j < window.parent.objLstPoas.lstPoas.length; j++) {
            if (window.parent.objLstPoas.lstPoas[j].idRegPoa == registroPoa) {
                for (var k = 0; k < window.parent.objLstPoas.lstPoas[j].lstObjetivos.length; k++) {
                    if (window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].registroObj == registroObj) {
                        tmpLstAlin = window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].lstAlineaciones;
                    }
                }
            }
        }
        return tmpLstAlin;
    }


    function loadFronParent() {
        var regObjetivo = jQuery("#idRegObjetivo").val();
        if (window.parent.objLstObjetivo) {
            for (var j = 0; j < window.parent.objLstObjetivo.lstObjetivos.length; j++) {
                if (window.parent.objLstObjetivo.lstObjetivos[j].registroObj == regObjetivo) {
                    var lstAlineaciones = window.parent.objLstObjetivo.lstObjetivos[j].lstAlineaciones;

                    if (lstAlineaciones) {
                        for (var k = 0; k < lstAlineaciones.length; k++) {
                            var oAlineacion = getAlineacion(lstAlineaciones[k]);
                            oGestionAlineacion.addAlineacion(oAlineacion);
                        }
                        oGestionAlineacion.reloadLstAlineacion();
                    }
                }
            }
        }
    }

}// 7 LOAD desde UNIDAD de GESTION



{   //  11 LOAD desde FUNCIONARIO
    function getAlinFromFuncionario() {
        var registroObj = parseInt(jQuery("#registroObj").val());
        var registroPoa = parseInt(jQuery("#registroPoa").val());

        var tmpLstAlin = getAlinLstFronFuncionario(registroPoa, registroObj);
        loadLstObjASObjAln(tmpLstAlin);
    }



    function getAlinLstFronFuncionario(registroPoa, regObjetivo) {
        var lstAlineaciones = new Array();
        if (window.parent.objLstPoas.lstPoas.length > 0) {
            for (var j = 0; j < window.parent.objLstPoas.lstPoas.length; j++) {
                if (window.parent.objLstPoas.lstPoas[j].idRegPoa == registroPoa) {

                    if (window.parent.objLstPoas.lstPoas[j].lstObjetivos.length > 0) {
                        for (var k = 0; k < window.parent.objLstPoas.lstPoas[j].lstObjetivos.length; k++) {

                            if (window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].registroObj == regObjetivo) {
                                lstAlineaciones = window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].lstAlineaciones;
                            }
                        }
                    }
                }
            }
        }
        return lstAlineaciones;

    }

}  //  11 LOAD desde FUNCIONARIO


{//  1 LOAD desde PROGRAMA
    function getAlinFromPrograma() {
        var registroObj = parseInt(jQuery("#registroObj").val());

        var tmpLstAlin = getAlinLstFronPrograma(registroObj);
        loadLstObjASObjAln(tmpLstAlin);
    }

    /**
     *  Carga la lista de alineaciones 
     *  @param {type} registroObj
     *  @returns {Array|window.parent.objLstObjetivo.lstObjetivos.lstAlineaciones|Window.objLstObjetivo.lstObjetivos.lstAlineaciones}
     */
    function  getAlinLstFronPrograma(registroObj) {
        var lstAlineaciones = new Array();
        if (window.parent.objLstObjetivo.lstObjetivos.length > 0) {
            for (var j = 0; j < window.parent.objLstObjetivo.lstObjetivos.length; j++) {
                if (window.parent.objLstObjetivo.lstObjetivos[j].registroObj == registroObj) {
                    lstAlineaciones = window.parent.objLstObjetivo.lstObjetivos[j].lstAlineaciones
                }
            }
        }
        return lstAlineaciones;
    }

}// 1 LOAD desde PROGRAMA

/**
 * 
 * @param {type} data
 * @returns {Alineacion|getAlineacion.oAlineacion}
 */
function getAlineacion(data) {
    var oAlineacion = new Alineacion();
    oAlineacion.setData(data);
    return oAlineacion;
}




{   //  11 LOAD desde FUNCIONARIO
    function getAlinFromPOAOperativo() {
        var registroObj = parseInt(jQuery("#registroObj").val());
        var registroPoa = parseInt(jQuery("#registroPoa").val());

        var tmpLstAlin = getAlinLstFromPOAOperativo( registroPoa );
        loadLstObjASObjAln( tmpLstAlin );
    }

    function getAlinLstFromPOAOperativo( registroPoa )
    {
        return window.parent.dtaPlanOperativo[registroPoa].planObjetivo.lstAlineaciones;
    }

}  //  11 LOAD desde FUNCIONARIO


/**
 * 
 * @param {type} tmpLstAlin
 * @returns {undefined}
 */
function loadLstObjASObjAln(tmpLstAlin) {
    if (tmpLstAlin.length > 0) {
        for (var j = 0; j < tmpLstAlin.length; j++) {
            oAlineacion = new Alineacion();
            oAlineacion.setData(tmpLstAlin[j]);

            oGestionAlineacion.addAlineacion(oAlineacion);
        }
        oGestionAlineacion.reloadLstAlineacion();
    }
}