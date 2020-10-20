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
class adminmapasControllerEcoraeMapa extends JControllerForm {

    protected $view_list = 'Ecoraemapas';

    protected function allowAdd($data = array()) {
        return true;
    }

    protected function allowEdit($data = array(), $key = 'id') {
        return true;
    }

    function add() {
        parent::add();
    }

    function save() {

        $dataFormulario = JRequest::getVar('jform');
        $modelEcoraeMapa = $this->getModel();

        // guardamos la data
        $idEcoraeMapa = $modelEcoraeMapa->saveEcoraeMapaDataForm( $dataFormulario );
        
        if ($idEcoraeMapa) {
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
        }
    }

    /**
     * Se encarga de guardar la imagen o el archivo zip del shape de un mapa de ECORAE
     *   
     */
    public function saveFiles()
    {
        if( $_FILES ){
            $tipo = JRequest::getVar('fileObjName');
            $idMapEcorae = JRequest::getVar('idMapEcorae');

            $modelo = $this->getModel();
            $modelo->saveUploadFiles($idMapEcorae, $tipo);
        }

        return;
    }

    /**
     *  Elimina los datos de un shape de ecorae
     */
    public function delete() {
        $ltsMapas = JRequest::getVar('cid');
        if (count($ltsMapas) > 0) {
            foreach ($ltsMapas AS $mapa) {
                $ecoraeMapaModel = $this->getModel();
                if ($ecoraeMapaModel->deleteMapaEcorae($mapa)) {
                    if ($ecoraeMapaModel->deleteImageShapeEcorae($mapa)) {
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
    
}