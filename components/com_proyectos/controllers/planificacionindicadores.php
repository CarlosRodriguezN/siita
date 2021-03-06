<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Cobertura Controller
 */
class ProyectosControllerPlanificacionIndicadores extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_PROYECTOS_PLANIFICACIONINDICADORES';
    
    public function getModel( $name = 'PlanificacionIndicadores', $prefix = 'ProyectosModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}