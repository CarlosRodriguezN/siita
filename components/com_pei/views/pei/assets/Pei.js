// lista de Archivos 
lstArchivos = new Array();

jQuery(document).ready(function() {

    //  Ejecuta la opcion guardar de un formulario
    Joomla.submitbutton = function(task)
    {
        switch( task ){    
            case 'pei.registroPei':
            case 'pei.guardarSalir':
                if( validarFrmPei() ){
                    jQuery.blockUI({ message: jQuery('#msgProgress') });
                    llamadaAjax( task );
                }
            break;
                
            case 'pei.cancel':
                event.preventDefault();
                history.back();
            break;
                
            case 'pei.organigrama':
                  SqueezeBox.fromElement('index.php?option=com_reporte&view=organigrama&layout=edit&tmpl=component&task=preview', {size: {x: 1024, y: 500}, handler: "iframe"});
            break;
            
            case 'pei.tableOrganigrama':
                  SqueezeBox.fromElement('index.php?option=com_reporte&view=tableuorganigrama&layout=edit&tmpl=component&task=preview', {size: {x: 1024, y: 500}, handler: "iframe"});
            break;

            case 'pei.deletePei':
                jConfirm( "Esta Accion Eliminara Totalmente el Plan, desea continuar", "SIITA - ECORAE", function( result ){
                    if( result ){
                        Joomla.submitform( task );
                    }
                });
            break;

            default: 
                Joomla.submitform( task );
            break;
        }

        return false;
    };
    
    
    
    function validarFrmPei()
    {
        var ban = false;
        
        var descripcionPei  = jQuery( '#jform_strDescripcion_pi' );
        var fchInicioPei    = jQuery( '#jform_dteFechainicio_pi' );
        var fchFinPei       = jQuery( '#jform_dteFechafin_pi' );
        
        if( descripcionPei.val() !== "" && fchInicioPei.val() !== "" && fchFinPei.val() !== "" ){
            ban = true;
        }else{
            validarElemento( descripcionPei );
            validarElemento( fchInicioPei );
            validarElemento( fchFinPei );
        }

        return ban;
    }
    
    
    function validarElemento( obj )
    {
        var ban = 1;
        
        if( obj.val() === "" || obj.val() === "0" ){
            ban = 0;
            obj.attr( 'class', 'required invalid' );
            
            var lbl = obj.selector + '-lbl';
            jQuery( lbl ).attr( 'class', 'hasTip required invalid' );
            jQuery( lbl ).attr( 'aria-invalid', 'true' );
        }
        
        return ban;
    }
    
    
    
    /*
     * Llamada Ajax para guardar la data de un Plan Estrategico Institucional
     * 
     * @returns {undefined}
     */
    function llamadaAjax( task ) {
        var url = window.location.href;
        var path = url.split('?')[0];
        var dtaFormulario = JSON.stringify( list2Object( dataFormulario() ) );
        
        //  Transforma en objeto Json a la informacion de Objetivos
        var dtaLstObjetivos = JSON.stringify( list2Object( objLstObjetivo.lstObjetivos ) );
        
        //  Transforma en JSon a la informacion de Contextos
        var dtaContextos = JSON.stringify( list2Object( objContexto ) );

        var banCambioFechas = existeCambioFechas();

        jQuery.ajax({   type: 'POST',
                        url: path,
                        dataType: 'JSON',
                        data: { method          : "POST",
                                option          : 'com_pei',
                                view            : 'pei',
                                tmpl            : 'component',
                                format          : 'json',
                                action          : 'guardarPei',
                                banFecha        : banCambioFechas,
                                dtaFrm          : dtaFormulario,
                                lstObjPei       : dtaLstObjetivos,
                                dtaLstContextos : dtaContextos
                        },
                        error: function(jqXHR, status, error) {
                            jQuery.unblockUI();
                            jAlert('Plan estratégico Institucional - Gestion PEI: ' + error + ' ' + jqXHR + ' ' + status, 'SIITA -  ECORAE');
                        }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            saveDocumentos( saveData, task );
        });
    }



    function existeCambioFechas( oFI, oFF )
    {
        var ban = 0;

        var fI = Date.parse( jQuery( '#jform_dteFechainicio_pi' ).val() );
        var fF = Date.parse( jQuery( '#jform_dteFechafin_pi' ).val() );

        var oldFI = ( jQuery( '#oldFchInicio' ).val() === '' )  ? fI
                                                                : Date.parse( jQuery( '#oldFchInicio' ).val() );
                                                                
        var oldFF = ( jQuery( '#oldFchFin' ).val() === '' ) ? fF
                                                            : Date.parse( jQuery( '#oldFchFin' ).val() );

        //  Si existe cambios en las fechas
        if( oldFI.getTime() !== fI.getTime() || oldFF.getTime() !== fF.getTime() ){
            ban = 1;
        }

        return ban;
    }


    //  
    //  Transforma un Array en Objecto de manera Recursiva
    //
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

    /**
     *  Arma la data general de un PEI
     * 
     * @returns {Array}
     */
    function dataFormulario()
    {
        dtaFrm = new Array();

        dtaFrm["intId_pi"]          = jQuery('#jform_intId_pi').val();
        dtaFrm["intIdentidad_ent"]  = jQuery('#jform_intIdentidad_ent').val();
        dtaFrm["intId_tpoPlan"]     = jQuery('#jform_intId_tpoPlan').val();
        dtaFrm["intCodigo_ins"]     = jQuery('#jform_intCodigo_ins').val();
        dtaFrm["strDescripcion_pi"] = jQuery('#jform_strDescripcion_pi').val();
        dtaFrm["dteFechainicio_pi"] = jQuery('#jform_dteFechainicio_pi').val();
        dtaFrm["dteFechafin_pi"]    = jQuery('#jform_dteFechafin_pi').val();
        dtaFrm["strAlias_pi"]       = jQuery('#jform_strAlias_pi').val();
        dtaFrm["blnVigente_pi"]     = jQuery('#jform_blnVigente_pi').val();
        dtaFrm["banFecha"]          = jQuery('#jform_banFecha').val();
        
        dtaFrm["published"] = 1;

        return dtaFrm;
    }

    /**
     *  Controla que el tipo de objetivo sea valido 
     *  y habilita o desabilita el boton para agregar
     */
    jQuery("#jform_published").change(function() {
        if (jQuery("#jform_published").val() == 0) {
            recorrerCombo(jQuery('#jform_blnVigente_pi option'), 0);
            jQuery('#jform_blnVigente_pi').attr('disabled', 'disabled');
        } else {
            recorrerCombo(jQuery('#jform_blnVigente_pi option'), 1);
            jQuery('#jform_blnVigente_pi').removeAttr('disabled', '');
        }
    });

    /**
     *  Recorro el combo de provincias a una determinada posicion
     * 
     * @param {type} combo
     * @param {type} posicion
     * @returns {undefined}
     */
    function recorrerCombo(combo, posicion)
    {
        jQuery(combo).each(function() {
            if (jQuery(this).val() == posicion) {
                jQuery(this).attr('selected', 'selected');
            }
        });
    }


    function getPoaByIdRegistro(idRegistro) {
        var poa = false;
        for (var i = 0; i < oPEI.lstPoasPei.length; i++) {
            if (oPEI.lstPoasPei[i].registroPoa == idRegistro)
                poa = oPEI.lstPoasPei[i];
        }
        return poa;
    }

});

/**
 * 
 * @param {type} data
 * @param {type} task
 * @returns {undefined}
 */
function saveDocumentos(data, task) {
    // Actualiza la informacion de los archivo( actualiza los id para armarm el path )
    updateDocsData(data);

    if( existFileToUpload() ){
        for ( var j = 0; j < lstArchivos.length; j++ ){
            var flag2 = ( j == ( lstArchivos.length - 1 ) ) ? true 
                                                            : false;

            if( lstArchivos[j] && lstArchivos[j].flag ){ // 1 archivo nuevo 0 archivo recuperado
                var options = {
                    option      : "com_pei",
                    controller  : "pei",
                    task        : "pei.saveFiles",
                    tmpl        : "component",
                    typeFileUpl : "documents",
                    fileObjName : "documents",
                    idPoa       : data.idPei,
                    tipo        : 1,
                    flag2       : flag2,
                    redirecTo   : task, // variable que indica a donde redicreccionar luego de complatar la carga.
                    idObjetivo  : lstArchivos[j].idObjetivo,
                    idActividad : lstArchivos[j].idActividad
                };

                jQuery('#uploadFather').data("uploadifive").addQueueItem( lstArchivos[j].file );
                jQuery('#uploadFather').data('uploadifive').settings.formData = options;
                jQuery('#uploadFather').uploadifive('upload');
            }
        }
    } else {
        if (task == "pei.registroPei") {
            location.href = 'http://' + window.location.host + '/index.php?option=com_pei&view=pei&layout=edit&intId_pi=' + data.idPei;
        } else {
            location.href = 'http://' + window.location.host + '/index.php?option=com_pei&view=peis';
        }
    }
}



/**
 * 
 * @param {type} data
 * @returns {Array}
 */
function updateDocsData(data) {
    var lstArchivos = new Array();
    if (data.lstObjetivos) {
        var lstObj = data.lstObjetivos;
        for (var j in lstObj) {
            var objetivo = lstObj[j];
            if (objetivo.lstActividades != null) {
                for (var k in objetivo.lstActividades) {
                    var actividad = objetivo.lstActividades[k];
                    if (actividad.lstArchivosActividad != null) {
                        for (var l in actividad.lstArchivosActividad) {
                            var archivo = actividad.lstArchivosActividad[l];
                            archivo.idObjetivo = objetivo.idObjetivo;
                            archivo.idActividad = actividad.idActividad;
                            setDataFile(archivo);
                        }
                    }
                }
            }
        }
    }
    return(lstArchivos);
}



function setDataFile(archivo) {
    var find = false;
    var i = 0;
    while (!find && i < lstArchivos.length) {
        var item = lstArchivos[i];
        if (item.regObjetivo == archivo.regObjetivo && item.registroAct == archivo.registroAct) {
            if (item.file) {
                item.file.idObjetivo = archivo.idObjetivo;
                item.file.idActividad = archivo.idActividad;
                find = true;
            }
        }
        i++;
    }
}


/**
 *  Busca si existen archivos para subir.
 *  
 * @returns {Boolean}
 */
function existFileToUpload() {
    var flag = false;
    for (var j = 0; j < lstArchivos.length; j++) {
        if (lstArchivos[j] && lstArchivos[j].flag) {
            flag = true;
        }
    }
    return flag;
}