<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class jTableSeguimiento extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    public function __construct( &$db ) 
    {
        parent::__construct( '#__ind_seguimiento', 'intId_seg', $db );
    }
    
    /**
     * 
     * Gestiono el registro de una variable
     * 
     * @param type $dtaSeguimiento    datos de la variable a registrar
     * 
     * @return type
     * @throws Exception
     */
    public function gestionSeguimiento( $idIndEntidad, $idIndVariable, $dtaSeguimiento )
    {
        if( $dtaSeguimiento->published == 1 ){
            $dtaSegVariable["intId_seg"]       = ( is_null( $dtaSeguimiento->idSeg ) )  ? 0 
                                                                                        : $dtaSeguimiento->idSeg;

            $dtaSegVariable["intId_iv"]        = $idIndVariable;
            $dtaSegVariable["intIdIndEntidad"] = $idIndEntidad;
            $dtaSegVariable["dteFecha_seg"]    = $dtaSeguimiento->fecha;
            $dtaSegVariable["dcmValor_seg"]    = $dtaSeguimiento->valor;

            if( $dtaSeguimiento->idSeg == 0 ){
                $dtaSegVariable["dteFechaRegistro_seg"]  = date("Y-m-d H:i:s");
            }else{
                $dtaSegVariable["dteFechaModificacion_seg"]  = date("Y-m-d H:i:s");
            }

            if( !$this->save( $dtaSegVariable ) ){
                echo $this->getError();
                exit;
            }
        }else{
            $this->delete( $dtaSeguimiento->idSeg );
        }
        
        return $this->intId_seg;
    }
    
    /**
     * 
     * Gestiona la eliminacion del Seguimiento de las variables asociadas a un 
     * determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     */
    public function deleteSeguimiento( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            //  Elimino seguimiento de variables asociadas a un indicador
            $query->delete( '#__ind_seguimiento' );
            $query->where( 'intId_iv IN (   SELECT t2.intId_iv 
                                            FROM tb_ind_indicador_variables t2
                                            WHERE t2.intCodigo_ind = '. $idIndicador .' )' );

            $db->setQuery( $query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Gestiona la eliminacion del Seguimiento de las variables asociadas a un 
     * determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     */
    public function deleteSeguimientoVariable( $idIndVariable )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            //  Elimino seguimiento de variables asociadas a un indicador
            $query->delete( '#__ind_seguimiento' );
            $query->where( 'intId_iv = '. $idIndVariable );
            
            $db->setQuery( $query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
}