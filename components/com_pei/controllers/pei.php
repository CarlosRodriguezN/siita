<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
// Inserto libreria de gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );
/**
 * 
 *  Controlador proyecto
 * 
 */
class PeiControllerPei extends JControllerForm
{
    protected $view_list = 'peis';

    
    protected function allowAdd( $data = array() )
    {
        return true;
    }
    
    protected function allowEdit($data = array(), $key = 'id')
    {
        return true;
    }
    
    function add()
    {
        parent::add();
    }
    /**
     * Gestiona la carga de archivos.
     */
    public function saveFiles()
    {
        $idObjetivo = JRequest::getVar( "idObjetivo" );
        $idActividad= JRequest::getVar( "idActividad" );
        $idPoa      = JRequest::getVar( "idPoa" );
        $tipo       = JRequest::getVar( "tipo" );
        $flag2      = JRequest::getVar( "flag2" );
        $redirecTo  = JRequest::getVar( "redirecTo" );

        switch( $tipo ) {
            case 1:
                $dirName = 'peis';
                break;
            case 2:
                $dirName = 'poas';
                break;
            case 3:
                $dirName = 'programas';
                break;
        }

        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . $dirName . DS . $idPoa . DS . 'objetivos' . DS . $idObjetivo . DS . 'actividades' . DS . $idActividad;
        $numArchivo = count( glob( $path . '/{*.*}', GLOB_BRACE ) ) + 1;
        $fileName = $idActividad . '_' . $numArchivo;

        $up_file = new upload( 'Filedata', NULL, $path, $fileName );
        $up_file->save();
       
        $data["flag2"]=$flag2;
        $data["redirecTo"]=$redirecTo;
        
        echo json_encode( $data );
        exit();
    }
    
    
    public function panel()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel', false ) );
    }
    
    
    public function listaPeis()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_pei', false ) );
    }
    
    /**
     *  Permite cerrar secion en el sistema 
     */
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
    
    
    
    public function deletePei()
    {
        $idPei = JRequest::getVar( 'intId_pi' );
        $mdPei = $this->getModel();
        
        $mdPei->eliminarPlan( $idPei );
        
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
      
    }
    
    
}
