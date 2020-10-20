<?php

//  Importa la tabla necesaria para hacer la gestion

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivosOperativos' . DS . 'tables' . DS . 'objetivoOperativo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
// Adjunto libreria de gestion de Indicador
jimport('ecorae.objetivos.objetivo.objetivo');
jimport('ecorae.objetivosOperativos.alineacionOperativa');
jimport('ecorae.objetivosOperativos.planAccionOperativo');
jimport('ecorae.objetivosOperativos.objetivoEntidad');
jimport('ecorae.entidad.entidad');

/**
 * Getiona el objetivo
 */
class ObjetivoOperativo {

    public function __construct() {
        return;
    }

    /**
     * 
     * @param JSON $data        Informacion del objetivo
     * @param Int  $idEntidadOwner   Identificador de la entidad
     * @return type
     */
    public function saveObjetivoOperativo($data, $idEntidadOwner) {
        $db = JFactory::getDBO();
        $tbObjOpe = new jTableObjetivoOperativo($db);
        if ($data->idEntidad == 0) {
            // creo una nueva entidad del objetivo
            $data->idEntidad = $this->_registroEntidadObjOperativo();
        }

        //  Guardo los objetivos
        $idObjOpe = $tbObjOpe->saveObjetivoOperativo($data, $idEntidadOwner);
        $entidad = $this->_getEntidad($idEntidadOwner);

        // Almaceno en la tabla "tb_gen_objetivo_entidad"
        if ($data->idObjetivo == 0) {
            $this->_saveObjEnt($idObjOpe, $entidad->idTipoEntidad, $data->published);
        } else {
            $this->_updObjEnt($idObjOpe, $entidad->idTipoEntidad, $data->published);
        }

        // Gestiono el registro de los indicadores de un objetivo.
        if ($data->lstIndicadores) {
            $this->_saveIndicadoresObjetivo($data->lstIndicadores, $data->idEntidad);
        }

        // GESTIONO las ALINEACIONES
        if ($data->lstAlineaciones) {

//            echo 'dtaLstAlineaciones: '. json_encode( $data->lstAlineaciones ). '<br>';
//            echo 'paramAlineaciones: '. $idObjOpe. ' / ' .$entidad->idTipoEntidad .'<br>';
//            echo 'dtaEntidad: <br>';
//            echo json_encode( $data );
//            echo '<hr>';

            $this->_saveAlineacionObjetivo( $data->lstAlineaciones, $idObjOpe, $entidad->idTipoEntidad );
        }

        // Gestiono el plan de accion
        if ($data->lstAcciones) {
            $this->_savePlanAccionObjetivo($data->lstAcciones, $idObjOpe, $entidad->idTipoEntidad);
        }


        return $idObjOpe;
    }

    private function _savePlanAccionObjetivo( $lstAcciones, $idObjOpe ) {
        if (count($lstAcciones) > 0) {
            $oplnAccion = new PlanAccionOperativo();
            $oplnAccion->savePlanAccionOperativo( $lstAcciones, $idObjOpe );
        }
    }
    
    /**
     * GESTIONA el REGISTRO de la ALINEACION de un OBJETIVO
     * @param type $lstAlineaciones Lista de alineaciones
     * @param type $idObjOpe        identificador del objetivo operativo
     * @param type $entidad         TIPO entidad a la que pertenece el objetivo que se esta alineando
     */
    private function _saveAlineacionObjetivo($lstAlineaciones, $idObjOpe, $tpEntidad) {
        if (count($lstAlineaciones) > 0) {
            $oAlineacion = new AlineacionOperativa();
            $oAlineacion->saveAlineacionesOperativas($lstAlineaciones, $idObjOpe, $tpEntidad);
        }
    }

    /**
     *  RECUPERA la informacion de la entidad
     * @param type $idEntidad
     * @return type
     */
    private function _getEntidad($idEntidad) {
        $entidad = new Entidad();
        $oEntidad = $entidad->getEntidad($idEntidad);
        return $oEntidad;
    }

    /**
     *  GESTIOBA la información en la tabla "tb_gen_objetivo_entidad"
     * @param type $idObjetivo
     * @param type $idEntidadOwn
     */
    private function _updObjEnt($idObjetivo, $idEntidadOwn, $published) {
        $objEnt = new ObjetivoEntidad();
        $objEnt->updObjEnt($idObjetivo, $idEntidadOwn, $published);
    }

    private function _saveObjEnt($idObjetivo, $idEntidadOwn, $published) {
        $objEnt = new ObjetivoEntidad();
        $objEnt->saveObjEnt($idObjetivo, $idEntidadOwn, $published);
    }

    /**
     * Registo al objetivo para que este disponible en la seleccion
     * @return type
     */
    private function _registroEntidadObjOperativo() {
        $db = JFactory::getDBO();
        $tblEntidad = new jTableEntidad($db);
        return $tblEntidad->saveEntidad(0, 4);   //  TIPO OBJETIVO
    }

    /**
     * Almaceno los indicadores del objetivo Operativo
     * @param type $lstIndicadores  Lista de indicadores
     * @param type $idEntidad       identificador de la entidad a la que pertenece el objetivo
     * @param type $idObjOper       Identificador del objetivo operativo
     */
    private function _saveIndicadoresObjetivo($lstIndicadores, $idEntidad) {
        if ($lstIndicadores) {
            foreach ($lstIndicadores AS $indicador) {
                $objIndicador = new Indicador();
                $indicador->idCategoria = 0;
                $objIndicador->registroIndicador( $idEntidad, $indicador, 0 );
            }
        }
    }

    /**
     * RECUPERA la lista de los objetivos operativos de una entidad.
     * @param type $idEntidad
     * @return type
     */
    public function getObjetivosOperativos($idEntidad) {
        $db = JFactory::getDBO();
        $tbObjOpe = new jTableObjetivoOperativo($db);
        $lstObjOpe = $tbObjOpe->getObjetivosOperativos($idEntidad);

        if ($lstObjOpe) {
            foreach ($lstObjOpe AS $key => $objetivo) {
                $objetivo->registroObj = $key;
            }

            //  INDICADORES
            $this->_getIndicadoresObjetivos($lstObjOpe);

            //  ALINEACIÓN
            $this->_getAlineacionObjetivo($lstObjOpe, $idEntidad);
            
            //  PLAN DE ACCION
            $this->_getPlanAccionObjetivo( $lstObjOpe );
        }
        
        return $lstObjOpe;
    }

    /**
     * Recupera los indicadores de un objetivo
     * @param type $lstObjOpe
     * @see getObjetivosOperativos()
     */
    private function _getIndicadoresObjetivos($lstObjOpe)
    {
        if ($lstObjOpe) {
            foreach ($lstObjOpe AS $key => $objetivo) {
                $objIndicador = new Indicadores();
                $objetivo->lstIndicadores = $objIndicador->getLstOtrosIndicadores( $objetivo->idEntidad );
            }
        }
        
        return;
    }

    /**
     * 
     * @param type $lstObjOpe
     * @param type $idEntidad
     */
    private function _getAlineacionObjetivo($lstObjOpe, $idEntidad) {
        if ($lstObjOpe) {
            foreach ($lstObjOpe AS $objetivo) {
                $objAlineacion = new AlineacionOperativa();
                $oEntidad = new Entidad();
                $entidad = $oEntidad->getEntidad($idEntidad);
                $objAlineacion->getAlineacionOperativa($objetivo, $entidad->idTipoEntidad);
            }
        }
    }

    private function _getPlanAccionObjetivo( $lstObjOpe ) {
        if ($lstObjOpe) {
            foreach ($lstObjOpe AS $key => $objetivo) {
                $oplnAccion = new PlanAccionOperativo();
                $oplnAccion->getPlanAccionOperativo( $objetivo );
            }
        }
    }
}
