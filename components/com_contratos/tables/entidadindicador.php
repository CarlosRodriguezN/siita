
<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

//  Import Joomla JUser Library
jimport('joomla.user.user');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ContratosTableEntidadIndicador extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ind_indicador_entidad', 'intIdIndEntidad', $db);
    }

    public function getIndicadoresEntidad($idEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('    t1.intIdIndEntidad          AS idIndEntidad,
                                t1.intCodigo_ind            AS idIndicador,
                                t6.intId_td                 AS idEnfoque,
                                t6.strDescripcion_td        AS enfoque,
                                t3.intId_dim                AS idDimension,
                                t4.strDescripcion_dim       AS dimension,
                                t2.strNombre_ind            AS nombre,
                                t2.strDescripcion_ind       AS descripcion,
                                t2.inpCodigo_unianl         AS idUndAnalisis,
                                t5.strDescripcion_unianl    AS undAnalisis,
                                t5.strAlias_unianl          AS alias,
                                t7.intId_tum                AS idTpoUndMedida,                                
                                t2.intCodigo_unimed         AS idUndMedida,
                                t7.strDescripcion_unimed    AS undMedida,                                
                                t1.intcodigo_per            AS idPeriodicidad,
                                IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )  AS formula,
                                t1.dcmValor_ind         AS valor');
            $query->from('#__ind_indicador_entidad t1');
            $query->join('INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind');
            $query->join('INNER', '#__ind_dimension_indicador t3 ON t2.intCodigo_ind = t3.intCodigo_ind');
            $query->join('INNER', '#__gen_dimension t4 ON t4.intId_dim = t3.intId_dim');
            $query->join('INNER', '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl');
            $query->join('INNER', '#__gen_enfoque t6 ON t6.intId_td = t4.intId_td');
            $query->join('INNER', '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed');
            $query->where('t1.intIdentidad_ent = ' . $idEntidad);
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObjectList()) ? $db->loadObjectList() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.entidad.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}