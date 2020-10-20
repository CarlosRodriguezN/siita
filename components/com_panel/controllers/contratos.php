<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * 
 *  Controlador Unidad de Gestion
 * 
 */
class PanelControllerContratos extends JControllerForm
{
    protected $view_list = 'contratos';

    /**
     * 
     * Redirecciono al componente de Gestion de Proyectos
     * y muestro su lista
     * 
     */
    public function lista()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_contratos', false ) );
    }
}