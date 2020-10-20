jQuery(document).ready(function() {
    var banIdRegVar = -1;
    var banNV = 0;
    var tpoElemento;
    
    /**
     *  Agrego un registro de una nueva variable
     */
    jQuery('#btnAddVariable').live('click', function() {
        var objVariable;       
        objVariable = lstTmpVar[banIdRegVar];

        //  Gestiono informacion de la variable
        objVariable.idTpoEntidad    = jQuery( '#jform_idTpoEntidad' ).val();
        objVariable.idEntidad       = jQuery( '#jform_idEntidad' ).val();

        //
        //  Si tenemos valor en el campo entidad sabemos que estamos registrando 
        //  un Indicador como variable
        //
        objVariable.idTpoElemento   = getTpoElemento( parseInt( objVariable.idEntidad ), parseInt( banIdRegVar ) );

        switch( true ){
            case ( objVariable.idTpoElemento === 1 || objVariable.idTpoElemento === 2 ):
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
                
            break;
            
            case ( objVariable.idTpoElemento === 3 || objVariable.idTpoElemento === 4 ):
                //  Indicador
                objVariable.nombre          = jQuery( '#jform_idIndicador :selected' ).text();
                objVariable.idTpoUM         = jQuery( '#jform_idIndTpoUndMedida' ).val();
                objVariable.idUndMedida     = jQuery( '#jform_idIndUndMedida' ).val();
                objVariable.undMedida       = jQuery( '#jform_idIndUndMedida :selected' ).text();
                objVariable.idElemento      = jQuery( '#jform_idIndicador' ).val();

                objVariable.UGResponsable   = jQuery( '#jform_UGResponsable' ).val();
                objVariable.UGFuncionario   = jQuery( '#jform_ResponsableUG' ).val();
                objVariable.FunResponsable  = jQuery( '#jform_funcionario' ).val();
            break;
        }
        
        
        lstTmpVar[banIdRegVar] = objVariable;
        updDtaVar( objVariable );

        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmVar();
    })
    
    
    
    function getTpoElemento( idEntidad, bIdRegVar )
    {
        var idTpoElemento = 0;
        
        switch( true ){
            //  Numerador de tipo variable
            case ( idEntidad === 0 && bIdRegVar === 0 ): 
                idTpoElemento = 1;
            break;
            
            //  Denominador de tipo variable
            case ( idEntidad === 0 && bIdRegVar === 1 ): 
                idTpoElemento = 2;
            break;
            
            //  Numerador de tipo Indicador
            case ( idEntidad !== 0 && bIdRegVar === 0 ): 
                idTpoElemento = 3;
            break;
            
            //  Denominador de tipo Indicador
            case ( idEntidad !== 0 && bIdRegVar === 1 ): 
                idTpoElemento = 4;
            break;
        }
        
        return idTpoElemento;
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
     * Gestiono la acualizacion los elementos de la formula
     */
    jQuery( '.updVar' ).live( 'click', function(){
        limpiarFrmVar();
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        banIdRegVar = idFila;

        //  Registro el tipo de elemento, 0: Numerador, 1: Denominador
        tpoElemento = idFila;

        if( typeof( lstTmpVar[banIdRegVar] ) != "undefined" ){
            var x = banIdRegVar;
            
            switch( true ){
                //  Variable                    
                case( parseInt( lstTmpVar[x].idTpoElemento ) === 1 || parseInt( lstTmpVar[x].idTpoElemento ) === 2 ):

                    jQuery( '#jform_nombreNV' ).attr( 'value', lstTmpVar[x].nombre );
                    jQuery( '#jform_descripcionNV' ).attr( 'value', lstTmpVar[x].descripcion );
                    recorrerComboVar( jQuery( '#jform_idUndAnalisisNV option' ), lstTmpVar[x].idUndAnalisis );
                    recorrerComboVar( jQuery( '#jform_idTpoUndMedidaNV option' ), lstTmpVar[x].idTpoUM );

                    jQuery( '#jform_idTpoUndMedidaNV' ).trigger( 'change', lstTmpVar[x].idUndMedida );
                    recorrerComboVar( jQuery( '#jform_idUGResponsableVar option' ), lstTmpVar[x].idUGResponsable );
                    recorrerComboVar( jQuery( '#jform_idUGFuncionarioVar option' ), lstTmpVar[x].idUGFuncionario );

                    jQuery( '#jform_idUGFuncionarioVar' ).trigger( 'change', lstTmpVar[x].idFunResponsable );
                break;
                    
                //  Indicador
                case( parseInt( lstTmpVar[x].idTpoElemento ) === 3 || parseInt( lstTmpVar[x].idTpoElemento ) === 4 ):

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
                    frmNuevaVariable();
                break;        
            }
        }else{
            var objVariable             = new Variable();
            objVariable.idRegVar        = banIdRegVar;
            objVariable.idIndVariable   = 0;

            lstTmpVar[banIdRegVar] = objVariable;
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
                
                delFilaLstVariables( idFila );
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

        //  Restauro a valores predeterminados formulario de registro de lineas base
        limpiarFrmVar();
    })
    
    /**
     * Gestion de Formulario de Nueva Variable
     */
    jQuery( '#btnVariableNueva' ).live( 'click', function(){
        limpiarFrmVar();
        frmNuevaVariable();

        //  Cambio el nombre del formulario de Indicadores a Variables
        jQuery( '#lblElemento' ).html( COM_INDICADORES_ELEMENTO_INDICADOR );
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
     * Muestro Formulario para agregar un Indicador como variable de una formula
     * 
     * @returns {undefined}
     * 
     */
    function frmNuevaVariable()
    {
        //  Oculto Parte del Formulario de Indicador - Variable
        jQuery('#frmIndicadorVar').css( 'display', 'block' );
        jQuery('#responsablesIndicador').css( 'display', 'block' );
        
        //  Muestro Formulario de Registro de Nuevas Variables
        jQuery('#nuevaVariable').css( 'display', 'none' );
        jQuery('#responsableNV').css( 'display', 'none' );
        
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
     * Actualizo informacion de la variable
     * 
     * @param {Object} objVariable   Objeto con datos de la variable
     * 
     * @returns {undefined}
     * 
     */
    function updDtaVar( objVariable )
    {
        switch( true ){
            //  Numerador                   
            case ( objVariable.idTpoElemento === 1 || objVariable.idTpoElemento === 3 ): 
                jQuery( '#numerador' ).attr( 'value', objVariable.nombre );
            break;
                
            //  Denominador
            case ( objVariable.idTpoElemento === 2 || objVariable.idTpoElemento === 4 ): 
                jQuery( '#denominador' ).attr( 'value', objVariable.nombre );
            break;
        }
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
        
        //  Recorro hasta una determinada posicion el combo de Unidad de Analisis de la variable
        recorrerComboVar( jQuery( '#jform_idUndAnalisisNV option' ), 0 );
        
        //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida de la variable
        recorrerComboVar( jQuery( '#jform_idTpoUndMedidaNV option' ), 0 );
        
        //  EnceroCombo Combo de Unidad de Medida de la variable
        enCerarComboVar( jQuery( '#jform_idVarUndMedidaNV option' ) );
        
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
        
        //  Oculto Formulario de Indicadores
        jQuery( '#frmIndicadorVar' ).css( 'display', 'none' );
        jQuery( '#responsablesIndicador' ).css( 'display', 'none' );
        jQuery( '#lblElemento' ).html( COM_INDICADORES_ELEMENTO_VARIABLE );

        //  Muestro Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'block' );
        jQuery('#responsableNV').css( 'display', 'block' );
    }
})