var dataMapa = new Array();
var nameFile = null;

jQuery( document ).ready(function(){
    
    /*
     *
     */
    function getLstCapas(){
        //  Lista de Capas Seleccionadas
        var lstCapas = [];
        var i = 0;
        //  Selecciono las Capas seleccionadas
        jQuery( '.chkCapas' ).each( function(){
            var capa = [];
            var dataCapa = jQuery( this ).val().split( '-' );
            capa["intCodigoMapLayers"] = dataCapa[0];//0-> cuando es nuevo ; n-> cuando se esta editando
            capa["name"] = dataCapa[1];
            capa["title"] = dataCapa[2];
            capa["checked"] =( jQuery( this ).is( ':checked' )) ? 1 
            : 0;
            jQuery( this ).removeAttr( "checked" );//de quita para que no se envie los datos con el form
            lstCapas[i++] = capa;
        })
        return lstCapas;
    }
    
    
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
    
    /*
     *@name Joomla.submitbutton
     *@return true cuando todo se ha cumplido correctamente.
     *@type boolean
     */
    Joomla.submitbutton = function( task )
    {
        jQuery( '#imageUpload' ).uploadify( 'upload', '*' );
        
        if( task == 'mapa.save' ){
            var capas = new Array();
            capas["capas"] = getLstCapas();
            //  Cambio la estructura del Objeto a Notacion JSON
            
            var info = JSON.stringify( list2Object( capas ) );
            //  Asigno esta Informacion a una Variable del formulario
            jQuery( '#jform_dataLayer' ).attr( 'value', info );
            
             
             
        }
        Joomla.submitform( task );
        return false;
    };
    
    
})

