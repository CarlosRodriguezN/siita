jQuery(document).ready(function() {

    //  Funcionalidades del os Botones
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            
            case 'programa.cancel':
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