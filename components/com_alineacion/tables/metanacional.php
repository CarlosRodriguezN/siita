<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__poa_plan_institucion )
 * 
 */
class AlineacionTableMetaNacional extends JTable
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct( '#__pnd_meta_nacional', 'idcodigo_mn', $db );
    }
    
     
    /**
     *  
     *  Retona una Lista de Metas Nacionales que cumplen un determinado 
     *  Objetivo y Politica Nacional 
     *  
     *  @param type $idObjetivo     Identificador del Objetivo Nacional
     *  @param type $idPlnNacional  Identificador del Plan Nacional
     * 
     *  @return type 
     */
    public function getLstMetaNacional( $idObjetivo, $idPlnNacional )
    {
        try{
            
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("idcodigo_mn as id,
                        CONCAT(  intcodigo_on, '.', intcodigo_pln, '.', intcodigo_mn, ': ', strdescripcion_mn ) as nombre");
            $query->from("#__pnd_meta_nacional pn");
            $query->where("pn.intcodigo_on = " . $idObjetivo);
            $query->where("intcodigo_pln = " . $idPlnNacional);
            $query->where("pn.published  = 1");
            $query->order("pn.intcodigo_mn");
            $db->setQuery($query);
            $db->query();

            $lsrMetasNacionales = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lsrMetasNacionales;
            
        } catch (Exception $ex) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_alineacion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}