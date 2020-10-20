jQuery(document).ready(function() {
    var idUndTerritorial;
    var banIdRegUT = -1;
    var tmpUT=false;

    /**
     *  Muestra el formulario de la linea base
     * @returns {undefined}
     */
    function showFormUT() {
        jQuery('#imgUndTerr').css("display", "none");
        jQuery('#frmUndTerr').css("display", "block");
    }
    
    /**
     *  Oculta el formulario de la linea base
     * @returns {undefined}
     */
    function hideFormUT() {
        jQuery('#imgUndTerr').css("display", "block");
        jQuery('#frmUndTerr').css("display", "none");
        banIdRegUT = -1;
        limpiarFrmUT();
    }
    
    /**
     *  evento CLICK en el boton AGREGAR unidad territorial 
     */
    jQuery('#addLnUndTerrTable').click(function() {
        banIdRegUT=-1;
        limpiarFrmUT();
        showFormUT();
    });

    /**
     *  Evento ClICK en el boton CANCELAR unidad territorial
     */
    jQuery('#btnClnUndTerritorial').click(function() {
        hideFormUT();
    });
    
    /**
     * 
     *  Valida si los campos del formulario Unidad Territorial estan completos.
     * 
     *  @returns {Boolean}   true en caso de estar completos
     *  
     */
    function valUndTerr()
    {
        var ban = false;
        var idProvincia = jQuery('#jform_idProvincia');
        
        if( idProvincia.val() !== ""
            && parseInt( idProvincia.val() ) > 0 ){
            ban = true;
        }else{
            idProvincia.validarElemento();
        }
        
        return ban;
    }
    
    /**
     * Recupera los datos de la unidad territorial desde el formulario
     * @returns {UnidadTerritorial|Boolean}
     */
    function getFormUndTer() {
        var undTerritorial = false;

        var idProvincia = parseInt( jQuery('#jform_idProvincia').val() );
        var idCanton    = parseInt( jQuery('#jform_idCanton').val() );
        var idParroquia = parseInt( jQuery('#jform_idParroquia').val() );
        
        var provincia   = jQuery('#jform_idProvincia :selected').text();
        var canton      = ( idCanton !== 0 )? jQuery('#jform_idCanton :selected').text()
                                            : '-----';
                                            
        var parroquia   = ( idParroquia !== 0 ) ? jQuery('#jform_idParroquia :selected').text()
                                                : '-----';

        switch (true) {
            case(idProvincia != 0 && idCanton != 0 && idParroquia != 0):
                idUndTerritorial = idParroquia;

                break;

            case(idProvincia != 0 && idCanton != 0 && idParroquia == 0):
                idUndTerritorial = idCanton;
                break;

            case(idProvincia != 0 && idCanton == 0 && idParroquia == 0):
                idUndTerritorial = idProvincia;
                break;
        }

        var idRegUT = (banIdRegUT == -1)? lstTmpUT.length
                                        : parseInt(banIdRegUT);

        undTerritorial = new UnidadTerritorial(idRegUT, idUndTerritorial, provincia, canton, parroquia);

        return undTerritorial;
    }
    
    /**
     * Evento CLICK en el boton GUARDAR unidad territorial
     */
    jQuery('#btnAddUndTerritorial').live('click', function() {
        var objUndTerr = getFormUndTer();

        if ( valUndTerr() ) {
            if (!existeUndTerritorial(objUndTerr)) {
                
                delFilaUT( -1 );
                
                if (banIdRegUT != -1) {
                    updRegUndTer(objUndTerr);
                } else {
                    lstTmpUT.push(objUndTerr);
                    //  Agrego la fila creada a la tabla
                    jQuery('#lstUndTerritorialesInd > tbody:last').append(objUndTerr.addFilaUT(0));
                    hideFormUT();
                    limpiarFrmUT();
                }
            } else {
                jAlert(MSG_VALOR_EXISTE_OK, SIITA_ECORAE);
            }
        } else {
            jAlert(MSG_VALOR_INCOMPLETO, SIITA_ECORAE);
        }
        
        //  Restauro a valores predeterminados formulario de registro de lineas base
        
    });
    
    /**
     * actualiza el REGISTRO de una UNIDAD TERRITORIAL
     * @param {JSON} objUndTerr unidad terririal
     * @returns {undefined}
     */
    function updRegUndTer(objUndTerr) {
        lstTmpUT[objUndTerr.idRegUT] = objUndTerr;
        updFilaUT(objUndTerr.addFilaUT(1),objUndTerr.idRegUT);
        banIdRegUT = -1
        tmpUT=false;
        hideFormUT();
        limpiarFrmUT();
    }
    
    /**
     * 
     * Registro de unidades territoriales
     * 
     * @param {Object} undTerritorial     Unidad Territorial
     * @returns {undefined}
     */
    function existeUndTerritorial(undTerritorial){
        var ban = false;
        for (var x = 0; x < lstTmpUT.length; x++) {
            if (lstTmpUT[x].toString() == undTerritorial.toString()) {
                ban = true;
            }
        }
        return ban;
    }
    
    /**
     *  Gestiono la acualizacion de una unidad territorial
     */
    jQuery( '.updIndUT' ).live( 'click', function(){
        var objUndTerr = getFormUndTer();
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        banIdRegUT = idFila;
        
        if (tmpUT) {
            if (tmpUT.toString() == objUndTerr.toString()) {
                loadFormUndTerr(banIdRegUT);
            } else {
                autoSaveUndTerr(objUndTerr, banIdRegUT);
            }
        } else {
            loadFormUndTerr(banIdRegUT);
        }
     });
    
    /**
     * Recupera la inforamción del formulario.
     * @param {int} banIdRegUT  id registro de la unidad territoria.
     * @returns {undefined}
     */
    function loadFormUndTerr(idFila){
        tmpUT = lstTmpUT[idFila];
        
        var url = window.location.href;
        var path = url.split('?')[0];
        
        jQuery.ajax({type: 'POST',
            url: path,
            dataType: 'JSON',
            data: {option: 'com_proyectos',
                view: 'proyecto',
                tmpl: 'component',
                format: 'json',
                action: 'getDPA',
                idUndTerritorial: lstTmpUT[idFila].idUndTerritorial
            },
            error: function(jqXHR, status, error) {
                alert(COM_INDICADORES_UT_ERROR + ' ' + error + ' ' + jqXHR + ' ' + status);
            }
        }).complete(function(data) {
            var dataInfo = eval("(" + data.responseText + ")");
            
            //  Recorro hasta una determinada posicion el combo de provincias
            jQuery('#jform_idProvincia option').recorrerCombo( dataInfo.idProvincia );

            //  Simulo una seleccion de un elemento de provincia
            jQuery('#jform_idProvincia').trigger('change', dataInfo.idCanton);

            //  Simulo una seleccion de un elemento de canton
            jQuery('#jform_idCanton').trigger('change', [dataInfo.idCanton, dataInfo.idParroquia]);
            showFormUT();
        });
    }
    
    /**
     * 
     * @param {type} objUndTerr
     * @returns {undefined}
     */
    function autoSaveUndTerr (objUndTerr,banIdRegUT){
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(COM_INDICADORES_AUTO_UNDTERR, COM_INIDCADORES_SIITA, function(result) {
            if (result) {
                updRegUndTer(objUndTerr);
                loadFormUndTerr(banIdRegUT);
            }else{
                loadFormUndTerr(banIdRegUT);
            }
        });
    }
    
    /**
     * Gestiona la eliminacion de la Unidad Territorial de un indicador
     */
    jQuery( '.delIndUT' ).live( 'click', function(){
        var updFila = jQuery( this ).parent().parent();
        var idFila = updFila.attr( 'id' );
        
        //  Emito un mensaje de confirmacion antes de eliminar registro
        jConfirm(  "¿Est&aacute; seguro que desea eliminar este registro?", "SIITA - ECORAE", function( result ){
            if( result ){
                lstTmpUT.splice( idFila, 1 );
                delFilaUT( idFila );
                
                validarFilasUT();
            }
        });
    });
    
    /**
     * 
     * Actualizo informacion de un determinada Unidad Territorial
     * 
     * @param {Object} undTerritorial   Objeto Unidad Territorial
     * @returns {undefined}
     */
    function updFilaUT( filaUT ,idRegUT){
        jQuery( '#lstUndTerritorialesInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idRegUT ){
                jQuery( this ).html( filaUT );
            }
        });
    }

    /**
     * 
     * Elimino una fila de la tabla Unidad Territorial
     * 
     * @param {int} idFila  Identificador de la fila
     * @returns {undefined}
     * 
     */
    function delFilaUT( idFila ){
        //  Elimino fila de la tabla lista de GAP
        jQuery( '#lstUndTerritorialesInd tr' ).each( function(){
            if( jQuery( this ).attr( 'id' ) == idFila ){
                jQuery( this ).remove();
            }
        })
    }
    
    
    
    function validarFilasUT()
    {
        var nrUT = lstTmpUT.length;
        
        if( nrUT === 0 ){
            objUT = new UnidadTerritorial();
            jQuery('#lstUndTerritorialesInd > tbody:last').append( objUT.addFilaSinRegistros() );
        }

        return;
    }
    
    
    jQuery( '#btnCancelUndTerritorial' ).live( 'click', function(){
        limpiarFrmUT();
    })
    

    /**
     * 
     * Restauro a valores predeterminados el formulario de gestion de lineas Base
     * 
     * @returns {undefined}
     * 
     */
    function limpiarFrmUT(){
        //  Coloco en la posicion inicial el combo de Provincias
        jQuery( '#jform_idProvincia option' ).recorrerCombo( 0 );

        //  EnceroCombo Combo de Cantones
        jQuery( '#jform_idCanton option' ).enCerarCombo();

        //  EnceroCombo Combo de Parroquias
        jQuery( '#jform_idParroquia option' ).enCerarCombo();
    }
    
});