jQuery( document ).ready( function(){
    
    //  Pestañas del formulario
    jQuery( '#tabsAttrIndicador' ).tabs();
    
    //
    //  DATOS GENERALES
    //
    jQuery( '#jform_nombreIndicador-lbl, #jform_descripcionIndicador-lbl, #jform_nombreReporte-lbl' ).css( 'min-width', '110px' );
    jQuery( '#jform_intIdUndGestion, #jform_intIdUGResponsable, #jform_idResponsable' ).css( 'width', '250px' );
    jQuery( '#jform_descripcionIndicador, #jform_nombreReporte, #jform_nombreIndicador' ).css( 'width', '325px' );
    jQuery( '#jform_nombreIndicador, #jform_descripcionIndicador' ).attr( 'readonly', 'readonly' );
    
    //
    //  FORMULA
    //
    
    jQuery( '#jform_factorPonderacion-lbl, #jform_idMetodoCalculo-lbl, #jform_idTpoEntidad-lbl, #jform_idEntidad-lbl, #jform_idIndicador-lbl, #jform_UGResponsable-lbl, #jform_ResponsableUG-lbl, #jform_funcionario-lbl' ).css( 'min-width', '135px' );
    
    jQuery( '#jform_idMetodoCalculo, #jform_idTpoEntidad, #jform_idEntidad, #jform_idIndicador' ).css( 'width', '227px' );
    jQuery( '#jform_factorPonderacion' ).css( 'width', '35px' );
    jQuery( '#jform_UGResponsable, #jform_ResponsableUG, #jform_funcionario' ).css( 'width', '225px' );
    
    //  Ajusto a 25px, el tamaño de los botones de operacion de la formula
    jQuery( '#btnFrmSuma, #btnFrmResta, #btnFrmMultiplicacion, #btnFrmDivision' ).css( 'width', '25px' );

    //  Cambio el tamaño del text area del formulario de Gestion de Formulas a 380px
    jQuery( '#formulaDescripcion' ).css( 'width', '415px' );
    
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