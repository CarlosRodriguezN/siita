<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * 
 *  Controlador Fases
 * 
 */
class ProyectosControllerPlanificacion extends JControllerForm
{
    protected $view_list = 'planificaciones';
    
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
     * 
     *  Redirecciono a la vista de Planificacion
     * 
     *  @return boolean
     *  
     */
    public function listaInd()
    {
        $idProyecto = JRequest::getVar( 'cid' );

        //  Redirecciono a la vista de Gestion de Variables, Planificacion y Seguimiento
        $this->setRedirect(
            JRoute::_(  'index.php?option=' . $this->option . '&view=' . $this->view_item
                        . $this->getRedirectToItemAppend( $idProyecto[0], 'idProyecto' ), false
            )
        );

        return FALSE;
    }
    
    
    public function regPlanificacion()
    {
        $modPlanificacion = $this->getModel();
        
        if( $modPlanificacion->registroPlanificacion() && $modPlanificacion->registroSeguimiento() ){
            $this->setRedirect(
                JRoute::_( 'index.php?option=' . $this->option . '&view=proyectos'. $this->getRedirectToListAppend(), false )
            );
        }
    }
}