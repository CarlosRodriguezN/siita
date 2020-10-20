<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla Funcionario Responsable ( tb_ind_funcionario_responsable )
 * 
 */
class jTableProgramacionDetalle extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ind_programacion_detalle', 'intId_prgDet', $db);
    }

    
    public function registroDetalleProgramacion($idPrgInd, $detalleProgramacion) {

        $dtaDtllPrgInd["intId_prgDet"] = $detalleProgramacion->idPrgDetalle;
        $dtaDtllPrgInd["intId_prgInd"] = $idPrgInd;
        $dtaDtllPrgInd["dteFechaFin_prgDet"] = $detalleProgramacion->fecha;
        $dtaDtllPrgInd["intValor_prgDet"] = $detalleProgramacion->valor;
        
        if (!$this->save($dtaDtllPrgInd)) {
            throw new Exception(JText::_('COM_PROYECTOS_REGISTRO_PROGRAMACION_INDICADOR'));
        }
        
        return $this->intId_prgDet;
    }
    
    public function getMetasProgramacion($idPrgInd)
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   pd.intId_prgDet         AS idPrgDetalle,
                                pd.dteFechaFin_prgDet   AS fecha,
                                pd.intValor_prgDet      AS valor');
            $query->from( '#__ind_programacion_detalle pd' );
            $query->where( 'pd.intId_prgInd = ' . $idPrgInd );
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $dtaProgramacion = array();
            
            if ($db->getNumRows() > 0) {
                $metasPrg = $db->loadObjectList();
                foreach ($metasPrg AS $key=>$meta) {
                    $meta->idReg = $key;
                    $dtaProgramacion[]=$meta;
                }
            }
            
            return $dtaProgramacion;
            
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        } 
    }

}