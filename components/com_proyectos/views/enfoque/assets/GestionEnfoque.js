jQuery(document).ready(function() {
    //  Seteo informacion especifica de otros Indicadores
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    
    var dtaObjEnfoque = parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador];
    var dtaLstEnfoque = false;
    var idEnfoque;
    var lstTmpEnfoques = new Array();
    var banIdEnfoque = -1;

    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split('?')[0];

    if( typeof( dtaObjEnfoque.lstEnfoque ) != "undefined" ){
        dtaLstEnfoque = dtaObjEnfoque.lstEnfoque;
        
        //  Actualizo informacion de la tabla de Unidades Territoriales
        if( dtaLstEnfoque != false ){
            var nrle = dtaLstEnfoque.length;
            for( var x = 0; x < nrle; x++  ){
                lstTmpEnfoques.push( dtaLstEnfoque[x] );
                addFilaEnfoque( dtaLstEnfoque[x], 0 );
            }
        }
    }

    /**
     *  Agrego Enfoque
     */
    jQuery('#btnAddEnfoque').live('click', function() {
        var idEnfoque = jQuery('#jform_idEnfoque').val();
        var idDimension = jQuery('#jform_idDimension').val();
        
        var enfoque = jQuery('#jform_idEnfoque :selected').text();
        var dimension = jQuery('#jform_idDimension :selected').text();
        
        var idRegEnfoque = ( banIdEnfoque == -1 )   ? lstTmpEnfoques.length
                                                    : parseInt( banIdEnfoque );
                                            
        var enfoque = new Enfoque( idRegEnfoque, idEnfoque, enfoque, idDimension, dimension );

        if( existeDimension( enfoque ) == 0 ){
            if( banIdEnfoque != -1 ){
                lstTmpEnfoques[banIdEnfoque] = enfoque;
                addFilaEnfoque( enfoque, 1 );
                banIdEnfoque = -1;
            }else{
                lstTmpEnfoques.push( enfoque );
                addFilaEnfoque( enfoque, 0 );
            }

        }else{
            jAlert( 'Enfoque, Registrado', 'SIITA - ECORAE' );
        }
    })
    
    
    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery( '.updIndUT' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        banIdEnfoque = idFila;
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getDPA',
                                idUndTerritorial: lstTmpEnfoques[idFila].idUndTerritorial
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Proyectos - Gestion Unidad Territorial: ' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval("(" + data.responseText + ")");

            //  Recorro hasta una determinada posicion el combo de provincias
            recorrerCombo( jQuery( '#jform_idProvincia option' ), dataInfo.idProvincia );

            //  Simulo una seleccion de un elemento de provincia
            jQuery( '#jform_idProvincia' ).trigger( 'change', dataInfo.idCanton );

            //  Simulo una seleccion de un elemento de canton
            jQuery( '#jform_idCanton' ).trigger( 'change', [dataInfo.idCanton, dataInfo.idParroquia] );
        })
     })
    
    
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delIndUT' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar esta Unidad Territorial", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpEnfoques.splice( idFila, 1 );
                delFilaUT( idFila );
            }
        });
    })
    
    
    /**
     * 
     * Agrego una fila en la table Unidad Territorial
     * 
     * @param {Object} dtaEnfoque     Datos del enfoque
     * 
     * @returns {undefined}
     */
    function addFilaEnfoque( dtaEnfoque, ban )
    {
        //  Construyo la Fila
        var fila = ( ban == 0 ) 
                ? "<tr id='"+ dtaEnfoque.idRegEnfoque +"'>"
                : "";
                
        fila += "       <td align='center'>"+ dtaEnfoque.dimension +"</td>"
                    + " <td align='center'>"+ dtaEnfoque.enfoque +"</td>"
                    + " <td align='center'> <a class='updEnfoque'> Editar </a> </td>"
                    + " <td align='center'> <a class='updEnfoque'> Eliminar </a> </td>";

        fila += ( ban == 0 )
                ? "  </tr>"
                : "";

        //  Agrego la fila creada a la tabla
        jQuery( '#lstEnfoques > tbody:last' ).append( fila );
    }

    /**
     * 
     * Registro de unidades territoriales
     * 
     * @param {Object} enfoque     Unidad Territorial
     * @returns {undefined}
     */
    function existeDimension( enfoque )
    {
        var nrut = lstTmpEnfoques.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpEnfoques[x].toString() == enfoque.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }


    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaUT( idFila )
    {
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstUndTerritorialesInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }


    /**
     * 
     * Recorre los comboBox del Formulario a la posicion inicial
     * 
     * @param {type} combo      Objeto ComboBox
     * @param {type} posicion   Posicion a la que el combo va a recorrer
     * 
     * @returns {undefined}
     */
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }


    //  Agrego informacion de linea base
    Joomla.submitbutton = function(task)
    {
        if (task == 'enfoque.asignar') {

            //  Vacio Lista de Unidades Territoriales
            parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstEnfoque = new Array();

            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
            for( var x = 0; x < lstTmpEnfoques.length; x++ ){
                parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstEnfoque.push( lstTmpEnfoques[x] );
            }

            if( lstTmpEnfoques.length > 0 ){
                //  Cambio la imagen del indicador seleccionado
                jQuery('#' + tpoIndicador.toUpperCase() + idRegIndicador + 'DIM', window.parent.document).html('<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_verde_small.png">');
            }

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        } else {
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
})