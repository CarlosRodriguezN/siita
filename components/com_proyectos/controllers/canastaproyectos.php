<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Cobertura Controller
 */
class ProyectosControllerCanastaProyectos extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_PROYECTOS_COBERTURA';
    
    public function getModel( $name = 'CanastaProyecto', $prefix = 'ProyectosModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
    
    
    /**
     * 
     * Redirecciono a la Canasta de Proyectos
     * 
     * @return boolean
     */
    public function lstCanastaProyectos()
    {
        //  Redirecciono a la vista de Gestion de Variables, Planificacion y Seguimiento
        $this->setRedirect( JRoute::_(  'index.php?option=com_canastaproy ' ), false );

        return FALSE;
    }
}