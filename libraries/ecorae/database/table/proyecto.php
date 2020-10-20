<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 *  Clase que gestiona informacion de la tabla de proyectos ( #__pei_plan_institucion )
 * 
 */
class JTableProyecto extends JTable {

    /**
     * Constructor
     * @param   JDatabase  &$db  A database connector object
     * @since   11.1
     */
    public function __construct(&$db) {
        parent::__construct('#__pfr_proyecto_frm', 'intCodigo_pry', $db);

    }
    
     /**
     *  Retorna la lista de proyectos de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG        Id de entidad de unidad de gestión
     * @return type
     */
    function getLstProyectosUG( $idEntidadUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("pry.intCodigo_pry AS idProyecto,
                            pry.strNombre_pry AS nombrePry,
                            pry.intIdEntidad_ent AS idEntidadPry,
                            pry.published");
            $query->from('#__pfr_proyecto_frm AS pry');
            $query->innerJoin( "#__pry_ug_responsable pug ON pug.intCodigo_pry = pry.intCodigo_pry");
            $query->innerJoin( "#__gen_unidad_gestion ug ON ug.intCodigo_ug = pug.intCodigo_ug");
            $query->where( "ug.intIdentidad_ent = {$idEntidadUG}");
            $query->where( "pug.intVigencia_pryUGR = 1");
            $query->order("pry.intCodigo_pry DESC");
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.proyectos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
     /**
     *  Retorna la lista de proyectos de un determinada funcionario
     * 
     * @param int      $idFnc        Id del funcionario
     * @return type
     */
    function getLstProyectosFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("pry.intCodigo_pry AS idProyecto,
                            pry.strNombre_pry AS nombrePry,
                            pry.intIdEntidad_ent AS idEntidadPry,
                            pry.published");
            $query->from('#__pfr_proyecto_frm AS pry');
            $query->innerJoin( "#__pry_funcionario_responsable pfr ON pfr.intCodigo_pry = pry.intCodigo_pry");
            $query->innerJoin( "#__gen_ug_funcionario ugf ON ugf.intId_ugf = pfr.intId_ugf");
            $query->where( "ugf.intCodigo_fnc = {$idFnc}");
            $query->where( "pfr.intVigencia_pryFR = 1");
            $query->order("pry.intCodigo_pry DESC");

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.proyectos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     * 
     * Listo los Proyectos asociados a una determinado Programa
     * 
     * @param type $idPrograma
     * @return type
     */
    function getLstProyectosPrg( $idPrograma )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select('t1.intCodigo_pry AS idProyecto, 
                            t1.intIdEntidad_ent AS idEntidadPry, 
                            t1.strNombre_pry AS nombrePry');
            $query->from( '#__pfr_proyecto_frm t1' );
            $query->where( 't1.intCodigo_prg = '. $idPrograma );
            $query->order( 't1.strNombre_pry asc' );

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.proyectos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    function getDtaProyecto( $idProyecto )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('    DISTINCT 
                                    t1.idProyecto,
                                    t1.idEntidad,
                                    t1.descripcionEntidad,
                                    t1.nombreProyecto,
                                    t1.descripcionProyecto,
                                    t1.cupProyecto,
                                    t1.fechaInicioEstimada,
                                    t1.fechaFinEstimada,
                                    t1.duracionEstimada,
                                    t1.unidadMedidaDuracion,
                                    t1.montoEstimado,
                                    t1.fechaCreacion,
                                    t1.fechaModificacion,
                                    t1.estado,
                                    t1.nombrePrograma,
                                    t1.nombreSubPrograma,
                                    t1.descripcionSector,
                                    t1.descripcionSubSector,
                                    t1.nombreUnidadGestion,
                                    t1.nombreUnidadGestionFuncionario,
                                    t1.nombreFuncionario');
            $query->from( '#__dim_proyectos t1' );
            $query->where( 't1.idProyecto = '. $idProyecto );
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 )
                        ? $db->loadObject()
                        : array ();

            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.proyectos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
}