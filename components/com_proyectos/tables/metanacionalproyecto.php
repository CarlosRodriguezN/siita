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

class ProyectosTableMetaNacionalProyecto extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pfr_proyecto_metanacional', 'intCodigo_pry', $db );
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
        
        $sql = "SELECT  pn.intcodigo_pln as id, 
                        concat ( pn.intcodigo_on, '.', pn.intcodigo_pln, ': ', pn.strdescripcion_pln ) as nombre
                FROM ". $db->nameQuote( "#__pnd_politica_nacional" ) ." pn
                WHERE pn.intcodigo_on = '". $idObjetivo ."'
                AND pn.published = 1 
                ORDER BY pn.intcodigo_pln";
        
        $db->setQuery( $sql );
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
    public function getLstMNP( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT t1.intCodigo_pry AS idProyecto, 
                                t3.intcodigo_on AS idObjNacional,
                                t4.strdescripcion_on AS objNacional,
                                
                                t2.intcodigo_pln AS idPoliticaNacional,
                                concat( t3.intcodigo_on, ".", t3.intcodigo_pln, ": ", t3.strdescripcion_pln ) AS politicaNacional,
                                
                                t2.idcodigo_mn AS idMetaNacional,
                                concat( t2.intcodigo_on, ".", t2.intcodigo_pln, ".", t2.intcodigo_mn, ": ", t2.strdescripcion_mn ) AS metaNacional' );

            $query->from( '#__pfr_proyecto_metanacional t1' );
            
            $query->join( 'INNER', '#__pnd_meta_nacional t2 ON t1.idcodigo_mn = t2.idcodigo_mn' );           
            $query->join( 'INNER', '#__pnd_politica_nacional t3 ON t2.intcodigo_pln = t3.intcodigo_pln AND t2.intcodigo_on = t3.intcodigo_on' );            
            $query->join( 'INNER', '#__pnd_objetivo_nacional t4 ON t3.intcodigo_on = t4.intcodigo_on ' );
            
            $query->where( 't1.intCodigo_pry = '. $idProyecto );
            
            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                            : FALSE;

             return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Elimina la relacion de un determinado proyecto con el PNBV
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     */
    private function _delRelacionAnterior( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $sql = "DELETE 
                    FROM #__pfr_proyecto_metanacional 
                    WHERE intCodigo_pry = '". $idProyecto ."'";
            
            $db->setQuery( (string)$sql );

            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            return false;
        }
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
    public function registrarRelacionPNBV( $idProyecto, $dataPNBV )
    {
        try {
            $rst = false;
            
            //  Eliminamos las relaciones anteriores
            if( $this->_delRelacionAnterior( $idProyecto ) ){
                
                $db = JFactory::getDBO();
                $lstRelacionPPNBV = json_decode( $dataPNBV );
                
                $sql = "INSERT INTO #__pfr_proyecto_metanacional(   intCodigo_pry, 
                                                                    idcodigo_mn ) 
                        VALUES";

                foreach( $lstRelacionPPNBV AS $relacion ){
                    $sql .= "( '". $idProyecto ."', '". $relacion->idMetaNacional ."' ),";
                }

                $db->setQuery( (string) trim( $sql, ',' ) );
                $db->query();

                $rst = ( $db->getAffectedRows() > 0 )   ? TRUE 
                                                        : FALSE;                
            }
            
            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }


    /**
     * 
     * Gestiono el registro de la alineacion del proyecto con el PNBV
     * 
     * @param type $alineacion  Datos de alineacion de un proyecto con PNBV
     * 
     * @return boolean
     * @throws Exception
     */
    public function registraAlineacionPryPNBV( $alineacion )
    {
        if( !$this->save( $alineacion ) ){
            throw new Exception( 'Error al Registrar la alineacion del proyecto con PNBV' );
        }

        return true;
    }
}