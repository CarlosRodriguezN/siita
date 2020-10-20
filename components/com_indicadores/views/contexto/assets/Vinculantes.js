jQuery( document ).ready( function(){

    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split( '?' )[0];

    /**
     *  "Indicador" - Gestiona informacion de Funcionarios de una determinadad Unidad de Gestion por "Indicador"
     */
    jQuery( '#jform_intIdUGResponsable' ).live( 'change', function( event, idResponsable ){
        jQuery( '#jform_idResponsable' ).html( '<option value="0">'+ COM_CONTEXTOS_CARGANDO +'</option>' );
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option  : 'com_indicadores',
                                view    : 'indicadores',
                                tmpl    : 'component',
                                format  : 'json',
                                action  : 'getResponsablesVariable',
                                idUndGestion: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( COM_CONTEXTO_FUNCIONARIO_UG + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;
            
            if( numRegistros > 0 ){
                var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idResponsable ) != 'undefined' ){
                        selected = ( dataInfo[x].id == idResponsable )  ? 'selected'
                                                                        : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idResponsable').html( items.join('') ); 
        })
    })
    
    //
    //  INDICADOR - CONTEXTOS
    //
    jQuery( '#jform_idTpoEntidad' ).change( function( event, idEntidad ){
        
        jQuery('#jform_idEntidad').html( '<option value="0">'+ COM_CONTEXTOS_CARGANDO +'</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'contexto',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getLstEntidad',
                                idTpoEntidad: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            jAlert( COM_CONTEXTOS_GESTION_TIPO_ENTIDAD + ' ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;
            
            if( numRegistros > 0 ){
                var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idEntidad ) != 'undefined' ){
                        selected = ( dataInfo[x].id == idEntidad )  ? 'selected'
                                                                    : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }

                jQuery('#jform_idEntidad').html( items.join('') );
            }
        });
    })
    
    
    /**
     * Gestiona la informacion de Indicadores asociados a una entidad ( PEI - POA - Programa - Proyecto - Contrato )
     */
    jQuery( '#jform_idEntidad' ).change( function( event, idEntidad, idIndicador ){
        jQuery('#jform_idIndicador').html( '<option value="0">'+ COM_CONTEXTOS_CARGANDO +'</option>' );
        
        var dtaIdEntidad = ( parseInt( jQuery( this ).val() ) !== 0 )   ? jQuery( this ).val()
                                                                        : idEntidad;
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'contexto',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getIndicadoresPorEntidad',
                                idEntidad   : dtaIdEntidad
                        },
                        error : function( jqXHR, status, error ) {
                            jAlert( COM_CONTEXTOS_GESTION_ENTIDAD + ' ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;
            
            if( numRegistros > 0 ){
                var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idIndicador ) != 'undefined' ){
                        selected = ( dataInfo[x].id == idIndicador )? 'selected'
                                                                    : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }

                jQuery('#jform_idIndicador').html( items.join('') );
            }
        });
    })
    
    
    /**
     * Gestiona Informacion de Unidades de Gestion Responsables - 
     * Funcionarios Responsables de un determinado Indicador
     */
    jQuery( '#jform_idIndicador' ).change( function( event, idEntidad, idIndicador ){

        jQuery( '#jform_UGResponsable' ).attr( 'value', '' );
        jQuery( '#jform_ResponsableUG' ).attr( 'value', '' );
        jQuery( '#jform_funcionario' ).attr( 'value', '' );

        var dataIdEntidad = ( typeof( idEntidad ) !== "undefined" ) ? idEntidad 
                                                                    : jQuery( '#jform_idEntidad' ).val();

        var dataIdIndicador = ( typeof( idIndicador ) !== "undefined" ) ? idIndicador 
                                                                        : jQuery( '#jform_idIndicador' ).val();

        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getResponsablesIndicador',
                                idEntidad   : dataIdEntidad,
                                idIndicador : dataIdIndicador
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Variable Unidad Medida - Variable: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                if( dataInfo.length > 0 ){
                    jQuery( '#jform_UGResponsable' ).attr( 'value', dataInfo[0].UGResponsable );
                    jQuery( '#jform_ResponsableUG' ).attr( 'value', dataInfo[0].responsableUG );
                    jQuery( '#jform_funcionario' ).attr( 'value', dataInfo[0].responsable );
                }
        });
    })
    
    /**
     * 
     * Elimina valores de un combo determinado
     * 
     * @param {type} combo
     * @returns {undefined}
     */
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
    
    
    /**
     * 
     * Recorro un determinado comboBox a una determinada posicion
     * 
     * @param {Objeto} combo        Combo a recorrer
     * @param {type} posicion       Posicion
     * 
     * @returns {undefined}
     * 
     */
    function recorrerCombo( combo, posicion )
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        });
    }
})