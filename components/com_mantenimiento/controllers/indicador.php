<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );

/**
 * 
 *  Controlador linea base
 * 
 */
class MantenimientoControllerIndicador extends JControllerForm
{

    protected $view_list = 'indicadores';

    protected function allowAdd( $data = array () )
    {
        return true;
    }

    protected function allowEdit( $data = array (), $key = 'id' )
    {
        return true;
    }
    
    
    public function gestionIndicadorPlantilla()
    {
        $dtaGralIndicador   = json_decode( JRequest::getVar( 'dtaIndicador' ) );
        $dtaVariables       = json_decode( JRequest::getVar( 'dtaVariables' ) );
        
        $mdIndicador = $this->getModel();
        
        echo $mdIndicador->gestionIndicadorPlantilla( $dtaGralIndicador, $dtaVariables );
        
        exit;
    }
            
    
    
    public function cancel()
    {
        parent::cancel();
    }

}
