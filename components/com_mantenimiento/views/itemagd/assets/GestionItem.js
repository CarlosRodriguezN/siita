jQuery(document).ready(function() {

    /**
     *  Controla si es un edicion o un nuevo registro
     */
    if (jQuery("#idItem").val() == 1){
        loadData();
    }
    
    /**
     *  Carga la data en caso de ser una edicion de un registro 
     * @returns {undefined}
     */
    function loadData()
    {
        if (owners.length > 0){
            var tmpItem = getItem();
            jQuery("#jform_strDescripcion_it").val( tmpItem.descripcionItem );
            jQuery("#jform_strNivel_it").val( tmpItem.nivelItem );
        }
    }

    /**
     *  Ejecuta la opcion guardar y cancelar de la POPUP
     * @param {type} task
     * @returns {Boolean}
     */
    Joomla.submitbutton = function(task)
    {
         switch (task) {
            case 'itenagd.registrar':
                if ( objetoValido() ) {
                    var objItem = new window.parent.ItemAgd();
                    var tmpLstItems = null;
                    
                    switch (parseInt(jQuery("#idItem").val())){
                        case 0:
                            if ( owners.length == 0 ){
                                tmpLstItems = window.parent.objLstItemsAgd.lstItemsAgd;
                                objItem.setDtaItemAgd(addDataItamBase());
                                tmpLstItems.push(objItem);
                                window.parent.objLstItemsAgd.lstItemsAgd = tmpLstItems;
                                jQuery( "#treeItems", window.parent.document ).empty();
                                window.parent.listarItems();
                                window.parent.SqueezeBox.close();
                            } else {
                                var tmpItem = getItem();
                                tmpItem = addItemHijo(tmpItem);
                                objItem.setDtaItemAgd( tmpItem );
                                tmpLstItems = addItem(objItem);
                                window.parent.objLstItemsAgd.lstItemsAgd = tmpLstItems;
                                jQuery( "#treeItems", window.parent.document ).empty();
                                window.parent.listarItems();
                                window.parent.SqueezeBox.close();
                            }
                            break;
                        case 1:
                            var tmpItem = getItem();
                            objItem.setDtaItemAgd(updDataItem(tmpItem));
                            tmpLstItems = addItem(objItem);
                            window.parent.objLstItemsAgd.lstItemsAgd = tmpLstItems;
                            jQuery( "#treeItems", window.parent.document ).empty();
                            window.parent.listarItems();
                            window.parent.SqueezeBox.close();
                            break;
                        
                    }
                    
                } else {
                    alert (JSL_ALERT_ALL_NEED, JSL_ECORAE);
                }
                break;

            case 'itenagd.cancel':
                window.parent.SqueezeBox.close();
                break;
        }
        return false;
    };

    function updDataItem( item )
    {
        item.descripcionItem    = jQuery('#jform_strDescripcion_it').val();
        item.nivelItem          = jQuery('#jform_strNivel_it').val();
        return item;
    }

    function addItem( objItem )
    {
        var tmpLstItems = window.parent.objLstItemsAgd.lstItemsAgd;
        var listItems = new Array();
        for (var i=0; i<owners.length; i++){
            listItems.push(tmpLstItems);
            tmpLstItems = tmpLstItems[owners[i]].itemsHijos;
        }
        
        var reverseOwners = owners.reverse();
        listItems.reverse();
        
        if (listItems.length > 1){
            for (var j=1; j<listItems.length; j++){
                listItems[j][reverseOwners[j]].itemsHijos = listItems[j-1];
                tmpLstItems = listItems[j];
            }
        } else {
            tmpLstItems = listItems[0]
        }
        
        return tmpLstItems;
    }

    function addDataItamBase()
    {
        var dtaFrm = new Array();

        dtaFrm["registroItem"]      = window.parent.objLstItemsAgd.lstItemsAgd.length;
        dtaFrm["registroOwner"]     = 0;
        dtaFrm["registroEtr"]       = jQuery('#regEstructura').val();
        dtaFrm["idItem"]            = 0;
        dtaFrm["idAgenda"]          = jQuery( '#jform_intIdAgenda_ag', window.parent.document ).val();
        dtaFrm["idEstructura"]      = getIdEstructura(jQuery('#regEstructura').val());
        dtaFrm["idOwner"]           = 0;
        dtaFrm["descripcionItem"]   = jQuery('#jform_strDescripcion_it').val();
        dtaFrm["nivelItem"]         = jQuery('#jform_strNivel_it').val();
        dtaFrm["published"]         = 1;    
        dtaFrm["itemsHijos"]        = new Array();

        return dtaFrm;
    }

    function addDataItamHijo( owner )
    {
        var dtaFrm = new Array();
        var estructuraHijo = getEstructuraHijo(jQuery('#regEstructura').val());

        dtaFrm["registroItem"]      = owner.itemsHijos.length;
        dtaFrm["registroOwner"]     = owner.registroItem;
        dtaFrm["registroEtr"]       = estructuraHijo.registroEtr;
        dtaFrm["idItem"]            = 0;
        dtaFrm["idAgenda"]          = jQuery( '#jform_intIdAgenda_ag', window.parent.document ).val();
        dtaFrm["idEstructura"]      = estructuraHijo.idEstructura;
        dtaFrm["idOwner"]           = owner.idItem;
        dtaFrm["descripcionItem"]   = jQuery('#jform_strDescripcion_it').val();
        dtaFrm["nivelItem"]         = jQuery('#jform_strNivel_it').val();
        dtaFrm["published"]         = 1;    
        dtaFrm["itemsHijos"]        = new Array();

        return dtaFrm;
    }

    function getEstructuraHijo( regErtOwner )
    {
        var estructura = null;
        for (var i=0; i<window.parent.avalibleEstr.length; i++){
            if (window.parent.avalibleEstr[i].registroEtr == regErtOwner){
                estructura = window.parent.avalibleEstr[i+1];
            }
        }
        return estructura;
    }

    function addItemHijo( itemOwner )
    {
        var objItem = new window.parent.ItemAgd();
        objItem.setDtaItemAgd(addDataItamHijo(itemOwner));
        itemOwner.itemsHijos.push(objItem);
        return itemOwner;
    }

    function objetoValido ()
    {
        var result = true;
        if ( jQuery("#jform_strDescripcion_it").val() == '' ||
             jQuery("#jform_strNivel_it").val() == '') {
            result = false;
        }
        
        return result;
    }
    
    
    function getItem()
    {
        var tmpLstItems = window.parent.objLstItemsAgd.lstItemsAgd;
        var item = null;
        for (var i=0; i<owners.length; i++){
            var index = owners[i];
            item = tmpLstItems[index];
            tmpLstItems = item.itemsHijos;
        }
        return item;
    }
    
    function getIdEstructura(idReg)
    {
        var idEtr = 0;
        for (var i=0; i<window.parent.avalibleEstr.length; i++){
            if (window.parent.avalibleEstr[i].registroEtr == idReg){
                idEtr = window.parent.avalibleEstr[i].idEstructura;
            }
        }
        return idEtr;
    }
});
