jQuery(document).ready(function() {
    var banIdRegLB = -1;
    var dtaInfoLB;

    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split('?')[0];
        
    //  Actualizo tabla de Linea base    
    for( var x = 0; x < lstTmpLineasBase.length; x++  ){
        //  Agrego la fila a la tabla Linea Base
        jQuery( '#lstLineasBase > tbody:last' ).append( addFilaLB( lstTmpLineasBase[x], 0 ) );
    }

    /**
     *  Agrego un registro de Linea Base
     */
    jQuery('#btnAddLineaBase').live('click', function() {
        var idFuente = jQuery('#jform_idFuente').val();
        var fuente = jQuery('#jform_idFuente :selected').text();
        var idLineaBase = jQuery('#jform_idLineaBase').val();
        var lineaBase = jQuery('#jform_idLineaBase :selected').text();
        var valorLB = jQuery('#jform_valorLineaBase').val();
        var idRegLB = lstTmpLineasBase.length;
                            
        var lineaBase = new LineaBase( idRegLB, idLineaBase, lineaBase, valorLB, idFuente, fuente );

        if( existeLineaBase( lineaBase ) == 0 ){
            if( banIdRegLB != -1 ){
                lstTmpLineasBase[banIdRegLB] = lineaBase;
                updFilaLB( addFilaLB( lineaBase, 1 ) );

                banIdRegLB = -1;
            }else{
                lstTmpLineasBase.push( lineaBase );

                //  Agrego la fila creada a la tabla
                jQuery( '#lstLineasBase > tbody:last' ).append( addFilaLB( lineaBase, 0 ) );
            }
        }else{
            jAlert( 'Linea Base, ya Registrada', 'SIITA - ECORAE' );
        }
        
        //  Restauro a valores predeterminados formualario de registro de lineas base
        limpiarFrmLB();
    })
    
    /**
     *  Actualizo el valor de linea base
     */
    jQuery( '#jform_idLineaBase' ).change( function(){
        for( var x = 0; x < dtaInfoLB.length; x++ ){
            if( dtaInfoLB[x].id == jQuery( this ).val() ){
                jQuery( '#jform_valorLineaBase' ).attr( 'value', dtaInfoLB[x].valor );
            }
        }
    })
    
    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery( '.updLB' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdRegLB = idFila;
        
        for( var x = 0; x < lstTmpLineasBase.length; x++ ){
            if( lstTmpLineasBase[x].idRegLB == banIdRegLB ){
                //  Ajusta a una determinada Posicion el combo de fuente
                recorrerCombo( jQuery( '#jform_idFuente option' ),  lstTmpLineasBase[x].idFuente );
                
                //  Simulo un cambio en la lista de fuentes y ajusto el contenido de linea base  
                jQuery( '#jform_idFuente' ).trigger( 'change', [lstTmpLineasBase[x].idFuente, lstTmpLineasBase[x].idLineaBase] );
                
                //  Ajusto el valor de linea base
                jQuery( '#jform_valorLineaBase' ).attr( 'value', lstTmpLineasBase[x].valor );
            }
        }
     })
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delLB' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar esta Linea Base", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpLineasBase.splice( idFila, 1 );
                delFilaLB( idFila );
            }
        });
    })
    
    /**
     * 
     * Agrego una fila en la table Linea Base
     * 
     * @param {type} lineaBase 
     * @returns {undefined}
     */
    function addFilaLB( lineaBase, ban )
    {
        //  Construyo la Fila
        var fila = ( ban == 0 ) ? "<tr id='"+ lineaBase.idRegLB +"'>" 
                                : "";
        
        fila += "   <td align='center'>"+ lineaBase.nombre +"</td>"
                + " <td align='center'>"+ lineaBase.valor +"</td>"
                + " <td align='center'>"+ lineaBase.fuente +"</td>"
                + " <td align='center'> <a class='updLB'> Editar </a> </td>"
                + " <td align='center'> <a class='delLB'> Eliminar </a> </td>";

        fila += ( ban == 0 )?  "</tr>" 
                            : "";

        return fila;
    }

    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaLB( fila )
    {
        jQuery( '#lstLineasBase tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIdRegLB ){
                jQuery( this ).html( fila );
            }
        })
    }


    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaLB( idFila )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstLineasBase tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }

    /**
     * 
     * Verifico la Existencia de una determinada linea base
     * 
     * @param {Object} objLineaBase     Objeto Linea Base con Informacion de Lineas Base Registradas
     * 
     * @returns {undefined}
     * 
     */
    function existeLineaBase( objLineaBase )
    {
        var nrut = lstTmpLineasBase.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpLineasBase[x].toString() == objLineaBase.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }

    /**
     * 
     * Recorre los comboBox del Formulario a la posicion inicial
     * 
     * @param {type} combo      Objeto ComboBox
     * @param {type} posicion   Posicion a la que el combo va a recorrer
     * 
     * @returns {undefined}
     */
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }



    jQuery( '#jform_idFuente' ).change( function( event, idFuente, idLineaBase ){
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        //  Cambio mensaje de combo linea base
        jQuery('#jform_idLineaBase').html( '<option value="0">CARGANDO...</option>' );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_valorLineaBase' ).attr( 'value', '' );
        
        jQuery.ajax({   type: 'GET',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getLineasBase',
                                idFuenteLB: jQuery( this ).val()
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Atributos Indicador - Gestion Linea Base' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval( data.responseText );
            var numRegistros = dataInfo.length;

            if( numRegistros > 0 ){
                var items = [];
                var selected = '';
                dtaInfoLB = dataInfo;
                
                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idLineaBase ) != 'undefined' ){
                        selected = ( dataInfo[x].id == idLineaBase )    ? 'selected'
                                                                        : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '"'+ selected +'>' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idLineaBase').html( items.join('') );
        });
    })


    /**
     * 
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmLB()
    {
        //  Coloco en la posicion inicial el combo de fuentes
        recorrerCombo( jQuery( '#jform_idFuente option' ),  0 );
        
        //  EnceroCombo Combo de Lineas Base
        enCerarCombo( jQuery( '#jform_idLineaBase option' ) );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_valorLineaBase' ).attr( 'value', '' );
    }

    /**
     * 
     * Vacia el contenido de un determinado Combo
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