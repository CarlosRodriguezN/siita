<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class ProyectosTableRango extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_rango_gestion', 'intId_rango', $db );
    }
    
    
    /**
     * 
     * Gestiono el registro de informacion de rangos de Gestion
     * 
     * @param int $idIndEntidad     Identificador de Indicador Entidad
     * @param Object $rango         Datos del Rango a Registrar
     * 
     * @return type
     * @throws Exception
     * 
     */
    public function registrarRango( $idIndEntidad, $rango )
    {
        if( $rango->published == 1 ){
            $dtaRango["intId_rango"] = $rango->idRango;
            $dtaRango["intIdIndEntidad"] = $idIndEntidad;
            $dtaRango["fltMinimo_rango"] = $rango->valMinimo;
            $dtaRango["fltMaximo_rango"] = $rango->valMaximo;
            $dtaRango["strColor_rango"] = $rango->color;
            
            if( $rango->idRango == 0 ){
                $dtaRango["dteFechaRegistro_rango"] = date("Y-m-d H:i:s");
            }else{
                $dtaRango["dteFechaModificacion_rango"] = date("Y-m-d H:i:s");
            }
            
            if( !$this->save( $dtaRango ) ){
                throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_RANGO' ) );
            }
            
            $idRango = $this->intId_rango;
        }else{
            $idRango = $rango->idRango;
            $this->delete( $rango->idRango );
        }
        
        return $idRango;
    }
    
    
    /**
     * 
     * Retorna una lista de rangos asociados a un determinado Indicador-Entidad
     * 
     * @param type $idIndEntidad    Identificador Entidad
     * 
     * @return type
     */
    public function getLstRangos( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_rango      AS idRango,
                                t1.fltMinimo_rango  AS valMinimo,
                                t1.fltMaximo_rango  AS valMaximo,
                                t1.strColor_rango   AS color, 
                                1 AS published' );
            $query->from( '#__ind_rango_gestion t1' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );

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