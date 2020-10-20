<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * HelloWorlds Controller
 */
class mantenimientoControllerDependencias extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_MANTENIMIENTO_DEPENDENCIA';
    
    public function getModel( $name = 'Dependencia', $prefix = 'mantenimientoModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}