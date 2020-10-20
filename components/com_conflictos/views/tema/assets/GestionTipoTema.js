    jQuery(document).ready(function() {
    
    idTipoTema = 0;
    
    /**
     * 
     */
    jQuery('#jform_intId_tt').change( function() {
        if ( jQuery( '#jform_intId_tt' ).val() == 0 ) {
            jQuery( '#addTipoTema' ).css("display", "block");
            jQuery( '#updTipoTema, #delTipoTema' ).css("display", "none");
        } else {
            jQuery( '#updTipoTema, #delTipoTema, #addTipoTema' ).css("display", "block");
        }
    });
    
    jQuery('#jform_intId_tt').trigger( 'change' );
    
    /**
     *  Avilita el formulario para el ingrezo de un nuevo registro
     */
    jQuery('#addTT').click(function () {
        resetForm( true );
        idTipoTema = 0;
    });
    
    /**
     *  Cansela la gestion de un nuevo registro
     */
    jQuery('#cancelTT').click(function () {
        resetForm( false );
        jQuery('#jform_idTipoTemaTxt').val('');
    });
    
    /**
     *  Gurada un nuevo registro
     */
    jQuery('#saveTT').click(function () {
        if ( jQuery('#jform_idTipoTemaTxt').val() != '' ) {
            guardarTipoTema();
            
        } else {
            jAlert( JSL_ALERT_TIPO_TEMA_NEED, JSL_ECORAE );
        }
    });
    
    /**
     *  Avilita da edicion de un registro
     */
    jQuery('#updTT').click(function () {
        idTipoTema = jQuery('#jform_intId_tt :selected').val();
        jQuery('#jform_idTipoTemaTxt').val( jQuery("#jform_intId_tt :selected").text() );
        resetForm( true );
    });
    
    /**
     *  Elimina un registro
     */
    jQuery('#delTT').click(function () {
        jConfirm( JSL_TEMA_CONFIRM_DEL_TIPO_TEMA, JSL_ECORAE, function( op ){
            if (op) {
                eliminarTipoTema();
            }
        });
    });
    
    /**
     *  Realiza la llamada ajax para guardar un registro
     * @returns {undefined}
     */
    function guardarTipoTema()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var tpoTema = JSON.stringify(list2Object(dataForm()));
        jQuery('#jform_idTipoTemaTxt').val('');
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'guardarTipoTema',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                tpoTema: tpoTema
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Tipo de Tema: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( dataInfo.length > 0){
                reloadListTpoTema( dataInfo );
                resetForm( false );
            } else {
                jAlert( JSL_TEMA_ERROR_GUARDAR, JSL_ECORAE )
            }
            jQuery('#jform_intId_tt').trigger( 'change' );
            
        });
    }
    
    /**
     *  Ejecuta la llamada Ajax para elimnar un registro
     * @returns {undefined}
     */
    function eliminarTipoTema()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var id = jQuery('#jform_intId_tt :selected').val();
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'eliminarTipoTema',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                id: id
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Tipo de Tema: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( dataInfo){
                reloadListTpoTema( dataInfo );
            } else {
                jAlert( JSL_TEMA_ERROR_DEL_REG, JSL_ECORAE );
            }
            jQuery('#jform_intId_tt').trigger( 'change' );
        });
    }
    
    /**
     * 
     * @returns {Array|dtaFrm}
     */
    function dataForm()
    {
        dtaFrm = new Array();

        dtaFrm["id"]        = idTipoTema;
        dtaFrm["nombre"]    = jQuery('#jform_idTipoTemaTxt').val();
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
            jQuery('#li-jform_intId_tt').css('display', 'none');
            jQuery('#li-jform_idTipoTemaTxt').css('display', 'block');
        } else {
            jQuery('#li-jform_intId_tt').css('display', 'block');
            jQuery('#li-jform_idTipoTemaTxt').css('display', 'none');
        }
    }
    
    /**
     * 
     * @param {type} list
     * @returns {undefined}
     */
    function reloadListTpoTema ( list )
    {
        jQuery( '#jform_intId_tt' ).empty();
        if ( list.length > 0) {
            jQuery('#jform_intId_tt').append(new Option( JSL_TEMA_SELECT_TIPO_TEMA, 0 ));
            for (var i = 0; i < list.length ; i++) {
                jQuery('#jform_intId_tt').append(new Option( list[i].nombre, list[i].id ));
            }
        } else {
            jQuery('#jform_intId_tt').append(new Option( JSL_SIN_REGISTROS, 0 ));
        }
    }
    
});