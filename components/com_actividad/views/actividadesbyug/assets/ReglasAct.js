jQuery(document).ready(function() {
    
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            case 'actividadesbyug.salir':
                window.parent.SqueezeBox.close();
                break;
            default: 
                Joomla.submitform(task);
                break;
        }
        
        return false;
    };
    
    
    /**
     *  Especifica el tamaño de los select
     */
//    jQuery("#jform_intIdTipoGestion_tpg").css({width: "100px"});

});

