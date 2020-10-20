jQuery( 'document' ).ready(function(){
    
    jQuery( '#jform_intCodigo_ins, #jform_intIdFuncionario_int' ).css( 'width', '450px' );
    jQuery( '#jform_intCodigo_ins-lbl, #jform_intIdFuncionario_int-lbl, #jform_strIPInstitucion_api-lbl, #jform_dteFechaInicio_api-lbl, #jform_dteFechaFin_api-lbl, #jform_strNombres_api-lbl, #jform_strCorreo_api-lbl' ).css( 'min-width', '119px' );
    
    Joomla.submitbutton = function(task)
    {
        Joomla.submitform(task);
    }
    
})