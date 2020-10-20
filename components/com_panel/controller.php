<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Template Component Controller
 *
 * @package    Template
 * @subpackage Components
 */
class PanelController extends JController
{
    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false)
    {
        $this->canDo = PanelHelper::getActions();

        switch( true ){
            case( JFactory::getUser()->authorise( 'core.admin', 'com_pei' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'peis' ) );
            break;
        
            //  Permiso de acceso a Unidad de Gestion
            case( JFactory::getUser()->authorise( 'core.admin', 'com_unidadgestion' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'unidadesgestion' ) );
            break;
        
            //  Permiso de acceso a Funcionarios
            case( JFactory::getUser()->authorise( 'core.admin', 'com_funcionarios' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'funcionarios' ) );
            break;
        
            //  Permiso de acceso a Funcionarios
            case( JFactory::getUser()->authorise( 'core.admin', 'com_funcionarios' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'funcionarios' ) );
            break;
        
            //  Permiso de acceso a Programas
            case( JFactory::getUser()->authorise( 'core.admin', 'com_programa' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'programas' ) );
            break;
        
            //  Permiso de acceso a Proyectos
            case( JFactory::getUser()->authorise( 'core.admin', 'com_proyectos' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'proyectos' ) );
            break;
        
            //  Permiso de acceso a Contratos
            case( JFactory::getUser()->authorise( 'core.admin', 'com_contratos' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'contratos' ) );
            break;
        
            //  Permiso de acceso a Convenios
            case( JFactory::getUser()->authorise( 'core.admin', 'com_contratos' ) ): 
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd( 'view', 'convenios' ) );
            break;
        }

        // call parent behavior
        parent::display($cachable);
    }
}