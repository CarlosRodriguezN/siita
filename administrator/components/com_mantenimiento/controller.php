<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Controlador General del componente de Gestion de Proyectos "com_gestionproyectos"
 */
class MantenimientoController extends JController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false) 
	{
		// set default view if not set
		JRequest::setVar( 'view', JRequest::getCmd( 'view', 'dpas' ) );
 
		// call parent behavior
		parent::display($cachable);
	}
}