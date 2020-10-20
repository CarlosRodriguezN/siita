jQuery( document ).ready( function(){
    //  Lista de Alineacion del proyecto
    var pnvb = new Array();
    var roles = eval( '('+ jQuery( '#dtaRoles' ).val() +')' );
    
///////////////////////////////////////
//  GESTION DE FECHAS POR DEFAULT
///////////////////////////////////////

jQuery( '#jform_dteFechaInicio_stmdoPry' ).on( 'blur', function(){

    var fchInicioUG = jQuery( '#jform_fchInicioPeriodoUG' ).val();
    var fchInicioFun = jQuery( '#jform_fchInicioPeriodoFuncionario' ).val();

    if( fchInicioUG.length === 0 ){
        jQuery( '#jform_fchInicioPeriodoUG' ).attr( 'value', jQuery( this ).val() );
    }

    if( fchInicioFun.length === 0 ){
        jQuery( '#jform_fchInicioPeriodoFuncionario' ).attr( 'value', jQuery( this ).val() );
    }

})


///////////////////////////////////////
//  RELACION DE PROYECTO CON EL P.N.V.B.
///////////////////////////////////////

    //
    //  Gestiono la relacion de un proyecto con el P.N.V.B.
    //
    jQuery( '#btnAddRelacion' ).click( function(){
        var relacion = [];
        var objetivoNac = jQuery( '#jform_intcodigo_on' ).val();
        var politicaNac = jQuery( '#jform_intcodigo_pn' ).val();
        var metaNac = jQuery( '#jform_idcodigo_mn' ).val();
        
        var infoObjNac = jQuery( '#jform_intcodigo_on option:selected' ).text();
        var infoPolNac = jQuery( '#jform_intcodigo_pn option:selected' ).text();
        var infoMetaNac = jQuery( '#jform_idcodigo_mn option:selected' ).text();

        relacion["objetivoNac"] = objetivoNac;
        relacion["politicaNac"] = politicaNac;
        relacion["metaNac"] = metaNac;

        pnvb.push( relacion );
        
        //  Construyo la Fila
        var fila = "<tr id='"+ metaNac +"'>"
                + "     <td align='center'>"+ infoObjNac +"</td>"
                + "     <td align='center'>"+ infoPolNac +"</td>"
                + "     <td align='center'>"+ infoMetaNac +"</td>";

        if( roles["core.create"] === true || roles["core.edit"] === true ){
            fila+= "    <td align='center'> <a id='upd' href='upd_"+ metaNac +"'>Editar</a> </td>"
                + "     <td align='center'> <a id='del' href='del_"+ metaNac +"'>Eliminar</a> </td>"
        }else{
            fila+= "<td align='center'> Editar </td>"
                + " <td align='center'> Eliminar </td>"
        }

        fila += " </tr>";
         
        //  Agrego la fila creada a la tabla
        jQuery( '#lstRelaciones > tbody:last' ).append( fila );
    });

    /**
     * 
     * Recorre los comboBox del Formulario a la posicion inicial
     * 
     * @param {type} combo
     * @param {type} posicion
     * @returns {undefined}
     */
    function recorrerCombo( combo, posicion )
    {
        jQuery( combo ).each( function(){
            if( jQuery( this ).val() == posicion ){
                jQuery( this ).attr( 'selected', 'selected' );
            }
        });
    }
    
    /**
     * 
     * Transforma un Array en Objecto de manera Recursiva
     * 
     * @param {type} list       Arreglo con informacion a transformar a JSon
     * @returns {unresolved}
     */
    function list2Object( list )
    {
        var obj = {};
        for( key in list ){
            if( typeof( list[key] ) == 'object' ){
                obj[key] = list2Object( list[key] );
            }else{
                obj[key] = list[key];
            }
        }
        
        return obj;
    }

    /**
     * 
     * Gestion de informacion general de un proyecto
     * 
     * @returns {Array}
     */
    function datosGrlesProyecto2Array()
    {
        var dtaGrlPry = [];
        
        dtaGrlPry['intCodigo_pry']              = jQuery( '#jform_intCodigo_pry' ).val();
        dtaGrlPry['intIdEntidad_ent']           = jQuery( '#jform_intIdEntidad_ent' ).val();
        dtaGrlPry['idPrograma']                 = jQuery( '#jform_intCodigo_prg' ).val();
        dtaGrlPry['idSubPrograma']              = jQuery( '#jform_intCodigo_sprg' ).val();
        dtaGrlPry['idTpoSubPrograma']           = jQuery( '#jform_intCodigo_tsprg' ).val();
        dtaGrlPry['strNombre_pry']              = jQuery( '#jform_strNombre_pry' ).val();
        dtaGrlPry['dteFechaInicio_stmdoPry']    = jQuery( '#jform_dteFechaInicio_stmdoPry' ).val();
        dtaGrlPry['dteFechaFin_stmdoPry']       = jQuery( '#jform_dteFechaFin_stmdoPry' ).val();
        dtaGrlPry['inpDuracion_stmdoPry']       = jQuery( '#jform_inpDuracion_stmdoPry' ).val();
        dtaGrlPry['intcodigo_unimed']           = jQuery( '#jform_intcodigo_unimed' ).val();
        dtaGrlPry['strCodigoInterno_pry']       = jQuery( '#jform_strCodigoInterno_pry' ).val();
        dtaGrlPry['strcup_pry']                 = jQuery( '#jform_strcup_pry' ).val();
        dtaGrlPry['inpCodigo_cb']               = jQuery( '#jform_inpCodigo_cb' ).val();
        
        dtaGrlPry['idFuncionario']              = jQuery( '#jform_idFuncionario' ).val();
        
        dtaGrlPry['strCargoResponsable_pry']    = jQuery( '#jform_strCargoResponsable_pry' ).val();
        dtaGrlPry['dcmMonto_total_stmdoPry']    = unformatNumber( jQuery( '#jform_dcmMonto_total_stmdoPry' ).val() );
        dtaGrlPry['strDescripcion_pry']         = jQuery( '#jform_strDescripcion_pry' ).val();
        dtaGrlPry['intTotal_benDirectos_pry']   = jQuery( '#jform_intTotal_benDirectos_pry' ).val();
        dtaGrlPry['intCodigo_ug']               = jQuery( '#jform_intCodigo_ug' ).val();
        
        // CAMPO URL TABLEU
        dtaGrlPry['urlTableU']              = jQuery( '#jform_strURLtableU' ).val();
        dtaGrlPry['responsable']            = getResponsableData();

        //  Sectores de intervencion
        dtaGrlPry['idSubSector']            = jQuery( '#jform_subsector' ).val();
        dtaGrlPry['idSIV']                  = jQuery( '#jform_intIdStr_intervencion' ).val();
        

        return dtaGrlPry;
    }


    /**
     * 
     * Gestion de meta de un proyecto
     * 
     * @returns {Array}
     */
    function datosMetaPry2Array()
    {
        var dtaMeta = [];
        
        dtaMeta['inpcodigo_unianl']         = jQuery( '#jform_idUndAnalisisMeta' ).val(); 
        dtaMeta['intcodigo_unimed']         = jQuery( '#jform_idUndMedidaMeta' ).val(); 
        dtaMeta['strdescripcion_metapry']   = jQuery( '#jform_descripcionMeta' ).val(); 
        dtaMeta['intvalor_metapry']         = jQuery( '#jform_valorMeta' ).val();
        
        return dtaMeta;
    }


    /**
     * 
     * Gestion de informacion de indicadores de financieros economico y benenficiarios
     * 
     * @returns {Array}
     */
    function datosIndGrls2Array()
    {
        var dtaInd = [];
        
        dtaInd['intTasaDctoEco'] = jQuery( '#jform_intTasaDctoEco' ).val();
        dtaInd['intValActualNetoEco'] = jQuery( '#jform_intValActualNetoEco' ).val();
        dtaInd['intTIREco'] = jQuery( '#jform_intTIREco' ).val();
        
        dtaInd['intTasaDctoFin'] = jQuery( '#jform_intTasaDctoFin' ).val();
        dtaInd['intValActualNetoFin'] = jQuery( '#jform_intValActualNetoFin' ).val();
        dtaInd['intTIRFin'] = jQuery( '#jform_intTIRFin' ).val();
        
        dtaInd['intBenfDirectoHombre'] = jQuery( '#jform_intBenfDirectoHombre' ).val();
        dtaInd['intBenfDirectoMujer'] = jQuery( '#jform_intBenfIndDirectoMujer' ).val();
        dtaInd['intTotalBenfDirectos'] = jQuery( '#jform_intTotalBenfIndDirectos' ).val();
        
        dtaInd['intBenfIndDirectoHombre'] = jQuery( '#jform_intBenfIndDirectoHombre' ).val();
        dtaInd['intBenfIndDirectoMujer'] = jQuery( '#jform_intBenfIndDirectoMujer' ).val();
        dtaInd['intTotalBenfIndDirectos'] = jQuery( '#jform_intTotalBenfIndDirectos' ).val();
        
        return dtaInd;
    }
    
    /**
     * Retorna la información de responsables
     * @returns {Array}
     */
    function getResponsableData()
    {
        var jsonResponsable = {
            // CAMPOS DE LA UNIDAD DE GESTION RESPONSABLE
            idUGR: jQuery('#jform_intIdUndGestion').val(),
            fchIniciUGR: jQuery('#jform_fchInicioPeriodoUG').val(),
            
            // CAMPOS DEL FUNCIONARIO RESPONSABLE
            idResponsable: jQuery('#jform_idResponsable').val(),
            fchIniciRes: jQuery('#jform_fchInicioPeriodoFuncionario').val()
        };

        return jsonResponsable;
    }


    Joomla.submitbutton = function(task)
    {
        switch (task) {
            
            case 'proyecto.save':
                jQuery.blockUI({ message: jQuery('#msgProgress') });
                guardarProyecto( 'save' );
            break;
            
            case 'proyecto.guardarContinuar':
                jQuery.blockUI({ message: jQuery('#msgProgress') });
                guardarProyecto( 'guardarContinuar' );
            break;
            
            //  En caso que usuario cancele la gestion de un proyecto, y regresa a lista de proyectos
            case 'proyecto.cancel':
                event.preventDefault();
                history.back();
            break;
            
            case 'proyecto.organigrama':
                SqueezeBox.fromElement('index.php?option=com_reporte&view=organigrama&layout=edit&tmpl=component&task=preview', {size:{x:1024,y:500}, handler:"iframe"});
            break;

            //  En caso que el usuario decida eliminar un proyecto
            case 'proyecto.delete':
                jConfirm("¿Seguro desea eliminar el Contrato?", "SIITA - ECORAE", function(r) {
                    if (r) {
                        if (validateDelete()) {
                            Joomla.submitform(task);
                        } else {
                            var cadMess = '';
                            cadMess += '<table>';
                            cadMess += '    <tr align=center>';
                            cadMess += '        <td>Existen relaciones con el proyecto, que no le permiten ser eliminado.</td>';
                            cadMess += '    </tr>';
                            cadMess += '    <tr align=center>';
                            cadMess += '        <td>Por favor consulte con el Administrador</td>';
                            cadMess += '    </tr>';
                            cadMess += '</table>';

                            jAlert(cadMess, 'SIITA - ECORAE');
                        }
                    } else {

                    }
                });
            break;

            default:
                Joomla.submitform(task);
            break;                
        }
    }
    
    
    function guardarProyecto( task )
    {
        //  Obtengo URL completa del sitio
        var url = window.location.href;
        var path = url.split('?')[0];

        //  Cambio array Datos Generales a Notacion JSON
        var dtaPry = JSON.stringify(list2Object(datosGrlesProyecto2Array()));

        //  Meta de un proyecto
        var dtaMeta = JSON.stringify(list2Object(datosMetaPry2Array()));

        //  Marco Logico de un proyecto
        var dtaML = JSON.stringify( list2Object( objMarcoLogico ) );

        //  Cambio array Objetivos a Notacion JSON
        var dtaObjetivos = JSON.stringify( objLstObjetivo );

        //  Cambio array Relacion Proyecto al PNVB a Notacion JSON
        var dtaRelProyecto = JSON.stringify( list2Object( lstAlineacionProyecto ) );

        //  Cambio array Unidad Territorial a Notacion JSON
        var dtaUndTerritorial = JSON.stringify( list2Object( undTerritorial ) );

        //  Cambio el array Lista de Coordenadas a Notacion JSON
        var dtaGraficos = JSON.stringify( list2Object( lstUbicacionesGeo ) );

        //  Recorro informacion de indicadores y seteo valores por default
        updValoresDefault( objGestionIndicador );

        //  Cambio array Beneficiarios a Notacion JSON
        var dtaIndicadores = JSON.stringify( list2Object( objGestionIndicador ) );

        //  Cambio array Beneficiarios a Notacion JSON
        var dtaPlanes = JSON.stringify( list2Object( dtaPlanOperativo ) );

        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: { method          : 'POST',
                    option          : 'com_proyectos',
                    view            : 'proyecto',
                    tmpl            : 'component',
                    format          : 'json',
                    controller      : 'proyecto',
                    action          : 'registroProyecto',
                    idEstadoEntidad : jQuery( '#jform_idEstadoEntidad' ).val(),
                    dtaProyecto     : dtaPry,
                    dtaMarcoLogico  : dtaML,
                    dtaMetaPry      : dtaMeta,
                    dtaObjetivos    : dtaObjetivos,
                    dtaDPA          : dtaUndTerritorial,
                    dtaGraficos     : dtaGraficos,
                    dtaPNBV         : dtaRelProyecto,
                    dtaIndicadores  : dtaIndicadores,
                    dtaPlanes       : dtaPlanes
            },
            error: function(jqXHR, status, error) {
                jQuery.unblockUI();
                alert('Proyectos - Registro de Proyectos: ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete( function( data ) {
            
            idProyecto = ( data.responseText.trim() !== "null" )? data.responseText.trim() 
                                                                : jQuery( '#jform_intCodigo_pry' ).val();

            var optionsImagenes = { method          : "POST",
                                    option          : "com_proyectos",
                                    controller      : "proyecto",
                                    task            : "proyecto.registroImagenes",
                                    tmpl            : "component",
                                    intCodigo_pry   : idProyecto
                                };

            if (banIconoExiste == 1) {
                jQuery( '#iconoProyecto' ).data('uploadifive').settings.formData = optionsImagenes;
                jQuery( '#iconoProyecto' ).uploadifive('upload');
            }

            //  SI EXISTEN IMAGENES, Gestionan la carga de Imagenes
            if (banImagenesExiste == 1) {
                jQuery('#imgsProyecto').data('uploadifive').settings.formData = optionsImagenes;
                jQuery('#imgsProyecto').uploadifive('upload');
            }

            if (!banImagenesExiste && !banIconoExiste) {
                if( task === 'save' ){
                    location.href = 'http://' + window.location.host + '/index.php?option=com_proyectos&view=proyectos';
                }else{
                    var idProyecto = jQuery( '#idProyecto' ).val();
                    location.href = 'http://' + window.location.host + '/index.php?option=com_proyectos&view=proyecto&layout=edit&intCodigo_pry= '+ idProyecto;
                }
            }
            
        });
    }

});



function updValoresDefault( Indicadores )
{
    recorrerIndicadores( Indicadores.indEconomico );
    recorrerIndicadores( Indicadores.indFinanciero );
    recorrerIndicadores( Indicadores.indBDirecto );
    recorrerIndicadores( Indicadores.indBIndirecto );
    
    return;
}


function recorrerIndicadores( lstIndicadores )
{
    var nri = lstIndicadores.length;
    for( var x = 0; x < nri; x++ ){
        setValoresDefault( lstIndicadores[x] );
    }

    return;
}


function setValoresDefault( dtaIndicador )
{
    var fchInicioEntidad= jQuery( '#jform_dteFechaInicio_stmdoPry' ).val();
    var fchFinEntidad   = jQuery( '#jform_dteFechaFin_stmdoPry' ).val();

    if( typeOf( dtaIndicador.fchHorzMimimo ) === "null" || dtaIndicador.fchHorzMimimo === "0000-00-00" ){
        dtaIndicador.fchHorzMimimo = fchInicioEntidad;
    }

    if( typeOf( dtaIndicador.fchHorzMaximo ) === "null" || dtaIndicador.fchHorzMaximo === "0000-00-00" ){
        dtaIndicador.fchHorzMaximo = fchFinEntidad;
    }

    if( typeOf( dtaIndicador.fchInicioFuncionario ) === "null" || dtaIndicador.fchInicioFuncionario === "0000-00-00" ){
        dtaIndicador.fchInicioFuncionario = fchInicioEntidad;
    }

    if( typeOf( dtaIndicador.fchInicioUG ) === "null" || dtaIndicador.fchInicioUG === "0000-00-00" ){
        dtaIndicador.fchInicioUG = fchInicioEntidad;
    }

    if( typeOf( dtaIndicador.idUGResponsable ) === "null" || parseInt( dtaIndicador.idUGResponsable ) === 0 ){
        dtaIndicador.idUGResponsable = jQuery( '#jform_intIdUndGestion' ).val();
    }

    if( typeOf( dtaIndicador.idResponsableUG ) === "null" || parseInt( dtaIndicador.idResponsableUG ) === 0 ){
        dtaIndicador.idResponsableUG = jQuery( '#jform_intIdUGResponsable' ).val();
    }

    if( typeOf( dtaIndicador.idResponsable ) === "null" || parseInt( dtaIndicador.idResponsable ) === 0 ){
        dtaIndicador.idResponsable = jQuery( '#jform_idResponsable' ).val();
    }
    
    return;
}


function validateDelete() {
    var flag = true;
    for (var j = 0; j < lstUbicacionesGeo.length; j++) {
        if (lstUbicacionesGeo[j].published == 1) {
            flag = false;
        }
    }
    for (var j = 0; j < lstObjetivos.length; j++) {
        if (lstObjetivos[j].published == 1) {
            flag = false;
        }
    }
    for (var j = 0; j < undTerritorial.length; j++) {
        if (undTerritorial[j].published == 1) {
            flag = false;
        }
    }
    for (var j = 0; j < lstLineasBase.length; j++) {
        if (lstLineasBase[j].published == 1) {
            flag = false;
        }
    }
    for (var j = 0; j < lstLineasBaseTmp.length; j++) {
        if (lstLineasBaseTmp[j].published == 1) {
            flag = false;
        }
    }
    for (var j = 0; j < lstAlineacionProyecto.length; j++) {
        if (lstAlineacionProyecto[j].published == 1) {
            flag = false;
        }
    }
    return flag;
}