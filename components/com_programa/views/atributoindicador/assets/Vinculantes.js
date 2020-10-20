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
                    for( var x = 0; x < numRegistros; x++ ){
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

    /**
     *  Gestiona los cantones de una determinada provincia 
     */
    jQuery( '#jform_intIdUGResponsable' ).live( 'change', function( event, idResponsable ){
        jQuery( '#jform_idResponsable' ).html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getResponsables',
                                idUndGestion: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Atributos Indicador: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;
            var items = [];
            
            if( numRegistros > 0 ){
                items.push('<option value="0">SELECCIONE RESPONSABLE</option>');
                for( x = 0; x < numRegistros; x++ ){
                    var selected = ( dataInfo[x].id == idResponsable )  ? 'selected' 
                                                                        : '';

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                }
            } else{
                items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
            }

            jQuery('#jform_idResponsable').html( items.join('') ); 
        })
    })
    
    /**
     *  Gestiona Tipo de Unidad de Medida - Unidad de Medida
     */
    jQuery( '#jform_intIdTpoUndMedida' ).change(function( event, idUndMedida ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idUndMedida').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getUnidadMedida',
                                idTpoUM: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Unidad Medida: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
                var items = [];
                
                if( numRegistros > 0 ){
                    
                    items.push('<option value="0">SELECCIONE UNIDAD DE MEDIDA</option>');
                    var selected = '';
                    for( x = 0; x < numRegistros; x++ ){
                        selected = ( dataInfo[x].id == idUndMedida )? 'selected'
                                                                    : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idUndMedida').html( items.join('') );
        });
    });
    
    /**
     *  Gestiona Filtro de Tipo de Unidad de Medida - Unidad de Medida
     */
    jQuery( '#jform_idVarTpoUndMedida' ).change(function( event, idUndMedida ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idVarUndMedida').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getUnidadMedida',
                                idTpoUM: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Unidad Medida - Variable: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
                var items = [];
                
                if( numRegistros > 0 ){
                    
                    items.push('<option value="0">SELECCIONE UNIDAD DE MEDIDA</option>');
                    var selected = '';
                    for( x = 0; x < numRegistros; x++ ){
                        selected = ( dataInfo[x].id == idUndMedida )? 'selected'
                                                                    : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idVarUndMedida').html( items.join('') );
        });
        
    });
    
    /**
     * Gestion de Unidades de Medida de una Variable
     */
    jQuery( '#jform_idVarUndMedida' ).change( function( event, idUndMedida, idVariable ){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idVariable').html( '<option value="0">CARGANDO...</option>' );
        
        var idUM = ( typeof( idUndMedida ) == 'undefined' ) ? jQuery( this ).val()
                                                            : idUndMedida;
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getVariablesUnidadMedida',
                                idUM: idUM
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Variable Unidad Medida - Variable: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
                var items = [];
                
                if( numRegistros > 0 ){
                    var selected = '';
                    var idSelect = '';

                    if( typeOf( idVariable ) === "null" ){
                        idSelect = -1;
                    }else{
                        idSelect = idVariable;
                    }

                    for( var x = 0; x < numRegistros; x++ ){
                        selected = ( dataInfo[x].id == idSelect )   ? 'selected'
                                                                    : '';

                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                }

                jQuery('#jform_idVariable').html( items.join('') );
        });
    })
    
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
                    items.push('<option value="0">SELECCIONE DIMENSION</option>');
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
})