<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

/**
 * 
 *  Clase que permite la gestion de Peticiones Ajax
 * 
 */
class IndicadoresViewContexto extends JView
{

    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $mdContexto = $this->getModel();

        switch( true ){
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getLstEntidad' ):
                $retval = $mdContexto->getLstEntidad( JRequest::getVar( 'idTpoEntidad' ) );
            break;
            
            //  Obtengo lista de Indicadores asociados a una determinada entidad
            case ( $action == 'getIndicadoresPorEntidad' ):
                $retval = $mdContexto->getDtaIndicadoresEntidad( JRequest::getVar( 'idEntidad' ) );
            break;
        }

        echo json_encode( $retval );
    }

}