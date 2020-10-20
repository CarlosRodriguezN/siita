jQuery( document ).ready( function() {
    

});

function vigenciaPei( id, task )
{
    var tpo = 1;
    var rstTask = ( task === 1 )? 0 
                                : 1;

    var rst = getPlnVigentes( id );

    if( rst === 1 ){
        jConfirm( "Existe otro Plan Activo, desea continuar", "SIITA - ECORAE", function( result ){
            if( result ){
                updVigenciaAjx( id, rstTask, tpo );
            }else{
                return false;
            }
        });
    }else{
        updVigenciaAjx( id, rstTask, tpo );
    }
}

function getPlnVigentes( idPln )
{
    var lstPlanes = eval("(" + jQuery( '#lstPlanes' ).val() + ")");
    var np = lstPlanes.length;
    var ban = 0;

    if( np > 1 ){
        for( var x = 0; x < np; x++ ){
            if( parseInt( lstPlanes[x].idPlan ) !== idPln && parseInt( lstPlanes[x].vigencia ) === 1 ){
                ban = 1;
            }
        }
    }

    return ban;
}


function vigenciaPppp( id, task ) {
    switch (task) {
        case 0:
            task = 1;
            break;
        case 1:
            task = 0;
            break;
    }
    var tpo = 3;
    updVigenciaAjx( id, task, tpo );
}

function vigenciaPapp( id, task ) {
    switch (task) {
        case 0:
            task = 1;
            break;
        case 1:
            task = 0;
            break;
    }
    var tpo = 4;
    updVigenciaAjx( id, task, tpo );
}

function updVigenciaAjx( id, op, tpo ) {
    
    jQuery.blockUI({ message: jQuery('#msgProgress') });

    var url = window.location.href;
    var path = url.split('?')[0];
    var idPlan = jQuery( '#jform_intId_pi' ).val();
    
    jQuery.ajax({type: 'POST',
        url: path,
        dataType: 'JSON',
        data: { method  : 'POST',
                option  : 'com_pei',
                view    : 'pei',
                tmpl    : 'component',
                format  : 'json',
                action  : 'updVigenciaPlan',
                idPadre : idPlan,
                op      : op,
                id      : id,
                tpo     : tpo
        },
        error: function(jqXHR, status, error) {
            alert( 'Plan estratégico Institucional - Gestion PEI: ' + error + ' ' + jqXHR + ' ' + status );
        }
    }).complete( function( data ) {
        switch( tpo ){
            //  Plan de tipo PEI
            case 1:
                location.href = 'http://' + window.location.host + '/index.php?option=com_pei';
            break;
            
            //  Plan de tipo PPPP
            case 3:
            case 4:
                location.href = 'http://' + window.location.host + '/index.php?option=com_pei&view=pei&layout=edit&intId_pi=' + idPlan;
            break;
        }
    });
}

function reloadPathPlanVigente( paht ) {
    var plnVigente = '';
    for (var i=0; i<paht.length; i++) {
        switch ( parseInt(paht[i].tipoPln) ) {
            case 1:
                plnVigente += makePath(paht[i], 'pathPei');
                break;
            case 3:
                plnVigente += makePath(paht[i], 'pathPppp');
                break;
            case 4:
                plnVigente += makePath(paht[i], 'pathPapp');
                break;
        }
    }
    
    plnVigente = plnVigente.substring(0, plnVigente.length-1);
    jQuery("#planes").html(plnVigente);
}


function makePath( plan, tipo ) {
    var path = '';
    if ( plan.idPln != 0 ) {
        if ( plan.color == 1 ){
            path += '<span id="' + tipo + '" style="color: #0B55C4; font-weight: bold;" > ' + plan.descripcionPln;
            path += (plan.tipoPln != 4) ? ' | </span>' : '</span>' ;
        } else {
            path += '<span id="' + tipo + '" style="color: red; font-weight: bold;" > ' + plan.descripcionPln;
            path += (plan.tipoPln != 4) ? ' | </span>' : '</span>' ;
        }
    } else {
        path += '<span id="' + tipo + '" style="color: red; font-weight: bold;" > ' + plan.descripcionPln;
        path += (plan.tipoPln != 4) ? ' | </span>' : '</span>' ;
    }
    
    return path;
}