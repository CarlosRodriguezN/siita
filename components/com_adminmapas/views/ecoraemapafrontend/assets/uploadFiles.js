jQuery( document ).ready(function(){
    jQuery('#image_upload').uploadify({
        'auto'              : false,
        'multi'             : true,
        'height'            : 25,
        'removeCompleted'   : false,
        'swf'               : 'media/system/swf/uploadify/uploadify.swf',
        'uploader'          : 'index.php',
        'buttonText'        : 'Seleccione Imagen',
        'fileTypeExts'      : '*.png',
        'uploadLimit'       : 1,
        'debug'             : true,
        
        'onUploadError'     : function( file, errorCode, errorMsg, errorString ) {
           // alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        },
        
        'onUploadComplete'  : function( file ){
          //  alert( 'Archivo 111111'+ file.name + 'Cargado con Exito' );
        }
    })
    
    jQuery('#shape_upload').uploadify({
        'auto'              : false,
        'multi'             : true,
        'height'            : 25,
        'removeCompleted'   : false,
        'swf'               : 'media/system/swf/uploadify/uploadify.swf',
        'uploader'          : 'index.php',
        'buttonText'        : 'Seleccione Shape',
        'fileTypeExts'      : '*.zip',
        'uploadLimit'       : 1,
        'debug'             : true,
        
        'onUploadError'     : function( file, errorCode, errorMsg, errorString ) {
          alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        },
        
        'onUploadComplete'  : function( file ){
        
        }
    })
    //    
    /*
     *@name list2Object
     *@description Trasforma un array a un JSON.
     *@param list 
     *@type JSON
     */
    function list2Object( list )
    {
        var obj = {};
        for( key in list ){
            if( typeof( list[key] ) == 'object' ){
                obj[key] = list2Object( list[key] );
            }else{
                obj[key] = list[key];
            }
        }
        return obj;
    }

    Joomla.submitbutton = function( task ){
        if( task == 'ecoraemapa.save' ){
            var lstData = [];
            
            lstData["strNombre"] = jQuery( '#jform_strNombre' ).val();
            lstData["strDescripcion"] = jQuery( '#jform_strDescripcion' ).val();
            lstData["strCopyright"] = jQuery( '#jform_strCopyright' ).val();
            
            var options = {
                "option" : "com_adminmapas", 
                "controller" : "ecoraemapa",
                "task":"ecoraemapa.saveFiles",
                "tmpl":"component",
                "typeFileUpl":"images",
                "dataFrm": JSON.stringify( list2Object( lstData ) )
            };
            
            jQuery( '#image_upload' ).uploadify( 'settings', 'formData', options );
            jQuery( '#image_upload' ).uploadify( 'upload', '*' );
            
            var optionsShape = {
                "option" : "com_adminmapas", 
                "controller" : "ecoraemapa",
                "task":"ecoraemapa.saveFiles",
                "tmpl":"component",
                "typeFileUpl":"shapes"
            };
            
            jQuery( '#shape_upload' ).uploadify( 'settings', 'formData', optionsShape );
            jQuery( '#shape_upload' ).uploadify( 'upload', '*' );
                        
            //Joomla.submitform( task );
            //            jQuery( '#shape_upload' ).uploadify( 'upload', '*' );

            return false;
        }else{
        }
        return false;
    }
})