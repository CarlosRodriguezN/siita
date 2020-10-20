jQuery(document).ready(function() {
    
    jQuery.alerts.okButton = JSL_OK;
    jQuery.alerts.cancelButton = JSL_CANCEL;
    
    //  Carga los POAs y obtiene el plan vigente
    var idRegPoa = -1;
    if ( typeof (objLstPoas.lstPoas) != "undefined" && objLstPoas.lstPoas.length > 0 ){
        var oGestionPoa = new GestionPoas();
        for (var i=0; i<objLstPoas.lstPoas.length; i++ ) {
            if ( objLstPoas.lstPoas[i].vigenciaPoa == 1){
                idRegPoa = objLstPoas.lstPoas[i].idRegPoa;
            }
            jQuery( '#tbLstPoas > tbody:last').append( oGestionPoa.makeFilaPOA( objLstPoas.lstPoas[i], 0 ) );
        }
    } else {
        jQuery("#srPoas").css("display", "block");
    }
    jQuery("#idRegPoa").val( idRegPoa );
    
    /**
     * Elimina un plan POA
     */
    jQuery(".delPoa").live("click", function (){
        var idPoa = jQuery(this).attr('id');
        var idRegPoa = parseInt(idPoa.toString().split('-')[1]);
        var lstObjetivosPoa = (objLstPoas.lstPoas[idRegPoa].lstObjetivos) ? objLstPoas.lstPoas[idRegPoa].lstObjetivos : false ;
        if ( lstObjetivosPoa == false || avalibleDel(lstObjetivosPoa) ){
            jConfirm(JSL_CONFIRM_DELETE, JSL_ECORAE, function(resutl) {
                if (resutl) {
                    objLstPoas.lstPoas[idRegPoa].published = 0;
                    delFila( idRegPoa, "#tbLstPoas" );
                    jQuery( "#poas").html("Poa's (" + numAvaliblePoas() + ")");
                }   
            });
        } else {
            jAlert(JSL_CONFIRM_NO_AVALIBLE_DEL, JSL_ECORAE);
        }
    });
    
    /**
     *  Retorna el numero de planes validos, activos de la lista de planes POAs
     * @returns {Number}
     */
    function numAvaliblePoas()
    {
        var numP = 0;
        for ( var i=0; i < objLstPoas.lstPoas.length; i++ ) {
            if (objLstPoas.lstPoas[i].published == 1){
                numP++;
            }
        }
        return numP;
    }
    
    /**
     *  Retorna True si no existen objetivos validos, si no retorna False
     * @param {type} lstObjetivosPoa
     * @returns {Boolean}
     */
    function avalibleDel(lstObjetivosPoa)
    {
        var result = true;
        for ( var i=0; i<lstObjetivosPoa.length; i++){
            if ( lstObjetivosPoa[i].published == 1){
                result = false;
            }
        }
        
        return result;
    }
    
    /**
     *  Elimina una fila de la tabla de planes POA
     * @param {type} idFila
     * @param {type} tabla
     * @returns {undefined}
     */
    function delFila(idFila, tabla)
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery(tabla + ' tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }
});
