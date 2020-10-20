<?php

//  import Joomla modelform library
jimport( 'ecorae.objetivos.objetivo.acciones.acciones' );
jimport( 'ecorae.objetivos.objetivo.actividades.actividades' );
jimport( 'ecorae.objetivos.objetivo.alineacion.alineaciones' );
jimport( 'ecorae.objetivos.objetivo.objetivo' );

//  Agrego Clase de Retorna informacion Especifica de un Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

// agrego la clse ALINEACIONOPERATIVA
jimport( 'ecorae.objetivosOperativos.alineacionOperativa' );
jimport( 'ecorae.objetivosOperativos.objetivoOperativo' );

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

//  require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'AjusteObjetivosPlan.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanObjetivoAsociado.php';

class Objetivos
{

    public function __construct()
    {
        return;
    }

    /**
     * 
     * Registra los OBJETIVOS de un plan estrategico institucional
     * 
     * @param array     $lstObjetivos   Lista de objetivos 
     * @param int       $idPadre        Id del plan al que pertenecen
     * @param int       $idTpoPlan      Identificador del Tipo de PEI
     *                                      1: PEI
     *                                      2: POA
     *                                      3: Programas
     * 
     * @return type
     * 
     */
    public function saveObjetivos( $lstObjetivos, $idPadre, $idTpoPlan )
    {
        $resultLst = array();

        foreach( $lstObjetivos AS $dtaObjetivo ){            
            if( $dtaObjetivo->published == 1 ){
                //  Bandera que indica si un objetivo es nuevo
                $nuevoObjetivo = ( ( int ) $dtaObjetivo->idObjetivo == 0 )  ? true 
                                                                            : false;

                $dataObj = $this->_registroObjetivo( $dtaObjetivo, $idPadre );
                
                if( $dataObj->idObj ){
                    
                    //  GESTION DE INDICADORES
                    $dtaObjetivo->lstIndicadores = $this->_registroIndicadores( $dataObj->idEntidad,    
                                                                                $dtaObjetivo->lstIndicadores, 
                                                                                $idTpoPlan, 
                                                                                $dataObj );

                    //  GESTION de ACCIONES de un objeto cuyo id es $idPadre
                    $dtaObjetivo->lstAcciones = $this->_registroAcciones(   $dtaObjetivo->lstAcciones, 
                                                                            $dataObj, 
                                                                            $idTpoPlan );

                    // GESTION de las ALINEACIONES
                    $this->_gestionAlineacionObjetivos( $dtaObjetivo, $dataObj->idObj, $idTpoPlan );

                    //  Registro ACTIVIDADES
                    $dtaObjetivo->lstActividades = ( count( $dtaObjetivo->lstActividades ) )? $this->_registroActividades( $dtaObjetivo->lstActividades, $idPadre, $dataObj->idPlnObjetivo, $idTpoPlan, $dataObj->idObj ) 
                                                                                            : array();

                    //  Creo el directorio para los OBJETIVOS
                    $this->_makeDirObjetivo( $idPadre, $dataObj->idObj, $idTpoPlan );
                }

                $dtaObjetivo->idObjetivo    = $dataObj->idObj;
                $dtaObjetivo->idEntidad     = $dataObj->idEntidad;
                $dtaObjetivo->idPlnObjetivo = $dataObj->idPlnObjetivo;

                //  Verifico si es un nuevo objetivo
                if( $nuevoObjetivo ){
                    $this->_generarPlanesComplementarios( $dtaObjetivo, $idPadre, $idTpoPlan );
                }

                $resultLst[( int ) $dtaObjetivo->registroObj] = $dtaObjetivo;
            } else{
                if( ( int ) $dtaObjetivo->idObjetivo != 0 ){
                    $this->_deleteObj( ( int ) $dtaObjetivo->idObjetivo, $idPadre, $idTpoPlan );
                }
            }
        }

        return $resultLst;
    }

    
    private function _generarPlanesComplementarios( $dtaObjetivo, $idPadre, $idTpoPlan )
    {
        //  Instancio la clase Plan Objetivo Asociado, la cual 
        //  me permite generar planes PPPP, PAPP, POA - UG, POA - F
        $oap = new PlanObjetivoAsociado( $dtaObjetivo, $idPadre );
        
        if( $oap->getNumPlanes() ){
            $oap->crearPlanObjetivosAsociados();
        } else if( $idTpoPlan == 1 ){
            $pln = new PlanInstitucion( $idPadre );

            //  Gestiona la Creacion de "PPPP's" de un PEI
            $pln->gestionarPPPP();

            //  Gestiono la creacion de POA's por Unidad de Gestion
            $pln->gestionarPoasxUG();

            //  Gestiono la creacion de POA's por Funcionario
            $pln->gestionarPoasXFuncionarios();
        }
        
        return;
    }
    
    
    /**
     * 
     * Registro Objetivo
     * 
     * @param type $dtaObjetivo     Datos del Objetivo a registrar
     * @param type $idPadre         Identificador del Plan al que esta asociado este objetivo
     * 
     * @return type
     * 
     */
    private function _registroObjetivo( $dtaObjetivo, $idPadre )
    {
        $objetivo = new Objetivo();
        return $objetivo->saveObjetivo( $dtaObjetivo, $idPadre );
    }

    /**
     * Elimina logicamente un objetivo de planes PEI POA 
     * 
     * @param type $idObjetivo
     * @param type $idTpoPlan
     * 
     * @return type
     */
    private function _deleteObj( $idObjetivo, $idPlan, $idTpoPlan )
    {
        $objetivo = new Objetivo();

        $result = $objetivo->eliminadoLogicoObj( $idObjetivo );
        
        return $result;
    }

    /**
     * 
     * Registro de Acciones asociadas a un Objetivo
     * 
     * @param Array     $lstAcciones     Lista de Acciones asociadas a un determinado Objetivo
     * @param Object    $dataObj         Datos del Objetivo
     * @param Int       $idTpoPlan       Identificador del tipo de Plan
     * 
     * @return type
     */
    private function _registroAcciones( $lstAcciones, $dataObj, $idTpoPlan )
    {
        $acciones = array();
        if( count( ( array ) $lstAcciones ) ){
            $oAcciones = new Acciones();
            $acciones = $oAcciones->saveAcciones(   $lstAcciones, 
                                                    $dataObj, 
                                                    $idTpoPlan );
        }

        return $acciones;
    }

    /**
     * 
     * Registro de indicadores asociados a un Objetivo
     * 
     * @param int   $idEntidad           Identificador de la Entidad del Objetivo
     * @param Array $lstIndicadores      Lista de Indicadores a registrar
     * @param int   $idTpoPlan           Identificador del tipo de plan
     * 
     * @return type
     * 
     */
    private function _registroIndicadores( $idEntidad, $lstIndicadores, $idTpoPlan, $dataObj = NULL )
    {
        if( count( ( array ) $lstIndicadores ) ){
            //  Registro indicadores asociados a un objetivo OEI
            foreach( $lstIndicadores as $dtaIndObjs ){
                //  Gestion de Indicadores - "ORIGEN - PLAN ( 1 )"
                $objIndicador = new Indicador( 0, $idTpoPlan );
                $dtaIndObjs->idTpoPln = $idTpoPlan;

                $dtaIndObjs->idIndEntidad = $objIndicador->registroIndicador( $idEntidad, $dtaIndObjs, 0, $dataObj );
            }
        }

        return $lstIndicadores;
    }

    /**
     * 
     * @param type $lstActividades
     * @param type $idPadre
     * @param type $idPlnObjetivo
     * @param type $idTpoPlan
     * 
     * @return type
     */
    private function _registroActividades( $lstActividades, $idPadre, $idPlnObjetivo, $idTpoPlan, $idObj )
    {
        //  Guardar actividades
        if( count( $lstActividades ) > 0 ){
            $oActividades = new Actividades();
            $lstActividades = $oActividades->saveActividades( $lstActividades, $idPadre, $idPlnObjetivo, $idTpoPlan, $idObj );
        }

        return $lstActividades;
    }

    /**
     * 
     * Gestiono informacion de Alineacion de Objetivos 
     * 
     * @param object    $dtaObjetivo        Datos del Objetivo
     * @param int       $idObjetivo         Identificador del Objetivo
     * @param int       $idTpoEntidad       Identificador del tipo de Entidad
     * 
     */
    public function _gestionAlineacionObjetivos( $dtaObjetivo, $idObjetivo, $idTpoEntidad )
    {
        switch( true ){
            case ( $idTpoEntidad == 1 || $idTpoEntidad == 3 || $idTpoEntidad == 4 ):
                if( count( $dtaObjetivo->lstAlineaciones ) > 0 ){
                    $oAlineaciones = new Alineaciones();
                    $oAlineaciones->saveAlineaciones( $dtaObjetivo->lstAlineaciones, $idObjetivo );
                }
                break;

            case ( $idTpoEntidad = 2 ):
                if( count( $dtaObjetivo->lstAlineaciones ) > 0 ){
                    $oAlineaciones = new AlineacionOperativa();
                    $oAlineaciones->saveAlineacionesOperativas( $dtaObjetivo->lstAlineaciones, $idObjetivo, 2 );
                }
                break;
        }

        return;
    }

    /**
     * 
     * Retorna una lista de Objetivos de acuerdo a un determinado tipo
     * 
     * @param type $$plan->idPlan           Identificador del PLAN
     * @param type $idTpoEntidad    Identificador del Tipo de PEI
     *                                  1: PEI
     *                                  2: POA
     *                                  3: Programas
     * 
     * @param type $idEntFnc        Identificador de la entidad del Funcionario, por default NULL
     * 
     * @return type
     */
    public function getLstObjetivos( $idPln, $idTpoEntidad, $idEntFnc = null, $idTpoPln = null, $fchInicioPln = null, $fchFinPln = null )
    {
        $objetivo = new objetivo();

        //  Listo todos los objetivos asociados a cada plan
        $listaObj = $objetivo->getObjetivos( $idPln, $idEntFnc, $idTpoPln, $fchInicioPln, $fchFinPln );

        if( count( $listaObj ) > 0 ){
            foreach( $listaObj AS $Key => $obj ){

                $obj->registroObj = $Key;

                //  Agrega la lista de acciones de un Objetivo
                $this->_addAcciones( $obj );

                //  Agrega la lista de indicadores de un Objetivo
                $this->_addIndicadores( $obj, $idTpoEntidad, $idEntFnc );

                //  Agrega la lista de actividades asociadas a un Objetivo
                $this->_addActividades( $idPln, $obj, $idTpoEntidad );

                //  Agrega la lista de indicadores de un Objetivo
                $this->_addAlineacion( $obj, $idTpoEntidad );
            }
        }

        return $listaObj;
    }
    

    public function getLstObjUG( $idPln, $idTpoEntidad, $idEntFnc = null, $idEntUG = null )
    {
        $objetivo = new objetivo();
        $listaObj = $objetivo->getObjetivos( $idPln );

        if( count( $listaObj ) > 0 ){
            foreach( $listaObj AS $Key => $obj ){
                $obj->registroObj = $Key;

                //  Agrega la lista de indicadores de un Objetivo
                $this->_addIndicadoresUG( $obj, $idTpoEntidad, $idEntFnc, $idEntUG );

                //  Agrega la lista de acciones de un Objetivo
                $this->_addAccionesUG( $obj, $idEntUG );

                //  Agrega la lista de acitividades de un Objetivo
                $this->_addActividades( $idPln, $obj, $idTpoEntidad );

                //  Agrega la lista de indicadores de un Objetivo
                $this->_addAlineacion( $obj, $idTpoEntidad );
            }
        }

        return $listaObj;
    }

    /**
     *  Retorna la data para la vista informativa de los detalles de un funcionario
     * @param type $idPln           Identificador del PLAN
     * @param type $tpoEntidad    Identificador del Tipo de PEI
     *                                  1: PEI
     *                                  2: POA
     *                                  3: Programas
     * 
     * @param type $idEntFnc        Identificador de la entidad del Funcionario, por default NULL
     * @return type
     */
    public function getViewLstObjetivos( $idPln, $tpoEntidad, $idEntFnc = null )
    {
        $objetivo = new objetivo();
        $listaObj = $objetivo->getObjetivos( $idPln );

        if( count( $listaObj ) > 0 ){
            foreach( $listaObj AS $Key => $obj ){
                $obj->registroObj = $Key;
                //  Agrega la lista de acciones de un Objetivo
                $this->_addAcciones( $obj );
                
                //  Agrega la lista de acitividades de un Objetivo
                $this->_addActividades( $idPln, $obj, $tpoEntidad );
                
                //  Agrega la lista de indicadores de un Objetivo
                $this->_addIndicadores( $obj, $tpoEntidad, $idEntFnc );
            }
        }

        return $listaObj;
    }

    /**
     *  AGREGA la ALINEACION de el objetivo 
     * 
     *  @param object   $objetivo           Datos del Objetivo
     *  @param int      $idTipoEntidad      Identificador del Tipo de Objetivo
     * 
     */
    private function _addAlineacion( $objetivo, $idTipoEntidad )
    {
        switch( true ){
            case ( $idTipoEntidad == 7 || $idTipoEntidad == 2 ):
                $this->_addAlineacionOperativa( $objetivo, $idTipoEntidad );
                break;

            default :
                $this->_addAlineacionEstrategica( $objetivo );
                break;
        }
    }

    /**
     * Recupera la ALINEACION del objetivo de un PEI
     * @param type $objetivo
     */
    private function _addAlineacionEstrategica( $objetivo )
    {
        $alineacion = new Alineaciones();
        $alineaciones = $alineacion->getLstAlineaciones( $objetivo->idObjetivo );

        if( $alineaciones ){
            foreach( $alineaciones AS $key => $alineacion ){
                $alineacion->idRegistro = $key;
            }
        } else{
            $alineaciones = array();
        }

        $objetivo->lstAlineaciones = $alineaciones;
    }

    /**
     * AGREGA LA ALINEACION operativa en el caso de UG
     * @param type $objetivo
     * @param type $idTipoEntidad
     */
    private function _addAlineacionOperativa( $objetivo, $tpoIdEntidad )
    {
        $oAlineacion = new AlineacionOperativa();
        $oAlineacion->getAlineacionOperativa( $objetivo, $tpoIdEntidad );
    }

    /**
     *  Agrega las acctividades de un objetivo
     * 
     * @param type $objetivo     Objeto objetivo
     */
    private function _addAcciones( $objetivo )
    {
        $accion = new Accion();
        $acciones = $accion->getLstAcciones( $objetivo->idPlnObjetivo );

        if( $acciones ){
            foreach( $acciones AS $key => $accion ){
                $accion->registroAcc = $key;
            }
        } else{
            $acciones = array();
        }

        $objetivo->lstAcciones = $acciones;
    }

    private function _addAccionesUG( $objetivo, $idEntUG )
    {
        $accion = new Accion();
        $acciones = $accion->getLstAccionesUG( $objetivo->idPlnObjetivo, $idEntUG );

        if( $acciones ){
            foreach( $acciones AS $key => $accion ){
                $accion->registroAcc = $key;
            }
        } else{
            $acciones = array();
        }

        $objetivo->lstAcciones = $acciones;
    }

    /**
     *  AGREGA las ACTIVIDADES a un OBJETIVO
     * 
     * @param type $idPln           Identificador del Plan
     * @param type $objetivo        Objeto objetivo
     * @param type $tipo            tipo de entidad del plan
     */
    private function _addActividades( $idPln, $objetivo, $tipo )
    {
        $oActividad = new Actividades();
        $lstActividades = $oActividad->getLstActividades(   $idPln, 
                                                            $objetivo->idPlnObjetivo, 
                                                            $objetivo->registroObj, 
                                                            $tipo, 
                                                            $objetivo->idObjetivo );

        $objetivo->lstActividades = $lstActividades;
    }

    /**
     * 
     * Agrego Informacion de Indicadores asociados a un deteminado Objetivo
     * 
     * @param Object    $obj            Informacion de un Objetivo
     * @param int       $tpoPlan        Identificador de tipo de plan PEI
     *                                      1 = PEI, 2 = POA
     * 
     * @param int       $idEntFnc       Identificador de la entidad del Funcionario
     * 
     */
    private function _addIndicadores( $obj, $tpoPlan, $idEntFnc = null )
    {
        $indicadores = new Indicadores();
        $obj->lstIndicadores = $indicadores->getLstOtrosIndicadores(    $obj->idEntidad, 
                                                                        $tpoPlan, 
                                                                        $idEntFnc, 
                                                                        $obj->fchInicioPlan, 
                                                                        $obj->fchFinPlan );

        return;
    }

    private function _addIndicadoresUG( $obj, $tpoPlan, $idEntFnc = null, $idEntUG )
    {
        $indicadores = new Indicadores();
        $obj->lstIndicadores = $indicadores->getLstIndUG(   $obj->idEntidad, 
                                                            $tpoPlan, 
                                                            $idEntFnc, 
                                                            $idEntUG, 
                                                            $obj->fchInicioPlan, 
                                                            $obj->fchFinPlan );
        
        return;
    }

    /**
     * 
     * Crea el DIRECTORIO para los archivos de las Objetivos.
     * 
     * @param int $idPadre      Identificador del PEI|POA|PROGRAMAS.... la que pertenece el objetivo
     * @param int $idObjetivo   Identificador del OBJETIVO
     * @param int $tipo         Identificador del tipo
     *                          1 PEI
     *                          2 POA
     *                          3 PROGRAMAS
     */
    private function _makeDirObjetivo( $idPadre, $idObjetivo, $tipo )
    {
        switch( $tipo ){
            case 1:
                $dirName = 'peis';
                break;
            case 2:
                $dirName = 'poas';
                break;
            case 3:
                $dirName = 'programas';
                break;
        }
        //path=media/ecorae/docs/pei|poa...|/idPei|idPoa...|/idObjetivo
        $path = JPATH_BASE . DS . 'media' . DS . 'ecorae' . DS . 'docs' . DS . $dirName . DS . $idPadre . DS . 'objetivos' . DS . $idObjetivo;
        if( !(file_exists( $path )) ){
            mkdir( $path, 0777, true );
        }
    }

    public function getLstObjetivosPoa( $lstObjUG )
    {
        foreach( $lstObjUG AS $obj ){
            //  Agrega la lista de acciones de un Objetivo
            $this->_addAcciones( $obj );

            //  Agrega la lista de indicadores de un Objetivo
            $this->_addIndicadores( $obj );

            $lstObjetivos[] = $obj;
        }

        return $lstObjetivos;
    }

    /**
     * 
     * Listo todos los Objetivos asociados a una unidad de gestion 
     * con informacion de Indicadores y Acciones
     * 
     * @param Int $idUndGestion     Identificador de Unidad de Gestion
     * @param Int $idPlan           Identificador del plan del cual se va a 
     *                              obtener informacion
     * 
     * @return Array
     * 
     */
    public function getLstObjetivosUG( $idUndGestion, $idPlan )
    {
        $db = JFactory::getDBO();
        $tbObjetivo = new jTableObjetivo( $db );

        return $tbObjetivo->getOEIxUGxAcc( $idUndGestion, $idPlan );
    }

    /**
     * 
     * Retorno una lista de funcion
     * 
     * @param type $idFuncionario   Identificador del Funcionario
     * @param type $idPlan          Identificador del Plan
     * 
     * @return type
     * 
     */
    public function getLstObjetivosFuncionarios( $idFuncionario, $idPlan )
    {
        $db = JFactory::getDBO();
        $tbObjetivo = new jTableObjetivo( $db );

        return $tbObjetivo->getOEIxFuncionarioxAcc( $idFuncionario, $idPlan );
    }

    /**
     * 
     * Asocio un nuevo objetivos a los planes asociados a un determinado plan
     * 
     * @param Object $dtaObjetivo   Datos del Objetivo, tomando en cuenta que se tiene 
     *                              informacion adicional como: 
     *                                  idObjetivo      registrado en la BBDD
     *                                  idEntidad       de tipo objetivo
     *                                  idPlnObjetivo   Plan Objetivo
     * 
     * @param type $idPlan          Identificador del tipo de plan al que pertenece el objetivo
     * 
     * @return type
     * 
     */
    private function _ajusteObjetivos( $dtaObjetivo, $idPlan )
    {
        $objAOP = new AjusteObjetivosPlan( $dtaObjetivo, $idPlan );
        $objAOP->ajusteObjetivo();

        return;
    }

    
    public function __destruct()
    {
        return;
    }
}
