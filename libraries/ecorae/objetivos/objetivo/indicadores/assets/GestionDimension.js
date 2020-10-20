jQuery(document).ready(function() {
    //  Seteo informacion especifica de otros Indicadores
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var banIdDimension = -1;

    /**
     *  Agrego Enfoque
     */
    jQuery('#btnAddDimension').live('click', function() {
        var idEnfoque = jQuery('#jform_idEnfoque').val();
        var idDimension = jQuery('#jform_idDimension').val();
        
        var enfoque = jQuery('#jform_idEnfoque :selected').text();
        var dimension = jQuery('#jform_idDimension :selected').text();

        var idRegistro = ( banIdDimension == -1 )   ? lstTmpDim.length
                                                    : banIdDimension;
                                                    
        var idDimIndicador = ( banIdDimension == -1 )   ? 0 
                                                        : lstTmpDim[banIdDimension].idDimIndicador;

        var objDimension = new Dimension( idRegistro, idDimIndicador, idEnfoque, enfoque, idDimension, dimension );

        if( existeDimension( objDimension ) == 0 ){
            if( banIdDimension != -1 ){
                lstTmpDim[banIdDimension] = objDimension;
                updFilaDimension( objDimension.addFilaDimension( 0 ) );
                banIdDimension = -1;
            }else{
                lstTmpDim.push( objDimension );

                //  Agrego la fila creada a la tabla
                jQuery( '#lstDimensiones > tbody:last' ).append( objDimension.addFilaDimension( 0 ) );
            }

        }else{
            jAlert( 'Dimension, Registrada', 'SIITA - ECORAE' );
        }
    })
    
    
    
    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery( '.updDim' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdDimension = idFila;
        
        for( var x = 0; x < lstTmpDim.length; x++ ){
            if( lstTmpDim[x].idRegDimension == banIdDimension ){
                //  Ajusta a una determinada Posicion el combo de fuente
                recorrerCombo( jQuery( '#jform_idEnfoque option' ),  lstTmpDim[x].idEnfoque );
                
                //  Simulo la seleccion de un determinado Enfoque y actualizo la lista de 
                jQuery( '#jform_idEnfoque' ).trigger( 'change', lstTmpDim[x].idDimension );
            }
        }
     })
    
    /**
     * 
     * Registro de unidades territoriales
     * 
     * @param {Object} enfoque     Unidad Territorial
     * @returns {undefined}
     */
    function existeDimension( enfoque )
    {
        var nrut = lstTmpDim.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpDim[x].toString() == enfoque.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }


    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaDimension( fila )
    {
        jQuery( '#lstDimensiones tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIdDimension ){
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
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }

})