<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */
class jTableLineaBase extends JTable
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
     * 
     * Retorno una lista de instituciones
     * 
     * @return type
     */
    public function getLstInstituciones()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   intCodigo_ins AS key, 
                                strNombre_ins AS value' );
            $query->from( '#__gen_institucion' );
            $query->where( 'published = 1' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                            : false;

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestion de Lineas Base
     * 
     * @param type $idFuente
     * @return type
     * 
     */
    public function getLineasBase( $idFuente )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intCodigo_lbind AS id, 
                                UPPER( t1.strDescripcion_lbind ) AS nombre, 
                                t1.dcmValor_lbind AS valor' );
            $query->from( '#__ind_linea_base t1' );
            $query->where( 't1.intCodigo_fuente = ' . $idFuente );
            $query->where( 't1.intTpoLB_lbind = 0' );
            $query->where( 't1.published = 1' );
            $query->order( 't1.strDescripcion_lbind' );

            $db->setQuery( ( string ) $query );
            $db->query();

            if( $db->getNumRows() > 0 ){
                $rst = $db->loadObjectList();
                $rst[] = ( object ) array('id' => '', 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LINEABASE_TITLE' ));
            } else{
                $rst = array();
                $rst[] = ( object ) array('id' => '', 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ));
            }

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestiono el registro de lineas base de un deteminado indicador
     * 
     * @param type $idIndicador     Identificador del indicador
     * @param type $dtaLineaBase    Datos de la linea base a registrar
     * 
     */
    public function regLBIndicador( $idIndicador, $dtaLineaBase )
    {
        //  Borro las lineas base de un determinado asociadas a un indicador
        $this->_delLBIndicador( $idIndicador );
        
        //  Registro las nuevas lineas base pertenecientes a un determinado indicador
        $this->_registroLBIndicador( $idIndicador, $dtaLineaBase );

        return;
    }

    /**
     * 
     * Elimino las lineas base de un determinado indicador
     * 
     * @param type $idIndicador     Identificador de un determinado indicador
     * 
     * @return type
     * 
     */
    private function _delLBIndicador( $idIndicador )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_linea_base_indicador ' );
            $query->where( 'intCodigo_ind = ' . $idIndicador );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

            $db->transactionCommit();

            return $ban;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestion de Registro de Lineas Base asociadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @param type $dtaLineaBase    Datos de Linea Base
     * @return type
     */
    private function _registroLBIndicador( $idIndicador, $dtaLineaBase )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            
            $sql = 'INSERT INTO #__ind_linea_base_indicador( intCodigo_lbind, intCodigo_ind ) VALUES';

            foreach( $dtaLineaBase as $lineaBase ){
                $sql .= '( ' . $lineaBase->idLineaBase . ', ' . $idIndicador . ' ),';
            }

            $db->setQuery( rtrim( $sql, ',' ) );
            $db->query();

            $db->transactionCommit();
            
            return $db->getAffectedRows();
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);            
        }
    }

    /**
     * 
     * Retorna una lista de lineas base relacionadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador de Indicador
     * 
     * @return type
     * 
     */
    public function getLstLineasBase( $idIndicador )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intCodigo_lbind          AS idLineaBase,
                                t2.strDescripcion_lbind     AS nombre,
                                t2.dcmValor_lbind           AS valor,
                                t3.intCodigo_fuente         AS idFuente,
                                t3.strObservacion_fuente    AS fuente, 
                                0                           AS isNew' );
            $query->from( '#__ind_linea_base_indicador t1' );
            $query->join( 'INNER', '#__ind_linea_base t2 ON t1.intCodigo_lbind = t2.intCodigo_lbind' );
            $query->join( 'INNER', '#__ind_fuente t3 ON t2.intCodigo_fuente = t3.intCodigo_fuente' );
            $query->where( 't1.intCodigo_ind = ' . $idIndicador );
            
            $db->setQuery( ( string ) $query );
            $db->query();

            $lstLB = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() 
                                                    : array();

            return $lstLB;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestiona el registro de lineas base generadas a partir de un proceso de 
     * prorrateo de indicadores
     * 
     * @param type $idIndicador     Identificador del indicador
     * @param type $dtaLineaBase    Data de Linea Base Prorrateada de tipo SUPUESTO
     * 
     * @return type
     */
    public function registroLB( $idIndicador, $dtaLineaBase, $idTpoPlan )
    {
        foreach( $dtaLineaBase as $lb ){
            if( $lb->isNew == 1 ){
                //  Borro las lineas base de un determinado asociadas a un indicador
                $this->_delLBIndicador( $idIndicador );
                
                //  Registro la linea base con la informacion de linea base, 
                //  con la consideracion que se registra siempre un nuevo registro de LB
                //  con la finalidad de mantener la informacion historia de LB
                $this->_regLineaBase( $idIndicador, $lb );

                //  Actualizo LB del indicador asociado al plan vigente
                $this->_updLBIndPV( $idIndicador, $lb, $idTpoPlan );
            }
        }

        return;
    }
    
    
    private function _regLineaBase( $idIndicador, $lb )
    {
        //  Registro Linea base de tipo supuesto, validando el tipo de linea base
        //  ya que un plan tiene informacion digitada ( PEI ) y generada ( PPPP, PAPP, etc. )  
        $idLBSupuesto = $this->_registroLBSupuesto( $lb );

        //  Registro Relacion LB Supuesto con indicador
        $this->_relacionLBSupIndicador( $idIndicador, $idLBSupuesto );
    }
    
    
    private function _updLBIndPV( $idIndicador, $lb, $idTpoPlan )
    {
        //  Homologo informacion de linea base
        $lstIndPlnVigente = $this->_getIdIndPlnVigente( $idIndicador, $idTpoPlan );

        if( count( $lstIndPlnVigente ) ){
            foreach( $lstIndPlnVigente as $plnVg ){
                //  Borro las lineas base de un determinado asociadas a un indicador
                $this->_delLBIndicador( $plnVg->idIndicador );

                //  Add linea base
                $this->_regLineaBase( $plnVg->idIndicador, $lb );

                //  recursiva con la finalidad de upd informacion de LB de indicadores Hijo
                //  asociados a este indicador
                $this->_updLBIndPV( $plnVg->idIndicador, $lb, $idTpoPlan );
            }
        }

        return;
    }
    
    private function _getIdIndPlnVigente( $idIndicador, $idTpoPlan )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intCodigo_ind AS idIndicador' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__pln_plan_objetivo t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
            $query->join( 'INNER', '#__pln_plan_institucion t3 ON t2.intId_pi = t3.intId_pi' );
            
            $query->where( 't1.intIdIEPadre_indEnt = '. $idIndicador );
            $query->where( 'NOW() BETWEEN t3.dteFechainicio_pi AND t3.dteFechafin_pi' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $lstLB = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList()
                                                    : FALSE;

            return $lstLB;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    

    /**
     * 
     * Borro lineas base de las tablas relacion indicador lineas base 
     * 
     * @param type $idIndicador     Identificador del Indicador
     * 
     * @return type
     * 
     */
    private function _delLBSupuestos( $idIndicador )
    {
        //  Lista de indicadores de tipo supuestos asociados a un indicador
        $lstIndSup = $this->_lstIndSupuestos( $idIndicador );
        $numReg = count( $lstIndSup );

        if( $numReg > 0 ){
            //  Borro indicadores de la tabla relacion supuestos
            $this->_delRelLBSup( $lstIndSup );

            //  Borro Lineas base Supuestos
            $this->_delLBSup( $lstIndSup );
        }

        return $numReg;
    }

    /**
     * 
     * Retorna una lista de indicadores de tipo supuestos asociados a un 
     * determinado indicador
     * 
     * @param type $idIndicador     Identificador del indicador
     * @return type
     */
    private function _lstIndSupuestos( $idIndicador )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intCodigo_lbind' );
            $query->from( '#__ind_linea_base_indicador t1' );
            $query->join( 'INNER', '#__ind_linea_base t2 ON t1.intCodigo_lbind = t2.intCodigo_lbind' );
            $query->where( 't1.intCodigo_ind = ' . $idIndicador );
            $query->where( 't2.intTpoLB_lbind = 1' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $lstIndSup = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $lstIndSup;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Elimina los indicadores de tipo supuesto asociados a un determinado 
     * indicador
     * 
     * @param type $lstIndSup   Lista de Indicadores supuestos
     * 
     * @return type
     * 
     */
    private function _delRelLBSup( $lstIndSup )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_linea_base_indicador ' );
            $query->where( ' intCodigo_lbind IN ( ' . implode( ',', $lstIndSup ) . ' )' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE   
                                                    : FALSE;

            $db->transactionCommit();
            return $ban;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }
    }

    /**
     * 
     * Elimina los indicadores de tipo Supuestos asociados a un determinado 
     * indicador
     * 
     * @param type $lstIndSup   Lista de indicadores de tipo supuesto
     * @return type
     * 
     */
    private function _delLBSup( $lstIndSup )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_linea_base' );
            $query->where( ' intCodigo_lbind IN ( ' . implode( ',', $lstIndSup ) . ' )' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

            $db->transactionCommit();
            return $ban;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
        }
    }

    /**
     * 
     * Registro una linea base de tipo supuesto
     * 
     * @param type $dtaLineaBase    Datos de la linea base prorrateada
     * 
     * @return type
     */
    private function _registroLBSupuesto( $dtaLineaBase )
    {
        $dtaLB["intCodigo_fuente"]      = $dtaLineaBase->idFuente;
        $dtaLB["strDescripcion_lbind"]  = ( is_null( $dtaLineaBase->descripcion ) ) 
                                                ? $dtaLineaBase->nombre
                                                : $dtaLineaBase->descripcion;

        $dtaLB["dcmValor_lbind"]        = $dtaLineaBase->valor;
        $dtaLB["dteFechaInicio_lbind"]  = $dtaLineaBase->fchInicio;
        $dtaLB["dteFechaFin_lbind"]     = $dtaLineaBase->fchFin;
        $dtaLB["intTpoLB_lbind"]        = ( is_null( $dtaLineaBase->tpoLB ) ) 
                                                ? 1
                                                : $dtaLineaBase->tpoLB;
        
        if( !$this->save( $dtaLB ) ){
            $this->getError();
            exit;
        }

        return $this->intCodigo_lbind;
    }

    /**
     * 
     * Gestiona el registro de asociacion entre una linea base Indicador 
     * Supuesto
     * 
     * @param type $idIndicador     Identificador Indicador
     * @param type $idLBSupuesto    Identificador Linea Base Supuesto
     * 
     * @return type
     * 
     */
    private function _relacionLBSupIndicador( $idIndicador, $idLBSupuesto )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();

            $sql = 'INSERT INTO #__ind_linea_base_indicador( intCodigo_lbind, intCodigo_ind ) VALUES';
            $sql .= '( '. $idLBSupuesto .', '. $idIndicador .' )';

            $db->setQuery( trim( $sql, ' ' ) );
            $db->query();

            $db->transactionCommit();
            
            return $db->getAffectedRows();
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Elimino las lineas base asociadas a un determinado indicador
     * 
     * @param type $lstLineasBase   Lista de Lineas Base
     * @return type
     */
    public function delLineasBase( $lstLineasBase )
    {
        $lstLB = array();
        foreach( $lstLineasBase as $lb ){
            $lstLB[] = $lb->idLineaBase;
        }

        //  Elimino la Relacion de las Lineas Base con los indicadores
        $this->_delRelLBSup( $lstLB );

        //  Elimino las Lineas Base
        $this->_delLBSup( $lstLB );

        return;
    }
    
    
    public function deleteLineaBase( $idIndicador )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_linea_base_indicador ' );
            $query->where( ' intCodigo_ind IN ( '. $idIndicador .' )' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE   
                                                    : FALSE;

            $db->transactionCommit();

            return $ban;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }       
    }

    
    public function getDtaLineaBase( $idIndEntidad )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t3.dcmValor_lbind               AS valorLB,
                                t1.dteHorizonteFchInicio_indEnt AS fchInicio,
                                t1.dteHorizonteFchFin_indEnt    AS fchFin,
                                t1.intIdIEPadre_indEnt			AS idIndPadre' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_linea_base_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->join( 'INNER', '#__ind_linea_base t3 ON t2.intCodigo_lbind = t3.intCodigo_lbind' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            $query->where( 't1.dteHorizonteFchInicio_indEnt = t3.dteFechaInicio_lbind' );
            $query->where( 't1.dteHorizonteFchFin_indEnt = t3.dteFechaFin_lbind' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $dtaLB = ( $db->getNumRows() > 0 )? $db->loadObject()
                                                : FALSE;

            return $dtaLB;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }    
    }
    
    
    public function __destruct()
    {
        return;
    }
}