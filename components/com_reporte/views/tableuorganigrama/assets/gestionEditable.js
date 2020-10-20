
//  Obtengo URL completa del sitio
var url = window.location.href;
var path = url.split('?')[0];
ent = 0;

jQuery(document).ready(function() {
    jQuery('.edit').live('click', function() {

        ent = parseInt(jQuery(this).attr('id'));

        jQuery(this).editable({type: "text", cols: 25, action: "click"}, function(e) {
            ajaxCall(ent, e.value);
        });
    });
});



function ajaxCall(idEntidad, url) {
    jQuery.ajax({type: 'GET',
        url: path,
        dataType: 'JSON',
        data: {option: 'com_reporte',
            view: 'tableuorganigrama',
            tmpl: 'component',
            format: 'json',
            action: 'updUrl',
            idEntidad: idEntidad,
            url: url
        },
        error: function(jqXHR, status, error) {
            alert('Reportes - Edicion de URL: ' + error + ' ' + jqXHR + ' ' + status);
        }
    }).complete(function(data) {

    });

}