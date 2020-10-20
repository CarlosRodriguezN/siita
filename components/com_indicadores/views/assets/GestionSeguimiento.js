jQuery(document).ready(function() {

    var banIdRegSg = -1;
    var idIV;

    /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFormSeguimiento() {
        jQuery('#imgSeguimiento').css("display", "none");
        jQuery('#frmSeguimiento').css("display", "block");
    }
    
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFormPlanificacion() {
        jQuery('#imgSeguimiento').css("display", "block");
        jQuery('#frmSeguimiento').css("display", "none");
    }
    
    jQuery( '#addSeguimientoTable' ).click( function(){
        
        if( parseInt( jQuery( '#jform_idVariableIndicador' ).val() ) != 0  ){
            limpiarFrmSg();
            showFormSeguimiento();
        }else{
            jAlert( 'Favor seleccione Variable' );
        }
        
    })

    jQuery('#jform_idVariableIndicador').live('change', function() {
        idIV = jQuery('#jform_idVariableIndicador').val();
        var nrv = lstTmpVar.length;

        //  Limpio la tabla de valores
        jQuery('#lstSeguimiento > tbody').empty();

        for (var x = 0; x < nrv; x++) {
            if (lstTmpVar[x].idIndVariable === idIV) {
                //  Obtengo lista de valores de seguimiento de una variable en 
                //  un determinado indicador
                lstTmpSg = lstTmpVar[x].lstSeguimiento;

                //  Actualizo Lista de Seguimiento de un determinado Indicador-Variable
                updLstSeguimiento(lstTmpSg);
            }
        }
    })

    /**
     * 
     * Actualizo lista de Seguimientos a una determinada variable
     * 
     * @param {Array} dtaLstSeg    Lista de 
     * 
     * @returns {undefined}
     * 
     */
    function updLstSeguimiento(dtaLstSeg)
    {
        var nrs = dtaLstSeg.length;

        if ( nrs > 0 ) {
            for (var x = 0; x < nrs; x++) {
                
                if( parseInt( dtaLstSeg[x].published ) === 1 ){
                    //  Agrego la fila creada a la tabla
                    jQuery('#lstSeguimiento > tbody:last').append( dtaLstSeg[x].addFilaSg( 0 ) );
                }

            }
        }else{
            var objSeg = new Seguimiento();
            
            //  Agrego la fila creada a la tabla
            jQuery('#lstSeguimiento > tbody:last').append( objSeg.addFilaSinRegistros() );
        }
        
        return;
    }

    /**
     * 
     * Calcula el valor total planificado, sumando el total de valores planificados
     * 
     * @returns {float}
     */
    function getTotalSeguimiento(lstSeg)
    {
        var ntp = 0;
        var nrp = lstSeg.length;

        if (nrp) {
            for (var x = 0; x < nrp; x++) {
                ntp += parseFloat(lstSeg[x].valor);
            }
        }

        return ntp.toFixed(2);
    }

    /**
     *  Agrego un registro de unidad territorial
     */
    jQuery('#btnAddSeguimiento').live('click', function() {
        var idVI = jQuery('#jform_idVariableIndicador').val();
        var fecha = jQuery('#jform_fchSeguimiento').val();
        var valor = jQuery('#jform_valorSeguimiento').val();

        var idRegSg = (banIdRegSg === -1) 
                        ? lstTmpSg.length
                        : banIdRegSg;
                        
        var objSg = new Seguimiento(idRegSg, 0, idVI, fecha, valor);
        
        objSg.idTpoUndMedida= jQuery('#jform_intIdTpoUndMedida').val();
        objSg.idUndMedida   = jQuery('#jform_idUndMedida').val();

        if ( validarFrmSeguimiento( objSg ) ){
            if (banIdRegSg === -1) {
                
                delFilaSG( -1 );
                
                lstTmpSg.push( objSg )

                //  Agrego la fila creada a la tabla
                jQuery('#lstSeguimiento > tbody:last').append(objSg.addFilaSg(0));
            } else {
                lstTmpSg[idRegSg].fecha = fecha;
                lstTmpSg[idRegSg].valor = valor;

                //  Actualizo la fila correspondiente
                updFilaSG(lstTmpSg[idRegSg].addFilaSg(1), idRegSg);
            }

            //  Restauro a valores predeterminados formulario de registro de lineas base
            limpiarFrmSg();
        }
    })


    function validarFrmSeguimiento( objSg )
    {
        var ban = true;
        
        switch( true ){
            case ( validarFormulario() === false ): 
                ban = false;
                jAlert( MSG_VALOR_INCOMPLETO, SIITA_ECORAE );
            break;
            
            case( existeSeguimiento(objSg) === false ):
                ban = false;
                jAlert( MSG_VALOR_EXISTE_OK, SIITA_ECORAE );
            break;
            
            case( validarFechas( objSg ) === false ):
                ban = false;
                jAlert( MSG_FECHAS_FUERA_RANGO, SIITA_ECORAE );
            break;
            
        }
        
        return ban;
        
    }
    
    
    function validarFormulario()
    {
        var ban = false;
        var idVI = jQuery('#jform_idVariableIndicador');
        var fecha = jQuery('#jform_fchSeguimiento');
        var valor = jQuery('#jform_valorSeguimiento');
        
        
        if( idVI.val() !== ""
            && fecha.val() !== ""
            && valor.val() !== "" ){
            ban = true;
        }else{
            idVI.validarElemento();
            fecha.validarElemento();
            valor.validarElemento();
        }
        
        return ban;
    }



    /**
     * 
     * Valida que la fecha de registro se encuentre dentro de los rangos 
     * correspondientes
     * 
     * @param {object} objSg
     * @returns {Boolean}
     */
    function validarFechas( objSg )
    {
        var ban = false;

        var fchInicio   = jQuery( '#jform_hzFchInicio' );
        var fchFin      = jQuery( '#jform_hzFchFin' );

        if( objSg.validarFecha( fchInicio.val(), fchFin.val() ) === true ){
            ban = true;
        }
        
        return ban;
    }



    /**
     *  Gestiono el proceso de actualizacion de Informacion del seguimiento de una variable
     */
    jQuery('.updSg').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila  = parseInt( updFila.attr('id') );
        var nrvg    = lstTmpSg.length;

        for (var x = 0; x < nrvg; x++) {
            if ( parseInt( lstTmpSg[x].idRegSeg ) === idFila ) {
                banIdRegSg = lstTmpSg[x].idRegSeg;

                jQuery('#jform_fchSeguimiento').val(lstTmpSg[x].fecha);
                jQuery('#jform_valorSeguimiento').val( lstTmpSg[x].setDataUmbral() );
                
                showFormSeguimiento();
            }
        }

        return;
    })

    /**
     * 
     * Actualizo la fila 
     * 
     * @param {html} fila       Fila a actualizar
     * @param {int} idRegSG     Identificador de la fila a actualizar
     * 
     * @returns {undefined}
     * 
     */
    function updFilaSG(fila, idRegSG) {
        jQuery('#lstSeguimiento tr').each(function() {
            if (jQuery(this).attr('id') == idRegSG) {
                jQuery(this).html(fila);
            }
        });
    }


    /**
     * Gestiona el proceso de Eliminacion de registro
     */
    jQuery('.delSg').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm("¿Est&aacute seguro que desea eliminar este registro?", "SIITA - ECORAE", function(result) {
            if (result) {
                //  Gestiono la eliminacion del registro de seguiento
                delRegistroSeguimiento( idFila )

                //  Elimina registro de la Tabla Seguimiento
                delFilaSG(idFila);
                
                validarFilaSG();
            }
        });
    })


    function delRegistroSeguimiento( idFila )
    {
        
        if( parseInt( lstTmpSg[idFila].idSeg ) === 0  ){
            lstTmpSg.splice( idFila, 1 );
        }else{
            //  Actualizo estado NO Publico el registro a eliminar
            lstTmpSg[idFila].published = 0;
        }
        
        return false;
    }



    function validarFilaSG()
    {

        idIV = jQuery('#jform_idVariableIndicador').val();
        var nrv = lstTmpVar.length;
        var nrVS;

        //  Limpio la tabla de valores
        //  jQuery('#lstSeguimiento > tbody').empty();

        for (var x = 0; x < nrv; x++) {
            if ( parseInt( lstTmpVar[x].idIndVariable ) === parseInt( idIV ) ) {
                //  Obtengo lista de valores de seguimiento de una variable en 
                //  un determinado indicador
                lstTmpSg = lstTmpVar[x].lstSeguimiento;

                nrVS = lstTmpSg.length;
                
                if( nrVS === 0 ){
                    //  Agrego la fila creada a la tabla
                    jQuery('#lstSeguimiento > tbody:last').append( lstTmpSg[x].addFilaSinRegistros() );
                }else{
                    
                    var csp = 0;
                    
                    for( var y = 0; y < nrVS; y++ ){
                        
                        if( parseInt( lstTmpVar[x].lstSeguimiento[y].published ) === 1 ){
                            csp++;
                        }
                        
                    }
                    
                    if( csp === 0 ){
                        //  Agrego la fila creada a la tabla
                        jQuery('#lstSeguimiento > tbody:last').append( lstTmpSg[x].addFilaSinRegistros() );
                    }

                }

            }
        }
        
        return;
    }



    /**
     * 
     * Elimino una fila de la tabla Seguimiento
     * 
     * @param {int} idFila  Identificador de la fila
     * 
     */
    function delFilaSG(idFila) {
        //  Elimino fila de la tabla lista de Rangos
        jQuery('#lstSeguimiento tr').each(function() {
            if ( parseInt( jQuery(this).attr( 'id' ) ) === parseInt( idFila ) ) {
                jQuery(this).remove();
            }
        })
    }

    /**
     * 
     * Verifico la Existencia de una determinada linea base
     * 
     * @param {Object} objSg     Objeto Linea Base con Informacion de Lineas Base Registradas
     * 
     * @returns {undefined}
     * 
     */
    function existeSeguimiento(objSg)
    {
        var nrut = lstTmpSg.length;
        var ban = true;

        for (var x = 0; x < nrut; x++) {
            if (    lstTmpSg[x].toString() === objSg.toString() 
                    && banIdRegSg === -1 ) {
                ban = false;
            }
        }

        return ban;
    }

    jQuery( '#btnCancelSeguimiento' ).live( 'click', function(){
        hideFormPlanificacion();
        limpiarFrmSg();
    })


    /**
     * 
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmSg()
    {
        //  Vacio contenido de valor de Linea Base
        jQuery('#jform_fchSeguimiento').attr('value', '');

        //  Vacio contenido de valor de Linea Base
        jQuery('#jform_valorSeguimiento').attr('value', '');
        
        jQuery('#jform_idVariableIndicador').delValidaciones();
        jQuery('#jform_fchSeguimiento').delValidaciones();
        jQuery('#jform_valorSeguimiento').delValidaciones();
        
        banIdRegSg = -1;
    }

})