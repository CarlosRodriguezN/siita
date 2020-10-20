<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Gestion del componente de Funcionarios
 */
class FuncionariosController extends JController
{
	/**
     * Method to display the view
		 * (Actually don't needed but added for academic purposes just to show
		 * that the parent display method is called)
     *
     * @access    public
     */
    function display($cachable = false) 
    {
        // set default view if not set
        JRequest::setVar( 'view', JRequest::getCmd( 'view', 'funcionarios' ) );

        // call parent behavior
        parent::display($cachable);
    }
}
