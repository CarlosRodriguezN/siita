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
class PanelTablePlan extends JTable
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

    public function getLstPoas($idEntidad) {

        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_pi            AS idPoa, 
                            intId_tpoPlan       AS idTipoPlanPoa, 
                            intCodigo_ins       AS idInstitucionPoa, 
                            intIdentidad_ent    AS idEntidadPoa, 
                            intIdPadre_pi       AS idPadrePoa, 
                            strDescripcion_pi   AS descripcionPoa,
                            dteFechainicio_pi   AS fechaInicioPoa,
                            dteFechafin_pi      AS fechaFinPoa,
                            blnVigente_pi       AS vigenciaPoa, 
                            strAlias_pi         As aliasPoa,
                            published");
            $query->from( "#__pln_plan_institucion" );
            $query->where( "intIdentidad_ent= " . $idEntidad );
            
            $db->setQuery($query);
            $db->query();

            $result = ($db->getAffectedRows() > 0)  ? $db->loadObjectList() 
                                                    : array();

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}