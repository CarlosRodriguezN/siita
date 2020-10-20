jQuery( document ).ready( function(){

    //  Pestañas General
    jQuery('#tabsPropuesta').tabs();
    
    //  Pestaña Ubicacion Geografica
    jQuery('#tabsUbicacion').tabs();
    
    //  Pestaña Ubicacion Geografica
    jQuery('#tabsIndicadores').tabs();
    
    //
    //  Controlo el ingreso de caracteres para el campo codigo con aceptacion de ".", "_", "-"
    //  numeros y letras.
    //
    jQuery( '#jform_strCodigoPropuesta_cp' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && tecla != 46 && tecla != 95) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres numericos en el campo monto de lapropuesta
    //
    jQuery( '#jform_strNombre_cp' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) || tecla > 123 ){
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres numericos en el campo monto de la propuesta de proyecto
    //
    jQuery( '#jform_dcmMonto_cp' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 )){
            return false;
        }
    });

    //
    //  Controlo el ingreso de caracteres bumericos en el campo beneficiarios
    //
    jQuery( '#jform_intNumeroBeneficiarios' ).keypress( function( e ){
        var tecla = e.which;
        if ( !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres alfanumercos en la descripción de una propuesta de proyecto
    //
    jQuery( '#jform_strDescripcion_cp' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 ) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    });
    
    //
    //  Control del ingreso de caracteres alfanumericos en la descripcion de un grafico de una obra
    //
    jQuery( '#jform_strDescripcionGrafico_gcp' ).keypress( function( e ){
        var tecla = e.which;
        
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 ) && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) || tecla > 122 ) {
            return false;
        }
    });

    //
    //  Controlo el ingreso de caracteres numericos negativos en el campo latitud de una coordenada
    //
    jQuery( '#jform_latitud' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres numericos negativos en el campo longitud de una coordenada
    //
    jQuery( '#jform_longitud' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 45 && tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    //
    //  Controlo el ingreso de caracteres numericos positivos en el campo radio
    //  para el tipo de graficocirculo de una obra.
    //
    jQuery( '#jform_Radio' ).keypress( function( e ){
        var tecla = e.which;
        if ( tecla != 46 && !(tecla > 47 && tecla < 58 ) ){
            return false;
        }
    });
    
    //
    //  Especifica el tamaño de los select
    //
     jQuery("#jform_idProvincia").css({width: "265px"});
     jQuery("#jform_idCanton").css({width: "265px"});
     jQuery("#jform_idParroquia").css({width: "265px"});
     jQuery("#jform_intCodigo_on").css({width: "400px"});
     jQuery("#jform_intCodigo_pn").css({width: "400px"});
     jQuery("#jform_idCodigo_mn").css({width: "400px"});
     jQuery("#jform_intCodigo_ins").css({width: "308px"});
     jQuery("#jform_inpCodigo_estado").css({width: "308px"});
     jQuery("#jform_strDescripcion_cp").css({width: "304px"});
    
    /**
     * Da formato de valor monetario al Monto
     */
    if ( jQuery( '#jform_dcmMonto_cp' ).val() != '' ) {
        var montoP = parseFloat(jQuery( '#jform_dcmMonto_cp' ).val());
        jQuery( '#jform_dcmMonto_cp' ).attr( 'value', formatNumber( montoP.toFixed( 2 ), '$' ) );
    }
    
    jQuery( '#jform_dcmMonto_cp' ).blur( function(){
        var val = unformatNumber( jQuery( this ).val() );
        var valor = parseFloat( val );
        jQuery( this ).attr( 'value', formatNumber( valor.toFixed( 2 ), '$' ) );
    });
    
    /**
     * VALIDACION DE LOS CAMPOS
     */
    jQuery("#formDataProuesta").validate({
        rules: {
            jform_intCodigo_ins             : { requiredlist: true },
            jform_inpCodigo_estado          : { requiredlist: true },
            jform_strCodigoPropuesta_cp     : { required: true, minlength: 2 },
            jform_strNombre_cp              : { required: true, minlength: 2 },
            jform_dcmMonto_cp               : { required: true },
            jform_intNumeroBeneficiarios    : { required: true },
        },
        
        messages: {
            jform_intCodigo_ins             : { requiredlist: "Seleccione institución" },
            jform_inpCodigo_estado          : { requiredlist: "Seleccione estado" },
            jform_strCodigoPropuesta_cp     : { required: "Código requerido", 
                                                minlength: "Ingrese almenos 2 caracteres en código" },
            jform_strNombre_cp              : { required: "Nombre requerido", 
                                                minlength: "Ingrese almenos 2 caracteres en nombre" },
            jform_dcmMonto_cp               : { required: "Monto requerido" },
            jform_intNumeroBeneficiarios    : { required: "Beneficiarios requerido" }
        },
        
        submitHandler: function (form) { 
            return false;
        },
        
        errorElement: "span"
    });
    
    jQuery("#frmDtaUnidadTerritorial").validate({
        rules: {
            jform_idProvincia   : { requiredlist: true },
        },
        messages: {
            jform_idProvincia   : { requiredlist: "Seleccione una provincia" },
        },
        submitHandler: function (form) { 
            return false;
        },
        errorElement: "span"
    });
    
    jQuery("#frmDtaUbcGeo").validate({
        rules: {
            jform_idTipoGrafico             : { requiredlist: true },
            jform_strDescripcionGrafico_gcp : { required: true, minlength: 2 },
        },
        messages: {
            jform_idTipoGrafico             : { requiredlist: "Seleccione un gráfico" },
            jform_strDescripcionGrafico_gcp : { required: "Descripci&oacute;n requerida", 
                                                minlength: "Ingrese almenos 2 caracteres en descripci&oacute;n" },
        },
        submitHandler: function (form) { 
            return false;
        },
        errorElement: "span"
    });
    
    
    jQuery("#frmDtaUbcGeoCoordenada").validate({
        rules: {
            jform_latitud   : { required: true, coorLatitud: true, number: true },
            jform_longitud  : { required: true, coorLongitud: true, number: true },
            jform_Radio     : { required: true, number: true },
        },
        messages: {
            jform_latitud   : { required: "Latitud requerida", 
                                number: "Solo valores numericos decimales" },
            jform_longitud  : { required: "Longitud requerida", 
                                number: "Solo valores numericos decimales" },
            jform_Radio     : { required: "Radio requerida", 
                                number: "Solo valores numericos decimales" },
        },
        submitHandler: function (form) { 
            return false;
        },
        errorElement: "span"
    });
    
    
    
});