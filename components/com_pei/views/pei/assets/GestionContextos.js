jQuery(document).ready(function() {
    //  Bandera de control de nuevos registros
    var banIdRegCtxto = -1;

    /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFrmContexto() {
        jQuery('#imgContexto').css("display", "none");
        jQuery('#frmContexto').css("display", "block");
    }
    
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFrmContexto() {
        jQuery('#imgContexto').css("display", "block");
        jQuery('#frmContexto').css("display", "none");
        limpiarFrmContexto();
    }
    
    
    jQuery( '#addContexto' ).live( 'click', function(){
        showFrmContexto();
    })



    //  Creo Objeto Contexto
    objContexto = new Contexto();
    
    if( jQuery( '#dtaContextos' ).val() != "null" && jQuery( '#dtaContextos' ).val() != "" ){
        updDtaContextos( jQuery( '#dtaContextos' ).val() );
    }
    
    /**
     * 
     * Actualizo informacion de contextos registrados
     * 
     * @param {type} dtaContexto    Datos de Contextos
     * @returns {undefined}
     * 
     */
    function updDtaContextos( dtaContexto )
    {
        var objDtaContexto = eval( "(" + dtaContexto + ")" );
        
        if( objDtaContexto.length > 0 ){
            for( var x = 0; x < objDtaContexto.length; x++ ){
                objContexto.idRegContexto = x;
                
                var objIndicador = new Indicador();
                objIndicador.setDtaIndicador( objDtaContexto[x] );
                objContexto.lstIndicadores.push( objIndicador );
                
                jQuery( '#tbLstContextos > tbody:last' ).append( objContexto.addFilaContexto( 0 ) );
            }
            
        }
        
    }
    
    jQuery( '#btnAddContexto' ).live( 'click', function(){
        if( validarFrmContexto() ){
            if( banIdRegCtxto === -1 ){
                objContexto.idRegContexto = objContexto.lstIndicadores.length;
                
                //  Creo Objeto Indicador
                var objIndicador = new Indicador();
                objIndicador.idRegIndicador     = objContexto.lstIndicadores.length;
                objIndicador.nombreIndicador    = jQuery( '#jform_strNombreContexto' ).val();
                objIndicador.descripcion        = jQuery( '#jform_strDescripcionContexto' ).val();
                objIndicador.idTpoIndicador     = 5;
                objIndicador.idClaseIndicador   = 6;
                objIndicador.idCategoria        = 10;
                objIndicador.idGpoDimension     = 7;
                objIndicador.idGpoDecision      = 4;
                objIndicador.idMetodoCalculo    = jQuery( '#jform_idMetodoCalculo' ).val();
                
                //  Agrego indicador a la lista de contextos
                objContexto.lstIndicadores.push( objIndicador );

                //  Agrego la fila creada a la tabla
                jQuery( '#tbLstContextos > tbody:last' ).append( objContexto.addFilaContexto( 0 ) );
            }else{
                objContexto.lstIndicadores[banIdRegCtxto].nombreIndicador   = jQuery( '#jform_strNombreContexto' ).val(); 
                objContexto.lstIndicadores[banIdRegCtxto].descripcion       = jQuery( '#jform_strDescripcionContexto' ).val(); 
                
                updFilaContexto( objContexto.addFilaContexto( 1 ) );
            }
            
            limpiarFrmContexto();
            hideFrmContexto();
        }
    })
    
    
    /**
     * 
     * Actualizo informacion de un determinada Variable
     * 
     * @param {HTML} fila       Datos a actualizar
     * @returns {undefined}
     */
    function updFilaContexto( fila )
    {
        jQuery( '#tbLstContextos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) !== "undefined" && parseInt( jQuery( this ).attr( 'id' ) ) === banIdRegCtxto ){
                jQuery( this ).html( fila );
            }
        })
    }
    
    
    /**
     * Gestiono la acualizacion de un Rango de Gestion
     */
    jQuery( '.updCtxto' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdRegCtxto = parseInt( idFila );
        
        showFrmContexto();
        
        jQuery( '#jform_strNombreContexto' ).attr( 'value', objContexto.lstIndicadores[banIdRegCtxto].nombreIndicador ); 
        jQuery( '#jform_strDescripcionContexto' ).attr( 'value', objContexto.lstIndicadores[banIdRegCtxto].descripcion ); 
        
     })
    
    
    
    
    function validarContexto( objContexto )
    {
        var ban = true;
        
        switch( true ){
            //  valido contenido del formulario
            case ( validarFrmContexto() === false ): 
                ban = false;
                jAlert( 'JSL_ALERT_ALL_NEED', 'JSL_ECORAE' );
            break;
            
            //  valido contenido del formulario
            case ( existeContexto( objContexto ) === false ): 
                ban = false;
                jAlert( 'COM_CONTEXTO_EXISTENTE', 'JSL_ECORAE' );
            break;
        }

        return ban;
    }
    
    /**
     * 
     * Valido el formulario de contexto
     * 
     * @returns {Boolean}
     */
    function validarFrmContexto()
    {
        var ban = false;
        var nombreContexto      = jQuery('#jform_strNombreContexto');
        
        if ( nombreContexto.val() !== "" ) {
            ban = true;
        } else {
            nombreContexto.validarElemento();
        }

        return ban;
    }
    
    /**
     * 
     * Verifico la Existencia de un determinado Contexto
     * 
     * @param {Object} contexto     Objeto de Tipo Contexto, con informacion de un contexto
     * 
     * @returns {undefined}
     * 
     */
    function existeContexto( objIndicador )
    {
        var numCtxtos = objContexto.lstIndicadores.length;
        var ban = 0;

        for( var x = 0; x < numCtxtos; x++ ){
            if( objContexto.lstIndicadores[x].toString() === objIndicador.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
    
    jQuery( '#btnCancelContexto' ).live( 'click', function(){
        limpiarFrmContexto();
        hideFrmContexto();
    })
    
    
    function limpiarFrmContexto()
    {
        banIdRegCtxto = -1;
        
        jQuery( '#jform_strNombreContexto' ).attr( 'value', '' );
        jQuery( '#jform_strDescripcionContexto' ).attr( 'value', '' );

        jQuery( '#jform_strNombreContexto' ).delValidaciones();
    }

})