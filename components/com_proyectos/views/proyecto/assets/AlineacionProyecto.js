jQuery( document ).ready( function(){
    var banMN = 0;
    var nmn = lstAlineacionProyecto.length;
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    
    //  Gestiona el agregar 
    jQuery( '#btnAddRelacion' ).click(function(){
        var objetivo = jQuery( '#jform_intcodigo_on' );
        var politica = jQuery( '#jform_intcodigo_pn' );
        var meta = jQuery( '#jform_idcodigo_mn' );
        
        var banExiste = existeAlcance( objetivo, politica, meta );
        var banValido = validaAlcance( objetivo, politica, meta );
        
        //  verifico si el alcance no esta ya registrado 
        //  y la informacion de objetivos, politicas y metas 
        //  hayan sido seleccionadas
        if( banExiste == false && banValido == true ){
            var objetivoTxt = jQuery( '#jform_intcodigo_on :selected' );
            var politicaTxt = jQuery( '#jform_intcodigo_pn :selected' );
            var metaTxt = jQuery( '#jform_idcodigo_mn :selected' );

            if( banMN == 0 ){
                var lstRelacion = [];
                lstRelacion["idRegistro"] = ++nmn;
                lstRelacion["idMetaNacional"] = meta.val();
                lstRelacion["idPoliticaNacional"] = politica.val();
                lstRelacion["idObjNacional"] = objetivo.val();
                lstRelacion["published"] = 1;

                //  Agrego fila en la tabla alineacion
                addFilaAlineacion( nmn, objetivoTxt, politicaTxt, metaTxt );

                lstAlineacionProyecto.push( lstRelacion );
            }else{
                //  Actualizo informacion editada
                updAlineacion( meta );
            }
        }else{
            switch( true ){
                case( banExiste == true && banValido == true ):
                    jAlert( 'Alineacion Existente', 'SIITA - ECORAE' );
                break;

                case( banExiste == false && banValido == false ):
                    jAlert( 'Revisar datos de Alineacion' );
                break;

                default:
                    jAlert( banExiste+ '' + banValido );
                    break;
            }
        }
        jQuery('#frmAlineacion').css("display", "none");
        jQuery('#imgAlineacion').css("display", "block");
        
    });
    
    // gestiono el evento del boton agregar.
    jQuery("#btnAdTableRelacion").live("click",function (){
        jQuery('#frmAlineacion').css("display", "block");
        jQuery('#imgAlineacion').css("display", "none");
    });
    
    function existeAlcance( objetivo, politica, meta )
    {
        var numReg = lstAlineacionProyecto.length;
        for( var x = 0; x < numReg; x++ ){
            if( lstAlineacionProyecto[x]["idObjNacional"] == objetivo.val() && lstAlineacionProyecto[x]["idPoliticaNacional"] == politica.val() && lstAlineacionProyecto[x]["idMetaNacional"] == meta.val() )
                return true;
        }
        
        return false;
    }
    
    function validaAlcance( objetivo, politica, meta )
    {
        var dtaObj = objetivo.val(); 
        var dtaPolitica = politica.val(); 
        var dtaMeta = meta.val(); 
        
        if( dtaObj != '0' && dtaPolitica != '0' && dtaMeta != '0' ){
            return true;
        }
        
        return false;
    }
    
    
    function updAlineacion( meta )
    {
        var numReg = lstAlineacionProyecto.length;
        var idFilaAlineacion = jQuery( '#idAlineacion' ).val();
        for( var x = 0; x < numReg; x++ ){
            if( lstAlineacionProyecto[x]["idRegistro"] == idFilaAlineacion ){
                banMN = 0;
                lstAlineacionProyecto[x]["idMetaNacional"] = jQuery( '#jform_idcodigo_mn' ).val();
                updFilaAlineacion( idFilaAlineacion );
                
                jQuery( '#idAlineacion' ).attr( 'value', '' );
            }
        }
            
    }
    
    function updFilaAlineacion( idFilaAlineacion )
    {
        jQuery( '#tbLstAlineacion tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFilaAlineacion ){
                
                //  Agrego color a la fila actualizada
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
                var objetivoTxt = jQuery( '#jform_intcodigo_on :selected' );
                var politicaTxt = jQuery( '#jform_intcodigo_pn :selected' );
                var metaTxt = jQuery( '#jform_idcodigo_mn :selected' );
                
                //  Construyo la Fila
                var filaAlineacion = "  <td align='center'>"+ objetivoTxt.text() +"</td>"
                                    + " <td align='center'>"+ politicaTxt.text() +"</td>"
                                    + " <td align='center'>"+ metaTxt.text() +"</td>"
                                    + " <td align='center'> <a class='updAlineacion'> Editar </a> </td>"
                                    + " <td align='center'> <a class='delAlineacion'> Eliminar </a> </td>";

                jQuery( this ).html( filaAlineacion );
            }
        })  
    }
    
    
    jQuery( '#btnLimpiarRelacion' ).click( function(){
        limpiarFrmAlineacion();
        
         //  Cambio la Etiqueta de los botones.
        jQuery('#btnAddRelacion').attr( "value", "Agregar Alineacion" );
    })
    
    
    //
    //  Agrega una fila a la tabla de alineaciones
    //
    function addFilaAlineacion( idRegistro, objetivo, politica, meta )
    {
        //  Construyo la Fila
        var filaAlineacion = "  <tr id='"+ idRegistro +"'>"
                        + "         <td align='center'>"+ objetivo.text() +"</td>"
                        + "         <td align='center'>"+ politica.text() +"</td>"
                        + "         <td align='center'>"+ meta.text() +"</td>";

        if( roles["core.create"] === true || roles["core.edit"] === true ){
            filaAlineacion  += "        <td align='center'> <a class='updAlineacion'> Editar </a> </td>"
                            + "         <td align='center'> <a class='delAlineacion'> Eliminar </a> </td>"
                            + "     </tr>";
        }else{
            filaAlineacion  += "        <td align='center'> Editar </td>"
                            + "         <td align='center'> Eliminar </td>"
                            + "     </tr>";
        }

        //  Agrego la fila creada a la tabla
        jQuery( '#tbLstAlineacion > tbody:last' ).append( filaAlineacion );
        
        //  Regreso a su posicion inicial los combos del formulario
        limpiarFrmAlineacion();
    }
    
    //  Limpia formulario de alineacion de un proyecto
    function limpiarFrmAlineacion()
    {
        //  Recorro a la posicion inicial el combo Objetivos Nacionales
        recorrerCombosAlineacion( jQuery( '#jform_intcodigo_on option' ), 0 );
        
        //  Recorro a la posicion inicial el combo Politica Nacional
        recorrerCombosAlineacion( jQuery( '#jform_intcodigo_pn option' ), 0 );
        
        //  Recorro a la posicion inicial el combo Metas Nacionales
        recorrerCombosAlineacion( jQuery( '#jform_idcodigo_mn option' ), 0 );
        
        //  Habilito combo de Objetivos Nacionales  
        jQuery('#jform_intcodigo_on').removeAttr( 'disabled' );
            
        //  Habilito combo de Plan Nacional
        jQuery('#jform_intcodigo_pn').removeAttr( 'disabled' );
        
         //  Cambio la Etiqueta de los botones.
        jQuery('#btnAddRelacion').attr( "value", "Agregar Alineacion" );
    }
    
    //  Recorre combos de alineacion a su posicion inicial
    function recorrerCombosAlineacion( combo, posicion )
    {
        jQuery( combo ).each( function(){
            if( jQuery( this ).val() == posicion ){
                jQuery( this ).attr( 'selected', 'selected' );
            }
        })
    }
    
    //  Gestiono la alineacion de un proyecto
    jQuery( '.updAlineacion' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Cambio la Etiqueta de los botones.
        jQuery('#btnAddRelacion').attr( "value", "Editar Alineacion" );
        
        //  Actualizo bandera de edicion a 1
        banMN = 1;
        
        //  Obtengo datos de alineacion de un proyecto
        var data = getDataAlineacion( idFila );

        if( data ){
            //  Recorro el combo de Objetivos
            recorrerCombosAlineacion( jQuery('#jform_intcodigo_on option'), [data["idObjNacional"]] );
            
            //  Ejecuto ajax para Politicas
            jQuery('#jform_intcodigo_on').trigger( 'change', [data["idObjNacional"], data["idPoliticaNacional"]] );
            
            //  Desabilito combo de Objetivos Nacionales  
            jQuery('#jform_intcodigo_on').attr( 'disabled', 'disabled' );
            
            //  Ejecuto ajax para Metas
            jQuery('#jform_intcodigo_pn').trigger( 'change', [data["idObjNacional"], data["idPoliticaNacional"], data["idMetaNacional"]] );
            
            //  Desabilito combo de Plan Nacional
            jQuery('#jform_intcodigo_pn').attr( 'disabled', 'disabled' );
            
            //  Registro en una variable temporal el identificador de la fila a editar
            jQuery( '#idAlineacion' ).attr( 'value', idFila );
        }

    })
    
    
    function getDataAlineacion( idFila )
    {
        var numReg = lstAlineacionProyecto.length;
        
        for( var x = 0; x < numReg; x++ ){
            if( lstAlineacionProyecto[x]["idRegistro"] == idFila ){
                return lstAlineacionProyecto[x];
            }
        }
        
        return false;
    }
    
    
    //
    //  Gestiono la eliminacion de un registro
    //
    jQuery( '#delAlineacion' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var numReg = lstAlineacionProyecto.length;
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Registro", "SIITA - ECORAE", function( result ){
            if( result ) {
                for( var x = 0; x < numReg; x++ ){
                    if( lstAlineacionProyecto[x]["idRegistro"] == idFila.val() ){

                        //  Actualizo el estado del registro a cero
                        lstAlineacionProyecto[x]["published"] = 0;

                        //  Elimino la fila de la tabla
                        delFilaAlineacion( idFila.val() );
                    }
                }
            }
        });
        
    })
    
    /**
     * 
     * Gestiono la eliminacion de una determinada fila de la tabla de alineaciones
     * 
     * @param {type} idFila Identificador de la fila
     * 
     * @returns {undefined}
     * 
     */
    function delFilaAlineacion( idFila )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#tbLstAlineacion tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        });
    }
    
});