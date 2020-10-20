jQuery( document ).ready(function(){
    //  Ajusto el tamaño del comboBox Objetivo Nacional
    jQuery('#cbLstProgramas').css( 'width', '300px' );
    jQuery('#cbLstProyectos').css( 'width', '300px' );
    
    //
    //  Gestiona el retorno de los cantones de una determinada 
    //
    jQuery( '#cbLstProgramas' ).change(function(){
        
        if( jQuery( this ).val() > 0 ){
            //  Obtengo URL completa del sitio
            var url = window.location.href;
            var path = url.split( '?' )[0];

            jQuery('#cbLstProyectos').html( '<option value="0">CARGANDO...</option>' );

            jQuery.ajax({   type: 'GET',
                            url: path,
                            dataType: 'JSON',
                            data:{  option      : 'com_proyectos',
                                    view        : 'canastaproyectos',
                                    tmpl        : 'component',
                                    format      : 'json',
                                    action      : 'getProyectos',
                                    idPrograma  : jQuery( this ).val()
                            },
                            error : function( jqXHR, status, error ) {
                                alert( 'Canasta de Proyectos - Gestion Proyectos: ' + error +' '+ jqXHR +' '+ status );
                            }
            }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
                var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                        : '';

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }

                jQuery('#cbLstProyectos').html( items.join( '' ) );
            })
        }else{
            enCerarComboPry( jQuery( '#cbLstProyectos option' ) );
        }  
    })
    
    
    
    /**
     * 
     * Vacia en contenido de un determinado comboBox
     * 
     * @param {type} combo
     * @returns {undefined}
     */
    function enCerarComboPry(combo)
    {
        //  Recorro contenido del combo
        jQuery(combo).each(function() {
            if (jQuery(this).val() > 0 ) {
                //  Actualizo contenido del combo
                jQuery(this).remove();
            }
        });
    }
    
    
})