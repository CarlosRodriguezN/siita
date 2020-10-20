<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador mapa
 * 
 */
class adminmapasControllerMapa extends JControllerForm
{

    protected $view_list = 'Mapas';

    protected function allowAdd($data = array())
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

    function save()
    {
        $info = JRequest::getVar('jform');

        //  Accedo al modelo del componente com_programa
        $modelo = $this->getModel();
        if( $modelo->saveWMSFromJSON($info) ){
            // Redirect to the list screen.
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
        }

        return true;
    }

    /**
     * función propia que joomla
     * @param type $key
     */
    function cancel($key = null)
    {
        parent::cancel($key);
    }

    function addEcorae()
    {
        parent::add();
    }

    function delete()
    {
        $ltsMapas = JRequest::getVar('cid');
        exit();
        if( count($ltsMapas) > 0 ){
            foreach( $ltsMapas AS $mapa ){
                $wmsMapaModel = $this->getModel();
                if( $wmsMapaModel->deleteWMS($mapa) ){
                    $this->setRedirect(
                            JRoute::_(
                                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                                    . $this->getRedirectToListAppend(), false
                            )
                    );
                }
            }
        }
    }

}
