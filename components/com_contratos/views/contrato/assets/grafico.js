function Grafico(data) {
    if (data == null) {
        data = {
            "idGrafico": "0",
            "idTipoGrafico": 0,
            "nmbTipoGrafico": "Sin Nombre",
            "descripcion": "0",
            "lstCoordenadas": new Array(),
            "published": 0
        };
    }
    this.idGrafico      = (data.idGrafico) ? data.idGrafico : "0";
    this.idTipoGrafico  = (data.idTipoGrafico) ? data.idTipoGrafico : "0";
    this.nmbTipoGrafico = (data.nmbTipoGrafico) ? data.nmbTipoGrafico : "Sin Nombre";
    this.descripcion    = (data.descripcion) ? data.descripcion : "0";
    this.published      = (data.published) ? data.published : "1";
    this.lstCoordenadas = new Array();
    this.buildLstCoordenada(data.lstCoordenadas);

}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
Grafico.prototype.addCoordenada = function(data) {
    if (data != null) {
        var oCoordenada = new Coordenada(data);
        oCoordenada.regCoordenada = this.lstCoordenadas.length + 1;
        this.lstCoordenadas.push(oCoordenada);
    }
};
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
Grafico.prototype.buildLstCoordenada = function(data) {
    if (data != null) {
        for (var j = 0; j < data.length; j++) {
            this.addCoordenada(data[j]);
        }
    }
};

/**
 * 
 * HASTA AQUI LA CLASE.
 * 
 */

var regGrafico = 0;
var oGrafico = null;
var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

jQuery(document).ready(function() {
    
    if ( contratos.lstGraficos.length > 0 ) {
        reloadGraficosTable();
        oGrafico = null;
    }
    
    /**
     * 
     */
    jQuery("#jform_intId_tg").change( function() {
        if ( jQuery(this).val() == 0 ){
            jQuery("#addCoordenadasFormGrafico").attr("disabled", "disabled");
        } else {
            jQuery("#addCoordenadasFormGrafico").removeAttr("disabled", "");
        }
    });

    /**
     *  Habilita el formulario para ingresar un grafico
     */
    jQuery("#addGraficoTable").click(function() {
        if ( oGrafico != null ) {
            var msg = '<p style="text-align: center;">';
            msg += JSL_SMS_EDIT_REG + '<br>';
            msg += JSL_SMS_EDIT_QUESTION;
            msg += '</p>';
            jConfirm( msg, SIITA_ECORAE, function(r) {
                if (r) {
                    jQuery(".saveGrafico").trigger("click");
                    availableFrmGrafico();
                } else {
                    jQuery(".cancelGrafico").trigger("click");
                    availableFrmGrafico();
                }
            });
        } else {
            availableFrmGrafico();
        }
        
    });
    
    /**
     *  Crea un objeto grafico y habilita el formulario para agragar las coordenadas
     */
    jQuery('#addCoordenadasFormGrafico').click(function() {
        if ( oGrafico == null) {
            var data = new Array();
            data["idTipoGrafico"]   = jQuery("#jform_intId_tg :selected").val();
            data["nmbTipoGrafico"]  = jQuery("#jform_intId_tg :selected").text();
            data["descripcion"]     = jQuery("#jform_strDescripcionGrafico_crtg").val();
            if ( validarFrmGrf(data) ) {
                data["idGrafico"] = 0;
                data["published"] = 1;
                oGrafico = new Grafico(data);
                oGrafico.regGrafico = contratos.lstGraficos.length + 1;
                showFormCoorTpoGrf( oGrafico.idTipoGrafico );
            } else {
                jAlert( JSL_ALERT_ALL_NEED, SIITA_ECORAE );
                return;
            }
        } else {
            showFormCoorTpoGrf( oGrafico.idTipoGrafico );
        }
    });
    
    /**
     *  Cancela el registro de un grafico
     */
    jQuery("#btnCancelarLstCoor").click( function() {
        clCmpGrafico();
        resetFormsGrafico();
    });

    /**
     *  Registra un grafico con sus coordenadas
     */
    jQuery(".saveGrafico").click( function() {
        var validateGrf = validarGrafico();
        if ( validateGrf.flag == true ){
            if ( regGrafico == 0 ){
                contratos.lstGraficos.push(oGrafico);
            } else {
                jQuery('#eg-' + regGrafico).html (JSL_EDIT );
                oGrafico.descripcion = jQuery("#jform_strDescripcionGrafico_crtg").val();
                for ( var i = 0; i < contratos.lstGraficos.length; i++) {
                    if ( contratos.lstGraficos[i].regGrafico == regGrafico) {
                        contratos.lstGraficos[i] = oGrafico;
                    }
                }
            }
            resetGrafico();
            reloadGraficosTable();
        } else {
            jAlert( validateGrf.sms, SIITA_ECORAE );
        }
    });
    
    /**
     * 
     */
    jQuery(".cancelGrafico").click(function() {
        resetGrafico();
    });

    /**
     * 
     */
    jQuery('.editGrafico').live("click", function(event) {
        var idReg = this.parentNode.parentNode.id;
        if (regGrafico == 0) {
            //  recupero la data del array
            cargaFormGrafico(idReg);
        } else if ( idReg != regGrafico ){
            var msg = '<p style="text-align: center;">';
            msg += JSL_SMS_EDIT_REG + '<br>';
            msg += JSL_SMS_EDIT_QUESTION;
            msg += '</p>';
            jConfirm( msg, SIITA_ECORAE, function(r) {
                if (r) {
                    jQuery(".saveGrafico").trigger("click");
                    cargaFormGrafico(idReg);
                } else {
                    jQuery('#eg-' + regGrafico).html (JSL_EDIT );
                    cargaFormGrafico(idReg);
                }
            });
        }
    });
    
});

/**
 *  Reinicia la visivilidad de los formularios
 * @returns {undefined}
 */
function resetFormsGrafico() {
    jQuery('#coordendasGrafico').css("display", "block");
    jQuery('#circuloGrafico').css("display", "none");
    jQuery('#contentCorGrf').css("display", "none");
    
    jQuery('#editGraficoContent').css("display", "none");
    jQuery('#imgGraficoForm').css("display", "block");
    
    if ( oGrafico.idTipoGrafico == 1 ) {
        availableCoorGrf( 1 );
    } else if( oGrafico.idTipoGrafico == 4 ) {
        availableFrmCoorCir( 1 );
    }
}

/**
 *  Retorna True cuando la data de tipo de grafico esta correcta si no retorna False
 * @param {type} data
 * @returns {Boolean}
 */
function validarFrmGrf(data) {
    var result = false;
    if ( data["idTipoGrafico"] != 0 &&
            data["descripcion"] != "" ) {
        result = true;
    } 
    return result;
}

function validarGrafico(){
    var result = {"flag":true};
    if ( jQuery("#jform_strDescripcionGrafico_crtg").val() == '' ){
        result.flag = false;
        result.sms = JSL_ALERT_ALL_NEED;
    } else if( !validarCoorGrf() ){
        result.flag = false;
        result.sms = JSL_ALERT_COORDENADA_NEED;
    }
    return result;
}

function validarCoorGrf(){
    var res = false;
    if ( oGrafico.lstCoordenadas.length > 0) {
        for (var i = 0; i < oGrafico.lstCoordenadas.length; i++) {
            if (oGrafico.lstCoordenadas[i].published == 1) {
                res = true;
            }
        }
    } 
    return res;
}

/**
 * 
 * @param {type} tpoGrf
 * @returns {undefined}
 */
function showFormCoorTpoGrf( tpoGrf ) {
    if ( parseInt(tpoGrf) != 4 ) {
        jQuery('#circuloGrafico').css("display", "none");
        jQuery('#coordendasGrafico').css("display", "block");
    } else {
        jQuery('#circuloGrafico').css("display", "block");
        jQuery('#coordendasGrafico').css("display", "none");
    }
    //  Desavilita el boton de agregar coordenadas y el combo de tipos de grafico
    jQuery("#addCoordenadasFormGrafico, #jform_intId_tg").attr("disabled", "disabled");
    jQuery('#contentCorGrf').css("display", "block");
    jQuery('#sumitDtaGeneralUG').css("display", "none ");
    
    if ( regGrafico != 0 ) {
        if ( parseInt(tpoGrf) != 4 ) {
            reloadCoordenadasGraficosTable();
        } else {
            addCirculoGraficoTable();
            clCmpCirculoaGrafico();
        }
    }
}

/**
 *  
 * @description limpia los campos de un atributo
 * @returns {undefined}
 */
function clCmpGrafico() {
    resetValidateForm( "#formGraficoCnt" );
    recorrerCombo(jQuery("#jform_intId_tg option"), 0);
    jQuery("#jform_intId_tg").trigger("change");
    jQuery("#jform_strDescripcionGrafico_crtg").val("");
    jQuery("#jform_intId_tg").removeAttr("disabled", "");
}

/**
 *  
 * @returns {undefined}
 */
function resetGrafico(){
    resetFormsGrafico();
    clCmpGrafico();
    if ( regGrafico != 0 ) {
        jQuery('#eg-' + regGrafico).html (JSL_EDIT );
    }
    jQuery("#sumitDtaGeneralUG").css("display", "block");
    reloadCoordenadasTables();
    regGrafico = 0;
    regCoordenada = 0;
    oGrafico = null;
}

/**
 * 
 * @returns {undefined}
 */
function availableFrmGrafico() {
    jQuery('#editGraficoContent').css("display", "block");
    jQuery('#imgGraficoForm').css("display", "none");
    jQuery("#jform_intId_tg").trigger("change");
}

/**
 * 
 * @returns {undefined}
 */
function reloadGraficosTable() {
    jQuery("#tbLtsGraficos > tbody").empty();
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].published == 1)
            addGrafico(contratos.lstGraficos[j]);
    }
}
/**
 * @description Agrega una fila a la tabla de atributos
 * @param {array} data
 * @returns {undefined}
 */
function addGrafico(data) {
    if (data.published == 1) {
        var row = '';
        row += '<tr id="' + data.regGrafico + '">';
        row += ' <td>' + data.descripcion + ' </td>';
        row += ' <td>' + data.nmbTipoGrafico + ' </td>';
        row += ' <td style="width: 15px"><a id="g-' + data.regGrafico + '" class="showGrafico">Ver</a></td>';
        
        if( roles["core.create"] === true || roles["core.edit"] === true ){
            row += ' <td style="width: 15px"><a id="eg-' + data.regGrafico + '" class="editGrafico" >Editar</a></td>';
            row += ' <td style="width: 15px"><a class="delGrafico" >Eliminar</a></td>';
        }else{
            row += ' <td style="width: 15px">Editar</td>';
            row += ' <td style="width: 15px">Eliminar</td>';
        }
        
        row += '</tr>';
        jQuery('#tbLtsGraficos > tbody:last').append(row);
    }
}

/**
 * 
 * @param {type} regGrf
 * @returns {undefined}
 */
function cargaFormGrafico( regGrf ) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGrf) {
            oGrafico = contratos.lstGraficos[j];
        }
    }
    
    jQuery('#eg-' + regGrf).html(JSL_EDITANDO);
    regGrafico = regGrf;
    //  muestro en el formulario
    recorrerCombo(jQuery("#jform_intId_tg option"), oGrafico.idTipoGrafico);
    jQuery("#jform_strDescripcionGrafico_crtg").val(oGrafico.descripcion);

    jQuery('#editGraficoContent').css("display", "block");
    jQuery('#imgGraficoForm').css("display", "none");
    jQuery("#addCoordenadasFormGrafico").trigger('click');
}










    jQuery('.delGrafico').live("click", function() {
        var regGraficoDel = this.parentNode.parentNode.id;
        jConfirm("¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function(r) {
            if (r) {
                elmGrafico(regGraficoDel);
                reloadGraficosTable();
            } 
        });
    });

/**
 * 
 * @param {type} regGraficoDel
 * @returns {undefined}
 */
function elmGrafico(regGraficoDel) {
    for (var j = 0; j < contratos.lstGraficos.length; j++) {
        if (contratos.lstGraficos[j].regGrafico == regGraficoDel) {
            contratos.lstGraficos[j].published = 0;
        }
    }
}

