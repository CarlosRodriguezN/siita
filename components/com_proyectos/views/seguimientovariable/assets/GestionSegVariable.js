jQuery( document ).ready( function(){
    var dtaObjIndicador = parent.window.objGestionIndicador;
    
    var idRegIndicador = jQuery('#idRegIndicador').val();
    var tpoIndicador = jQuery('#tpoIndicador').val();
    var tpo = jQuery('#tpo').val();
    
    var dtaLstVar = false;
    var lstTmpSegVariables = new Array();
    var banIdRegSV = -1;
    
    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split('?')[0];

    //  Cargo informacion de acuerdo al tipo de indicador
    switch( tpoIndicador ){
        //  Indicadores Economicos
        case 'eco': 
            //  Actualizo informacion de la tabla de Unidades Territoriales
            if( typeof( dtaObjIndicador.indEconomico[idRegIndicador].lstVariables ) != "undefined" ){
                dtaLstVar = dtaObjIndicador.indEconomico[idRegIndicador].lstVariables;
                
                //  Actualizo comboBox de Variables asociadas al indicador
                updCbVariables( dtaLstVar );
            }
        break;
    }
    
    
    function updCbVariables( lstVar )
    {
        var items = [];
        var numRegistros = lstVar.length;

        if( numRegistros > 0 ){
            items.push('<option value="0">'+ COM_PROYECTOS_SELECCIONE_VARIABLE +'</option>');
            for( var x = 0; x < numRegistros; x++ ){
                items.push('<option value="' + lstVar[x].idVariable + '">' + lstVar[x].nombre + '</option>');
            }
        } else{
            items.push('<option value="0">'+ COM_PROYECTOS_SIN_REGISTROS +'</option>');
        }

        jQuery('#jform_idVariableIndicador').html( items.join('') );
    }
    
    
    
    /**
     *  Agrego un registro de unidad territorial
     */
    jQuery('#btnAddSeguimiento').live('click', function() {
        var idVariable = jQuery('#jform_idVariableIndicador').val();
        var nombreVar = jQuery('#jform_idVariableIndicador :selected').text();
        var fecha = jQuery('#jform_fchSeguimiento').val();
        var valor = jQuery('#jform_valorSeguimiento').val();

        var idRegSV = lstTmpSegVariables.length;
        var objSegVariable = new SegVariable( idRegSV, idVariable, nombreVar, fecha, valor );

        //  Verificamos si la variable seleccionada tiene indicadores
        if( existeSV( idVariable, objSegVariable ) == 0 ){

            if( banIdRegSV != -1 ){
                //  Actualizo el contenido de seguimiento de variable
                lstTmpSegVariables[banIdRegSV] = objSegVariable;
                
                //  Actualizo informacion de la fila
                updInfoFilaPI( banIdRegSV, addFilaSV( objSegVariable, 1 ) );
                
                //  EnCero el contenido de la bandera registro de planificacion
                banIdRegSV = -1;
            }else{
                //  Registro una nueva planificacion  
                lstTmpSegVariables.push( objSegVariable );
                
                //  Registro una nueva fila en la tabla de planificacion
                jQuery( '#lstSegVariablesIndicadores > tbody:last' ).append( addFilaSV( objSegVariable, 0 ) );
            }
        }else{
            jAlert( 'Seguimiento de Variable, ya existe', 'SIITA - ECORAE' );
        }
        
        limpiarFrmSupuesto();
    })
    
    /**
     * 
     * Valido la no existencia de un seguiento de una variable
     * 
     * @param {type} sv
     * @returns {Number}
     */
    function existeSV( sv )
    {
        var nrpi = lstTmpSegVariables.length;
        var ban = 0;

        for( var x = 0; x < nrpi; x++ ){
            if( lstTmpSegVariables[x].toString() == sv.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }

    /**
     * 
     * Agrego una fila a la tabla Planificacion Institucional
     * 
     * @param {type} dtaSegVariable   Datos de Planificacion
     * @param {type} banTipo            Bandera que indica que controla si es nuevo o existente
     *                                  0: Nuevo, 1: Actualizacion
     *                                  
     * @returns {undefined}
     */
    function addFilaSV( dtaSegVariable, banTipo )
    {
        //  Construyo la Fila
        var fila = ( banTipo == 0 ) ? "<tr id='"+ dtaSegVariable.idRegSV +"'>" 
                                    : "";

        fila += "<td align='center'>"+ dtaSegVariable.nombre +"</td>"
            + " <td align='center'>"+ dtaSegVariable.fecha +"</td>"
            + " <td align='center'>"+ dtaSegVariable.valor +"</td>"
            + " <td align='center'> <a class='updSV'> Editar </a> </td>"
            + " <td align='center'> <a class='delSV'> Eliminar </a> </td>";
            
        fila += (banTipo == 0 ) ? "</tr>"
                                : "";

        return fila;
    }
    
    
    /**
     * 
     * Actualizo el contenido de una fila de Planificacion
     * 
     * @param {type} idFila     Identificador de la fila
     * @param {type} dataUpd    Datos de una fila
     * 
     * @returns {undefined}
     */
    function updInfoFilaPI( idFila, dataUpd )
    {
        jQuery( '#lstSegVariablesIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).html( dataUpd );
            }
        })
    }

    /**
     * Gestiono la actualizacion de la Planificacion de un indicador
     */
    jQuery( '.updSV' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        var dtaSV = lstTmpSegVariables[idFila];
        banIdRegSV = idFila;
        
        //  Recorro hasta una determinada posicion el combo de variables
        recorrerCombo( jQuery( '#jform_idProvincia option' ), dtaSV.idVariable );

        //  Actualizo informacion de formulario Planificacion Indicador
        jQuery('#jform_fchSeguimiento').attr( 'value', dtaSV.fecha );
        jQuery('#jform_valorSeguimiento').attr( 'value', dtaSV.valor );
    })
    
    
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


    /**
     * 
     * Limpia formulario de Supuestos
     * 
     * @returns {undefined}
     */
    function limpiarFrmSupuesto()
    {
        jQuery( '#jform_txtSupuestos' ).attr( 'value', '' );
    }


    //  Agrego informacion de linea base
    Joomla.submitbutton = function(task)
    {
        if (task == 'seguimientoVariable.asignar') {
            switch (tpoIndicador) {
                case 'eco':
                    //  Vacio Lista de Unidades Territoriales
                    parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstSegVariables = new Array();

                    //  Agrego Lista editada de Unidades Territoriales en un indicador economico
                    for( var x = 0; x < lstTmpSegVariables.length; x++ ){
                        parent.window.objGestionIndicador.indEconomico[idRegIndicador].lstSegVariables.push( lstTmpSegVariables[x] );
                    }
                break;
            }
            
            //  Cambio la imagen del indicador seleccionado
            jQuery( '#'+ tpoIndicador.toUpperCase() + idRegIndicador + tpo.toUpperCase() +'SV', window.parent.document ).html( '<img src="/media/com_proyectos/images/btnLineaBase/PN/pn_verde_small.png">' );

            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        } else {
            //  Cierro la ventana modal( popup )
            window.parent.SqueezeBox.close();
        }
    }
})