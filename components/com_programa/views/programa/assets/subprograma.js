var regSubPrograma = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
function Programa(data) {
    if (data == null) {
        data = {
            lstSubProgramas: new Array(),
            idEntidad: 0,
            idMenu: 0
        };
    }
    this.idMenu = (data.idMenu) ? data.idMenu : 0;
    this.idEntidad = (data.idEntidad) ? data.idEntidad : 0;
    this.lstSubProgramas = (data.lstSubProgramas) ? data.lstSubProgramas : "0";
}


function Subprograma(data) {
    if (data == null) {
        data = {
            "idSubPrograma": data.idSubPrograma,
            "codigoSubPrograma": data.codigoSubPrograma,
            "alias": data.alias,
            "descripcion": data.descripcion,
            "lstTiposSubPrograma": new Array(),
            "idMenu": 0,
            "published": 1
        };
    }

    this.idSubPrograma = (data.idSubPrograma) ? data.idSubPrograma : "0";
    this.codigoSubPrograma = (data.codigoSubPrograma) ? data.codigoSubPrograma : "0";
    this.alias = (data.alias) ? data.alias : "";
    this.descripcion = (data.descripcion) ? data.descripcion : "";
    this.lstTiposSubPrograma = new Array();
    this.idMenu = (data.idMenu) ? data.idMenu : "0";
    this.published = 1;
}


var regSubPrograma = 0;

jQuery(document).ready(function() {
    /**
     * Agregar Editar SubPrograma
     */
    jQuery('#addSubPrograma').click(function() {
        var data = new Object();

        data.alias = jQuery("#jform_strAlias").val();
        data.codigoSubPrograma = jQuery("#jform_strCodigoSubPrograma").val();
        data.descripcion = jQuery("#jform_strDescripcion").val();

        if (validarCampSubPrograma()) {
            if (regSubPrograma == 0) {
                var oSubPrograma = new Subprograma(data);
                oSubPrograma.regSubPrograma = oPrograma.lstSubProgramas.length + 1;
                addSubPrograma(oSubPrograma);
                oPrograma.lstSubProgramas.push(oSubPrograma);
                clcmpSubPrograma();
            }
            else {
                data.regSubPrograma = regSubPrograma;
                actSubPrograma(data);
            }
            reloadOptionsSubPrograma();
            jQuery('#formSubPrograma').css("display", "none");
            jQuery('#imgSubPrograma').css("display", "block");
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE");
            return;
        }

    });
    /**
     * carga par la edición de un subprograma
     */
    jQuery('.editSubPrograma').live("click", function() {
        jQuery('#imgSubPrograma').css("display", "none");
        jQuery('#formSubPrograma').css("display", "block");

        regSubPrograma = parseInt(this.parentNode.parentNode.id);
        //  recupero la data del array
        var data;
        for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
            if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubPrograma) {
                data = oPrograma.lstSubProgramas[j];
            }
        }
        jQuery("#jform_strAlias").val(data.alias);
        jQuery("#jform_strCodigoSubPrograma").val(data.codigoSubPrograma);
        jQuery("#jform_strDescripcion").val(data.descripcion);
        recorrerCombo(jQuery("#jform_publishedSubPrograma option"), data.estadoSubPrograma);

        resetValidateForm("#frmSubPrgPrg");

    });

    jQuery('.delSubPrograma').live("click", function() {
        var regProrrogaDel = this.parentNode.parentNode.id;
        jConfirm("¿Está seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                if (regProrrogaDel == regSubPrograma) {
                    clcmpSubPrograma();
                }
                elmSubPrograma(regProrrogaDel);
                reloadSubrograma();
                reloadOptionsSubPrograma();
            }
        });
    });

    /**
     * Agregar un nuevo sub Programa
     */
    jQuery("#addSubPorgramaTable").click(function() {
        if (regSubPrograma != 0) {
            clcmpSubPrograma();
        }
        jQuery('#imgSubPrograma').css("display", "none");
        jQuery('#formSubPrograma').css("display", "block");
    });

    /**
     * cancelar la edicion de un subprograma.
     */
    jQuery("#cancelarSubPrograma").click(function() {
        clcmpSubPrograma();
    });

    jQuery(".trSubPrograma").live("click", function() {
        regSubProgramaSelected = this.id;
        var data = getSubProgramaByReg(this.id);
        recorrerCombo(jQuery("#cb_intId_SubPrograma option"), data.idSubPrograma);
        jQuery("#cb_intId_SubPrograma").trigger("change");
    });

});

function _validarCampSubPrograma(data) {
    if (data.alias != "" &&
            data.codigoSubPrograma != "" &&
            data.descripcion != ""
            ) {
        return true;
    } else {
        return false;
    }
}


function validarCampSubPrograma(data)
{
    var result = false;
    var idSubPrg = jQuery("#jform_strCodigoSubPrograma");
    var descripcion = jQuery("#jform_strDescripcion");

    if (idSubPrg.val() !== ""
            && descripcion.val() !== "") {
        result = true;
    } else {
        idSubPrg.validarElemento();
        descripcion.validarElemento();
    }

    return result;
}


function limpiarFrmSubPrograma()
{
    jQuery("#jform_strCodigoSubPrograma").delValidaciones();
    jQuery("#jform_strDescripcion").delValidaciones();
}


/**
 * @description Agrega una fila a la tabla de prorrogas
 * @param {array} data
 * @returns {undefined}
 */
function addSubPrograma(data) {
    var row = '';
    row += '<tr id="' + data.regSubPrograma + '" class="trSubPrograma">';
    row += ' <td>' + data.codigoSubPrograma + ' </td>';
    row += ' <td>' + data.alias + ' </td>';
    row += ' <td>' + data.descripcion + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a class="editSubPrograma" >Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delSubPrograma" >Eliminar</a></td>';
        
    }else{
        row += ' <td style="width: 15px"> Editar </td>';
        row += ' <td style="width: 15px"> Eliminar </td>';
    }
    
    
    row += '</tr>';
    jQuery('#tbSubProgramas > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clcmpSubPrograma() {
    jQuery('#formSubPrograma').css("display", "none");
    jQuery('#imgSubPrograma').css("display", "block");

    regSubPrograma = 0;
    resetValidateForm("#frmSubPrgPrg");

    jQuery("#jform_strAlias").val("");
    jQuery("#jform_strCodigoSubPrograma").val("");
    jQuery("#jform_strDescripcion").val("");
    jQuery("#programa-form").submit();
    jQuery("#jform_strAlias").removeClass("error");
    jQuery("#jform_strCodigoSubPrograma").removeClass("error");
    jQuery("#jform_strDescripcion").removeClass("error");
    
    
    limpiarFrmSubPrograma();
    
}
/**
 * 
 * @param {type} idRegProrroga
 * @returns {undefined}
 */
function elmSubPrograma(regSubProgramaElim) {
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regSubProgramaElim) {
            oPrograma.lstSubProgramas[j].published = 0;
        }
    }
}

function actSubPrograma(data) {
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == data.regSubPrograma) {
            oPrograma.lstSubProgramas[j].alias = data.alias;
            oPrograma.lstSubProgramas[j].codigoSubPrograma = data.codigoSubPrograma;
            oPrograma.lstSubProgramas[j].descripcion = data.descripcion;
            oPrograma.lstSubProgramas[j].published = 1;
        }
    }
    clcmpSubPrograma();
    reloadSubrograma();
}

function reloadSubrograma() {
    jQuery("#tbSubProgramas > tbody").empty();
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].published == 1)
            addSubPrograma(oPrograma.lstSubProgramas[j]);
    }
}


function reloadOptionsSubPrograma() {
    var items = new Array();
    jQuery('#cb_intId_SubPrograma').html(items.join(''));
    if (oPrograma.lstSubProgramas.length > 0) {
        items.push('<option value="0">SELECCIONE UN SUB PROGRAMA</option>');
        for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
            if (oPrograma.lstSubProgramas[j].published == 1)
                items.push('<option value="' + oPrograma.lstSubProgramas[j].regSubPrograma + '">' + oPrograma.lstSubProgramas[j].alias + '</option>');
        }
    } else {
        items.push('<option value="0">SIN REGISTROS DISPONIBLES</option>');
    }
    jQuery('#cb_intId_SubPrograma').html(items.join(''));
}


function getSubProgramaByReg(regTr) {
    var data = null;
    for (var j = 0; j < oPrograma.lstSubProgramas.length; j++) {
        if (oPrograma.lstSubProgramas[j].regSubPrograma == regTr)
            data = oPrograma.lstSubProgramas[j];
    }
    return data;
}



