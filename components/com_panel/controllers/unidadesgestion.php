<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

// Inserto libreria de gestion de carga de archivos
jimport('ecorae.uploadfile.upload');

/**
 * 
 *  Controlador proyecto
 * 
 */
class PanelControllerUnidadesGestion extends JControllerForm
{

    protected $view_list = 'UnidadesGestion';

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

    /**
     *  Redirecciona al componente Unidad de Gestion
     */
    public function lista()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_unidadgestion', false ) );
    }
}
