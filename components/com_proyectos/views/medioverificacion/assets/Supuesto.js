
{// SUPUESTO

    var Supuesto = function()
    {
        this.idRegSup;
        this.idSupuesto;
        this.descSupuesto;
        this.published=1;
        this.roles      = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    };


    Supuesto.prototype.toString = function()
    {
        return this.descSupuesto;
    }


    Supuesto.prototype.setData = function(data)
    {
        this.idRegSup   = data.idRegSup;
        this.idSupuesto = data.idSupuesto;
        this.descSupuesto = data.descSupuesto;
    }
    /**
     * 
     * Retorno Fila de una tabla con informacion de Linea Base 
     * 
     * @param {type} ban
     * @returns {String}
     */
    Supuesto.prototype.addFilaSupuesto = function(ban)
    {
        //  Construyo la Fila
        var fila = (ban == 0) ? "<tr id='" + this.idRegSup + "'>"
                : "";

        fila += "   <td align='center'>" + this.descSupuesto + "</td>";
        
        if( this.roles["core.create"] === true || this.roles["core.edit"] === true ){
            fila+= " <td align='center'> <a class='updSP'>" + LB_EDITAR + "</a> </td>"
                + " <td align='center'> <a class='delSP'>" + LB_ELIMINAR + "</a> </td>";
        }else{
            fila+= " <td align='center'>" + LB_EDITAR + "</td>"
                + " <td align='center'>" + LB_ELIMINAR + "</td>";
        }

        fila += (ban == 0) ? "</tr>"
                : "";

        return fila;
    }
    
    
    Supuesto.prototype.sinRegistros = function()
    {
        //  Construyo la Fila
        var fila = "<tr id='-1'>";
        fila += "       <td align='center' colspan='3'>Sin Registros Disponibles</td>";
        fila += "   </tr>";

        return fila;
    }
    
    
}

     


jQuery(document).ready(function() {
    //  Seteo informacion especifica de otros Indicadores
    var banSP = -1;
    var tmpSP = false;

    /**
     *  MUESTRA el FORMULARIO de la DIMENCION
     * @returns {undefined}
     */
    function showFormSP() {
        jQuery('#imgSupuesto').css("display", "none");
        jQuery('#frmSupuesto').css("display", "block");
    }

    /**
     *  OCULTA el FORMULARIO de la DIMENCION 
     * @returns {undefined}
     */
    function hideFormSP() {
        jQuery('#imgSupuesto').css("display", "block");
        jQuery('#frmSupuesto').css("display", "none");
        tmpSP = false;
        banSP = -1;
        limpiarFrmSP();
    }

    /**
     * evento CLICK en el boton AGREGAR dimencion
     */
    jQuery('#btnAddTableSupuesto').click(function() {
        banSP = -1;
        limpiarFrmSP();
        showFormSP();
    });

    /**
     * evento CLICl en el boton CANCELAR dimencion
     */
    jQuery('#btnClnSupuesto').click(function() {
        tmpSP = false;
        hideFormSP();
    });

    /**
     *  Valida si los campos del formulario estan completos.
     * @param {JSON} data
     * @returns {Boolean}   true en caso de estar completos
     */
    function valMedVer(data) {
        var flag = false;
        if (data.descSupuesto != "")
            flag = true;
        return flag;
    }

    /**
     * 
     * @returns {Dimension}
     */
    function getFormSup() {
        var data = {
            "descSupuesto"  : jQuery("#jform_txtSupuestos").val(),
            "idRegSup"      : (banSP == -1) ? lstTmpSupuestos.length : banSP,
            "idSupuesto"    : (banSP == -1) ? 0 : lstTmpSupuestos[banSP].idSupuesto
        };

        var objSup = new Supuesto();
        objSup.setData(data);
        return objSup;
    }

    /**
     * Actualiza los datos de un registro de una dimencion;
     * @param {type} objSup
     * @returns {undefined}
     */
    function updRegSup(objSup) {
        lstTmpSupuestos[objSup.idRegSup] = objSup;
        updFilaSupu(objSup.addFilaSupuesto(1), objSup.idRegSup);
        banSP = -1;
        tmpSP = false;
        hideFormSP();
    }

    /**
     *  Agrego Enfoque
     */
    jQuery('#btnAddSupuesto').live('click', function() {
        var objSup = getFormSup();
        if (valMedVer(objSup)) {
            if (banSP != -1) {
                updRegSup(objSup);
            } else {
                lstTmpSupuestos.push(objSup);
                //  Agrego la fila creada a la tabla
                jQuery('#lstSupuestos > tbody:last').append(objSup.addFilaSupuesto(0));
                hideFormSP();
            }
        } else {
            jAlert(MSG_VALOR_INCOMPLETO, SIITA_ECORAE);
        }
    });

    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery('.updSP').live('click', function() {
        
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');
        
        var objSup = getFormSup();
        banSP = idFila;

        if (tmpSP) {
            if (tmpSP.toString() == objSup.toString()) {
                loadDataFromSupu(banSP);
            } else {
                autoSaveMedVer(objSup, banSP);
            }
        } else {
            loadDataFromSupu(banSP);
        }
    });

    /**
     * CARGA la informacion de la DIMENCION en el FORMULARIO
     * @param {type} banSP
     * @returns {undefined}
     */
    function loadDataFromSupu(banSP) {
        for (var x = 0; x < lstTmpSupuestos.length; x++) {
            if (lstTmpSupuestos[x].idRegSup == banSP) {
                jQuery("#jform_txtSupuestos").val(lstTmpSupuestos[x].descSupuesto);
                tmpSP = lstTmpSupuestos[x];
                showFormSP();
            }
        }
    }

    /**
     * 
     * @param {type} objSup
     * @returns {undefined}
     */
    function autoSaveMedVer(objSup, banSP) {
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_AUTO_MED_VER, SIITA_ECORAE, function(result) {
            if (result) {
                updRegSup(objSup);
                loadDataFromSupu(banSP);
            } else {
                loadDataFromSupu(banSP);
            }
        });
    }

    /**
     * gestion la ELIMINACION de la DIMENCION de un indicador.
     */
    jQuery('.delSP').live('click', function() {
        var updFila = jQuery(this).parent().parent();
        var idFila = updFila.attr('id');

        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_PROYECTO_ELIMINAR_SUPUESTO, SIITA_ECORAE, function(result) {
            if (result) {
                lstTmpSupuestos[idFila].published=0;
                delFilaSP(idFila);
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
    function delFilaSP(idFila) {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstSupuestos tr').each(function() {
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
    function existeDimension(objSupu) {
        var ban = false;
        for (var x = 0; x < lstTmpSupuestos.length; x++) {
            if (lstTmpSupuestos[x].toString() == objSupu.toString()) {
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
    function updFilaSupu(fila, idReg) {
        jQuery('#lstSupuestos tr').each(function() {
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
    function delFilaSP(idFila) {
        //  Elimino fila de la tabla lista de GAP
        jQuery('#lstSupuestos tr').each(function() {
            if (jQuery(this).attr('id') == idFila) {
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
    function limpiarFrmSP() {
        tmpSP = false;
        jQuery("#jform_txtSupuestos").val("");
    }

});