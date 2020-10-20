<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Unidad de Gestion Controller
 */

class UnidadGestionControllerUnidadesGestion extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_UNIDAD_GESTION';
    
    public function getModel( $name = 'UnidadGestion', $prefix = 'UnidadGestionModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}