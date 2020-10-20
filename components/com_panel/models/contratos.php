<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );
// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );

jimport( 'ecorae.objetivos.objetivo.objetivos' );

//  Agrego Clase de Retorna informacion Especifica de un Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.common.TicketTableu' );

/**
 * PEI List Model
 */
class PanelModelContratos extends JModelList
{
    public function __construct( $config = array( ) )
    {
        parent::__construct( $config );
    }
    
    /**
     * 
     * Retorna una lista de Objetivos Estrategicos (OEI)
     * 
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     * 
     */
    public function lstIndicadores()
    {
        $lstObjetivos = false;
        $indicadores = new Indicadores();
        $lstIndicadores = array();

        $lstContratosVigentes = $this->_lstContratosSinPrograma();
        
        foreach( $lstContratosVigentes as $contrato ){
            $dtaIndicadores["idEntidad"]    = $contrato->idEntidad;
            $dtaIndicadores["idContrato"]   = $contrato->idContrato;
            $dtaIndicadores["nombre"]       = $contrato->nombre;
            $dtaIndicadores["lstIndicadores"] = $indicadores->getLstIndicadores( $contrato->idEntidad );
            $lstIndicadores[] = $dtaIndicadores;
        }

        return $lstIndicadores;
    }
    
    
    /**
     * 
     * Retorna una lista de programas vigentes 
     * identificados por su entidad
     * 
     * @return type
     * 
     */
    private function _lstContratosSinPrograma()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   t1.intIdentidad_ent AS idEntidad,
                            t1.intIdContrato_ctr AS idContrato,
                            t1.strDescripcion_ctr AS nombre' );
        $query->from( '#__ctr_contrato t1' );
        $query->where( 't1.intIdTipoContrato_tc = 1' );
        $query->where( 't1.intCodigo_pry IN (   SELECT t2.intCodigo_pry
                                                FROM tb_pfr_proyecto_frm t2
                                                WHERE t2.intCodigo_prg = 0 )' );
        $query->order( 't1.strDescripcion_ctr' );
        
        $db->setQuery( (string)$query );
        $db->query();
        
        $lstContratos = ( $db->getNumRows() > 0 )   ? $db->loadObjectList() 
                                                    : array();
        
        return $lstContratos;
    }
    
    /**
     * 
     * Retorna url con ticket de confianza de Tableau
     * 
     * @param String $nombreDashBoard
     * @return type
     * 
     */
    public function getTicketTableuPorNombre( $nombreDashBoard )
    {
        $ticket = new TicketTableu( JText::_( 'COM_PANEL_TABLEU_SERVER' ),
                                    JText::_( 'COM_PANEL_TABLEU_USER' ),
                                    JText::_( 'COM_PANEL_TABLEU_SITE' ),
                                    $nombreDashBoard );

        return $ticket->getUrl();
    }
    
    public function __destruct()
    {
        return;
    }
    
}