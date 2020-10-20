<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class PeiTablePei extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_plan_institucion', 'intId_pi', $db );
    }

    /**
     * 
     * REGISTRA el PLAN
     * 
     * @param type $dtaPlan
     * @return type
     */
    public function registroPlan( $dtaPlan )
    {

        if( !$this->save( $dtaPlan ) ){
            echo $this->getError();
            exit;
        }

        return $this->intId_pi;
    }

    /**
     *
     * Averigua si el PLAN puede o no ser eliminado. 
     * 
     * @param type $idPei   Identificador del PEI
     * @return type
     */
    public function avalibleDel( $idPei )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "po.intId_pln_objetivo" );
            $query->from( "#__pln_plan_objetivo po" );
            $query->innerJoin( "#__pln_objetivo_institucion o ON o.intId_ob = po.intId_ob" );
            $query->where( "po.intId_pi= " . $idPei );
            $query->where( "o.published=1 " );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getNumRows() > 0)   ? false 
                                                : true;

            return $result;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $idPei
     * @return type
     */
    public function getLstPoas( $idPei )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->select( "intId_pi, intIdPadre_pi, strDescripcion_pi, blnVigente_pi, published" );
            $query->from( "#__pln_plan_institucion" );
            $query->where( "intIdPadre_pi= " . $idPei );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false;

            return $result;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * RECUPERA el IDENTIDADA de la INSTITUCION
     * 
     * @param type $idIns   identificador de la Institucion
     * @return type
     */
    public function getIdEntidadIns( $idIns )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->select( "indIdentidad_ent" );
            $query->from( "#__gen_institucion" );
            $query->where( "intCodigo_ins= " . $idIns );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;

            return $result;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /////////////////// FUNCIONES PARA LA GESTION DE VIGENCIAS DE PLANES /////////////////////////////////////

    /**
     *  Actualiza los registros de planes activos a NO VIGENTE
     * @return type
     */
//    public function noVigente( $tpoPln )
//    {
//        try {
//            $db = & JFactory::getDBO();
//            $query = $db->getQuery(TRUE);
//            $query->update("#__pln_plan_institucion");
//            $query->set("blnVigente_pi = 0");
//            $query->where("published = 1");
//            switch ($tpoPln){
//                case 1:
//                    $query->where("intId_tpoPlan >= 1");
//                    break;
//                case 3:
//                    $query->where("intId_tpoPlan > 1");
//                    break;
//                case 4:
//                    $query->where("intId_tpoPlan = 4");
//                    break;
//            }
//            $db->setQuery($query);
//            $db->query();
//            $result = ( $db->getAffectedRows() > 0 ) ? true : false;
//            return $result;
//        } catch (Exception $e) {
//            jimport('joomla.error.log');
//            $log = &JLog::getInstance('com_pei.tables.log.php');
//            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
//        }
//    }

    /**
     *  Actualiza la vigencia de un determinado PLAN
     * @param type $idPlan       Id del Plan  actualizar la vigencia
     * @param type $opVgen      Opcion de la vigencia (0, 1)
     * @return type
     */
//    public function updVigencia($idPlan, $opVgen)
//    {
//        try {
//            $db = & JFactory::getDBO();
//            $query = $db->getQuery(TRUE);
//            $query->update("#__pln_plan_institucion");
//            $query->set("blnVigente_pi=" . $opVgen);
//            $query->where("intId_pi= " . $idPlan);
//            $db->setQuery($query);
//            $db->query();
//            $result = ( $db->getAffectedRows() > 0 ) ? true : false;
//            return $result;
//        } catch (Exception $e) {
//            jimport('joomla.error.log');
//            $log = &JLog::getInstance('com_pei.tables.log.php');
//            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
//        }
//    }

    /**
     *  Retorna la lista de Planes de un Plan Padre (PEI)
     * 
     * @param int       $idPlnPadre     Id del plan PEI
     * @param int       $tpoPlan        Id de tipo de plan a obtener
     * @return type
     */
    public function getLstPlanes( $idPlnPadre, $tpoPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "   t1.intId_pi            AS idPlan, 
                                t1.intIdentidad_ent    AS idEntidad, 
                                t1.intIdPadre_pi       AS idPlnPadre, 
                                t1.intId_tpoPlan       AS tipoPln, 
                                t1.strDescripcion_pi   AS descripcionPln, 
                                t1.blnVigente_pi       AS vigentePln, 
                                t1.dteFechainicio_pi   AS fechaInicioPln, 
                                t1.dteFechafin_pi      AS fechaFinPln, 
                                t1.published           AS published" );
            $query->from( "#__pln_plan_institucion t1" );
            $query->where( "t1.intIdPadre_pi = " . $idPlnPadre );
            $query->where( "t1.intId_tpoPlan = " . $tpoPlan );
            $query->where( "t1.published = 1" );
            
            $db->setQuery( $query );
            $db->query();

            $result = ( $db->getAffectedRows() > 0 )
                        ? $db->loadObjectList() 
                        : array();

            return $result;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorna un objeto con al data general de un Plan
     * @param type $idPlan   Identificador del Plan
     * @return type
     */
    public function getDataPlan( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPln,
                                t1.intId_tpoPlan        AS idTpoPln,
                                t1.intCodigo_ins        AS idInstitucion,
                                t1.intIdPadre_pi        AS idPadre,
                                t1.intIdentidad_ent     AS idEntidad,
                                t1.strDescripcion_pi    AS descripcion,
                                t1.dteFechainicio_pi    AS fchInicio,
                                t1.dteFechafin_pi       AS fchFin,
                                t1.published            AS published' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intId_pi = ' . $idPlan );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObject() : array();

            return $retval;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}
