jQuery( document ).ready(function(){
    Joomla.submitbutton = function( task )
    {
        if( task == 'variable.asignar' ){
            //  recorro los checkbox
            jQuery( 'input[type=checkbox]' ).each( function(){
                //  Verifico si ha sido seleccionado alguna variable
                if( jQuery( this ).is(":checked") ){
                    
                    //  Verifico si la variable a registrar no esta ya asignado al indicador 
                    if( !existeVariable( jQuery( this ).val(), jQuery('#idIndicador').val() ) ){
                        var dataVariable = new window.parent.variable;
                        var idVariable = jQuery( this ).val();
                        var nomVariable = recorrerTabla( idVariable );

                        dataVariable.idVariable = idVariable;
                        dataVariable.idIndicador = jQuery('#idIndicador').val();
                        dataVariable.nombre = nomVariable;
                        dataVariable.tipoVariable = jQuery('#idIndicador').val();

                        //  Agrego las variables pertenecientes a un determinado Indicador
                        window.parent.lstVariables.push( dataVariable );

                        //  Agrego una fila 
                        addVariable( dataVariable );
                    }else{
                        jAlert( 'Variable ya registrada, desea continuar', 'SIITA - ECORAE' );
                    }
                }
            })

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
    
    //  Verifico la existencia de una determinada variable
    function existeVariable( idVariable, idIndicador )
    {
        var lstVariables = window.parent.lstVariables;
        var numReg = lstVariables.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstVariables[x].idVariable == idVariable && lstVariables[x].idIndicador == idIndicador ){
                return true;
            }
        }
        
        return false;
    }
    
    //  Agrego la variable a la tabla de variables
    function addVariable( data )
    {
        //  Construyo la Fila
        //  tip: http://danmrichards.wordpress.com/2010/11/24/opening-a-joomla-lightbox-from-jquery-generated-html/
        
        var fila = "<tr id='"+ data.idVariable +"'>"
                    + " <td align='center'>"+ data.nombre +"</td>"
                    + " <td align='center'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=planificacionvariable&layout=edit&idIndicador="+ data.idIndicador +"&idVariable="+ data.idVariable +"&tmpl=component\", {size:{x:800,y:400}, handler:\"iframe\"});'> Planificacion </a> </td>"
                    + " <td align='center'> <a onclick='SqueezeBox.fromElement( \"index.php?option=com_proyectos&view=seguimientovariable&layout=edit&idIndicador="+ data.idIndicador +"&idVariable="+ data.idVariable +"&tmpl=component\", {size:{x:800,y:400}, handler:\"iframe\"});'> Seguimiento </a> </td>"
                    + " <td align='center'> <a class='delVariable'> Eliminar </a> </td>"
                +"  </tr>";

        //  Agrego la fila creada a la tabla
        jQuery( '#tb_'+ data.idIndicador +' > tbody:last', window.parent.document ).append( fila );
    }

    //  Recorro las filas de una tabla hasta una posicion (pos) determinada
    function recorrerTabla( pos )
    {
        var ban = false;
        var x;
        var nombreVar;

        jQuery( '.adminlist tbody tr' ).each( function(){
            jQuery(this).children( 'td' ).each(function() {
                var elemento = jQuery(this).find( 'input:checkbox:first' );
                
                if( elemento.length > 0 && elemento[0].value == pos ){
                    ban = true;
                    x = 0;
                }else if( ban == true ){
                    x = x + 1;
                }
                
                if( ban == true && x > 0 ){
                    nombreVar = jQuery.trim( jQuery( this ).text() );
                    ban = false;
                    x = 0;
                }
            })
        })
        
        return nombreVar;
    }
})