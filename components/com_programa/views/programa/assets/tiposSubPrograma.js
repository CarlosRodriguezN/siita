function Tiposubprograma(data) {
    if (data == null) {
        data = {
            "idTipoSubPrograma": 0,
            "codigoTipoSubPrograma": "",
            "descripcion": "",
            "estadoTipoSubPrograma": "",
            "idMenu": "",
            "published": 1
        };
    }

    this.idMenu = (data.idMenu) ? data.idMenu : 0;
    this.idTipoSubPrograma = (data.idTipoSubPrograma) ? data.idTipoSubPrograma : 0;
    this.codigoTipoSubPrograma = (data.codigoTipoSubPrograma) ? data.codigoTipoSubPrograma : "";
    this.descripcion = (data.descripcion) ? data.descripcion : "";
    this.estadoTipoSubPrograma = (data.estadoTipoSubPrograma) ? data.estadoTipoSubPrograma : "";
    this.published = (data.published) ? data.published : 1;
};

regTipoSubPrograma = 0;
regSubProgramaSelected = 1;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    /**
     * Agregar Editar SubPrograma
     */
    jQuery('#AddTipoSubPrograma').click(function() {
        var data = new Object();

        data.codigoTipoSubPrograma = jQuery("#jform_strCodigoTipoSubPrograma").val();
        data.descripcion = jQuery("#jform_strDescripcionTipoPrograma").val();
        if (valCampTipoSubPrograma(data)) {
            if (regTipoSubPrograma == 0) {
                clcmpTipoSubPrograma();
                var oTipoSubPrograma = new Tiposubprograma(data);
                addTipoToSubPrograma(oTipoSubPrograma);
                addTipoSubPrograma(oTipoSubPrograma);
            }
            else {
                data.regTipoSubPrograma = regTipoSubPrograma;
                actTipoSubPrograma(data);
            }
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE");
        }
    });

    /**
     * carga par la edición de un subprograma
     */
    jQuery('.editTipoSubPrograma').live("click", function() {
        jQuery('#imgTiposProgramas').css("display", "none");
        jQuery('#formTiposProgramas').css("display", "block");

        regTipoSubPrograma = parseInt(this.parentNode.parentNode.id);

        var data = getTipoSubProgramaByReg(regTipoSubPrograma);

        jQuery("#jform_strCodigoTipoSubPrograma").val(data.codigoTipoSubPrograma);
        jQuery("#jform_strDescripcionTipoPrograma").val(data.descripcion);
        
        resetValidateForm( "#formTpoSubPrgPrg" );

    });

    /**
     * 
     */
    jQuery('.delTipoSubPrograma').live("click", function() {
        var regTipoSubProgramDel = this.parentNode.parentNode.id;
        jConfirm("¿Está seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                if (regTipoSubProgramDel == regTipoSubPrograma) {
                    clcmpTipoSubPrograma();
                }
                elmTipoSubPrograma(regTipoSubProgramDel);
                reloadTipoSubrograma();
            }
        });
    });
    
    /**
     * Agregar un nuevo sub Programa
     */
    jQuery("#addTipoSubProgramaTable").click(function() {
        if (jQuery("#cb_intId_SubPrograma").val() != 0) {
            if ( regTipoSubPrograma != 0 ) {
                clcmpTipoSubPrograma();
            }
            jQuery('#imgTiposProgramas').css("display", "none");
            jQuery('#formTiposProgramas').css("display", "block");
        } else {
            jAlert( JSL_ALERT_SELECT_SP_TSP, JSL_ECORAE);
        }
    });
    
    /**
     * cancelar la edicion de un subprograma.
     */
    jQuery("#cancelTipoSubPrograma").click(function() {
        clcmpTipoSubPrograma();
        regTipoSubPrograma = 0;
        jQuery('#formTiposProgramas').css("display", "none");
        jQuery('#imgTiposProgramas').css("display", "block");
    });
    /**
     * Cambio en la seleccion del subprograma.
     */
    jQuery("#cb_intId_SubPrograma").change(function(event) {
        var idProgramaCB = jQuery("#cb_intId_SubPrograma").val();
        var subPrograma = getSubProgramaByID(idProgramaCB);
        if (subPrograma) {
            regSubProgramaSelected = subPrograma.regSubPrograma;
            reloadTipoSubrograma();
        }
    });

    jQuery("#cb_intId_SubPrograma").trigger("change");

});

/**
 * Valida que los campos necesarios esten ingresados.
 * @param {type} data
 * @returns {Boolean}
 */
function valCampTipoSubPrograma(data) {
    if (data.codigoTipoSubPrograma != "" &&
            data.descripcion != ""
            ) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * @description Agrega una fila a la tabla de prorrogas
 * @param {array} data
 * @returns {undefined}
 */
function addTipoSubPrograma(data) {
    var row = '';
    row += '<tr id="' + data.regTipoSubPrograma + '">';
    row += ' <td>' + data.codigoTipoSubPrograma + ' </td>';
    row += ' <td>' + data.descripcion + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a class="editTipoSubPrograma" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delTipoSubPrograma" >Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px"> Editar </td>';
        row += ' <td style="width: 15px"> Eliminar </td>';
    }

    row += '</tr>';
    jQuery("#tbtiposSubProgramas > tbody:last").append(row);
}

/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clcmpTipoSubPrograma() {
    jQuery('#imgTiposProgramas').css("display", "block");
    jQuery('#formTiposProgramas').css("display", "none");
    
    regTipoSubPrograma = 0;
    resetValidateForm( "#formTpoSubPrgPrg" );
    
    jQuery("#jform_strCodigoTipoSubPrograma").val("");
    jQuery("#jform_strDescripcionTipoPrograma").val("");
    recorrerCombo(jQuery("#jform_publishedTipoSubPrograma option"), 0);
}
/**
 * 
 * @param {type} idRegProrroga
 * @returns {undefined}
 */
function elmTipoSubPrograma(regTipoSubProgramDel) {
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubProgramaSelected) {
            for (var k = 0; k < oPrograma.lstSubProgramas[j].lstTiposSubPrograma.length; k++) {
                if (oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].regTipoSubPrograma == regTipoSubProgramDel) {
                    oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].published = 0;
                }
            }
        }
    }
}

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actTipoSubPrograma(data) {
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubProgramaSelected) {
            for (var k = 0; k < oPrograma.lstSubProgramas[j].lstTiposSubPrograma.length; k++) {
                if (oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].regTipoSubPrograma == data.regTipoSubPrograma) {
                    oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].codigoTipoSubPrograma = data.codigoTipoSubPrograma;
                    oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].descripcion = data.descripcion;
                }
            }
        }
    }
    clcmpTipoSubPrograma();
    reloadTipoSubrograma();
}

/**
 * Construye toda la tabla de tipos de sub Programas 
 * @returns {undefined}
 */
function reloadTipoSubrograma() {
    jQuery("#tbtiposSubProgramas > tbody").empty();
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubProgramaSelected) {
            for (var k = 0; k < oPrograma.lstSubProgramas[j].lstTiposSubPrograma.length; k++) {
                if (oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].published == 1) {
                    addTipoSubPrograma(oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k]);
                }
            }
        }
    }
}

/**
 * Agregando un tipo de subprograma a un subprograma.
 * @param {type} data
 * @returns {undefined}
 */
function addTipoToSubPrograma(data) {
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubProgramaSelected) {
            data.regTipoSubPrograma = oPrograma.lstSubProgramas[j].lstTiposSubPrograma.length + 1;
            oPrograma.lstSubProgramas[j].lstTiposSubPrograma.push(data);
        }
    }
}

function getTipoSubProgramaByReg(regTipoSubPrograma) {
    var data = null;
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubProgramaSelected) {
            for (var k = 0; k < oPrograma.lstSubProgramas[j].lstTiposSubPrograma.length; k++) {
                if (oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k].regTipoSubPrograma == regTipoSubPrograma)
                    data = oPrograma.lstSubProgramas[j].lstTiposSubPrograma[k];
            }
        }
    }
    return data;
}

function getSubProgramaByID(idSubPrograma) {
    var data = null;
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].idSubPrograma == idSubPrograma)
            data = oPrograma.lstSubProgramas[j];
    }
    return data;
}