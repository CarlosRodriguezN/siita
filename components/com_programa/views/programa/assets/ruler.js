jQuery(document).ready(function() {
    jQuery('#tabs').tabs();
    jQuery('#tabsIndicadores').tabs();


    jQuery("#accordion").accordion({header: "> div > h3"}).sortable({axis: "y", handle: "h3",
        stop: function(event, ui) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children("h3").triggerHandler("focusout");
        }
    });


    //  Ajuste de tamaño
    jQuery('#jform_cbGpoAtencionPrioritario').css('width', '250px');
    jQuery('#jform_cbEnfoqueIgualdad').css('width', '230px');
    jQuery('#jform_idEnfoqueIgualdad').css('width', '230px');
    jQuery('#jform_intIdUndGestion').css('width', '400px');
    jQuery('#jform_intIdUGResponsable').css('width', '400px');
    jQuery('#jform_idResponsable').css('width', '400px');
    jQuery('#jform_strDescripcion_prg').css('width', '244px');


    /************* TAB GENERAL ***************/
    //  solo CARACTERES campo NOMBRE PROGRAMA 
    jQuery('#jform_strNombre_prg').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32) && !(tecla > 64 && tecla < 91) || tecla > 122) {
            return false;
        }
    });

    //  solo CARACTERES campo DESCRIPCION 
    jQuery('#jform_strdescripcion_prg').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32) && !(tecla > 64 && tecla < 91) || tecla > 122) {
            return false;
        }
    });


    jQuery("#programa-form").validate({
        rules: {
            jform_strNombre_prg: {required: true, minlength: 2},
            jform_strAlias_prg: {required: true, minlength: 2},
            jform_idEstadoEntidad: {requiredlist: true},
            jform_strDescripcion_ob: {required: true, minlength: 2},
            jform_strCodigoSubPrograma: {required: true},
            jform_strDescripcion: {required: true, minlength: 2},
            jform_strAlias: {required: true, maxlength: 10},
            cb_intId_SubPrograma: {requiredlist: true},
            jform_strCodigoTipoSubPrograma: {required: true},
            jform_strDescripcionTipoPrograma: {required: true, minlength: 2},
            jform_intIdUndGestion: {requiredlist: true},
            jform_idResponsable: {requiredlist: true}

        },
        messages: {
            jform_strNombre_prg: {required: "Nombre requerido",
                minlength: "Ingrese almenos 2 caracteres en nombre"},
            jform_strAlias_prg: {required: "Alias requerido",
                minlength: "Ingrese almenos 2 caracteres en alias"},
            jform_idEstadoEntidad: {requiredlist: "Estado requerido"},
            jform_strDescripcion_ob: {required: "Descripción requerida",
                minlength: "Ingrese almenos 2 caracteres en descripción"},
            jform_strCodigoSubPrograma: {
                required: "Codigo requerido"
            },
            jform_strDescripcion: {
                required: "Descripción requerida",
                minlength: "Ingrese almenos 2 caracteres en descripción"
            },
            jform_strAlias: {
                required: "Alias requerida",
                maxlength: "Maximo 10 caracteres en descripción"
            },
            cb_intId_SubPrograma: {
                requiredlist: "Sub Programa requerido"
            },
            jform_strCodigoTipoSubPrograma: {
                required: "Codigo requerido"
            },
            jform_strDescripcionTipoPrograma: {
                required: "Descripción requerida",
                minlength: "Ingrese almenos 2 caracteres en descripción"
            },
            jform_intIdUndGestion: {
                requiredlist: "Unidad de Gestión requerida"
            },
            jform_idResponsable: {
                requiredlist: "Responsable requerido"
            }
        },
        submitHandler: function() {
            return false;
        },
        errorElement: "span"
    });

    /************* TAB SUB PROGRAMA ***************/
    //  solo CARACTERES campo CODIGO SUB PROGRAMA
    jQuery('#jform_strCodigoSubPrograma').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });

    //  solo CARACTERES campo DESCRIPCION
    jQuery('#jform_strDescripcion').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32) && !(tecla > 64 && tecla < 91) || tecla > 122) {
            return false;
        }
    });

    //  solo CARACTERES campo ALIAS
    jQuery('#jform_strAlias').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32) && !(tecla > 64 && tecla < 91) || tecla > 122) {
            return false;
        }
    });

    /************* TAB TIPOS SUB PROGRAMA ***************/
    //  solo CARACTERES campo CODIGO TIPO SUB PROGRAMA
    jQuery('#jform_strCodigoTipoSubPrograma').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });
    //  solo CARACTERES campo DESCRIPCION  TIPO SUB PROGRAMA
    jQuery('#jform_strDescripcionTipoPrograma').keypress(function(e) {
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32) && !(tecla > 64 && tecla < 91) || tecla > 122) {
            return false;
        }
    });


    // VALIDACION CAMPOS INDICADORES   

    /**
     * Controlo el ingreso de caracteres numéricos en los campos Económicos y financieros
     */
    jQuery('#jform_intTasaDctoEco, #jform_intTIREco, #jform_intTasaDctoFin, #jform_intTIRFin').keypress(function(e) {
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    jQuery('#jform_intValActualNetoEco,#jform_intValActualNetoFin').keypress(function(e) {
        var tecla = e.which;
        if (tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    /**
     * Controlo el ingreso de caracteres numéricos en los campos Beneficiarios
     */
    jQuery('#jform_intBenfDirectoHombre, #jform_intBenfDirectoMujer, #jform_intTotalBenfDirectos, #jform_intBenfIndDirectoHombre, #jform_intBenfIndDirectoMujer, #jform_intTotalBenfIndDirectos').keypress(function(e) {
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    /**
     * Controlo el ingreso de caracteres numéricos en los campos GAP
     */
    jQuery('#jform_intGAPMasculino, #jform_intGAPFemenino,#jform_intGAPTotal').keypress(function(e) {
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    /**
     * Controlo el ingreso de caracteres numéricos en los campos de Enfoque de Igualdad
     */
    jQuery('#jform_intEIMasculino, #jform_intEIFemenino, #jform_intEITotal').keypress(function(e) {
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    /**
     * Controlo el ingreso de caracteres numéricos en los campos de Enfoque de Ecorae
     */
    jQuery('#jform_intEnfEcoMasculino, #jform_intEnfEcoFemenino, #jform_intEnfEcoTotal').keypress(function(e) {
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58)) {
            return false;
        }
    });


    // VALIDACION CAMPOS INDICADORES FINANCIEROS Y ECONÓMICOS
    jQuery('#jform_intValActualNetoEco').css('width', '100px');
    jQuery('#jform_intValActualNetoFin').css('width', '100px');
    jQuery('#jform_intValActualNetoEco').attr('value', formatNumber(jQuery('#jform_intValActualNetoEco').val(), '$'));
    jQuery('#jform_intValActualNetoFin').attr('value', formatNumber(jQuery('#jform_intValActualNetoFin').val(), '$'));

    //  Gestiono la actualizacion de un nuevo valor en funcion al cambio de valor
    jQuery('#jform_intValActualNetoEco, #jform_intValActualNetoFin').blur(function() {
        var val = unformatNumber(jQuery(this).val());
        var valor = parseFloat(val);
        jQuery(this).attr('value', formatNumber(valor.toFixed(2), '$'));
    })

    // Readonly de los campos fecha
    jQuery('#jform_fchInicioPeriodoUG').attr('readonly', 'readonly');
    jQuery('#jform_fchInicioPeriodoFuncionario').attr('readonly', 'readonly');
});

// Recorro el combo de provincias a una determinada posicion
function recorrerCombo(combo, posicion)
{
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}