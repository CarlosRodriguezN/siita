<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla beneficiario ( #__gen_benefificario )
 * 
 */
class MantenimientoTableLineaBase extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_linea_base', 'intCodigo_lbind', $db );
    }
    
    /**
     * Guarda un registro en de linea base y retorna su Id
     * 
     * @param type $dtaLB       Data de la linea base
     * @return type
     */
    function registroLineaBase( $dtaLB )
    {
        if( !$this->save( $dtaLB ) ){
            echo 'error';
            exit;
        }
        
        return $this->intCodigo_lbind;
    }
    
    
    public function getLineaBase( $idLinBas )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("lb.intCodigo_lbind              AS idLineaBase,
                            lb.intCodigo_fuente             AS idFuente,
                            lb.intCodigo_per                AS idPeriodicidad,
                            lb.strDescripcion_lbind         AS descLB,
                            lb.dcmValor_lbind               AS valorLB,
                            lb.dteFechaInicio_lbind         AS fchInicioLB,
                            lb.dteFechaFin_lbind            AS fchFinLB,
                            lb.dteFechaRegistro_lbind       AS fchRegistroLB,
                            lb.dteFechaModificacion_lbind   AS fchModificacionLB,
                            lb.intTpoLB_lbind               AS tpoLB,
                            lb.published                    AS published");
            $query->from("#__ind_linea_base lb");
            $query->where("lb.intCodigo_lbind = {$idLinBas}");
            $db->setQuery($query);

            $result = ( $db->query() ) ? $db->loadObject() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.lineabase.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Actualiza el valor de una determinada linea base en la base de datos
     * @param type $idLB        Id de la linea base
     * @param type $valor       Valor a actulizar
     * @return type
     */
    public function updValorLB( $idLB, $valor )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update( "#__ind_linea_base " );
            $query->set( "dcmValor_lbind = {$valor}" );
            $query->set( "dteFechaModificacion_lbind = '" . date("Y-m-d H:i:s") . "' " );
            $query->where( "intCodigo_lbind = {$idLB}" );
            $db->setQuery($query);
            
            $result = ( $db->query() ) ? $this->getLineaBase( $idLB ) : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.lineabase.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}