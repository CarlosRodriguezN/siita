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
class ApiRestControllerUrls extends JControllerForm
{
    protected $view_list = 'urls';
    
    public function indicadores()
    {
        $mdIndicadores = $this->getModel( 'Indicadores', 'ApiRestModel' );
        echo json_encode( $mdIndicadores->getDataIndicadores() );

        exit;
    }

    public function enfoques()
    {
        $mdEnfoque = $this->getModel( 'Indicadores', 'ApiRestModel' );
        echo json_encode( $mdEnfoque->getDataEnfoques() );

        exit;
    }
    
}