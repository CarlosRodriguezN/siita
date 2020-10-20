{// MEDIO DE VERIFICACION
    var MedioVerificacion = function()
    {
        this.idRegMv;
        this.idMedVerificacion;
        this.descMV;
        this.published  = 1;
        this.roles      = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    };

    /**
     * 
     * Retorna informacion relevante de un objeto
     * 
     * @returns {String}
     */
    MedioVerificacion.prototype.toString = function()
    {
        return this.descMV;
    }

    /**
     * setea la informacion del medio de verificacion.
     * @param {type} data   informacion en formato JSON
     * @returns {undefined}
     */
    MedioVerificacion.prototype.setData = function(data)
    {
        this.idRegMv = data.idRegMv;
        this.idMedVerificacion = data.idMedVerificacion;
        this.descMV = data.descMV;
    };

    /**
     * 
     * Retorno Fila de una tabla con informacion de Linea Base 
     * 
     * @param {type} ban
     * @returns {String}
     */
    MedioVerificacion.prototype.addFilaMedVer = function(ban)
    {
        //  Construyo la Fila
        var fila = ( ban === 0 )? "<tr id='" + this.idRegMv + "'>"
                                : "";

        fila += "   <td align='center'>" + this.descMV + "</td>";

        if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
            fila+= "<td align='center'> <a class='updMV'>" + LB_EDITAR + "</a> </td>"
                +  "<td align='center'> <a class='delMV'>" + LB_ELIMINAR + "</a> </td>";
        }else{
            fila+= "<td align='center'>" + LB_EDITAR + "</td>"
                +  "<td align='center'>" + LB_ELIMINAR + "</td>";
        }
        
        fila += ( ban === 0 )   ? "</tr>"
                                : "";

        return fila;
    }
    
    
    MedioVerificacion.prototype.sinRegistros = function()
    {
        //  Construyo la Fila
        var fila = "<tr id='-1'>";
        fila += "       <td align='center' colspan='3'>Sin Registros Disponibles</td>";
        fila += "   </tr>";

        return fila;
    }
    
    
}

var banMV = -1;
var tmpMV = false;

jQuery(document).ready(function() {
    //  Seteo informacion especifica de otros Indicadores

    /**
     *  MUESTRA el FORMULARIO de la DIMENCION
     * @returns {undefined}
     */
    function showFormMV() {
        jQuery('#imgMedioVerificacion').css("display", "none");
        jQuery('#frmMedioVerificacion').css("display", "block");
    }

    /**
     *  OCULTA el FORMULARIO de la DIMENCION 
     * @returns {undefined}
     */
    function hideFormMV() {
        jQuery('#imgMedioVerificacion').css("display", "block");
        jQuery('#frmMedioVerificacion').css("display", "none");
        tmpMV = false;
        banMV = -1;
        limpiarFrmMV();
    }

    /**
     * evento CLICK en el boton AGREGAR dimencion
     */
    jQuery('#btnAddTableMedVerificacion').click(function() {
        banMV = -1;
        limpiarFrmMV();
        showFormMV();
    });

    /**
     * evento CLICl en el boton CANCELAR dimencion
     */
    jQuery('#btnCLnMedVerificacion').click(function() {
        tmpMV = false;
        hideFormMV();
    });

    /**
     *  Valida si los campos del formulario estan completos.
     * @param {JSON} data
     * @returns {Boolean}   true en caso de estar completos
     */
    function valMedVer(data) {
        var flag = false;
        if (data.descMV != "")
            flag = true;
        return flag;
    }

    /**
     * 
     * @returns {Dimension}
     */
    function getFormMedVer() {
        var data = {"descMV": jQuery("#jform_txtMedVerificacion").val(),
            "idRegMv": (banMV == -1) ? lstTmpMedVerificacion.length : banMV,
            "idMedVerificacion": (banMV == -1) ? 0 : lstTmpMedVerificacion[banMV].idMedVerificacion
        };

        var objMedVer = new MedioVerificacion();
        objMedVer.setData(data);
        return objMedVer;
    }

    /**
     * Actualiza los datos de un registro de una dimencion;
     * @param {type} objMedVer
     * @returns {undefined}
     */
    function updRegMedVer(objMedVer) {
        lstTmpMedVerificacion[objMedVer.idRegMv] = objMedVer;
        updFilaMedVer(objMedVer.addFilaMedVer(1), objMedVer.idRegMv);
        banMV = -1;
        tmpMV = false;
        hideFormMV();
    }

    /**
     *  Agrego Enfoque
     */
    jQuery('#btnAddMedVerificacion').live('click', function() {
        var objMedVer = getFormMedVer();
        if (valMedVer(objMedVer)) {
            if (banMV != -1) {
                updRegMedVer(objMedVer);
            } else {
                lstTmpMedVerificacion.push(objMedVer);
                //  Agrego la fila creada a la tabla
                jQuery('#lstMedVertificacion > tbody:last').append(objMedVer.addFilaMedVer(0));
                hideFormMV();
            }
        } else {
            jAlert(MSG_VALOR_INCOMPLETO, SIITA_ECORAE);
        }
    });

    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery('.updMV').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        
        var objMedVer = getFormMedVer();
        banMV = idFila;

        if (tmpMV) {
            if (tmpMV.toString() == objMedVer.toString()) {
                loadDataFromMedVer(banMV);
            } else {
                autoSaveMedVer(objMedVer, banMV);
            }
        } else {
            loadDataFromMedVer(banMV);
        }
    });

    /**
     * CARGA la informacion de la DIMENCION en el FORMULARIO
     * @param {type} banMV
     * @returns {undefined}
     */
    function loadDataFromMedVer(banMV) {
        for (var x = 0; x < lstTmpMedVerificacion.length; x++) {
            if (lstTmpMedVerificacion[x].idRegMv == banMV) {
                jQuery("#jform_txtMedVerificacion").val(lstTmpMedVerificacion[x].descMV);
                tmpMV = lstTmpMedVerificacion[x];
                showFormMV();
            }
        }
    }

    /**
     * 
     * @param {type} objMedVer
     * @returns {undefined}
     */
    function autoSaveMedVer(objMedVer, banMV) {
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_AUTO_MED_VER, SIITA_ECORAE, function(result) {
            if (result) {
                updRegMedVer(objMedVer);
                loadDataFromMedVer(banMV);
            } else {
                loadDataFromMedVer(banMV);
            }
        });
    }

    /**
     * gestion la ELIMINACION de la DIMENCION de un indicador.
     */
    jQuery('.delMV').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_PROYECTO_ELIMINAR_MEDIO_VERIFICACION, SIITA_ECORAE, function(result) {
            if (result) {
                lstTmpMedVerificacion[idFila].published = 0;
                delFilaMV(idFila);
            }
        });
    });

    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaMV(idFila) {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstMedVertificacion tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
            }
        });
    }

    /**
     * Verifica si existe un objeto igual en la lista de objetos dimencion
     * @param {JOSN}    Objeto Dimenciona 
     * @returns {Boolean}   True en caso de existir un objeto igual.
     */
    function existeDimension(objMeVer) {
        var ban = false;
        for (var x = 0; x < lstTmpMedVerificacion.length; x++) {
            if (lstTmpMedVerificacion[x].toString() == objMeVer.toString()) {
                ban = true;
            }
        }
        return ban;
    }

    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaMV(idFila) {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstMedVertificacion tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
                jQuery(this).remove();
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
    function recorrerCombo(combo, posicion) {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }

    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaMedVer(fila, idReg) {
        jQuery('#lstMedVertificacion tr').each(function() {
            if (jQuery(this).attr('id') == idReg) {
                jQuery(this).html(fila);
            }
        });
    }


    /**
     * Coloca un select en la posicion 0
     * @param {object}  Select
     * @returns {undefined}
     */
    function enCerarCombo(combo) {
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
    function limpiarFrmMV() {
        tmpMV = false;
        jQuery("#jform_txtMedVerificacion").val("");
    }

});