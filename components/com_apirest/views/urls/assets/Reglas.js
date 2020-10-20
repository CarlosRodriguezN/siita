jQuery(document).ready(function() {

    jQuery( '.apiVigencia' ).on( 'click', function(){
        var idUrl = jQuery( this ).attr( 'id' );
        var url = window.location.href;
        var path = url.split('?')[0];

        //  Obtengo URL completa del sitio
        jQuery.ajax({   type    : 'POST',
                        url     : path,
                        dataType: 'JSON',
                        data: { option  : 'com_apirest',
                                view    : 'url',
                                tmpl    : 'component',
                                format  : 'json',
                                action  : 'updVigencia',
                                idUrl   : idUrl
                        },
                        error: function(jqXHR, status, error) {
                            alert( 'Api - Rest: ' + error + ' ' + jqXHR + ' ' + status );
                        }
        }).complete(function( data ) {
            if( parseInt( data.responseText ) === 1 ){
                jQuery( '#'+idUrl ).html( '<img src= "media/system/images/siitaGestion/btnIndicadores/atributo/attrRojoSmall.png">' );
            }else{
                jQuery( '#'+idUrl ).html( '<img src= "media/system/images/siitaGestion/btnIndicadores/atributo/attrVerdeSmall.png">' );
            }
                
        });

    })

});
