
jQuery(document).ready(function() {

    //  Pestaña Ubicacion Geografica
    jQuery('#tabsLstPoa').tabs();

});

function vigencia(task, id, published) {
    if (published == 0 && task==0) {
        jAlert("Un POA no puede ser vigente si no tiene un estatus activo", "SIITA - ECORAE")
    } else {
        if (task == 0) {
            task = 1;
            updVigenciaAjx(task, id);
        } else {
            task = 0;
            updVigenciaAjx(task, id);
        }
    }
}

function updVigenciaAjx(op, id) {
    var url = window.location.href;
    var path = url.split('?')[0];
    jQuery.ajax({type: 'POST',
        url: path,
        dataType: 'JSON',
        data: {method: "POST",
            option: 'com_poa',
            view: 'poa',
            tmpl: 'component',
            format: 'json',
            action: 'updVigenciaPoa',
            op: op,
            id: id
        },
        error: function(jqXHR, status, error) {
            alert('Plan estratégico Institucional - Gestion POA: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function() {
        if (op == 0)
            jQuery('#ingVigencia-' + id).html('<a href="javascript:vigencia('+ op +', '+ id+')" > <img src = "images/ico_facebook.png" title="POA no vigente"> </a>');
        else
            jQuery('#ingVigencia-' + id).html('<a href="javascript:vigencia('+ op +', '+ id+')" > <img src = "images/ico_twitter.png" title="POA vigente"> </a>');
        return true;
    });

}