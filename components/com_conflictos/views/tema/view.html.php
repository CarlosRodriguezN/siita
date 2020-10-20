<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );
//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista Categoria
 */
class ConflictosViewTema extends JView
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
        $tema = $this->get( 'Tema' );

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        if ( $item->published == 0 && $item->intId_tma != 0  ){
            // Set the internal error and also the redirect error.
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect( JRoute::_( 'index.php?option=com_conflictos' ) );
        }
        
        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = ConflictosHelper::getActions();
        
        //  Pregunta si es el administrados para la gestion de campos clave
        $user = JFactory::getUser();
        $this->admin = ( in_array( 7, $user->groups ) || in_array( 8, $user->groups )) ? true : false;
        
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        $this->tema = $tema;
        
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
        if( $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'BTN_GUARDAR' ), 'tema.save', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'BTN_GUARDAR_SALIR' ), 'tema.saveExit', false );
        }

        
        if( $this->canDo->get( 'core.delete' ) && $this->item->intId_tma != 0 && $this->_avalibleDel() ){
            $bar->appendButton( 'Standard', 'delete', JText::_( 'BTN_ELIMINAR' ), 'tema.delete', false );
        }
        
        $bar->appendButton( 'Separator' );

        $bar->appendButton( 'Standard', 'list', JText::_( 'BTN_LISTAR_CONFLICTOS' ), 'tema.list', false );
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'BTN_CANCELAR' ), 'tema.cancel', false );

        //generate the html and return
        return $bar->render();
    }

    /**
     * Metodo para establecer las propiedades del documento
     *
     * @return void
     */
    protected function setDocument()
    {
        $isNew = ($this->item->intId_tma < 1);
        $document = JFactory::getDocument();

        $document->setTitle( $isNew ? JText::_( 'COM_CONFLICTOS_TEMA_CREATING' ) : JText::_( 'COM_CONFLICTOS_TEMA_EDITING' )  );

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
                '<![endif]-->' . "\n" .
                '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
        );

        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadfive/uploadifive.css' );

        //  hoja de estilos para los formularios
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );

        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );
        
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );
        
        //  Adjunto script JQuery sobre los eventos
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root() . 'media/system/js/jquery.tablesorter.min.js' );

        //  Adjunto libreria java script upladifi
        $document->addScript( JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );

        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );
        
        // adjunto la clase java script de un tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/common.js' );

        // adjunto la clase java script par ala carga de archivos
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/uploadFiles.js' );

        // adjunto la clase java script de un tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/ajaxCalls.js' );

        // adjunto la clase java script de un tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/Tema.js' );

        // adjunto la clase java script de un estdo tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/EstadosTema.js' );

        // adjunto la clase java script de un estdo tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/unidadterritorial.js' );

        // adjunto la clase java script de un tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/ActorDetalle.js' );

        // adjunto la clase java script de un gestion de fuentes
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/FuenteTema.js' );

        // adjunto la clase java script que gestiona los tipos de tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/GestionTipoTema.js' );

        // adjunto la clase java script que gestiona los estados del tema
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/GestionEstado.js' );

        // adjunto las reglas.
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/ruler.js' );

        // gestion del tema usando ajax
        $document->addScript( JURI::root() . 'components/com_conflictos/views/tema/assets/save.js' );

        //  Adjunto lista de Arreglos de Sub contratos
        $document->addScriptDeclaration( $this->_scriptTema(), $type = 'text/javascript' );

        JText::script( 'COM_CONFLICTOS_TEMA_ERROR_UNACCEPTABLE' );
    }

    /**
     * 
     * @return boolean
     */
    function _avalibleDel(){
       $result = true;
       if ( count( $this->tema->lstActDeta ) > 0 ||
            count( $this->tema->lstEstTema ) > 0 ||    
            count( $this->tema->lstFetTema ) > 0 ||    
            count( $this->tema->lstUnidadesTerritoriales ) > 0 ) {
           $result = false;
       }
       return $result;
    }
    
    /**
     * Arma el JavaScript de el tema 
     * @return string
     */
    private function _scriptTema()
    {
        $oTema = $this->tema;
        $script = "";
        $script .="var oTema = new cTema();";
        if( $this->item->intId_tma != 0 ) {
            $temaJSON = json_encode( $oTema );
            $script .='oTema.setDataGeneral(' . $temaJSON . ');';
            if( count( $oTema->lstActDeta ) > 0 ) {
                $lstActores = json_encode( $oTema->lstActDeta );
                $script .='oTema.setLstActores(' . $lstActores . ');';
            }
            if( count( $oTema->lstEstTema ) > 0 ) {
                $lstEstados = json_encode( $oTema->lstEstTema );
                $script .='oTema.setLstEstados(' . $lstEstados . ');';
            }
            if( count( $oTema->lstFetTema ) > 0 ) {
                $lstFuentes = json_encode( $oTema->lstFetTema );
                $script .='oTema.setLstFuentes(' . $lstFuentes . ');';
            }
            if( count( $oTema->lstUnidadesTerritoriales ) > 0 ) {
                $lstUnidTe = json_encode( $oTema->lstUnidadesTerritoriales );
                $script .='oTema.setlstUnidadesTerritoriales(' . $lstUnidTe . ');';
            }
            if( count( $oTema->lstArchivo ) > 0 ) {
                $lstArchivos = json_encode( $oTema->lstArchivo );
                $script .='oTema.setlstArchivo(' . $lstArchivos . ');';
            }
        }
        return $script;
    }

    private function getScriptActorDetalle( $lstActores )
    {
        $script = "";
        if( $lstActores ) {
            $script.="var lstActores=new Array();";
            foreach( $lstActores AS $actor ) {
                $JSONactor = json_encode( $actor );
                $script.='var oActor= new ActorDetalle();';
                $script.='oActor.setData(' . $JSONactor . ');';
                $JSONactorArchivos = json_encode( $actor->lstArchivos );
                $script.='oActor.setLstArchivosActor(' . $JSONactorArchivos . ');';
            }
        }
        return $script;
    }

}