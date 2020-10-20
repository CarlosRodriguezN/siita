jQuery(document).ready(function() {
    
    if (jQuery("#idItem").val() == 1){
        loadData();
    }
    
    function loadData()
    {
        if (owners.length > 0){
            var tmpItem = getItem();
            jQuery("#jform_intIdItem_it").val( tmpItem.idItem );
            jQuery("#jform_intIdAgenda_ag").val( tmpItem.idAgenda );
            jQuery("#jform_intIdEstructura_es").val( tmpItem.idEstructura );
            jQuery("#jform_intIdItem_padre_it").val( tmpItem.idOwner );
            jQuery("#jform_strDescripcion_it").val( tmpItem.descripcionItem );
            jQuery("#jform_strNivel_it").val( tmpItem.nivelItem );
        }
    }
    
});

