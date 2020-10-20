jQuery(document).ready(function () {
    
    //  varibles glovales para la gestion de items
    updEstructura = 0;
    regLastEtr = 0;
    avalibleEstr = new Array();
    lastEstructura = null;
//    lista = ''; 
    
    //  Ejecuta la opcion guardar de un formulario
    Joomla.submitbutton = function(task)
    {
        switch( task ){
            case 'agenda.save': 
                if ( confirmData() ) {
                    llamadaAjax(task); 
                } else {
                    var msg = '<p style="text-align: center;">';
                    msg += JSL_ALERT_DTA_GENERAL_NEED + '<br>';
                    msg += JSL_ALERT_ALL_NEED;
                    msg += '</p>';
                    jAlert(msg, JSL_ECORAE);
                }
            break;
            
            case 'agenda.saveExit': 
                if ( confirmData() ) {
                    llamadaAjax(task); 
                } else {
                    jAlert( JSL_ALERT_DTA_GENERAL + ', ' + JSL_ALERT_ALL_NEED, JSL_ECORAE );
                }
            break;
            
            case 'agenda.cancel':
                event.preventDefault();
                history.back();
            break;
                
            case 'agenda.delete':
                jConfirm( JSL_CONFIRM_DELETE, JSL_ECORAE, function(res) {
                    if (res) {
                        eliminarAgenda();
                    }
                });
            break;
                
            default: 
                Joomla.submitform(task);
            break;
        }
        return false;
    };
    
    /*
     * Llamada Ajax para guardar la data de una Agenda
     * @returns {undefined}
     */
    function llamadaAjax(task) {
        var url = window.location.href;
        var path = url.split('?')[0];
        
        var dtaFormulario = JSON.stringify( list2Object( dataFormulario() ) );
        var dtaLstDetalles = JSON.stringify(list2Object(objLstDetallesAgd.lstDetallesAgd));
        var dtaLstEstructura = JSON.stringify( list2Object( objLstEstructuraAgd.lstEstructurasAgd ) );
        var dtaLstItems = JSON.stringify( list2Object( objLstItemsAgd.lstItemsAgd ) );
        
        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method       : "POST",
                option          : 'com_mantenimiento',
                view            : 'agenda',
                tmpl            : 'component',
                format          : 'json',
                action          : 'guardarAgenda',
                dtaFrm          : dtaFormulario,
                lstDetalles     : dtaLstDetalles,
                lstEstructura   : dtaLstEstructura,
                lstItems        : dtaLstItems
            },
            error: function(jqXHR, status, error) {
                jAlert('Agendas - Gestion Agenda: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE);
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            var newReg = (jQuery("#jform_intIdAgenda_ag").val() == 0 ) ? true : false;
            switch (task){
                case 'agenda.save': 
                    if ( newReg ) {
                        location.href = 'http://' + window.location.host + '/index.php?option=com_mantenimiento&view=agenda&layout=edit&intIdAgenda_ag=' + saveData;
                    } else {
                        location.reload();
                    }
                break;
                case 'agenda.saveExit':
                    location.href = 'http://' + window.location.host + '/index.php?option=com_mantenimiento&view=agendas';
                break;
            }

        });
    }
    
    /**
     *  Verifica que la informacion general obligatoria haya sido ingresada
     * @returns {Boolean}
     */
    function confirmData()
    {
        var result = true;
        if ( jQuery('#jform_strDescripcion_ag').val() == '' ||
                jQuery('#jform_dteFechaInicio_ag').val() == '' ||
                jQuery('#jform_dteFechaFin_ag').val() == '' ){
            result = false;
        }
        return result;
    }
    
    function dataFormulario()
    {
        var dtaForm = new Array();
        dtaForm["idAgenda"] = jQuery("#jform_intIdAgenda_ag").val();
        dtaForm["descripcionAgd"] = jQuery("#jform_strDescripcion_ag").val();
        dtaForm["fechaInicio"] = jQuery("#jform_dteFechaInicio_ag").val();
        dtaForm["fechaFin"] = jQuery("#jform_dteFechaFin_ag").val();
        dtaForm["published"] = jQuery("#jform_published :selected").val();
        return dtaForm;
    }
    
    function eliminarAgenda(){
        var url = window.location.href;
        var path = url.split('?')[0];
        
        jQuery.blockUI({ message: jQuery('#msgProgress') });

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {method       : "POST",
                option          : 'com_mantenimiento',
                view            : 'agenda',
                tmpl            : 'component',
                format          : 'json',
                action          : 'eliminarAgenda',
                id              : jQuery("#jform_intIdAgenda_ag").val()
            },
            error: function(jqXHR, status, error) {
                jAlert('Agendas - Gestion Agenda: ' + error + ' ' + jqXHR + ' ' + status, JSL_ECORAE);
                jQuery.unblockUI();
            }
        }).complete(function(data) {
            var saveData = eval("(" + data.responseText + ")");
            if (saveData) {
                location.href = 'http://' + window.location.host + '/index.php?option=com_mantenimiento&view=agendas';
            } else {
                jAlert( JSL_CONFIRM_NO_AVALIBLE_DEL, JSL_ECORAE);
            }
        });
    }
    
});

var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );

/**
 *  Elimina una fila de la tabla de bjetivos de un pei
 * @param {type} idFila 
 * @param {type} tabla
 * @returns {undefined}
 */
function delFila(idFila, tabla)
{
    //  Elimino fila de la tabla lista de GAP
    jQuery(tabla + ' tr').each(function() {
        if (jQuery(this).attr('id') == idFila) {
            jQuery(this).remove();
        }
    });
}

/**
 *  Retorna True en el caso que exita almenos un registro diaponible, si no False
 * @param {type} lstObjetos     Lista de objetos a ser revizada
 * @returns {Boolean}
 */
function existRegAvalible( lstObjetos )
{
    var result = false;
    var numReg = lstObjetos.length;
    for (var i = 0; i < numReg; i++) {
        if (lstObjetos[i].published == 1 ) {
            result = true;
        }
    }
    return result;
}

/**
 *  Recorro un combo una determinada posicion
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

/**
 *  Transforma un Array en Objecto de manera Recursiva
 * @param {type} list
 * @returns {unresolved}
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

//////////////////////        Gestion Estructura     //////////////////////////
/**
 *  Asigna la estructura vigente de la agenda si es que se a realizado modificaciones
 * @returns {undefined}
 */
function estructuraVigente()
{
    var numDtll = objLstEstructuraAgd.lstEstructurasAgd.length;
    var estructura = objLstEstructuraAgd.lstEstructurasAgd;
    avalibleEstr = new Array();
    if ( numDtll > 0 ){
        estructura.sort( function (a, b) {
            return parseInt(a.nivelEtr) - parseInt(b.nivelEtr);
        });

        for (var i=0; i<estructura.length; i++){
            if (estructura[i].published == 1){
                avalibleEstr.push(estructura[i]);
            }
        }
        lastEstructura = ( avalibleEstr.length > 0 ) ? avalibleEstr[avalibleEstr.length-1] : null;
        regLastEtr = (lastEstructura) ? parseInt(avalibleEstr[avalibleEstr.length-1].registroEtr) : 0 ;
    } 
}

//////////////////////        Gestion Items       /////////////////////////////

/**
 *  Caega la lista de los items de una agenda
 * @returns {undefined}
 */
function listarItems()
{
    if (jQuery('#srItem').is (':visible')) {
        jQuery("#srItem").css("display", "none");
    }
    var lista = '';
    jQuery('#treeItems').empty();
    if ( objLstItemsAgd.lstItemsAgd.length > 0 ){
        lista += '<ul id="listaItems">';
        for (var i=0; i<objLstItemsAgd.lstItemsAgd.length; i++){
            lista += makeLista(objLstItemsAgd.lstItemsAgd[i], null);
        }
        lista += '</ul>';
        jQuery('#treeItems').html(lista);
        addMakeTree();
    } else {
        jQuery("#srItem").css("display", "block");
    }
    return false;
}

/**
 *  Arma la estructura la para lista de los items
 * @param {type} item           Data del item     
 * @param {type} owner          id del item padre
 * @returns {String}
 */
function makeLista( item, owner )
{
    var htmlResult = '';
    if (item.published == 1) {
        var nivel = (owner != null) ? owner + '-' + item.registroItem : item.registroItem; 
        htmlResult += '<li id="item-' + nivel + '"> ';
        htmlResult += '  <div>';
        htmlResult += '     <span>' + item.nivelItem + '.- ' + item.descripcionItem + '  </span>';
        htmlResult += '     <span id="itSpan-' + nivel + '" value="' + item.registroEtr + '" class="fltrt" style="position: absolute">';
        
        if( roles["core.create"] === true || roles["core.edit"] === true ){
            if ( typeof item.registroEtr != 'undefined' && parseInt(item.registroEtr) != regLastEtr ) {
                htmlResult += '        <span class="newItem" >';
                htmlResult += '            <img src="/media/system/images/mantenimiento/new.png" title="Nuevo">';
                htmlResult += '        </span>';
            }

            htmlResult += '        <span class="updItem" >';
            htmlResult += '            <img src="/media/system/images/mantenimiento/edit.png" title="Editar">';
            htmlResult += '        </span>';

            if ( avalibleDelete( item ) ){
                htmlResult += '        <span class="delItem" >';
                htmlResult += '            <img src="/media/system/images/mantenimiento/delete.png" title="Eliminar">';
                htmlResult += '        </span>';
            }

            htmlResult += '     </span>';
        }
        
        htmlResult += '  </div>';
        if ( !avalibleDelete( item ) && parseInt(item.registroEtr) != regLastEtr ) {
            htmlResult += '<ul id="itemsH-' + nivel + '">';
            for (var j=0; j<item.itemsHijos.length; j++){
                htmlResult += makeLista( item.itemsHijos[j], nivel );
            }
            htmlResult += '</ul>';
        }
        htmlResult += '</li>';
    }
    return htmlResult;
}

/**
 *  agrega  el estilo de arbol TreeView a la lista de items
 * @returns {undefined}
 */
function addMakeTree() {
    jQuery("#listaItems").treeview({
        collapsed: true,
        animated: "normal"
    });
}

function avalibleDelete( item )
{
    var result = true;
    if( item.itemsHijos.length > 0 ){
        for (var i=0; i<item.itemsHijos.length; i++){
            if (item.itemsHijos[i].published == 1){
                result = false;
            }
        }
    }
    return result;
}