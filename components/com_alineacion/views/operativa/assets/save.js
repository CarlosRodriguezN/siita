jQuery(document).ready(function () {
    Joomla.submitbutton = function (task) {
        switch (task) {
            case 'operativa.asignar':
                asignarAlineacionObjetivo();
                window.parent.SqueezeBox.close();
                break;

            case 'operativa.cancel':
                window.parent.SqueezeBox.close();
                break;
        }
    };

});


function asignarAlineacionObjetivo() {
    var tpoEntidad = parseInt(jQuery('#tpoEntidad').val());
    switch (tpoEntidad) {
        case 1: // PROGRAMA
        case 2: // PROYECTO
        case 3: // CONTRATO & CONVENIO
            asignarAlineacionPrograma();
            break;

        case 7:// unidad Gestion
            asiganarAlineacionesUnidadGestion();
            break;

        case 11:// FUNCIONARIO
            asignarAlineacionFuncionario()
            break;

        case 13:// funcionario
            asignarAlineacionPOAOperativo();
            break;

    }
}


function asignarAlineacionFuncionario() {
    var regObjetivo = parseInt(jQuery("#registroObj").val());
    var registroPoa = parseInt(jQuery("#registroPoa").val());

    if (window.parent.objLstPoas.lstPoas.length > 0) {
        for (var j = 0; j < window.parent.objLstPoas.lstPoas.length; j++) {
            if (window.parent.objLstPoas.lstPoas[j].idRegPoa == registroPoa) {

                if (window.parent.objLstPoas.lstPoas[j].lstObjetivos.length > 0) {
                    for (var k = 0; k < window.parent.objLstPoas.lstPoas[j].lstObjetivos.length; k++) {

                        if (window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].registroObj == regObjetivo) {
                            window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].lstAlineaciones = new Array();
                            for (var l = 0; l < oGestionAlineacion.lstAlineaciones.length; l++) {
                                window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].lstAlineaciones.push(oGestionAlineacion.lstAlineaciones[l]);
                            }
                            window.parent.semaforoAlineacion(regObjetivo, 2, registroPoa);
                        }
                    }
                }
            }
        }
    }
}


function asignarAlineacionPrograma() {
    var regObjetivo = parseInt(jQuery("#registroObj").val());

    if (window.parent.objLstObjetivo.lstObjetivos.length > 0) {
        for (var j = 0; j < window.parent.objLstObjetivo.lstObjetivos.length; j++) {
            if (window.parent.objLstObjetivo.lstObjetivos[j].registroObj == regObjetivo) {
                window.parent.objLstObjetivo.lstObjetivos[j].lstAlineaciones = new Array();
                for (var l = 0; l < oGestionAlineacion.lstAlineaciones.length; l++) {
                    window.parent.objLstObjetivo.lstObjetivos[j].lstAlineaciones.push(oGestionAlineacion.lstAlineaciones[l]);
                }
                window.parent.semaforoAlineacion(regObjetivo);
            }
        }
    }
}

/**
 * ASIGNA la alineacion a una UNIDAD DE GESTION
 * @returns {undefined}
 */
function asiganarAlineacionesUnidadGestion()
{
    var regObjetivo = parseInt( jQuery("#registroObj").val() );
    var registroPoa = parseInt( jQuery("#registroPoa").val() );
    
    for (var j = 0; j < window.parent.objLstPoas.lstPoas.length; j++) {
        if( parseInt( window.parent.objLstPoas.lstPoas[j].idRegPoa ) === registroPoa ) {
            for (var k = 0; k < window.parent.objLstPoas.lstPoas[j].lstObjetivos.length; k++) {
                if ( parseInt( window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].registroObj ) === regObjetivo) {
                    window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].lstAlineaciones = new Array();
                    for (var l = 0; l < oGestionAlineacion.lstAlineaciones.length; l++) {
                        window.parent.objLstPoas.lstPoas[j].lstObjetivos[k].lstAlineaciones.push(oGestionAlineacion.lstAlineaciones[l]);
                    }

                    window.parent.semaforoAlineacion( regObjetivo, 2, registroPoa );
                }
            }
        }
    }

    return;
}



function asignarAlineacionPOAOperativo()
{
    var regObjetivo = parseInt(jQuery("#registroObj").val());
    var registroPoa = parseInt(jQuery("#registroPoa").val());

    if (oGestionAlineacion.lstAlineaciones.length > 0) {
        window.parent.dtaPlanOperativo[registroPoa].planObjetivo.lstAlineaciones = new Array();

        for (var x = 0; x < oGestionAlineacion.lstAlineaciones.length; x++) {
            window.parent.dtaPlanOperativo[registroPoa].planObjetivo.lstAlineaciones.push(oGestionAlineacion.lstAlineaciones[x]);
        }

        if (parseInt(jQuery('#tpoEntidad').val()) != 13) {
            window.parent.semaforoAlineacion(regObjetivo, 2, registroPoa);
        }
    }
}