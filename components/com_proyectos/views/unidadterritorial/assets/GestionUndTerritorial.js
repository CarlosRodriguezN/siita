jQuery(document).ready(function() {
    var dtaObjIndicador = parent.window.objGestionIndicador;
    
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpo = jQuery('#tpo').val();
    
    var dtaLstUT = false;
    var idUndTerritorial;
    var lstTmpUndsTerritoriales = new Array();
    var banIdRegUT = -1;
    

    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split('?')[0];

    //  Cargo informacion de acuerdo al tipo de indicador
    switch( tpoIndicador ){
        
        //  Indicadores Economicos
        case 'eco': 
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indEconomico[idRegIndicador].lstUndsTerritoriales ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indEconomico[idRegIndicador].lstUndsTerritoriales;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }
        break;
        
        //  Indicadores Financieros
        case 'fin':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indFinanciero[idRegIndicador].lstUndsTerritoriales ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indFinanciero[idRegIndicador].lstUndsTerritoriales;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }
        break;
        
        //  Beneficiarios Directos
        case 'bd':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indBDirecto[idRegIndicador].lstUndsTerritoriales ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indBDirecto[idRegIndicador].lstUndsTerritoriales;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }
        break;
        
        //  Beneficiarios Indirectos
        case 'bi':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indBIndirecto[idRegIndicador].lstUndsTerritoriales ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.indBIndirecto[idRegIndicador].lstUndsTerritoriales;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }
        break;
        
        //  Grupos de Atencion prioritaria
        case 'gap':
            switch( tpo ){
                case 'm': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstGAP[idRegIndicador].gapMasculino.lstUndsTerritoriales;
                    }
                break;
                
                case 'f': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstGAP[idRegIndicador].gapFemenino.lstUndsTerritoriales;
                    }
                break;
                
                case 't': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstGAP[idRegIndicador].gapTotal.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstGAP[idRegIndicador].gapTotal.lstUndsTerritoriales;
                    }
                break;
            }
            
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( dtaLstUT != false ){

                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }

        break;
        
        //  Enfoque de Igualdad
        case 'ei':
            switch( tpo ){
                case 'm': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstUndsTerritoriales;
                    }
                break;
                
                case 'f': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstUndsTerritoriales;
                    }
                break;
                
                case 't': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstUndsTerritoriales;
                    }                    
                break;
            }
            
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( dtaLstUT != false ){

                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }

        break;
        
        //  Enfoque de Ecorae
        case 'ee':
            switch( tpo ){
                case 'm': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstUndsTerritoriales;
                    }
                break;
                
                case 'f': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstUndsTerritoriales;
                    }
                break;
                
                case 't': 
                    //  Actualizo informacion de la tabla de Unidades Territoriales
                    if( typeof( dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstUndsTerritoriales ) != "undefined" ){
                        dtaLstUT = dtaObjIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstUndsTerritoriales;
                    }
                break;
            }
            
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( dtaLstUT != false ){

                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }
        break;
        
        //  Otros Indicadores
        case 'oi':
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.lstOtrosIndicadores[idRegIndicador].lstUndsTerritoriales ) != "undefined" ){
                dtaLstUT = dtaObjIndicador.lstOtrosIndicadores[idRegIndicador].lstUndsTerritoriales;
                var nrut = dtaLstUT.length;

                for( var x = 0; x < nrut; x++  ){
                    lstTmpUndsTerritoriales.push( dtaLstUT[x] );
                    addFilaUT( dtaLstUT[x] );
                }
            }
        break;
        
    }

    /**
     *  Agrego un registro de unidad territorial
     */
    jQuery('#btnAddUndTerritorial').live('click', function() {
        var idProvincia = jQuery('#jform_idProvincia').val();
        var idCanton = jQuery('#jform_idCanton').val();
        var idParroquia = jQuery('#jform_idParroquia').val();
        
        var provincia = jQuery('#jform_idProvincia :selected').text();
        var canton = jQuery('#jform_idCanton :selected').text();
        var parroquia = jQuery('#jform_idParroquia :selected').text();
        
        switch (true) {
            case(idProvincia != 0 && idCanton != 0 && idParroquia != 0):
                idUndTerritorial = idParroquia;
                
                break;

            case(idProvincia != 0 && idCanton != 0 && idParroquia == 0):
                idUndTerritorial = idCanton;
                break;

            case(idProvincia != 0 && idCanton == 0 && idParroquia == 0):
                idUndTerritorial = idProvincia;
                break;
        }

        var idRegUT = ( banIdRegUT == -1 )  ? lstTmpUndsTerritoriales.length
                                            : parseInt( banIdRegUT );
                                            
        var undTerritorial = new UnidadTerritorial( idRegUT, idUndTerritorial, provincia, canton, parroquia );

        if( existeUndTerritorial( undTerritorial ) == 0 ){
            
            if( banIdRegUT != -1 ){
                lstTmpUndsTerritoriales[banIdRegUT] = undTerritorial;
                updFilaUT( undTerritorial );
                
                banIdRegUT = -1;
            }else{
                lstTmpUndsTerritoriales.push( undTerritorial );
                addFilaUT( undTerritorial );
            }
        }else{
            jAlert( 'Unidad Territorial, ya existe', 'SIITA - ECORAE' );
        }
    })
    
    
    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery( '.updIndUT' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        banIdRegUT = idFila;
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_proyectos',
                                view: 'proyecto',
                                tmpl: 'component',
                                format: 'json',
                                action: 'getDPA',
                                idUndTerritorial: lstTmpUndsTerritoriales[idFila].idUndTerritorial
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
                lstTmpUndsTerritoriales.splice( idFila, 1 );
                delFilaUT( idFila );
            }
        });
    })
    
    
    /**
     * 
     * Agrego una fila en la table Unidad Territorial
     * 
     * @param {type} undTerritorial 
     * @returns {undefined}
     */
    function addFilaUT( undTerritorial )
    {
        //  Construyo la Fila
        var fila = "<tr id='"+ undTerritorial.idRegUT +"'>"
                    + " <td align='center'>"+ undTerritorial.provincia +"</td>"
                    + " <td align='center'>"+ undTerritorial.canton +"</td>"
                    + " <td align='center'>"+ undTerritorial.parroquia +"</td>"
                    + " <td align='center'> <a class='updIndUT'> Editar </a> </td>"
                    + " <td align='center'> <a class='delIndUT'> Eliminar </a> </td>"
                +"  </tr>";

        //  Agrego la fila creada a la tabla
        jQuery( '#lstUndTerritorialesInd > tbody:last' ).append( fila );
    }

    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaUT( undTerritorial )
    {
        jQuery( '#lstUndTerritorialesInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIdRegUT ){
                
                //  Agrego color a la fila actualizada
                jQuery( this ).attr( 'style', 'border-color: black;background-color: bisque;' );
                
               //  Construyo la Fila
                var fila = "<td align='center'>"+ undTerritorial.provincia +"</td>"
                        + " <td align='center'>"+ undTerritorial.canton +"</td>"
                        + " <td align='center'>"+ undTerritorial.parroquia +"</td>"
                        + " <td align='center'> <a class='updIndUT'> Editar </a> </td>"
                        + " <td align='center'> <a class='delIndUT'> Eliminar </a> </td>";

                jQuery( this ).html( fila );
            }
        })
    }

    /**
     * 
     * Registro de unidades territoriales
     * 
     * @param {Object} undTerritorial     Unidad Territorial
     * @returns {undefined}
     */
    function existeUndTerritorial( undTerritorial )
    {
        var nrut = lstTmpUndsTerritoriales.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpUndsTerritoriales[x].toString() == undTerritorial.toString() ){
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
        if (task == 'unidadterritorial.asignar') {

            switch (tpoIndicador) {
                case 'eco':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstUndsTerritoriales = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                        parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                    }
                break;
                
                case 'fin':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indFinanciero[idRegIndicador].lstUndsTerritoriales = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                        parent.window.objGestionIndicador.indFinanciero[idRegIndicador].lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                    }
                break;
                
                case 'bd':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indBDirecto[idRegIndicador].lstUndsTerritoriales = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                        parent.window.objGestionIndicador.indBDirecto[idRegIndicador].lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                    }
                break;
                
                case 'bi':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].lstUndsTerritoriales = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                        parent.window.objGestionIndicador.indBIndirecto[idRegIndicador].lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                    }
                break;
                
                case 'gap':
                    switch( tpo ){
                        case 'm': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapMasculino.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                        
                        case 'f': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapFemenino.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                        
                        case 't': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstGAP[idRegIndicador].gapTotal.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                    }

                break;
                
                case 'ei':
                    switch( tpo ){
                        case 'm': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiMasculino.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                        
                        case 'f': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiFemenino.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                        
                        case 't': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfIgualdad[idRegIndicador].eiTotal.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                    }

                break;
                
                case 'ee':
                    switch( tpo ){
                        case 'm': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeMasculino.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                        
                        case 'f': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeFemenino.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                        
                        case 't': 
                            //  Vacio Lista de Unidades Territoriales
                            parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstUndsTerritoriales = new Array();

                            //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                            for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                                parent.window.objGestionIndicador.lstEnfEcorae[idRegIndicador].eeTotal.lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                            }
                        break;
                    }

                break;
                
                //  Otros Indicadores
                case 'oi':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstUndsTerritoriales = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpUndsTerritoriales.length; x++ ){
                        parent.window.objGestionIndicador.lstOtrosIndicadores[idRegIndicador].lstUndsTerritoriales.push( lstTmpUndsTerritoriales[x] );
                    }
                break;

            }
            
            //  Cambio la imagen del indicador seleccionado
            jQuery('#' + tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() +'UT', window.parent.document).html('<img src="/media/com_proyectos/images/btnLineaBase/UT/ut_verde_small.png">');

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        } else {
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
})