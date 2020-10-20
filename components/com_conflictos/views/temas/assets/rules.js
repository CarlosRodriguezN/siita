jQuery(document).ready(function() {
    
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'tema.cancel':
                event.preventDefault();
                history.back();
                break;
            default:
                Joomla.submitform(task);
                break;
        }
    };
    
    
});