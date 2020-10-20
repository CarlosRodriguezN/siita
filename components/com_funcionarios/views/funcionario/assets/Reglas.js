newRegFnc = (jQuery('#jform_intCodigo_fnc').val() == 0 ) ? true : false;
jQuery( document ).ready( function(){
    
    //  Opciones para los acordiones
    var optionsAccordion = {collapsible : true,
                            heightStyle : "content",
                            autoHeight  : false,
                            clearStyle  : true,
                            header      : 'h3'};
    
    //  Acordion para los objetivos con sus indicadores
    jQuery("#accFNC").accordion(optionsAccordion);
    
    //  Pestañas Generales
    jQuery('#tabsFuncionario').tabs();
    jQuery('#tabsPoasPrg').tabs();
    
    jQuery('#jform_strDescripcion_ob').css({width: "305px"});
    jQuery('#jform_intPrioridad_ob').css({width: "145px"});
    jQuery('#jform_intCodigo_ug').css({width: "290px"});
    
    jQuery('#jform_strCelular_fnc').mask("999-999-9999");
    jQuery('#jform_strTelefono_fnc').mask("999-999-999");
    
    jQuery("#funcionario-form").validate({
            rules: {
			jform_strCI_fnc: {
				required: true,
                                rangelength: [10, 10]
			},
			jform_strApellido_fnc: {
				required: true,
				minlength: 2
			},
			jform_strNombre_fnc: {
				required: true,
				minlength: 2
			},
			jform_strCorreoElectronico_fnc: {
				required: true,
				email: true
			},
			jform_password: {
				required: true,
				minlength: 8
			},
			jform_passwordConfirm: {
				required: true,
				equalTo: "#jform_password"
			}
            },
        messages: {
			jform_strCI_fnc: {
				required: "Cédula requerida",
                                rangelength: "Ingrese un número válido"
			},
			jform_strApellido_fnc: {
				required: "Apellido requerido",
				minlength: "Ingrese almenos 2 caracteres en apellido"
			},
			jform_strNombre_fnc: {
				required: "Nombre requerido",
				minlength: "Ingrese almenos 2 caracteres en nombre"
			},
                        jform_strCorreoElectronico_fnc: {
				required: "Email requerido",
				email: "Ingrese un correo v&aacute;lido"
			},
                        jform_password: {
				required: "Contraseña requerida",
				minlength: "La contraseña debe tener almenos 8 caracteres"
			},
			jform_passwordConfirm: {
				required: "Confirmación de contraseña requerida",
				equalTo: "Las contraseñas no coinciden"
			}
        },
        errorElement: "span"
    });
    
    /**
     * Controlo el ingreso de caracteres numericos en el campo CI cedula de identidad
     */
    jQuery( '#jform_strCI_fnc' ).keypress( function( e ){
        var tecla = e.which;
        if (!(tecla > 47 && tecla < 58 ) || (jQuery(this).val().length>9) ){
            return false;
        }
    });
    
    /**
     * Controlo el ingreso de caracteres alfabeticos en el campo Nombre y Apellido
     */
    jQuery( '#jform_strNombre_fnc' ).keypress( function( e ){
        var tecla = e.which;
//        if ( ( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) || (tecla > 122 && (tecla != 164 || tecla != 165)) ){
        if (( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) && !( tecla > 96 && tecla < 123 ) && ( tecla != 241 && tecla != 209 )){
            return false;
        }
    });
    
    jQuery( '#jform_strApellido_fnc' ).keypress( function( e ){
        var tecla = e.which;
        if (( tecla < 97 && tecla != 0 && tecla != 8 && tecla != 32 ) && !( tecla > 64 && tecla < 91 ) && !( tecla > 96 && tecla < 123 ) && ( tecla != 241 && tecla != 209 )){
            return false;
        }
    });
    
    jQuery('#uploadFather').uploadifive({
        'auto': false,
        'buttonText': 'Imagen',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'fileType': false,
        'uploadScript': 'index.php',
        onAddQueueItem: function(file) {
        },
        onCancel: function() {
        },
        onUploadFile: function(file) {
        },
        onUploadComplete: function(file, data) {
            var docData = eval("(" + data + ")");
            resdirecTo(docData);
        }
    });
    
    //  Bloquea los imput de las fechas
    jQuery( '#jform_dteFechaInicio_ugf, #jform_dteFechaFin_ugf' ).attr( 'readonly', 'readonly' );
});
