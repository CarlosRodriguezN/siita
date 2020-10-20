jQuery(document).ready(function() {
    var banIdRegPln = -1;
    var valorMeta   = jQuery( '#jform_umbralIndicador' ).val();
    
    /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFormPlanificacion() {
        jQuery('#imgPlanificacion').css("display", "none");
        jQuery('#frmPlanificacion').css("display", "block");
    }
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFormPlanificacion() {
        jQuery('#imgPlanificacion').css("display", "block");
        jQuery('#frmPlanificacion').css("display", "none");
    }
    
    
    jQuery( '#addPlanificacionTable' ).click( function(){
        limpiarFrmPln();
        showFormPlanificacion();
    })
    
    
     /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFormPln() {
        jQuery('#imgRango').css("display", "none");
        jQuery('#frmRango').css("display", "block");
    }
    
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFormPln() {
        limpiarFrmPln();
        hideFormPlanificacion();
    }
    
    /**
     * Evento CLICK boton AGREGAR rango
     */
    jQuery('#addLnRangoTable').click(function() {
        banIdRegPln = -1;
        limpiarFrmPln();
        showFormPln();
    });

    /**
     * Evento CLICK boton CANCELAR rango
     */
    jQuery('#btnClnRango').click(function() {
        tmpRango = false;
        hideFormPln();
        banIdRegPln = -1;
    });
    
    /**
     * 
     * Valida que los campos obligatorios delformulario
     * 
     * @returns {Boolean}
     */
    function valPlanificacion()
    {
        var ban     = false;
        var fchPln  = jQuery( '#jform_fchPlanificacion' );
        var valor   = jQuery( '#jform_valorPlanificacion' );
    
        if( fchPln.val() !== "" && valor.val() !== "" ){
            ban = true;
        }else{
            fchPln.validarElemento();
            valor.validarElemento();
        }
    
        return ban;
    }
    
    /**
     * 
     * Valida que la fecha de registro se encuentre dentro de los rangos 
     * correspondientes
     * 
     * @param {type} objPlanificacion
     * @returns {Boolean}
     */
    function validarFechas( objPlanificacion )
    {
        var ban = false;

        var fchInicio   = jQuery( '#jform_hzFchInicio' );
        var fchFin      = jQuery( '#jform_hzFchFin' );

        if( objPlanificacion.validarFecha( fchInicio.val(), fchFin.val() ) === true ){
            ban = true;
        }
        
        return ban;
    }

    
   /**
    *   
    *   RETORNA objeto Rango con la información del formulario.
    *   
    *   @returns {Rango}
    *   
    */
    function getFormPlanificacion() {
        var fecha   = jQuery('#jform_fchPlanificacion').val();
        var valor   = jQuery('#jform_valorPlanificacion').val();
        var tum     = jQuery('#jform_intIdTpoUndMedida').val();
        var um      = jQuery('#jform_idUndMedida').val();
        var dum     = jQuery('#jform_idUndMedida option:selected').text();

        var objPlanificacion;

        if( banIdRegPln === -1 ){
            objPlanificacion = new Planificacion( lstTmpPln.length, 0, fecha, valor, tum, um, dum );
        }else{
            var dtaPln = lstTmpPln[banIdRegPln];
            objPlanificacion = new Planificacion( banIdRegPln, dtaPln.idPln, fecha, valor, tum, um, dum );
        }

        return objPlanificacion;
    }
    
    /**
     * evento CLICK al boton GUARDAR rango
     */
    jQuery('#btnAddPlanIndicador').live('click', function() {
        //  Creo Objeto Planificacion desde la informacion gestionada desde el formulario de planificacion
        var objPlanificacion = getFormPlanificacion();
        
        if ( validarFormulario( objPlanificacion ) ) {
            
            delFilaPln( -1 );
            
            if (banIdRegPln !== -1) {
                updRegPlanificacion(objPlanificacion);
            } else {
                lstTmpPln.push(objPlanificacion);

                //  Agrego la fila creada a la tabla
                jQuery('#lstPlanificacionIndicadores > tbody:last').append(objPlanificacion.addFilaPln(0));
            }

            hideFormPln();
        }
    });
    
    
    
    function validarFormulario( objPlanificacion )
    {
        var ban = true;
        
        switch( true ){
            case ( valPlanificacion() === false ): 
                ban = false;
                jAlert( MSG_VALOR_INCOMPLETO, SIITA_ECORAE );
            break; 

            case( validarFechas( objPlanificacion ) === false ):
                ban = false;
                jAlert( MSG_FECHAS_FUERA_RANGO, SIITA_ECORAE );
            break;

            case( existePlanificacion( objPlanificacion ) === false ):
                ban = false;
                jAlert( MSG_VALOR_EXISTE_OK, SIITA_ECORAE );
            break;
            
            case( validarValor( objPlanificacion ) === false ):
                ban = false;
                jAlert( MSG_VALOR_FUERA_RANGO, SIITA_ECORAE );
            break;
        }
        
        return ban;
    }
    
    

    /**
     * Verifico la Existencia de un determinado valor de planificacion
     * 
     * @param {Object} planificacion     Objeto Planificacion con Informacion de Planificaciones Registradas
     * @returns {undefined}
     * 
     */
    function existePlanificacion( planificacion ){
        var ban = true;
        for (var x = 0; x < lstTmpPln.length; x++) {
            if (lstTmpPln[x].toString() === planificacion.toString() && banIdRegPln === -1 ) {
                ban = false;
            }
        }
        return ban;
    }
    
    
    /**
     * 
     * Valida si el valor de planificacion registrado, 
     * 
     * @param {object} planificacion    Objeto con informacion de planificacion del indicador
     * @returns {Boolean}
     */
    function validarValor( planificacion )
    {
        var ban = true;
        if( parseFloat( planificacion.valor ) > parseFloat( jQuery( '#jform_umbralIndicador' ).val() ) ){
            ban = false;
        }

        return ban;
    }
    
    
    /**
     * Gestiono la acualizacion de Planificacion
     */
    jQuery('.updPln').live('click', function() {
        showFormPlanificacion();
        var updFila = jQuery(this).parent().parent();
        var idFila  = updFila.attr( 'id' );
        banIdRegPln = idFila;

        jQuery( '#jform_fchPlanificacion' ).attr( 'value', lstTmpPln[idFila].fecha );
        jQuery( '#jform_valorPlanificacion' ).attr( 'value', lstTmpPln[idFila].valor );
    });
    
     /**
     * CARGA la informacion del RANGO en el FORMULARIO
     * @param {type} banIdRegPln ID del REGISTRO rango
     * @returns {undefined}
     */
    function loadDataFromPlanificacion(banIdRegPln) {
        for (var x = 0; x < lstTmpPln.length; x++) {
            if (lstTmpPln[x].idRegPln == banIdRegPln) {
                jQuery( '#jform_fchPlanificacion').attr( 'value', lstTmpPln[x].fecha );
                jQuery( '#jform_valorPlanificacion').attr( 'value', lstTmpPln[x].valor );
                tmpRango = lstTmpPln[x];
                showFormPln();
            }
        }
    }
     
     /**
     * 
     * @param {type} objRango
     * @returns {undefined}
     */
    function autoSavePlanificacion (objRango,banIdRegPln){
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_AUTO_RANGO, COM_INIDCADORES_SIITA, function(result) {
            if (result) {
                updRegPlanificacion(objRango);
                loadDataFromPlanificacion(banIdRegPln);
            }else{
                loadDataFromPlanificacion(banIdRegPln);
            }
        });
    }
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delPln' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpPln.splice( idFila, 1 );
                delFilaPln( idFila );
                
                validarFilasPln();
            }
        });
    });

    jQuery( '#btnCancelPlanIndicador' ).live( 'click', function(){
        limpiarFrmPln();
        hideFormPlanificacion();
    })

    /*
     * ACTUALIZA los datos de un RANGO;
     * @param {type} objDimension
     * @returns {undefined}
     */
    function updRegPlanificacion( objPlanificacion )
    {
        lstTmpPln[objPlanificacion.idRegPln] = objPlanificacion;
        updFilaPln( lstTmpPln[objPlanificacion.idRegPln].addFilaPln( 1 ), objPlanificacion.idRegPln );
        banIdRegPln = -1;
        tmpRango = false;
        limpiarFrmPln();
        hideFormPln();
    }
    
    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaPln( fila, idRegPln )
    {
        jQuery( '#lstPlanificacionIndicadores tr' ).each(function() {
            if( jQuery(this).attr('id') == idRegPln ){
                jQuery(this).html( fila );
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
    function delFilaPln( idFila ){
        //  Elimino fila de la tabla lista de Rangos
        jQuery( '#lstPlanificacionIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    
    function validarFilasPln()
    {
        var nrPln = lstTmpPln.length;

        if( nrPln === 0 ){
            objPlanificacion = new Planificacion();
            jQuery('#lstPlanificacionIndicadores > tbody:last').append( objPlanificacion.addFilaSinRegistros() );
        }

        return;
    }
    
    
    
    /**
     * 
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmPln(){
        banIdRegPln = -1;
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_fchPlanificacion' ).attr( 'value', '' );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_valorPlanificacion' ).attr( 'value', '' );
        
        jQuery( '#jform_fchPlanificacion' ).delValidaciones();
        jQuery( '#jform_valorPlanificacion' ).delValidaciones();
    }
});