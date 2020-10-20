<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * 
 *  Clase que permite la gestion de Peticiones Ajax
 * 
 */
class IndicadoresViewIndicador extends JView
{
    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdIndicador = $this->getModel();

        switch (true) {
            //  Retorna una lista de cantones asociados a una determinada Provincia
            case( $action == 'getCantones' ):
                $retval = $mdIndicador->getCantones( JRequest::getVar( 'idProvincia' ) );
            break;
        
            //  Retorna una lista de parroquias asociados a una determinado canton
            case( $action == 'getParroquias' ):
                $retval = $mdIndicador->getParroquias( JRequest::getVar( 'idCanton' ) );
            break;
        
            //  Retorna una lista de Unidades de Medida de acuerdo a un tipo de Unidad de Medida
            case( $action == 'getUnidadMedida' ):
                $retval = $mdIndicador->getUnidadMedida( JRequest::getVar( 'idTpoUM' ) );
            break;
        
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getLstEntidad' ):
                $retval = $mdIndicador->getLstEntidad( JRequest::getVar( 'idTpoEntidad' ) );
            break;

            //  Retorna una lista de Indicadores asociados a una entidad y a un tipo de unidad de medida
            case( $action == 'getIndicadores' ):
                $retval = $mdIndicador->getIndicadores( JRequest::getVar( 'idEntidad' ), JRequest::getVar( 'idUM' ) );
            break;
            
            //  Retorna una Informacion de Unidades de Gestion y Funcionarios Responsables de un determinado Indicador
            case( $action == 'getResponsablesIndicador' ):
                $retval = $mdIndicador->getResponsablesIndicador( JRequest::getVar( 'idEntidad' ), JRequest::getVar( 'idIndicador' ) );
            break;

            //  Retorna lista de dimensiones asociadas a un determinado Enfoque
            case( $action == 'getDimensiones' ):
                $retval = $mdIndicador->getDimensiones( JRequest::getVar( 'idEnfoque' ) );
            break;
        
            //  Retorna una funcionarios responsanbles asociados a una determinada Unidad de Gestion
            case( $action == 'funcionariosResponsables' ):
                $retval = $mdIndicador->getFuncionariosResponsables( JRequest::getVar( 'idUndGestion' ) );
            break;
        
            //  Retorna informacion de indicadores por el identificador de plantilla
            case( $action == 'getDtaPlantilla' ):
                $retval = $mdIndicador->getDtaPlantillaPorId( JRequest::getVar( 'idPlantilla' ) );
            break;

            //  Obtengo informacion de indicador de acuerdo a una determinada dimension
            case ( $action == 'getIndicadorPorDimension' ): 
                $retval = $mdIndicador->getDtaPlantillaPorDimension( JRequest::getVar( 'dtaDimension' ) );
            break;        
        
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getResponsablesVariable' ):
                $retval = $mdIndicador->getResponsablesVariable( JRequest::getVar( 'idUndGestion' ) );
            break;
        
            //  Retorna una lista de Valores asociados a un determinada Linea Base
            case( $action == 'getLineasBase' ):
                $retval = $mdIndicador->getLineasBase( JRequest::getVar( 'idFuenteLB' ) );
            break;        
        
            case 'getTiposEnfoqueIgualdad':
                $retval = $mdIndicador->getTiposEnfoqueIgualdad( JRequest::getVar( 'idTipoEnfoque' ) );
            break;
        }

        echo json_encode($retval);
    }
}