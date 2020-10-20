jQuery(document).ready(function() {
//  Bandera que verifica si hay archivos que cargar
    banIconoExiste = 0;
    banImagenesExiste = 0;
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    
    jQuery('#iconoProyecto').uploadifive({
        'height'        : 15,
        'width'         : 150,
        'auto'          : false,
        'dnd'           : false,
        'fileSizeLimit' : 2048,
        'multi'         : false,
        'uploadScript'  : 'index.php',
        'fileObjName'   : 'iconoPry',
        'buttonText'    : 'Seleccione Icono',
        'queueSizeLimit': 1,
        
       'onSelect': function(file) {
            banIconoExiste = 1;
        },

        'onUploadComplete': function(file, data) {
            if ( banIconoExiste && !banImagenesExiste){
                 location.href = 'http://' + window.location.host + '/index.php?option=com_proyectos&view=proyectos';
            }
        },
        'onUploadFile': function(file) {
            alert('El archivo ' + file.name + ' esta en proceso de carga.');
        },
        'onAddQueueItem': function(file) {
            var data = {
                nameArchivo: file.name,
                file: file,
                published: 1,
                regArchivo: lstImagenes.length
            };
            addFilaIcono(data);
        }
    });
    
    /**
     *Imagenes del proyecto 
     */
    jQuery('#imgsProyecto').uploadifive({
        'auto'          : false,
        'buttonText'    : 'Seleccione Imagen',
        'dnd'           : false,
        'fileSizeLimit' : 2048,
        'width'         : 150,
        'height'        : 15,
        'queueSizeLimit': 5,
        'multi'         : true,
        'fileType'      : ['image\/png', 'image\/jpg', 'image\/jpeg'],
        'fileObjName'   : "imagesPry",
        'uploadScript'  : 'index.php',

        'onSelect': function(file) {
            banImagenesExiste = 1;
        },
        'onUploadComplete': function(file, data) {
            if ( !banIconoExiste && banImagenesExiste){
                 location.href = 'http://' + window.location.host + '/index.php?option=com_proyectos&view=proyectos';
            }
        },
        'onUploadFile': function(file) {
            alert('El archivo ' + file.name + ' se esta en proceso de carga.');
        },
        'onAddQueueItem': function(file) {
            var data = {
                nameArchivo: file.name,
                published: 1,
                regArchivo: lstImagenes.length
            };
            lstImagenes.push(data);
            addFilaArchivo(data);
        }
    });


    function addFilaIcono(data)
    {
        //  Construyo la Fila
        var fila = '';
        fila += '<tr id="1">';
        fila += '   <td align="center">' + data.nameArchivo + "</td>";
        fila += '   <td align="center"><a href="#">' + JES_VER + '</a></td>;';
        
        if( roles["core.create"] === true || roles["core.edit"] === true ){
            fila += '   <td align="center"><a href="#">' + JES_DOWNLOAD + '</a></td>;';
            fila += '   <td align="center" class="elimIcono"><a href="#">' + JES_ELIMINAR + '</a></td>';
        }else{
            fila += '   <td align="center">' + JES_DOWNLOAD + '</td>;';
            fila += '   <td align="center">' + JES_ELIMINAR + '</td>';
        }
        
        fila += '</tr>';

        //  Agrego la fila creada a la tabla
        jQuery("#lstIconos > tbody").append(fila);
    }
    /**
     *  Eliminar una imagen
     *  
     */
    jQuery(".elmIcon").live("click", function() {
        //  Emito un mensaje de confirmacion antes de eliminar registro
        var regDelArchivo = this.parentNode.parentNode.id;
        var url = window.location.href;
        var path = url.split('?')[0];
        
        jConfirm( ELIMINAR_ICON, SIITA_ECORAE, function( result ) {
            if (result) {
                jQuery.ajax({
                    type: 'GET',
                    url: path,
                    dataType: 'JSON',
                    data: { action      : 'deleteIcon',
                            option      : 'com_proyectos',
                            view        : 'proyecto',
                            tmpl        : 'component',
                            format      : 'json',
                            idProyecto  : jQuery('#jform_intCodigo_pry').val()
                    },
                    error: function(jqXHR, status, error) {
                        alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
                    }
                }).complete(function( data ){ //función que se ejecuta cuando llega una respuesta.
                    var rst = String( data.responseText ).trim();
                    if( rst === "true" ){
                        delArchivoIcon(regDelArchivo);
                    }else{
                        jAlert( 'Archivo NO existe, favor consultar con la administracion del sistema', 'SIITA - ECORAE' );
                    }

                });
            }
        });
    });
    /**
     * Eliminar ina imagen
     */
    jQuery(".elmImagen").live("click", function() {

//  Emito un mensaje de confirmacion antes de eliminar registro
        var regArchivoDel = this.parentNode.parentNode.id;
        var url = window.location.href;
        var path = url.split('?')[0];
        jConfirm(ELIMINAR_IMAG, SIITA_ECORAE, function(result) {
            if (result) {
                jQuery.ajax({
                    type: 'GET',
                    url: path,
                    dataType: 'JSON',
                    data: {
                        action: 'deleteImagen',
                        option: 'com_proyectos',
                        view: 'proyecto',
                        tmpl: 'component',
                        format: 'json',
                        idProyecto: jQuery('#jform_intCodigo_pry').val(),
                        nmbArchivo: getArchivoName(regArchivoDel)
                    },
                    error: function(jqXHR, status, error) {
                        alert('Administracion de contratos: ' + error + ' ' + jqXHR + ' ' + status);
                    }
                }).complete(function(data) {//función que se ejecuta cuando llega una respuesta.
                    delArchivoImagen(regArchivoDel);
                });
            }
        });
    });
});
/**
 * 
 * @param {type} regArchivo
 * @returns {String} */
function getArchivoName(regArchivo) {
    var name = '';
    for (var j = 0; j < lstImagenes.length; j++) {
        if (lstImagenes[j].regArchivo == regArchivo)
            name = lstImagenes[j].nameArchivo;
    }
    return name;
}
/**
 * 
 * @returns {undefined}
 */
function reloadDocumentTable() {
    jQuery("#lstImagenes > tbody").empty();
    for (var j = 0; j < lstImagenes.length; j++) {
        if (lstImagenes[j].published == 1)
            addFilaArchivo(lstImagenes[j]);
    }
    ;
}
/**
 * 
 * @param {type} data
 * @returns {undefined}
 */
function addFilaArchivo(data) {
    var idProyecto = jQuery('#jform_intCodigo_pry').val();
    var aHref = window.location.href + '/components/com_proyectos/images/' + idProyecto + '/icon/' + idProyecto + '/' + data.nameArchivo;
    var row = '';
    row += '<tr id="' + data.regArchivo + '">';
    row += ' <td align="center">' + data.nameArchivo + '</td>';
    row += ' <td align="center"><a class="modal" href="' + aHref + '">' + JES_VER + '</a></td>';
    
    if( roles["core.create"] === true || roles["core.edit"] === true ){
        row += ' <td align="center"><a href="' + aHref + '">' + JES_DOWNLOAD + '</a></td>';
        row += ' <td align="center"><a class="elmImagen" href="#">' + JES_ELIMINAR + '</a></td>';
    }else{
        row += ' <td align="center">' + JES_DOWNLOAD + '</td>';
        row += ' <td align="center">' + JES_ELIMINAR + '</td>';
    }

    row += '</tr>';
    jQuery('#lstImagenes > tbody:last').append(row);
}
/**
 * 
 * @param {int} regDocumento
 * @returns {undefined}
 */
function delArchivoImagen(regDocumento) {
    jQuery("#lstImagenes > tbody").empty();
    for (var j = 0; j < lstImagenes.length; j++) {
        if (lstImagenes[j].regArchivo == regDocumento) {
            lstImagenes[j].published = 0;
        }
    }
    reloadDocumentTable();
}


function delArchivoIcon(regDocumento){
    jQuery("#lstIconos > tbody").empty();

    for (var j = 0; j < lstImagenes.length; j++) {
        if (lstImagenes[j].regArchivo == regDocumento) {
            lstImagenes[j].published = 0;
        }
    }
    reloadDocumentTable();
}
