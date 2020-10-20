<?php

//  Importa la tabla necesaria para hacer la gestion
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'objetivo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'objetivoentidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planobjetivo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'acciones' . DS . 'acciones.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'objetivos.php';

/**
 * Getiona el objetivo
 */
class Objetivo
{

    public function __construct()
    {
        return;
    }

    /**
     * 
     * @param object    $objetivo       Objeto OBJETIVO
     * @param int       $idPadre        Identificador de la entidad a la que 
     *                                  pertenece el objetivo
     * @return int                      Identificador del objetivo.
     */
    public function saveObjetivo( $objetivo, $idPadre )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tblObjetivo = new jTableObjetivo( $db );
            $idObj = FALSE;

            $objetivoPln["intId_ob"]                = $objetivo->idObjetivo;
            $objetivoPln["intIdPadre_ob"]           = $objetivo->idPadreObj;
            $objetivoPln["strDescripcion_ob"]       = $objetivo->descObjetivo;
            $objetivoPln["intId_tpoObj"]            = $objetivo->idTpoObj;
            $objetivoPln["intIdPrioridad_pr"]       = $objetivo->idPrioridadObj;
            $objetivoPln["dteFechamodificacion_ob"] = date( "Y-m-d H:i:s" );
            $objetivoPln["published"]               = $objetivo->published;

            if( $objetivo->idObjetivo == 0 ){
                $objetivoPln["dteFecharegistro_ob"] = date( "Y-m-d H:i:s" );
            }

            //  Registro el objetivo con el plan relacionado
            $idObj      = $tblObjetivo->registroObjPei( $objetivoPln );
            $idEntPlnObj= $objetivo->idEntidad;
            $idPlnObj   = $objetivo->idPlnObjetivo;

            //  $idPlnObj identificador que relaciona el plan con el objetivo
            if( $idObj && $idPlnObj == 0 ){
                $idEntPlnObj = ( int ) $this->registroEntidad();
                $idPlnObj = $this->registroPlanObjetivo(    $objetivo->idPlnObjetivo, 
                                                            $idObj, 
                                                            $idPadre, 
                                                            $idEntPlnObj );
            }

            if( $objetivo->idObjetivo == 0 && $idObj && $idPlnObj ){
                $idObjTpoEnt = $this->registroObjEntPlan( $idObj, $idPadre );
            }

            $dtaObjetivo["idObj"]           = $idObj;
            $dtaObjetivo["idEntidad"]       = $idEntPlnObj;
            $dtaObjetivo["idPlnObjetivo"]   = $idPlnObj;

            $db->transactionCommit();

            return ( object ) $dtaObjetivo;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     *  Gestiona el registro de una Entidad de tipo Objetivo
     *  4 es el ID de Tipo Entidad "Objetivo"
     */
    public function registroEntidad()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tblEntidad = new jTableEntidad( $db );
            $idEntidad = $tblEntidad->saveEntidad( 0, 4 );

            $db->transactionCommit();

            return $idEntidad;   //  saveEntidad retorna un nuevo ID de entidad
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     *  Eliminacion Logica de un objetivo
     * 
     *  @param type $idObj
     *  @param type $objPln
     *  @param type $idTpoEnt
     *
     *  @return type
     */
    public function delete( $idObj, $idTpoEnt )
    {
        $result = FALSE;

        $db = JFactory::getDBO();
        $tblPei = new jTableObjetivo( $db );
        $result = $tblPei->deleteObjPei( $idObj );

        if( $result ){
            switch( ( int ) $idTpoEnt ){
                case 1:
                    $tpoEnt = 12;
                    break;
                case 2:
                    $tpoEnt = 13;
                    break;
            }
            $tblPei->deleteObjEnt( $idObj, $tpoEnt );
        }
        return $result;
    }

    /**
     *  Ejecuta la eliminacion fisica de un objetivo
     * @param type $idObj
     * @param type $idTpoEnt
     * @return type
     */
    public function eliminadoFisicoObj( $idObj, $idTpoEnt )
    {
        $db = JFactory::getDBO();
        $tblObj = new jTableObjetivo( $db );
        $result = $tblObj->eliminarRegistroObj( $idObj );
        switch( ( int ) $idTpoEnt ){
            case 1:
                $tpoEnt = 12;
                break;
            case 2:
                $tpoEnt = 13;
                break;
        }
        $tblObj->eliminarRegistroObjEnt( $idObj, $tpoEnt );
        return $result;
    }

    /**
     *  Retorna la lista de objetivos 
     * @param type $idPln
     * @return type
     */
    public function getObjetivos( $idPln, $idEntFnc, $idTpoPln, $fchInicioPln = null, $fchFinPln = null )
    {
        $result = array();
        $db = JFactory::getDBO();
        $tblObjetivo = new jTableObjetivo( $db );

        if( $tblObjetivo ){
            $result = $tblObjetivo->getLstObjetivos( $idPln, $idEntFnc, $idTpoPln, $fchInicioPln, $fchFinPln );
        }
        return $result;
    }

    /**
     * 
     * Registro la relacion entre un determinado plan con un determinado Objetivo
     * 
     * @param type $idPlnObj    Identificador del Plan Objetivo
     * @param type $idObj       Identificador del Objetivo
     * @param type $idPlan      Identificador del Plan al que pertenece
     * @param type $idEntObj    Identificador de la Entidad Objetivo
     * @param type $idPadre     Identificador del objetivo padre al que pertenece
     * 
     * @return type
     * 
     */
    function registroPlanObjetivo( $idPlnObj, $idObj, $idPlan, $idEntObj, $idPadre = null )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tblPlnObj = new jTablePlanObjetivo( $db );

            $dtaPlnObj["intId_pln_objetivo"]= ( !empty( $idPlnObj ) )  
                                                    ? $idPlnObj 
                                                    : 0;

            $dtaPlnObj["intId_ob"]          = $idObj;
            $dtaPlnObj["intId_pi"]          = $idPlan;
            $dtaPlnObj["intIdentidad_ent"]  = $idEntObj;
            $dtaPlnObj["intIdPadre"]        = $idPadre;
            
            $idPObj = $tblPlnObj->registroPlnObj( $dtaPlnObj );

            $db->transactionCommit();

            return $idPObj;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Registra informacion de los objetivos prorrateados
     * 
     * @param Object    $dtaObjetivo     Datos del Objetivo prorrateado ( Alineacion, Indicadores, Acciones, etc )
     * @param Object    $dataObj         Datos de Registro del Objetivo ( identificador del objetivo, idEntidad, etc )
     * @param int       $idTpoEntidad    Datos de Registro del Objetivo ( 1: PEI,  )
     * 
     * @return type
     */
    public function registroObjetivosProrrateados( $dtaObjetivo, $dataObj, $idTpoEntidad = 1 )
    {
        //  Registra las ACCIONES de un objeto cuyo id es $idPadre
        if( count( ( array ) $dtaObjetivo->lstAcciones ) ){
            $oAcciones = new Acciones();
            $dtaObjetivo->lstAcciones = $oAcciones->saveAcciones( $dtaObjetivo->lstAcciones, $dataObj );
        }

        //  Registro Informacion de INDICADORES
        if( count( (array) $dtaObjetivo->lstIndicadores ) > 0 ){
            
            //  Registro indicadores asociados a un objetivo OEI
            foreach( $dtaObjetivo->lstIndicadores as $dtaIndObjs ){
                //  Gestion de Indicadores generados a partir del prorrateo de un indicador
                $objIndicador = new Indicador( 1 );
                $dtaIndObjs->idIndEntidad = $objIndicador->registroIndicador(   $dataObj->idEntidad, 
                                                                                $dtaIndObjs );
            }
        }

        return;
    }

    /**
     * Retorna Informacion general de un determinado Objetivo
     * @param type $idObjetivo  Identificador del Objetivo
     * @return type
     */
    public function getDtaObjetivo( $idObjetivo )
    {
        $db = JFactory::getDBO();
        $tblPei = new jTableObjetivo( $db );
        return $tblPei->getDtaObjetivo( $idObjetivo );
    }

    /**
     * Retorno todos los indicadores de una determinada entidad
     * @param type $idIndEntidad    Identificador de un Indicador Entidad
     * @return type
     */
    public function getLstIndicadores( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        $tbIndicador = new jTableIndicador( $db );
        return $tbIndicador->getDataIndicadores( $idIndEntidad );
    }

    /**
     * Retorno todas las acciones
     * @param type $idAccion    Identificador de la Accion
     * @return type
     */
    public function getLstAcciones( $idAccion )
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );
        return $tbPlanAccion->getPlanAccion( $idAccion );
    }

    /**
     *  Guarda el registro del objetivo para la alineacion de los objetivos internos
     * 
     * @param type $idObj       Id del Objetivo
     * @param type $idPln       Id del Plan al que pertinece el onjetivo
     * @return type
     */
    public function registroObjEntPlan( $idObj, $idPln )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tblObjEntidad = new jTableObjetivoEntidad( $db );

            $idTpoPlan = ( int ) $tblObjEntidad->getTpoEntidad( $idPln );

            if( $idTpoPlan == 1 || $idTpoPlan == 2 ){
                switch( ( int ) $idTpoPlan ){
                    case 1:
                        $tpoEnt = 12;
                        break;
                    case 2:
                        $tpoEnt = 13;
                        break;
                }
                $result = $this->registroObjTpoEnt( $idObj, $tpoEnt );
            }

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     *  Retorna el id de registro de un nuevo objetivo entidad para la alineacion 
     * @param type $idObj       Id del Objetivo
     * @param type $tpoEnt      Id del tipo de entidad a al que pertenece
     * @return type
     */
    public function registroObjTpoEnt( $idObj, $tpoEnt )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tblObjEntidad = new jTableObjetivoEntidad( $db );

            $dtaObjEnt["intId_obj_ent"]     = 0;
            $dtaObjEnt["intId_objetivo"]    = $idObj;
            $dtaObjEnt["intId_tpoEntidad"]  = $tpoEnt;
            $dtaObjEnt["published"]         = 1;

            $result = $tblObjEntidad->registroObjEnt( $dtaObjEnt );

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     *  Retorna True para una eliminacion logica del registro, caso contrario 
     * retorna False si NO existe almenos una relacion con el Plan y se puede 
     * eliminar el registro fisicamente
     * 
     * @param type $idPlan
     */
    public function getExistenciaRelacionPlan( $idPlan )
    {
        $result = True;
        $db = JFactory::getDBO();
        $tblObjetivo = new jTableObjetivo( $db );
        if( $tblObjetivo ){
            $result = $tblObjetivo->getRegsRelacionPln( $idPlan );
        }
        return $result;
    }

    //////////      GESTION - ELIMINACION LOGICA Y FISICA DE UN OBJETIVO    //////////

    /**
     *  Retorna True para una eliminacion logica del registro, caso contrario 
     * retorna False si NO existe almenos una relacion con el Objetivo y se puede 
     * eliminar el registro fisicamente
     * 
     * @param type $idObjetivo
     */
    public function controlEliminarObj( $idObjetivo )
    {
        $result = True;
        $db = JFactory::getDBO();
        $tblObjetivo = new jTableObjetivo( $db );

        if( $tblObjetivo ){
            $ind = $tblObjetivo->getRegsObjInds( $idObjetivo );
            $act = $tblObjetivo->getRegsObjActs( $idObjetivo );
            $acc = $tblObjetivo->getRegsObjAccs( $idObjetivo );

            $result = ( $ind || $act || $acc ) ? True : False;
        }
        return $result;
    }
    
    
    /**
     * 
     * Eliminado logico de los Objetivos asociados a un determinado plan
     * 
     * @param int $idObjetivo  Identificador del Objetivo
     * @param int $idPlan      Identificado del plan al que pertenece
     * 
     * @return type
     * 
     */
    public function eliminadoLogicoObj( $idObjetivo )
    {
        $db = JFactory::getDBO();
        $tblPei = new jTableObjetivo( $db );

        return $tblPei->eliminadoLogicoObjetivo( $idObjetivo );
    }
    
    
    private function _getLstPlnObjetivoAsociado( $idObjetivo, $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPI = new jTablePlanInstitucion( $db );
        
        return $tbPI->getDtaPlanObjetivo( $idObjetivo, $idPlan );
    }
    
    

}
