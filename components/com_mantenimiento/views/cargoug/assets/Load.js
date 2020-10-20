oCargo = null;
jQuery.alerts.okButton = JSL_OK;
jQuery.alerts.cancelButton = JSL_CANCEL;
jQuery(document).ready(function() {
    
    infoUG = JSON.parse(atob(jQuery("#infoUG").val()));
    jQuery("#jform_strNombreUG").val(infoUG.strNombre_ug);
    jQuery("#jform_intCodigo_ug").val(parseInt(jQuery("#idUG").val()));
    
    if ( jQuery("#idGrupo").val() != 0 ) {
        loadDataUpd();
    }
    
    cargarComponentes();
    
    function loadDataUpd()
    {
        var ind = jQuery("#indexGrp").val();
        var idUG = jQuery("#idUG").val();
        var lstCargos = window.parent.objLstCargosUG.lstCargosUG;
        for ( var j=0; j<lstCargos.length; j++){
            if (lstCargos[j].idUG == idUG){
                oCargo = lstCargos[j].lstCargosUG[ind];
            }
        }
        
        if ( oCargo ){
            jQuery("#jform_intId_ugc").val(oCargo.intId_ugc);
            jQuery("#jform_intIdGrupo_cargo").val(oCargo.intIdGrupo_cargo);
            jQuery("#jform_inpCodigo_cargo").val(oCargo.inpCodigo_cargo);
            jQuery("#jform_strDescripcion_cargo").val(oCargo.strDescripcion_cargo);

        }
    }
    
    function cargarComponentes()
    {
        if( lstComponentes.length > 0 ){
            for ( var i=0; i<lstComponentes.length; i++){
                agregarFila(lstComponentes[i]);
            }
        }
    }
    
    function agregarFila( obj )
    {
        var fila = mekeFila( obj, 0 );
        jQuery('#tbPermisosByCom> tbody:last').append(fila);
    }
    
    function mekeFila ( obj, op )
    {
        var flagPrm = ( !jQuery.isArray(obj.permisos) && !jQuery.isEmptyObject(obj.permisos) ) ? true : false;
        var html = '';
        html += ( op == 0 ) ? '<tr id="com-' + obj.id + '" class = "row' + obj.idReg % 2 + '" >' : '';
        html += '<td>';
        html += (obj.dtageneral.description != '') ? obj.dtageneral.description : obj.nombre;
        html += '</td>';
        html += '<td align="center">';
        html += '   <input id="acc-' + obj.id + '" type="checkbox" value="' + obj.id + '-1" style="margin: 0px;" ';
        html += ( flagPrm && (obj.permisos.admin == 1) ) ? 'checked="true" >' : '>';
        html += '</td>';
        html += '<td align="center">';
        html += '   <input id="add-' + obj.id + '" type="checkbox" value="' + obj.id + '-2" style="margin: 0px;" ';
        html += ( flagPrm && (obj.permisos.add == 1) ) ? 'checked="true" >' : '>';
        html += '</td>';
        html += '<td align="center">';
        html += '   <input id="upd-' + obj.id + '" type="checkbox" value="' + obj.id + '-3" style="margin: 0px;" ';
        html += ( flagPrm && (obj.permisos.upd == 1) ) ? 'checked="true" >' : '>';
        html += '</td>';
        html += '<td align="center">';
        html += '   <input id="del-' + obj.id + '" type="checkbox" value="' + obj.id + '-4" style="margin: 0px;" ';
        html += ( flagPrm && (obj.permisos.del == 1) ) ? 'checked="true" >' : '>';
        html += '</td>';
        html += ( op == 0 ) ? ' </tr>' : '';
        return html;
    }
});
    
    

    
