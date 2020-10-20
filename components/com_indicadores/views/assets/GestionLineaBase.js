jQuery.alerts.okButton = JSL_OK;
jQuery.alerts.cancelButton = JSL_CANCEL;
jQuery(document).ready(function () {
    var banIdRegLB = -1;
    var dtaInfoLB;
    
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')', window.parent.document );
    
    /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFormLB() {
        jQuery('#imgLnBase').css("display", "none");
        jQuery('#frmLnBase').css("display", "block");
    }
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFormLB() {
        jQuery('#imgLnBase').css("display", "block");
        jQuery('#frmLnBase').css("display", "none");
        banIdRegLB = -1;
        limpiarFrmLB();
    }


    jQuery('#addLnBaseTable').click(function () {
        if (lstTmpLB.length == 0) {
            limpiarFrmLB();
            showFormLB();
        } else {
            jAlert(MSG_VALOR_SOLO_UNA_LB, SIITA_ECORAE);
        }
    });

    jQuery('#btnCnlLineaBase').click(function () {
        hideFormLB();
    });

    /**
     *  Valida si los campos del formulario estan completos.
     * @param {JSON} data
     * @returns {Boolean}   true en caso de estar completos
     */
    function valLnBase() {
        var ban = false;
        var idFuente    = jQuery( '#jform_idFuente' );
        var idLineaBase = jQuery( '#jform_idLineaBase' );
        
        if( idFuente.val() !== "" 
            && parseInt( idFuente.val() ) > 0
            && idLineaBase.val() !== "" ){
            ban = true;
        }else{
            idFuente.validarElemento();
            idLineaBase.validarElemento();
        }
        
        return ban;
    }
    
    /**
     *  Agrego un registro de Linea Base
     */
    jQuery('#btnAddLineaBase').live('click', function () {
        var idFuente    = jQuery('#jform_idFuente').val();
        var fuente      = jQuery('#jform_idFuente :selected').text();
        var idLineaBase = jQuery('#jform_idLineaBase').val();
        var lineaBase   = jQuery('#jform_idLineaBase :selected').text();
        var valorLB     = (parseFloat(jQuery('#jform_valorLineaBase').val())).toFixed(2);
        var idRegLB     = lstTmpLB.length;

        var lineaBase = new LineaBase(idRegLB, idLineaBase, lineaBase, valorLB, idFuente, fuente);

        if ( valLnBase() ) {
            if (!existeLineaBase(lineaBase)) {
                
                delFilaLB( -1 );
                
                if ( parseInt( banIdRegLB ) !== -1 ) {
                    lstTmpLB[banIdRegLB] = lineaBase;

                    lstTmpLB[banIdRegLB].isNew = ( parseInt( lstTmpLB[banIdRegLB].idLineaBase ) !== 0 ) 
                                                    ? 1 
                                                    : 0;
                                                    
                    updFilaLB( lineaBase.getFilaLineaBase( 1 ) );
                } else {
                    lstTmpLB.push(lineaBase);

                    //  Agrego la fila creada a la tabla
                    jQuery('#lstLineasBase > tbody:last').append(lineaBase.getFilaLineaBase(0));
                }
                hideFormLB();
            } else {
                jAlert(MSG_VALOR_EXISTE_OK, SIITA_ECORAE);
            }
        } else {
            jAlert(MSG_VALOR_INCOMPLETO, SIITA_ECORAE);
        }
    });

    /**
     *  Actualizo el valor de linea base
     */
    jQuery('#jform_idLineaBase').change(function () {
        for (var x = 0; x < dtaInfoLB.length; x++) {
            if (dtaInfoLB[x].id == jQuery(this).val()) {
                jQuery('#jform_valorLineaBase').attr('value', (parseFloat(dtaInfoLB[x].valor)).toFixed(2));
            }
        }
    })

    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery('.updLB').live('click', function () {

        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        banIdRegLB = idFila;

        for (var x = 0; x < lstTmpLB.length; x++) {
            if ( parseInt( lstTmpLB[x].idRegLB ) === parseInt( banIdRegLB ) ){
                //  Ajusta a una determinada Posicion el combo de fuente                
                jQuery('#jform_idFuente option').recorrerCombo( lstTmpLB[x].idFuente);

                //  Simulo un cambio en la lista de fuentes y ajusto el contenido de linea base  
                jQuery('#jform_idFuente').trigger('change', [lstTmpLB[x].idFuente, lstTmpLB[x].idLineaBase]);

                //  Ajusto el valor de linea base
                jQuery('#jform_valorLineaBase').attr('value', (parseFloat(lstTmpLB[x].valor)).toFixed(2));

                showFormLB();
            }
        }
    })

    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery('.delLB').live('click', function () {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_ELIMINAR_LINEA_BASE, COM_INIDCADORES_SIITA, function (result) {
            if (result) {
                lstTmpLB.splice(idFila, 1);
                delFilaLB( idFila );
                
                validarElementosLB();
            }
        });
    });
    
    
    
    function validarElementosLB()
    {
        var nrLB = lstTmpLB.length;
        
        if( nrLB === 0 ){
            objLB = new LineaBase();
            jQuery('#lstLineasBase > tbody:last').append( objLB.getFilaSinRegistros() );
        }

    }
    
    

    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaLB(fila) {
        jQuery('#lstLineasBase tr').each(function () {
            if (jQuery(this).attr('id') == banIdRegLB) {
                jQuery(this).html(fila);
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
    function delFilaLB(idFila) {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstLineasBase tr').each(function () {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
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
    function existeLineaBase(objLineaBase) {
        var ban = false;

        for (var x = 0; x < lstTmpLB.length; x++) {
            if( lstTmpLB[x].toString() === objLineaBase.toString() && banIdRegLB === -1 ) {
                ban = true;
            }
        }

        return ban;
    }

    jQuery('#jform_idFuente').change(function (event, idFuente, idLineaBase) {
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        //  Cambio mensaje de combo linea base
        jQuery('#jform_idLineaBase').html('<option value="0">CARGANDO...</option>');

        //  Vacio contenido de valor de Linea Base
        jQuery('#jform_valorLineaBase').attr('value', '');

        jQuery.ajax({type: 'GET',
            url: path,
            dataType: 'JSON',
            data: { option: 'com_indicadores',
                    view: 'indicador',
                    tmpl: 'component',
                    format: 'json',
                    action: 'getLineasBase',
                    idFuenteLB: jQuery(this).val()
            },
            error: function (jqXHR, status, error) {
                alert(COM_INDICADORES_LINEA_BASE_FUENTE_ERROR + '  ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function (data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            if (numRegistros > 0) {
                var items = [];
                var selected = '';
                dtaInfoLB = dataInfo;

                for (var x = 0; x < numRegistros; x++) {
                    if ( typeOf( idLineaBase ) !== 'null' ) {
                        selected = ( parseInt( dataInfo[x].id ) === parseInt( idLineaBase ) )
                                        ? 'selected'
                                        : '';
                    } else {
                        selected = ( dataInfo[x].id === "" || parseInt( dataInfo[x].id ) === 0 ) 
                                        ? 'selected'
                                        : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '"' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idLineaBase').html(items.join(''));
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
        jQuery('#jform_idFuente option').recorrerCombo(0);

        //  EnceroCombo Combo de Lineas Base
        jQuery('#jform_idLineaBase option').enCerarCombo();

        //  Vacio contenido de valor de Linea Base
        jQuery('#jform_valorLineaBase').attr('value', '');
    }

})