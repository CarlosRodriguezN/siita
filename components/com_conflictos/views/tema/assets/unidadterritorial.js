/**
 *  Class Unidad Territorial
 * @param {type} data
 * @returns {UnidadTerritorial}
 */
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

var regUnidadTerritorial = -1;

jQuery(document).ready(function() {

    jQuery('#saveUnidadTerritorialTema').click(function() {

        var data = new Array();
        data["idProvincia"] = jQuery("#jform_undTerr_provicia").val();
        data["provincia"] = jQuery("#jform_undTerr_provicia :selected").text();
        data["idCanton"] = jQuery("#jform_undTerr_canton").val();
        data["canton"] = jQuery("#jform_undTerr_canton :selected").text();
        data["idParroquia"] = jQuery("#jform_undTerr_parroquia").val();
        data["parroquia"] = jQuery("#jform_undTerr_parroquia :selected").text();

        if (validarCampUndTerr(data)) {
            if (regUnidadTerritorial == -1) {
                var oUnidadTerritorial = new UnidadTerritorial(data);
                oUnidadTerritorial.regUnidadTerritorial = oTema.lstUnidadesTerritoriales.length + 1;
                saveUnidadTerritorialTema(oUnidadTerritorial);
                oTema.lstUnidadesTerritoriales.push(oUnidadTerritorial);
            }
            else {
                data["regUnidadTerritorial"] = regUnidadTerritorial;
                actUnidadTerritorial(data);
                regUnidadTerritorial = -1;
            }
            clCmpUnidadTerritorial();
        } else {
            jAlert( JSL_ALERT_ALL_NEED, JSL_ECORAE );
            return;
        }

    });

    /**
     * 
     */
    jQuery('.updUnidadTerritorial').live("click", function() {
        jQuery('#imgeUnidadTerritorial').css("display", "none");
        jQuery('#formUnidadTerritorial').css("display", "block");
        regUnidadTerritorial = parseInt(this.parentNode.parentNode.id);
        //  recupero la data del array
        data = getUnidadTerritorial(regUnidadTerritorial);
        if (data) {
            recorrerCombo(jQuery("#jform_undTerr_provicia option"), data.idProvincia);
            jQuery("#jform_undTerr_provicia").trigger("change");
        }
        cleanValidateForn ( "#formTemaUndTrr" );
    });

    jQuery('.delUnidadTerritorial').live("click", function() {
        var regProrrogaDel = this.parentNode.parentNode.id;
        jConfirm("¿Desea eliminar la Unidad Territorial?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmUnidadTerritorial(regProrrogaDel);
                reloadUnidadTerritorialTable();
            } else {

            }
        });
    });
    
    /**
     * 
     */
    jQuery("#addUnidadTerritorialTema").click(function() {
        if ( regUnidadTerritorial == -1 ) {
            clCmpUnidadTerritorial();
        }
        jQuery('#imgeUnidadTerritorial').css("display", "none");
        jQuery('#formUnidadTerritorial').css("display", "block");
    });
    
    /**
     * 
     */
    jQuery("#cancelUnidadTerritorialTema").click(function() {
        clCmpUnidadTerritorial();
    });
});

function validarCampUndTerr(data) {
    if (data["idProvincia"] != 0 ) {
        return true;
    } else {
        return false;
    }
}

/**
 * @description Agrega una fila a la tabla de prorrogas
 * @param {array} data
 * @returns {undefined}
 */
function saveUnidadTerritorialTema(data) {

    var cadCanton = (data.idCanton != 0) ? data.canton : "---";
    var cadParroq = (data.idParroquia != 0) ? data.parroquia : "---";
    var row = '';
    row += '<tr id="' + data.regUnidadTerritorial + '">';
    row += ' <td>' + data.provincia + ' </td>';
    row += ' <td>' + cadCanton + ' </td>';
    row += ' <td>' + cadParroq + ' </td>';
    row += ' <td style="width: 15px"><a href="#" class="updUnidadTerritorial" >Editar</a></td>';
    row += ' <td style="width: 15px"><a href="#" class="delUnidadTerritorial" >Eliminar</a></td>';
    row += '</tr>';
    jQuery('#tbLstUnidadTerritorial > tbody:last').append(row);
}

/**
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpUnidadTerritorial() {
    jQuery('#formUnidadTerritorial').css("display", "none");
    jQuery('#imgeUnidadTerritorial').css("display", "block");
    regUnidadTerritorial = -1;
    
    cleanValidateForn ( "#formTemaUndTrr" );
    
    recorrerCombo(jQuery("#jform_undTerr_provicia option"), 0);
    jQuery('#jform_undTerr_canton').html( '<option value="0"> ' + JSL_SELECT_CANTON + ' </option>' );
    jQuery('#jform_undTerr_parroquia').html( '<option value="0"> ' + JSL_SELECT_PARROQUIA + ' </option>' );
}

/**
 * 
 * @param {type} regUnidadTerritorialDel
 * @returns {undefined}
 */
function elmUnidadTerritorial(regUnidadTerritorialDel) {
    for (var j = 0; j < oTema.lstUnidadesTerritoriales.length; j++) {
        if (oTema.lstUnidadesTerritoriales[j].regUnidadTerritorial == regUnidadTerritorialDel) {
            oTema.lstUnidadesTerritoriales[j].published = 0;
        }
    }
}

/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function actUnidadTerritorial(data) {
    for (var j = 0; j < oTema.lstUnidadesTerritoriales.length; j++) {
        if (oTema.lstUnidadesTerritoriales[j].regUnidadTerritorial == data.regUnidadTerritorial) {
            oTema.lstUnidadesTerritoriales[j].canton = data.canton;
            oTema.lstUnidadesTerritoriales[j].idCanton = data.idCanton;
            oTema.lstUnidadesTerritoriales[j].idParroquia = data.idParroquia;
            oTema.lstUnidadesTerritoriales[j].idProvincia = data.idProvincia;
            oTema.lstUnidadesTerritoriales[j].provincia = data.provincia;
            oTema.lstUnidadesTerritoriales[j].parroquia = data.parroquia;
        }
    }
    clCmpUnidadTerritorial();
    reloadUnidadTerritorialTable();
}

/**
 * 
 * @returns {undefined}
 */
function reloadUnidadTerritorialTable() {
    jQuery("#tbLstUnidadTerritorial > tbody").empty();
    for (var j = 0; j < oTema.lstUnidadesTerritoriales.length; j++) {
        if (oTema.lstUnidadesTerritoriales[j].published == 1)
            saveUnidadTerritorialTema(oTema.lstUnidadesTerritoriales[j]);
    }
}

/**
 * 
 * @param {type} regUnidadTerritorial
 * @returns {unresolved}
 */
function  getUnidadTerritorial(regUnidadTerritorial) {
    var data = null;
    for (var j = 0; j < oTema.lstUnidadesTerritoriales.length; j++) {
        if (oTema.lstUnidadesTerritoriales[j].regUnidadTerritorial == regUnidadTerritorial) {
            data = oTema.lstUnidadesTerritoriales[j];
        }
    }
    return  data;
}