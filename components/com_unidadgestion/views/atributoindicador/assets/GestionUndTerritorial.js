jQuery(document).ready(function() {
    var idUndTerritorial;
    var banIdRegUT = -1;

    /**
     *  Agrego un registro de unidad territorial
     */
    jQuery('#btnAddUndTerritorial').live('click', function() {
        var idProvincia = jQuery('#jform_idProvincia').val();
        var idCanton = jQuery('#jform_idCanton').val();
        var idParroquia = jQuery('#jform_idParroquia').val();
        var provincia = jQuery('#jform_idProvincia :selected').text();
        var canton = jQuery('#jform_idCanton :selected').text();
        var parroquia = jQuery('#jform_idParroquia :selected').text();
        
        switch (true) {
            case(idProvincia != 0 && idCanton != 0 && idParroquia != 0):
                idUndTerritorial = idParroquia;
                
                break;

            case(idProvincia != 0 && idCanton != 0 && idParroquia == 0):
                idUndTerritorial = idCanton;
                break;

            case(idProvincia != 0 && idCanton == 0 && idParroquia == 0):
                idUndTerritorial = idProvincia;
                break;
        }

        var idRegUT = ( banIdRegUT == -1 )  ? lstTmpUT.length
                                            : parseInt( banIdRegUT );
                                            
        var undTerritorial = new UnidadTerritorial( idRegUT, idUndTerritorial, provincia, canton, parroquia );

        if( existeUndTerritorial( undTerritorial ) == 0 ){

            if( banIdRegUT != -1 ){
                lstTmpUT[banIdRegUT] = undTerritorial;
                updFilaUT( undTerritorial.addFilaUT( 1 ) );
                banIdRegUT = -1;
            }else{
                lstTmpUT.push( undTerritorial );
                
                //  Agrego la fila creada a la tabla
                jQuery( '#lstUndTerritorialesInd > tbody:last' ).append( undTerritorial.addFilaUT( 0 ) );
            }
        }else{
            jAlert( 'Unidad Territorial, ya existe', 'SIITA - ECORAE' );
        }
        
        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmUT();
    })
    
    /**
     * 
     * Registro de unidades territoriales
     * 
     * @param {Object} undTerritorial     Unidad Territorial
     * @returns {undefined}
     */
    function existeUndTerritorial( undTerritorial )
    {
        var nrut = lstTmpUT.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpUT[x].toString() == undTerritorial.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery( '.updIndUT' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        banIdRegUT = idFila;
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getDPA',
                                idUndTerritorial: lstTmpUT[idFila].idUndTerritorial
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Unidad Territorial: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval("(" + data.responseText + ")");

            //  Recorro hasta una determinada posicion el combo de provincias
            recorrerComboUT( jQuery( '#jform_idProvincia option' ), dataInfo.idProvincia );

            //  Simulo una seleccion de un elemento de provincia
            jQuery( '#jform_idProvincia' ).trigger( 'change', dataInfo.idCanton );

            //  Simulo una seleccion de un elemento de canton
            jQuery( '#jform_idCanton' ).trigger( 'change', [dataInfo.idCanton, dataInfo.idParroquia] );
        })
     })
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delIndUT' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar esta Unidad Territorial", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpUT.splice( idFila, 1 );
                delFilaUT( idFila );
            }
        });
    })
    
    
    
    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaUT( filaUT )
    {
        jQuery( '#lstUndTerritorialesInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIdRegUT ){
                jQuery( this ).html( filaUT );
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
    function delFilaUT( idFila )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstUndTerritorialesInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
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
    function recorrerComboUT(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }



    //  Resetea los valores de un combo determinado
    function enCerarComboUT(combo)
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
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmUT()
    {
        //  Coloco en la posicion inicial el combo de fuentes
        recorrerComboUT( jQuery( '#jform_idProvincia option' ),  0 );

        //  EnceroCombo Combo de Cantones
        enCerarComboUT( jQuery( '#jform_idCanton option' ) );

        //  EnceroCombo Combo de Parroquias
        enCerarComboUT( jQuery( '#jform_idParroquia option' ) );
    }
    
})