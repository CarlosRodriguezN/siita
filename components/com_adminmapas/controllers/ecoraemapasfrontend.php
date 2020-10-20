<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * HelloWorlds Controller
 */
class adminmapasControllerEcoraeMapasFrontEnd extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_ADMINMAPAS_ECORAEMAPAS';
    
    public function getModel( $name = 'EcoraeMapaFrontEnd', $prefix = 'adminmapasModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );        
        return $model;
    }
}