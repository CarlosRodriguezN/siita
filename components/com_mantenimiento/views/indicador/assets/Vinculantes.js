jQuery( document ).ready( function(){

    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split( '?' )[0];

    /**
     * Gestiona Informacion de unidad de medida de acuerdo a un determinado 
     * "tipo" de unidad de medida
     */
    jQuery( '#jform_intIdTpoUndMedida' ).live( 'change', function( event, idUndMedida ){

        jQuery('#jform_intCodigo_unimed').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option  : 'com_indicadores',
                                view    : 'indicadores',
                                tmpl    : 'component',
                                format  : 'json',
                                action  : 'getUnidadMedida',
                                idTpoUM : jQuery( '#jform_intIdTpoUndMedida' ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( COM_INDICADORES_UNIDAD_MEDIDA + ' ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( '('+ data.responseText +')' );
                var numRegistros = dataInfo.length;
                var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idUndMedida ) !== 'undefined' ){
                        selected = ( parseInt( dataInfo[x].id ) === parseInt( idUndMedida ) )   
                                        ? 'selected'
                                        : '';
                    }else{
                        selected = ( parseInt( dataInfo[x].id ) === 0 || dataInfo[x].id === "" ) 
                                        ? 'selected'
                                        : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }

                jQuery('#jform_intCodigo_unimed').html( items.join('') );
        });
    });
    
//
//  NUEVAS VARIABLES
//
    jQuery( '#jform_idTpoUndMedidaNV' ).change( function( event, idUndMedida ){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idVarUndMedidaNV').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option  : 'com_indicadores',
                                view    : 'indicadores',
                                tmpl    : 'component',
                                format  : 'json',
                                action  : 'getUnidadMedida',
                                idTpoUM : jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Unidad Medida - Variable: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( '('+ data.responseText +')' );
            var numRegistros = dataInfo.length;
            var items = [];

            var selected = '';

            for( var x = 0; x < numRegistros; x++ ){
                if( typeof( idUndMedida ) != 'undefined' ){
                    selected = ( dataInfo[x].id == idUndMedida )? 'selected'
                                                                : '';
                }else{
                    selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                        : '';
                }

                items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
            }

            jQuery('#jform_idVarUndMedidaNV').html( items.join('') );
        });
    })

})