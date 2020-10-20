function UnidadTerritorial(data) {
    if (data == null) {
        data = {
            "idProvincia": 0,
            "provincia": '---',
            "idCanton": 0,
            "canton": '---',
            "idParroquia": 0,
            "parroquia": '---',
            "published": 1,
        };
    }
    this.idProvincia = (data.idProvincia) ? data.idProvincia : 0;
    this.provincia = (data.provincia) ? data.provincia : "---";
    this.idCanton = (data.idCanton) ? data.idCanton : 0;
    this.canton = (data.canton) ? data.canton : "---";
    this.idParroquia = (data.idParroquia) ? data.idParroquia : 0;
    this.parroquia = (data.parroquia) ? data.parroquia : "---";
    this.published = 1;
}

var regUnidadTerritorial = 0;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {

    jQuery('#addUnidadTerritorial').click(function() {

        var data = new Array();
        data["idProvincia"] = jQuery("#jform_intIDProvincia_dpa").val();
        data["provincia"] = jQuery("#jform_intIDProvincia_dpa :selected").text();
        data["idCanton"] = jQuery("#jform_intIDCanton_dpa").val();
        data["canton"] = jQuery("#jform_intIDCanton_dpa :selected").text();
        data["idParroquia"] = jQuery("#jform_intIDParroquia_dpa").val();
        data["parroquia"] = jQuery("#jform_intIDParroquia_dpa :selected").text();

        if (validarCampUndTerr(data)) {
            if (regUnidadTerritorial == 0) {
                clCmpUnidadTerritorial();
                var oUnidadTerritorial = new UnidadTerritorial(data);
                oUnidadTerritorial.regUnidadTerritorial = contratos.lstUnidadesTerritoriales.length + 1;
                addUnidadTerritorial(oUnidadTerritorial);
                contratos.lstUnidadesTerritoriales.push(oUnidadTerritorial);
                clCmpUnidadTerritorial();
            }
            else {
                data["regUnidadTerritorial"] = regUnidadTerritorial;
                actUnidadTerritorial(data);
            }
            
        } else {
            jAlert("Campos con asterisco, SON OBLIGATORIOS", "SIITA - ECORAE")
            return;
        }

    });


    jQuery('.editUndTrr').live("click", function() {
        jQuery('#ieavUnidadTerritorial').css("display", "none");
        jQuery('#editUnidadTerritorialForm').css("display", "block");
        regUnidadTerritorial = parseInt(this.parentNode.parentNode.id);
        //  recupero la data del array
        data = getUnidadTerritorial(regUnidadTerritorial);
        if (data) {
            recorrerCombo(jQuery("#jform_intIDProvincia_dpa option"), data.idProvincia);
            jQuery("#jform_intIDProvincia_dpa").trigger("change", data.idCanton);
        }
    });

    jQuery('.delUndTrr').live("click", function() {
        var regProrrogaDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                if (regUnidadTerritorial == regProrrogaDel) {
                    clCmpUnidadTerritorial();
                }
                elmUnidadTerritorial(regProrrogaDel);
                reloadUnidadTerritorialTable();
            } else {

            }
        });
    });
    
    jQuery("#addUnidadTerritorialTable").click(function() {
        if( regUnidadTerritorial != 0 ){
            clCmpUnidadTerritorial();
        }
        jQuery('#ieavUnidadTerritorial').css("display", "none");
        jQuery('#editUnidadTerritorialForm').css("display", "block");
    });
    
    jQuery("#cancelUnidadTerritorial").click(function() {
        clCmpUnidadTerritorial();
    });
    
});

function validarCampUndTerr(data) {
    if (data["idProvincia"] != 0
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
function addUnidadTerritorial(data) {

    var cadCanton = (data.idCanton != 0) ? data.canton : "---";
    var cadParroq = (data.idParroquia != 0) ? data.parroquia : "---";
    var row = '';
    row += '<tr id="' + data.regUnidadTerritorial + '">';
    row += ' <td>' + data.provincia + ' </td>';
    row += ' <td>' + cadCanton + ' </td>';
    row += ' <td>' + cadParroq + ' </td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td style="width: 15px"><a class="editUndTrr">Editar</a></td>';
        row += ' <td style="width: 15px"><a class="delUndTrr">Eliminar</a></td>';
    }else{
        row += ' <td style="width: 15px">Editar</td>';
        row += ' <td style="width: 15px">Eliminar</td>';
    }

    row += '</tr>';
    jQuery('#tbLtUnidadTerritorialContrato > tbody:last').append(row);
}
/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpUnidadTerritorial() {
    jQuery('#editUnidadTerritorialForm').css("display", "none");
    jQuery('#ieavUnidadTerritorial').css("display", "block");
    
    regUnidadTerritorial = 0;
    resetValidateForm( "#formUbcTerritorialCnt" );
    
    resetValidateForm("#formUbcTerritorialCnt");
    recorrerCombo(jQuery("#jform_intIDProvincia_dpa option"), 0);
    jQuery("#jform_intIDCanton_dpa").html('<option value="0">' + JSL_SELECTED_CANTON + '</option>');
    jQuery("#jform_intIDParroquia_dpa").html('<option value="0">' + JSL_SELECTED_PARROQUIA + '</option>');
    
}
/**
 * 
 * @param {type} regUnidadTerritorialDel
 * @returns {undefined}
 */
function elmUnidadTerritorial(regUnidadTerritorialDel) {
    for (var j = 0; j < contratos.lstUnidadesTerritoriales.length; j++) {
        if (contratos.lstUnidadesTerritoriales[j].regUnidadTerritorial == regUnidadTerritorialDel) {
            contratos.lstUnidadesTerritoriales[j].published = 0;
        }
    }
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actUnidadTerritorial(data) {
    for (var j = 0; j < contratos.lstUnidadesTerritoriales.length; j++) {
        if (contratos.lstUnidadesTerritoriales[j].regUnidadTerritorial == data.regUnidadTerritorial) {
            contratos.lstUnidadesTerritoriales[j].canton = data.canton;
            contratos.lstUnidadesTerritoriales[j].idCanton = data.idCanton;
            contratos.lstUnidadesTerritoriales[j].idParroquia = data.idParroquia;
            contratos.lstUnidadesTerritoriales[j].idProvincia = data.idProvincia;
            contratos.lstUnidadesTerritoriales[j].provincia = data.provincia;
            contratos.lstUnidadesTerritoriales[j].parroquia = data.parroquia;
        }
    }
    clCmpUnidadTerritorial();
    reloadUnidadTerritorialTable();
}
function reloadUnidadTerritorialTable() {
    jQuery("#tbLtUnidadTerritorialContrato > tbody").empty();
    for (var j = 0; j < contratos.lstUnidadesTerritoriales.length; j++) {
        if (contratos.lstUnidadesTerritoriales[j].published == 1)
            addUnidadTerritorial(contratos.lstUnidadesTerritoriales[j]);
    }
}
/**
 * 
 * @param {type} regUnidadTerritorial
 * @returns {unresolved}
 */
function  getUnidadTerritorial(regUnidadTerritorial) {
    var data = null;
    for (var j = 0; j < contratos.lstUnidadesTerritoriales.length; j++) {
        if (contratos.lstUnidadesTerritoriales[j].regUnidadTerritorial == regUnidadTerritorial) {
            data = contratos.lstUnidadesTerritoriales[j];
        }
    }
    return  data;
}