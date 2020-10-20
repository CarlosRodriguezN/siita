jQuery( document ).ready( function(){
    
    jQuery("#passwordForm").validate({
            rules: {
			jform_password: {
				required: true
			},
			jform_newpassword: {
				required: true,
				minlength: 8
			},
			jform_newpasswordConfirm: {
				required: true,
				equalTo: "#jform_newpassword"
			}
            },
        messages: {
                        jform_password: {
				required: "Contraseña actual requerida",
			},
                        jform_newpassword: {
				required: "Nueva contraseña requerida",
				minlength: "La contraseña debe tener almenos 8 caracteres"
			},
			jform_newpasswordConfirm: {
				required: "Confirmacion de contraseña requerida",
				equalTo: "Las contraseñas no coinciden"
			}
        },
        errorElement: "span"
    });
    
});