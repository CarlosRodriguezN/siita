jQuery(document).ready(function() {
    
    //  Carga la lista de items con la estructura vigente
    listarItems();
    
    //  Carga la esturctura vigente en el caso de que se haya modificado
    jQuery("#controlItmEtr").click( function() {
        if ( updEstructura === 1 ){
            estructuraVigente();
            listarItems();
        }
    });
    
    /**
     *  Clase que crea nuevos registros para los items
     */
    jQuery(".newItem").live("click", function() {
        var item = jQuery(this).parent().attr('id');
        var regEtr = jQuery(this).parent().attr('value');
        if (avalibleEstr.length > 0){
            gestionItem( item, regEtr, 0 );
        } else {
            alert(JSL_ALERT_NO_EVALIBLE_ETR, JSL_ECORAE);
        }
    });
        
    /*
     *  Clase que actualiza la informacion de un ITEM
     */
    jQuery(".updItem").live("click", function() {
        var item = jQuery(this).parent().attr('id');
        var regEtr = jQuery(this).parent().attr('value');
        gestionItem( item, regEtr, 1 );
    });

    //  Carga la esturctura vigente en el caso de que se haya modificado
    jQuery(".delItem").live("click", function() {
        var item = jQuery(this).parent().attr('id');
        eliminarItem( item );
    });

    /**
     *  Gestion la data de un item 
     * @param {type} idItem
     * @param {type} regEtr
     * @param {type} op
     * @returns {undefined}
     */
    function gestionItem( idItem, regEtr, op )
    {
        var arrayIds = new Array();
        if ( idItem != 'nuevoItem'){
            arrayIds = idItem.split('-');
            arrayIds.shift();
            arrayIds = JSON.stringify(arrayIds);
            
        } 
        SqueezeBox.fromElement('index.php?option=com_mantenimiento&view=itemagd&layout=edit&idItem=' + op + '&regEstructura=' + regEtr + '&owners=' + arrayIds + '&tmpl=component&task=preview', 
        {size:{x:700,y:400}, handler:'iframe'} );
    }

    function eliminarItem( item )
    {
        var arrayIds = new Array();
        if ( item.length > 0 ){
            arrayIds = item.split('-');
            arrayIds.shift();
        } 
        
        var tmpLstItems = objLstItemsAgd.lstItemsAgd;
        var tmpItem = null;
        for (var i=0; i<arrayIds.length; i++){
            tmpItem = tmpLstItems[arrayIds[i]];
            if ( i<arrayIds.length-1 ){
                tmpLstItems = tmpItem.itemsHijos;
            }
        }
        
        if( avalibleDelete(tmpItem) ){
           jConfirm( JSL_CONFIRM_DELETE, JSL_ECORAE, function(res){
               if (res){
                tmpItem.published = 0;
                listarItems();
               }
           });
        }
        
    }
    
    function avalibleDelete(tmpItem)
    {
        var result = true;
        for (var i=0; i<tmpItem.length; i++) {
            if (tmpItem[i].published == 1){
                result = false;
            }
        }
        return result;
    }
});
