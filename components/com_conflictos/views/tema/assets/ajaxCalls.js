var url = window.location.href;
var path = url.split('?')[0];

jQuery(document).ready(function() {


    /**
     * recuperando las fuentes de un tena segun el tipo
     */
    jQuery("#jform_fuente_intId_tf").change(function(event, id) {
        jQuery('#jform_fuente_intId_fte').html( '<option value="0"> ' + JSL_CARGANDO + ' </option>' );
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getFuentes',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                idTipoFuente: jQuery('#jform_fuente_intId_tf').val()
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval("(" + data.responseText + ")");
            var items = [];
            var numRegistros = dataInfo.length;
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_TEMA_SELECT_FUENTE + '</option>');
                for (var x = 0; x < numRegistros; x++) {
                    var selected = ( dataInfo[x].idFuente == id ) ? 'selected': '';
                    items.push('<option value="' + dataInfo[x].idFuente + '" ' + selected + '>' + dataInfo[x].descripcion + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }
            jQuery('#jform_fuente_intId_fte').html(items.join(''));
        });
    });

    /**
     * 
     */
    jQuery( '#jform_undTerr_provicia' ).change(function( event, idCanton ){
        jQuery('#jform_undTerr_canton').html( '<option value="0"> ' + JSL_CARGANDO + ' </option>' );
        //  EnceroCombo Parroquias
        enCerarCombo(jQuery('#jform_undTerr_parroquia option'));
        
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getCantonesContrato',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idProvincia: jQuery( this ).val()
            },
            error: function(jqXHR, status, error) {
                alert('Conflictos - Gestion Provincias: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0"> ' + JSL_SELECT_CANTON + ' </option>');
                    for( x = 0; x < numRegistros; x++ ){
                        var selected = ( dataInfo[x].id == idCanton )   ? 'selected' 
                                                                        : '';
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0"> ' + JSL_SIN_REGISTROS + ' </option>');
                }
                jQuery('#jform_undTerr_canton').html( items.join('') );
                
                if (regUnidadTerritorial != -1) {
                data = getUnidadTerritorial(regUnidadTerritorial);
                if (data) {
                    recorrerCombo(jQuery("#jform_undTerr_canton option"), data.idCanton);
                    jQuery("#jform_undTerr_canton").trigger("change");
                }

            }
        });
    });
    
    /**
     * carga de cantones.
     */
    jQuery("#jform_undTerr_canton").change(function() {
        jQuery('#jform_undTerr_parroquia').html( '<option value="0">' + JSL_CARGANDO + '</option>' );
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getParroquias',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idCanton: jQuery( this ).val()
            },
            error: function(jqXHR, status, error) {
                alert('Conflictos - Gestion Cantones: '+ error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;
            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_SELECT_PARROQUIA + '</option>');
                for (x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }
            jQuery('#jform_undTerr_parroquia').html(items.join(''));
            if (regUnidadTerritorial != -1) {
                data = getUnidadTerritorial(regUnidadTerritorial);
                if (data) {
                    recorrerCombo(jQuery("#jform_undTerr_parroquia option"), data.idParroquia);
                }

            }
        });
    });

    //<editor-fold defaultstate="collapsed" desc="eliminacion del archivo de un actor">

    /**
     *  eliminar un archivo de unn actor
     */
    jQuery(".delDocActDeta").live("click", function() {
        var regDocActDetaDel = this.parentNode.parentNode.id;
        jConfirm(JSL_COM_CONFLICTOS_DEL_ARCHIVO_ACTOR, JSL_ECORAE, function(r) {
            if (r) {
                var data = getArchivoActorByReg(regActDeta, regDocActDetaDel);
                delArchivoActorDetalle(data);
            } else {
            }
        });
    })


    /**
     * Obtiene el archivo de un actor
     * @param {type} regActDeta
     * @param {type} regDocActDetaDel
     * @returns {_L4.getArchivoActorByReg.data}
     */
    function  getArchivoActorByReg(regActDeta, regDocActDetaDel) {
        var data = null;
        for (var j = 0; j < oTema.lstActDeta.length; j++) {
            if (oTema.lstActDeta[j].regActDeta == regActDeta) {
                for (var k = 0; k < oTema.lstActDeta[j].lstArchivosActor.length; k++) {
                    if (oTema.lstActDeta[j].lstArchivosActor[k].regArchivoActor == regDocActDetaDel)
                        data = {
                            idActorDetalle: oTema.lstActDeta[j].idActorDetalle,
                            archivoActor: oTema.lstActDeta[j].lstArchivosActor[k]
                        }
                }
            }

        }
        return data;
    }

    /**
     * 
     * @param {type} data       Data del archivo a ser eliminado
     * @returns {undefined}
     */
    function delArchivoActorDetalle(data) {
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'delActorArchivo',
                option: 'com_conflictos',
                view: 'tema',
                tmpl: 'component',
                format: 'json',
                dataArchivo: data,
                idTema: oTema.idTema
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            reloadDocActorDetalle();
        });
    }
    
    //</editor-fold>
       
});
