jQuery(document).ready(function() {
    
    /**
     *  Ejecuta la opcion guardar de un formulario
     * @param {type} task
     * @returns {Boolean}
     */
    Joomla.submitbutton = function(task)
    {
         switch (task) {
            case 'poa.registrar':
                if ( objetoValido() ) {
                    if ( validarAnioPlan() ){
                        //  Controla si no existen POAs para habilitar la tabla de POAs
                        if ( window.parent.objLstPoas.lstPoas.length == 0 ) {
                            jQuery( '#srPoas', window.parent.document ).css("display", "none");
                            jQuery( '#tbPoas', window.parent.document ).css("display", "block");
                        }
                        
                        agregarPOA();
                        
                        window.parent.SqueezeBox.close();
                    } else {
                        jAlert( JSL_ALERT_DATE_EXIST, JSL_ECORAE);
                    }
                } else {
                    jAlert( JSL_ALERT_ALL_NEED, JSL_ECORAE);
                }
                break;

            case 'poa.cancel':
                window.parent.SqueezeBox.close();
                break;
        }
        return false;
    };

    /**
     *  Gestion la edicion o el ingreso de planes
     * @returns {undefined}
     */
    function agregarPOA()
    {
        var idPoa = jQuery("#idRegPoa").val();
        var opPoa = parseInt(jQuery("#opPoa").val());
        var objPoa = new window.parent.Poa();
        var gestionPoa = new window.parent.GestionPoas();
        switch (opPoa) {
            case 0:
                //  Si es un nuevo registro
                objPoa.setDtaPoa( dataFormulario() );
                window.parent.objLstPoas.lstPoas.push(objPoa);
                //  Agrego la fila creada a la tabla
                jQuery( '#tbLstPoas > tbody:last', window.parent.document ).append( gestionPoa.makeFilaPOA( objPoa, 0 ) );
                jQuery( "#poas", window.parent.document ).html("Poa's (" + avalibleReg(window.parent.objLstPoas.lstPoas) + ")");
                break;
            case 1:
                //  Si es un registro existente
                var lstTmpPoas = window.parent.objLstPoas.lstPoas;
                for ( var j=0; j<lstTmpPoas.length; j++ ) {
                    if ( idPoa == lstTmpPoas[j].idRegPoa) {
                        var anio = parseInt((jQuery('#jform_dteFechafin_pi').val()).toString().split('-')[0]);
                        lstTmpPoas[j].descripcionPoa    = jQuery('#jform_strDescripcion_pi').val();
                        lstTmpPoas[j].aliasPoa          = jQuery('#jform_strAlias_pi').val();
                        lstTmpPoas[j].fechaInicioPoa    = jQuery('#jform_dteFechainicio_pi').val();
                        lstTmpPoas[j].fechaFinPoa       = jQuery('#jform_dteFechafin_pi').val();
                        lstTmpPoas[j].vigenciaPoa       = ( parseInt(jQuery('#anioVigente', window.parent.document).val()) == anio ) ? 1 : 0;
                    }
                }
                //  Actulisa la informacion en la tabla de planes 
                jQuery( '#tbLstPoas tr', window.parent.document ).each( function() {
                    if (jQuery(this).attr('id') == idPoa) {
                        //  Actualizo la Fila
                        jQuery(this).html( gestionPoa.makeFilaPOA( lstTmpPoas[idPoa], 1 ) );
                    }
                });
                break;
        }
    }

    /**
     *  Arma un array con la informacion genral de un plan POA
     * @returns {Array}
     */
    function dataFormulario()
    {
        var dtaFrm = new Array();
        var anio = parseInt((jQuery('#jform_dteFechafin_pi').val()).toString().split('-')[0]);

        dtaFrm["idPoa"]             = jQuery('#jform_intId_pi').val();
        dtaFrm["idPadrePoa"]        = 0;
        dtaFrm["idTipoPlanPoa"]     = jQuery('#jform_intId_tpoPlan').val();
        dtaFrm["idInstitucionPoa"]  = 0;
        dtaFrm["idEntidadPoa"]      = jQuery('#idEntidad').val();;
        dtaFrm["descripcionPoa"]    = jQuery('#jform_strDescripcion_pi').val();
        dtaFrm["fechaInicioPoa"]    = jQuery('#jform_dteFechainicio_pi').val();
        dtaFrm["fechaFinPoa"]       = jQuery('#jform_dteFechafin_pi').val();
        dtaFrm["aliasPoa"]          = jQuery('#jform_strAlias_pi').val();
        dtaFrm["vigenciaPoa"]       = ( parseInt(jQuery('#anioVigente', window.parent.document).val()) == anio ) ? 1 : 0;
        dtaFrm["published"]         = 1;
        dtaFrm["idRegPoa"]          = window.parent.objLstPoas.lstPoas.length;
        dtaFrm["lstObjetivos"]      = new Array();

        return dtaFrm;
    }
    
    /**
     *  Valida si el año del plan ingresado existe en la lista de planes ingresados
     * @returns {Boolean}
     */
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
                var op =  parseInt(jQuery("#opPoa").val());
                switch ( op ){
                    case 0:
                        if ( anioPlan == anioNewPOA && lstPoas[i].published == 1 ) {
                            result = false;
                        }
                        break;
                    case 1:
                        if ( anioPlan == anioNewPOA && lstPoas[i].published == 1 && lstPoas[i].idRegPoa != jQuery("#idRegPoa").val() ) {
                            result = false;
                        }
                        break;
                }
            }
        }

        return result;
    }


    function objetoValido()
    {
        var ban = false;
        
        var descripcion = jQuery( '#jform_strDescripcion_pi' );
        var fchInicio   = jQuery( '#jform_dteFechainicio_pi' );
        var fchFin      = jQuery( '#jform_dteFechafin_pi' );

        if (    descripcion.val() != ""
                && fchInicio.val() != ""
                && fchFin.val() != "" ) {
            ban = true;
        }else{
            descripcion.validarElemento();
            fchInicio.validarElemento();
            fchFin.validarElemento();
        }
        
        return ban;
    }

    /**
     *  Retorna el numero de registro validos de la data enviada
     * @param {type} data
     * @returns {Boolean}
     */
    function avalibleReg(data)
    {
        var result = 0;
        if (data) {
            var numReg = data.length;
            for (var i = 0; i < numReg; i++) {
                if (data[i].published == 1){
                    result = ++result;
                }
            }
        }
        return result;
    }
});


