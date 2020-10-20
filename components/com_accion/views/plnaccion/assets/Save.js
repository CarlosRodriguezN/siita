jQuery(document).ready(function() {
    Joomla.submitbutton = function(task)
    {
        switch (task) {
            case 'plnaccion.registrar':
                var idObjetivo = jQuery( '#idRegObjetivo' ).val();
                var idPlan = jQuery( '#idRegPlan' ).val();
                var lstAcciones = new Array;
                if ( typeof (idPlan) != 'undefined' && idPlan != '' ) {
                    var tpoPlan = parseInt(jQuery( '#tpoPln' ).val());
                    switch (tpoPlan) {
                        case 2:
                            window.parent.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstAcciones = new Array();
                            lstAcciones = window.parent.objLstPoas.lstPoas[idPlan].lstObjetivos[idObjetivo].lstAcciones;
                        break;
                            
                        case 3:
                            window.parent.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones = new Array();
                            lstAcciones = window.parent.oLstPPPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones;
                        break;

                        case 4:
                            window.parent.oLstPAPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones = new Array();
                            lstAcciones = window.parent.oLstPAPPs.lstPppp[idPlan].lstObjetivos[idObjetivo].lstAcciones;
                        break;

                        case 5:
                            window.parent.dtaPlanOperativo[idPlan].planObjetivo.lstAcciones = new Array();
                            lstAcciones = window.parent.dtaPlanOperativo[idPlan].planObjetivo.lstAcciones;
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
                if( tpoPlan != 5 ){
                    window.parent.semaforoPlanAnccion( idObjetivo, tpoPlan, idPlan );
                }
                
                window.parent.SqueezeBox.close();
            break;

            case 'plnaccion.cancel':
                window.parent.SqueezeBox.close();
            break;
        }
    };

});
