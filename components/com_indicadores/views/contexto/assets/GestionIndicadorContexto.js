jQuery( document ).ready( function(){
    //  Obtengo el identificador del contexto a gestionar
    var idRegContexto = jQuery( '#idRegContexto' ).val();
    
    //  Obtengo informacion del indicador a gestionar
    var dtaContexto = window.parent.objContexto.lstIndicadores[idRegContexto];
    
    //  Lista de variables - indicador
    lstTmpVar = new Array();
    
    //  Lista temporal de rangos asociados a un contexto
    lstTmpRango = new Array();
    
    //  Actualizo la informacion del contexto
    updateDtaContexto( dtaContexto );
    
    /**
     * 
     * Actualizo informacion de contextos 
     * 
     * @param {type} dtaContexto    Objeto datos de un contexto
     * 
     * @returns {undefined}
     * 
     */
    function updateDtaContexto( dtaContexto )
    {
        jQuery( '#jform_nombreIndicador' ).attr( 'value', dtaContexto.nombreIndicador );
        jQuery( '#jform_descripcionIndicador' ).attr( 'value', dtaContexto.descripcion );
        jQuery( '#jform_nombreReporte' ).attr( 'value', dtaContexto.nombreReporte );
        jQuery( '#formulaDescripcion' ).attr( 'value', dtaContexto.formula );

        //  Unidad de Gestion
        jQuery('#jform_idMetodoCalculo option').recorrerCombo( dtaContexto.idMetodoCalculo );
        jQuery('#jform_intIdUndGestion option').recorrerCombo( dtaContexto.idUGResponsable );
        
        //  Funcionario Responsable
        jQuery('#jform_intIdUGResponsable option').recorrerCombo( dtaContexto.idResponsableUG );
        jQuery('#jform_intIdUGResponsable').trigger( 'change', dtaContexto.idResponsable );

        //  SENPLADES
        if( dtaContexto.senplades === 1 ){
            jQuery('#jform_intSenplades_indEnt1').attr( 'checked', 'checked' );
        }else{
            jQuery('#jform_intSenplades_indEnt0').attr( 'checked', 'checked' );
        }

        setDtaRangos( dtaContexto.lstRangos );
        setDtaVariables( dtaContexto.lstVariables );
    }
    
    /**
     * 
     * Seteo Informacion de Unidades Territoriales
     * 
     * @param {type} lstUT      Lista de Informacion de Unidad Territorial
     * 
     * @returns {undefined}
     * 
     */
    function setDtaRangos( lstRg )
    {
        lstTmpRG = new Array();

        var objRg;
        for( var x = 0; x < lstRg.length; x++ ){
            objRg = new Rango();
            objRg.setDtaRango( lstRg[x] );
            objRg.idRegRG = x;

            //  Agrego una fila a la tabla de lineas base
            jQuery( '#lstRangos > tbody:last' ).append( objRg.addFilaRG( 0 ) );
            lstTmpRG.push( objRg );
        }

        return;
    }
    
    /**
     * 
     * Seteo Informacion de Variables de Tipo Indicador asociado a un contexto
     * 
     * @param {type} lstVar      Lista de Informacion de variables
     * 
     * @returns {undefined}
     * 
     */
    function setDtaVariables( lstVar )
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

        return;
    }
    
    
    /**
     * 
     * Controla la opcion de asignacion de informacion de un indicador
     * 
     * @param {type} task   tarea, p.e.: asignar, cancelar
     * @returns {undefined}
     */
    Joomla.submitbutton = function(task)
    {
        switch (task){
            case 'contexto.asignar':
                var dtaContexto = window.parent.objContexto.lstIndicadores[idRegContexto];

                dtaContexto.nombreIndicador = jQuery( '#jform_nombreIndicador' ).val();
                dtaContexto.descripcion     = jQuery( '#jform_descripcionIndicador' ).val();
                dtaContexto.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
                dtaContexto.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
                dtaContexto.idResponsable   = jQuery( '#jform_idResponsable' ).val();
                dtaContexto.formula         = jQuery( '#formulaDescripcion' ).val();
                dtaContexto.senplades       = jQuery( '#jform_intSenplades_indEnt' ).val();
                dtaContexto.nombreReporte   = jQuery( '#jform_nombreReporte' ).val();
                
                //  Actualizo informacion de Rangos de Gestion
                dtaContexto.lstRangos = new Array();
                for (var x = 0; x < lstTmpRG.length; x++) {
                    dtaContexto.lstRangos.push( lstTmpRG[x] );
                }
                
                //  Actualizo informacion de Variables
                dtaContexto.lstVariables = new Array();
                for (var x = 0; x < lstTmpVar.length; x++) {
                    dtaContexto.lstVariables.push( lstTmpVar[x] );
                }
            break;
        }
        
        //  Cierro la ventana modal( popup )
        window.parent.SqueezeBox.close();
    }
})