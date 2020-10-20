jQuery(document).ready(function() {
    
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'fuente.add':
                event.preventDefault();
                break;
            case 'fuente.cancel':
                event.preventDefault();
                history.back();
                break;
            default:
                Joomla.submitform(task);
                break;
        }
    };
    
    
});