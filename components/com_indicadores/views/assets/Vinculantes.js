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
                var dataInfo = eval( '('+ data.responseText +')' );
                var numRegistros = dataInfo.length;
                var items = [];
                
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE CANT&Oacute;N</option>');
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
            var dataInfo = eval( '('+ data.responseText +')' );
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
     * Gestiona Informacion de unidad de medida de acuerdo a un determinado 
     * "tipo" de unidad de medida
     */
    jQuery( '#jform_intIdTpoUndMedida' ).live( 'change', function( event, idUndMedida ){

        jQuery('#jform_idUndMedida').html( '<option value="0">CARGANDO...</option>' );
        
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
                    if( typeof( idUndMedida ) != 'undefined' ){
                        selected = ( dataInfo[x].id == idUndMedida )? 'selected'
                                                                    : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }

                jQuery('#jform_idUndMedida').html( items.join('') );
        });
    });

    /**
     *  "Indicador" - Gestiona informacion de Funcionarios de una determinadad Unidad de Gestion por "Indicador"
     */
    jQuery( '#jform_intIdUGResponsable' ).live( 'change', function( event, idResponsable ){
        jQuery( '#jform_idResponsable' ).html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getFuncionariosResponsables',
                                idUndGestion: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( COM_INDICADORES_FUNCIONARIOS + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( '('+ data.responseText +')' );
            var numRegistros = dataInfo.length;
            
            if( numRegistros > 0 ){
                var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idResponsable ) !== 'undefined' ){
                        selected = ( parseInt( dataInfo[x].id ) === parseInt( idResponsable ) ) ? 'selected'
                                                                                                : '';
                    }else{
                        selected = ( parseInt( dataInfo[x].id ) === 0 ) ? 'selected'
                                                                        : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idResponsable').html( items.join('') ); 
        })
    })
    
    
    
    //
    //  INDICADOR - CONTEXTOS
    //
    jQuery( '#jform_idTpoEntidad' ).change( function( event, idEntidad ){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        var dtaTpoEntidad = jQuery('#jform_idTpoEntidad :selected').text().toLowerCase();       
        var tpoEntidad = dtaTpoEntidad.replace(/^[a-z]/, function(m){ return m.toUpperCase() });

        jQuery('#jform_idEntidad').html( '<option value="0">'+ COM_INDICADORES_CARGANDO +'</option>' );
        jQuery( '#jform_idEntidad-lbl' ).html( tpoEntidad + ' '+ '<span class="star">&nbsp;*</span>' );

        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getLstEntidad',
                                idTpoEntidad: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( COM_INDICADORES_GESTION_ENTIDAD + ' ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( '('+ data.responseText +')' );
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
    
    
    
    
    jQuery('#jform_idEntidad').change( function(){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idIndicador').html( '<option value="0">'+ COM_INDICADORES_CARGANDO +'</option>' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getLstIndicadoresPorEntidad',
                                idEntidad   : jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( COM_INDICADORES_GESTION_ENTIDAD + ' ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( '('+ data.responseText +')' );
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

                jQuery('#jform_idIndicador').html( items.join('') );
            }
        });
    });
    
    
    
    /**
     *  INDICADOR - CONTEXTO: Gestiona Unidades de Medida asociadas a un determinado Tipo de Unidad de Medida
     */
    jQuery( '#jform_idIndTpoUndMedida' ).change(function( event, idUndMedida ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idIndUndMedida').html( '<option value="0">CARGANDO...</option>' );
        
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
                            alert( COM_INDICADORES_GESTION_ENTIDAD + ' ' + error +' '+ jqXHR +' '+ status );
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

                jQuery('#jform_idIndUndMedida').html( items.join('') );
        });
    });
    
    /**
     *  Gestiona Filtro de Tipo de Unidad de Medida - Unidad de Medida
     */
    jQuery( '#jform_idIndUndMedida' ).change(function( event, idEntidad, idUndMedida, idElemento ){
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery('#jform_idIndicador').html( '<option value="0">CARGANDO...</option>' );
        
        var dataIdEntidad = ( typeof( idEntidad ) != "undefined" )  ? idEntidad 
                                                                    : jQuery( '#jform_idEntidad' ).val();
        
        var dataIdUM = ( typeof( idUndMedida ) != "undefined" ) ? idUndMedida 
                                                                : jQuery( this ).val();
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getIndicadores',
                                idEntidad   : dataIdEntidad,
                                idUM        : dataIdUM
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
                    if( typeof( idElemento ) != 'undefined' ){
                        selected = ( dataInfo[x].id == idElemento )? 'selected'
                                                                    : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }
                                                    
                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +' >' + dataInfo[x].nombre + '</option>');
                }
                
                jQuery('#jform_idIndicador').html( items.join('') );
        });
        
    });
    
    /**
     * Gestiona Informacion de Unidades de Gestion Responsables - 
     * Funcionarios Responsables de un determinado Indicador
     */
    jQuery( '#jform_idIndicador' ).change( function( event, idEntidad, idIndicador ){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];

        jQuery( '#jform_UGResponsable' ).attr( 'value', '' );
        jQuery( '#jform_ResponsableUG' ).attr( 'value', '' );
        jQuery( '#jform_funcionario' ).attr( 'value', '' );
        
        var dataIdEntidad = ( typeof( idEntidad ) != "undefined" )  ? idEntidad 
                                                                    : jQuery( '#jform_idEntidad' ).val();
        
        var dataIdIndicador = ( typeof( idIndicador ) != "undefined" )  ? idIndicador 
                                                                        : jQuery( this ).val();
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_indicadores',
                                view: 'indicadores',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getResponsablesIndicador',
                                idEntidad: dataIdEntidad,
                                idIndicador: dataIdIndicador
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Variable Unidad Medida - Variable: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( '('+ data.responseText +')' );
                if( dataInfo.length > 0 ){
                    jQuery( '#jform_UGResponsable' ).attr( 'value', dataInfo[0].UGResponsable );
                    jQuery( '#jform_ResponsableUG' ).attr( 'value', dataInfo[0].responsableUG );
                    jQuery( '#jform_funcionario' ).attr( 'value', dataInfo[0].responsable );
                }
        });
    })
    
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
                        data:{  option: 'com_indicadores',
                                view: 'indicadores',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getUnidadMedida',
                                idTpoUM: jQuery( this ).val()
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
    
    /**
     *  Gestiona los dimensiones de un determinado enfoque
     */
    jQuery( '#jform_idEnfoque' ).live( 'change', function( event, idDimension ){
        jQuery( '#jform_idDimension' ).html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getDimensiones',
                                idEnfoque   : jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Indicadores - Gestion Enfoque: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
                var dataInfo = eval( '('+ data.responseText +')' );
                var numRegistros = dataInfo.length;
            
                var items = [];
                if( numRegistros > 0 ){
                    items.push('<option value="0">SELECCIONE DIMENSION</option>');
                    for( var x = 0; x < numRegistros; x++ ){

                        var selected = ( dataInfo[x].id == idDimension )? 'selected' 
                                                                        : '';

                        items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                    }
                }

                jQuery('#jform_idDimension').html( items.join('') );
        })
    })
    
    /**
     *  Gestiona los cantones de una determinada provincia 
     */
    jQuery( '#jform_idUGFuncionarioVar' ).live( 'change', function( event, idResponsable ){
        jQuery( '#jform_idFunResponsableVar' ).html( '<option value="0">CARGANDO...</option>' );
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getResponsablesVariable',
                                idUndGestion: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Atributos Indicador: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( '('+ data.responseText +')' );
            var numRegistros = dataInfo.length;
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

            jQuery('#jform_idFunResponsableVar').html( items.join('') ); 
        })
    })
    
    
    
    //  PLANTILLA DE INDICADORES
    jQuery( '#jform_idPlantilla' ).live( 'change', function(){
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option      : 'com_indicadores',
                                view        : 'indicadores',
                                tmpl        : 'component',
                                format      : 'json',
                                action      : 'getDtaPlantilla',
                                idPlantilla : jQuery( '#jform_idPlantilla' ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Atributos Indicador: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( '('+ data.responseText +')' );

            if( !Array.isArray( dataInfo ) ){
                //  Creo un Objeto Indicador
                var objIndicador = new Indicador();
                objIndicador.idRegIndicador = lstTmpIndicadores.length;
                objIndicador.setDtaIndicador( dataInfo );

                recorrerCombo( jQuery('#jform_idTpoIndicador option'), dataInfo.idTpoIndicador );
                recorrerCombo( jQuery('#jform_idClaseIndicador option'), dataInfo.idClaseIndicador );
                recorrerCombo( jQuery('#jform_intIdUndAnalisis option'), dataInfo.idUndAnalisis );

                recorrerCombo( jQuery('#jform_intIdTpoUndMedida option'), dataInfo.idTpoUndMedida );
                jQuery( '#jform_intIdTpoUndMedida' ).trigger( 'change', dataInfo.idUndMedida );

                jQuery( '#jform_nombreIndicador' ).attr( 'value', dataInfo.nombreIndicador );
                jQuery( '#jform_descripcionIndicador' ).attr( 'value', dataInfo.descripcion );
                jQuery( '#jform_strFormulaIndicador' ).attr( 'value', dataInfo.formula );

                //  Seteo Informacion de variables desde la plantilla
                for( var x = 0; x < objIndicador.lstVariables.length; x++ ){
                    objVar = new Variable();
                    objIndicador.lstVariables[x].idRegVar = x;
                    objVar.setDtaVariable( objIndicador.lstVariables[x] );

                    //  Agrego una fila a la tabla de Variables
                    jQuery( '#lstVarIndicadores > tbody:last' ).append( objVar.addFilaVar( 0 ) );
                    lstTmpVar.push( objVar );
                }
            }else{
                jAlert( COM_INDICADORES_PLANTILLA_ERROR, COM_INDICADORES_SIITA );
            }
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
    
    
    // Recorro el combo de provincias a una determinada posicion
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        });
    }
    
})