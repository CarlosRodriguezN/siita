<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class UnidadGestionTablePlan extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__pln_plan_institucion', 'intId_pi', $db);
    }

    public function registroPlan($dtaPlan)
    {
        
        if (!$this->save($dtaPlan)) {
            echo $this->getError();
            exit;
        }

        return $this->intId_pi;
    }
    
    /**
     *  Retorna la data de un plan segun la entidad y un anio espesifico
     * @param type $entidad
     * @param type $anio
     */
    public function getPlan( $entidad, $anio )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_pi AS id, 
                            strDescripcion_pi AS nombre");
            $query->from( "#__pln_plan_institucion" );
            $query->where( "intIdentidad_ent = {$entidad}" );
            $query->where( "dteFechainicio_pi = '{$anio}-01-01'" );
            $query->where( "published = 1" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}