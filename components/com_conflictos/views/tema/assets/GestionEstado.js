jQuery(document).ready(function() {
    
    idEstado = 0;
    
    /**
     * 
     */
    jQuery('#jform_estado_intId_ec').change( function() {
        if ( jQuery( '#jform_estado_intId_ec' ).val() == 0 ) {
            jQuery( '#addEstado' ).css("display", "block");
            jQuery( '#updEstado, #delEstado' ).css("display", "none");
        } else {
            jQuery( '#updEstado, #delEstado, #addEstado' ).css("display", "block");
        }
    });
    
    jQuery('#jform_estado_intId_ec').trigger( 'change' );
    
    /**
     *  Avilita el formulario para el ingrezo de un nuevo registro
     */
    jQuery('#addEst').click(function () {
        habilitarBtns(2);
        resetForm( true );
        idEstado = 0;
    });
    
    /**
     *  Cansela la gestion de un nuevo registro
     */
    jQuery('#cancelEst').click(function () {
        resetForm( false );
        jQuery('#jform_idEstadoTxt').val('');
        habilitarBtns(1);
    });
    
    /**
     *  Gurada un nuevo registro
     */
    jQuery('#saveEst').click(function () {
        if ( jQuery('#jform_idEstadoTxt').val() != '' ) {
            guardarEstado();
            habilitarBtns(1);
        } else {
            jAlert( JSL_ALERT_ESTADO_NEED, JSL_ECORAE );
        }
    });
    
    /**
     *  Avilita da edicion de un registro
     */
    jQuery('#updEst').click(function () {
        idEstado = jQuery('#jform_estado_intId_ec :selected').val();
        jQuery('#jform_idEstadoTxt').val( jQuery("#jform_estado_intId_ec :selected").text() );
        resetForm( true );
        habilitarBtns(2);
    });
    
    /**
     *  Elimina un registro
     */
    jQuery('#delEst').click(function () {
        jConfirm( JSL_TEMA_CONFIRM_DEL_ESTADO, JSL_ECORAE, function( op ){
            if (op) {
                eliminarEstado();
            }
        });
    });
    
    /**
     *  Realiza la llamada ajax para guardar un registro
     * @returns {undefined}
     */
    function guardarEstado()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var estadoDta = JSON.stringify(list2Object(dataForm()));
        jQuery('#jform_idEstadoTxt').val('');
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'guardarEstado',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                estado: estadoDta
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Estado: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray( dataInfo ) ){
                reloadListEstado( dataInfo );
                resetForm( false );
                if ( idEstado != 0 ) {
                    updEstLstTemas( idEstado, dataInfo );
                    reloadEstTemaTable();
                }
            } else {
                jAlert( JSL_TEMA_ERROR_GUARDAR, JSL_ECORAE )
            }
            jQuery('#jform_estado_intId_ec').trigger( 'change' );
            
        });
    }
    
    /**
     *  Ejecuta la llamada Ajax para elimnar un registro
     * @returns {undefined}
     */
    function eliminarEstado()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var id = jQuery('#jform_estado_intId_ec :selected').val();
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'eliminarEstado',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                id: id
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Estado: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray( dataInfo ) ){
                reloadListEstado( dataInfo );
            } else {
                jAlert( JSL_TEMA_ERROR_DEL_REG, JSL_ECORAE );
            }
            jQuery('#jform_estado_intId_ec').trigger( 'change' );
        });
    }
    
    /**
     * 
     * @returns {Array|dtaFrm}
     */
    function dataForm()
    {
        dtaFrm = new Array();

        dtaFrm["id"]        = idEstado;
        dtaFrm["nombre"]    = jQuery('#jform_idEstadoTxt').val();
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
            jQuery('#li-jform_estado_intId_ec').css('display', 'none');
            jQuery('#li-jform_idEstadoTxt').css('display', 'block');
        } else {
            jQuery('#li-jform_estado_intId_ec').css('display', 'block');
            jQuery('#li-jform_idEstadoTxt').css('display', 'none');
        }
    }
    
    /**
     * 
     * @param {type} list
     * @returns {undefined}
     */
    function reloadListEstado ( list )
    {
        jQuery( '#jform_estado_intId_ec' ).empty();
        if ( list.length > 0){
            jQuery('#jform_estado_intId_ec').append(new Option( JSL_TEMA_SELECT_ESTADO, 0 ));
            for (var i = 0; i < list.length ; i++) {
                jQuery('#jform_estado_intId_ec').append(new Option( list[i].nombre, list[i].id ));
            }
        } else {
            jQuery('#jform_estado_intId_ec').append(new Option( JSL_SIN_REGISTROS, 0 ));
        }
    }
    
    /**
     * 
     * @param {type} op
     * @returns {undefined}
     */
    function habilitarBtns( op ){
        if ( op == 1 ){
            jQuery("#saveEstadoTema").removeAttr("disabled");
        } else {
            jQuery("#saveEstadoTema").attr("disabled", "disabled");
        }
    }
    
    /**
     * 
     * @param {type} id
     * @param {type} list
     * @returns {undefined}
     */
    function updEstLstTemas( id, list ){
        var reg = -1;
        for (var i = 0; i < list.length; i++) {
            if ( list[i].id == id ) {
                reg = i;
            }
        }
        
        if ( reg != -1 ) {
            for ( var j = 0; j < oTema.lstEstados.length; j++ ) {
                if ( oTema.lstEstados[j].idEstado == id )
                    oTema.lstEstados[j].nmbEstado = list[reg].nombre;
            }
        }
    }
    
});
