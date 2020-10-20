var url = window.location.href;
var path = url.split('?')[0];

jQuery( document ).ready(function(){
    //
    //  Gestiona el retorno de los cantones de una determinada 
    //
    jQuery( '#jform_idProvincia' ).change(function( event, idCanton ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idCanton').html( '<option value="0">CARGANDO...</option>' );
        
        //  EnceroCombo Parroquias
        enCerarCombo(jQuery('#jform_idParroquia option'));
                        
        jQuery.ajax({   type: 'GET',
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
                    items.push('<option value="0">SELECCIONE CANT&Oacute;N</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        
                        var selected = ( dataInfo[x].id == idCanton )   ? 'selected' 
                                                                        : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idCanton').html( items.join('') );
        });
    });
    
    //
    //  Gestiona Combo Parroquias
    //
    jQuery( '#jform_idCanton' ).change(function( event, idCanton, idParroquia ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
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
        });
    });
    
    //
    //  Gestiona Combo Objetivo Nacional, y actualiza combo de politicas
    //
    jQuery( '#jform_intcodigo_on' ).change(function( event, idObjetivo, idPolitica ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        var dataIdObjetivo = ( typeof( idObjetivo ) !== "undefined" )   ? idObjetivo 
                                                                        : jQuery( this ).val();
        
        jQuery('#jform_intcodigo_pn').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getPoliticaNacional',
                                idObjNac: dataIdObjetivo
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Politica Nacional: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
                var banPN = false;

                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE POLITICA NACIONAL</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        
                        var selected = ( dataInfo[x].id == idPolitica )? 'selected'
                                                                        : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_intcodigo_pn').html( items.join('') );
                
                //  Ajusto el tamaño del comboBox
                jQuery('#jform_intcodigo_pn').css( 'width', '375px' );
        });
    });
    
    //
    //  Gestiona Combo Politica Nacional
    //
    jQuery( '#jform_intcodigo_pn' ).change(function( event, idObjetivo, idPolitica, idMeta ){

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        var dataIdObjetivo = ( typeof( idObjetivo ) != "undefined" )? idObjetivo 
                                                                    : jQuery( '#jform_intcodigo_on' ).val();
                                                                    
        var dataIdPolitica = ( typeof( idPolitica ) != "undefined" )? idPolitica 
                                                                    : jQuery( '#jform_intcodigo_pn' ).val();

        jQuery('#jform_idcodigo_mn').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getMetaNacional',
                                idObjNac: dataIdObjetivo,
                                idPolNac: dataIdPolitica
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Meta Nacional: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE META NACIONAL</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        
                        var selected = ( dataInfo[x].id == idMeta ) ? 'selected' 
                                                                    : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                        items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idcodigo_mn').html( items.join('') );
                
                //  Ajusto el tamaño del comboBox
                jQuery('#jform_idcodigo_mn').css( 'width', '375px' );
        });
    });
    
    //
    //  Gestiona MacroSector, para que retorne subSectores
    //
    jQuery( '#jform_macrosector' ).change(function(){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_sector').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getSectores',
                                idMacroSector: jQuery( this ).val(),
                                idSIV: jQuery( "#jform_intIdStr_intervencion" ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Sub Sector: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];

                if( numRegistros > 0 ){
                    for( x = 0; x < numRegistros; x++ ){

                        var selected = ( dataInfo[x].id == 0 )  ?   'selected'
                                                                :   '';

                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                }

                jQuery('#jform_sector').html( items.join('') );
        });
    });
    
    //
    //  Gestiona Sector, para que retorne subSectores
    //
    jQuery( '#jform_sector' ).change(function(){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_subsector').html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getSubSector',
                                idSector: jQuery( this ).val(),
                                idSIV: jQuery( "#jform_intIdStr_intervencion" ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Sub Sector: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];

                if( numRegistros > 0 ){
                    for( x = 0; x < numRegistros; x++ ){

                        var selected = ( dataInfo[x].id == 0 )  ?   'selected'
                                                                :   '';

                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                }

                jQuery('#jform_subsector').html( items.join('') );
        });
    });
    
    //
    //  Gestiona Tipo de Unidad de Medida - Unidad de Medida
    //
    jQuery( '#jform_mtaTipoUndMedida' ).change(function(){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_mtaUndMedida').html( '<option value="0">CARGANDO...</option>' );
        
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
                            alert( 'Proyectos - Gestion Unidad Medida: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE UNIDAD DE MEDIDA</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_mtaUndMedida').html( items.join('') );
        });
    });
    
    //
    //  Gestiona Enfoque de Igualdad
    //
    jQuery( '#jform_cbEnfoqueIgualdad' ).change(function( event, idTpoEnfoque, idEnfoque ){

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idEnfoqueIgualdad').html( '<option value="0">CARGANDO...</option>' );

        var dataIdTpoEnf = ( typeof( idTpoEnfoque ) != "undefined" )    ? idTpoEnfoque 
                                                                        : jQuery( '#jform_cbEnfoqueIgualdad' ).val();

        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getTiposEnfoqueIgualdad',
                                idTipoEnfoque: dataIdTpoEnf
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Enfoque Igualdad: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE ENFOQUE DE IGUALDAD</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        var selected = ( dataInfo[x].id == idEnfoque )  ? 'selected' 
                                                                        : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idEnfoqueIgualdad').html( items.join('') );
        });
    });
    
    
    //
    //  Gestiona unidades de medida de un tipo de unidad de medida en otros indicadores
    //
    jQuery( '#jform_idTpoUndMedida' ).change(function( event, idTpoUM, umInd ){

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idUndMedidaMetaNewInd').html( '<option value="0">CARGANDO...</option>' );

        var dataTpoUM = ( typeof( idTpoUM ) != "undefined" )    ? idTpoUM 
                                                                : jQuery( '#jform_idTpoUndMedida' ).val();

        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getUnidadMedidaTipo',
                                idTpoUndMedida: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Unidad de Medida: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">Seleccione Unidad de Medida</option>');
                    for( x = 0; x < numRegistros; x++ ){
                        
                        var selected = ( dataInfo[x].id == umInd )  ? 'selected' 
                                                                        : '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                } else{
                    items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
                }

                jQuery('#jform_idUndMedidaMetaNewInd').html( items.join('') );
        });
    });


    //
    //  Gestiono los Sub Programas pertenecientes a un determinado SubPrograma
    //
    jQuery( '#jform_intCodigo_prg' ).change( function(){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_intCodigo_sprg').html( '<option value="0">CARGANDO...</option>' );

        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getSubProgramas',
                                idPrograma: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Sub Programas: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( '('+ data.responseText +')' );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    for( var x = 0; x < numRegistros; x++ ){
                        var selected = ( dataInfo[x].id === 0 ) ?   'selected'
                                                                :   '';
                        
                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                }

                jQuery('#jform_intCodigo_sprg').html( items.join('') );
        });
    });
    
    
    //
    //  Gestiono los Sub Programas pertenecientes a un determinado SubPrograma
    //
    jQuery( '#jform_intCodigo_sprg' ).change( function(){
        //  Obtengo URL completa del sitio
        jQuery('#jform_intCodigo_tsprg').html( '<option value="0">CARGANDO...</option>' );

        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getTiposSubProgramas',
                                idSubPrograma: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Tipos de Sub Programas: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( data.responseText );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    for( x = 0; x < numRegistros; x++ ){
                        var selected = ( dataInfo[x].id == 0 )  ?   'selected'
                                                                :   '';

                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                    }
                }

                jQuery( '#jform_intCodigo_tsprg' ).html( items.join('') );
        });
    });
    
     jQuery('#jform_intIdUGResponsable').live('change', function(event, idResponsable) {
        jQuery('#jform_idResponsable').html('<option value="0">' + COM_LIST_LOAD + '</option>');

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_proyectos',
                view: 'proyecto',
                tmpl: 'component',
                format: 'json',
                action: 'getResponsablesUG',
                idUndGestion: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert( 'Responsable Unidad de Gestion' + error + ' ' + jqXHR + ' ' + status );
            }
        }).complete(function(data) {
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;

            if (numRegistros > 0) {
                var items = [];

                var selected = '';

                for (var x = 0; x < numRegistros; x++) {
                    if (typeof(idEntidad) != 'undefined') {
                        selected = (dataInfo[x].id === idEntidad)   ? 'selected'
                                                                    : '';
                    } else {
                        selected = (dataInfo[x].id === 0)   ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + ' >' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idResponsable').html(items.join(''));
        });
    });

    
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
});