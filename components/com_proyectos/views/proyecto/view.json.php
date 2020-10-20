<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
//  Clase de gestion de Objetivos Planificacion Operativa
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'GestionOPO.php';

/**
 * 
 *  Clase que Retorna en formato JSON, Provincias, Cantones y Parroquias 
 *  pertenecientes a una determinada Unidad Territorial
 */
class ProyectosViewProyecto extends JView
{
    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelo = $this->getModel();
        
        $action = JRequest::getVar( 'action' );
        switch( $action ){
            //  Retorna informacion de una determinada Politica Nacional
            case 'getPoliticaNacional':
                $retval = $this->get( 'LstPoliticaNacional' );
            break;
            
            //  Retorna informacion de una determinada Meta Nacional
            case 'getMetaNacional':
                $retval = $this->get( 'LstMetaNacional' );
            break;
            
            //  Retorna informacion de una determinada Unidad de Medida
            case 'getUnidadMedida':
                $retval = $this->get( 'UndMedidaTipo' );
            break;
            
            //  Retorno Otros Indicadores
            case 'getOtrosIndicadores':
                $retval = $modelo->getOtrosIndicadores( JRequest::getVar( 'idProyecto' ) );
            break;
            
            //  Lista de Cantones pertenecientes a una determinada Provincia
            case 'getCantones':
                $retval = $modelo->getCantones();
            break;

            //  Lista de Parroquias pertenecientes a un determinado canton 
            //  en una determinada provincia
            case 'getParroquias':
                $retval = $modelo->getParroquias();
            break;
        
            //  Sector
            case 'getSectores':
                $retval = $modelo->getSectores( JRequest::getVar( 'idMacroSector' ), JRequest::getVar( 'idSIV' ) );
            break;
        
            //  SubSector
            case 'getSubSector':
                $retval = $modelo->getSubSector( JRequest::getVar( 'idSector' ), JRequest::getVar( 'idSIV' ) );
            break;
        
            case 'getTiposEnfoqueIgualdad':
                $retval = $modelo->getTiposEnfoqueIgualdad( JRequest::getVar( 'idTipoEnfoque' ) );
            break; 
        
            case 'getUnidadMedidaTipo':
                $retval = $modelo->getUndMedidaTipo( JRequest::getVar( 'idTpoUM' ) );
            break; 
        
            case 'getSubProgramas':
                $retval = $modelo->getLstSubProgramas( JRequest::getVar( 'idPrograma' ) );
            break; 
        
            case 'getTiposSubProgramas':
                $retval = $modelo->getLstTiposSubProgramas( JRequest::getVar( 'idSubPrograma' ) );
            break; 
        
            case 'registroProyecto':
                $retval = $modelo->registroDataProyecto();
            break;
        
            case 'getDPA':
                $retval = $modelo->getDPA( JRequest::getVar( 'idUndTerritorial' ) );
            break;
        
            case 'getDimensiones':
                $retval = $modelo->getDimensiones( JRequest::getVar( 'idEnfoque' ) );
            break;
        
            case 'getResponsables':
                $retval = $modelo->getResponsable( JRequest::getVar( 'idUndGestion' ) );
            break;

            //  Retorna una Variables de acuerdo a un determinado Tipo de Unidad de Medida
            case 'getVariablesUnidadMedida':
                $retval = $modelo->getVarUndMedida( JRequest::getVar( 'idUM' ) );
            break;
        
            // Eliminacion por AJAX del icono
            case 'deleteIcon':
                $retval = $modelo->deleteIcon(JRequest::getVar( 'idProyecto' ));
            break;
        
            // Eliminacion por AJAX de una imagen
            case 'deleteImagen':
                $retval = $modelo->deleteImagen(JRequest::getVar( 'idProyecto' ),JRequest::getVar( 'nmbArchivo' ));
            break;
        
            //  Genera una propuesta de planificacion en funcion a la fecha de inicio y fin del proyecto
            case 'getLstPlanesOperativos':
                $opo = new GestionOPO();

                $retval = $opo->generarPlanOperativo(   JRequest::getVar( 'objetivo' ),
                                                        JRequest::getVar( 'idUGResponsable' ),
                                                        JRequest::getVar( 'idUGFuncionarioR' ),
                                                        JRequest::getVar( 'idFuncionarioR' ),
                                                        JRequest::getVar( 'fchInicioPln' ), 
                                                        JRequest::getVar( 'fchFinPln' ), 
                                                        JRequest::getVar( 'monto' ) );
            break;
        
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( 'getResponsablesUG' ):
                $retval = $modelo->getResponsablesUG( JRequest::getVar( 'idUndGestion' ) );
            break;
        }

        echo json_encode( $retval );
    }
}