    jQuery( document ).ready( function(){
    
    var valor = 0;
    
    // Readonly de los campos fecha
    jQuery( '#jform_dteFechaInicio_stmdoPry' ).attr( 'readonly', 'readonly' );
    jQuery( '#jform_dteFechaFin_stmdoPry' ).attr( 'readonly', 'readonly' );
    jQuery( '#jform_fchInicioPeriodoUG' ).attr( 'readonly', 'readonly' );
    jQuery( '#jform_fchInicioPeriodoFuncionario' ).attr( 'readonly', 'readonly' );
         
         
    jQuery("#accordion").accordion({ header: "> div > h3" }).sortable({ axis: "y", handle: "h3",
        stop: function(event, ui) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children("h3").triggerHandler("focusout");
        }
    });
    
    jQuery('#prueba').click(function() {
        var id = '#smf-' + '0';
        jQuery(id).css('background-position', '0 -66px');
    })
    
    //  Pestañas Generales, Marco Logico, Indicadores, Ubicacion geografica
    jQuery('#tabs').tabs();
    jQuery('#tabsMarcoLogico').tabs();
    jQuery('#tabsIndicadores').tabs();
    jQuery('#tabsUbicacionGeografica').tabs();
    
    //  Ajusto el tamaño del comboBox de programas
    jQuery('#jform_intCodigo_prg').css( 'width', '375px' );
    jQuery('#jform_intCodigo_sprg').css( 'width', '375px' );
    jQuery('#jform_intCodigo_tsprg').css( 'width', '375px' );
    jQuery('#jform_inpCodigotipo_inv').css( 'width', '375px' );
    jQuery('#jform_intCodigo_ug').css( 'width', '375px' );
    jQuery('#jform_inpCodigo_cb').css( 'width', '375px' );
    jQuery('#jform_macrosector').css( 'width', '375px' );
    jQuery('#jform_sector').css( 'width', '375px' );
    jQuery('#jform_subsector').css( 'width', '375px' );
    jQuery('#jform_idFuncionario').css( 'width', '375px' );
    jQuery('#jform_idProvincia').css( 'width', '250px' );
    jQuery('#jform_idCanton').css( 'width', '250px' );
    jQuery('#jform_idParroquia').css( 'width', '250px' );
    jQuery('#jform_intcodigo_on').css( 'width', '250px' );
    jQuery('#jform_intcodigo_pn').css( 'width', '250px' );
    jQuery('#jform_idcodigo_mn').css( 'width', '250px' );
    jQuery('#jform_strNombre_pry').css( 'width', '370px' );
    jQuery('#jform_dcmMonto_total_stmdoPry').css( 'width', '129px' );
    jQuery('#jform_strDescripcion_pry').css( 'width', '369px' );
    jQuery('#jform_cbEnfoqueIgualdad').css( 'width', '230px' );
    jQuery('#jform_idEnfoqueIgualdad').css( 'width', '230px' );
    
    jQuery('#jform_idResponsable, #jform_intIdUGResponsable, #jform_intIdUndGestion').css( 'width', '350px' );
    
    jQuery('#jform_cbGpoAtencionPrioritario').css( 'width', '250px' );
    
    //  jQuery('#jform_cbEnfoqueIgualdad-lbl, #jform_idEnfoqueIgualdad-lbl').css( 'min-width', '140px' );
    
    jQuery( '#jform_strNombre_pry, #jform_txtNombreFin, #jform_strMLFin, #jform_objGeneral, #jform_objEspecifico' ).keypress( function( e ){
        jQuery( this ).attr( 'value', jQuery( this ).val().toUpperCase() );
    });
    
    //  Monto estimado del proyecto
    jQuery( '#jform_dcmMonto_total_stmdoPry' ).attr( 'value', formatNumber( jQuery( '#jform_dcmMonto_total_stmdoPry' ).val(), '$' ) );

    //  VAN - ECONOMICO
    jQuery( '#jform_intValActualNetoEco' ).attr( 'value', formatNumber( jQuery( '#jform_intValActualNetoEco' ).val(), '$' ));
    jQuery( '#jform_intValActualNetoEco' ).css( 'width', '100px' );

    //  VAN - Financiero
    jQuery( '#jform_intValActualNetoFin' ).attr( 'value', formatNumber( jQuery( '#jform_intValActualNetoFin' ).val(), '$' ) );
    jQuery( '#jform_intValActualNetoFin' ).css( 'width', '100px' );

    //  Gestiono la actualizacion de un nuevo valor en funcion al cambio de valor
    jQuery( '#jform_dcmMonto_total_stmdoPry, #jform_intValActualNetoEco, #jform_intValActualNetoFin' ).blur( function(){
        var val = unformatNumber( jQuery( this ).val() );
        var valor = parseFloat( val );
        jQuery( this ).attr( 'value', formatNumber( valor.toFixed( 2 ), '$' ) );
    })
 
//
//  REGLAS
//

    /*
     * descripcion de el grafico
     */
    jQuery( '#jform_strDescripcionObra' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 ) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    })

    /*
     * Descripcion de un proyecto
     */
    jQuery( '#jform_strNombre_pry' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 ) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    })
    /*
     *  campo LATITUD 
     */
    jQuery( '#jform_latitud' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( (tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 )) || (jQuery(this).val().length>17) ){
            return false;
        }
    })
    /*
     *  campo LOGITUD 
     */
    jQuery( '#jform_longitud' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( (tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 )) || (jQuery(this).val().length>17) ){
            return false;
        }
    })
    
    /**
     * Controlo el ingreso de caracteres numericos positivos en el campo radio
     * para el tipo de graficocirculo de una obra.
     */
    jQuery( '#jform_Radio' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    /**
     * Controlo el ingreso de caracteres numericos.
     */
    jQuery( '#jform_intTotal_benDirectos_pry, #jform_inpDuracion_stmdoPry' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    /**
     * Controlo el ingreso de caracteres numericos monto.
     */
    jQuery( '#jform_dcmMonto_total_stmdoPry' ).keypress( function( e ){
        var tecla = e.which;
        if (tecla == 190 || tecla != 46 && !(tecla > 47 && tecla < 58)) {
                return false;
        }        
    });
    
    /**
     * Controlo el ingreso de caracteres numericos en campos de indicadores Economicos.
     */
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
     * Controlo el ingreso de caracteres numericos en campos de indicadores Beneficiarios.
     */
    jQuery( '#jform_intBenfDirectoHombre, #jform_intBenfDirectoMujer, #jform_intTotalBenfDirectos, #jform_intBenfIndDirectoHombre, #jform_intBenfIndDirectoMujer, #jform_intTotalBenfIndDirectos' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    /**
     * Controlo el ingreso de caracteres numericos en campos de indicadores GAP.
     */
    jQuery( '#jform_intGAPMasculino, #jform_intGAPFemenino, #jform_intGAPTotal' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    /**
     * Controlo el ingreso de caracteres numericos en campos de indicadores Enfoque de Igualdad.
     */
    jQuery( '#jform_intEIMasculino, #jform_intEIFemenino, #jform_intEITotal' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    /**
     * Controlo el ingreso de caracteres numericos en campos de indicadores Enfoque ECORAE.
     */
    jQuery( '#jform_intEnfEcoMasculino, #jform_intEnfEcoFemenino, #jform_intEnfEcoTotal' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    //
    //  Validaciones y reglas generales
    //
    
    /**
     *  Control de ingreso de caracteres alfanumericos con caracteres los especiasles .,-_ñ 
     */
    var alfaNum = '';
    alfaNum += '#jform_strCodigoInterno_pry';
    
    jQuery( alfaNum ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla < 97 && tecla !== 0 && tecla !== 8 && tecla !== 32 && tecla !== 45 && !( tecla > 64 && tecla < 91 ) ) {
            return false;
        }
    });
        
    /**
     *  Control de ingreso de caracteres alfanumericos con caracteres especiasles 
     */
    var alfaNumCon = '';
    alfaNumCon += '#jform_strDescripcion_pry';
    
    jQuery( alfaNumCon ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) ) {
            return false;
        }
    });
        
    /**
     *  Control de ingreso de caracteres alfabeticos
     */
    var alfabeticos = '';
    alfabeticos += '';
    
    jQuery( alfabeticos ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) ) {
            return false;
        }
    });
    
    /**
     *  Control de ingreso de caracteres numericos y el punto .
     */
    var numericos = '';
    numericos += '#jform_strcup_pry';
    
    jQuery( numericos ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });

    /**
     * 
     */
    jQuery("#proyecto-form").validate({
        rules: {
            jform_strDescripcion_ob         : { required: true, minlength: 2 },
            jform_intCodigo_prg             : { requiredlist: true },
            jform_inpCodigotipo_inv         : { requiredlist: true },
            jform_strNombre_pry             : { required: true, minlength: 2 },
            jform_dteFechaInicio_stmdoPry   : { required: true, dateAMD: true },
            jform_dteFechaFin_stmdoPry      : { required: true, dateAMD: true },
            jform_dcmMonto_total_stmdoPry   : { montoUS: true },
            jform_idEstadoEntidad           : { requiredlist: true },
            jform_intIdUndGestion           : { requiredlist: true },
            jform_idResponsable             : { requiredlist: true },
            
            jform_idProvincia               : { requiredlist: true },
            jform_idTipoGrafico             : { requiredlist: true },
            jform_strDescripcionObra        : { minlength: 2 },
            jform_latitud                   : { required: true, coorLatitud: true, number: true },
            jform_longitud                  : { required: true, coorLongitud: true, number: true },
            jform_Radio                     : { required: true, number: true },
            
            jform_txtNombreFin              : { required: true, minlength: 2 },
            jform_txtNombreProposito        : { required: true, minlength: 2 },
            jform_txtNombreComponente       : { required: true, minlength: 2 },
            jform_cbMLComponente            : { requiredlist: true },
            jform_txtNombreActividad        : { required: true, minlength: 2 },
            
        },
        
        messages: {
            jform_strDescripcion_ob         : { required: "Descripci&oacute;n requerida",
                                                minlength: "Ingrese almenos 2 caracteres en descripci&oacute;n" },
            jform_intCodigo_prg             : { requiredlist: "Seleccione Programa" },
            jform_inpCodigotipo_inv         : { requiredlist: "Seleccione Tipo de Proyecto" },
            jform_strNombre_pry             : { required: "Nombre requerida",
                                                minlength: "Ingrese almenos 2 caracteres en nombre" },
            jform_dcmMonto_total_stmdoPry   : { required: "Monto requerido", montoUS: "Monto requerido" },
            jform_idEstadoEntidad           : { requiredlist: "Seleccione Estado" },
            jform_intIdUndGestion           : { requiredlist: "Seleccione Unidad de Gesti&oacute;n" },
            jform_idResponsable             : { requiredlist: "Seleccione Responsable" },
            
            jform_idProvincia               : { requiredlist: "Seleccione Provincia" },
            jform_idTipoGrafico             : { requiredlist: "Seleccione Tipo de Gr&aacute;fico" },
            jform_strDescripcionObra        : { minlength: "Ingrese almenos 2 caracteres en descripci&oacute;n" },
            jform_latitud                   : { required: "Latitud requerida", 
                                                number: "Solo valores numericos decimales" },
            jform_longitud                  : { required: "Latitud requerida", 
                                                number: "Solo valores numericos decimales" },
            jform_Radio                     : { required: "Radio requerido",
                                                number: "Solo valores numericos decimales" },
                                            
            jform_txtNombreFin              : { required: "Fin requerido", 
                                                minlength: "Ingrese almenos 2 caracteres en fin del proyecto" },
            jform_txtNombreProposito        : { required: "Prop&oacute;sito requerido", 
                                                minlength: "Ingrese almenos 2 caracteres en prop&oacute;sito" },
            jform_txtNombreComponente       : { required: "Nombre requerido", 
                                                minlength: "Ingrese almenos 2 caracteres en nombre" },
            jform_cbMLComponente            : { requiredlist: "Seleccione componente" },
            jform_txtNombreActividad        : { required: "Actividad requerida", 
                                                minlength: "Ingrese almenos 2 caracteres en actividad" },
            
            
        },
        submitHandler: function () { 
            return false;
        },
        errorElement: "span"
    });
    
    
});

/**
 *  Resetea los sms del validate en los input de una determinada parte del formulario 
 * @param {type} form
 * @returns {undefined}
 */
function cleanValidateForn ( form )
{
    jQuery("#proyecto-form").submit();
    
    jQuery( form + " select").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( form + " input[type=text]").each(function () {
        jQuery( this ).removeClass( "error" );
    });
    
    jQuery( form + " textarea").each(function () {
        jQuery( this ).removeClass( "error" );
    });
}

/**
 *  Obtiene la fecha actual 
 * @returns {String}
 */
function getDateNow()
{
    var f = new Date();
    var mes = (f.getMonth() + 1 > 9) ? (f.getMonth() + 1) : "0" + (f.getMonth() + 1);
    var dia = (f.getDate() > 9) ? f.getDate() : "0" + f.getDate();
    var fecha = f.getFullYear() + "-" + mes + "-" + dia;
    return fecha;
}