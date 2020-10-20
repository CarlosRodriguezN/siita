<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Proyectos ( tb_pfr_proyecto_frm )
 * 
 */

class ProyectosTableProyecto extends JTable
{
    /**
    *   Constructor
    *
    *   @param object Database connector object
    * 
    */    
    function __construct( &$db ) 
    {
        parent::__construct( '#__pfr_proyecto_frm', 'intCodigo_pry', $db );
    }
    
    /**
     *  Retorna una lista de Unidades de Medida de acuerdo a un 
     *  determinado "Tipo" de Unidades de Medida
     */
    public function getLstUnidadMedida( $tipoUM )
    {
        $db = $this->getDbo();
        $sql = "    SELECT  intcodigo_unimed as id, 
                            concat( strsimbolo_unimed, ' - ', strdescripcion_unimed ) as nombre
                    FROM tb_gen_unidad_medida 
                    WHERE intId_tum = '". $tipoUM ."'";
        
        $db->setQuery( $sql );
        $db->query();
        
        $rstUndMedida = ( $db->getNumRows() )   ? $db->loadObjectList() 
                                                : FALSE;
        
        return $rstUndMedida;
    }

    /**
     * 
     * Retorno informacion especifica de un proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type object
     */
    public function getDataMeta( $idProyecto )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   m.strdescripcion_metapry as meta, 
                            m.intvalor_metapry as valorMeta' );

        $query->from( '#__pgp_meta_proyecto m' );
        $query->where( 'm.intcodigo_pry = '. $idProyecto );
    
        $db->setQuery( (string)$query );
        $db->query();
        
        $meta = ( $db->getNumRows() > 0 )   ? $db->loadObject() 
                                            : FALSE;
        
        return $meta;
    }

    
    /**
     * 
     * Retorno informacion general de un proyecto
     * 
     * @param type $idProyecto  Identificador de un proyecto
     * 
     * @return type
     */
    public function getDataProyecto( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   pry.strNombre_pry AS nomProyecto,
                                pry.intCodigo_pry AS idProyecto,  
                                pry.intIdEntidad_ent AS intIdEntidad_ent,  
                                if( prg.strNombre_prg IS NULL, " ", prg.strNombre_prg ) AS nomPrograma,
                                if( prg.strAlias_prg  IS NULL, " ", prg.strAlias_prg  ) AS prgAlias,
                                if( pry.intCodigo_prg IS NULL, " ", prg.intCodigo_prg  )AS idPrograma,
                                pry.dcmMonto_total_stmdoPry AS monto,  
                                pry.inpDuracion_stmdoPry AS duracion,
                                um.strdescripcion_unimed as undMedida,
                                pry.strDescripcion_pry AS descripcion,
                                pry.dteFechaInicio_stmdoPry as fchInicio,
                                pry.dteFechaFin_stmdoPry as fchFin,
                                IF( fun.strnombre_fnc IS NULL, " ", CONCAT( fun.strnombre_fnc , " ", fun.strapellido_fnc ) ) as responsable,
                                pry.strCargoResponsable_pry as cargoResponsable' );
            $query->from( '#__pfr_proyecto_frm as pry' );

            $query->join( 'INNER', '#__pfr_programa prg ON pry.intcodigo_prg = prg.intcodigo_prg' );
            $query->join( 'LEFT', 'fun_responsable_pry fr ON fr.intcodigo_pry = pry.intcodigo_pry' );
            $query->join( 'LEFT', '#__gen_funcionario fun ON fun.intcodigo_fnc = fr.intcodigo_fnc' );
            $query->join( 'LEFT', '#__gen_unidad_medida um ON um.intcodigo_unimed = pry.intcodigo_unimed' );

            $query->where( 'pry.intCodigo_pry = '. $idProyecto );

            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : FALSE;

            return $result;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
        
    /**
     * 
     *  Gestiono el registro de los datos generales de un proyecto
     * 
     *  @param array $dtaProyecto Datos Generales de un proyecto
     * 
     *  @return boolean
     * 
     */
    public function registroDtaGralProyecto( $dtaProyecto )
    {
        if( !$this->save( $dtaProyecto ) ){
            echo $this->getError();
            exit;
        }
        
        return $this->intCodigo_pry;
    }
    
    /**
     * 
     * Retorno una lista de Proyecto
     *  
     * @param type $idPrograma  Identificador de Programa
     */
    public function lstProyectos( $idPrograma )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   pry.intCodigo_pry AS id, 
                                upper( pry.strNombre_pry ) AS nombre' );
            $query->from( '#__pfr_proyecto_frm as pry' );
            $query->where( 'pry.intCodigo_prg = '. $idPrograma );
            $query->where( 'pry.published = 1' );

            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : FALSE;

            return $result;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    public function getProyectos(){
         try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( ''
                    . '*,'
                    . 'strNombre_pry AS grDescripcion' );
            $query->from( '#__pfr_proyecto_frm as pry' );
            $query->where( 'pry.published = 1' );

            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : FALSE;

            return $result;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}