<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableObjetivoOperativo extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_objetivos_operativos', 'intIdObjetivo_operativo', $db );
    }

    /**
     * Recupera los objetivos de una entidad
     * @param type $idEntidad
     * @return type
     */
    public function getObjetivosOperativos( $idEntidad )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( ''
                    . 'intIdObjetivo_operativo  AS  idObjetivo,'
                    . 'intIdEntidad_owner       AS  idEntOwn,'
                    . 'intIdEntidad             AS  idEntidad,'
                    . 'strDescripcion_ObjOp     AS  descObjetivo,'
                    . 'published                AS  published' );
            $query->from( '#__gen_objetivos_operativos' );
            $query->where( 'intIdEntidad_owner = ' . $idEntidad );
            $query->where( 'published = 1' );
            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $data
     * @param type $idEntidadOwner
     * @return type
     */
    public function saveObjetivoOperativo( $data, $idEntidadOwner )
    {
        $idObjetivo = 0;
        $inf['intIdObjetivo_operativo'] = $data->idObjetivo;
        $inf['intIdEntidad_owner']      = $idEntidadOwner;
        $inf['intIdEntidad']            = $data->idEntidad;
        $inf['strDescripcion_ObjOp']    = $data->descObjetivo;
        $inf['published']               = $data->published;
        if( $this->save( $inf ) ){
            $idObjetivo = $this->intIdObjetivo_operativo;
        }
        return $idObjetivo;
    }

}