<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

/**
 * 
 *  Clase que Retorna en formato JSON,  
 *  pertenecientes a una determinada Unidad Territorial
 */
class PeiViewPei extends JView
{
    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $mdPlanEI = $this->getModel();

        switch( true ){
            //  Retorna el id del pai que se guardo
            case( $action == 'guardarPei' ):
                $retval = (int) $mdPlanEI->guardarPei();
            break;

            //  Gestion la vigencia de un plan (PEI)
            case( $action == 'updVigenciaPlan' ):
                $retval = $mdPlanEI->updVigenciaPlan();
            break;

            //  Borra un Archivo
            case( $action == 'delArchivo' ):
                $retval = (int) $mdPlanEI->delArchivo();
            break;

            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getLstEntidad' ):
                $retval = $mdPlanEI->getLstEntidad( JRequest::getVar( 'idTpoEntidad' ) );
            break;

            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getUnidadMedida' ):
                $retval = $mdPlanEI->getUnidadMedida( JRequest::getVar( 'idTpoUM' ) );
            break;

            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getIndicadores' ):
                $retval = $mdPlanEI->getIndicadores( JRequest::getVar( 'idEntidad' ), JRequest::getVar( 'idUM' ) );
            break;

            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getResponsablesIndicador' ):
                $retval = $mdPlanEI->getResponsablesIndicador( JRequest::getVar( 'idEntidad' ), JRequest::getVar( 'idIndicador' ) );
            break;

            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getResponsablesVariable' ):
                $retval = $mdPlanEI->getResponsablesVariable( JRequest::getVar( 'idUndGestion' ) );
            break;

            //  Retorna informacion de indicadores de tipo plantilla
            case( $action == 'getDtaPlantilla' ):
                break;

            //  Genera el un POA por unidad de gestion
            case( $action == 'generarPoaUG' ):
                $retval = $mdPlanEI->generarPoaUG( JRequest::getVar( 'taskDate' ), JRequest::getVar( 'idPadre' ) );
            break;
        
            case( $action == 'existePlanVigente' ):
                $retval = $mdPlanEI->existePlnVigente( JRequest::getVar( 'idPln' ), JRequest::getVar( 'idTpoPln' ) );
            break;
        }

        echo json_encode( $retval );
        exit;
    }

}
