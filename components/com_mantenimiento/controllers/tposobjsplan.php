<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * HelloWorlds Controller
 */
class MantenimientoControllerTposObjsPlan extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_MANTENIMIENTO_TIPO_OBJETIVO_PLAN';
    
    public function getModel( $name = 'TpoObjPlan', $prefix = 'MantenimientoModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}