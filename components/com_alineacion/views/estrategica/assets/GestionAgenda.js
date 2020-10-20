 jQuery.alerts.okButton = JSL_OK;
 jQuery.alerts.cancelButton = JSL_CANCEL;

jQuery(document).ready(function() {

    loadFronParent();
    /**
     * AGREGAR una NUEVA alineacion
     */
    jQuery("#btnNewAgnd").click(function() {
        if (jQuery("#frmAgd").is(":hidden")){
            regAlineacion = -1;
            clmFrom();
            showFormAgd();
        }
    });

    /**
     * CANCELAR edicion de una  AGENDA
     */
    jQuery('#btnClnAgnd').click(function() {
        regAlineacion = -1;
        hideFormAgd();
        clmFrom();
    });

    /**
     * GUARDAR una nueva AGENDA
     */
    jQuery('#btnAddAgnd').click(function() {
        oAlineacion.getFormItemsData();
        oAlineacion.validateObj();
        
        if ( oAlineacion.valido === true ) {
            if (regAlineacion == -1) {
                oGestionAlineacion.addAlineacion(oAlineacion);
            } else {
                oGestionAlineacion.updAlineacion(regAlineacion, oAlineacion);
                oGestionAlineacion.reloadLstAlineacion();
            }

            jQuery('#btnClnAgnd').trigger('click');            
        } else {
            var cbAgendas = jQuery( '#lstAgendas li :input' );

            for( var x = 0; x < cbAgendas.length; x++ ){
                validarElemento( jQuery( cbAgendas[x] ) );
            }
            
            jAlert( oAlineacion.valido, SIITA_ECORAE );
        }
        
    });
    
    
    
    function validarElemento( obj )
    {
        var ban = 1;
        
        if( obj.val() === "" || obj.val() === "0" ){
            ban = 0;
            obj.attr( 'class', 'required invalid' );

            var lbl = ( obj.selector !== "" )   ? obj.selector + '-lbl'
                                                : obj.attr( 'id' ) + '-lbl';

            jQuery( lbl ).attr( 'class', 'hasTip required invalid' );
            jQuery( lbl ).attr( 'aria-invalid', 'true' );
        }
        
        return ban;
    }
    

    /**
     * ACTUALIZAR la AGENDA
     */
    jQuery('.updAln').live('click', function() {
        showFormAgd();
        regAlineacion = parseInt(this.parentNode.parentNode.id);
        oAlineacion = oGestionAlineacion.getAlineacionByReg(regAlineacion);
        oAlineacion.loadNivel();
        loadFormAlineacion(oAlineacion);
    });

    /**
     * ELIMINAR la AGENDA
     */
    jQuery('.delAln').live('click', function() {
        var regAlineacionDel = parseInt(this.parentNode.parentNode.id);
        jConfirm(ELM_MES_ALINEACON, SIITA_ECORAE, function(result) {
            if (result) {
                oGestionAlineacion.delAlineacion(regAlineacionDel);
            }
        });
    });
    
});



/**
 * CARGA los datos en el formulario de la ALINEACION
 * @param {type} oAlineacion
 * @returns {undefined}
 */
function loadFormAlineacion(oAlineacion) {
    if (oAlineacion.niveles.length > 0) {
        for (var j = 0; j < oAlineacion.niveles.length; j++) {
            var itmData = {
                idPadre: (oAlineacion.niveles[j - 1]) ? oAlineacion.niveles[j - 1].item.idItem : 0,
                idAgenda: oAlineacion.idAgenda
            };
            loadItems(itmData, oAlineacion.niveles[j].item.idItem);
        }
    }
}


{//Funciones de edición

    /**
     * OCULTA le FORMULARIO
     * @returns {undefined}
     */
    function hideFormAgd() {
        jQuery("#imgAgd").css("display", "block");
        jQuery("#frmAgd").css("display", "none");
    }
    /**
     * MOSTRAR el FORMULARIO
     * @returns {undefined}
     */
    function showFormAgd() {
        jQuery("#frmAgd").css("display", "block");
        jQuery("#imgAgd").css("display", "none");
    }
    /**
     * LIMPIAR  el FORMULARIO
     * @returns {undefined}
     */
    function clmFrom() {
        recorrerCombo(jQuery("#jform_idAgenda option"), 0);
        jQuery("#jform_idAgenda").trigger('change');
    }

}//Funciones de edición

/**
 * 
 * @returns {undefined}
 */
function loadFronParent() {
    var regObjetivo = parseInt(jQuery("#idRegObjetivo").val());
    var tpoPln = parseInt(jQuery("#tpoPln").val());
    var regPlan = parseInt(jQuery("#registroPln").val());
    var tmpLstObjetivos = new Array();
    switch (tpoPln){
        case 3:
            tmpLstObjetivos = window.parent.oLstPPPPs.lstPppp[regPlan].lstObjetivos;
            break;
        case 4:
            tmpLstObjetivos = window.parent.oLstPAPPs.lstPapp[regPlan].lstObjetivos;
            break;
        default :
            tmpLstObjetivos = window.parent.objLstObjetivo.lstObjetivos;
            break;
    }
    
    for (var j = 0; j < tmpLstObjetivos.length; j++) {
        if (tmpLstObjetivos[j].registroObj == regObjetivo) {
            var lstAlineaciones = tmpLstObjetivos[j].lstAlineaciones;

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