<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso /Edicion de un Programa
 */
class ProgramaViewPrograma extends JView
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
        $subProgramas = $this->get( "SubProgramas" );

        // What Access Permissions does this user have? What can (s)he do?
        $this->canDo = ProgramaHelper::getActions();
        
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->idEntidad = $item->intIdEntidad_ent;
        $this->script = $script;
        $this->subProgramas = $subProgramas;

        if ( $this->item->published == 0 && $this->item->intCodigo_prg != 0  ){
            // Set the internal error and also the redirect error.
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect( JRoute::_( 'index.php?option=com_programa' ) );
        }
        
        $mdPrograma = $this->getModel();
        //$this->items = $mdPrograma->lstIndicadores( JRequest::getVar( 'intCodigo_prg' ) );
        //  Listo todos los proyectos asociados a este programa
        $idPrograma = JRequest::getVar( 'intCodigo_prg' );

        if( isset( $idPrograma ) ){
            $this->lstProyectos = $mdPrograma->lstProyectosPrg( $idPrograma );

            //  Listo todos los contratos asociados a este programa
            $this->lstContratos = $mdPrograma->lstContratosPrg( $idPrograma );

            //  Listo todos los convenios asociados a este programa
            $this->lstConvenios = $mdPrograma->lstConveniosPrg( $idPrograma );

            //  Obtengo una lista de ojetivos del programa
            $this->lstObjetivos = $mdPrograma->getLstObjetivos( $this->idEntidad );

            //  Obtengo los ids del articulo asociado
            $this->articlePrg = $this->_getArticlePrg();
        }

        //  Obtengo los archivos imagen icono y logo del programa
        if( $this->item->intCodigo_prg != null ){

            $path = JPATH_BASE . DS . 'images' . DS . 'stories' . DS . 'programa';

            //  Imagen
            $imgPrg = JPATH_BASE . DS . 'cache' . DS . 'lofthumbs'. DS .'708x248-' . $this->item->intCodigo_prg;
            $img = $this->verificarFile( $imgPrg );
            $this->imagenPrg = ( $img != 0 )? JURI::root() . 'cache' . DS . 'lofthumbs' . DS . '708x248-' . $img
                                            : JURI::root() . 'images' . DS . 'sinimagen.jpg';

            //  Logo
            $logoPrg = $path . DS . 'logo' . DS . $this->item->intCodigo_prg;
            $logo = $this->verificarFile( $logoPrg );
            $this->logoPrg = ( $logo != 0 ) ? JURI::root() . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'logo' . DS . $logo
                                            : JURI::root() . 'images' . DS . 'sinimagen.jpg';

            //  Icono
            $iconoPrg = $path . DS . 'icono' . DS . $this->item->intCodigo_prg;
            $icono = $this->verificarFile( $iconoPrg );
            $this->iconoPrg = ( $icono != 0 )   ? JURI::root() . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'icono' . DS . $icono
                                                : JURI::root() . 'images' . DS . 'sinimagen.jpg';
        }

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
        
    }

    public function verificarFile( $file )
    {        
        switch( true ){
            case ( file_exists( $file . ".png" ) ):
                $result = $this->item->intCodigo_prg . ".png";
            break;

            case ( file_exists( $file . ".jpeg" ) ):
                $result = $this->item->intCodigo_prg . ".jpeg";
            break;
        
            case ( file_exists( $file . ".jpg" ) ):
                $result = $this->item->intCodigo_prg . ".jpg";
            break;
        
            case ( file_exists( $file . ".gif" ) ):
                $result = $this->item->intCodigo_prg . ".gif";
            break;
        
            default :
                $result = 0;
            break;
        }

        return $result;
    }

    /**
     * Arma el objeto Programa
     * @return string
     */
    public function arrayPrograma()
    {
        $idEntidad = ($this->item->intIdEntidad_ent) ? $this->item->intIdEntidad_ent : 0;

        $idMenu = ($this->item->idMenu) ? $this->item->idMenu : 0;

        $arrayProgramas = 'data = new Object();';
        $arrayProgramas.='data.idEntidad = ' . $idEntidad . ';';
        $arrayProgramas.='data.idMenu = ' . $idMenu . ';';
        $arrayProgramas.='data.lstSubProgramas = lstSubProgramas;';
        $arrayProgramas.='var oPrograma = new Programa(data);';
        return $arrayProgramas;
    }

    /**
     * Array de Sub Programas
     * @return string
     */
    public function arraySubProgramas()
    {
        $subProgramas = $this->subProgramas;
        $scriptSubProgramas = "var lstSubProgramas = new Array();";
        if( $subProgramas ){
            foreach( $subProgramas AS $subPrograma ){
                $JSONPrograma = json_encode( $subPrograma );
                $scriptSubProgramas.='var oSubPrograma = new Subprograma(' . $JSONPrograma . ');';
                $scriptSubProgramas.='oSubPrograma.regSubPrograma = lstSubProgramas.length + 1;';
                if( $subPrograma->lstTiposSubPrograma ){
                    foreach( $subPrograma->lstTiposSubPrograma AS $tipoSubProgramas ){
                        $JSONTipoSubPrograma = json_encode( $tipoSubProgramas );
                        $scriptSubProgramas.='var oTipoSubPrograma = new Tiposubprograma(' . $JSONTipoSubPrograma . ');';
                        $scriptSubProgramas.='oTipoSubPrograma.regTipoSubPrograma = oSubPrograma.lstTiposSubPrograma.length + 1 ;';
                        $scriptSubProgramas.='oSubPrograma.lstTiposSubPrograma.push( oTipoSubPrograma );';
                    }
                }
                $scriptSubProgramas.='lstSubProgramas.push( oSubPrograma );';
            }
        }
        return $scriptSubProgramas;
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        $input = JFactory::getApplication()->input;
        $input->set( 'hidemainmenu', true );

        //  Si tiene permiso para editar, se abilita la opcion para guardar
        if( $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_PROGRAMA_EVENTO_GUARDAR' ), 'programa.save', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_PROGRAMA_EVENTO_GUARDAR_SALIR' ), 'programa.saveSalir', false );
        }
        
        
        if ( $this->canDo->get( 'core.delete' ) && $this->_availibleDelete() ) {
            $bar->appendButton( 'Standard', 'delete', JText::_( 'COM_PROGRAMA_EVENTO_ELIMINAR' ), 'programa.delete', false );
        }
        
        $bar->appendButton( 'Separator' );
        
        if( $this->canDo->get( 'core.edit' ) && $this->canDo->get( 'core.create' ) && $this->canDo->get( 'core.delete' )){
            $bar->appendButton( 'Standard', 'list', JText::_( 'COM_PROGRAMA_EVENTO_LISTAR' ), 'programa.list', false );
        }
        
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_PROGRAMA_REPORTE_CANCELAR' ), 'programa.cancel', false );
        
        //  generate the html and return
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
        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadfive/uploadifive.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css' );

        //  hoja de estilos para los formularios
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );

        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );
        
        //  PlugIn jQuery uploadify
        $document->addScript( JURI::root() . 'media/system/js/uploadify/swfobject.js' );

        //  Adjunto script JQuery sobre los eventos
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root() . 'media/system/js/jquery.tablesorter.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo carga de imagenes
        $document->addScript( JURI::root() . 'media/system/js/uploadfive/jquery.uploadifive.min.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

        //  Adjunto libreria para mostrar la ventana pop pop de guardado.
        $document->addScript(JURI::root() . 'media/system/js/FormatoNumeros.js');
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );
        
        //  Adjunto librerias para la validacion de formularios
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );
        
        //  INDICADORES
        
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

        $document->addScript(JURI::root() . 'components/com_indicadores/views/indicador/assets/GestionIndicadorMeta.js');
        //  $document->addScript(JURI::root() . 'components/com_indicadores/views/indicador/assets/GestionObjetivoIndicador.js');

        //  Adjunto libreria que gestiona el lenguaje para el java script
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/Common.js' );
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/Vinculantes.js' );

        //  Adjunto libreria que la gestion de Objetivos
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/GestionObjetivo.js' );
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/GestionObjetivos.js' );
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/Objetivo.js' );
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/Objetivos.js' );
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/ES_OBJETIVO.js' );

        //  Adjunto libreria que gestiona las reglas
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/ruler.js' );

        //  Adjunto libreria que gestiona las reglas
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/tiposSubPrograma.js' );

        //  Adjunto libreria que gestiona las reglas
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/subprograma.js' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'components/com_programa/views/programa/assets/uploadfile.js' );
        
        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );

        //  Adjunto lista de Arreglos de Sub programas
        $document->addScriptDeclaration( $this->arraySubProgramas(), $type = 'text/javascript' );

        //  Adjunto lista de Arreglos de Sub programas
        $document->addScriptDeclaration( $this->arrayPrograma(), $type = 'text/javascript' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto lista de objetivos
        $document->addScriptDeclaration( $this->_getLstObjetivos(), $type = 'text/javascript' );

        JText::script( 'COM_PROGRAMA_COBERTURA_ERROR_UNACCEPTABLE' );
    }

    /**
     *  Retorna TRUE en el caso que se pueda ELIMINAR un registro si no retorna FALSE 
     * @return boolean
     */
    private function _availibleDelete()
    {
        $reslut = true;
        
        if( count( $this->lstObjetivos ) != 0 || 
                count( $this->lstProyectos ) != 0 || 
                count( $this->lstContratos ) != 0 || 
                count( $this->lstConvenios ) != 0 || 
                count( $this->subProgramas ) != 0 ||
                $this->item->intCodigo_prg == 0){
            $reslut = false;
        }
        return $reslut;
    }
    
    /**
     * 
     * @return string
     */
    private function _getLstObjetivos()
    {
        //  Lista de Objetivos Especificos
        $retval = '';
        $retval .= 'var objLstObjetivo = new GestionObjetivos();';

        //  lista de objetivos del proyecto
        if( $this->lstObjetivos ){
            foreach( $this->lstObjetivos as $objetivo ){
                $retval .= 'var objObjetivo = new Objetivo();';
                $retval .= 'objObjetivo.setDtaObjetivo( ' . json_encode( $objetivo ) . ' );';
                $retval .= 'objLstObjetivo.addObjetivo( objObjetivo );';
            }
        }
        return $retval;
    }

    /**
     *  Arma el json que gestiona el articulo para el programa
     * @return type
     */
    private function _getArticlePrg()
    {
        $article = new stdClass();
        $article->idMenu = ($this->item->idMenu) ? $this->item->idMenu : 0;
        $article->idAssets = ($this->item->idAssets) ? $this->item->idAssets : 0;
        $article->idContent = ($this->item->idContent) ? $this->item->idContent : 0;
        return base64_encode(json_encode($article));
    }
    
}
