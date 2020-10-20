<?php

jimport('ecorae.entidad.entidad');
jimport('ecorae.unidadgestion.unidadgestion');
jimport('ecorae.unidadgestion.funcionarios');

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'organigrama' . DS . 'tables' . DS . 'descripcion.php';

/**
 * Getiona el objetivo
 */
class Organigrama {

    public function __construct() {
        return;
    }

    /**
     *
     * RECUPERA el organigrama
     *  
     * @param type $idEntidad   Identificador de el entidad
     * @return type
     */
    public function getOrganigrama($idEntidad) {
        $oEntidad = new Entidad();
        $entidad = $oEntidad->getEntidad($idEntidad);
        $organigrama = false;
        switch ( (int) $entidad->idTipoEntidad ) {
            case 1:// Programa
                $organigrama = $this->_getOrganigramaPry($idEntidad, 8);
                break;
            case 3:// Contrato
                $oContrato = $this->_getContratoByEntidad($idEntidad);
                if ($oContrato->intCodigoTipo_ins == 1) {
                    $organigrama = $this->_getOrganigramaUG($idEntidad, 7);
                } else {
                    $organigrama = $this->_getOrganigramaPry($idEntidad, 8);
                }
                break;
            case 8://PEI
                $organigrama = $this->_getOrganigramaPei($idEntidad, 8);
                break;
            case 7:// unidad gestion
                $organigrama = $this->_getOrganigramaUG($idEntidad, 7);
                break;
        }
        return $organigrama;
    }

    /**
     * 
     * @param type $idEntidad
     */
    private function _getContratoByEntidad($idEntidad) {
        $db = JFactory::getDBO();
        $tbDesc = new jTableDescripcionOrg($db);
        $oDesc = $tbDesc->getContratoByEntidad($idEntidad);
        return $oDesc;
    }

    /**
     * 
     * RECUPERA el organigrama de un proyecto.
     * 
     * @param type $idEntidad
     * @param type $tipoEntidad
     */
    private function _getOrganigramaPry($idEntidad, $tipoEntidad) {
        $institucion = $this->_getInstitucion();
        if ($institucion) {
            $institucion->tpoEntidad = $tipoEntidad;
            $this->_addInstitucionUG($institucion);
        }

        return $institucion;
    }

    /**
     * 
     * RECUPERA el organigrama desde el nivel de una unidad de gestion.
     * 
     * @param type $idEntidad       idetificador de la entidad de un plan.
     * @param type $tipoEntidad     tipo de la entidad plan.
     */
    private function _getOrganigramaPei($idEntidad, $tipoEntidad) {
        $plan = $this->_getPlan($idEntidad);
        $institucion = array();

        if ($plan) {
            $institucion = $this->_getInstitucion($plan->intCodigo_ins);
            $institucion->tpoEntidad = $tipoEntidad;

            if ($institucion) {
                $this->_addInstitucionUG($institucion);
            }
        }

        return $institucion;
    }

    /**
     * 
     * @param type $institucion
     */
    private function _addInstitucionUG($institucion) {
        $lstUndGestion = $this->_getLstUndGestion($institucion->idInstitucion);
        $institucion->lstUndGest = array();
        if (count($lstUndGestion) > 0) {
            foreach ($lstUndGestion AS $undGestion) {
                $undGestion->lstHijos = $this->_getUGHijos( $undGestion->intCodigo_ug, 7 );
                $undGestion->tpoEntidad = 7;
                $undGestion->lstFuncionarios = $this->_getFunctionarios($undGestion);
                $institucion->lstUndGest[] = $undGestion;
            }
        }
    }
    
    /**
     *  Carga las unidades de gestion hijas y los funcionarios de un adeterminada unidad de gestion
     * @param type $codigoUG
     * @param type $tpoEntidad
     * @return type
     */
    private function _getUGHijos( $codigoUG, $tpoEntidad )
    {
        $oUndGes = new UnidadGestion();
        $undGesHijos = $oUndGes->getUGHijos($codigoUG);
        if ( count($undGesHijos) > 0 ) {
            foreach ( $undGesHijos AS $ugh ){
                $ugh->lstHijos = $this->_getUGHijos( $ugh->intCodigo_ug, $tpoEntidad );
                $ugh->tpoEntidad = $tpoEntidad;
                $ugh->idEntidad = $ugh->intIdentidad_ent;
                $ugh->lstFuncionarios = $this->_getFunctionarios($ugh);
            }
        }
        return $undGesHijos;
    }
    
    /**
     * Recupera el plan degun el id de la entidad.
     * @param type $idEntidad   identificador de la entidad.
     * @return type
     */
    private function _getPlan($idEntidad) {
        $db = JFactory::getDBO();
        $tbDesc = new jTableDescripcionOrg($db);
        $oDesc = $tbDesc->getPLan($idEntidad);
        return $oDesc;
    }

    /**
     * Recupera la institucion por el id de la institucion
     * @param type $idInstitucion identificador de la institucion 282= ecorae 
     * @return type
     */
    private function _getInstitucion($idInstitucion = 1) {
        $db = JFactory::getDBO();
        $tbDesc = new jTableDescripcionOrg($db);
        $oDesc = $tbDesc->getInstitucion($idInstitucion);
        return $oDesc;
    }

    /**
     * 
     * Recupera la lista de unidades de gestion
     * 
     * @return type
     */
    private function _getLstUndGestion($idInstitucion) {
        $db = JFactory::getDBO();
        $tbDesc = new jTableDescripcionOrg($db);
        $oDesc = $tbDesc->getUnidadesGestion($idInstitucion);
        return $oDesc;
    }

    /**
     * 
     * RECUPERA el organigrama de una unidad de gestion
     * 
     * @param type $idEntidad       idetnficador de la entidad
     * @param type $tipoEntidad     Tipo de la entidad ug 7
     * @return type
     */
    private function _getOrganigramaUG($idEntidad, $tipoEntidad) {
        $oUndGes = new UnidadGestion();
        $undGes = $oUndGes->getUnidadGestionByEntidad($idEntidad);
        if ( $undGes ) {
            $undGes->lstHijos = array();
            $undGesHijos = $oUndGes->getUGHijos($undGes->intCodigo_ug);
            if (count($undGesHijos) > 0 ) {
                foreach ( $undGesHijos AS $ugh ) {
                    $ugh->lstHijos = $this->_getUGHijos( $ugh->intCodigo_ug, $tipoEntidad );
                    $ugh->idEntidad = $ugh->intIdentidad_ent;
                    $ugh->lstFuncionarios = $this->_getFunctionarios($ugh);
                }
                $undGes->lstHijos = $undGesHijos;
            }
            $undGes->lstFuncionarios = $this->_getFunctionarios($undGes);
            $undGes->tpoEntidad = $tipoEntidad;
        }
        
        return $undGes;
    }

    /**
     * 
     * Recupera los funcionarios de una UNIDAD de GESTION  
     * 
     * @param type $undGes
     */
    private function _getFunctionarios($undGes) {
        $oUndGes = new Funcionarios();
        $lstUndGes = $oUndGes->lstFuncionariosPorUG($undGes->intCodigo_ug);
        return $lstUndGes;
    }

    /**
     * 
     */
    public function getOrganigramaPrograma() {
        
    }

}
