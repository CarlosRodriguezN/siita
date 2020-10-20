jQuery( document ).ready(function(){
    //
    //  Gestiona el retorno de los grupos pertenecientes a una determinada categoria 
    //
    jQuery( '#jform_strCodigoCategoria' ).change(function(){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_intCodigo_grp').html( '<option value="0">CARGANDO...</option>' );
                        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_mantenimiento',
                                view: 'beneficiario',
                                tmpl: 'component',
                                format: 'json',
                                action: 'lstGrupos',
                                idCategoria: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            jAlert( 'Mantenimiento - Gestion Beneficiarios: ' + error +' '+ jqXHR +' '+ status, 'SIITA - ECORAE' );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE GRUPO</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_intCodigo_grp').html( items.join('') );
        })
    })
})