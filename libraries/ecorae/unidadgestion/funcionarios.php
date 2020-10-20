<?php

//  import Joomla modelform library
jimport( 'ecorae.objetivos.objetivo.acciones.acciones' );
jimport( 'ecorae.objetivos.objetivo.actividades.actividades' );
jimport( 'ecorae.objetivos.objetivo.objetivo' );

//  Agrego Clase de Retorna informacion Especifica de un Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

class Funcionarios
{

    public function __construct()
    {}
    
    /**
     *  Registra los OBJETIVOS de un plan estrategico institucional
     * 
     * @param array     $lstObjetivos    lista de objetivos 
     * @param int       $idPadre         id del plan al que pertenecen
     * @param int       $idTpoEntidad    Identificador del Tipo de PEI
     *                                      1: PEI
     *                                      2: POA
     *                                      3: Programas
     * 
     * @return type
     */
    public function saveObjetivos( $lstObjetivos, $idPadre, $idTpoEntidad )
    {
        $dtaLstObjetivos = ( is_array( $lstObjetivos ) )? $lstObjetivos 
                                                        : json_decode( $lstObjetivos ); 

        $objetivo = new Objetivo();

        foreach( $dtaLstObjetivos AS $dtaObjetivo ) {
            if( $dtaObjetivo->published == 1 ) {
                $dataObj = $objetivo->saveObjetivo( $dtaObjetivo, $idPadre );

                if( $dataObj->idObj ) {
                    //  Creo el directorio para los OBJETIVOS
                    $this->_makeDirObjetivo( $idPadre, $dataObj->idObj, $idTpoEntidad );

                    //  Registra las ACCIONES de un objeto cuyo id es $idPadre
                    $oAcciones = new Acciones();
                    $lstAcciones = $oAcciones->saveAcciones( $dtaObjetivo->lstAcciones, $dataObj, $idTpoEntidad );
                    $dtaObjetivo->lstAcciones = $lstAcciones;

                    //  Gestion de Indicadores
                    $objIndicador = new Indicador();
                    
                    //  Registro indicadores asociados a un objetivo OEI
                    foreach( $dtaObjetivo->lstIndicadores as $dtaIndObjs ){
                        $dtaIndObjs->idCategoria = 2;
                        $objIndicador->registroIndicador( $dataObj->idEntidad, $dtaIndObjs, $dataObj, 2 );
                    }

                    //  Guardar actividades
                    $oActividades = new Actividades();
                    $lstActividades = $oActividades->saveActividades( $dtaObjetivo->lstActividades, $idPadre, $dataObj->idObj, $idTpoEntidad );
                    $dtaObjetivo->lstActividades = $lstActividades;
                }

                $dtaObjetivo->idObejtivo = $dataObj->idObj;
                $dtaObjetivo->idEntidad = $dataObj->idEntidad;
                $dtaObjetivo->idPlnObjetivo = $dataObj->idPlnObjetivo;
            } else {
                if( (int) $dtaObjetivo->idObjetivo != 0 ) {
                    $objetivo->delete( (int) $dtaObjetivo->idObjetivo );
                }
            }
        }
        
        return $dtaLstObjetivos;
    }
    
    /**
     * 
     * Retorna una lista de Objetivos de acuerdo a un determinado tipo
     * 
     * @param type $idPei           Identificador del PEI
     * @param type $idTpoEntidad    Identificador del Tipo de PEI
     *                                  1: PEI
     *                                  2: POA
     *                                  3: Programas
     * 
     * @return type
     */
    public function getLstObjetivos( $idPei, $idTpoEntidad )
    {
        $objetivo = new objetivo();
        $listaObj = $objetivo->getObjetivos( $idPei );

        foreach( $listaObj AS $Key => $obj ) {
            $obj->registroObj = $Key;
            
            //  Agrega la lista de acciones de un Objetivo
            $this->_addAcciones( $obj );
            
            //  Agrega la lista de acitividades de un Objetivo
            $this->_addActividades( $idPei, $obj, $idTpoEntidad );
            
            //  Agrega la lista de indicadores de un Objetivo
            $this->_addIndicadores( $obj, $idTpoEntidad );

            $lstObjetivos[] = $obj;
        }
        
        return $lstObjetivos;
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

        if( $acciones ) {
            foreach( $acciones AS $key => $accion ) {
                $accion->registroAcc = $key;
            }
        } else {
            $acciones = array( );
        }

        $objetivo->lstAcciones = $acciones;
    }

    /**
     * 
     *  AGREGA las ACTIVIDADES a un OBJETIVO
     * 
     * @param int       $idPei      Identificador del 
     * @param object    $objetivo   Objeto objetivo
     */
    private function _addActividades( $idPei, $objetivo, $tipo )
    {
        $oActividad = new Actividades();
        $lstActividades = $oActividad->getLstActividades( $idPei, $objetivo->idObjetivo, $objetivo->registroObj, $tipo );

        $objetivo->lstActividades = $lstActividades;
    }
    
    /**
     * 
     * Agrego Informacion de Indicadores asociados a un deteminado Objetivo
     * 
     * @param Object $obj           Informacion de un Objetivo
     * @param int $tpoPlan          Identificador de tipo de plan PEI
     *                              1 = PEI, 2 = POA
     * 
     */
    private function _addIndicadores( $obj, $tpoPlan)
    {
        $indicadores = new Indicadores();
        $obj->lstIndicadores = $indicadores->getLstOtrosIndicadores( $obj->idEntidad, $tpoPlan );
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
        switch( $tipo ) {
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
        if( !(file_exists( $path )) ) {
            mkdir( $path, 0777, true );
        }
    }

    public function getLstObjetivosPoa( $lstObjUG ) {
        foreach( $lstObjUG AS $obj ) {
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
     * Recupera la lista de funciononarion de una unidad de gestion
     * 
     * @param type $idUnidadGestion Identificador de la unidad de gestion
     * @return type
     */
    public function lstFuncionariosPorUG($idUnidadGestion) {
        $db = JFactory::getDBO();
        $tblPei = new JTableUnidadFuncionario($db);
        $result = $tblPei->lstFuncionariosPorUG($idUnidadGestion);
        return $result;
    }

}
