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
class AlineacionTablePoliticaNacional extends JTable
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct( '#__pnd_objetivo_nacional', 'intCodigo_on', $db );
    }
    
   /**
     * 
     *  Retorna una Lista de Politicas que estan bajo un determinado Objetivo Nacional
     *  
     *  @param type $idObjetivo Identificador del Objetivo Nacional
     *  @return type 
     */
    public function getLstPoliticaNacional($idObjetivo) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("pn.intcodigo_pln AS id, 
                            concat ( pn.intcodigo_on, '.', pn.intcodigo_pln, ': ', pn.strdescripcion_pln ) AS nombre");
            $query->from('#__pnd_politica_nacional AS pn');
            $query->where('pn.intcodigo_on = ' . $idObjetivo);
            $query->where('pn.published  = 1');
            $query->order('pn.intcodigo_pln');
            $db->setQuery($query);
            $db->query();

            $lstPoliticasNacionales = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lstPoliticasNacionales;
        } catch (Exception $ex) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_alineacion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function savePoliticaByObjetico($idObjetivo, $data){
        
    }    
}