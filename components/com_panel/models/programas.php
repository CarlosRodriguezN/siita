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
class PanelModelProgramas extends JModelList
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

        $lstProgramasVigentes = $this->_lstProgramasVigentes();
        
        foreach( $lstProgramasVigentes as $programa ){
            $dtaIndicadores["idPrograma"]       = $programa->idPrograma;
            $dtaIndicadores["nombre"]           = $programa->nombre;
            $dtaIndicadores["idEntidad"]        = $programa->idEntidad;
            $dtaIndicadores["lstIndicadores"]   = $indicadores->getLstIndicadores( $programa->idEntidad );

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
    private function _lstProgramasVigentes()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   t1.intIdEntidad_ent AS idEntidad,
                            t1.intCodigo_prg AS idPrograma,
                            t1.strNombre_prg AS nombre' );
        $query->from( '#__pfr_programa t1');
        $query->where( 't1.intIdEntidad_ent > 0' );
        $query->where( 't1.published = 1' );
        
        $db->setQuery( (string)$query );
        $db->query();
            
        $lstProgramas = ( $db->getNumRows() > 0 )   ? $db->loadObjectList() 
                                                    : FALSE;
        
        return $lstProgramas;
    }
    
    
    public function getTicketTableuPorNombre( $nombreDashBoard )
    {
        $ticket = new TicketTableu( JText::_( 'COM_PANEL_TABLEU_SERVER' ),
                                    JText::_( 'COM_PANEL_TABLEU_USER' ),
                                    JText::_( 'COM_PANEL_TABLEU_SITE' ),
                                    $nombreDashBoard );

        return $ticket->getUrl();
    }
    
}