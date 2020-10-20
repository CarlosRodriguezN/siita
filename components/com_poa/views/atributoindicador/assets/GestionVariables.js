jQuery(document).ready(function() {
    var banIdRegVar = -1;
    var banNV = 0;
    
    if( lstTmpVar.length > 0 ){
        //  Actualizo tabla de Rangos de Gestion   
        for( var x = 0; x < lstTmpVar.length; x++  ){
            //  Agrego la fila creada a la tabla
            jQuery( '#lstVarIndicadores > tbody:last' ).append( addFilaVar( lstTmpVar[x], 0 ) );
        }
    }
    
    //  Obtengo URL completa del sitio
    var url = window.location.href;
    var path = url.split('?')[0];

    //  Muestro Formulario de Asiganacion de Variables
    jQuery('#frmVariable').css( 'display', 'block' );
    
    //  Oculto Formulario de Asiganacion de Variables
    jQuery('#nuevaVariable').css( 'display', 'none' );

    /**
     *  Agrego un registro de unidad territorial
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

        if( banNV == 1 ){
            //  Gestiono informacion de una NUEVA variable
            objVariable.nombre = jQuery( '#jform_nombreNV' ).val();
            objVariable.descripcion = jQuery( '#jform_descripcionNV' ).val();
            objVariable.idUndAnalisis = jQuery( '#jform_idUndAnalisisNV' ).val();
            objVariable.undAnalisis = jQuery( '#jform_idUndAnalisisNV :selected' ).text();
            
            objVariable.idTpoUM = jQuery( '#jform_idTpoUndMedidaNV' ).val();

            objVariable.idUndMedida = jQuery( '#jform_idVarUndMedidaNV' ).val();
            objVariable.undMedida = jQuery( '#jform_idVarUndMedidaNV :selected' ).text();
            
            objVariable.ban = 1;
        }else{
            //  Gestiono informacion de una variable existente
            objVariable.idTpoUM = jQuery( '#jform_idVarTpoUndMedida' ).val();
            objVariable.idUndMedida = jQuery( '#jform_idVarUndMedida' ).val();
            objVariable.idVariable = jQuery( '#jform_idVariable' ).val();
            objVariable.nombre = jQuery( '#jform_idVariable :selected' ).text();
            objVariable.undMedida = jQuery( '#jform_idVarUndMedida :selected' ).text();
        }
        
        objVariable.idUGResponsable = jQuery( '#jform_idUGResponsableVar' ).val();
        objVariable.idUGFuncionario = jQuery( '#jform_idUGFuncionarioVar' ).val();
        objVariable.idFunResponsable = jQuery( '#jform_idFunResponsableVar' ).val();
        
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
                
                if( lstTmpVar[x].ban == 0 ){
                    //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida
                    recorrerComboVar( jQuery( '#jform_idVarTpoUndMedida option' ), lstTmpVar[x].idTpoUM );
                    
                    //  Actualizo comboBox de Unidad de Medida
                    jQuery( '#jform_idVarTpoUndMedida' ).trigger( 'change', lstTmpVar[x].idUndMedida );
                    
                    //  Actualizo comboBox de Unidad de Medida
                    jQuery( '#jform_idVarUndMedida' ).trigger( 'change', [lstTmpVar[x].idUndMedida, lstTmpVar[x].idVariable] );
                }else{

                    jQuery( '#jform_nombreNV' ).attr( 'value', lstTmpVar[x].nombre );
                    
                    jQuery( '#jform_descripcionNV' ).attr( 'value', lstTmpVar[x].descripcion );
                    
                    //  Recorro hasta una determinada posicion el combo de Unidad de Analisis
                    recorrerComboVar( jQuery( '#jform_idUndAnalisisNV option' ), lstTmpVar[x].idUndAnalisis );

                    //  Recorro hasta una determinada posicion el combo de Unidad de Analisis
                    recorrerComboVar( jQuery( '#jform_idTpoUndMedidaNV option' ), lstTmpVar[x].idTpoUM );
                    
                    //  Actualizo comboBox de Unidad de Medida
                    jQuery( '#jform_idTpoUndMedidaNV' ).trigger( 'change', lstTmpVar[x].idUndMedida );
                    
                    //  Muestro el formulario de una nueva variable
                    frmNuevaVariable();
                }
                
                //  Recorro hasta una determinada posicion el combo de Unidad de Gestion - Responsable
                recorrerComboVar( jQuery( '#jform_idUGResponsableVar option' ), lstTmpVar[x].idUGResponsable );
                
                //  Recorro hasta una determinada posicion el combo de Unidad de Gestion - Responsable
                recorrerComboVar( jQuery( '#jform_idUGFuncionarioVar option' ), lstTmpVar[x].idUGFuncionario );
                
                //  Actualizo comboBox de Unidad de Medida
                jQuery( '#jform_idUGFuncionarioVar' ).trigger( 'change', lstTmpVar[x].idFunResponsable );
                
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
    jQuery( '#btnNuevaVariable' ).live( 'click', function(){
        frmNuevaVariable();
    })
    
    /**
     * 
     * Muestro Formulario de una nueva variable
     * 
     * @returns {undefined}
     */
    function frmNuevaVariable()
    {
        //  Oculto Parte del Formulario de Variables
        jQuery('#frmVariable').css( 'display', 'none' );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'block' );
        
        banNV = 1;

        banIdRegVar = ( banIdRegVar != -1 ) ? banIdRegVar 
                                            : -1;
                                            
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
        
        //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida
        recorrerComboVar( jQuery( '#jform_idVarTpoUndMedida option' ), 0 );
        
        //  EnceroCombo Combo de Unidad de Medida
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
        jQuery('#frmVariable').css( 'display', 'block' );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'none' );
    }
})