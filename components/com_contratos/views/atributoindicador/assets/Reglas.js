jQuery( document ).ready( function(){
    //  Pestañas
    jQuery( '#tabsAttrIndicador' ).tabs();
    
    //  Ajusto el tamaño del comboBox de programas
    jQuery('#jform_intIdVariable').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de programas
    jQuery('#jform_idUndMedida').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de programas
    jQuery('#jform_intIdFrcMedicion').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Unidad de Analisis
    jQuery('#jform_intIdUndAnalisis').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Unidad de Analisis
    jQuery('#jform_intIdUndAnalisis').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Unidad de Gestion
    jQuery('#jform_intIdUndGestion').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Unidad de Gestion
    jQuery('#jform_intIdUGResponsable').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Unidad de Gestion
    jQuery('#jform_idResponsable').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Fuente
    jQuery('#jform_idFuente').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Linea Base
    jQuery('#jform_idLineaBase').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Linea Base
    jQuery('#jform_idLineaBase').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idProvincia').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idCanton').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idParroquia').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idVarTpoUndMedida').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idLineaBase').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idVarUndMedida').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idVariable').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idUndAnalisisVar').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Provincias
    jQuery('#jform_idDimension').css( 'width', '203px' );
    
    //  Ajusto el tamaño del comboBox de Unidad de Analisis - Nueva Variable
    jQuery('#jform_idUndAnalisisNV, #jform_idTpoUndMedidaNV, #jform_idVarUndMedidaNV').css( 'width', '203px' );
    
    //  Ajusto tamaño de area de texto de descripcion
    jQuery('#jform_descripcionNV, #jform_descripcionIndicador').css( 'width', '244px' );
    
    jQuery('#jform_descripcionIndicador, #jform_strFormulaIndicador').css( 'width', '214px' );
    
    
    
    //  Oculto el texto de otra variable
    jQuery('#otraVariable').css( 'display', 'none' );
    
    
    
    /*
     *  
     *  Solo Caracteres AlfaNumericos
     *  
     */
    jQuery( '#jform_nombreIndicador, #jform_descripcionIndicador, #jform_strFormulaIndicador, #jform_nombreVar, #jform_descripcionOV' ).keypress( function( e ){
        var tecla = e.which;

        if ((tecla < 96 && tecla != 0 && tecla != 8 && tecla != 32) && !(tecla > 64 && tecla < 91) || tecla > 122) {
            return false;
        }
    })
    
    /**
     * Controlo el ingreso de caracteres numericos.
     */
    jQuery( '#jform_umbralIndicador, #jform_valMinimo, #jform_valMaximo, #jform_rgValMinimo, #jform_rgValMaximo' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    
    jQuery( '#jform_hzFchInicio, #jform_hzFchFin, #jform_valorLineaBase' ).attr( 'readonly', 'readonly' );
    
})