
jQuery(document).ready(function() {

    loadFronParent();

    /**
     * AGREGAR una NUEVA alineacion
     */
    jQuery("#btnNewAln").click(function() {
        regAlineacion = -1;
        clmFromAln();
        showFormAln();
    });

    /**
     * CANCELAR edicion de una  AGENDA
     */
    jQuery('#btnClnAln').click(function() {
        regAlineacion = -1;
        clmFromAln();
        hideFormAln();
    });

    /**
     * GUARDAR una nueva AGENDA
     */
    jQuery('#btnAddAln').click(function() {
        oAlineacion = new Alineacion();
        oAlineacion.getDataForm();
        var valid = ( typeof(oAlineacion.idObjetivo) != "undefined" && oAlineacion.idObjetivo != 0 && oAlineacion.idObjetivo != null) ? true : false;
        if ( valid ) {
            if (regAlineacion == -1) {
                oAlineacion.regAlineacion = oGestionAlineacion.lstAlineaciones.length;
                oGestionAlineacion.addAlineacion(oAlineacion);
                oAlineacion.addTrAlineacion();
            } else {
                oAlineacion.regAlineacion = regAlineacion;
                oGestionAlineacion.updAlineacion(regAlineacion, oAlineacion);
                oGestionAlineacion.reloadLstAlineacion();
            }
            jQuery('#btnClnAln').trigger('click');
        } else {
            jAlert( ALL_CAMPOS_REQUERIDOS, SIITA_ECORAE );
        }
    });

    /**
     * ACTUALIZAR la AGENDA
     */
    jQuery('.updAln').live('click', function() {
        showFormAln();
        regAlineacion = parseInt(this.parentNode.parentNode.id);
        oAlineacion = oGestionAlineacion.getAlineacionByReg(regAlineacion);
        oAlineacion.loadFormData();
    });

    /**
     * ELIMINAR la AGENDA
     */
    jQuery('.delAln').live('click', function() {
        var regAlineacionDel = parseInt(this.parentNode.parentNode.id);
        jConfirm(ELM_MES_ALINEACON, SIITA_ECORAE, function(result) {
            if (result) {
                oGestionAlineacion.delAlineacion(regAlineacionDel);
                oGestionAlineacion.reloadLstAlineacion();
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
    function hideFormAln() {
        jQuery("#imgAln").css("display", "block");
        jQuery("#frmAln").css("display", "none");
    }
    /**
     * MOSTRAR el FORMULARIO
     * @returns {undefined}
     */
    function showFormAln() {
        jQuery("#frmAln").css("display", "block");
        jQuery("#imgAln").css("display", "none");
    }
    /**
     * LIMPIAR  el FORMULARIO
     * @returns {undefined}
     */
    function clmFromAln() {
        recorrerCombo(jQuery("#jform_Alineacion option"), 0);
        jQuery("#jform_Alineacion").trigger('change');
    }

}//Funciones de edición
