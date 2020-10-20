jQuery(document).ready(function() {
    
    //  PESTAÑAS
    jQuery('#tabsAttrIndicador').tabs();

    //
    //  PESTAÑA DATOS GENERALES
    //
    jQuery('#jform_idTpoIndicador, #jform_idClaseIndicador, #jform_intIdUndAnalisis, #jform_intIdTpoUndMedida, #jform_idUndMedida, #jform_IdHorizonte, #jform_idFrcMonitoreo, #jform_idGpoDimension, #jform_idGpoDecisiones, #jform_nombreIndicador').css('width', '250px');
    jQuery('#jform_intIdTpoUndMedida-lbl, #jform_idTpoIndicador-lbl, #jform_nombreIndicador-lbl, #jform_umbralIndicador-lbl, #jform_descripcionIndicador-lbl, #jform_idClaseIndicador-lbl, #jform_intIdUndAnalisis-lbl, #jform_intIdTpoUndMedida-lbl, #jform_idUndMedida-lbl').css('width', '166px');

    //
    //  PESTAÑA RESPONSABLE
    //
    jQuery('#jform_intIdUndGestion, #jform_intIdUGResponsable, #jform_idResponsable').css('width', '250px');

    //
    //  PESTAÑA LINEAS BASE
    //
    jQuery('#jform_idFuente, #jform_idLineaBase').css('width', '250px');
    jQuery('#jform_idFuente-lbl, #jform_idLineaBase-lbl, #jform_valorLineaBase-lbl').css('min-width', '140px');

    //  Ajusto el tamaño de los input para mantenimiento de linea base
    jQuery('#jform_idFuenteNew').css('width', '200px');
    jQuery('#jform_idLineaBaseNew').css('width', '200px');

    //
    //  PESTAÑA UNIDAD TERRITORIAL
    //
    jQuery('#jform_idProvincia, #jform_idCanton, #jform_idParroquia').css('width', '250px');

    //
    //  PESTAÑA FORMULA
    //
    jQuery('#jform_intIdVariable').css('width', '200px');
    jQuery('#jform_intIdFrcMedicion').css('width', '200px');
    jQuery('#jform_idTpoEntidad').css('width', '200px');
    jQuery('#jform_idEntidad').css('width', '200px');
    jQuery('#jform_idIndTpoUndMedida').css('width', '200px');
    jQuery('#jform_idIndUndMedida').css('width', '200px');
    jQuery('#jform_idIndicador').css('width', '200px');
    jQuery('#jform_idVariable').css('width', '200px');
    jQuery('#jform_idUndAnalisisVar').css('width', '200px');
    jQuery('#jform_nombreNV').css('width', '223px');

    jQuery('#jform_idUGResponsableVar').css('width', '225px');
    jQuery('#jform_idUGFuncionarioVar').css('width', '225px');
    jQuery('#jform_idFunResponsableVar').css('width', '225px');

    jQuery('#jform_UGResponsable').css('width', '200px');
    jQuery('#jform_ResponsableUG').css('width', '200px');
    jQuery('#jform_funcionario').css('width', '200px');

    //  Oculto Formulario de Responsable por Indicador
    jQuery('#frmIndicadorVar').css('display', 'none');
    jQuery('#responsablesIndicador').css('display', 'none');

    //  Ajusto el tamaño del comboBox de Unidad de Analisis - Nueva Variable
    jQuery('#jform_idUndAnalisisNV, #jform_idTpoUndMedidaNV, #jform_idVarUndMedidaNV').css('width', '225px');
    jQuery('#jform_nombreNV-lbl, #jform_aliasNV-lbl, #jform_descripcionNV-lbl, #jform_idUndAnalisisNV-lbl, #jform_idTpoUndMedidaNV-lbl, #jform_idVarUndMedidaNV-lbl, #jform_idUGResponsableVar-lbl, #jform_idUGFuncionarioVar-lbl, #jform_idFunResponsableVar-lbl').css('min-width', '135px');

    //  Ajusto tamaño de area de texto de descripcion
    jQuery('#jform_descripcionNV').css('width', '219px');
    jQuery('#jform_descripcionIndicador').css('width', '244px');
    jQuery('#jform_strFormulaIndicador').css('width', '214px');

    //  Muestro Barra de Herramientas de Lista de Indicadores
    jQuery("#tbIndicadoresObjetivo").css("display", "block");

    //  Muestro Lista de Indicadores asociados a un objetivo
    jQuery("#lstIndicadoresObjetivo").css("display", "block");

    //  Ajusto a 25px, el tamaño de los botones de operacion de la formula
    jQuery('#btnFrmSuma, #btnFrmResta, #btnFrmMultiplicacion, #btnFrmDivision').css('width', '25px');

    //  Cambio el tamaño del text area del formulario de Gestion de Formulas a 415px
    jQuery('#formulaDescripcion').css('width', '415px');

    //
    //  PESTAÑA DIMENSION
    //
    jQuery('#jform_idEnfoque, #jform_idDimension').css('width', '250px');

    //
    //  PESTAÑA PLANIFICACION
    //

    //
    //  PESTAÑA SEGUIMIENTO
    //
    jQuery('#jform_idVariableIndicador').css('width', '250px');

    /*
     *  
     *  Solo Caracteres AlfaNumericos
     *  
     */
    jQuery('#jform_strFormulaIndicador, #jform_nombreVar, #jform_metodologia, #jform_limitacion, #jform_interpretacion').keypress(function(e) {
        var tecla = e.which;

        if ( (  tecla < 96 && tecla !== 0 
                && tecla !== 8 
                && tecla !== 32 ) 
                && !( tecla > 64 && tecla < 91 ) || tecla > 122 ) {
            return false;
        }
    })

    /**
     * Controlo el ingreso de caracteres numericos.
     */
    jQuery('#jform_umbralIndicador, #jform_valMinimo, #jform_valMaximo, #jform_rgValMinimo, #jform_rgValMaximo').keypress(function(e) {
        var tecla = e.which;
        if ( tecla !== 46 && !( tecla > 47 && tecla < 58 ) ) {
            return false;
        }
    });

    /**
     * Controlo el ingreso de caracteres numericos.
     */
    jQuery('#jform_valorSeguimiento').keypress(function(e) {
        var tecla = e.which;
        if ( tecla !== 46 && tecla !== 8 && !(tecla > 47 && tecla < 58)) {
            return false;
        }
    });

    jQuery('#jform_hzFchInicio, #jform_hzFchFin, #jform_valorLineaBase').attr('readonly', 'readonly');

})