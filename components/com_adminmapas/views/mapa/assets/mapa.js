var dataMapa = new Array();
var nameFile = null;

jQuery(document).ready(function () {
    jQuery('#btnLayersMapa').click(function () {
        var url = window.location.href;
        var path = url.split('?')[0];

        jQuery('#infoCapas').html('Cargando...');

        //  Llamada ajax que permite cargar las capas de una URL 
        jQuery.ajax({
            type: 'GET',
            url: path,
            dataType: 'JSON',
            data: { option  : 'com_adminmapas',
                    view    : 'mapa',
                    tmpl    : 'component',
                    format  : 'json',
                    action  : 'getLayers',
                    url     : jQuery('#jform_strURLService').val()
            },
            error: function (jqXHR, status, error) {
                alert('Administracion de Mapas: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function (data) {
            //    Función que se ejecuta cuando llega una respuesta.
            var dataInfo = eval('(' + data.responseText + ')');
            var items = [];
            var numRegistros = dataInfo.length;

            if (numRegistros > 0) {
                for (var x = 0; x < numRegistros; x++) {
                    items.push('<div class="clr"></div><div><input type="checkbox" checked="checked" class="chkCapas" name="chkCapas[]" value="0-' + dataInfo[x].name + '-' + dataInfo[x].title + '">' + dataInfo[x].title + '</div>');
                }
            } else {
                items.push('Sin registros disponibles');
            }

            jQuery('#infoCapas').html(items.join(''));
        });
    })

    /*
     *
     */
    function getLstCapas() {
        //  Lista de Capas Seleccionadas
        var lstCapas = [];
        var i = 0;
        //  Selecciono las Capas seleccionadas
        jQuery('.chkCapas').each(function () {
            var capa = [];
            var dataCapa = jQuery(this).val().split('-');

            capa["intCodigoMapLayers"] = dataCapa[0];//0-> cuando es nuevo ; n-> cuando se esta editando
            capa["name"] = dataCapa[1];
            capa["title"] = dataCapa[2];
            capa["checked"] = (jQuery(this).is(':checked'))
                    ? 1
                    : 0;

            jQuery(this).removeAttr("checked");//de quita para que no se envie los datos con el form
            lstCapas.push(capa);
        });

        return lstCapas;
    }


    /*
     *@name list2Object
     *@description Trasforma un array a un JSON.
     *@param list 
     *@type JSON
     */
    function list2Object(list)
    {
        var obj = {};
        for (key in list) {
            if (typeof (list[key]) == 'object') {
                obj[key] = list2Object(list[key]);
            } else {
                obj[key] = list[key];
            }
        }
        return obj;
    }

    /*
     *@name Joomla.submitbutton
     *@return true cuando todo se ha cumplido correctamente.
     *@type boolean
     */
    Joomla.submitbutton = function (task)
    {
        switch (task) {
            case 'mapa.save':
                var capas = new Array();
                capas["urlFuente"] = jQuery('#jform_urlsmapas').val();
                capas["capas"] = getLstCapas();

                //  Cambio la estructura del Objeto a Notacion JSON
                var info = JSON.stringify(list2Object(capas));

                //  Asigno esta Informacion a una Variable del formulario
                jQuery('#jform_dataLayer').attr('value', info);

                Joomla.submitform(task);
                break;


            case 'ecoraemapa.save':
                jQuery.blockUI({message: jQuery('#msgProgress')});

                //  Obtengo URL completa del sitio
                var url = window.location.href;
                var path = url.split('?')[0];
                var lstData = [];

                lstData["intCodigo_wms"] = jQuery('#jform_intCodigo_wms').val();
                lstData["strNombre"] = jQuery('#jform_strNombre').val();
                lstData["strDescripcion"] = jQuery('#jform_strDescripcion').val();
                lstData["strCopyright"] = jQuery('#jform_strCopyright').val();
                lstData["strURLService"] = jQuery('#jform_strURLService').val();
                lstData["intTpoMapa"] = 0;
                lstData["published"] = jQuery('#jform_published').val();

                //  Llamada AJAX para guardar la data
                jQuery.ajax({type: 'POST',
                    url: path,
                    dataType: 'JSON',
                    data: {option: 'com_adminmapas',
                        view: 'ecoraemapa',
                        tmpl: 'component',
                        format: 'json',
                        action: 'save',
                        jDataFormEcoMap: JSON.stringify(list2Object(lstData)),
                        dtaLayers: JSON.stringify(list2Object(getLstCapas()))
                    },
                    error: function (jqXHR, status, error) {
                        jQuery.unblockUI();
                        alert('Mapas - Registro de Mapas: ' + error + ' ' + jqXHR + ' ' + status);
                    }
                }).complete(function (data) {

                    if (banImagenMapa || banShape || banTextoMapa) {

                        // para subir la Imagen
                        if (banImagenMapa) {
                            var options = {"option": "com_adminmapas",
                                "controller": "ecoraemapa",
                                "task": "ecoraemapa.saveFiles",
                                "view": 'ecoraemapa',
                                "tmpl": "component",
                                "typeFileUpl": "images",
                                "fileObjName": "images",
                                "idMapEcorae": data.responseText
                            };
                            jQuery('#image_upload').data('uploadifive').settings.formData = options;
                            jQuery('#image_upload').uploadifive('upload');
                        }

                        // para subir el SHAPE
                        if (banShape) {
                            var optionsShape = {"option": "com_adminmapas",
                                "controller": "ecoraemapa",
                                "task": "ecoraemapa.saveFiles",
                                "tmpl": "component",
                                "typeFileUpl": "shapes",
                                "fileObjName": "shapes",
                                "idMapEcorae": data.responseText
                            };

                            jQuery('#shape_upload').data('uploadifive').settings.formData = optionsShape;
                            jQuery('#shape_upload').uploadifive('upload');
                        }

                        // para subir el SHAPE
                        if (banTextoMapa) {
                            var optionsShape = {"option": "com_adminmapas",
                                "controller": "ecoraemapa",
                                "task": "ecoraemapa.saveFiles",
                                "tmpl": "component",
                                "typeFileUpl": "geoservicio",
                                "fileObjName": "geoservicio",
                                "idMapEcorae": data.responseText
                            };

                            jQuery('#texto_upload').data('uploadifive').settings.formData = optionsShape;
                            jQuery('#texto_upload').uploadifive('upload');
                        }
                    }else{
                        location.href = 'http://' + window.location.host + '/index.php?option=com_adminmapas&view=ecoraemapas';
                    }

                });

                break;

            default:
                Joomla.submitform(task);
                break;
        }
    };

});

