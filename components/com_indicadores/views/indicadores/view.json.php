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
class IndicadoresViewIndicadores extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdIndicadores = $this->getModel();

        switch (true) {
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getLstEntidad' ):
                $retval = $mdIndicadores->getLstEntidad( JRequest::getVar( 'idTpoEntidad' ) );
            break;
            
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getUnidadMedida' ):
                $retval = $mdIndicadores->getUnidadMedida( JRequest::getVar( 'idTpoUM' ) );
            break;
            
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getIndicadores' ):
                $retval = $mdIndicadores->getIndicadores( JRequest::getVar( 'idEntidad' ), JRequest::getVar( 'idUM' ) );
            break;
            
            //  Retorna una lista de Funcionarios Asociados a una determinada Unidad de Gestion
            case( $action == 'getFuncionariosResponsables' ):
                $retval = $mdIndicadores->getFuncionariosResponsables( JRequest::getVar( 'idUndGestion' ) );
            break;
                
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getResponsablesIndicador' ):
                $retval = $mdIndicadores->getResponsablesIndicador( JRequest::getVar( 'idEntidad' ), JRequest::getVar( 'idIndicador' ) );
            break;
        
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getResponsablesVariable' ):
                $retval = $mdIndicadores->getResponsablesVariable( JRequest::getVar( 'idUndGestion' ) );
            break;
        
            //  Retorna informacion de indicadores de tipo plantilla
            case( $action == 'getDtaPlantilla' ):
                $retval = $mdIndicadores->getDtaPlantilla( JRequest::getVar( 'idPlantilla' ) );
            break;
        
            //  Retorna informacion de dimensiones asociadas a un determinada enfoque
            case ( $action == 'getDimensiones' ):
                $retval = $mdIndicadores->getDimensiones( JRequest::getVar( 'idEnfoque' ) );
            break;
        
            //  Retorna informacion de indicadores asociadas a un determinada entidad
            case ( $action == 'getLstIndicadoresPorEntidad' ):
                $retval = $mdIndicadores->getLstIndicadoresPorEntidad( JRequest::getVar( 'idEntidad' ) );
            break;
        
            //  Retorna informacion de indicadores asociadas a un determinada entidad
            case ( $action == 'cierreIndicador' ):
                $retval = $mdIndicadores->cierreIndicador( JRequest::getVar( 'idIndEntidad' ), JRequest::getVar( 'idIndicador' ) );
            break;
        }

        echo json_encode( $retval );
    }
}

