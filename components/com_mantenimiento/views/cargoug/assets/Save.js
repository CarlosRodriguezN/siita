jQuery(document).ready(function() {
    /**
     *  Ejecuta la opcion guardar de un formulario
     * @param {type} task
     * @returns {Boolean}
     */
    Joomla.submitbutton = function(task)
    {
         switch (task) {
            case 'cargoug.registrar':
                if ( objetoValido() ) {
                    agregarCargo();
                } else {
                    jAlert( JSL_ALERT_ALL_NEED, JSL_ECORAE);
                }
                break;

            case 'cargoug.cancel':
                window.parent.SqueezeBox.close();
            break;
        }
        return false;
    };

    /**
     * 
     * @param {type} task
     * @returns {undefined}
     */
    function agregarCargo()
    {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaCargoUG = JSON.stringify( list2Object( dataFormulario() ) );

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: { option          : 'com_mantenimiento',
                    view            : 'cargoug',
                    tmpl            : 'component',
                    format          : 'json',
                    action          : 'asignarCargoUG',
                    cargoUG         : dtaCargoUG
                },
            error: function(jqXHR, status, error) {
                jAlert('Mantenimiento - Gesti&oacute;n Cargo: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE );
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            if ( saveData.error  ){
                jAlert( saveData.error, JSL_ECORAE );
            } else if ( saveData.data && saveData.data.intId_ugc ) {
                
                var lstCargosParent = window.parent.objLstCargosUG.lstCargosUG;
                for ( var g=0; g < lstCargosParent.length; g++) {
                    if ( lstCargosParent[g].idUG == jQuery("#idUG").val() ){
                        var indexUG = g;
                    }
                }
                
                if ( jQuery('#jform_intId_ugc').val() == 0 ){
                    saveData.data.idReg = window.parent.objLstCargosUG.lstCargosUG[indexUG].lstCargosUG.length;
                    saveData.data.url = "/index.php?option=com_mantenimiento&amp;view=cargoug&amp;layout=edit";
                    window.parent.objLstCargosUG.lstCargosUG[indexUG].lstCargosUG.push( saveData.data );
                }
                
                window.parent.objLstCargosUG.loadTableCargosUG( window.parent.objLstCargosUG.lstCargosUG[indexUG] );
                
                window.parent.SqueezeBox.close();
            }
            
            
        });
    }

    /**
     *  Arma un array con la informacion genral de un plan POA
     * @returns {Array}
     */
    function dataFormulario()
    {
        var dtaFrm = new Array();

        dtaFrm["intId_ugc"]             = jQuery('#jform_intId_ugc').val();
        dtaFrm["intCodigo_ug"]          = jQuery('#jform_intCodigo_ug').val();
        dtaFrm["inpCodigo_cargo"]       = jQuery('#jform_inpCodigo_cargo :selected').val();
        dtaFrm["intIdGrupo_cargo"]      = jQuery('#jform_intIdGrupo_cargo').val();  
        dtaFrm["strDescripcion_cargo"]  = jQuery('#jform_strDescripcion_cargo').val();
        dtaFrm["published"]             = 1;
        dtaFrm["lstPermisos"]           = getPermisosCargo();
        dtaFrm["upd"]                   = ( dtaFrm["intId_ugc"]  != 0  && dtaFrm["strDescripcion_cargo"] != oCargo.strDescripcion_cargo) ? true : false;

        return dtaFrm;
    }
    
    /**
     *  Retorna la lista de permisos de un grupo sobre los componentes
     * @returns {Array}
     */
    function getPermisosCargo()
    {
        var lstPermisosByCom = new Array();
        
        jQuery('#tbPermisosByCom tr').each(function() {
            var idCom = jQuery(this).attr('id');
            if ( idCom ){
                var permisos = getPermisosCom( idCom );
                lstPermisosByCom[idCom] = permisos;
            }
        });
        
        return lstPermisosByCom;
    }
    
    /**
     *  Retorna los permisos del grupo sobre un componente
     * @param {type} id
     * @returns {Array}
     */
    function getPermisosCom ( id ) 
    {
        var permisos = new Array();
            jQuery('#' + id + ' input[type=checkbox]').each(function(){
                if (this.checked) {
                    var permiso = {permiso: jQuery(this).val()};
                    permisos.push(permiso);
                }
            }); 
        return permisos;
    }
    
    /**
     *  Retorna True en el caso de que el objeto Plan sea valido y False en caso contrario
     * @returns {Boolean}
     */
    function objetoValido ()
    {
        var result = true;
        if (jQuery("#jform_inpCodigo_cargo").val() == 0 ||
            jQuery("#jform_strDescripcion_cargo") .val() == '' ) {
            result = false
        }
        return result;
    }
    
    
    /**
     *  Retorna el numero de registro validos de la data enviada
     * @param {type} data
     * @returns {Boolean}
     */
    function avalibleReg(data)
    {
        var result = 0;
        if (data) {
            var numReg = data.length;
            for (var i = 0; i < numReg; i++) {
                if (data[i].published == 1){
                    result = ++result;
                }
            }
        }
        return result;
    }
    
    
});

/**
 *  Transforma un Array en Objecto de manera Recursiva
 * @param {type} list
 * @returns {list2Object.list}
 */
function list2Object(list)
{
    var obj = {};
    for (key in list) {
        if (typeof(list[key]) == 'object') {
            obj[key] = list2Object(list[key]);
        } else {
            obj[key] = list[key];
        }
    }

    return obj;
}

