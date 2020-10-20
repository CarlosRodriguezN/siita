<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Cobertura Controller
 */
class ProyectosControllerUnidadesTerritoriales extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_PROYECTOS_UNIDADTERRITORIAL';
    
    public function getModel( $name = 'UnidadTerritorial', $prefix = 'ProyectosModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}