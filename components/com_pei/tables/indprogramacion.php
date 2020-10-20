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
class PeiTableIndProgramacion extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__ind_programacion_indicador', 'intId_prgInd', $db);
    }

    public function registroPrgInd( $prgInd, $idPrgPoa )
    {
        
        $prgIndEnt["intId_prgInd"] = 0;
        $prgIndEnt["intId_pi"] = $idPrgPoa;
        $prgIndEnt["intIdIndEntidad"] = $prgInd->idIndEntidad;
        
        if (!$this->save($prgIndEnt)) {
            echo $this->getError();
            exit;
        }

        return $this->intId_prgInd;
    }

    public function getLstPrgDetalle( $idPrgInd )
    {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(TRUE);
            
            $query->select('intId_prgDet    AS idPrgDetalle, 
                            intId_prgInd    AS idPrgInd,
                            dteFechaFin_prgDet  AS fecha,
                            intValor_prgDet     AS valor');
            $query->from('#__ind_programacion_detalle');
            $query->where('intId_prgInd = ' . $idPrgInd);
            
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
    
    public function resitroDetallePrg( $detalle, $idPrgIndEnt )
    {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(TRUE);
            
            $query->insert('#__ind_programacion_detalle');
            $query->columns('intId_prgDet, 
                            intId_prgInd,
                            dteFechaFin_prgDet,
                            intValor_prgDet');
            $query->values('0, ' . $idPrgIndEnt. ', "' . $detalle->fecha . '", ' . $detalle->valor );
            
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