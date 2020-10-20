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
class PanelModelPeis extends JModelList
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
    public function lstOEI()
    {
        $lstObjetivos = false;
        $objetivos = new Objetivos();
        
        if( $objetivos ) {
            $lstObjetivos = $objetivos->getLstObjetivos( 1, 1 );
        }
        
        return $lstObjetivos;
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

}