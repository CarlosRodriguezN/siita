jQuery(document).ready(function () {

    //  Pestañas General
    jQuery('#tabsAcciones').tabs();

    //
    //  Controlo el ingreso de caracteres alfanumercos en la descripción de una Acción
    //
    jQuery('#jform_strDescripcion_plnAccion').keypress(function (e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });

    //
    //  Controlo el ingreso de caracteres numericos en el campo presupuesto para la acción
    //
    jQuery('#jform_mnPresupuesto_plnAccion').keypress(function (e, b) {
        var tecla = e.which;
        var cifra;
        var valor;
        var monto;

        if (tecla != 46 && !(tecla > 47 && tecla < 58)) {
            return false;
        } else {
            cifra = String.fromCharCode(e.charCode);
            valor = unformatNumber(jQuery('#jform_mnPresupuesto_plnAccion').val());
            monto = (tecla !== 46) ? parseFloat(valor + '' + cifra)
                    : valor + (parseFloat(0 + '.' + cifra));

            jQuery('#jform_mnPresupuesto_plnAccion').attr('value', formatNumber(monto, '$'));

            return false;
        }
    });

    //
    //  Controlo el ingreso de caracteres para el campo observación con aceptacion de ".", "_", "-"
    //  numeros y letras.
    //
    jQuery('#jform_strObservacion_plnAccion').keypress(function (e) {
        var tecla = e.which;

        if ((tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !(tecla > 64 && tecla < 91) && !(tecla > 47 && tecla < 58) || tecla > 122) {
            return false;
        }
    });

    //
    //  Controlo el ingreso de caracteres numericos en el campo fecha de ejecución
    //
    jQuery('#jform_dteFechaEjecucion_planAccion').keypress(function (e) {
        var tecla = e.which;
        if (tecla != 45 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    //
    //  Bolque el ingreso de fechas
    //
    jQuery('#jform_dteFechaInicio_planAccion, #jform_dteFechaFin_planAccion, #jform_dteFechaInicio_plnFR, #jform_dteFechaInicio_plnUGR').attr('readonly', 'readonly');

    //
    //  Especifica el tamaño de los select
    //
    jQuery("#jform_intTpoActividad_plnAccion").css({width: "160px"});
    jQuery("#jform_strDescripcion_plnAccion").css({width: "154px"});
    jQuery("#jform_unidad_gestion").css({width: "160px"});
    jQuery("#jform_intId_ugf").css({width: "160px"});
    jQuery("#jform_intCodigo_ug").css({width: "160px"});
    jQuery("#jform_mnPresupuesto_plnAccion").css({width: "158px"});
    jQuery("#jform_strObservacion_plnAccion").css({width: "154px"});

    jQuery("#jform_intCodigo_ug-lbl, #jform_dteFechaInicio_plnUGR-lbl, #jform_dteFechaInicio_plnFR-lbl, #jform_unidad_gestion-lbl, #jform_intId_ugf-lbl").css( 'min-width', '120px' );
    jQuery("#jform_unidad_gestion, #jform_intId_ugf, #jform_intCodigo_ug").css( 'width', '225px' );
    
    jQuery("#jform_intTpoActividad_plnAccion-lbl, #jform_strDescripcion_plnAccion-lbl, #jform_dteFechaInicio_planAccion-lbl, #jform_dteFechaFin_planAccion-lbl, #jform_mnPresupuesto_plnAccion-lbl, #jform_strObservacion_plnAccion-lbl").css( 'min-width', '120px' );    
    jQuery("#jform_intTpoActividad_plnAccion").css( 'width', '225px' );
    jQuery("#jform_strDescripcion_plnAccion, #jform_strObservacion_plnAccion").css( 'width', '219px' );

});