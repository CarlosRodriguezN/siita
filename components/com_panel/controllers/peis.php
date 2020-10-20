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
class PanelControllerPeis extends JControllerForm
{
    protected $view_list = 'unidadesgestion';

    public function panel()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=peis', false ) );
    }
    
    /**
     * Redirecciono al componente de Gestion de Plan Estrategico Institucional - PEI
     * y muestro su lista
     */
    public function lista()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_pei', false ) );
    }
    
    
    public function cerrarSesion()
    {
        JSession::checkToken( 'request' ) or jexit( JText::_( 'JInvalid_Token' ) );

        $app = JFactory::getApplication();

        // Perform the log in.
        $error = $app->logout();

        // Check if the log out succeeded.
        if( !($error instanceof Exception) ){
            // Get the return url from the request and validate that it is internal.
            $return = JRequest::getVar( 'return', '', 'method', 'base64' );
            $return = base64_decode( $return );
            if( !JURI::isInternal( $return ) ){
                $return = '';
            }

            // Redirect the user.
            $app->redirect( JRoute::_( $return, false ) );
        } else{
            $app->redirect( JRoute::_( 'index.php?option=com_users&view=login', false ) );
        }
    }

}
