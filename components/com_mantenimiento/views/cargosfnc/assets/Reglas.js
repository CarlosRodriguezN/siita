jQuery(document).ready(function() {

    //  identifica el div que se comporta como acordion
    jQuery("#acordionUG").accordion({collapsible : true,
                            heightStyle : "content",
                            autoHeight  : false,
                            clearStyle  : true,
                            header      : 'h3'});

    //  Funcionalidades del os Botones
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            
            case 'cargosfnc.cancel':
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
