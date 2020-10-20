jQuery(document).ready(function() {
    
    if ( objLstCargosUG.lstCargosUG.length > 0){
        for ( var i=0; i< objLstCargosUG.lstCargosUG.length; i++){
            var objCargos = new GestionCargosUG();
            objCargos.loadTableCargosUG( objLstCargosUG.lstCargosUG[i] );
        }
    }
    
    jQuery(".delCargo").live("click", function(){
        var fila = jQuery(this).attr('id');
        var regCargo = parseInt(fila.toString().split('-')[1]);
        var table = jQuery(this).parent().parent().parent().parent().attr('id');
        var regUG = parseInt(table.toString().split('-')[1]);
        jConfirm( JSL_CONFIRM_DELETE, JSL_ECORAE, function(op){
            if (op){
                eliminarCargoUG( regUG, regCargo );
            }
        });
    });
    
    function eliminarCargoUG( regUG, regCargo )
    {
        var oCargo = objLstCargosUG.lstCargosUG[regUG].lstCargosUG[regCargo];
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaCargoUG = JSON.stringify( oCargo );

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: { option          : 'com_mantenimiento',
                    view            : 'cargoug',
                    tmpl            : 'component',
                    format          : 'json',
                    action          : 'eliminarCargoUG',
                    cargoUG         : dtaCargoUG
                },
            error: function(jqXHR, status, error) {
                jAlert('Mantenimiento - Gesti&oacute;n Cargo: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            if ( saveData  ){
                eliminarCargo( regUG, regCargo );
            } else {
                jAlert( JSL_ERROR_DEL, JSL_ECORAE );
            }
        });
    }
    
    function eliminarCargo( regUG, regCargo )
    {
        var lista = objLstCargosUG.lstCargosUG[regUG].lstCargosUG;
        var newLista = new Array();
        for ( var j=0; j<lista.length; j++ ){
            if ( lista[j].idReg != regCargo ){
                lista[j].idReg = newLista.length;
                newLista.push(lista[j]);
            }
        }
        
        objLstCargosUG.lstCargosUG[regUG].lstCargosUG = newLista;
        objLstCargosUG.loadTableCargosUG( objLstCargosUG.lstCargosUG[regUG] );
        jQuery("#aUG-" + regUG).html( "[" + objLstCargosUG.lstCargosUG[regUG].lstCargosUG.length + "]  " + objLstCargosUG.lstCargosUG[regUG].nombreUG);
        return true;
    }
    
});
