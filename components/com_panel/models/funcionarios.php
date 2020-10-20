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
class PanelModelFuncionarios extends JModelList
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
        $lstProyectosVigentes = $this->_lstProyectosSinPrograma();
        
        foreach( $lstProyectosVigentes as $proyecto ){

            $dtaIndicadores["idEntidad"]        = $proyecto->idEntidad;
            $dtaIndicadores["idProyecto"]       = $proyecto->idProyecto;
            $dtaIndicadores["nombre"]           = $proyecto->nombre;
            $dtaIndicadores["lstIndicadores"]   = $indicadores->getLstIndicadores( $proyecto->idEntidad );

            $lstIndicadores[]                   = $dtaIndicadores;

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
    private function _lstProyectosSinPrograma()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   t1.intIdEntidad_ent AS idEntidad, 
                            t1.intCodigo_pry AS idProyecto,
                            t1.strNombre_pry AS nombre' );
        $query->from( '#__pfr_proyecto_frm t1');
        $query->where( 't1.intCodigo_prg = 0' );
        $query->where( 't1.published = 1' );
        $query->order( 't1.strNombre_pry' );
        
        $db->setQuery( (string)$query );
        $db->query();
            
        $lstProyectos = ( $db->getNumRows() > 0 )   ? $db->loadObjectList() 
                                                    : FALSE;
        
        return $lstProyectos;
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