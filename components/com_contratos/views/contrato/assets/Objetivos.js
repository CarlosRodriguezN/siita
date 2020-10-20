jQuery( document ).ready( function(){
    
    var numObjetivo = objLstObjetivo.lstObjetivos.length;
    var banUpdObj = 0;
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    
    jQuery( '#btnAddObjetivo' ).live( 'click', function(){
        var objetivo = [];
        
        objetivo["idRegistro"] = ++numObjetivo;
        objetivo["idObjetivo"] = 0;
        objetivo["idTpoObj"] = 4;
        objetivo["descripcion"] = jQuery( '#jform_objEspecifico' ).val();
        objetivo["published"] = 1;

        if( banUpdObj == 0 ){
            //  Agrego objetivo
            addFilaObjetivos( objetivo );

            //  Agrego el objetivo especifico en la lista de objetivos
            objLstObjetivo.lstObjetivos.push( objetivo );
        }else{
            //  Actualizo el campo con informacion actualizada
            updFilaObjetivos( jQuery( '#jform_objEspecifico' ).val() );
        }

        //  Limpio contenido de Objetivo Especifico
        jQuery('#jform_objEspecifico').attr('value', '');
        jQuery('#frmObjetivoPry').css("display", "none");
        jQuery('#imgObjetivoPry').css("display", "block");
        
    });
    
    
    jQuery( '#jform_objGeneral' ).live( 'change', function(){
        var numReg = objLstObjetivo.lstObjetivos.length;
        var objGral = [];

        for( var x = 0; x < numReg; x++ ){
            if( objLstObjetivo.lstObjetivos[x]["idTpoObj"] == 3 ){
                objLstObjetivo.lstObjetivos[x]["descripcion"] = jQuery( this ).val();
                return true;
            }
        }
        
        objGral["idRegistro"] = ++numObjetivo;
        objGral["idObjetivo"] = 0;
        objGral["idTpoObj"] = 3;
        objGral["descripcion"] = jQuery( this ).val();
        objGral["published"] = 1;

        objLstObjetivo.lstObjetivos.push( objGral );

        return true;
    });
    
    
    //
    //  Agrego latitud y longitud de un punto de una determinada coordenada
    //
    function addFilaObjetivos( dataObjetivo )
    {
        //  Construyo la Fila
        var fila = "    <tr id='"+ dataObjetivo["idRegistro"] +"'>"
                    + "     <td align='center'>Objetivo Especifico</td>"
                    + "     <td align='center'>"+ dataObjetivo["descripcion"] +"</td>";

        if( roles["core.create"] === true || roles["core.edit"] === true ){
            fila+= "        <td align='center'> <a class='updObjetivo'>Editar</a> </td>"
                + "         <td align='center'> <a class='delObjetivo'>Eliminar</a> </td>";
        }else{
            fila+= "        <td align='center'> Editar </td>"
                + "         <td align='center'> Eliminar </td>";
        }

        fila += " </tr>";

        //  Agrego la fila creada a la tabla
        jQuery( '#lstObjEspecificos > tbody:last' ).append( fila );
    }


    //
    //  Gestiono la Edicion de un determinado objetivo
    //
    jQuery( '.updObjetivo' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var numReg = objLstObjetivo.lstObjetivos.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( objLstObjetivo.lstObjetivos[x]["idRegistro"] == idFila ){
                banUpdObj = 1;
                jQuery( '#jform_objEspecifico' ).attr( 'value', objLstObjetivo.lstObjetivos[x]["descripcion"] );
                jQuery( '#idObjetivo' ).attr( 'value', idFila );
            }
        }
    });
    
    // Gestiono el evento click en el boton agregar objetivo
    jQuery("#btnAddTableObjetivo").live("click", function() {
        banUpdObj=0;
        jQuery('#frmObjetivoPry').css("display", "block");
        jQuery('#imgObjetivoPry').css("display", "none");
    });


    function updFilaObjetivos( data )
    {
        var numReg = objLstObjetivo.lstObjetivos.length;
        for( var x = 0; x < numReg; x++ ){
            if( objLstObjetivo.lstObjetivos[x]["idRegistro"] == jQuery( '#idObjetivo' ).attr( 'value' ) ){
                banUpdObj = 0;
                objLstObjetivo.lstObjetivos[x]["descripcion"] = data;
                //  EnCero el valor
                jQuery( '#idObjetivo' ).attr( 'value', '' );
                updInfoFilaObjetivo( objLstObjetivo.lstObjetivos[x] );
            }
        }
    }
    
    //
    //  Actualizo informacion en a fila actualizada
    //
    function updInfoFilaObjetivo( dataUpd )
    {
        jQuery( '#lstObjEspecificos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == dataUpd["idRegistro"] ){
                
                //  Agrego color a la fila actualizada
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
                //  Construyo la Fila
                var fila = "    <td align='center'>Objetivo Especifico</td>"
                            + " <td align='center'>"+ dataUpd["descripcion"] +"</td>"
                            + " <td align='center'> <a class='updObjetivo'>Editar</a> </td>"                
                            + " <td align='center'> <a class='delObjetivo'>Eliminar</a> </td>";

                jQuery( this ).html( fila );
            }
        });
    }

    jQuery( '.delObjetivo' ).click( function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var numReg = objLstObjetivo.lstObjetivos.length;
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Objetivo", "SIITA - ECORAE", function( result ){
            if( result ) {
                for( var x = 0; x < numReg; x++ ){
                    if( objLstObjetivo.lstObjetivos[x]["idRegistro"] == idFila ){
                        objLstObjetivo.lstObjetivos[x]["published"] = 0;
                        delFilaObjetivo( objLstObjetivo.lstObjetivos[x]["idRegistro"] );
                    }
                }
            } 
        });
    })
    
    //
    //  Busco y Elimino fila de la tabla de GAP
    //
    function delFilaObjetivo( idObjetivo )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstObjEspecificos tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idObjetivo ){
                jQuery( this ).remove();
            }
        });
    }
    
    jQuery( '#btnLimpiarObjetivo' ).click( function(){
        //  enCero bandera de actualizacion
        banUpdObj = 0;
        
        //  Cambio el nombre del Boton
        jQuery( '#btnAddObjetivo' ).attr( 'value', 'Agregar Objetivo Especifico' );
        
        //  Limpio contenido de Objetivo Especifico
        jQuery( '#jform_objEspecifico' ).attr( 'value', '' );
        
        //  EnCero el campo oculto para identificar objetivo
        jQuery( '#idObjetivo' ).attr( 'value', '' );
    })
})