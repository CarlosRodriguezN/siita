jQuery(document).ready(function() {
    var banIdRegVar = -1;
    var banNV = 0;
    
    /**
     *  
     *  Muestra el formulario de variables
     *  
     *  @returns {undefined}
     */
    function showFormVariables() {
        jQuery('#imgVariables').css("display", "none");
        jQuery('#frmVariables').css("display", "block");
    }
    
    /**
     *  
     *  Oculta el formulario de variables
     *  
     *  @returns {undefined}
     */
    function hideFormVariables() {
        jQuery('#imgVariables').css("display", "block");
        jQuery('#frmVariables').css("display", "none");

        banIdRegVar = -1;
        limpiarFrmVar();
    }
    
    /**
     * Gestion de Formulario de Nueva Variable
     */
    jQuery( '#btnVariableNueva' ).live( 'click', function(){
        showFormVariables();
        muestroFrmNuevaVariable();
        limpiarFrmVar();
        
        //  Cambio el nombre del formulario de Indicadores a Variables
        jQuery( '#lblElemento' ).html( COM_INDICADORES_ELEMENTO_VARIABLE );
    })
    
    /**
     * Gestion de Formulario de un Indicador como Variable
     */
    jQuery( '#btnIndicadorVariable' ).live( 'click', function(){
        //  Muestro formularios
        showFormVariables();

        //  Muestro el formulario para asignar un indicador como variable
        muestroFrmIndicadorVariable();

        //  Cambio el nombre del formulario de Indicadores a Variables
        jQuery( '#lblElemento' ).html( COM_INDICADORES_ELEMENTO_INDICADOR );
    })

    /**
     * 
     * Muestro Formulario para agregar un Indicador como variable a una formula
     * 
     * @returns {undefined}
     * 
     */
    function muestroFrmNuevaVariable()
    {
        //  Muestro Formulario de Registro de Nuevas Variables
        jQuery('#frmNuevaVariable').css( 'display', 'block' );
        jQuery('#responsableNV').css( 'display', 'block' );
        
        //  Oculto Formulario de registro de Indicador como Variable
        jQuery('#frmIndicadorVar').css( 'display', 'none' );
        jQuery('#responsablesIndicador').css( 'display', 'none' );
        
        //  Oculto Formulario de registro de Indicador como Variable
        jQuery('#frmVariableExistente').css( 'display', 'none' );
        jQuery('#responsablesIndicador').css( 'display', 'none' );

        banIdRegVar = ( banIdRegVar !== -1 )? banIdRegVar 
                                            : -1;
                                            
        return;
    }
    
    /**
     * 
     * Muestro Formulario para agregar un Indicador como variable a una formula
     * 
     * @returns {undefined}
     * 
     */
    function muestroFrmIndicadorVariable()
    {
        //  Muestro Formulario de registro de Indicador como Variable
        jQuery('#frmIndicadorVar').css( 'display', 'block' );
        jQuery('#responsablesIndicador').css( 'display', 'block' );
        
        //  Oculto Formulario de Registro de Nuevas Variables
        jQuery('#frmNuevaVariable').css( 'display', 'none' );
        jQuery('#responsableNV').css( 'display', 'none' );
        
        //  Oculto Formulario de registro de Indicador como Variable
        jQuery('#frmVariableExistente').css( 'display', 'none' );
        
        banIdRegVar = ( banIdRegVar !== -1 )? banIdRegVar 
                                            : -1;
                                            
        return;
    }

    /**
     *  Agrego un registro de una nueva variable
     */
    jQuery('#btnAddVariable').live('click', function() {
        var objVariable = new Variable();
        if( banIdRegVar === -1 ){
            objVariable.idRegVar        = lstTmpVar.length;
            objVariable.idIndVariable   = 0;
        }else{
            objVariable.setDtaVariable( lstTmpVar[banIdRegVar] );
        }

        //  Gestiono informacion de una NUEVA variable
        objVariable.idTpoEntidad    = jQuery( '#jform_idTpoEntidad' ).val();
        objVariable.idEntidad       = jQuery( '#jform_idEntidad' ).val();

        //  1: Variable - 2:Indicador
        objVariable.idTpoElemento   = ( parseInt( objVariable.idEntidad ) === 0 )   
                                        ? 1
                                        : 2;

        if( objVariable.idTpoElemento === 1 ){
            //  Variable
            objVariable.idTpoEntidad    = jQuery( '#jform_idTpoEntidad' ).val();
            objVariable.idEntidad       = jQuery( '#jform_idEntidad' ).val();
            objVariable.nombre          = jQuery( '#jform_nombreNV' ).val();
            objVariable.alias           = jQuery( '#jform_aliasNV' ).val();
            objVariable.descripcion     = jQuery( '#jform_descripcionNV' ).val();
            objVariable.idUndAnalisis   = jQuery( '#jform_idUndAnalisisNV' ).val();
            objVariable.undAnalisis     = jQuery( '#jform_idUndAnalisisNV :selected' ).text();
            objVariable.idTpoUM         = jQuery( '#jform_idTpoUndMedidaNV' ).val();
            objVariable.idUndMedida     = jQuery( '#jform_idVarUndMedidaNV' ).val();
            objVariable.undMedida       = jQuery( '#jform_idVarUndMedidaNV :selected' ).text();
            
            //  Gestiono Informacion de Und Gestion Responsable - Funcionario Responsable
            objVariable.idUGResponsable = jQuery( '#jform_idUGResponsableVar' ).val();
            objVariable.idUGFuncionario = jQuery( '#jform_idUGFuncionarioVar' ).val();
            objVariable.idFunResponsable= jQuery( '#jform_idFunResponsableVar' ).val();

        }else{
            //  Indicador
            objVariable.nombre          = jQuery( '#jform_idIndicador :selected' ).text();
            objVariable.idTpoUM         = jQuery( '#jform_idIndTpoUndMedida' ).val();
            objVariable.idUndMedida     = jQuery( '#jform_idIndUndMedida' ).val();
            objVariable.undMedida       = jQuery( '#jform_idIndUndMedida :selected' ).text();
            objVariable.idElemento      = jQuery( '#jform_idIndicador' ).val();

            objVariable.UGResponsable   = jQuery( '#jform_UGResponsable' ).val();
            objVariable.UGFuncionario   = jQuery( '#jform_ResponsableUG' ).val();
            objVariable.FunResponsable  = jQuery( '#jform_funcionario' ).val();
        }


        if( existeVariable( objVariable ) === 0 ){
            
            delFilaLstVariables( -1 );
            
            if( banIdRegVar === -1 ){
                lstTmpVar.push( objVariable );

                //  Agrego la fila creada a la tabla
                jQuery( '#lstVarIndicadores > tbody:last' ).append( objVariable.addFilaVar( 0 ) );
            }else{
                //  Actualizo
                lstTmpVar[banIdRegVar] = objVariable;
                updFilaVar( objVariable.addFilaVar( 1 ) );
            }
        }else{
            jAlert( COM_INDICADORES_VARIABLE_EXISTENTE, COM_INDICADORES_SIITA );
        }

        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmVar();
        
        //  Oculto formulario de variables
        hideFormVariables();
    })
    
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
            if( lstTmpVar[x].toString() === variable.toString() && banIdRegVar === -1 ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
    /**
     * Muestro la caja de texto para el registro de una nueva variable
     */
    jQuery( '#jform_idVariable' ).live( 'change', function(){
        if( jQuery( this ).val() === 0 ){
            //  Muestro el texto de otra variable
            jQuery('#otraVariable').css( 'display', 'block' );
        }
    })
    
    
    /**
     * Gestiono la acualizacion de un Rango de Gestion
     */
    jQuery( '.updVar' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdRegVar = idFila;
        
        //  Muestro el formulario de variables
        showFormVariables();
        
        for( var x = 0; x < lstTmpVar.length; x++ ){
            if( lstTmpVar[x].idRegVar == banIdRegVar ){
                
                if( lstTmpVar[x].idTpoElemento == 1 ){
                    //  Variable
                    jQuery( '#jform_nombreNV' ).attr( 'value', lstTmpVar[x].nombre );
                    jQuery( '#jform_aliasNV' ).attr( 'value', lstTmpVar[x].alias );
                    jQuery( '#jform_descripcionNV' ).attr( 'value', lstTmpVar[x].descripcion );
                    
                    jQuery( '#jform_idUndAnalisisNV option' ).recorrerCombo( lstTmpVar[x].idUndAnalisis );
                    jQuery( '#jform_idTpoUndMedidaNV option' ).recorrerCombo( lstTmpVar[x].idTpoUM );
                    jQuery( '#jform_idTpoUndMedidaNV' ).trigger( 'change', lstTmpVar[x].idUndMedida );
                    
                    jQuery( '#jform_idUGResponsableVar option' ).recorrerCombo( lstTmpVar[x].idUGResponsable );
                    jQuery( '#jform_idUGFuncionarioVar option' ).recorrerCombo( lstTmpVar[x].idUGFuncionario );
                    jQuery( '#jform_idUGFuncionarioVar' ).trigger( 'change', lstTmpVar[x].idFunResponsable );

                    muestroFrmNuevaVariable();
                }else{
                    //  Indicador
                    //  Recorro ComboBox de tipos de entidades 
                    jQuery( '#jform_idTpoEntidad option' ).recorrerCombo( lstTmpVar[x].idTpoEntidad );
                    
                    //  Actualizo contenido de comboBox Entidad y selecciono la entidad registrada
                    jQuery( '#jform_idTpoEntidad' ).trigger( 'change', lstTmpVar[x].idEntidad );
                    
                    //  Recorro comboBox Tipo de Unidad de Medida
                    jQuery( '#jform_idIndTpoUndMedida option' ).recorrerCombo( lstTmpVar[x].idTpoUM );
                    
                    //  Actualizo contenido de comboBox Unidad de Medida y selecciono la unidad de medida registrada
                    jQuery( '#jform_idIndTpoUndMedida' ).trigger( 'change', lstTmpVar[x].idUndMedida );

                    //  Actualizo contenido de comboBox Indicador y selecciono Indicado registrado
                    jQuery( '#jform_idIndUndMedida' ).trigger( 'change', [lstTmpVar[x].idEntidad, lstTmpVar[x].idUndMedida, lstTmpVar[x].idElemento] );

                    //  Actualizo las cajas de texto asociadas a los responsables de un indicador
                    jQuery( '#jform_idIndicador' ).trigger( 'change', [lstTmpVar[x].idEntidad, lstTmpVar[x].idElemento] );
                    
                    frmIndicador();
                    muestroFrmIndicadorVariable();
                }
                
                break;
            }
        }
     })
    
    /**
     * Gestiona la eliminacion de una variable que forma parte de la 
     * formula de un indicador
     */
    jQuery( '.delVar' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function( result ){
            if( result ){
                if( lstTmpVar[idFila].idIndVariable != 0 ){
                    lstTmpVar[idFila].published = 0;
                }else{
                    lstTmpVar.splice( idFila, 1 );
                }
                
                //  Elimino una determinada fila de la lista de variables
                delFilaLstVariables( idFila );
                
                //  Valido el numero de elementos de la tabla de variables
                validarLstVariables();
            }
        });
    })
    
    
    /**
     * Gestiona Creacion de Formula
     */
    jQuery( '.addFormula' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        var dtaFormula = jQuery( '#formulaDescripcion' ).val();
        
        dtaFormula = dtaFormula + ' ' + lstTmpVar[idFila].toString();
        jQuery( '#formulaDescripcion' ).attr( 'value', dtaFormula );
    })
    
    /**
     * Cancelo la operacion de "INSERCION" y/o "EDICION", limpiando el formulario
     */
    jQuery( '#btnCancelarVariable' ).live( 'click', function(){
        banIdRegVar = -1;

        //  Restauro a valores predeterminados formulario de registro de variables
        limpiarFrmVar();
        
        //  Oculto formulario de variables
        hideFormVariables();
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
     * Muestra Formulario de Indicador ocultando el formulario de variables
     * 
     * @returns {undefined}
     */
    function frmIndicador()
    {
        //  Oculto Formulario de Registro de Nuevas Variables
        jQuery( '#nuevaVariable' ).css( 'display', 'none' );
        jQuery( '#responsableNV' ).css( 'display', 'none' );
        
        //  Muestro Formulario de Indicador - Variable
        jQuery( '#frmIndicadorVar' ).css( 'display', 'block' );
        jQuery( '#responsablesIndicador' ).css( 'display', 'block' );
    }
    
    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaVar( fila )
    {
        jQuery( '#lstVarIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == banIdRegVar ){
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
    function delFilaLstVariables( idFila )
    {
        //  Elimino fila de la tabla lista de Rangos
        jQuery( '#lstVarIndicadores tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    
    function validarLstVariables()
    {
        var nrV = lstTmpVar.length;

        if( nrV === 0 ){
            var objVar = new Variable();
            jQuery('#lstVarIndicadores > tbody:last').append( objVar.addFilaSinRegistros() );
        }

        return false;
    }
    
    function eliminarVariable( idFila )
    {
        if( lstTmpVar[idFila].idIndVariable != 0 ){
            lstTmpVar[idFila].published = 0;
        }
        return false;
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
        //  Recorro hasta una determinada posicion el combo de Tipo de Entidad
        jQuery( '#jform_idTpoEntidad option' ).recorrerCombo( 0 );

        //  EnceroCombo Combo de Entidades
        jQuery( '#jform_idEntidad option' ).enCerarCombo();

        //  Recorro hasta una determinada posicion el combo de Indicador - Tipo de Unidad de Medida
        jQuery( '#jform_idIndTpoUndMedida option' ).recorrerCombo( 0 );

        //  Recorro hasta una determinada posicion el combo de Unidad de Analisis de la variable
        jQuery( '#jform_idUndAnalisisNV option' ).recorrerCombo( jQuery( '#jform_intIdUndAnalisis' ).val() );

        //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida de la variable
        jQuery( '#jform_idTpoUndMedidaNV option' ).recorrerCombo( jQuery( '#jform_intIdTpoUndMedida' ).val() );
        jQuery('#jform_idTpoUndMedidaNV').trigger('change', jQuery( '#jform_idUndMedida' ).val());

        //  EnceroCombo Combo de Indicador - Unidad de Medida
        jQuery( '#jform_idIndUndMedida option' ).enCerarCombo();

        //  EnceroCombo Combo de Indicadores
        jQuery( '#jform_idIndicador option' ).enCerarCombo();

        jQuery( '#jform_UGResponsable' ).attr( 'value', '' );
        jQuery( '#jform_ResponsableUG' ).attr( 'value', '' );
        jQuery( '#jform_funcionario' ).attr( 'value', '' );

        //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida
        jQuery( '#jform_idVarTpoUndMedida option' ).recorrerCombo( 0 );

        //  EnceroCombo Combo de Variable - Unidad de Medida
        jQuery( '#jform_idVarUndMedida option' ).enCerarCombo();

        //  EnceroCombo Combo de Variables
        jQuery( '#jform_idVariable option' ).enCerarCombo();

        //  Recorro hasta una determinada posicion el combo de Unidad de Analisis
        jQuery( '#jform_idUndAnalisisVar option' ).recorrerCombo( -1 );

        //  Recorro ComboBox de Unidades de Gestion Responsable
        jQuery('#jform_idUGResponsableVar option').recorrerCombo( jQuery( '#jform_intIdUndGestion' ).val());

        //  Recorro ComboBox de Unidades de Gestion del Funcionario - Responsable
        jQuery('#jform_idUGFuncionarioVar option').recorrerCombo( jQuery( '#jform_intIdUGResponsable' ).val());
        jQuery('#jform_idUGFuncionarioVar').trigger('change', jQuery( '#jform_idResponsable' ).val());

        jQuery( '#jform_nombreNV' ).attr( 'value', '' );
        jQuery( '#jform_aliasNV' ).attr( 'value', '' );
        jQuery( '#jform_descripcionNV' ).attr( 'value', '' );

        //  Oculto Formulario de Indicadores
        jQuery( '#frmIndicadorVar' ).css( 'display', 'none' );
        jQuery( '#responsablesIndicador' ).css( 'display', 'none' );
        jQuery( '#lblElemento' ).html( COM_INDICADORES_ELEMENTO_VARIABLE );

        //  Muestro Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'block' );
        jQuery('#responsableNV').css( 'display', 'block' );

        jQuery( '#jform_idEntidad-lbl' ).html( COM_INDICADORES_ELEMENTO + ' '+ '<span class="star">&nbsp;*</span>' );
        
    }
})