<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador Fases
 * 
 */
class ApiRestControllerUrl extends JControllerForm
{
    protected $view_list = 'urls';

    protected function allowAdd($data = array())
    {
        return true;
    }

    protected function allowEdit($data = array(), $key = 'id')
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

    
    function registar()
    {
        $mdUrl = $this->getModel( 'Url', 'ApiRestModel' );
        $mdUrl->registrarUrl();
        
        // Redirect to the edit screen.
        $this->setRedirect( JRoute::_( 'index.php?option=' . $this->option, false ) );

    }
    
    
    function saveFiles() {
        $ban = false;
        $input  = new JInput;
        $idUrl  = $input->getInt( 'idUrl' );
        
        $dtaFile= new JInputFiles;
        if( $dtaFile->get( 'Filedata' ) ) {
            $mdUrl = $this->getModel( 'Url', 'ApiRestModel' );
            $ban = $mdUrl->saveUploadFiles( $idUrl );
        }

        return $ban;
    }

}
