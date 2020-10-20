jQuery( document ).ready( function(){
    
    //  identifica el div que se comporta como acordion
    jQuery("#acordionUG").accordion({header: "> div > h3", collapsible: true}).sortable({axis: "y",
        handle: "h3",
        stop: function(event, ui) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children("h3").triggerHandler("focusout");
        }
    });
    
    //  Funcionalidades del os Botones
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            
            case 'funcionario.cancel':
                event.preventDefault();
                history.back();
                break;

            default: 
                Joomla.submitform( task );
                break;
        }
          return false;
    };
    

});