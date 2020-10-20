jQuery(document).ready(function() {
    var banIdRegRG = -1;
    var tmpRango = false;
    
     /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFormRG() {
        jQuery('#imgRango').css("display", "none");
        jQuery('#frmRango').css("display", "block");
    }
    
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFormRG() {
        jQuery('#imgRango').css("display", "block");
        jQuery('#frmRango').css("display", "none");
        tmpRango = false;
        banIdRegRG = -1;
        limpiarFrmRG();
    }
    
    /**
     * Evento CLICK boton AGREGAR rango
     */
    jQuery('#addLnRangoTable').click(function() {
        banIdRegRG = -1;
        limpiarFrmRG();
        showFormRG();
        
        jQuery( '#jform_rgColor' ).css( 'background-color', getColor( lstTmpRG.length ) );
    });


    function getColor( IdRG )
    {
        var color;
        
        switch( IdRG ){
            case 1: 
                color = '#FFFF00';  //  Amarillo
            break;

            case 2: 
                color = '#00FF00'   //  Verde
            break;
                
            default:
                color = '#FF0000'; //   Rojo
            break;
        }
        
        return color;
    }
    

    /**
     * Evento CLICK boton CANCELAR rango
     */
    jQuery('#btnClnRango').click(function() {
        tmpRango = false;
        hideFormRG();
        banIdRegRG = -1;
    });
    
    /**
     *  VALIDA que los CAMPOS del formulario RANGO
     * @param {JSON} data  Objeto JSON con los campos del FORMULARIO
     * @returns {Boolean}
     */
    function valRG()
    {
        var valMinimo   = jQuery('#jform_rgValMinimo');
        var valMaximo   = jQuery('#jform_rgValMaximo');
        
        var ban = false;
        if ( valMinimo.val() !== "" && valMaximo.val() !== "" ){
            ban = true;
        }else{
            valMinimo.validarElemento();
            valMaximo.validarElemento();
        }

        return ban;
    }
    
    
   /**
    *   RETORNA objeto Rango con la información del formulario.
    * @returns {Rango}
    */
    function getFormRango()
    {
        var minimo  = jQuery('#jform_rgValMinimo').val();
        var maximo  = jQuery('#jform_rgValMaximo').val();

        var color   = ( parseInt( banIdRegRG ) === -1 ) ? getColor( lstTmpRG.length )
                                                        : getColor( parseInt( banIdRegRG ) );

        var idRegRG = ( parseInt( banIdRegRG ) === -1 ) ? lstTmpRG.length 
                                                        : parseInt( banIdRegRG );
                                        
        if( isNaN( maximo )  ){
            minimo  = 0;
            maximo  = parseFloat( jQuery('#jform_umbralIndicador').val() );
            color   = '#ff0000';
        }
        
        return new Rango( idRegRG, 0, minimo, maximo, color );
    }
    
    /**
     * evento CLICK al boton GUARDAR rango
     */
    jQuery('#btnAddRango').live('click', function() {
        var objRango = getFormRango();

        if (valRG()) {
            if (existeRango(objRango)) {
                
                delFilaRG( -1 );
                
                if (banIdRegRG != -1) {
                    updRegRango(objRango);
                } else {
                    lstTmpRG.push(objRango);
                    //  Agrego la fila creada a la tabla
                    jQuery('#lstRangos > tbody:last').append(objRango.addFilaRG(0));
                    hideFormRG();
                    limpiarFrmRG();
                }
            } else {
                jAlert(MSG_VALOR_EXISTE_OK, SIITA_ECORAE);
            }
        } else {
            jAlert(MSG_VALOR_INCOMPLETO, SIITA_ECORAE);
        }
        
        if( lstTmpRG.length === 3 ){
            jQuery( '#addLnRangoTable' ).attr( 'disabled', 'disabled' );
        }

    });

    /**
     * Verifico la Existencia de una determinada linea base
     * @param {Object} rango     Objeto Linea Base con Informacion de Lineas Base Registradas
     * @returns {undefined}
     * 
     */
    function existeRango(rango) {
        var ban = true;
        for (var x = 0; x < lstTmpRG.length; x++) {
            if (lstTmpRG[x].toString() == rango.toString()) {
                ban = false;
            }
        }
        return ban;
    }
    
    /**
     * Gestiono la acualizacion de un Rango de Gestion
     */
    jQuery('.updRG').live('click', function() {
        var objRango= getFormRango();
        var updFila = jQuery(this).parent().parent();
        var idFila  = updFila.attr('id');
        
        banIdRegRG = idFila;
        
        if (tmpRango) {
            if (tmpRango.toString() === objRango.toString()) {
                loadDataFromRango(banIdRegRG);
            } else {
                autoSaveRango(objRango, banIdRegRG);
            }
        } else {
            loadDataFromRango(banIdRegRG);
        }
    });

     /**
     * CARGA la informacion del RANGO en el FORMULARIO
     * @param {type} banIdRegRG ID del REGISTRO rango
     * @returns {undefined}
     */
    function loadDataFromRango(banIdRegRG) {
        for (var x = 0; x < lstTmpRG.length; x++) {
            if (lstTmpRG[x].idRegRG == banIdRegRG) {
                jQuery('#jform_rgValMinimo').attr('value', lstTmpRG[x].setDataValor( lstTmpRG[x].valMinimo ) );
                jQuery('#jform_rgValMaximo').attr('value', lstTmpRG[x].setDataValor( lstTmpRG[x].valMaximo ) );
                jQuery( '#jform_rgColor' ).css( 'background-color', lstTmpRG[x].color );
                
                tmpRango = lstTmpRG[x];
                showFormRG();
            }
        }
    }
     
     /**
     * 
     * @param {type} objRango
     * @returns {undefined}
     */
    function autoSaveRango (objRango,banIdRegRG){
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_AUTO_RANGO, COM_INIDCADORES_SIITA, function(result) {
            if (result) {
                updRegRango(objRango);
                loadDataFromRango(banIdRegRG);
            }else{
                loadDataFromRango(banIdRegRG);
            }
        });
    }
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delRG' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function( result ){
            if( result ){
                //  Elimino Rango del la tabla Temporal de Rangos
                lstTmpRG.splice( idFila, 1 );

                //  Elimino Fila de la tabla de Rangos
                delFilaRG( idFila );
                
                validarFilasRango();
            }
        });
    });

    /*
     * ACTUALIZA los datos de un RANGO;
     * @param {type} objDimension
     * @returns {undefined}
     */
    function updRegRango(objRango)
    {
        lstTmpRG[objRango.idRegRG] = objRango;
        updFilaRG( lstTmpRG[objRango.idRegRG].addFilaRG(1),objRango.idRegRG );

        banIdRegRG  = -1;
        tmpRango    = false;
        
        limpiarFrmRG();
        hideFormRG();
    }
    
    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaRG(fila, idRegRG) {
        jQuery('#lstRangos tr').each(function() {
            if (jQuery(this).attr('id') == idRegRG) {
                jQuery(this).html(fila);
            }
        });
    }
    
    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaRG( idFila ){
        //  Elimino fila de la tabla lista de Rangos
        jQuery( '#lstRangos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    
    function validarFilasRango()
    {
        var nrR = lstTmpRG.length;

        if( nrR === 0 ){
            objRg = new Rango();
            jQuery('#lstRangos > tbody:last').append( objRg.addFilaSinRegistros() );
        }
        
        return;
    }
    
    
    jQuery( '#btnCancelRango' ).live( 'click', function(){
        limpiarFrmRG();
    })
    
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
        jQuery( '#jform_rgValMaximo' ).attr( 'value', '' );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_rgValMinimo' ).attr( 'value', '' );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_rgColor' ).attr( 'value', '' );
        
        jQuery( '#jform_rgValMaximo' ).delValidaciones();
        jQuery( '#jform_rgValMinimo' ).delValidaciones();
        jQuery( '#jform_rgColor' ).delValidaciones();
        
    }
});