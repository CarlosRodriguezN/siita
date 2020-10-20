jQuery( document ).ready( function(){
    //  Oculto Barra de Herramientas de Gestion de Indicadores
    jQuery("#tbFrmIndicador").css("display", "none");

    //  Oculto Formulario de registro de indicadores
    jQuery("#frmIndicador").css("display", "none");
    
    //  Oculto el texto de otra variable
    jQuery('#otraVariable').css( 'display', 'none' );
    
    //  Oculto el texto de otra variable
    jQuery('#jform_intIdUndGestion-lbl, #jform_fchInicioPeriodoUG-lbl, #jform_intIdUGResponsable-lbl, #jform_idResponsable-lbl, #jform_fchInicioPeriodoFuncionario-lbl').css( 'min-width', '118px' );

    //  
    jQuery('#jform_idUndMedida-lbl').css( 'min-width', '166px' );
    
    //
    jQuery('#jform_intIdUndGestion, #jform_intIdUGResponsable, #jform_idResponsable').css( 'width', '300px' );
    
    //  Desavilita los input de fechas
    jQuery( '#jform_fchInicioPeriodoUG, #jform_fchInicioPeriodoFuncionario, #jform_fchPlanificacion, #jform_fchSeguimiento' ).attr( 'readonly', 'readonly' );
});