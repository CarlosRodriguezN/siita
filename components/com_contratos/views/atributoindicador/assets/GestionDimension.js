jQuery(document).ready(function() {
    //  Seteo informacion especifica de otros Indicadores
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var banIdDimension = -1;

    //  Actualizo tabla de Enfoque    
    for( var x = 0; x < lstTmpDimension.length; x++  ){
        //  Agrego la fila a la tabla Linea Base
        jQuery( '#lstDimensiones > tbody:last' ).append( addFilaDimension( lstTmpDimension[x], 0 ) );
    }

    /**
     *  Agrego Enfoque
     */
    jQuery('#btnAddDimension').live('click', function() {
        var idEnfoque = jQuery('#jform_idEnfoque').val();
        var idDimension = jQuery('#jform_idDimension').val();
        
        var enfoque = jQuery('#jform_idEnfoque :selected').text();
        var dimension = jQuery('#jform_idDimension :selected').text();

        var idRegistro = ( banIdDimension == -1 )   ? lstTmpDimension.length
                                                    : banIdDimension;
                                                    
        var idDimIndicador = ( banIdDimension == -1 )   ? 0 
                                                        : lstTmpDimension[banIdDimension].idDimIndicador;

        var objDimension = new Dimension( idRegistro, idDimIndicador, idEnfoque, enfoque, idDimension, dimension );

        if( existeDimension( objDimension ) == 0 ){
            if( banIdDimension != -1 ){
                lstTmpDimension[banIdDimension] = objDimension;
                updFilaDimension( addFilaDimension( objDimension, 1 ) );
                banIdDimension = -1;
            }else{
                lstTmpDimension.push( objDimension );

                //  Agrego la fila creada a la tabla
                jQuery( '#lstDimensiones > tbody:last' ).append( addFilaDimension( objDimension, 0 ) );
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
        
        for( var x = 0; x < lstTmpDimension.length; x++ ){
            if( lstTmpDimension[x].idRegDimension == banIdDimension ){
                //  Ajusta a una determinada Posicion el combo de fuente
                recorrerCombo( jQuery( '#jform_idEnfoque option' ),  lstTmpDimension[x].idEnfoque );
                
                //  Simulo la seleccion de un determinado Enfoque y actualizo la lista de 
                jQuery( '#jform_idEnfoque' ).trigger( 'change', lstTmpDimension[x].idDimension );
            }
        }
     })

    /**
     * 
     * Agrego una fila en la table Unidad Territorial
     * 
     * @param {Object} dtaDimension     Datos del enfoque
     * 
     * @returns {undefined}
     */
    function addFilaDimension( dtaDimension, ban )
    {
        //  Construyo la Fila
        var fila = ( ban == 0 ) 
                ? "<tr id='"+ dtaDimension.idRegDimension +"'>"
                : "";
                
        fila += "       <td align='center'>"+ dtaDimension.dimension +"</td>"
                    + " <td align='center'>"+ dtaDimension.enfoque +"</td>"
                    + " <td align='center'> <a class='updDim'> Editar </a> </td>"
                    + " <td align='center'> <a class='delDim'> Eliminar </a> </td>";

        fila += ( ban == 0 )
                ? "  </tr>"
                : "";

        return fila;
    }

    /**
     * 
     * Registro de unidades territoriales
     * 
     * @param {Object} enfoque     Unidad Territorial
     * @returns {undefined}
     */
    function existeDimension( enfoque )
    {
        var nrut = lstTmpDimension.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpDimension[x].toString() == enfoque.toString() ){
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