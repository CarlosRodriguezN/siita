<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );

/**
 * 
 *  Controlador proyecto
 * 
 */
class ConflictosControllerTema extends JControllerForm
{

    protected $view_list = 'Temas';

    protected function allowAdd( $data = array( ) )
    {
        return true;
    }

    protected function allowEdit( $data = array( ), $key = 'id' )
    {
        return true;
    }

    function add()
    {
        parent::add();
    }
    
    function cancel()
    {
        parent::cancel();
    }

    /**
     * 
     * Gestiono el registro de archivos
     * 
     * @return boolean
     */
    public function registroArchivos()
    {
        $ban = true;
        
        //  Verifico la existencia de imagenes en el formulario
        if( isset( $_FILES ) ){
            //  Accedo al modelo del de TEMA
            $modelo = $this->getModel();
            return $modelo->saveFilesTema();
        }

        return $ban;
    }
    
}