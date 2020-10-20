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
        jQuery("#jform_strURLtableU").css({width: "278px"});
        jQuery("#jform_intCodigo_pry").css({width: "280px"});
        jQuery("#jform_intIdSubrograma").css({width: "280px"});
        jQuery("#jform_intidPrograma").css({width: "280px"}); 
        jQuery("#jform_intIdPartida_pda").css({width: "280px"}); 
        jQuery("#jform_intIdFiscalizador_fc").css({width: "280px"}); 
        jQuery("#jform_intIdUGResponsable").css({width: "390px"}); 
        jQuery("#jform_idResponsable").css({width: "390px"}); 
        jQuery("#jform_intIdUndGestion").css({width: "390px"}); 
        jQuery("#jform_intIdTpoGarantia_tg").css({width: "245px"}); 
        jQuery("#jform_intIdFrmGarantia_fg").css({width: "245px"}); 
        jQuery("#jform_intIdPersonasCargo_cgo").css({width: "180px"}); 
        jQuery("#jform_intIdCargo_cgo").css({width: "180px"}); 
        jQuery("#jform_intIDProvincia_dpa").css({width: "170px"}); 
        jQuery("#jform_intIDCanton_dpa").css({width: "170px"}); 
        jQuery("#jform_intIDParroquia_dpa").css({width: "170px"}); 
        jQuery("#jform_intPlazo_ctr").css({width: "201px"}); 
        
        // Readonly de los campos fecha
        jQuery('#jform_dteFechaInicio_ctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFin_ctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaDesde_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaHasta_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaInicio_cctr' ).attr( 'readonly', 'readonly' );
        jQuery('jform_dteFechaInicio_cctr' ).attr( 'readonly', 'readonly' );
        jQuery('jform_dteFechaFin_cctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFin_cctr' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFiscFechaDesde_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFiscFechaHasta_gta' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFactura_fac' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaFactura_fac_adl' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaPago_pgo' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_fchInicioPeriodoUG' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_fchInicioPeriodoFuncionario' ).attr( 'readonly', 'readonly' );
        jQuery('#jform_dteFechaRegistro_cctr' ).attr( 'readonly', 'readonly' );
        
        //  indicadores/grupode atencion prioritaria
        jQuery("#jform_cbGpoAtencionPrioritario").css({width: "280px"});
        jQuery('#jform_cbEnfoqueIgualdad').css( 'width', '230px' );
        jQuery('#jform_idEnfoqueIgualdad').css( 'width', '230px' );
    } //  Tamaño de los combos

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
     *  campo LATITUD LOGITUD 
     */
    jQuery( '#jform_dcmLatitud_coor, #jform_dcmLlong_coor' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    })
    
    // VALIDACION CAMPOS INDICADORES    
        
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
    
    
    //inicalizar fechas por defecto
    jQuery("#jform_dteFechaRegistro_cctr").val(getDateNow());
    jQuery("#jform_fchInicioPeriodoUG").val(getDateNow());
    jQuery("#jform_fchInicioPeriodoFuncionario").val(getDateNow());
            

    {// inicializar los combos.
        recorrerCombo(jQuery("#jform_intidPrograma option"), parseInt(jQuery("#idPrograma").val()));
        recorrerCombo(jQuery("#jform_strContratistaContacto_cta option"), 1);
        jQuery('#jform_strContratistaContacto_cta').trigger('change', jQuery('#jform_strContratistaContacto_cta').val());
    }// inicializar los conbos.

    /**
     * VALIDACION DE LOS CAMPOS
     */
    jQuery("#contratos-form").validate({
        rules: {
            jform_strDescripcion_ob     : { required: true, minlength: 2 },
            jform_intIdPartida_pda      : { requiredlist: true },
            jform_strCodigoContrato_ctr : { required: true },
            jform_intNumContrato_ctr    : { required: true },
            jform_strCUR_ctr            : { required: true },
            jform_dcmMonto_ctr          : { required: true },
            jform_strDescripcion_ctr    : { required: true, minlength: 2 },
            jform_dteFechaInicio_ctr    : { required: true, dateAMD: true },
            jform_dteFechaFin_ctr       : { required: true, dateAMD: true },
            
            jform_intIdTpoGarantia_tg   : { requiredlist: true },
            jform_intIdFrmGarantia_fg   : { requiredlist: true },
            jform_intCodGarantia_gta    : { required: true },
            jform_dcmMonto_gta          : { required: true },
            jform_dteFechaDesde_gta     : { required: true, dateAMD: true },
            jform_dteFechaHasta_gta     : { required: true, dateAMD: true },
            
            jform_intIdGarantia_sele_eg     : { requiredlist: true },
            jform_intIdEstadoGarantia_eg    : { requiredlist: true },
            jform_strObservasion_ge         : { minlength: 2 },
                
            jform_strContratista_cta    : { requiredlist: true },
            jform_dteFechaInicio_cctr   : { required: true, dateAMD: true },
            jform_dteFechaFin_cctr      : { required: true, dateAMD: true },
            
            jform_strContratistaContacto_cta: { requiredlist: true },
            jform_intIdCargo_cgo            : { requiredlist: true },
            jform_intIdPersonasCargo_cgo    : { requiredlist: true },
            jform_strCedula_pc              : { required: true },
            jform_strApellidos_pc           : { required: true, minlength: 2 },
            jform_strNombres_pc             : { required: true, minlength: 2 },
            jform_strCorreoElectronico_pc   : { required: true, email: true },
            jform_strTelefono_pc            : { required: true },
            jform_strCelular_pc             : { required: true },
            
            jform_intIdPersonasFiscalizador_cgo : { requiredlist: true },
            jform_dteFiscFechaDesde_gta         : { required: true, dateAMD: true },
            jform_dteFiscFechaHasta_gta         : { required: true, dateAMD: true },
            
            jform_intCodMulta_mta       : { required: true, minlength: 2 },
            jform_dcmMonto_mta          : { required: true },
            jform_strObservacioin_mta   : { required: true, minlength: 2 },
            
            jform_intCodProroga_prrga   : { required: true, minlength: 2 },
            jform_strObservacion_prrga  : { required: true, minlength: 2 },
            jform_strDocumento_prrga    : { required: true, minlength: 2 },
            jform_dcmMora_prrga         : { required: true },
            jform_intPlazo_prrga        : { required: true },
            
            jform_strNumero_fac         : { required: true },
            jform_intCodFactura_fac     : { required: true },
            jform_dcmMonto_fac          : { required: true },
            jform_dteFechaFactura_fac   : { required: true },
            
            jform_inpCodigo_tp      : { requiredlist: true },
            jform_intCodPago_pgo    : { required: true },
            jform_strCUR_pgo        : { required: true },
            jform_dcmMonto_pgo      : { required: true },
            
            jform_intCodPlanPago_pgo    :{ required: true },
            jform_strPlanProducto_pgo   :{ required: true, minlength: 2 },
            jform_dcmPlanPagoMonto_pgo  :{ required: true },
            jform_inpPorCiento_pgo      :{ required: true },
            jform_dteFechaPago_pgo      :{ required: true, dateAMD: true },
            
            jform_intIDProvincia_dpa            : { requiredlist: true },
            jform_intId_tg                      : { requiredlist: true },
            jform_strDescripcionGrafico_crtg    : { required: true, minlength: 2 },
            jform_dcmLatitud_coor               : { required: true },
            jform_dcmLlong_coor                 : { required: true },
            jform_dcmLatitud_coorCir            : { required: true },
            jform_dcmLongitud_coorCir           : { required: true },
            jform_dcmRadio_coorCir              : { required: true },
            
            jform_intIdUndGestion               : { requiredlist: true },
            jform_idResponsable                 : { requiredlist: true },
            jform_fchInicioPeriodoUG            : { required: true },
            jform_fchInicioPeriodoFuncionario   : { required: true }
            
            
            
        },
        
        messages: {
            jform_strDescripcion_ob     : { required: "Descripción requerida",
                                            minlength: "Ingrese almenos 2 caracteres en descripción" },
            jform_intIdPartida_pda      : { requiredlist: "Seleccione partida" },
            jform_strCodigoContrato_ctr : { required: "Codigo requerido" },
            jform_intNumContrato_ctr    : { required: "Número de contrato requerido" },
            jform_strCUR_ctr            : { required: "CUR requerido" },
            jform_dcmMonto_ctr          : { required: "Monto requerido" },
            jform_strDescripcion_ctr    : { required: "Descripción requerida",
                                            minlength: "Ingrese almenos 2 caracteres en descripción"  },
            jform_dteFechaInicio_ctr    : { required: "Fecha reqerida" },
            jform_dteFechaFin_ctr       : { required: "Fecha reqerida" },
            
            jform_intIdTpoGarantia_tg   : { requiredlist: "Seleccione tipo" },
            jform_intIdFrmGarantia_fg   : { requiredlist: "Seleccione forma" },
            jform_intCodGarantia_gta    : { required: "C&oacute;digo requerida" },
            jform_dcmMonto_gta          : { required: "Monto requerido" },
            jform_dteFechaDesde_gta     : { required: "Fecha requerida" },
            jform_dteFechaHasta_gta     : { required: "Fecha requerida" },
                                    
            jform_intIdGarantia_sele_eg     : { requiredlist: "Seleccione c&oacute;digo de garant&iacute;a" },
            jform_intIdEstadoGarantia_eg    : { requiredlist: "Seleccione estado" },
            jform_strObservasion_ge         : { minlength: "Ingrese almenos 2 caracteres en descripción" },
            
            jform_strContratista_cta    : { requiredlist: "Seleccione contratista" },
            jform_dteFechaInicio_cctr   : { required: "Fecha requerida" },
            jform_dteFechaFin_cctr      : { required: "Fecha requerida" },
            
            jform_strContratistaContacto_cta: { requiredlist: "Seleccione contratista" },
            jform_intIdCargo_cgo            : { requiredlist: "Seleccione cargo" },
            jform_intIdPersonasCargo_cgo    : { requiredlist: "Seleccione contanto" },
            jform_strCedula_pc              : { required: "C&eacute;dula requerida" },
            jform_strApellidos_pc           : { required: "Apellidos requeridos", 
                                                minlength: "Ingrese almenos 2 caracteres en apellidos" },
            jform_strNombres_pc             : { required: "Nombres requeridos", 
                                                minlength: "Ingrese almenos 2 caracteres en nombres" },
            jform_strCorreoElectronico_pc   : { required: "Email requerido",
                                                email: "Ingrese un correo v&aacute;lido" },
            jform_strTelefono_pc            : { required: "Tel&eacute;fono requerido" },
            jform_strCelular_pc             : { required: "Celular requerido" },
            
            jform_intIdPersonasFiscalizador_cgo : { requiredlist: "Seleccione Fiscalizador" },
            jform_dteFiscFechaDesde_gta : { required: "Fecha reqerida" },
            jform_dteFiscFechaHasta_gta : { required: "Fecha reqerida" },
            
            jform_intCodMulta_mta       : { required: "C&oacute;digo requerido", 
                                            minlength: "Ingrese almenos 2 caracteres en c&oacute;digo" },
            jform_dcmMonto_mta          : { required: "Monto requerido" },
            jform_strObservacioin_mta   : { required: "Observaci&oacute;n requerida", 
                                            minlength: "Ingrese almenos 2 caracteres en observaci&oacute;n" },
                                        
            jform_intCodProroga_prrga   : { required: "C&oacute;digo requerido", 
                                            minlength: "Ingrese almenos 2 caracteres en c&oacute;digo" },
            jform_strObservacion_prrga  : { required: "Observaci&oacute;n requerida", 
                                            minlength: "Ingrese almenos 2 caracteres en observaci&oacute;n" },
            jform_strDocumento_prrga    : { required: "Documento requerido", 
                                            minlength: "Ingrese almenos 2 caracteres en documento" },
            jform_dcmMora_prrga         : { required: "Mora requerido" },
            jform_intPlazo_prrga        : { required: "Plazo requerido" },
            
            jform_strNumero_fac         : { required: "N&uacute;mero requerido" },
            jform_intCodFactura_fac     : { required: "C&oacute;digo requerido" },
            jform_dcmMonto_fac          : { required: "Monto requerido" },
            jform_dteFechaFactura_fac   : { required: "Fecha requerida" },
            
            jform_inpCodigo_tp      : { requiredlist: "Seleccione tipo de pago" },
            jform_intCodPago_pgo    : { required: "C&oacute;digo requerido" },
            jform_strCUR_pgo        : { required: "CUR requerido" },
            jform_dcmMonto_pgo      : { required: "Monto requerido" },
            
            jform_intCodPlanPago_pgo    :{ required: "C&oacute;digo requerido" },
            jform_strPlanProducto_pgo   :{ required: "Nombre del producto requerido", 
                                            minlength: "Ingrese almenos 2 caracteres en producto" },
            jform_dcmPlanPagoMonto_pgo  :{ required: "Monto requerido" },
            jform_inpPorCiento_pgo      :{ required: "Porcentaje requerido" },
            jform_dteFechaPago_pgo      :{ required: "Fecha requerida" },
            
            jform_intIDProvincia_dpa            : { requiredlist: "Seleccione provincia" },
            jform_intId_tg                      : { requiredlist: "Seleccione tipo de gr&aacute;fico" },
            jform_strDescripcionGrafico_crtg    : { required: "Descripci&oacute;n requerida", 
                                                    minlength: "Ingrese almenos 2 caracteres en Descripci&oacute;n" },
            jform_dcmLatitud_coor               : { required: "Latitud requerida" },
            jform_dcmLlong_coor                 : { required: "Longitud requerida" },
            jform_dcmLatitud_coorCir            : { required: "Latitud requerida" },
            jform_dcmLongitud_coorCir           : { required: "Longitud requerida" },
            jform_dcmRadio_coorCir              : { required: "Radio requerido" },
            
            form_intIdUndGestion                : { requiredlist: "Seleccione unidad responsable" },
            jform_idResponsable                 : { requiredlist: "Seleccione funcionario responsable" },
            jform_fchInicioPeriodoUG            : { required: "Fecha requerida" },
            jform_fchInicioPeriodoFuncionario   : { required: "Fecha requerida" }
            
            
        },
        submitHandler: function (form) { 
            return false;
        },
        errorElement: "span"
    });
    
    /**
     * 
     */
    jQuery( '#jform_strCedula_pc' ).attr( 'disabled', 'disabled' );
    jQuery( '#jform_strApellidos_pc' ).attr( 'disabled', 'disabled' );
    jQuery( '#jform_strNombres_pc' ).attr( 'disabled', 'disabled' );
    jQuery( '#jform_strCorreoElectronico_pc' ).attr( 'disabled', 'disabled' );
    jQuery( '#jform_strTelefono_pc' ).attr( 'disabled', 'disabled' );
    jQuery( '#jform_strCelular_pc' ).attr( 'disabled', 'disabled' );
    jQuery( '#jform_strCedula_pc' ).attr( 'disabled', 'disabled' );
    
    
    /**
     * Da formato de valor monetario al Monto
     */
    jQuery( '#jform_dcmMonto_ctr' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_ctr' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_gta' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_gta' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_mta' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_mta' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_fac' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_fac' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_pgo_adl' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_pgo_adl' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_pgo' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_pgo' ).val(), '$' ) );
    jQuery( '#jform_dcmMora_prrga' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMora_prrga' ).val(), '$' ) );
    jQuery( '#jform_dcmPlanPagoMonto_pgo' ).attr( 'value', formatNumber( jQuery( '#jform_dcmPlanPagoMonto_pgo' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_fac_adl' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_fac_adl' ).val(), '$' ) );
    jQuery( '#jform_dcmMonto_ctr, #jform_dcmMonto_gta, #jform_dcmMonto_mta, #jform_dcmMonto_fac, #jform_dcmMonto_pgo, #jform_dcmMora_prrga, #jform_dcmPlanPagoMonto_pgo, #jform_dcmMonto_pgo_adl, #jform_dcmMonto_fac_adl' ).blur( function(){
        var val = unformatNumber( jQuery( this ).val() );
        var valor = parseFloat( val );
        jQuery( this ).attr( 'value', formatNumber( valor.toFixed( 2 ), '$' ) );
    });
    
    /**
     * Controlo el ingreso de caracteres numericos en determinadas campos
     */
    jQuery( '#jform_strCedula_pc, #jform_dcmMora_prrga, #jform_intPlazo_prrga, #jform_dcmMonto_pgo, #jform_intCodProroga_prrga, #jform_intCodPlanPago_pgo' ).keypress( function( e ){
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    /**
     * Controla el ingreso de caracteres alfabeticos
     */
    jQuery( '#jform_strApellidos_pc, #jform_strNombres_pc' ).keypress( function( e ){
        var tecla = e.which;
        if (( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) && !( tecla > 96 && tecla < 123 ) && ( tecla != 241 && tecla != 209 )){
            return false;
        }
    });
    
    /**
     * Mascaras para los lofrmatos de telefonos
     */
    jQuery('#jform_strCedula_pc').mask("999999999-9");
    jQuery('#jform_strCelular_pc').mask("999-999-9999");
    jQuery('#jform_strTelefono_pc').mask("999-999-999");

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

/**
 * 
 * @returns {String}Retorna la fecha actual en formato anio-mes-dia
 */
function getDateNow()
{
    var f = new Date();
    var mes = (f.getMonth() + 1 > 9) ? (f.getMonth() + 1) : "0" + (f.getMonth() + 1);
    var dia = (f.getDate() > 9) ? f.getDate() : "0" + f.getDate();
    var fecha = f.getFullYear() + "-" + mes + "-" + dia;
    return fecha;
}


function resetValidateForm( idForm )
{
    jQuery("#contratos-form").submit();
    
    jQuery( idForm + " select").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( idForm + " input[type=text]").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( idForm + " textarea").each(function () {
        jQuery( this ).removeClass( "error" );
    });
}
