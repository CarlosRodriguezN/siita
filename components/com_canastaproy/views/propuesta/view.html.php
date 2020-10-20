<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso /Edicion de un SubProgramaPrograma
 */
class CanastaproyViewPropuesta extends JView
{
    protected $canDo;

    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        // get the Data
        $form = $this->get( 'Form' );
        $item = $this->get( 'Item' );
        $script = $this->get( 'Script' );

        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = CanastaProyHelper::getActions();

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;

        //  Asigna la data relacionada con la propuesta
        if ( $item->intIdPropuesta_cp != 0 ){
            $mdPropuesta = $this->getModel();

            $this->undTerritorial = $mdPropuesta->lstUnidadesTerritoriales( $item->intIdPropuesta_cp );
            $this->ubicGeografica = $mdPropuesta->lstUbicacionesGeograficas( $item->intIdPropuesta_cp );
            $this->alineacionPropuesta = $mdPropuesta->lstAlineacionesPropuesta( $item->intIdPropuesta_cp );
        }

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        //and make whatever calls you require
        //and make whatever calls you require
        if ( $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_CANASTAPROY_EVENTO_GUARDAR' ), 'propuesta.registrar', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_CANASTAPROY_EVENTO_GUARDAR_SALIR' ), 'propuesta.registrarSalir', false );
        }

        if ( $this->canDo->get( 'core.delete' ) && $this->_availableDel() && $this->item->intIdPropuesta_cp != 0 ){
            $bar->appendButton( 'Standard', 'delete', JText::_( 'COM_CANASTAPROY_EVENTO_ELIMINAR' ), 'propuesta.deletePropuesta', false );
        }

        $bar->appendButton( 'Separator' );

        if ( $this->canDo->get( 'core.edit' ) && $this->canDo->get( 'core.create' ) && $this->canDo->get( 'core.delete' ) ){
            $bar->appendButton( 'Standard', 'list', JText::_( 'COM_CANASTAPROY_EVENTO_LISTAR' ), 'propuesta.list', false );
        }

        $bar->appendButton( 'Standard', 'pdf', JText::_( 'COM_CANASTAPROY_EVENTO_PDF' ), 'propuesta.pdf', false );
        $bar->appendButton( 'Standard', 'excel', JText::_( 'COM_CANASTAPROY_EVENTO_EXCEL' ), 'propuesta.excel', false );
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_CANASTAPROY_EVENTO_CANCELAR' ), 'propuesta.cancel', false );

        //generate the html and return
        return $bar->render();
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();

        //  Accdemos a la hoja de estilos del administrador
        $document->addStyleSheet( 'administrator/templates/system/css/system.css' );

        //  Agregamos hojas de estilo complementarias 
        $document->addCustomTag(
                '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />' . "\n\n" .
                '<!--[if IE 7]>' . "\n" .
                '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<!--[if gte IE 8]>' . "\n\n" .
                '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n"
        );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Accdemos a la hoja de estilos de uploadify
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadify/uploadify.css' );

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );

        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );

        //  Adjunto lista de Arreglos GAP
        $document->addScriptDeclaration( $this->_getLstData(), $type = 'text/javascript' );

        //  Adjunto libreria que controla el lenguaje del java script
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/ES_CANASTAPROY.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/Reglas.js' );

        //  Adjunto libreria para los combos vinculantes 
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/Vinculantes.js' );

        //  Adjunto libreria para gestionar las unidades teritoriales
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/UnidadTerritorial.js' );

        //  Adjunto libreria para gestionar las ubicacion geografica
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/UbicacionGeografica.js' );

        $document->addScript( JURI::root() . 'media/system/js/FormatoNumeros.js' );

        //  Adjunto libreria de la api de google.
        $document->addScript( "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry" );

        //  Adjunto libreria para los mapas de google.
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/Mapa.js' );

        //  Adjunto libreria para gestionar las alineaciones de las propuestas de proyectos.
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/AlineacionPropuesta.js' );

        //  Adjunto libreria para gestionar las alineaciones de las propuestas de proyectos.
        $document->addScript( JURI::root() . 'components/com_canastaproy/views/propuesta/assets/Propuesta.js' );

        //  Archivo que gestiona formateo de numeros de tipo moneda
        $document->addScript( JURI::root() . 'media/system/js/FormatoNumeros.js' );

        //
        //  INDICADORES
        //
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/Indicador.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/Dimension.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/Common.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndFijos.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndGAP.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndEIgualdad.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndEEcorae.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndicador.js' );

        //  Adjunto lista de Arreglos para gestion de tablas dinamicas
        $document->addScriptDeclaration( $this->_getLstData(), $type = 'text/javascript' );

        JText::script( 'COM_CANASTAPROY_PROPUESTA_ERROR_UNACCEPTABLE' );
    }

    /**
     *  Retorna TRUE en el caso que se pueda ELIMINAR un registro si no retorna FALSE 
     * @return boolean
     */
    private function _availableDel()
    {
        $reslut = true;
        if ( count( $this->undTerritorial ) != 0 ||
                count( $this->ubicGeografica ) != 0 ||
                count( $this->alineacionPropuesta ) != 0 ){
            $reslut = false;
        }
        return $reslut;
    }

    /**
     * 
     * Genero los arreglos ( arrays ), con informacion de Indicadores de 
     * Grupo de Accion Prioritaria, Otros Indicadores, Lineas Base
     * 
     * Los cuales sirven para la gestion de informacion de dichos indicadores
     * 
     * @return string
     */
    private function _getLstData()
    {
        $retval = '';

        //  Lista de Coordenadas
        $retval .= 'var lstUbicacionesGeo = new Array();';

        //  Lista de Unidades Territoriales 
        $retval .= 'var lstUndTerritorial = new Array();';

        //  Lista de Metas Nacionales de un Proyecto
        $retval .= 'var lstAlineacionPropuesta = new Array();';

        //  Ubicacion territorial
        if ( $this->undTerritorial ){
            foreach ( $this->undTerritorial as $key => $ut ){
                $retval .= 'var unti = new Array();';

                $retval .= 'unti["idRegistro"]=  ' . ++$key . ';';
                $retval .= 'unti["provincia"]=  "' . $ut->provincia . '";';
                $retval .= 'unti["idProvincia"]= ' . $ut->idProvincia . ';';
                $retval .= 'unti["canton"]=     "' . $ut->canton . '";';
                $retval .= 'unti["idCanton"]=    ' . $ut->idCanton . ';';
                $retval .= 'unti["parroquia"]=  "' . $ut->parroquia . '";';
                $retval .= 'unti["idParroquia"]= ' . $ut->idParroquia . ';';
                $retval .= 'unti["published"]= 1;';

                $retval .= 'lstUndTerritorial.push( unti );';
            }
        }

        //  Ubicacion Geografica
        if ( $this->ubicGeografica ){

            foreach ( $this->ubicGeografica as $key => $ubicGeo ){
                $retval .= 'var ubicGeo = [];';

                $retval .= 'ubicGeo["idRegGrafico"]= ' . ++$key . ';';
                $retval .= 'ubicGeo["idGrafico"]= ' . $ubicGeo->intId_gcp . ';';
                $retval .= 'ubicGeo["tpoGrafico"]= ' . $ubicGeo->intId_tg . ';';
                $retval .= 'ubicGeo["infoTpoGrafico"]= "' . $ubicGeo->strDescripcion_tg . '";';
                $retval .= 'ubicGeo["descGrafico"]= "' . $ubicGeo->strDescripcionGrafico_gcp . '";';
                $retval .= 'ubicGeo["published"]= 1;';

                $retval .= 'var lstPuntos = [];';

                foreach ( $ubicGeo->lstCoordenadas as $keyCoord => $coordenada ){
                    $retval .= 'var punto = []; ';

                    $retval .= 'punto["idRegCoordenada"] = ' . ++$keyCoord . ';';
                    $retval .= 'punto["idCoordenada"] = ' . $coordenada->intId_cgcp . ';';
                    $retval .= 'punto["latitud"] = ' . $coordenada->fltLatitud_cord . ';';
                    $retval .= 'punto["longitud"] = ' . $coordenada->fltLongitud_cord . ';';
                    $retval .= 'punto["published"] = 1;';

                    $retval .= ' lstPuntos.push( punto );';
                }

                $retval .= 'ubicGeo["lstCoordenadas"] = lstPuntos;';

                $retval .= 'lstUbicacionesGeo.push( ubicGeo );';
            }
        }

        //  Alineacion PNBV de la propuesta 
        if ( $this->alineacionPropuesta ){

            foreach ( $this->alineacionPropuesta as $key => $alineacionPrp ){
                $retval .= 'var alnPrpPry = []; ';

                $retval .= 'alnPrpPry["idRegistro"]= ' . ++$key . ';';
                $retval .= 'alnPrpPry["idAlnPropPNBV"]= ' . $alineacionPrp->idPrpPry . ';';
                $retval .= 'alnPrpPry["idObjNacional"]= ' . $alineacionPrp->idObjNacional . ';';
                $retval .= 'alnPrpPry["idPoliticaNacional"]= ' . $alineacionPrp->idPoliticaNacional . ';';
                $retval .= 'alnPrpPry["idMetaNacional"]= ' . $alineacionPrp->idMetaNacional . ';';
                $retval .= 'alnPrpPry["published"]= 1;';

                $retval .= 'lstAlineacionPropuesta.push( alnPrpPry );';
            }
        }

        return $retval;
    }

}