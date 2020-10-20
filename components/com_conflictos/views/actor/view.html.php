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
class ConflictosViewActor extends JView
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
        $actor = $this->get( 'Actor' );
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        //  Controla que el registro este eliminado de manera logica
        if ( $item->published == 0 && $item->intId_act != 0  ){
            JError::raiseError( 500, implode( '<br />', array( 0=>"Pagina no existe" ) ) );
            return false;
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
        $this->actor = $actor;
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
        if( $this->canDo->get( 'core.edit' ) || $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'BTN_GUARDAR' ), 'actor.save', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'BTN_GUARDAR_SALIR' ), 'actor.saveExit', false );
        }
        
        if( $this->availableDelete() ){
            $bar->appendButton( 'Standard', 'delete', JText::_( 'BTN_ELIMINAR' ), 'actor.delete', false );
        }
        
        $bar->appendButton( 'Separator' );

        $bar->appendButton( 'Standard', 'list', JText::_( 'BTN_LISTAR_ACTORES' ), 'actor.list', false );
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'BTN_CANCELAR' ), 'actor.cancel', false );
        
        //generate the html and return
        return $bar->render();
    }

    /**
     *  Verifica que el registro se puede eliminar
     * 
     * @return boolean
     */
    public function availableDelete() {
        $model = $this->getModel();
        $reg = (int)$this->item->intId_act;
        $result = false;
        switch (TRUE) {
            case ( $reg == 0 ):
                $result = false;
                break;
            case ( $reg > 0 && $model->validoEliminar( $reg ) && $this->canDo->get( 'core.delete' )):
                $result = true;
                break;
        }
        
        return $result;
    }
    
    /**
     * Metodo para establecer las propiedades del documento
     *
     * @return void
     */
    protected function setDocument()
    {
        $isNew = ($this->item->intId_act > 0);
        $document = JFactory::getDocument();

        $document->setTitle( $isNew ? JText::_( 'COM_CONFLICTOS_ACTOR_CREATING' ) : JText::_( 'COM_CONFLICTOS_ACTOR_EDITING' )  );

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

        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  hoja de estilos para los formularios
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );
        
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Adjunto script JQuery sobre los eventos
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root() . 'media/system/js/jquery.tablesorter.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );
        
        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );

        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );

        // adjunto la clase java script de un actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/common.js' );

        // adjunto la clase java script de un actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/ajaxCalls.js' );

        // adjunto la clase java script de un actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/Actor.js' );

        // adjunto la clase java script de un incidencia de una actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/IncidenciaActor.js' );
        
        // adjunto la clase java script de un incidencia de una actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/FuncionActor.js' );

        // adjunto la clase java script de un incidencia de una actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/LegitimidadActor.js' );

        // adjunto la clase java script de un conmponente comunes
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/common.js' );

        // adjunto la clase java script de un reglas 
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/ruler.js' );

        // adjunto la clase java script de un almacenamiento
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/save.js' );

        // adjunto la clase java script de un actor
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/UnidadTerritorial.js' );
        
        // adjunto la clase java script para la gestion de Incidencia
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/GestionIncidencia.js' );
        
        // adjunto la clase java script para la gestion de Legitimidad
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/GestionLegitimidad.js' );

        // adjunto la clase java script para la gestion de Legitimidad
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actor/assets/GestionFuncion.js' );

        //  Adjunto lista de Arreglos de Sub contratos
        $document->addScriptDeclaration( $this->_scriptActor(), $type = 'text/javascript' );

        JText::script( 'COM_CONFLICTOS_TEMA_ERROR_UNACCEPTABLE' );
    }

    /**
     * Arma el JavaScript de el actor 
     * @return string
     */
    private function _scriptActor()
    {
        $oActor = $this->actor;
        $script = "";
        $script .="var oActor = new cActor();";
        if( $this->item->intId_act != 0 ) {
            $actorJSON = json_encode( $oActor );
            $script .='oActor.setDataGeneral(' . $actorJSON . ');';
            if( count( $oActor->lstIncidencias ) > 0 ) {
                $lstIncidenciaJSON = json_encode( $oActor->lstIncidencias );
                $script .='oActor.setlstIncidencia(' . $lstIncidenciaJSON . ');';
            }
            if( count( $oActor->lstLegitimidad ) > 0 ) {
                $lstLegitimidadJSON = json_encode( $oActor->lstLegitimidad );
                $script .='oActor.setlstLegitimidad(' . $lstLegitimidadJSON . ');';
            }
            if( count( $oActor->lstFunciones ) > 0 ) {
                $lstFuncionesJSON = json_encode( $oActor->lstFunciones );
                $script .='oActor.setlstFunciones(' . $lstFuncionesJSON . ');';
            }
//            if( count( $oActor->unidadTerrirorial ) > 0 ) {
//                $unidadTerrirorial = json_encode( $oActor->unidadTerrirorial );
//                $script .='oActor.setUnidadTerritorial(' . $unidadTerrirorial . ');';
//            }
        }
        return $script;
    }

}