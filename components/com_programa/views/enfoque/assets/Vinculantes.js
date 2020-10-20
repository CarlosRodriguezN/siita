jQuery( document ).ready( function(){
    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split( '?' )[0];

    /**
     *  Gestiona los dimensiones de un determinado enfoque
     */
    jQuery( '#jform_idEnfoque' ).live( 'change', function( event, idDimension ){
        jQuery( '#jform_idDimension' ).html( '<option value="0">CARGANDO...</option>' );
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getDimensiones',
                                idEnfoque: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Enfoque: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE CANTON</option>');
                    for( var x = 0; x < numRegistros; x++ ){

                        var selected = ( dataInfo[x].id == idDimension )? 'selected' 
                                                                        : '';

                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idDimension').html( items.join('') );
        })
    })
})

