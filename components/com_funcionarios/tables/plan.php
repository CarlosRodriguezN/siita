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
class FuncionariosTablePlan extends JTable
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

    public function registroPlan( $dtaPlan )
    {

        if ( !$this->save( $dtaPlan ) ){
            echo $this->getError();
            exit;
        }

        return $this->intId_pi;
    }

    /**
     * 
     * Retorno todos los planes de tipo POA 
     * asociados a una determinada Entidad Unidad de Gestion
     * 
     * @param type $idEntidad   Identificador Entidad -Unidad de Gestion
     * @return type
     * 
     */
    public function getLstPoas( $idEntidad )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "   t1.intId_pi            AS idPoa, 
                                t1.intId_tpoPlan       AS idTipoPlanPoa, 
                                t1.intCodigo_ins       AS idInstitucionPoa, 
                                t1.intIdentidad_ent    AS idEntidadPoa, 
                                t1.intIdPadre_pi       AS idPadrePoa, 
                                t1.strDescripcion_pi   AS descripcionPoa,
                                t1.dteFechainicio_pi   AS fechaInicioPoa,
                                t1.dteFechafin_pi      AS fechaFinPoa,
                                t1.strAlias_pi         As aliasPoa,
                                ( SELECT IF( NOW() BETWEEN t1.dteFechainicio_pi AND t1.dteFechafin_pi, 1, 0 )) AS vigenciaPoa,
                                t1.published" );
            $query->from( "#__pln_plan_institucion t1" );
            $query->where( "t1.blnVigente_pi = 1" );
            $query->where( "t1.intIdentidad_ent= " . $idEntidad );
            
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0)
                            ? $db->loadObjectList()
                            : array ();

            return $result;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}
