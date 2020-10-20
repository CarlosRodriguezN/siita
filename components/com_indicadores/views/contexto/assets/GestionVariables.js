jQuery(document).ready(function() {
    var banIdRegVar = -1;
    var banNV = 0;

    /**
     *  Controlo Factor de ponderacion de acuerdo al valor 
     */
    jQuery( '#jform_idMetodoCalculo' ).live( 'change', function(){
        var idReg = jQuery( '#idRegContexto' ).val();            
        var dtaContexto = window.parent.objContexto.lstIndicadores[idReg];
        
        if( jQuery.inArray( parseInt( jQuery( this ).val() ), [3,4] ) != -1 ){
            //  Seteo metodo de calculo del indicador de tipo contexto
            dtaContexto.idMetodoCalculo = jQuery(this).val();
            jQuery( '#jform_factorPonderacion' ).removeAttr( 'readonly' );
        }else{
            dtaContexto.idMetodoCalculo = jQuery(this).val();
            jQuery( '#jform_factorPonderacion' ).attr( 'value', 1 );
            jQuery( '#jform_factorPonderacion' ).attr( 'readonly', 'readonly' );
        }
    })

    /**
     *  Agrego un registro de una nueva variable
     */
    jQuery('#btnAddElementoContexto').live('click', function() {
        
        var objVariable = new Variable();
        
        if( banIdRegVar === -1 ){
            objVariable.idRegVar = lstTmpVar.length;
        }else{
            objVariable = lstTmpVar[banIdRegVar];
            banNV = lstTmpVar[banIdRegVar].ban;
        }


        if( validarVariable() === 1 ){

            //  1: Variable - 2:Indicador ( Contextos )
            objVariable.idTpoElemento   = 2;

            //  Gestiono informacion de un elemento de tipo indicador asociado a una variable
            objVariable.idTpoEntidad        = jQuery( '#jform_idTpoEntidad' ).val();
            objVariable.idEntidad           = jQuery( '#jform_idEntidad' ).val();
            objVariable.nombre              = jQuery( '#jform_idIndicador :selected' ).text();
            objVariable.idElemento          = jQuery( '#jform_idIndicador' ).val();
            objVariable.factorPonderacion   = jQuery( '#jform_factorPonderacion' ).val();
            objVariable.UGResponsable       = jQuery( '#jform_UGResponsable' ).val();
            objVariable.UGFuncionario       = jQuery( '#jform_ResponsableUG' ).val();
            objVariable.FunResponsable      = jQuery( '#jform_funcionario' ).val();

            if( banIdRegVar === -1 ){
                if( existeVariable( objVariable ) === 0 ){
                    lstTmpVar.push( objVariable );

                    //  Agrego la fila creada a la tabla
                    jQuery( '#lstVarIndicadores > tbody:last' ).append( objVariable.addFilaVar( 0 ) );
                }else{
                    jAlert( COM_CONTEXTOS_ELEMENTO_YA_REGISTRADO, COM_CONTEXTOS_SISTEMA_SIITA );
                }
            }else{
                lstTmpVar[banIdRegVar] = objVariable;
                updFilaVar( objVariable.addFilaVar( 1 ) );
            }
            
        }

        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmVar();
    })
    
    
    
    function validarVariable()
    {
        var idTpoEntidad        = jQuery('#jform_idTpoEntidad');
        var idEntidad           = jQuery('#jform_idEntidad');
        var idIndicador         = jQuery('#jform_idIndicador');
        var factorPonderacion   = jQuery('#jform_factorPonderacion');
        
        var ban = 0;
        if (    parseInt( idTpoEntidad.val() ) !== 0 
                && parseInt( idEntidad.val() ) !== 0 
                && parseInt( idIndicador.val() ) !== 0
                && factorPonderacion.val() !== "" 
                && parseFloat( factorPonderacion.val() ) > 0 ){
            ban = 1;
        }else{
            idTpoEntidad.validarElemento();
            idEntidad.validarElemento();
            idIndicador.validarElemento();
            factorPonderacion.validarElemento();
        }

        return ban;
    }
    
    
    /**
     * 
     * Verifico la Existencia de una determinada linea base
     * 
     * @param {Object} variable     Objeto Linea Base con Informacion de Lineas Base Registradas
     * 
     * @returns {undefined}
     * 
     */
    function existeVariable( variable )
    {
        var nrut = lstTmpVar.length;
        var ban = 0;

        for( var x = 0; x < nrut; x++ ){
            if( lstTmpVar[x].toString() === variable.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
    /**
     * Gestiono la acualizacion de un Rango de Gestion
     */
    jQuery( '.updElemento' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdRegVar = parseInt( idFila );
        
        for( var x = 0; x < lstTmpVar.length; x++ ){
            if( parseInt( lstTmpVar[x].idRegVar ) === banIdRegVar ){
                
                jQuery( '#jform_factorPonderacion' ).attr( 'value', lstTmpVar[x].factorPonderacion );
                
                //  Recorro ComboBox de tipos de entidades 
                jQuery( '#jform_idTpoEntidad option' ).recorrerCombo( lstTmpVar[x].idTpoEntidad );

                //  Actualizo contenido de comboBox Entidad y selecciono la entidad registrada
                jQuery( '#jform_idTpoEntidad' ).trigger( 'change', lstTmpVar[x].idEntidad );

                //  Actualizo las cajas de texto asociadas a los responsables de un indicador
                jQuery( '#jform_idEntidad' ).trigger( 'change', [ lstTmpVar[x].idEntidad, lstTmpVar[x].idElemento] );
                
                //  Actualizo informacion de responsables del indicador
                jQuery( '#jform_idIndicador' ).trigger( 'change', [ lstTmpVar[x].idEntidad, lstTmpVar[x].idElemento] );
            }
        }
     })
    
    /**
     * Gestiona Creacion de Formula
     */
    jQuery( '.addFormula' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var dtaFormula = jQuery( '#formulaDescripcion' ).val();
        
        if( !existeEnFormula( lstTmpVar[idFila].toString() ) ){
            dtaFormula = dtaFormula + ' ' + lstTmpVar[idFila].toString();
            jQuery( '#formulaDescripcion' ).attr( 'value', dtaFormula );
        }else{
            jAlert( COM_ELEMENTO_FORMULA_YA_REGISTRADO, COM_CONTEXTOS_SISTEMA_SIITA );
        }
    })
    
    /**
     * Cancelo la operacion de "INSERCION" y/o "EDICION", limpiando el formulario
     */
    jQuery( '#btnCancelarContexto' ).live( 'click', function(){
        banIdRegVar = -1;

        //  Restauro a valores predeterminados formulario
        limpiarFrmVar();
    })
    
    
    /**
     * Agrega una operacion a la formula
     */
    jQuery( '#btnFrmSuma, #btnFrmResta, #btnFrmMultiplicacion, #btnFrmDivision' ).live( 'click', function(){
        var dtaFormula = jQuery( '#formulaDescripcion' ).val();
        dtaFormula = dtaFormula + ' ' + jQuery( this ).val();
        jQuery( '#formulaDescripcion' ).attr( 'value', dtaFormula );
    })
    
    /**
     * Borra el contenido de la formula
     */
    jQuery( '#btnLimpiarFormula' ).live( 'click', function(){
        jQuery( '#formulaDescripcion' ).attr( 'value', '' );
    })
    
    /**
     * 
     * Actualizo informacion de un determinada Variable
     * 
     * @param {HTML} fila       Datos a actualizar
     * @returns {undefined}
     */
    function updFilaVar( fila )
    {
        jQuery( '#lstVarIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) !== "undefined" && parseInt( jQuery( this ).attr( 'id' ) ) === banIdRegVar ){
                jQuery( this ).html( fila );
            }
        })
    }
    
    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaRG( idFila )
    {
        //  Elimino fila de la tabla lista de Rangos
        jQuery( '#lstVarIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) === idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    
    /**
     * 
     * Verifica la existencia de un determinado elemento en la formula
     * 
     * @param {type} elemento   Elemento a buscar en la formula
     * 
     * @returns {Boolean}       False:  NO existe elemento en la formula
     *                          TRUE:   SI existe elemento en la formula
     * 
     */
    function existeEnFormula( elemento )
    {
        var ban = false;
        var formula = jQuery( '#formulaDescripcion' ).val();
        
        if( formula.lastIndexOf( elemento ) != -1 ){
            ban = true;
        }
        
        return ban;
    }

    /**
     * 
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmVar()
    {
        jQuery( '#jform_factorPonderacion' ).attr( 'value', '1' );
        
        //  Recorro hasta una determinada posicion el combo de Tipo de Entidad
        jQuery( '#jform_idTpoEntidad option' ).recorrerCombo( 0 )
        
        //  EnceroCombo Combo de Entidades
        jQuery( '#jform_idEntidad option' ).enCerarCombo()
        
        //  EnceroCombo Combo de Indicadores asociados a una entidad
        jQuery( '#jform_idIndicador option' ).enCerarCombo();
        
        jQuery( '#jform_factorPonderacion' ).attr( 'value', '1' );
        jQuery( '#jform_UGResponsable' ).attr( 'value', '' );
        jQuery( '#jform_ResponsableUG' ).attr( 'value', '' );
        jQuery( '#jform_funcionario' ).attr( 'value', '' );
    }
})