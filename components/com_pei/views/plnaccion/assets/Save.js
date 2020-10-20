jQuery(document).ready(function() {
    Joomla.submitbutton = function(task)
    {
        switch (task) {
            case 'plnaccion.registrar':
                var idObjetivo = jQuery( '#idRegObjetivo' ).val();
                var lstAcciones = new Array();
                if (jQuery( '#idRegPlan' ).val()) {
                    var tpoPlan = jQuery( '#tpoPln' ).val();
                    var idPlan = jQuery( '#idRegPlan' ).val();
                    switch (tpoPlan) {
                        case 3:
                            window.parent.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones = new Array();
                            lstAcciones = window.parent.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones;
                            break;

                        case 4:
                            window.parent.oLstPAPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones = new Array();
                            lstAcciones = window.parent.oLstPAPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones;
                            break;
                    }

                } else {
                    window.parent.objLstObjetivo.lstObjetivos[idObjetivo].lstAcciones = new Array();
                    lstAcciones = window.parent.objLstObjetivo.lstObjetivos[idObjetivo].lstAcciones;
                }
                
                for( var x = 0; x < lstTmpAcciones.length; x++ ){
                    lstAcciones.push( lstTmpAcciones[x] );
                }
                
                //  Actualiza el semaforo del objetivo
                window.parent.validateSemaforoObjetivo(idObjetivo, tpoPlan, idPlan);
                window.parent.SqueezeBox.close();
                break;

            case 'plnaccion.cancel':
                window.parent.SqueezeBox.close();
                break;

        }
    };

});
