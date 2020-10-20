<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );
// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'entidad' . DS . 'proyecto.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores' . DS .'indicadores.php';

/**
 * Etapas Model
 */

class ReportesModelProyectos extends JModelList
{

    public function __construct( $config = array () )
    {
        parent::__construct( $config );
    }

    public function getDtaProyecto( $idProyecto )
    {
        $proyecto = new Proyecto();
        $dtaProyecto = $proyecto->getReporteProyecto( $idProyecto );
        $dtaProyecto->indicadores = $this->_getDataIndicadoresProyecto( $dtaProyecto->idEntidad );

        return $dtaProyecto;
    }

    /**
     * 
     * Seteo Informacion de Indicadores pertenecientes a un proyecto 
     * asociado por su entidad
     * 
     * @return type
     */
    private function _getDataIndicadoresProyecto( $idEntidad )
    {
        $objDtaIndicadores = new stdClass();

        //  Instancio la Clase Indicadores
        $objIndicadores = new Indicadores();
        $dtaIndicadores = $objIndicadores->getLstIndicadores( $idEntidad );

        //  Seteo informacion de Indicadores en el Formulario
        if( count( $dtaIndicadores ) ){

            foreach( $dtaIndicadores as $indicador ){
                //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                $indicador->tpoIndicador                = $indicador->alias;

                $dtoIndicador["idIndEntidad"]           = $indicador->idIndEntidad;
                $dtoIndicador["idIndicador"]            = $indicador->idIndicador;
                
                $dtoIndicador["nombreIndicador"]        = ( strlen( $indicador->nombreIndicador ) > 0 )
                                                            ? $indicador->nombreIndicador
                                                            : JText::_( 'COM_REPORTES_NO_APLICA' );

                $dtoIndicador["modeloIndicador"]        = $indicador->modeloIndicador;
                $dtoIndicador["umbral"]                 = $this->_getValorUmbral( $indicador );
                $dtoIndicador["tendencia"]              = (int) $indicador->tendencia;
                
                $dtoIndicador["descripcion"]            = ( strlen( $indicador->descripcion ) > 0 )
                                                                ? $indicador->descripcion
                                                                : JText::_( 'COM_REPORTES_NO_APLICA' );
                
                $dtoIndicador["idUndAnalisis"]          = (int) $indicador->idUndAnalisis;
                $dtoIndicador["undAnalisis"]            = $indicador->undAnalisis;
                $dtoIndicador["idTpoUndMedida"]         = (int) $indicador->idTpoUndMedida;
                $dtoIndicador["idUndMedida"]            = (int) $indicador->idUndMedida;
                $dtoIndicador["undMedida"]              = $indicador->undMedida;
                $dtoIndicador["simbolo"]                = $indicador->simbolo;
                $dtoIndicador["idTpoIndicador"]         = $indicador->idTpoIndicador;
                $dtoIndicador["formula"]                = $indicador->formula;
                $dtoIndicador["fchHorzMimimo"]          = $indicador->fchHorzMimimo;
                $dtoIndicador["fchHorzMaximo"]          = $indicador->fchHorzMaximo;
                $dtoIndicador["umbMinimo"]              = $indicador->umbMinimo;
                $dtoIndicador["idHorizonte"]            = $indicador->idHorizonte;
                $dtoIndicador["idClaseIndicador"]       = $indicador->idClaseIndicador;
                $dtoIndicador["idFrcMonitoreo"]         = $indicador->idFrcMonitoreo;
                $dtoIndicador["frecuenciaMonitoreo"]    = $indicador->frecuenciaMonitoreo;
                $dtoIndicador["idUGResponsable"]        = $indicador->idUGResponsable;
                $dtoIndicador["idResponsableUG"]        = $indicador->idResponsableUG;
                $dtoIndicador["fchInicioUG"]            = $indicador->fchInicioUG;
                $dtoIndicador["idResponsable"]          = $indicador->idResponsable;
                $dtoIndicador["fchInicioFuncionario"]   = $indicador->fchInicioFR;
                $dtoIndicador["idDimension"]            = $indicador->idDimension;
                $dtoIndicador["idEnfoque"]              = $indicador->idEnfoque;
                $dtoIndicador["enfoque"]                = $indicador->enfoque;
                $dtoIndicador["idCategoria"]            = $indicador->categoriaInd;
                $dtoIndicador["idDimension"]            = $indicador->idDimension;
                $dtoIndicador["idDimIndicador"]         = $indicador->idDimIndicador;

                //  Informacion complementaria
                $dtoIndicador["metodologia"]            = $indicador->metodologia;
                $dtoIndicador["limitaciones"]           = $indicador->limitaciones;
                $dtoIndicador["interpretacion"]         = $indicador->interpretacion;
                $dtoIndicador["disponibilidad"]         = $indicador->disponibilidad;
                $dtoIndicador["fechaElaboracion"]       = $indicador->fechaElaboracion;
                $dtoIndicador["fechaModificacion"]      = $indicador->fechaModificacion;

                $dtoIndicador["lstUndTerritorial"]      = $this->_getDtaUT( $objIndicadores->getLstIndUndTerritorial( $indicador->idIndEntidad ) );
                $dtoIndicador["lstLineaBase"]           = $objIndicadores->getLstLineasBase( $indicador->idIndicador );
                $dtoIndicador["lstRangos"]              = $objIndicadores->getLstRangos( $indicador->idIndEntidad );
                $dtoIndicador["lstVariables"]           = $objIndicadores->getLstVariables( $indicador->idIndicador );
                $dtoIndicador["lstDimensiones"]         = $this->_getDtaDimensiones( $objIndicadores->getLstDimensiones( $indicador->idIndicador ) );
                $dtoIndicador["lstPlanificacion"]       = $objIndicadores->getLstPlanificacion( $indicador->idIndEntidad );
                $dtoIndicador["published"]              = 1;

                switch( true ){
                    //  Armo informacion de indicadores Fijos - Economicos ( 1 )
                    case( $indicador->categoriaInd == 1 ):
                        $lstIndEconomicos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Financieros ( 2 )
                    case( $indicador->categoriaInd == 2 ):
                        $lstIndFinancieros[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Beneficiarios Directos ( 1 )
                    case( $indicador->categoriaInd == 3 ):
                        $lstBDirectos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Beneficiarios Indirectos ( 1 )
                    case( $indicador->categoriaInd == 4 ):
                        $lstBIndirectos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - GAP ( 2 )
                    case( $indicador->categoriaInd == 5 ):
                        $lstTmpGAP[] = $dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - Enfoque de Igualdad ( 2 )
                    case( $indicador->categoriaInd == 6 ):                        
                        $lstTmpEI[] = $dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - Enfoque de Ecorae ( 2 )
                    case( $indicador->categoriaInd == 7 ):
                        $lstTmpEE[] = $dtoIndicador;
                    break;
                }
            }

            $objDtaIndicadores->lstIndEconomicos   = $lstIndEconomicos;
            $objDtaIndicadores->lstIndFinancieros  = $lstIndFinancieros;
            $objDtaIndicadores->lstBDirectos       = $lstBDirectos;
            $objDtaIndicadores->lstBIndirectos     = $lstBIndirectos;
            
            //  Seteo indicadores de tipo GAP
            $objDtaIndicadores->lstGAP = ( count( $lstTmpGAP ) )
                                            ? $objIndicadores->getLstGAP( $lstTmpGAP ) 
                                            : array();

            //  Seteo indicadores de tipo Enfoque de Igualdad
            $objDtaIndicadores->lstEnfIgualdad = ( count( $lstTmpEI ) ) 
                                                    ? $objIndicadores->getLstEI( $lstTmpEI ) 
                                                    : array();

            //  Seteo indicadores de tipo Enfoque de ECORAE
            $objDtaIndicadores->lstEnfEcorae = ( count( $lstTmpEE ) )   
                                                    ? $objIndicadores->getLstEE( $lstTmpEE ) 
                                                    : array();
        }

        //  echo json_encode( $objDtaIndicadores ); exit;

        return $objDtaIndicadores;
    }

    
    private function _getValorUmbral( $indicador )
    {
        $retval = '';
        
        switch( true ){
            //  Moneda
            case( (int)$indicador->idUndMedida == 17 ):
                $retval = "$ ". number_format( $indicador->umbral, 2, ".", "," ) . $indicador->simbolo;
            break;

            //  Unidad
            case( in_array( (int)$indicador->idUndMedida, array( 1, 2, 3, 4, 5, 6, 7 ) ) ):
                $retval = (int)$indicador->umbral . ' ' . $indicador->simbolo;
            break;
        
            default:
                $retval = number_format( $indicador->idUndMedida, 2, ".", "," ) . ' ' . $indicador->simbolo;
            break;
        }

        return $retval;
    }
    
    
    
    private function _getDtaUT( $lstIndUndTerritorial )
    {
        $retval = '';
        
        if( count( $lstIndUndTerritorial ) ){
            foreach( $lstIndUndTerritorial as $ut ){
                $retval .= $ut->provincia .', '. $ut->canton . ', ' . $ut->parroquia .'<br>';
            }
        }
        
        return $retval;
    }
    
    
    
    private function _getDtaDimensiones( $lstDimensiones )
    {
        $retval = '';
        
        if( count( $lstDimensiones ) ){
            foreach( $lstDimensiones as $dimension ){
                $retval .= $dimension->dimension .'<br>';
            }
        }
        
        return $retval;
    }
            
    
    
}
