jQuery( document ).ready( function(){


    //  Opciones para los acordiones
    var optionsAccordion = {collapsible : true,
                            heightStyle : "content",
                            autoHeight  : false,
                            clearStyle  : true,
                            header      : 'h3'};
                        
    //  Acordion de objetivos con sus indicadores
    jQuery("#accUG").accordion(optionsAccordion);
    
    //  Pestañas Generales
    jQuery('#tabsUndGes').tabs();
    
    //  Especifica el tamaño de los select
    jQuery("#jform_tb_intCodigo_ug").css({width: "310px"});
    jQuery("#jform_intTpoUG_ug").css({width: "310px"});
    jQuery("#jform_inpCodigo_cargo").css({width: "290px"});
    jQuery("#jform_intCodigo_fnc").css({width: "290px"});
    jQuery("#jform_intCodigo_fnc_aux").css({width: "290px"});
    jQuery('#jform_intCodigo_fnc_aux').attr("disabled", "disabled");
    jQuery("#jform_urlOp").css({width: "280px"});
    jQuery("#jform_descripcionOp").css({width: "280px"});
    
    jQuery('#jform_dteFechaInicio_ugf').mask("9999-99-99");
    jQuery( '#jform_dteFechaInicio_ugf' ).attr( 'readonly', 'readonly' ); 
    jQuery('#jform_dteFechaFin_ugf').mask("9999-99-99");
    jQuery( '#jform_dteFechaFin_ugf' ).attr( 'readonly', 'readonly' ); 
    
    jQuery("#unidadgestion-form").validate({
        rules: {
            jform_strNombre_ug: {
                    required: true,
                    minlength: 2
            },
            jform_strAlias_ug: {
                    required: true,
                    minlength: 2
            },
            jform_inpCodigo_cargo: {
                    requiredlist: true
            },
            jform_intCodigo_fnc: {
                    requiredlist: true
            },
            // las fechas no tienen messages personalizado ya que es una 
            // validacion propia creada para el sistema 
            jform_dteFechaInicio_ugf: {
                    required: true,
                    dateAMD: true
            },
            jform_dteFechaFin_ugf: {
                    required: true,
                    dateAMD: true
            }
        },
        messages: {
            jform_strNombre_ug: {
                    required: "Nombre requerido",
                    minlength: "Ingrese almenos 2 caracteres en nombre"
            },
            jform_strAlias_ug: {
                    required: "Alias requerido",
                    minlength: "Ingrese almenos 2 caracteres en alias"
            },
            jform_inpCodigo_cargo: {
                    requiredlist: "Cargo requerido"
            },
            jform_intCodigo_fnc: {
                    requiredlist: "Funcionario requerido"
            },
            jform_dteFechaInicio_ugf: {
                    required: "Fecha reqerida"
            },
            jform_dteFechaFin_ugf: {
                    required: "Fecha reqerida"
            }
        },
        submitHandler: function () { 
            return false;
        },

        errorElement: "span"
    });
    
    //  Controlo el ingreso de caracteres alfabeticos y guion en el nombre de la unidad de gestion
    jQuery( '#jform_strNombre_ug' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) ) 
             || (tecla > 122 && !( tecla > 159 && tecla < 166 ) ) ) {
            return false;
        }
    });
   
    //  Controlo el ingreso de caracteres alfanumericos y guion para el alias
    jQuery( '#jform_strAlias_ug' ).keypress( function( e ){
        var tecla = e.which;
        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 && tecla != 45 && !( tecla > 64 && tecla < 91 ) && !(tecla > 47 && tecla < 58 ) ) 
             || (tecla > 122 && !( tecla > 159 && tecla < 166 ) ) ) {
            return false;
        }
    });
    
    //Campo nombre de opciones adicionales  
    jQuery( '#jform_nombreOp' ).keypress( function( e ){
        var tecla = e.which;
        if (( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) && !( tecla > 96 && tecla < 123 ) && ( tecla != 241 && tecla != 209 )){
            return false;
        }
    });
   
});