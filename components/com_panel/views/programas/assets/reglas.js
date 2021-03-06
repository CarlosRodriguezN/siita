jQuery(document).ready(function() {

    jQuery( '.info' ).live( 'click', function(){
        var idPrograma = jQuery( this ).attr( 'id' );
        var nombre = jQuery( this ).attr( 'name' );
        location.href = 'http://' + window.location.host + '/index.php?option=com_programa&view=programa&layout=edit&intCodigo_prg=' + idPrograma + '&nombre=' + nombre;
    })

    jQuery("#accordion").accordion({ header: "> div > h3" }).sortable({ axis: "y", handle: "h3",
        stop: function(event, ui) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children("h3").triggerHandler("focusout");
        }
    });
    
})