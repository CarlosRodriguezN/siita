jQuery(document).ready(function() {
    
    idLegitimidad = 0;
    
    /**
     * 
     */
    jQuery('#jform_intId_leg').change( function() {
        if ( jQuery( '#jform_intId_leg' ).val() == 0 ) {
            jQuery( '#addLegitimidad' ).css("display", "block");
            jQuery( '#updLegitimidad, #delLegitimidad' ).css("display", "none");
        } else {
            jQuery( '#updLegitimidad, #delLegitimidad, #addLegitimidad' ).css("display", "block");
        }
    });
    
    jQuery('#jform_intId_leg').trigger( 'change' );
    
    /**
     *  Avilita el formulario para el ingrezo de un nuevo registro
     */
    jQuery('#addLeg').click(function () {
        habilitarBtns( 2, "#saveLegitimidadActor" );
        resetForm( true );
        idLegitimidad = 0;
    });
    
    /**
     *  Cansela la gestion de un nuevo registro
     */
    jQuery('#cancelLeg').click(function () {
        resetForm( false );
        jQuery('#jform_idLegitimidadTxt').val('');
        habilitarBtns( 1, "#saveLegitimidadActor" );
    });
    
    /**
     *  Gurada un nuevo registro
     */
    jQuery('#saveLeg').click(function () {
        if ( jQuery('#jform_idLegitimidadTxt').val() != '' ) {
            habilitarBtns( 1, "#saveLegitimidadActor" );
            guardarLegitimidad();
        } else {
            jAlert( JSL_ALERT_LEGITIMIDAD_NEED, JSL_ECORAE );
        }
    });
    
    /**
     *  Avilita da edicion de un registro
     */
    jQuery('#updLeg').click(function () {
        idLegitimidad = jQuery('#jform_intId_leg :selected').val();
        jQuery('#jform_idLegitimidadTxt').val( jQuery("#jform_intId_leg :selected").text() );
        resetForm( true );
        habilitarBtns( 2, "#saveLegitimidadActor" );
    });
    
    /**
     *  Elimina un registro
     */
    jQuery('#delLeg').click(function () {
        jConfirm( JSL_FUENTE_CONFIRM_DEL_LEGITIMIDAD, JSL_ECORAE, function( op ){
            if (op) {
                eliminarLegitimidad();
            }
        });
    });
    
    /**
     *  Realiza la llamada ajax para guardar un registro
     * @returns {undefined}
     */
    function guardarLegitimidad()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var legitimidad = JSON.stringify(list2Object(dataForm()));
        jQuery('#jform_idLegitimidadTxt').val('');
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'guardarLegitimidad',
                option: 'com_conflictos',
                view: 'actor',
                tmpl: 'component',
                format: 'json',
                legitimidad: legitimidad
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Legitimidad: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray(dataInfo) ){
                reloadListLegitimidades( dataInfo );
                resetForm( false );
                if ( idLegitimidad != 0 ) {
                    updLstLgtActores( idLegitimidad, dataInfo );
                    reloadLegitimidadesActTb();
                }
            } else {
                jAlert( JSL_FUENTE_ERROR_GUARDAR, JSL_ECORAE )
            }
            jQuery('#jform_intId_leg').trigger( 'change' );
        });
    }
    
    /**
     *  Ejecuta la llamada Ajax para elimnar un registro
     * @returns {undefined}
     */
    function eliminarLegitimidad()
    {
        jQuery.blockUI({ message: jQuery('#msgProgress') });
        var id = jQuery('#jform_intId_leg :selected').val();
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'eliminarLegitimidad',
                option: 'com_conflictos',
                view: 'actor',
                tmpl: 'component',
                format: 'json',
                id: id
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Conflictos - Gestión de Legitimidad: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            jQuery.unblockUI();
            var dataInfo = eval("(" + data.responseText + ")");
            if ( Array.isArray(dataInfo) ){
                reloadListLegitimidades( dataInfo );
            } else {
                jAlert( JSL_FUENTE_ERROR_DEL_REG, JSL_ECORAE );
            }
            jQuery('#jform_intId_leg').trigger( 'change' );
        });
    }
    
    /**
     * 
     * @returns {Array|dtaFrm}
     */
    function dataForm()
    {
        dtaFrm = new Array();

        dtaFrm["intId_leg"]             = idLegitimidad;
        dtaFrm["strDescripcion_leg"]    = jQuery('#jform_idLegitimidadTxt').val();
        dtaFrm["published"]             = 1;

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
            jQuery('#li-jform_intId_leg').css('display', 'none');
            jQuery('#li-jform_idLegitimidadTxt').css('display', 'block');
        } else {
            jQuery('#li-jform_intId_leg').css('display', 'block');
            jQuery('#li-jform_idLegitimidadTxt').css('display', 'none');
        }
    }
    
    /**
     * 
     * @param {type} list
     * @returns {undefined}
     */
    function reloadListLegitimidades ( list )
    {
        jQuery( '#jform_intId_leg' ).empty();
        if ( list.length ) {
            jQuery('#jform_intId_leg').append(new Option( JSL_FUENTE_SELECT_LEGITIMIDAD, 0 ));
            for (var i = 0; i < list.length ; i++) {
                jQuery('#jform_intId_leg').append(new Option( list[i].nombre, list[i].id ));
            }
        } else {
            jQuery('#jform_intId_leg').append(new Option( JSL_SIN_REGISTROS, 0 ));
        }
    }
    
    /**
     * 
     * @param {type} id
     * @param {type} list
     * @returns {undefined}
     */
    function updLstLgtActores( id, list ){
        var reg = -1;
        for (var i = 0; i < list.length; i++) {
            if ( list[i].id == id ) {
                reg = i;
            }
        }
        
        if ( reg != -1 ) {
            for ( var j = 0; j < oActor.lstLegitimidad.length; j++ ) {
                if ( oActor.lstLegitimidad[j].idLegitimidad == id )
                    oActor.lstLegitimidad[j].nmbLegitimidad = list[reg].nombre;
            }
        }
    }
    
});