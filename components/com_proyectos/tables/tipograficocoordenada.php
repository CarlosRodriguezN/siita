<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProyectosTableTipograficocoordenada extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__tg_coordenadas', 'intRel_Tg_Coordenada', $db);
    }

    /**
     * 
     * @param type $intRel_Tg_Coordenada
     * @param type $intCodigo_pry
     * @param type $intId_tg
     * @param type $strDescripcionGrafico
     */
    function saveDescription($intRel_Tg_Coordenada, $intCodigo_pry, $intId_tg, $strDescripcionGrafico) {
        
    }

}