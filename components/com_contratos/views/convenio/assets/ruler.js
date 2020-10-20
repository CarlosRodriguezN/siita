/*sirve para controlar algunos tamaños de los elementos.
 */

jQuery(document).ready(function() {
    
    jQuery("#accordion").accordion({ header: "> div > h3" }).sortable({ axis: "y", handle: "h3",
        stop: function(event, ui) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children("h3").triggerHandler("focusout");
        }
    });

    {// gestion de tabs
        jQuery("#contratosTab").tabs();
        jQuery("#contratistaTab").tabs();
        jQuery("#garantiasTab").tabs();
        jQuery("#facturasTab").tabs();
        jQuery("#ubicacionTab").tabs();
        jQuery("#tabsIndicadores").tabs();
    }// gestion de tabs

    {// Tamaño de los combos

        // datos generales
        jQuery("#jform_intCodigo_pry").css({width: "280px"});
        jQuery("#jform_intIdSubrograma").css({width: "280px"});
        jQuery("#jform_intidPrograma").css({width: "280px"});
        jQuery('#jform_cbEnfoqueIgualdad').css( 'width', '230px' );
        jQuery('#jform_idEnfoqueIgualdad').css( 'width', '230px' );
        jQuery('#jform_intIdUGResponsable').css( 'width', '230px' );
        jQuery('#jform_idResponsable').css( 'width', '230px' );
        jQuery('#jform_intIdUndGestion').css( 'width', '230px' );
        
          
        // VALIDACION CAMPOS INDICADORES FINANCIEROS Y ECONÓMICOS
   
        
        
        //Readonly de los campos fecha
        jQuery('#jform_dteFechaInicio_ctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFin_ctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaDesde_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaHasta_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaInicio_cctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFin_cctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFiscFechaDesde_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFiscFechaHasta_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFactura_fac' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFactura_fac_adl' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaPago_pgo' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_fchInicioPeriodoUG' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_fchInicioPeriodoFuncionario' ).attr( 'readonly', 'readonly' );
        
        
        //CAMPOS INDICADORES

        //Controlo el ingreso de caracteres numéricos en los campos indicadores Económicos y Financieros
        jQuery( '#jform_intTasaDctoEco, #jform_intTIREco, #jform_intTasaDctoFin, #jform_intTIRFin' ).keypress( function( e ){
            var tecla = e.which;
            if (!(tecla > 47 && tecla < 58 )){
                return false;
            }
        }); 
        
        jQuery( '#jform_intValActualNetoEco,#jform_intValActualNetoFin' ).keypress( function( e ){
            var tecla = e.which;
            if (tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
                return false;
            }
        }); 
        
        /**
          * Controlo el ingreso de caracteres numéricos en los campos Beneficiarios
         */
        jQuery( '#jform_intBenfDirectoHombre, #jform_intBenfDirectoMujer, #jform_intTotalBenfDirectos, #jform_intBenfIndDirectoHombre, #jform_intBenfIndDirectoMujer, #jform_intTotalBenfIndDirectos' ).keypress( function( e ){
            var tecla = e.which;
            if (!(tecla > 47 && tecla < 58 )){
                return false;
            }
        }); 
    
        /**
          * Controlo el ingreso de caracteres numéricos en los campos GAP
         */
        jQuery( '#jform_intGAPMasculino, #jform_intGAPFemenino,#jform_intGAPTotal' ).keypress( function( e ){
            var tecla = e.which;
            if (!(tecla > 47 && tecla < 58 )){
                return false;
            }
        });
        
        /**
         * Controlo el ingreso de caracteres numéricos en los campos de Enfoque de Igualdad
         */
        jQuery( '#jform_intEIMasculino, #jform_intEIFemenino, #jform_intEITotal' ).keypress( function( e ){
            var tecla = e.which;
            if ( !(tecla > 47 && tecla < 58 )){
                return false;
            }
        });

            /**
         * Controlo el ingreso de caracteres numéricos en los campos de Enfoque de Ecorae
         */
        jQuery( '#jform_intEnfEcoMasculino, #jform_intEnfEcoFemenino, #jform_intEnfEcoTotal' ).keypress( function( e ){
            var tecla = e.which;
            if ( !(tecla > 47 && tecla < 58 )){
                return false;
            }
        });

        // VALIDACION CAMPOS INDICADORES FINANCIEROS Y ECONÓMICOS
        jQuery( '#jform_intValActualNetoEco' ).css( 'width', '100px' );
        jQuery( '#jform_intValActualNetoFin' ).css( 'width', '100px' );  
        jQuery( '#jform_intValActualNetoEco' ).attr( 'value', formatNumber( jQuery( '#jform_intValActualNetoEco' ).val(), '$' ) );
        jQuery( '#jform_intValActualNetoFin' ).attr( 'value', formatNumber( jQuery( '#jform_intValActualNetoFin' ).val(), '$' ) );
        
        //  Gestiono la actualizacion de un nuevo valor en funcion al cambio de valor
        jQuery( '#jform_intValActualNetoEco, #jform_intValActualNetoFin' ).blur( function(){
            var val = unformatNumber( jQuery( this ).val() );
            var valor = parseFloat( val );
            jQuery( this ).attr( 'value', formatNumber( valor.toFixed( 2 ), '$' ) );
        })
    
    
        /*
         *  campo LATITUD 
         */
        jQuery( '#jform_dcmLatitud_coor' ).keypress( function( e ){
            var tecla = e.which;

            if ( tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
                return false;
            }
        })
        /*
         *  campo LOGITUD 
         */
        jQuery( '#jform_dcmLlong_coor' ).keypress( function( e ){
            var tecla = e.which;

            if ( tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
                return false;
            }
        })
    
        //  indicadores/grupode atencion prioritaria
        jQuery("#jform_cbGpoAtencionPrioritario").css({width: "280px"});

    } //  Tamaño de los combos

        //iniicalizar fechas por defecto

        var f = new Date();
        var fecha = f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();
        jQuery("#jform_dteFechaRegistro_cctr").val(fecha);


        {// inicializar los conbos.
            recorrerCombo(jQuery("#jform_intidPrograma option"), parseInt(jQuery("#idPrograma").val()));
            recorrerCombo(jQuery("#jform_strContratistaContacto_cta option"), 1);
            jQuery('#jform_strContratistaContacto_cta').trigger('change', jQuery('#jform_strContratistaContacto_cta').val());
        }// inicializar los conbos.


        
});

/**
 * 
 * @param {type} combo
 * @param {type} posicion
 * @returns {undefined}
 */
function recorrerCombo(combo, posicion)
{
    jQuery(combo).each(function() {
        if (jQuery(this).val() == posicion) {
            jQuery(this).attr('selected', 'selected');
        }
    });
}
