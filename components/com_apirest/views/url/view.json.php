<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * 
 *  Clase que Retorna en formato JSON,  
 *  pertenecientes a una determinada Unidad Territorial
 */
class ApiRestViewUrl extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $input = new JInput;
        $mdUrl = $this->getModel();
        $action = $input->get( 'action' );

        switch (true) {

            //  Retorna retorna una lista de responsables
            case( $action === 'registrarUrl' ):
                $retval = $mdUrl->registrarUrl( $input->getString( 'dataFrm' ) );
            break;

            //  Retorna retorna una lista de responsables
            case( $action === 'delDocumento' ):
                $retval = $mdUrl->delDocumento( $input->getString( 'idDocumento' ) );
            break;

            //  Retorna retorna una lista de responsables
            case( $action === 'updVigencia' ):
                $retval = $mdUrl->updVigencia( $input->getString( 'idUrl' ) );
            break;
        }

        echo json_encode($retval);
    }

}

