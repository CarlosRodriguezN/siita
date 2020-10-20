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

class CanastaproyTableAlineacionPropuestaProy extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__cp_propuesta_pnbv', 'intId_PropuestaPNBV', $db );
    }
    
    /**
     * 
     * Gestiona el registro de la relacion de un proyecto con el PNBV
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @param type $dataPNBV    Datos de relacion de proyecto con le PNBV
     * 
     * @return type
     */
    public function registrarAlineacionPropuesta( $dataAlineacion )
    {
         if (!$this->save($dataAlineacion)) {
            echo 'error al guardar la alineacion de propuesta';
            exit;
        }
        return $this->intId_PropuestaPNBV;
    }
    
    /**
     * 
     *  Retorna una Lista de Politicas que estan bajo un determinado Objetivo Nacional
     *  
     *  @param type $idObjetivo Identificador del Objetivo Nacional
     *  @return type 
     */
    public function getLstPoliticaNacional( $idObjetivo )
    {
        
        $db = $this->getDbo();
        
        $sql = "SELECT  pn.intCodigo_pln as id, 
                        concat ( pn.intCodigo_on, '.', pn.intCodigo_pln, ': ', pn.strDescripcion_pln ) as nombre
                FROM ". $db->nameQuote( "#__pnd_politica_nacional" ) ." pn
                WHERE pn.intCodigo_on = '". $idObjetivo ."'
                AND pn.published = 1 
                ORDER BY pn.intCodigo_pln";
        
        $db->setQuery( $sql );
        $db->query();
       
        $rstPolitica = $db->loadObjectList();
        
        return $rstPolitica;
    }
    
    /**
     *  
     *  Retona una Lista de Metas Nacionales que cumplen un determinado 
     *  Objetivo y Politica Nacional 
     *  
     *  @param type $idObjetivo     Identificador del Objetivo Nacional
     *  @param type $idPlnNacional  Identificador del Plan Nacional
     * 
     *  @return type 
     */
    public function getLstMetaNacional( $idObjetivo, $idPlnNacional )
    {
        $db = $this->getDbo();
        
        $sql = "SELECT	idcodigo_mn as id,
                        CONCAT(  intcodigo_on, '.', intcodigo_pln, '.', intcodigo_mn, ': ', strdescripcion_mn ) as nombre
                FROM ". $db->nameQuote( "#__pnd_meta_nacional" ) ."
                WHERE intcodigo_on = '". $idObjetivo ."'
                AND intcodigo_pln = '". $idPlnNacional ."' 
                ORDER BY intcodigo_mn";
        
        $db->setQuery( $sql );
        $rstMetaNac = $db->loadObjectList();
        
        return $rstMetaNac;
    }

    /**
     * 
     * Retorna una el objetivo, politica y meta del PNBV 
     * que esta relacionado con un determinado proyecto
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * 
     * @return type
     */
    public function getLstAlnPropueta( $idPropuesta )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT t1.intId_PropuestaPNBV AS idPrpPry, 
                                t1.intIdPropuesta_cp AS idPropuesta, 
                                t3.intcodigo_on AS idObjNacional,
                                t4.strdescripcion_on AS objNacional,
                                
                                t2.intcodigo_pln AS idPoliticaNacional,
                                concat( t3.intcodigo_on, ".", t3.intcodigo_pln, ": ", t3.strdescripcion_pln ) AS politicaNacional,
                                
                                t2.idcodigo_mn AS idMetaNacional,
                                concat( t2.intcodigo_on, ".", t2.intcodigo_pln, ".", t2.intcodigo_mn, ": ", t2.strdescripcion_mn ) AS metaNacional' );

            $query->from( '#__cp_propuesta_pnbv t1' );
            
            $query->join( 'INNER', '#__pnd_meta_nacional t2 ON t1.idcodigo_mn = t2.idcodigo_mn' );           
            $query->join( 'INNER', '#__pnd_politica_nacional t3 ON t2.intcodigo_pln = t3.intcodigo_pln AND t2.intcodigo_on = t3.intcodigo_on' );            
            $query->join( 'INNER', '#__pnd_objetivo_nacional t4 ON t3.intcodigo_on = t4.intcodigo_on ' );
            
            $query->where( 't1.intIdPropuesta_cp = '. $idPropuesta );
            
            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() : array();
            
             return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    

    public function deleteAlineacion( $idAlineacionProp ){
     try {
         
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete("#__cp_propuesta_pnbv");
            $query->where("intId_PropuestaPNBV='{$idAlineacionProp}'");
            $db->setQuery($query);
            $db->query();
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function delAlineacionPropuesta($idPropuesta) {
        try {
         
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete("#__cp_propuesta_pnbv");
            $query->where("intIdPropuesta_cp='{$idPropuesta}'");
            $db->setQuery($query);
            $result = ($db->query()) ? true : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}