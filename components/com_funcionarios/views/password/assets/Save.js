jQuery(document).ready(function() {
    Joomla.submitbutton = function(task)
    {
        switch (task) {
            case 'password.registrar':
                if ( validateForm() ){
                    updPassword();
                } else {
                    jAlert( JSL_ALERT_ALL_NEED, JSL_ECORAE );
                }
                break;

            case 'password.cancel':
                window.parent.SqueezeBox.close();
                break;
        }
    };

    function validateForm()
    {
        var result = true;
        if (jQuery('#jform_password').val() == '' ||
            jQuery('#jform_newpassword').val() == '' ||
            jQuery('#jform_newpasswordConfirm').val() == '') {
            result = false;
        }
        return result;
    }

    function updPassword()
    {
        var url = window.location.href;
        var path = url.split('?')[0];
        
        var idUsrFnc = jQuery( '#idUsrFnc' ).val();
        var password = jQuery( '#jform_password' ).val();
        var newPass = jQuery( '#jform_newpassword' ).val();

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method: "POST",
                option      : 'com_funcionarios',
                view        : 'password',
                tmpl        : 'component',
                format      : 'json',
                action      : 'updPass',
                id          : idUsrFnc,
                password    : password,
                newPass     : newPass
            },
            error: function(jqXHR, status, error) {
                jAlert('Funcionarios - Cambio de contraseña: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            
            if ( saveData.error ){
                jAlert( saveData.error, JSL_ECORAE);
            } else if ( saveData.data ){
                window.parent.SqueezeBox.close();
            } else {
                jAlert( JSL_ALERT_ERROR, JSL_ECORAE);
            }
            
            return true;
        });
    }

});
