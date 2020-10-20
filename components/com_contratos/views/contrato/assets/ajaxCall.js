var url = window.location.href;
var path = url.split('?')[0];
jQuery(document).ready(function() {
    /**
     * recuperando los SubProgramas de un programa
     */
    jQuery("#jform_intidPrograma").change(function() {
        jQuery('#jform_intIdSubrograma').html('<option value="0"> ' + COM_LIST_LOAD + ' </option>');
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getProyectos',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idPrograma: jQuery('#jform_intidPrograma').val()//url de la que se consumira el servicio
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var items = [];
            
            var numRegistros = dataInfo.length;
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_SELECTED_PROYECTO + '</option>');
                for (x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].name + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }   
            jQuery('#jform_intCodigo_pry').html(items.join(''));
        });
    });
    /**
     * recuperando los proyectos de un subPrograma
     */
    jQuery("#jform_intIdSubrograma").change(function() {
        jQuery('#jform_intCodigo_pry').html( '<option value="0"> ' + COM_LIST_LOAD + ' </option>' );
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getProyectos',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idSubPrograma: jQuery('#jform_intIdSubrograma').val()//url de la que se consumira el servicio
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var items = [];
            var numRegistros = dataInfo.length;
            if (numRegistros > 0) {
                for (x = 0; x < numRegistros; x++) {
                    items.push('<option value="' + dataInfo[x].id + '">' + dataInfo[x].name + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }
            jQuery('#jform_intCodigo_pry').html(items.join(''));
        });
    });
    /**
     * recuperando la data de una persona contactos
     */
    jQuery("#jform_intIdPersonasCargo_cgo").change(function() {

        /**
         * solo menajse cargando
         */
        jQuery("#jform_strCedula_pc").val("Cargando...");
        jQuery("#jform_strApellidos_pc").val("Cargando...");
        jQuery("#jform_strNombres_pc").val("Cargando...");
        jQuery("#jform_strCorreoElectronico_pc").val("Cargando...");
        jQuery("#jform_strTelefono_pc").val("Cargando...");
        jQuery("#jform_strCelular_pc").val("Cargando...");
        /***/
        /**
         * llamada ajax 
         */
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getDataPersona',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idPersona: jQuery('#jform_intIdPersonasCargo_cgo').val()//url de la que se consumira el servicio
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            if (dataInfo[0]) {
                jQuery("#jform_strCedula_pc").val((dataInfo[0].cedula) ? dataInfo[0].cedula : '');
                jQuery("#jform_strApellidos_pc").val((dataInfo[0].apellido) ? dataInfo[0].apellido : '');
                jQuery("#jform_strNombres_pc").val((dataInfo[0].nombre) ? dataInfo[0].nombre : '');
                jQuery("#jform_strCorreoElectronico_pc").val((dataInfo[0].correo) ? dataInfo[0].correo : '');
                jQuery("#jform_strTelefono_pc").val((dataInfo[0].telefono) ? dataInfo[0].telefono : '');
                jQuery("#jform_strCelular_pc").val((dataInfo[0].celular) ? dataInfo[0].celular : '');
            } else {
                jQuery("#jform_strCedula_pc").val("");
                jQuery("#jform_strApellidos_pc").val("");
                jQuery("#jform_strNombres_pc").val("");
                jQuery("#jform_strCorreoElectronico_pc").val("");
                jQuery("#jform_strTelefono_pc").val("");
                jQuery("#jform_strCelular_pc").val("");
            }
        });
    });
    /**
     * recuperando la data de una persona fiscalizador
     */
    jQuery("#jform_intIdPersonasFiscalizador_cgo").change(function() {

        /**
         * solo menajse cargando
         */
        jQuery("#jform_strFiscaCedula_pc").val("Cargando...");
        jQuery("#jform_strFiscaRuc_fs").val("Cargando...");
        jQuery("#jform_strFiscaApellidos_pc").val("Cargando...");
        jQuery("#jform_strFiscaNombres_pc").val("Cargando...");
        jQuery("#jform_strFiscaCorreoElectronico_pc").val("Cargando...");
        jQuery("#jform_strFiscaTelefono_pc").val("Cargando...");
        jQuery("#jform_strFiscaCelular_pc").val("Cargando...");
        /***/
        /**
         * llamada ajax 
         */
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getDataPersonaFiscalizador',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idFiscalizador: jQuery('#jform_intIdPersonasFiscalizador_cgo').val()//url de la que se consumira el servicio
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            if (dataInfo[0]) {
                jQuery("#jform_strFiscaCedula_pc").val(dataInfo[0].cedula);
                jQuery("#jform_strFiscaRuc_fs").val(dataInfo[0].ruc);
                jQuery("#jform_strFiscaApellidos_pc").val(dataInfo[0].apellidos);
                jQuery("#jform_strFiscaNombres_pc").val(dataInfo[0].nombres);
                jQuery("#jform_strFiscaCorreoElectronico_pc").val(dataInfo[0].correo);
                jQuery("#jform_strFiscaTelefono_pc").val(dataInfo[0].telefono);
                jQuery("#jform_strFiscaCelular_pc").val(dataInfo[0].celular);
            } else {
                jQuery("#jform_strFiscaCedula_pc").val("");
                jQuery("#jform_strFiscaRuc_fs").val("");
                jQuery("#jform_strFiscaApellidos_pc").val("");
                jQuery("#jform_strFiscaNombres_pc").val("");
                jQuery("#jform_strFiscaCorreoElectronico_pc").val("");
                jQuery("#jform_strFiscaTelefono_pc").val("");
                jQuery("#jform_strFiscaCelular_pc").val("");
            }
        });
    });

    /**
     * 
     */
    jQuery("#jform_intIDProvincia_dpa").change(function( event, idCnt ) {
        jQuery("#jform_intIDCanton_dpa").html('<option value="0">' + COM_LIST_LOAD + '</option>');
        jQuery("#jform_intIDParroquia_dpa").html('<option value="0">' + JSL_SELECTED_PARROQUIA + '</option>');
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getCantonesContrato',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idProvincia: jQuery('#jform_intIDProvincia_dpa').val()
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;
            var items = [];
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_SELECTED_CANTON + '</option>');
                var selected = '';
                for (x = 0; x < numRegistros; x++) {
                    selected = ( idCnt > 0 && idCnt == dataInfo[x].id ) ? 'selected' : '';
                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }
            jQuery('#jform_intIDCanton_dpa').html(items.join(''));

            var idPrr = 0;
            if ( regUnidadTerritorial != 0 ) {
                data = getUnidadTerritorial(regUnidadTerritorial);
                if (data) {
                    idPrr = data.idParroquia;
                    if ( idPrr != 0 ) {
                        jQuery("#jform_intIDCanton_dpa").trigger("change", idPrr );
                    }
                }
            } 
            

        });
    });


    /**
     * carga de cantones.
     */
    jQuery("#jform_intIDCanton_dpa").change(function( event, idPrr) {
        jQuery("#jform_intIDParroquia_dpa").html('<option value="0">' + COM_LIST_LOAD + '</option>');
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: {
                action: 'getParroquias',
                option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                idCanton: jQuery('#jform_intIDCanton_dpa').val()
            },
            error: function(jqXHR, status, error) {
                alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;
            jQuery('#jform_intIDParroquia_dpa').html('');
            var items = [];
            var selected = '';
            if (numRegistros > 0) {
                items.push('<option value="0">' + JSL_SELECTED_PARROQUIA + '</option>');
                for (x = 0; x < numRegistros; x++) {
                    selected = ( idPrr > 0 && idPrr == dataInfo[x].id ) ? 'selected' : '';
                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + '>' + dataInfo[x].nombre + '</option>');
                }
            } else {
                items.push('<option value="0">' + JSL_SIN_REGISTROS + '</option>');
            }
            jQuery('#jform_intIDParroquia_dpa').html(items.join(''));
//            if (regUnidadTerritorial != 0) {
//                data = getUnidadTerritorial(regUnidadTerritorial);
//                if (data) {
//                    recorrerCombo(jQuery("#jform_intIDParroquia_dpa option"), data.idParroquia);
//                }
//
//            }
        });
    });
    /**
     * Gestion del reponsable
     */
    jQuery('#jform_intIdUGResponsable').live('change', function(event, idResponsable) {
        jQuery('#jform_idResponsable').html('<option value="0">' + COM_LIST_LOAD + '</option>');

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_contratos',
                view: 'contrato',
                tmpl: 'component',
                format: 'json',
                action: 'getResponsablesUG',
                idUndGestion: jQuery(this).val()
            },
            error: function(jqXHR, status, error) {
                alert(COM_INDICADORES_FUNCIONARIOS + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            if (numRegistros > 0) {
                var items = [];

                var selected = '';

                for (var x = 0; x < numRegistros; x++) {
                    if (typeof (idEntidad) != 'undefined') {
                        selected = (dataInfo[x].id == idEntidad) ? 'selected'
                                : '';
                    } else {
                        selected = (dataInfo[x].id == 0) ? 'selected'
                                : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" ' + selected + ' >' + dataInfo[x].nombre + '</option>');
                }
            }

            jQuery('#jform_idResponsable').html(items.join(''));
        })
    })

});
