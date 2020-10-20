jQuery(document).ready(function() {

    jQuery("#jform_anioPOA").live("click", function() {
        var anio = jQuery("#jform_anioPOA :selected").text();
        jQuery("#jform_dteFechainicio_pi").val(anio + "-01-01");
        jQuery("#jform_dteFechafin_pi").val(anio + "-12-31");
    });

    //
    //  Ejecuta la opcion guardar de un formulario
    //
    Joomla.submitbutton = function(task)
    {
         switch (task) {
            case 'poa.registrar':
                if ( validarAnioPlan() && objetoValido() ) {
                    var objPoa = new window.parent.Poa();
                    var gestionPoa = new window.parent.GestionPoas();
                    //  Controla si no existen POAs para habilitar la tabla de POAs
                    if ( window.parent.objLstPoas.lstPoas.length == 0 ) {
                        jQuery( '#srPoas', window.parent.document ).css("display", "none");
                        jQuery( '#tbPoas', window.parent.document ).css("display", "block");
                    }
                    objPoa.setDtaPoa( dataFormulario() );
                    window.parent.objLstPoas.lstPoas.push(objPoa);
                    //  Agrego la fila creada a la tabla
                    jQuery( '#tbLstPoas > tbody:last', window.parent.document ).append( gestionPoa.makeFilaPOA( objPoa, 0 ) );
                    jQuery( "#poas", window.parent.document ).html("Poa's (" + window.parent.objLstPoas.lstPoas.length + ")");
                    window.parent.SqueezeBox.close();
                } else {
                    jAlert("Ya existe un plan con la fecha ingresada","SIITA-ECORAE");
                }
                break;

            case 'poa.cancel':
                window.parent.SqueezeBox.close();
                break;
        }
        return false;
    };


    function dataFormulario()
    {
        var dtaFrm = new Array();

        dtaFrm["idPoa"]             = jQuery('#jform_intId_pi').val();
        dtaFrm["idPadrePoa"]        = 0;
        dtaFrm["idTipoPlanPoa"]     = jQuery('#jform_intId_tpoPlan').val();
        dtaFrm["idInstitucionPoa"]  = 0;
        dtaFrm["idEntidadPoa"]      = jQuery('#idEntidadUG').val();;
        dtaFrm["descripcionPoa"]    = jQuery('#jform_strDescripcion_pi').val();
        dtaFrm["fechaInicioPoa"]    = jQuery('#jform_dteFechainicio_pi').val();
        dtaFrm["fechaFinPoa"]       = jQuery('#jform_dteFechafin_pi').val();
        dtaFrm["aliasPoa"]          = jQuery('#jform_strAlias_pi').val();
        dtaFrm["vigenciaPoa"]       = ( jQuery("#anioVigente", window.parent.document).val() == jQuery("#jform_anioPOA :selected").text() )
                                        ? 1 
                                        : jQuery('#jform_blnVigente_pi').val();
        dtaFrm["published"]         = 1;
        dtaFrm["idRegPoa"]          = window.parent.objLstPoas.lstPoas.length;
        dtaFrm["lstObjetivos"]      = new Array();

        return dtaFrm;
    }

    function validarAnioPlan()
    {
        var result = true;
        var lstPoas =  window.parent.objLstPoas.lstPoas;
        var fNew = jQuery('#jform_dteFechainicio_pi').val();
        var anioNewPOA = parseInt(fNew.toString().split('-')[0]);

        if ( lstPoas.length > 0 ){
            for (var i=0; i < lstPoas.length; i++) {
                var fPlan = lstPoas[i].fechaInicioPoa;
                var anioPlan = parseInt(fPlan.toString().split('-')[0]);
                if ( anioPlan == anioNewPOA && lstPoas[i].published == 1) {
                    result = false;
                }
            }
        }
        
        return result;
    }
    
    function objetoValido ()
    {
        var result = true;
        if (jQuery("#jform_strDescripcion_pi").val() == '' ||
            jQuery("#jform_dteFechainicio_pi") .val() == '' ||
            jQuery("#jform_dteFechainicio_pi") .val() == '') {
            result = false
        }
        
        return result;
    }
    
});
