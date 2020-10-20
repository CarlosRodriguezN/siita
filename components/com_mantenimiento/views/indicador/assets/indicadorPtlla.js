jQuery( 'document' ).ready( function(){
    
    var lstVar = eval( jQuery( '#jform_lstVIP' ).val() );
    var dtaGralInd;

    if( lstVar.length > 0 ){
        setDtaVariables()
    }
    
    /**
     * 
     * Seteo Informacion de Variables
     * 
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * 
     * @returns {undefined}
     * 
     */
    function setDtaVariables()
    {
        lstTmpVar = new Array();
        var objVar;

        for( var x = 0; x < lstVar.length; x++ ){
            objVar = new Variable();
            lstVar[x].idRegVar = x;
            objVar.setDtaVariable( lstVar[x] );

            //  Agrego una fila a la tabla de Variables
            jQuery( '#lstVarIndicadores > tbody:last' ).append( objVar.addFilaVar( 0 ) );
            lstTmpVar.push( objVar );
        }

        return false;
    }
    
    
    function dtaGralIndicador()
    {
        var dtaInd = [];
        
        dtaInd["intId_pi"]          = jQuery( '#jform_intId_pi' ).val();
        dtaInd["strNombre_pi"]      = jQuery( '#jform_strNombre_pi' ).val();
        dtaInd["strDescripcion_pi"] = jQuery( '#jform_strDescripcion_pi' ).val();
        dtaInd["inpCodigo_claseind"]= jQuery( '#jform_inpCodigo_claseind' ).val();
        dtaInd["inpCodigo_unianl"]  = jQuery( '#jform_inpCodigo_unianl' ).val();
        dtaInd["intIdTpoUndMedida"] = jQuery( '#jform_intIdTpoUndMedida' ).val();
        dtaInd["intCodigo_unimed"]  = jQuery( '#jform_intCodigo_unimed' ).val();
        dtaInd["formulaDescripcion"]= jQuery( '#formulaDescripcion' ).val();
        
        return dtaInd;
    }
    
    
    
    Joomla.submitbutton = function(task)
    {
        
        switch (task) {
            
            case 'indicador.add':
                gestionIndicadorPlantilla();
            break;
            
            default:
                Joomla.submitform(task);
            break;                
        }
    }
    
    
    /**
     * 
     * Transforma un Array en Objecto de manera Recursiva
     * 
     * @param {type} list       Arreglo con informacion a transformar a JSon
     * @returns {unresolved}
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
    
    
    function gestionIndicadorPlantilla()
    {
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        //  Cambio array Datos Generales a Notacion JSON
        var dtaGI       = JSON.stringify( list2Object( dtaGralIndicador() ) );
        var dtaVariables= JSON.stringify( list2Object( lstTmpVar ) );

        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data: { method      : 'POST',
                                option      : 'com_mantenimiento',
                                view        : 'indicador',
                                tmpl        : 'component',
                                task        : 'indicador.gestionIndicadorPlantilla',

                                dtaIndicador: dtaGI,
                                dtaVariables: dtaVariables
                        },

                        error: function(jqXHR, status, error) {
                            alert('Mantenimiento - Registro de Plantilla de Indicadores: ' + error + ' ' + jqXHR + ' ' + status);
                        }
        }).complete( function( data ){
            if( parseInt( data.responseText ) > 0 ){
                location.href = 'http://' + window.location.host + '/index.php?option=com_mantenimiento&view=indicadores';
            }
        });

    }

})