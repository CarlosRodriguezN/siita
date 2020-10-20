<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//  import Joomla view library
jimport('joomla.application.component.view');

//  load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

/**
 * Vista Fase
 */
class ProyectosViewProyecto extends JViewLegacy
{
    /**
     * display method of Hello view
     * @return void
     */
    protected $_dtaML;
    protected $_dtaPlanes;
    protected $_pathIcono;
    protected $_ticketTableu;
    protected $canDo;


    public function display( $tpl = null )
    {
        // Get the Data
        $form   = $this->get( 'Form' );
        $item   = $this->get( 'Item' );
        $script = $this->get( 'Script' );
        $levels = JFactory::getUser()->getAuthorisedViewLevels();
        
        $this->canDo = ProyectosHelper::getActions();
        
        if( $this->item->proyecto_access && !in_array( $this->item->proyecto_access, $levels ) ){
            $tpl = 'upgrade';
        }
        
        if( is_null( $tpl ) ){
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
            }
            
            $this->title = JText::_( 'COM_PROYECTOS_PROYECTOS' );

            // Assign the Data
            $this->form             = $form;
            $this->item             = $item;
            $this->script           = $script;
            $this->intCodigo_pry    = $item->intCodigo_pry;
            $this->idEntidad        = $item->intIdEntidad_ent;

            $this->_dtaML           = FALSE;
            $this->coordenadas      = FALSE;
            $this->lstGraficos      = array();
            $this->lstPuntos        = array();
            $this->lstImagenes      = array();
            $this->lstLineasBase    = array();
            $this->undTerritorial   = FALSE;
            
            //  Accedo al Modelo ( ProyectosModelProyecto )
            $modelo = $this->getModel();
            
            //  Si el Identifador del Proyecto es Diferente de cero "0", 
            //  obtengo Informacion de Indicadores.
            if ($item->intCodigo_pry <> 0) {
                //  Obtiene la estructura de los cestores de intervencio
                $this->strVgt =$item->intIdStr_intervencion;
                $this->strSecIntrv = $modelo->getStrSecIntrv( $this->strVgt );

                //  Obtengo informacion de marco logico de un proyecto
                $this->_dtaML = $modelo->getDataML();

                //  Obtengo una lista de las unidades territoriales de el proyecto
                $this->undTerritorial = $modelo->getLstUnidadTerritorial();

                //  Obtengo una lista de Graficos a los que esta relacionado un determinado Proyecto
                $this->lstGraficos = $modelo->getLstGraficosProyecto( $item->intCodigo_pry );

                //  Obtengo una lista de Imagenes cargadas en el sistema
                $this->lstObjetivos = $modelo->getLstObjetivos( $this->idEntidad );

                //  Obtengo lista de MetasNacionales que cumple el proyecto
                $this->lstMetasNacionalesProyecto = $modelo->getListMetasNacionalesProyecto( $item->intCodigo_pry );

                //  Obtengo lista de de imagenes del proyecto
                $this->lstImagenes =$this->get( 'ImagenesProyecto' );

                //  Obtengo lista de de icons del proyecto
                $this->icono = $this->get( 'IconProyecto' );

                //  Listo todos los contratos asociados a este programa
                $this->lstContratos = $modelo->lstContratosPry( JRequest::getVar( 'intCodigo_pry' ) );

                //  Listo todos los convenios asociados a este programa
                $this->lstConvenios = $modelo->lstConveniosPry( JRequest::getVar( 'intCodigo_pry' ) );
            } else {
                //  Obtiene el sertor de intervencion vigente para obtener us estructrura
                $strVgt = $modelo->getSctIntrvVigente();
                $this->strVgt = $strVgt->id;
                
                //  Obtiene la estructura de los cestores de intervencio
                $this->strSecIntrv = $modelo->getStrSecIntrv( $this->strVgt );
            }
        }
        
        $this->_ticketTableu = $this->_getTicketTableu( 'IndicadoresporSexo/IndicadoresporSexo' );
        
        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    private function _getTicketTableu( $nombreDashBoard )
    {
        //  Retorna informacion URL con ticket de confianza de los server de ECORAE
        $mdTableau = $this->getModel();
        return $mdTableau->getTicketTableuPorNombre( $nombreDashBoard );
    }
    
    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar('toolbar');

        //  boton ORGANIGRAMA
        $bar->appendButton( 'Standard', 'organization', JText::_( 'COM_PROYECTOS_ORGANIGRAMA' ), 'proyecto.organigrama', false );

        if( $this->canDo->get( 'core.admin' ) ){
            //  Control y Monitoreo
            $bar->appendButton( 'Standard', 'control', JText::_( 'COM_PROYECTOS_CONTROL_Y_MONITOREO' ), 'proyecto.panel', false );
        }
        
        
        //  and make whatever calls you require
        $bar->appendButton( 'Standard', 'pdf', JText::_( 'COM_PROYECTOS_REPORTE_SENPLADES' ), 'proyecto.senplades', false );
        
        $bar->appendButton( 'Separator' );

        //  Si tiene permiso para editar, se abilita la opcion para guardar
        if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ){
            //  botones  GUARDAR
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_PROYECTOS_EVETO_GUARDAR' ), 'proyecto.guardarContinuar', false );

            //  botones  GUARDAR y CERRAR
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_PROYECTOS_GUARDAR_CERRAR' ), 'proyecto.save', false );
            
            //  Boton Eliminar
            $bar->appendButton( 'Standard', 'delete', JText::_( 'COM_PROYECTOS_REPORTE_ELIMINAR' ), 'proyecto.delete', false );
        }
        
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_PROYECTOS_REPORTE_CANCELAR' ), 'proyecto.listar', false );

        //  Generate the html and return
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
        $document->addStyleSheet('administrator/templates/system/css/system.css');

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

        //  hoja de estilos para los formularios
        $document->addStyleSheet(JURI::root() . 'media/system/css/siita/common.css');
        
        //  Accdemos a la hoja de estilos de uploadify
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadfive/uploadifive.css');

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css');

        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/css/alerts/jquery.alerts.css');
        
        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css');
        
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Archivo comun
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Common.js');

        //  Adjunto script JQuery al sitio
        $document->addScript(JURI::root() . 'media/system/js/jquery-1.7.1.min.js');

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript(JURI::root() . 'media/system/js/jquery-noconflict.js');

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript(JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');
        

        $document->addScriptDeclaration( $this->_getLstData(), $type = 'text/javascript' );
        
        //  Adjunto libreria para mostrar la ventana pop pop de guardado.
        $document->addScript(JURI::root() . 'media/system/js/bPopup.js');
        
        //  PlugIn jQuery uploadify
        $document->addScript(JURI::root() . 'media/system/js/uploadfive/jquery.uploadifive.min.js');
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );
        
        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Reglas.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Vinculantes.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Proyecto.js');
        
        //  Adjunto libreria para mostrar la ventana pop pop de guardado.
        $document->addScript(JURI::root() . 'media/system/js/FormatoNumeros.js');
        
        //
        //  Planficacion Operativa
        //
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/plan/GestionPlanes.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/plan/PlanObjetivo.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/plan/PlanOperativo.js');
        
        //
        //  MARCO LOGICO
        //  Adjunto libreria que la gestion de Marco Logico de un proyecto
        //  
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/MarcoLogico/GestionMarcoLogico.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/MarcoLogico/MarcoLogico.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/MarcoLogico/Fin.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/MarcoLogico/Proposito.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/MarcoLogico/Componente.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/MarcoLogico/Actividad.js');

        //
        //  INDICADORES
        //
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/Indicador.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/Dimension.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/Common.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndFijos.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndGAP.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndEIgualdad.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndEEcorae.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndicador.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/OtrosIndicadores.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionOtrosIndicadores.js');

        //  Adjunto libreria que la gestion de Unidades Territoriales
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/UnidadTerritorial.js');

        //  Adjunto libreria que la gestion de Coordenadas
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Coordenadas.js');

        //  Adjunto libreria que la gestion de Objetivos
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/GestionObjetivo.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/GestionObjetivos.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Objetivo.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Objetivos.js');
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/ES_OBJETIVO.js');

        //  Adjunto libreria que la gestion de Alineacion de Proyecto
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/AlineacionProyecto.js');

        //  Adjunto libreria que la gestion de Carga de Archivos
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/CargaArchivos.js');

        //agregando la api de google
        $document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry");

        //agregando lo necesario para le mapa.
        $document->addScript(JURI::root() . 'components/com_proyectos/views/proyecto/assets/Mapa.js');

        //  Adjunto lista de objetivos
        $document->addScriptDeclaration( $this->_getLstObjetivos(), $type = 'text/javascript' );
        
        // Adjunto la lista de imagenes
        $document->addScriptDeclaration( $this->_getLstArchivos(), $type = 'text/javascript' );
       
        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
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
    private function _getLstData() {
        
        $retval = '';
        //  Lista de Coordenadas
        $retval .= 'lstUbicacionesGeo = new Array();';

        //  Lista de Objetivos Especificos
        $retval .= 'var lstObjetivos = new Array();';

        //  Lista de Unidades Territoriales 
        $retval .= 'var undTerritorial = new Array();';

        //  Lista de Lineas Base
        $retval .= 'var lstLineasBase = new Array();';

        //  Lista de Temporal de Lineas Base
        $retval .= 'var lstLineasBaseTmp = new Array();';

        //  Lista de Metas Nacionales de un Proyecto
        $retval .= 'var lstAlineacionProyecto = new Array();';

        //  Ubicacion Geografica
        if ($this->lstGraficos) {
            
            foreach ($this->lstGraficos as $key => $ubicGeo) {
                $retval .= 'var ubicGeo = new Array();';

                $retval .= 'ubicGeo["idRegGrafico"] = ' . ++$key . ';';
                $retval .= 'ubicGeo["idGrafico"]    = ' . $ubicGeo->idGrafico . ';';
                $retval .= 'ubicGeo["tpoGrafico"]   = ' . $ubicGeo->tpoGrafico . ';';
                $retval .= 'ubicGeo["infoTpoGrafico"]= "' . $ubicGeo->infoTpoGrafico . '";';
                $retval .= 'ubicGeo["descGrafico"]  = "' . $ubicGeo->descGrafico . '";';
                $retval .= 'ubicGeo["published"]    = 1;';

                $retval .= 'var lstPuntos = new Array();';

                foreach ($ubicGeo->lstCoordenadas as $keyCoord => $coordenada) {
                    $retval .= 'var punto = new Array(); ';

                    $retval .= 'punto["idRegCoordenada"]= ' . ++$keyCoord . ';';
                    $retval .= 'punto["idCoordenada"]   = ' . $coordenada->idCoordenada . ';';
                    $retval .= 'punto["latitud"]        = ' . $coordenada->latitud . ';';
                    $retval .= 'punto["longitud"]       = ' . $coordenada->longitud . ';';
                    $retval .= 'punto["published"]      = 1;';

                    $retval .= ' lstPuntos.push( punto );';
                }

                $retval .= 'ubicGeo["lstCoordenadas"] = lstPuntos;';

                $retval .= 'lstUbicacionesGeo.push( ubicGeo );';
            }
        }

        //  Unidad Territorial
        if ($this->undTerritorial) {
            foreach ($this->undTerritorial as $key=>$ut) {
                $retval .= 'var unti = new Array();';

                $retval .= 'unti["idRegistro"]=  ' . $key++ . ';';
                $retval .= 'unti["provincia"]=  "' . $ut->provincia . '";';
                $retval .= 'unti["idProvincia"]= ' . $ut->idProvincia . ';';
                $retval .= 'unti["canton"]=     "' . $ut->canton . '";';
                $retval .= 'unti["idCanton"]=    ' . $ut->idCanton . ';';
                $retval .= 'unti["parroquia"]=  "' . $ut->parroquia . '";';
                $retval .= 'unti["idParroquia"]= ' . $ut->idParroquia . ';';
                $retval .= 'unti["published"]= 1;';

                $retval .= 'undTerritorial.push( unti );';
            }
        }

        // Metas Nacionales
        if ($this->lstMetasNacionalesProyecto) {
            foreach ($this->lstMetasNacionalesProyecto as $key => $mnp) {
                $retval .= 'var lmn = new Array();';

                $retval .= 'lmn["idRegistro"] = ' .$key++. ';';
                $retval .= 'lmn["idProyecto"] = ' . $mnp->idProyecto . ';';
                $retval .= 'lmn["idMetaNacional"] = ' . $mnp->idMetaNacional . ';';
                $retval .= 'lmn["idPoliticaNacional"] = ' . $mnp->idPoliticaNacional . ';';
                $retval .= 'lmn["idObjNacional"] = ' . $mnp->idObjNacional . ';';
                $retval .= 'lmn["published"] = 1;';

                $retval .= 'lstAlineacionProyecto.push( lmn );';
            }
        }

        //  lista de objetivos del proyecto
        if( $this->lstObjetivos ) {
            foreach( $this->lstObjetivos as $objetivo ) {
                $retval .= 'var objObjetivo = new Objetivo();';
                $retval .= 'objObjetivo.setDtaObjetivo( ' . json_encode( $objetivo ) . ' );';
                $retval .= 'lstObjetivos.push( objObjetivo );';
            }
        }
        
        return $retval;
    }
    
    
    private function _getLstArchivos()
    {
        $retval = '';
        $retval .='var lstImagenes = new Array();';

        if( count( $this->lstImagenes ) > 0 ) {
            foreach( $this->lstImagenes AS $key=>$imagen ) {
                $retval.='var imagen = new Array();';
                $retval.='imagen["nameArchivo"] ="'.$imagen["nameArchivo"].'";';
                $retval.='imagen["regArchivo"]  ="'.$imagen["regArchivo"].'";';
                $retval.='imagen["published"]   ="'.$imagen["published"].'";';
                $retval.='lstImagenes.push(imagen);';
            }
        }
        return $retval;
    }
    
    private function _getLstObjetivos()
    {
        //  Lista de Objetivos Especificos
        $retval .= 'var objLstObjetivo = new GestionObjetivos();';

        //  lista de objetivos del proyecto
        if( $this->lstObjetivos ) {
            foreach( $this->lstObjetivos as $objetivo ) {
                $retval .= 'var objObjetivo = new Objetivo();';
                $retval .= 'objObjetivo.setDtaObjetivo( ' . json_encode( $objetivo ) . ' );';
                $retval .= 'objLstObjetivo.addObjetivo( objObjetivo );';
            }
        }
        return $retval;
    }

}