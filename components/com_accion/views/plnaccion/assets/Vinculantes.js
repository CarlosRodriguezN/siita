jQuery(document).ready(function() {

    //
    //  Gestiona el retorno de los cantones de una determinada provincia
    //
    jQuery('#jform_unidad_gestion').change(function(event, idFunUG) {

        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#jform_intId_ugf').html('<option value="0">Cargando...</option>');

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_accion',
                view: 'plnaccion',
                tmpl: 'component',
                format: 'json',
                action: 'getResponsables',
                idUnidadGestion: jQuery('#jform_unidad_gestion').val()
            },
            error: function(jqXHR, status, error) {
                alert('Plan Estrategico Istitucional - Gestión Objetivos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval(data.responseText);
            var numRegistros = dataInfo.length;

            var items = [];

                var selected = '';

                for( var x = 0; x < numRegistros; x++ ){
                    if( typeof( idFunUG ) != 'undefined' && idFunUG != '' ){
                        selected = ( dataInfo[x].id == idFunUG )? 'selected'
                                                                : '';
                    }else{
                        selected = ( dataInfo[x].id == 0 )  ? 'selected'
                                                            : '';
                    }

                    items.push('<option value="' + dataInfo[x].id + '" '+ selected +'>' + dataInfo[x].nombre + '</option>');
                }

            jQuery('#jform_intId_ugf').html(items.join(''));
        });
    });
});


