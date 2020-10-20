jQuery(document).ready(function () {
    banShape = false;
    banShapeComplete = false;

    banImagenMapa = false;
    banImagenMapaComplete = false;

    banTextoMapa = false;
    banTextoMapaComplete = false;

    //  Gestion de Carga de Shape
    jQuery('#shape_upload').uploadifive({
        'auto': false,
        'buttonText': 'Seleccione Shape',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'uploadScript': 'index.php',
        'onSelect': function (file) {
            banShape = true;
        },
        'onUploadFile': function (file) {
            alert('El archivo ' + file.name + ' is being uploaded.');
        },
        'onUploadComplete': function (file, data) {
            banShapeComplete = true;
            redireccionamiento();
        }

    });

    //  Gestion de Carga de Imagenes
    jQuery('#image_upload').uploadifive({
        'auto': false,
        'buttonText': 'Seleccione Imagen',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'fileType': ['image\/gif', 'image\/jpeg', 'image\/png'],
        'uploadScript': 'index.php',
        'onSelect': function (file) {
            banImagenMapa = true;
        },
        'onUploadFile': function (file) {
            alert('El archivo ' + file.name + ' is being uploaded.');
        },
        'onUploadComplete': function (file, data) {
            banImagenMapaComplete = true;
            redireccionamiento();
        }

    });

    //  Gestion de Carga de Archivo de texto
    jQuery('#texto_upload').uploadifive({
        'auto': false,
        'buttonText': 'Seleccione Archivo',
        'dnd': false,
        'fileSizeLimit': 2048,
        'width': 150,
        'queueSizeLimit': 1,
        'multi': false,
        'uploadScript': 'index.php',
        'onSelect': function (file) {
            banTextoMapa = true;
        },
        'onUploadFile': function (file) {
            alert('El archivo ' + file.name + ' inicia su carga.');
        },
        'onUploadComplete': function (file, data) {
            banImagenMapaComplete = true;
            redireccionamiento();
        }

    });


    function redireccionamiento()
    {
        var ban = false;

        switch (true) {

            //  Images
            case(banImagenMapaComplete && banShape === false && banTextoMapa === false):
                ban = true;
                break

                //  Shape
            case(banShapeComplete && banImagenMapa === false && banTextoMapa === false):
                ban = true;
                break;

                //  GeoServicios
            case(banTextoMapaComplete && banShape === false && banImagenMapa === false):
                ban = true;
                break;

            case(banImagenMapaComplete && banShapeComplete && banTextoMapa === false):
                ban = true;
                break;

            case(banImagenMapaComplete && banTextoMapaComplete && banShape === false):
                ban = true;
                break;

            case(banImagenMapaComplete && banShapeComplete && banTextoMapaComplete):
                ban = true;
            break;
        }

        if (ban) {
            location.href = 'http://' + window.location.host + '/index.php?option=com_adminmapas&view=ecoraemapas';
        }

        return;
    }

});