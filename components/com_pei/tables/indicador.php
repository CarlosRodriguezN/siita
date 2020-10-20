<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */

class PeiTableIndicador extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_indicador', 'intCodigo_ind', $db );
    }
    
    public function getLstIndicadores( $idEntObj, $idUG ) 
    {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(TRUE);
            
            $query->select('ie.intIdIndEntidad');
            $query->from('#__ind_indicador_entidad ie');
            $query->innerJoin('#__ind_ug_responsable ugr ON ugr.intIdIndEntidad = ie.intIdIndEntidad');
            $query->where('ie.intIdentidad_ent = ' . $idEntObj);
            $query->where('ugr.intCodigo_ug = ' . $idUG);
            
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0)? $db->loadObjectList(): false;
            
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function getPrgIndEnt( $indEnt ) 
    {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(TRUE);
            $query->select('ip.intId_prgInd       AS idProInd, 
                            ip.intIdIndEntidad    AS idIndEntidad, 
                            pi.dteFechainicio_pi  AS fechaInico,
                            pi.dteFechafin_pi     AS fechaFin');
            $query->from('#__ind_programacion_indicador ip');
            $query->innerJoin('#__pln_plan_institucion pi ON pi.intId_pi = ip.intId_pi');
            $query->where('ip.intIdIndEntidad = ' . $indEnt);
            $query->where('pi.intId_tpoPlan = 4' );  //  4 Id de tipo de plan (PAPP)
            
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0)? $db->loadObjectList(): false;
            
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
        
    }
    
}
