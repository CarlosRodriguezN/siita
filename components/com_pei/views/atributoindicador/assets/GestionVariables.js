jQuery(document).ready(function() {
    var banIdRegVar = -1;
    var banNV = 0;

    /**
     *  Agrego un registro de una nueva variable
     */
    jQuery('#btnAddVariable').live('click', function() {
        
        var objVariable;
        
        if( banIdRegVar == -1 ){
            objVariable = new Variable();
            objVariable.idRegVar = lstTmpVar.length;
        }else{
            objVariable = lstTmpVar[banIdRegVar];
            banNV = lstTmpVar[banIdRegVar].ban;
        }

        //  Gestiono informacion de una NUEVA variable
        objVariable.idTpoEntidad    = jQuery( '#jform_idTpoEntidad' ).val();
        objVariable.idEntidad       = jQuery( '#jform_idEntidad' ).val();

        //  1: Variable - 2:Indicador
        objVariable.idTpoElemento   = ( objVariable.idEntidad == 0 )? 1
                                                                    : 2;

        if( objVariable.idTpoElemento == 1 ){
            //  Variable
            objVariable.idTpoEntidad    = jQuery( '#jform_idTpoEntidad' ).val();
            objVariable.idEntidad       = jQuery( '#jform_idEntidad' ).val();
            objVariable.nombre          = jQuery( '#jform_nombreNV' ).val();
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
        
        if( banIdRegVar == -1 ){
            lstTmpVar.push( objVariable );

            //  Agrego la fila creada a la tabla
            jQuery( '#lstVarIndicadores > tbody:last' ).append( objVariable.addFilaVar( 0 ) );
        }else{
            updFilaVar( objVariable.addFilaVar( 1 ) );
        }
        
        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmVar();
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
            if( lstTmpVar[x].toString() == variable.toString() ){
                ban = 1;
            }
        }
        
        return ban;
    }
    
    /**
     * Muestro la caja de texto para el registro de una nueva variable
     */
    jQuery( '#jform_idVariable' ).live( 'change', function(){
        if( jQuery( this ).val() == 0 ){
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
        
        for( var x = 0; x < lstTmpVar.length; x++ ){
            if( lstTmpVar[x].idRegVar == banIdRegVar ){
                
                if( lstTmpVar[x].idTpoElemento == 1 ){
                    //  Variable
                    
                    jQuery( '#jform_nombreNV' ).attr( 'value', lstTmpVar[x].nombre );
                    
                    jQuery( '#jform_descripcionNV' ).attr( 'value', lstTmpVar[x].descripcion );
                    
                    recorrerComboVar( jQuery( '#jform_idUndAnalisisNV option' ), lstTmpVar[x].idUndAnalisis );
                    
                    recorrerComboVar( jQuery( '#jform_idTpoUndMedidaNV option' ), lstTmpVar[x].idTpoUM );
                    
                    jQuery( '#jform_idTpoUndMedidaNV' ).trigger( 'change', lstTmpVar[x].idUndMedida );
                    
                    recorrerComboVar( jQuery( '#jform_idUGResponsableVar option' ), lstTmpVar[x].idUGResponsable );
                    
                    recorrerComboVar( jQuery( '#jform_idUGFuncionarioVar option' ), lstTmpVar[x].idUGFuncionario );

                    jQuery( '#jform_idUGFuncionarioVar' ).trigger( 'change', lstTmpVar[x].idFunResponsable );

                    frmNuevaVariable();
                }else{
                    //  Indicador
                    //  Recorro ComboBox de tipos de entidades 
                    recorrerComboVar( jQuery( '#jform_idTpoEntidad option' ), lstTmpVar[x].idTpoEntidad );
                    
                    //  Actualizo contenido de comboBox Entidad y selecciono la entidad registrada
                    jQuery( '#jform_idTpoEntidad' ).trigger( 'change', lstTmpVar[x].idEntidad );
                    
                    //  Recorro comboBox Tipo de Unidad de Medida
                    recorrerComboVar( jQuery( '#jform_idIndTpoUndMedida option' ), lstTmpVar[x].idTpoUM );
                    
                    //  Actualizo contenido de comboBox Unidad de Medida y selecciono la unidad de medida registrada
                    jQuery( '#jform_idIndTpoUndMedida' ).trigger( 'change', lstTmpVar[x].idUndMedida );

                    //  Actualizo contenido de comboBox Indicador y selecciono Indicado registrado
                    jQuery( '#jform_idIndUndMedida' ).trigger( 'change', [lstTmpVar[x].idEntidad, lstTmpVar[x].idUndMedida, lstTmpVar[x].idElemento] );

                    //  Actualizo las cajas de texto asociadas a los responsables de un indicador
                    jQuery( '#jform_idIndicador' ).trigger( 'change', [lstTmpVar[x].idEntidad, lstTmpVar[x].idElemento] );
                    
                    frmIndicador();
                }
                
                break;
            }
        }
     })
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delRG' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm( "Esta Seguro de Eliminar este Rango", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpVar.splice( idFila, 1 );
                delFilaRG( idFila );
            }
        });
    })
    
    
    /**
     * Cancelo la operacion de "INSERCION" y/o "EDICION", limpiando el formulario
     */
    jQuery( '#btnCancelarVariable' ).live( 'click', function(){
        banIdRegVar = -1;

        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmVar();
    })
    
    /**
     * Gestion de Formulario de Nueva Variable
     */
    jQuery( '#btnVariableNueva' ).live( 'click', function(){
        jQuery( '#lblElemento' ).html( COM_PEI_ELEMENTO_VARIABLE );
        limpiarFrmVar();
        
        frmNuevaVariable();
    })
    
    /**
     * 
     * Muestro Formulario de una nueva variable
     * 
     * @returns {undefined}
     * 
     */
    function frmNuevaVariable()
    {
        //  Oculto Parte del Formulario de Indicador - Variable
        jQuery('#frmIndicadorVar').css( 'display', 'none' );
        jQuery('#responsablesIndicador').css( 'display', 'none' );
        
        //  Muestro Formulario de Registro de Nuevas Variables
        jQuery('#nuevaVariable').css( 'display', 'block' );
        jQuery('#responsableNV').css( 'display', 'block' );
        
        banNV = 1;

        banIdRegVar = ( banIdRegVar != -1 ) ? banIdRegVar 
                                            : -1;
    }
    
    /**
     * 
     * Muestra Formulario de Indicador ocultando el formulario de variables
     * 
     * @returns {undefined}
     */
    function frmIndicador()
    {
        
        //  Oculto Formulario de Registro de Nuevas Variables
        jQuery('#nuevaVariable').css( 'display', 'none' );
        jQuery('#responsableNV').css( 'display', 'none' );
        
        //  Muestro Formulario de Indicador - Variable
        jQuery('#frmIndicadorVar').css( 'display', 'block' );
        jQuery('#responsablesIndicador').css( 'display', 'block' );
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
    function delFilaRG( idFila )
    {
        //  Elimino fila de la tabla lista de Rangos
        jQuery( '#lstVarIndicadores tr' ).each( function(){
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
    function recorrerComboVar(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }
    
    /**
     * 
     * Vacia en contenido de un determinado comboBox
     * 
     * @param {type} combo
     * @returns {undefined}
     */
    function enCerarComboVar(combo)
    {
        //  Recorro contenido del combo
        jQuery(combo).each(function() {
            if (jQuery(this).val() > 0 ) {
                //  Actualizo contenido del combo
                jQuery(this).remove();
            }
        });
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
        recorrerComboVar( jQuery( '#jform_idTpoEntidad option' ), 0 );
        
        //  EnceroCombo Combo de Entidades
        enCerarComboVar( jQuery( '#jform_idEntidad option' ) );
        
        //  Recorro hasta una determinada posicion el combo de Indicador - Tipo de Unidad de Medida
        recorrerComboVar( jQuery( '#jform_idIndTpoUndMedida option' ), 0 );
        
        //  EnceroCombo Combo de Indicador - Unidad de Medida
        enCerarComboVar( jQuery( '#jform_idIndUndMedida option' ) );
        
        //  EnceroCombo Combo de Indicadores
        enCerarComboVar( jQuery( '#jform_idIndicador option' ) );
        
        jQuery( '#jform_UGResponsable' ).attr( 'value', '' );
        jQuery( '#jform_ResponsableUG' ).attr( 'value', '' );
        jQuery( '#jform_funcionario' ).attr( 'value', '' );
        
        //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida
        recorrerComboVar( jQuery( '#jform_idVarTpoUndMedida option' ), 0 );
        
        //  EnceroCombo Combo de Variable - Unidad de Medida
        enCerarComboVar( jQuery( '#jform_idVarUndMedida option' ) );
        
        //  EnceroCombo Combo de Variables
        enCerarComboVar( jQuery( '#jform_idVariable option' ) );
        
        //  Recorro hasta una determinada posicion el combo de Unidad de Analisis
        recorrerComboVar( jQuery( '#jform_idUndAnalisisVar option' ), -1 );
        
        //  Recorro ComboBox de Unidades de Gestion Responsable
        recorrerComboVar( jQuery( '#jform_idUGResponsableVar option' ), 0 );
        
        //  Recorro ComboBox de Unidades de Gestion del Funcionario - Responsable
        recorrerComboVar( jQuery( '#jform_idUGFuncionarioVar option' ), 0 );
        
        //  Vacio Combo Box de Funcionario Responsable
        enCerarComboVar( jQuery( '#jform_idFunResponsableVar option' ) );
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_nombreNV' ).attr( 'value', '' );
        
        jQuery( '#jform_descripcionNV' ).attr( 'value', '' );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#frmIndicadorVar').css( 'display', 'block' );
        jQuery('#responsablesIndicador').css( 'display', 'block' );
        jQuery( '#lblElemento' ).html( COM_PEI_ELEMENTO_INDICADOR );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'none' );
        jQuery('#responsableNV').css( 'display', 'none' );
    }
})