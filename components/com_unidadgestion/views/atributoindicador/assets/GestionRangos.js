jQuery(document).ready(function() {
    var banIdRegRG = -1;
    
    /**
     *  Agrego un registro de unidad territorial
     */
    jQuery('#btnAddRango').live('click', function() {
        
        var minimo = jQuery('#jform_rgValMinimo').val();
        var maximo = jQuery('#jform_rgValMaximo').val();
        var color = jQuery('#jform_rgColor').val();
        
        var idRegRG = ( banIdRegRG == -1 )  ? lstTmpRG.length 
                                            : banIdRegRG;

        if( banIdRegRG == -1 ){
            var objRango = new Rango( idRegRG, 0, minimo, maximo, color );
            
            if( existeRango( objRango ) == 0 ){
                lstTmpRG.push( objRango );

                //  Agrego la fila creada a la tabla
                jQuery( '#lstRangos > tbody:last' ).append( objRango.addFilaRG( 0 ) );
            }else{
                jAlert( 'Rango, ya Registrado', 'SIITA - ECORAE' );
            }
        }else{
            lstTmpRG[banIdRegRG].valMinimo = minimo;
            lstTmpRG[banIdRegRG].valMaximo = maximo;
            lstTmpRG[banIdRegRG].color = color;

            updFilaRG( lstTmpRG[banIdRegRG].addFilaRG( 1 ) );
        }

        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmRG();
    })

    /**
     * 
     * Verifico la Existencia de una determinada linea base
     * 
     * @param {Object} rango     Objeto Linea Base con Informacion de Lineas Base Registradas
     * 
     * @returns {undefined}
     * 
     */
    function existeRango( rango )
    {
        var nrut = lstTmpRG.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpRG[x].toString() == rango.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
    /**
     * Gestiono la acualizacion de un Rango de Gestion
     */
    jQuery( '.updRG' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdRegRG = idFila;
        
        for( var x = 0; x < lstTmpRG.length; x++ ){
            if( lstTmpRG[x].idRegRG == banIdRegRG ){
                jQuery('#jform_rgValMinimo').attr( 'value', lstTmpRG[x].valMinimo );
                jQuery('#jform_rgValMaximo').attr( 'value', lstTmpRG[x].valMaximo );
                jQuery('#jform_rgColor').attr( 'value', lstTmpRG[x].color );
            }
        }

     })
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delRG' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Rango", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpRG.splice( idFila, 1 );
                delFilaRG( idFila );
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
    function updFilaRG( fila )
    {
        jQuery( '#lstRangos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIdRegRG ){
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
    function delFilaRG( idFila )
    {
        //  Elimino fila de la tabla lista de Rangos
        jQuery( '#lstRangos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    /**
     * 
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmRG()
    {
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_rgColor' ).attr( 'value', '' );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_rgValMinimo' ).attr( 'value', '' );

        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_rgValMaximo' ).attr( 'value', '' );
    }
})