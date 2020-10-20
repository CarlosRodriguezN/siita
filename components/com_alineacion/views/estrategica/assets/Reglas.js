jQuery( document ).ready( function(){
    //  Pestañas del formulario
    jQuery( '#tabsAlineacion' ).tabs();
    
    jQuery( '#jform_idObjetivo-lbl, #jform_idPolitica-lbl, #jform_idMeta-lbl' ).css( 'min-width', '60px' );
    
    jQuery( '#jform_idAgenda-lbl' ).css( 'min-width', '80px' )
    jQuery( '#jform_idAgenda' ).css( 'width', '340px' )
    
    //  Cambio el tamaño de los selects del formulario indicadores a 160px
    jQuery( '#jform_idObjetivo ,#jform_idPolitica, #jform_idMeta' ).css( 'width', '260px' );
})