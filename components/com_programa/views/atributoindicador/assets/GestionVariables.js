jQuery(document).ready(function() {
    var banIdRegVar = -1;
    
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
        var idVariable;
        var nombreVariable;
        
        var idUndAnalisis = '';
        var undAnalisis = '------'; 
        var ban = 0;
        
        if( jQuery( '#jform_idVariable' ).val() == 0 ){
            idVariable = jQuery( '#jform_idVariable' ).val();
            nombreVariable = jQuery( '#jform_nombreVar' ).val();
            
            idUndAnalisis = jQuery( '#jform_idUndAnalisisVar' ).val();
            undAnalisis = jQuery( '#jform_idUndAnalisisVar :selected' ).text();
            
            ban = 1;
        }else{
            idVariable = jQuery( '#jform_idVariable' ).val();
            nombreVariable = jQuery( '#jform_idVariable :selected' ).text();
        }
        
        var idIndicador = jQuery( '#idIndicador' ).val();
        var idTpoUM = jQuery( '#jform_idVarTpoUndMedida' ).val();
        var idUndMedida = jQuery( '#jform_idVarUndMedida' ).val();
        var undMedida = jQuery( '#jform_idVarUndMedida :selected' ).text();
        var descripcion = jQuery( '#jform_descripcionOV' ).val();

        var idRegVar = ( banIdRegVar != -1 )? banIdRegVar 
                                            : lstTmpVar.length;

        var objVariable = new Variable( idRegVar, 0, idIndicador, idVariable, nombreVariable, descripcion, idTpoUM, idUndMedida, undMedida, idUndAnalisis, undAnalisis, ban );

        if( existeVariable( objVariable ) == 0 ){
            if( banIdRegVar != -1 ){
                lstTmpVar[banIdRegVar] = objVariable;
                updFilaVar( addFilaVar( objVariable, 1 ) );

                banIdRegVar = -1;
            }else{
                lstTmpVar.push( objVariable );
                var filaVar = addFilaVar( objVariable, 0 );

                //  Agrego la fila creada a la tabla
                jQuery( '#lstVarIndicadores > tbody:last' ).append( filaVar );
            }
        }else{
            jAlert( 'Variable, ya Registrada', 'SIITA - ECORAE' );
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
                
                //  Recorro hasta una determinada posicion el combo de Tipo de Unidad de Medida
                recorrerComboVar( jQuery( '#jform_idVarTpoUndMedida option' ), lstTmpVar[x].idTpoUM );
                
                //  Actualizo comboBox de Unidad de Medida
                jQuery( '#jform_idVarTpoUndMedida' ).trigger( 'change', lstTmpVar[x].idUndMedida );
                
                //  Actualizo comboBox de Unidad de Medida
                jQuery( '#jform_idVarUndMedida' ).trigger( 'change', [lstTmpVar[x].idUndMedida, lstTmpVar[x].idVariable] );
                
                if( lstTmpVar[x].ban == 0 ){
                    //  Oculto informacion adicional del formulario
                    jQuery('#otraVariable').css( 'display', 'none' );
                }else{
                    //  Recorro hasta una determinada posicion el combo de Unidad de Analisis
                    recorrerComboVar( jQuery( '#jform_idUndAnalisisVar option' ), lstTmpVar[x].idUndAnalisis );
                    
                    //  Actualizo Nombre de la nueva variable
                    jQuery( '#jform_nombreVar' ).attr( 'value', lstTmpVar[x].nombre );
                    
                    //  Actualizo Descripcion de la nueva variable
                    jQuery( '#jform_descripcionOV' ).attr( 'value', lstTmpVar[x].descripcion );
                    
                    //  Muestro informacion adicional del formulario
                    jQuery('#otraVariable').css( 'display', 'block' );
                }
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
        //  Oculto Parte del Formulario de Variables
        jQuery('#frmVariable').css( 'display', 'none' );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'block' );  
    })
    
    /**
     * 
     * Agrego una fila en la table Linea Base
     * 
     * @param {type} variable 
     * @returns {undefined}
     */
    function addFilaVar( variable, ban )
    {
        //  Construyo la Fila
        var fila = ( ban == 0 ) ? "<tr id='"+ variable.idRegVar +"'>" 
                                : "";
        
        fila += "   <td align='center'>"+ variable.nombre +"</td>"
                + " <td align='center'>"+ variable.undMedida +"</td>"
                + " <td align='center'>"+ variable.undAnalisis +"</td>"
                + " <td align='center'> <a class='updVar'> Editar </a> </td>"
                + " <td align='center'> <a class='delVar'> Eliminar </a> </td>";

        fila += ( ban == 0 )?  "</tr>" 
                            : "";

        return fila;
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
    
    //  Resetea los valores de un combo determinado
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
        
        //  Vacio contenido de valor de Linea Base
        jQuery( '#jform_nombreVar' ).attr( 'value', '' );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#frmVariable').css( 'display', 'block' );
        
        //  Oculto Parte del Formulario de Variables
        jQuery('#nuevaVariable').css( 'display', 'none' );
    }
})