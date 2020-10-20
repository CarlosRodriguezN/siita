jQuery(document).ready(function() {
    
    idIncidencia = 0;
    
    /**
     * 
     */
    jQuery('#jform_intId_inc').change( function() {
        if ( jQuery( '#jform_intId_inc' ).val() == 0 ) {
            jQuery( '#addIncidencia' ).css("display", "block");
            jQuery( '#updIncidencia, #delIncidencia' ).css("display", "none");
        } else {
            jQuery( '#updIncidencia, #delIncidencia, #addIncidencia' ).css("display", "block");
        }
    });
    
    jQuery('#jform_intId_inc').trigger( 'change' );
    
    /**
     *  Avilita el formulario para el ingrezo de un nuevo registro
     */
    jQuery('#addInc').click(function () {
        habilitarBtns( 2, "#saveIncidenciaFuente" );
        resetForm( true );
        idIncidencia = 0;
    });
    
    /**
     *  Cansela la gestion de un nuevo registro
     */
    jQuery('#cancelInc').click(function () {
        resetForm( false );
        jQuery('#jform_idIncidenciaTxt').val('');
        habilitarBtns( 1, "#saveIncidenciaFuente" );
    });
    
    /**
     *  Gurada un nuevo registro
     */
    jQuery('#saveInc').click(function () {
        if ( jQuery('#jform_idIncidenciaTxt').val() != '' ) {
            habilitarBtns( 1, "#saveIncidenciaFuente" );
            guardarIncidencia();
        } else {
            jAlert( JSL_ALERT_INCIDENCIA_NEED, JSL_ECORAE );
        }
    });
    
    /**
     *  Avilita da edicion de un registro
     */
    jQuery('#updInc').click(function () {
        idIncidencia = jQuery('#jform_intId_inc :selected').val();
        jQuery('#jform_idIncidenciaTxt').val( jQuery("#jform_intId_inc :selected").text() );
        resetForm( true );
        habilitarBtns( 2, "#saveIncidenciaFuente" );
    });
    
    /**
     *  Elimina un registro
     */
    jQuery('#delInc').click(function () {
        jConfirm( JSL_FUENTE_CONFIRM_DEL_INCIDENCIA, JSL_ECORAE, function( op ){
            if (op) {
                eliminarIncidencia();
            }
        });
    });
    
    /**
     *  Realiza la llamada ajax para guardar un registro
     * @returns {undefined}
     */
    function guardarIncidencia()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var incidencia = JSON.stringify(list2Object(dataForm()));
        jQuery('#jform_idIncidenciaTxt').val('');
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'guardarIncidencia',
                option: 'com_conflictos',
                view: 'fuente',
                tmpl: 'component',
                format: 'json',
                incidencia: incidencia
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Incidencia: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray( dataInfo ) ){
                reloadListInicidencias( dataInfo );
                resetForm( false );
                if ( idIncidencia != 0 ) {
                    updLstInsFnt( idIncidencia, dataInfo );
                    reloadIncidenciasFntTb();
                }
            } else {
                jAlert( JSL_FUENTE_ERROR_GUARDAR, JSL_ECORAE )
            }
            jQuery('#jform_intId_inc').trigger( 'change' );
        });
    }
    
    /**
     *  Ejecuta la llamada Ajax para elimnar un registro
     * @returns {undefined}
     */
    function eliminarIncidencia()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var id = jQuery('#jform_intId_inc :selected').val();
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'eliminarIncidencia',
                option: 'com_conflictos',
                view: 'fuente',
                tmpl: 'component',
                format: 'json',
                id: id
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Incidencia: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray( dataInfo ) ){
                reloadListInicidencias( dataInfo );
            } else {
                jAlert( JSL_FUENTE_ERROR_DEL_REG, JSL_ECORAE );
            }
            jQuery('#jform_intId_inc').trigger( 'change' );
        });
    }
    
    /**
     * 
     * @returns {Array|dtaFrm}
     */
    function dataForm()
    {
        dtaFrm = new Array();

        dtaFrm["intId_inc"]     = idIncidencia;
        dtaFrm["strNombre_inc"] = jQuery('#jform_idIncidenciaTxt').val();
        dtaFrm["published"]     = 1;

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
            jQuery('#li-jform_intId_inc').css('display', 'none');
            jQuery('#li-jform_idIncidenciaTxt').css('display', 'block');
        } else {
            jQuery('#li-jform_intId_inc').css('display', 'block');
            jQuery('#li-jform_idIncidenciaTxt').css('display', 'none');
        }
    }
    
    /**
     * 
     * @param {type} list
     * @returns {undefined}
     */
    function reloadListInicidencias ( list )
    {
        jQuery( '#jform_intId_inc' ).empty();
        if ( list.length > 0 ){
            jQuery('#jform_intId_inc').append(new Option( JSL_FUENTE_SELECT_INCIDENCIA, 0 ));
            for (var i = 0; i < list.length ; i++) {
                jQuery('#jform_intId_inc').append(new Option( list[i].nombre, list[i].id ));
            }
        } else {
            jQuery('#jform_intId_inc').append(new Option( JSL_SIN_REGISTROS, 0 ));
        }
    }
    
    /**
     *  Actualiza los registros afectados por la actulizacion de alguna incidencia
     * @param {type} id
     * @param {type} list
     * @returns {undefined}
     */
    function updLstInsFnt( id, list ){
        var reg = -1;
        for (var i = 0; i < list.length; i++) {
            if ( list[i].id == id ) {
                reg = i;
            }
        }
        
        if ( reg != -1 ) {
            for ( var j = 0; j < oFuente.lstIncidencias.length; j++ ) {
                if ( oFuente.lstIncidencias[j].idIndidencia == id )
                    oFuente.lstIncidencias[j].nmbIncidencia = list[reg].nombre;
            }
        }
    }
    
});