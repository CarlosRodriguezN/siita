<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

/**
 * Vista de Ingreso /Edicion de un Programa
 */
class adminmapasViewEcoraeMapa extends JView
{

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        switch( true ){
            case( $action == 'save' ):
                $dataFormulario = JRequest::getVar('jDataFormEcoMap');
                $dtaLayers      = JRequest::getVar('dtaLayers');

                $modelEcoraeMapa = $this->getModel();

                //  Guardamos la data general del Mapa
                $idEcoraeMapa = $modelEcoraeMapa->saveEcoraeMapaDataForm( $dataFormulario );
                
                //  Registro informacion de capas del mapa
                $modelEcoraeMapa->saveLayersMapa( $idEcoraeMapa, $dtaLayers );

                //  Obtengo el Id de registro del programa
                echo $idEcoraeMapa;
        }
    }

}
