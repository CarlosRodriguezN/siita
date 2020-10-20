jQuery(document).ready(function () {
    
    /**
     * Clase que gestiona nuevos registros en el mantenimiento de linea base
     */
    jQuery(".saveReg").live("click", function () {
        var idField = (jQuery(this).parent().parent()).attr('id');
        var fuente = 0;
        var liBase = 0;
        var valor = 0;
        
        switch (true){
            case (idField =="li-jform_idFuenteNew"):
                if ( jQuery("#jform_idFuenteNew").val() != ''){
                    fuente = jQuery("#jform_idFuenteNew").val();
                    mantenimientoLineBase(fuente, liBase, valor, 1);
                }
                break;
            case (idField =="li-jform_idLineaBaseNew"):
                if (jQuery("#jform_idLineaBaseNew").val() != '' && jQuery("#jform_valorLineaBaseUpd").val() != '' ){
                    fuente = jQuery("#jform_idFuente :selected").val();
                    liBase = jQuery("#jform_idLineaBaseNew").val();
                    valor = jQuery("#jform_valorLineaBaseUpd").val();
                    mantenimientoLineBase(fuente, liBase, valor, 2);
                }
                break;
            case (idField =="li-jform_valorLineaBaseUpd"):
                if( jQuery("#jform_valorLineaBaseUpd").val() != '' ){
                    fuente = jQuery("#jform_idFuente :selected").val();
                    liBase = jQuery("#jform_idLineaBase :selected").val();
                    valor = jQuery("#jform_valorLineaBaseUpd").val();
                    mantenimientoLineBase(fuente, liBase, valor, 3);
                }
                break;
        }
    });
    
    /**
     * Clase que cancela la ecicion o ingreso de registros del mantenimeinto de linea base
     */
    jQuery(".cancelReg").live("click", function () {
        var idFieldGestion = (jQuery(this).parent().parent()).attr('id');
        var n = idFieldGestion.length - 3;
        var idField = idFieldGestion.substr(0,n);
        
        switch (true){
            case (idField =="li-jform_idFuente"):
                habilitarNewField (idField, "New", 0);
                break;
            case (idField =="li-jform_idLineaBase"):
                habilitarNewField (idField, "New", 0);
                makeFieldValorLB (1);
                habilitarNewField ("li-jform_valorLineaBase", "Upd", 0);
                break;
            case (idField =="li-jform_valorLineaBase"):
                habilitarNewField (idField, "Upd", 0);
                break;
        }
    });
    
    /**
     * Clase que actulisa el valor de una determinada linea base
     */
    jQuery(".updReg").live("click", function () {
        var idField = (jQuery(this).parent().parent()).attr('id');
        var frmField = idField.substr(3);
        if( jQuery("#"+frmField).val() != "" ){
            habilitarNewField (idField, "Upd", 1);
            jQuery("#"+frmField+"Upd").val(jQuery("#"+frmField).val());
        }
    });
    
    jQuery(".newReg").live("click", function () {
        var idField = (jQuery(this).parent().parent()).attr('id');
        switch (true) {
            case ( idField == 'li-jform_idFuente'):
                habilitarNewField( idField, "New", 1);
                break;
            case ( idField == 'li-jform_idLineaBase'):
                if (jQuery("#jform_idFuente :selected").val() != 0) {
                    habilitarNewField( idField, "New", 1);
                    makeFieldValorLB (0);
                    habilitarNewField ("li-jform_valorLineaBase", "Upd", 1);
                } 
                break;  
        }
    });
    
    
    /**
     *  Hbilita o desabilita el Field de un nuevo registro
     * @param {type} field      Id del Field
     * @param {type} accion     Accion que se va a realizar
     * @param {type} op         Opcion para habiltar = 1 y desabilitar = 0 
     * @returns {undefined}
     */
    function habilitarNewField( field, accion, op)
    {
        switch (op){
            case 0:
                jQuery("#"+field).css("display", "block");
                jQuery("#"+field+accion).css("display", "none");
                break;
            case 1:
                jQuery("#"+field).css("display", "none");
                jQuery("#"+field+accion).css("display", "block");
                break;
        }
    }
    
    /**
     * Arma el HTML del campo valor de la lina base, dependiendo de la opcion para guardado
     * @param {type} op         Opcion para el guadado del valor de Linea Base 1:habilita los botones, 0=no 
     * @returns {undefined}
     */
    function makeFieldValorLB( op )
    {
        var htmlValorLB = '';
        htmlValorLB += '<label id="jform_valorLineaBaseUpd-lbl" for="jform_valorLineaBaseUpd" class="hasTip" title="">Valor</label>';
        htmlValorLB += '<input type="text" name="jform[valorLineaBaseUpd]" id="jform_valorLineaBaseUpd" value="" class="required" size="15">';
        if ( op == 1 ){
            htmlValorLB += '<span>';
            htmlValorLB += '    <a href="#" class="saveReg"> <img src="/media/system/images/mantenimiento/save.png" title="Guardar"> </a>';
            htmlValorLB += '</span>';
            htmlValorLB += '<span>';
            htmlValorLB += '    <a href="#" class="cancelReg"> <img src="/media/system/images/mantenimiento/close.png" title="Cancelar"> </a>';
            htmlValorLB += '</span>';
        }
        jQuery("#li-jform_valorLineaBaseUpd").html(htmlValorLB);
    }
    
    
    function mantenimientoLineBase(fuente, liBase, valor, op)
    {
        var url = window.location.href;
        var path = url.split( '?' )[0];
        
        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data:{  option: 'com_mantenimiento',
                                view: 'lineabase',
                                tmpl: 'component',
                                format: 'json',
                                action: 'mntLineaBase',
                                fuente: fuente,
                                liBase: liBase,
                                valor: valor,
                                op: op
                        },
                        error : function( jqXHR, status, error ) {
                            alert( 'Mantenimiento - Linea Base' + error +' '+ jqXHR +' '+ status );
                        }
        }).complete( function( data ){
            var dataInfo = eval("(" + data.responseText + ")");
            switch (op){
                case 1:
                    updFieldFuente( dataInfo );
                    break;
                case 2:
                    updFieldLineaBase( dataInfo );
                    break;
                case 3:
                    updFieldValorLB( dataInfo );
                    break;
            }
                
        });
    }
    
    
    function updFieldFuente( data )
    {
        var opcion = '';
        opcion += '<option value="' + data.idFuente + '">' + data.descFuente + '</option>';
        jQuery("#jform_idFuente").append(opcion);
        jQuery("#jform_idFuenteNew").val('');
        habilitarNewField( "li-jform_idFuente", "New", 0);
        recorrerCombo("#jform_idFuente", 0);
    }
    
    function updFieldLineaBase( data )
    {
//        var opcion = '';
//        opcion += '<option value="' + data.idFuente + '">' + data.descFuente + '</option>';
//        jQuery("#jform_idLineaBase").append(opcion);
        jQuery("#jform_idLineaBaseNew").val('');
        jQuery( '#jform_idFuente' ).trigger( 'change', [data.idFuente, 0] );
        habilitarNewField( "li-jform_idLineaBase", "New", 0);
        makeFieldValorLB (1);
        habilitarNewField ("li-jform_valorLineaBase", "Upd", 0);
        
    }
    
    function updFieldValorLB( data )
    {
        jQuery("#jform_valorLineaBase").val(data.valorLB);
        habilitarNewField( "li-jform_valorLineaBase", "Upd", 0);
    }
    
    
    /**
     *  Recorre los comboBox del Formulario a la posicion inicial
     * @param {type} combo      Objeto ComboBox
     * @param {type} posicion   Posicion a la que el combo va a recorrer
     * @returns {undefined}
     */
    function recorrerCombo(combo, posicion){
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        })
    }
});
