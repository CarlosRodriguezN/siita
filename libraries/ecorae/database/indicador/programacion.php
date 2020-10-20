<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de Programacion de 
 * un indicador ( tb_pln_plan_institucion )
 * 
 */
class jTableProgramacion extends JTable {

//
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pln_plan_institucion', 'intId_pi', $db);
    }

    public function registroProgramacion($programacion) 
    {
        
        $dtaPrg["intId_pi"]            = $programacion->idProgramacion;
        $dtaPrg["intId_tpoPlan"]       = $programacion->idTipo;
        $dtaPrg["dteFechainicio_pi"]   = $programacion->fechaInicio;
        $dtaPrg["dteFechafin_pi"]      = $programacion->fechaFin;
        $dtaPrg["intIdPadre_pi"]       = $programacion->padre;
        $dtaPrg["strDescripcion_pi"]   = $programacion->descripcion;
        $dtaPrg["strAlias_pi"]         = $programacion->alias;
        $dtaPrg["intCodigo_ins"]       = 1;     //  1 Id de la institucion ecorae
        $dtaPrg["intIdentidad_ent"]    = 0;
        $dtaPrg["blnVigente_pi"]       = NULL;

        if (!$this->save($dtaPrg)) {
            throw new Exception(JText::_('COM_PROYECTOS_REGISTRO_PROGRAMACION_INDICADOR'));
        }

        return $this->intId_pi;
    }

    public function getProgramacionIndicador( $idIndEntidad, $tpoPlan ) 
    {
       try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   prg.intId_pi                AS idProgramacion,
                                prg.intId_tpoPlan           AS idTipo,
                                tp.strDescripcion_tpoPlan   AS tipoPlanDescripcion,
                                prg.strDescripcion_pi       AS descripcion,
                                prg.dteFechainicio_pi       AS fechaInicio,
                                prg.dteFechafin_pi          AS fechaFin,
                                prg.intIdPadre_pi           AS padre,
                                prg.strAlias_pi             AS alias,
                                pi.intId_prgInd             AS idPrgInd' );
            $query->from( '#__pln_plan_institucion prg' );
            $query->join( 'INNER', '#__ind_programacion_indicador pi ON prg.intId_pi = pi.intId_pi' );
            $query->join( 'INNER', '#__pln_tipo_plan_ins tp ON prg.intId_tpoPlan = tp.intId_tpoPlan' );
            $query->where( 'pi.intIdIndEntidad = ' . $idIndEntidad );
            if ( $tpoPlan == 1 ) {
                //  3 y 4 Ids de Tipo de programacion de un PIE
                $query->where( '(prg.intId_tpoPlan = 3 OR prg.intId_tpoPlan = 4)' );
            } elseif ( $tpoPlan == 2 ) {
                $query->where( 'prg.intId_tpoPlan = 6' );   //  6 Id de Tipo de programacion de un POA
            }
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaProgramacion = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                        : array();
            return $dtaProgramacion;
            
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        } 
    }

}