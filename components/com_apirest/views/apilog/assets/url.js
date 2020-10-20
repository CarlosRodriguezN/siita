jQuery( document ).ready( function(){
    
    var banDocumento = false;
    var url = window.location.href;
    var path = url.split('?')[0];
    
     /**  Inicializando el botón IMAGEN **/
    jQuery('#docUpLoad').uploadifive({
        'auto'          : false,
        'buttonText'    : 'Seleccionar Documento',
        'dnd'           : false,
        'fileSizeLimit' : 2048,
        'width'         : 250,
        'queueSizeLimit': 1,
        'multi'         : false,
        'fileType'      : ['image\/gif', 'image\/jpeg', 'image\/png'],
        'uploadScript'  : 'index.php',

        'onSelect': function( file ) {
            banDocumento = true;
        },

        'onUploadComplete': function( file, data ){
            location.href = 'http://' + window.location.host + '/index.php?option=com_apirest';
        }
    });
    
    
    //
    //  Carga Imagen
    //
    Joomla.submitbutton = function( task ) {
        if( task === "url.registar" ){
            var dtaFrm = [];
            
            dtaFrm["intIdApiUrl"]         = jQuery('#jform_intIdApiUrl').val();
            dtaFrm["intCodigo_ins"]       = jQuery('#jform_intCodigo_ins').val();
            dtaFrm["strNombres_api"]      = jQuery('#jform_strNombres_api').val();
            dtaFrm["strCorreo_api"]       = jQuery('#jform_strCorreo_api').val();
            dtaFrm["strIPInstitucion_api"]= jQuery('#jform_strIPInstitucion_api').val();
            dtaFrm["dteFechaInicio_api"]  = jQuery('#jform_dteFechaInicio_api').val();
            dtaFrm["dteFechaFin_api"]     = jQuery('#jform_dteFechaFin_api').val();

            //  Obtengo URL completa del sitio
            jQuery.ajax({   type    : 'POST',
                            url     : path,
                            dataType: 'JSON',
                            data: { option  : 'com_apirest',
                                    view    : 'url',
                                    tmpl    : 'component',
                                    format  : 'json',
                                    action  : 'registrarUrl',
                                    dataFrm : JSON.stringify( list2Object( dtaFrm ) )
                            },
                            error: function(jqXHR, status, error) {
                                alert( 'Api - Rest: ' + error + ' ' + jqXHR + ' ' + status );
                            }
            }).complete(function( data ) {

                if( banDocumento === true ){
                    //  Carga Imagen
                    var optionsImagen = {   method      : "POST",
                                            option      : "com_apirest",
                                            controller  : "url",
                                            task        : "url.saveFiles",
                                            tmpl        : "component",
                                            typeFileUpl : "documento",
                                            fileObjName : "documento",
                                            idUrl       : data.responseText
                    };

                    jQuery('#docUpLoad').data('uploadifive').settings.formData = optionsImagen;
                    jQuery('#docUpLoad').uploadifive('upload');
                }else{
                    location.href = 'http://' + window.location.host + '/index.php?option=com_apirest';
                }
                
            });
        }else{
            Joomla.submitform(task);
        }
    }
    
    
    jQuery( '.delDocumento' ).on( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idDocumento  = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function( result ){
            if( result ){
                if( delArchivo( idDocumento ) ){

                    //  Elimino Fila de la tabla de Rangos
                    delFilaRG( idDocumento );
                }
            }
        });
    })
    
    
    function delArchivo( idDocumento )
    {
        var ban = false;

        //  Obtengo URL completa del sitio
        jQuery.ajax({   type    : 'POST',
                        url     : path,
                        dataType: 'JSON',
                        data: { option      : 'com_apirest',
                                view        : 'url',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'delDocumento',
                                idDocumento : idDocumento
                        },
                        error: function(jqXHR, status, error) {
                            alert( 'Api - Rest: ' + error + ' ' + jqXHR + ' ' + status );
                        }
        }).complete(function( data ) {
            if( data.responseText ){
                jAlert( 'Eliminado con exito' );
            }
        })
        
        return ban;
    }
    
    
    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaRG( idFila ){
        //  Elimino fila de la tabla lista de Documentos
        jQuery( '#lstDocumentos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) === idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    /**
     * 
     *  Transforma un Array en Objecto de manera Recursiva
     *  
     *  @param {type} list
     *  @returns {unresolved}
     */
    function list2Object(list)
    {
        var obj = {};
        for (key in list) {
            if (typeof (list[key]) == 'object') {
                obj[key] = list2Object(list[key]);
            } else {
                obj[key] = list[key];
            }
        }

        return obj;
    }

})