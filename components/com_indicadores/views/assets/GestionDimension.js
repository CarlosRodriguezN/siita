jQuery(document).ready(function () {
    //  Seteo informacion especifica de otros Indicadores
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var banIdDimension = -1;
    var tmpDimencion = false;


    /**
     *  MUESTRA el FORMULARIO de la DIMENCION
     * @returns {undefined}
     */
    function showFormDM() {
        jQuery('#imgDim').css("display", "none");
        jQuery('#frmDim').css("display", "block");
    }

    /**
     *  OCULTA el FORMULARIO de la DIMENCION 
     * @returns {undefined}
     */
    function hideFormDM() {
        jQuery('#imgDim').css("display", "block");
        jQuery('#frmDim').css("display", "none");
        tmpDimencion = false;
        banIdDimension = -1;
        limpiarFrmDI();
    }

    /**
     * evento CLICK en el boton AGREGAR dimencion
     */
    jQuery('#addLnDimTable').click(function () {
        limpiarFrmDI();
        showFormDM();
    });

    /**
     * evento CLICl en el boton CANCELAR dimencion
     */
    jQuery('#btnCnlDim').click(function () {
        tmpDimencion = false;
        hideFormDM();
    });

    /**
     *  Valida si los campos del formulario estan completos.
     * @param {JSON} data
     * @returns {Boolean}   true en caso de estar completos
     */
    function valDim() {
        var ban = false;
        var idEnfoque   = jQuery('#jform_idEnfoque');
        var idDimension = jQuery('#jform_idDimension');

        if (    idEnfoque.val() !== "" 
                && parseInt( idEnfoque.val() ) > 0
                && idDimension.val() !== "" 
                && parseInt( idDimension.val() ) > 0 ) {
            ban = true;
        } else {
            idEnfoque.validarElemento();
            idDimension.validarElemento();
        }

        return ban;
    }

    /**
     * 
     * @returns {Dimension}
     */
    function getFormDimencion() {
        var idEnfoque = jQuery('#jform_idEnfoque').val();
        var idDimension = jQuery('#jform_idDimension').val();
        var enfoque = jQuery('#jform_idEnfoque :selected').text();
        var dimension = jQuery('#jform_idDimension :selected').text();

        var idRegistro = (banIdDimension === -1) ? lstTmpDim.length
                : banIdDimension;

        var idDimIndicador = (banIdDimension === -1) ? 0
                : lstTmpDim[banIdDimension].idDimIndicador;

        return new Dimension(idRegistro, idDimIndicador, idEnfoque, enfoque, idDimension, dimension);
    }

    /**
     * Actualiza los datos de un registro de una dimencion;
     * @param {type} objDimension
     * @returns {undefined}
     */
    function updRegDimension(objDimension) {
        lstTmpDim[objDimension.idRegDimension] = objDimension;
        updFilaDimension(objDimension.addFilaDimension(1), objDimension.idRegDimension);
        banIdDimension = -1;
        tmpDimencion = false;
        hideFormDM();
    }

    /**
     *  Agrego Enfoque
     */
    jQuery('#btnAddDim').live('click', function () {
        var objDimension = getFormDimencion();
        if ( valDim() ) {
            if (!existeDimension(objDimension)) {
                
                delFilaDM( -1 );
                
                if (banIdDimension != -1) {
                    updRegDimension(objDimension);
                } else {
                    lstTmpDim.push(objDimension);

                    //  Agrego la fila creada a la tabla
                    jQuery('#lstDimensiones > tbody:last').append(objDimension.addFilaDimension(0));
                    hideFormDM();
                }
            } else {
                jAlert(MSG_VALOR_EXISTE_OK, SIITA_ECORAE);
            }
        } else {
            jAlert(MSG_VALOR_INCOMPLETO, SIITA_ECORAE);
        }
    });

    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery('.updDim').live('click', function () {
        var objDimension = getFormDimencion();
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        banIdDimension = idFila;
        if (tmpDimencion) {
            if (tmpDimencion.toString() == objDimension.toString()) {
                loadDataFromDimencion(banIdDimension);
            } else {
                autoSaveDimencion(objDimension, banIdDimension);
            }
        } else {
            loadDataFromDimencion(banIdDimension);
        }
    });

    /**
     * CARGA la informacion de la DIMENCION en el FORMULARIO
     * @param {type} banIdDimension
     * @returns {undefined}
     */
    function loadDataFromDimencion(banIdDimension) {
        for (var x = 0; x < lstTmpDim.length; x++) {
            if (lstTmpDim[x].idRegDimension == banIdDimension) {

                //  Ajusta a una determinada Posicion el combo de fuente
                jQuery('#jform_idEnfoque option').recorrerCombo( lstTmpDim[x].idEnfoque );
                
                //  Simulo la seleccion de un determinado Enfoque y actualizo la lista de 
                jQuery('#jform_idEnfoque').trigger('change', lstTmpDim[x].idDimension);
                tmpDimencion = lstTmpDim[x];
                showFormDM();
            }
        }
    }

    /**
     * 
     * @param {type} objDimension
     * @returns {undefined}
     */
    function autoSaveDimencion(objDimension, banIdDimension) {
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_AUTO_DIMENCION, COM_INIDCADORES_SIITA, function (result) {
            if (result) {
                updRegDimension(objDimension);
                loadDataFromDimencion(banIdDimension);
            } else {
                loadDataFromDimencion(banIdDimension);
            }
        });
    }

    /**
     * gestion la ELIMINACION de la DIMENCION de un indicador.
     */
    jQuery('.delDim').live('click', function () {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_ELIMINAR_DIMENCION, COM_INIDCADORES_SIITA, function (result) {
            if (result) {

                if (lstTmpDim[idFila].idDimIndicador !== 0) {
                    lstTmpDim[idFila].published = 0;
                } else {
                    lstTmpDim.splice(idFila, 1);
                }

                delFilaDM(idFila);
                
                validarFilasDim();
            }
        });
    });


    jQuery('#btnCancelDim').live('click', function () {
        limpiarFrmDI();
    })


    function validarFilasDim()
    {
        var nrDim = lstTmpDim.length;
        
        if( nrDim === 0 ){
            var objDim = new Dimension();
            
            //  Agrego una fila a la tabla de lineas base
            jQuery('#lstDimensiones > tbody:last').append(objDim.addFilaSinRegistros());
        }
        
        return;
    }

    /**
     * Verifica si existe un objeto igual en la lista de objetos dimencion
     * @param {JOSN}    Objeto Dimenciona 
     * @returns {Boolean}   True en caso de existir un objeto igual.
     */
    function existeDimension(objDim) {
        var ban = false;
        for (var x = 0; x < lstTmpDim.length; x++) {
            if (lstTmpDim[x].toString() == objDim.toString()) {
                ban = true;
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
    function updFilaDimension(fila, idReg) {
        jQuery('#lstDimensiones tr').each(function () {
            if (jQuery(this).attr('id') == idReg) {
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
    function delFilaDM(idFila) {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstDimensiones tr').each(function () {
            if ( parseInt( jQuery(this).attr('id') ) === parseInt( idFila ) ) {
                jQuery(this).remove();
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
    function limpiarFrmDI() {
        tmpDimencion = false;
        banIdDimension = -1;

        var idEnfoque   = jQuery('#jform_idEnfoque option');
        var idDimension = jQuery('#jform_idDimension option');

        //  Coloco en la posicion inicial el combo de fuentes
        idEnfoque.recorrerCombo( 0 );

        //  EnceroCombo Combo de Cantones
        idDimension.enCerarCombo();
        
        //  Limpio validaciones de Enfoque
        idEnfoque.delValidaciones();
        
        //  Limpio validaciones de Dimension
        idDimension.delValidaciones();
    }

});