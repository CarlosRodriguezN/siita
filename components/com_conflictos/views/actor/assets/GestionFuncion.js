jQuery(document).ready(function() {
    
    idFuncionAct = 0;
    
    /**
     * 
     */
    jQuery('#jform_intId_fcn').change( function() {
        if ( jQuery( '#jform_intId_fcn' ).val() == 0 ) {
            jQuery( '#addFuncion' ).css("display", "block");
            jQuery( '#updFuncion, #delFuncion' ).css("display", "none");
        } else {
            jQuery( '#updFuncion, #delFuncion, #addFuncion' ).css("display", "block");
        }
    });
    
    jQuery('#jform_intId_fcn').trigger( 'change' );
    
    /**
     *  Avilita el formulario para el ingrezo de un nuevo registro
     */
    jQuery('#addFnc').click(function () {
        habilitarBtns( 2, "#saveFuncionActor" );
        resetForm( true );
        idFuncionAct = 0;
    });
    
    /**
     *  Cansela la gestion de un nuevo registro
     */
    jQuery('#cancelFnc').click(function () {
        habilitarBtns( 1, "#saveFuncionActor" );
        resetForm( false );
        jQuery('#jform_idFuncionActorTxt').val('');
    });
    
    /**
     *  Gurada un nuevo registro
     */
    jQuery('#saveFnc').click(function () {
        if ( jQuery('#jform_idFuncionActorTxt').val() != '' ) {
            guardarFuncion();
            habilitarBtns( 1, "#saveFuncionActor" );
        } else {
            jAlert( JSL_ALERT_FUNCION_ACT_NEED, JSL_ECORAE );
        }
    });
    
    /**
     *  Avilita da edicion de un registro
     */
    jQuery('#updFnc').click(function () {
        idFuncionAct = jQuery('#jform_intId_fcn :selected').val();
        jQuery('#jform_idFuncionActorTxt').val( jQuery("#jform_intId_fcn :selected").text() );
        resetForm( true );
        habilitarBtns( 2, "#saveFuncionActor" );
    });
    
    /**
     *  Elimina un registro
     */
    jQuery('#delFnc').click(function () {
        jConfirm( JSL_FUENTE_CONFIRM_DEL_FUNCION_ACT, JSL_ECORAE, function( op ){
            if (op) {
                eliminarLegitimidad();
            }
        });
    });
    
    /**
     *  Realiza la llamada ajax para guardar un registro
     * @returns {undefined}
     */
    function guardarFuncion()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var funcion = JSON.stringify(list2Object(dataForm()));
        jQuery('#jform_idFuncionActorTxt').val('');
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'guardarFuncion',
                option: 'com_conflictos',
                view: 'actor',
                tmpl: 'component',
                format: 'json',
                funcion: funcion
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Funci&oacute;n del Actor: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray(dataInfo) ){
                reloadListFunciones( dataInfo );
                resetForm( false );
                if ( idFuncionAct != 0 ) {
                    updLstFmcActor( idFuncionAct, dataInfo );
                    reloadFuncionActTb();
                }
            } else {
                jAlert( JSL_FUENTE_ERROR_GUARDAR, JSL_ECORAE );
            }
            jQuery('#jform_intId_fcn').trigger( 'change' );
        });
    }
    
    /**
     *  Ejecuta la llamada Ajax para elimnar un registro
     * @returns {undefined}
     */
    function eliminarLegitimidad()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var id = jQuery('#jform_intId_fcn :selected').val();
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'eliminarFuncion',
                option: 'com_conflictos',
                view: 'actor',
                tmpl: 'component',
                format: 'json',
                id: id
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Funci&oacute;n del Actor: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray(dataInfo) ){
                reloadListFunciones( dataInfo );
                if ( idFuncionAct != 0 ) {
                    updLstIncActores( idIncidencia, dataInfo );
                    reloadIncidenciasActTb();
                }
            } else {
                jAlert( JSL_FUENTE_ERROR_DEL_REG, JSL_ECORAE );
            }
            jQuery('#jform_intId_fcn').trigger( 'change' );
        });
    }
    
    /**
     * 
     * @returns {Array|dtaFrm}
     */
    function dataForm()
    {
        dtaFrm = new Array();

        dtaFrm["id"]        = idFuncionAct;
        dtaFrm["nombre"]    = jQuery('#jform_idFuncionActorTxt').val();
        dtaFrm["published"] = 1;

        return dtaFrm;
    }
    
    /**
     * 
     * @param {type} op
     * @returns {undefined}
     */
    function resetForm( op )
    {
        if ( op ) {
            jQuery('#li-jform_intId_fcn').css('display', 'none');
            jQuery('#li-jform_idFuncionActorTxt').css('display', 'block');
        } else {
            jQuery('#li-jform_intId_fcn').css('display', 'block');
            jQuery('#li-jform_idFuncionActorTxt').css('display', 'none');
        }
    }
    
    /**
     * 
     * @param {type} list
     * @returns {undefined}
     */
    function reloadListFunciones ( list )
    {
        jQuery( '#jform_intId_fcn' ).empty();
        if ( list.length > 0 ) {
            jQuery('#jform_intId_fcn').append(new Option( JSL_FUENTE_SELECT_FUNCION_ACT, 0 ));
            for (var i = 0; i < list.length ; i++) {
                jQuery('#jform_intId_fcn').append(new Option( list[i].nombre, list[i].id ));
            }
        } else {
            jQuery('#jform_intId_fcn').append(new Option( JSL_SIN_REGISTROS, 0 ));
        }
    }
    
    /**
     * 
     * @param {type} id
     * @param {type} list
     * @returns {undefined}
     */
    function updLstFmcActor( id, list ){
        var reg = -1;
        for (var i = 0; i < list.length; i++) {
            if ( list[i].id == id ) {
                reg = i;
            }
        }
        
        if ( reg != -1 ) {
            for ( var j = 0; j < oActor.lstFunciones.length; j++ ) {
                if ( oActor.lstFunciones[j].idFuncion == id )
                    oActor.lstFunciones[j].nmbFuncion = list[reg].nombre;
            }
        }
    }
    
});