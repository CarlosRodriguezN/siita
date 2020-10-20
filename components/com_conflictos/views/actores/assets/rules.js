jQuery(document).ready(function() {
    
    Joomla.submitbutton = function(task) {
        switch (task) {
            case 'actor.add':
                event.preventDefault();
                break;
            case 'actor.cancel':
                event.preventDefault();
                history.back();
                break;
            default:
                Joomla.submitform(task);
                break;
        }
    };
    
    
});