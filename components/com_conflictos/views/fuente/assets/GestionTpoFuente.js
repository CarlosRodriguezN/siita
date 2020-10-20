jQuery(document).ready(function() {
    
    idTipoFuente = 0;
    
    /**
     * 
     */
    jQuery('#jform_intId_tf').change( function() {
        if ( jQuery( '#jform_intId_tf' ).val() == 0 ) {
            jQuery( '#addTpoFnt' ).css("display", "block");
            jQuery( '#updTpoFnt, #delTpoFnt' ).css("display", "none");
        } else {
            jQuery( '#updTpoFnt, #delTpoFnt, #addTpoFnt' ).css("display", "block");
        }
    });
    
    jQuery('#jform_intId_tf').trigger( 'change' );
    
    /**
     *  Avilita el formulario para el ingrezo de un nuevo registro
     */
    jQuery('#addTF').click(function () {
        resetForm( true );
        idTipoFuente = 0;
    });
    
    /**
     *  Cansela la gestion de un nuevo registro
     */
    jQuery('#cancelTF').click(function () {
        resetForm( false );
        jQuery('#jform_idTpoFuenteTxt').val('');
    });
    
    /**
     *  Gurada un nuevo registro
     */
    jQuery('#saveTF').click(function () {
        if ( jQuery('#jform_idTpoFuenteTxt').val() != '' ) {
            guardarTipoFuente();
            
        } else {
            jAlert( JSL_ALERT_TPO_FUENT_NEED, JSL_ECORAE );
        }
    });
    
    /**
     *  Avilita da edicion de un registro
     */
    jQuery('#updTF').click(function () {
        idTipoFuente = jQuery('#jform_intId_tf :selected').val();
        jQuery('#jform_idTpoFuenteTxt').val( jQuery("#jform_intId_tf :selected").text() );
        resetForm( true );
    });
    
    /**
     *  Elimina un registro
     */
    jQuery('#delTF').click(function () {
        jConfirm( JSL_FUENTE_CONFIRM_DEL_TPO_FUENTE, JSL_ECORAE, function( op ){
            if (op) {
                eliminarTipoFuente();
            }
        });
    });
    
    /**
     *  Realiza la llamada ajax para guardar un registro
     * @returns {undefined}
     */
    function guardarTipoFuente()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var tpoFuente = JSON.stringify(list2Object(dataForm()));
        jQuery('#jform_idTpoFuenteTxt').val('');
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'guardarTipoFuente',
                option: 'com_conflictos',
                view: 'fuente',
                tmpl: 'component',
                format: 'json',
                tpoFuente: tpoFuente
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Tipo de Fuente: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( dataInfo.length > 0){
                reloadListTpoFnt( dataInfo );
                resetForm( false );
            } else {
                jAlert( JSL_FUENTE_ERROR_GUARDAR, JSL_ECORAE )
            }
            jQuery('#jform_intId_tf').trigger( 'change' );
        });
    }
    
    /**
     *  Ejecuta la llamada Ajax para elimnar un registro
     * @returns {undefined}
     */
    function eliminarTipoFuente()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var id = jQuery('#jform_intId_tf :selected').val();
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'eliminarTipoFuente',
                option: 'com_conflictos',
                view: 'fuente',
                tmpl: 'component',
                format: 'json',
                id: id
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Tipo de Fuente: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray( dataInfo ) ){
                reloadListTpoFnt( dataInfo );
            } else {
                jAlert( JSL_FUENTE_ERROR_DEL_REG, JSL_ECORAE );
            }
            jQuery('#jform_intId_tf').trigger( 'change' );
        });
    }
    
    /**
     * 
     * @returns {Array|dtaFrm}
     */
    function dataForm()
    {
        dtaFrm = new Array();

        dtaFrm["intId_tf"]          = idTipoFuente;
        dtaFrm["strDescripcion_tf"] = jQuery('#jform_idTpoFuenteTxt').val();
        dtaFrm["published"]         = 1;

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
            jQuery('#li-jform_intId_tf').css('display', 'none');
            jQuery('#li-jform_idTpoFuenteTxt').css('display', 'block');
        } else {
            jQuery('#li-jform_intId_tf').css('display', 'block');
            jQuery('#li-jform_idTpoFuenteTxt').css('display', 'none');
        }
    }
    
    /**
     * 
     * @param {type} list
     * @returns {undefined}
     */
    function reloadListTpoFnt ( list )
    {
        jQuery( '#jform_intId_tf' ).empty();
        if ( list.length > 0 ){
            jQuery('#jform_intId_tf').append(new Option( JSL_FUENTE_SELECT_TIPO_FUENTE, 0 ));
            for (var i = 0; i < list.length ; i++) {
                jQuery('#jform_intId_tf').append(new Option( list[i].nombre, list[i].id ));
            }
        } else {
            jQuery('#jform_intId_tf').append(new Option( JSL_SIN_REGISTROS, 0 ));
        }
         
    }
    
});