jQuery( document ).ready( function(){

    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split( '?' )[0];

    /**
     *  Gestiona los cantones de una determinada provincia 
     */
    jQuery( '#jform_idProvincia' ).live( 'change', function( event, idCanton ){
        jQuery( '#jform_idCanton' ).html( '<option value="0">CARGANDO...</option>' );
        
        //  EnceroCombo Parroquias
        enCerarCombo(jQuery('#jform_idParroquia option'));
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getCantones',
                                idProvincia: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Provincias: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE CANTON</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        var selected = ( dataInfo[x].id == idCanton )   ? 'selected' 
                                                                        : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idCanton').html( items.join('') );
        })
        
    })

    /**
     *  Gestiona Combo Parroquias
     */
    jQuery( '#jform_idCanton' ).live( 'change', function( event, idCanton, idParroquia ){
        jQuery('#jform_idParroquia').html( '<option value="0">CARGANDO...</option>' );
        
        var dataIdCanton = ( typeof( idCanton ) != "undefined" )? idCanton 
                                                                : jQuery( this ).val();
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getParroquias',
                                idCanton: dataIdCanton
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Cantones: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;

            var items = [];
            if( numRegistros > 0 ){
                items.push('<option value="0">SELECCIONE PARROQUIA</option>');
                for( x = 0; x < numRegistros; x++ ){

                    var selected = ( dataInfo[x].id == idParroquia )? 'selected'
                                                                    : '';

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                }
            } else{
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_idParroquia').html( items.join('') );
        })
    })
    
    
    //  Elimina valores de un combo determinado
    function enCerarCombo(combo)
    {
        //  Recorro contenido del combo
        jQuery(combo).each(function() {
            if (jQuery(this).val() > 0) {
                //  Actualizo contenido del combo
                jQuery(this).remove();
            }
        });
    }
    
})

