<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Cobertura Controller
 */
class MantenimientoControllerTiposVariables extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_MANTENIMIENTO_TIPOVARIABLE';
    
    public function getModel( $name = 'TipoVariable', $prefix = 'MantenimientoModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}