
<?php

//  Importa la tabla necesaria para hacer la gestion
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'planinstitucion.php';
;

class Funcionario {

    public function __construct() {
        
    }

    public function getActividadesFuncionarioUG($idUnidadGestion) {
        $lstFuncionarios = $this->lstFuncionariosPorUG($idUnidadGestion);
        foreach ($lstFuncionarios as $funcionario) {
            $lstPoas = $this->getlstPoasByFuncionario($funcionario);
            foreach ($lstPoas AS $poa) {
                $oObjetivo = new Objetivo();
                $funcionario->lstObjetivos[] = $oObjetivo->getLstObjetivos($poa->idPln, 2);
            }
        }
    }

    /**
     * Retorna el pei Vigente
     * 
     * 
     * @return type
     */
    public function getlstPoasByFuncionario($funcionario) {
        $oPlan = PlanInstitucion();
        $oPlanVige = $oPlan->getPeiVigente();

        $lstPPPP = $oPlan->getLstPlanesByTpo($oPlanVige->idPln, 3);
        $lstPoas = array();
        foreach ($lstPPPP AS $pppp) {
            $lstPoas[] = $oPlan->getLstPlanesByTpo($pppp->idPln, 2, $funcionario->idEntidad);
        }
        return $lstPoas;
    }

    /**
     * 
     * @param type $idUnidadGestion
     * @return type
     */
    public function lstFuncionariosPorUG($idUnidadGestion) {
        $result = FALSE;
        $db = JFactory::getDBO();
        $tblPei = new JTableUnidadFuncionario($db);
        $result = $tblPei->lstFuncionariosPorUG($idUnidadGestion);
        return $result;
    }

    /**
     *  Cambia de funcionario responsable en Programas, Proyectos, Contratos y Convenios
     * @param type $idFncUGOld          ID de 
     * @param type $idFncUGNew
     */
    public function changeFuncionarioResPPCC( $idFncUGOld, $idFncUGNew, $fchInicioNew )
    {
        $this->changeFuncionarioResPrg($idFncUGOld, $idFncUGNew, $fchInicioNew);
        $this->changeFuncionarioResPry($idFncUGOld, $idFncUGNew, $fchInicioNew);
        $this->changeFuncionarioResCtrCnv($idFncUGOld, $idFncUGNew, $fchInicioNew);
    }
    
    /**
     *  Cambia de funcionario responsable en Programas
     * @param type $idFncUGOld
     * @param type $idFncUGNew
     */
    public function changeFuncionarioResPrg( $idFncUGOld, $idFncUGNew, $fchInicioNew )
    {
        $db = JFactory::getDBO();
        $tbFncUG = new JTableUnidadFuncionario($db);
        $lstProgramasFR = $tbFncUG->getProgramasByFR( $idFncUGOld );
        if ( !empty($lstProgramasFR) ){
            foreach ($lstProgramasFR AS $prgFR){
                //  Actualizar la fecha de fin de gestion y la vigencia del antiguo Funcionario responsable
                $fecha = ( $prgFR->fechaInicio > date("Y-m-d") ) ? date("Y-m-d") : $prgFR->fechaInicio;
                $tbFncUG->actualizarFinFncRspPrograma( $prgFR->idPrgFR, $fecha, 0 );
                
                //  Registrar la nueva relacion del proyecto con el nuevo funcionario responsable
                $tbFncUG->registrarFncRspPrograma( $prgFR->idPrg, $idFncUGNew, $fchInicioNew );
            }
        }
    }
    
    /**
     *  Cambia de funcionario responsable en Proyectos
     * @param type $idFncUGOld
     * @param type $idFncUGNew
     */
    public function changeFuncionarioResPry( $idFncUGOld, $idFncUGNew, $fchInicioNew )
    {
        $db = JFactory::getDBO();
        $tbFncUG = new JTableUnidadFuncionario($db);
        $lstProyectosFR = $tbFncUG->getProyectosByFR( $idFncUGOld );
        if ( !empty($lstProyectosFR) ){
            foreach ($lstProyectosFR AS $pryFR){
                //  Actualizar la fecha de fin de gestion y la vigencia del antiguo Funcionario responsable
                $fecha = ( $pryFR->fechaInicio > date("Y-m-d") ) ? date("Y-m-d") : $pryFR->fechaInicio;
                $tbFncUG->actualizarFinFncRspPry($pryFR->idPryFR, $fecha, 0);
                
                //  Registrar la nueva relacion del proyecto con el nuevo funcionario responsable
                $tbFncUG->registrarFncRspPry( $pryFR->idPry, $idFncUGNew, $fchInicioNew );
            }
        }
    }
    
    /**
     *  Cambia de funcionario responsable en Contratos o Convenios
     * @param type $idFncUGOld 
     * @param type $idFncUGNew
     */
    public function changeFuncionarioResCtrCnv( $idFncUGOld, $idFncUGNew)
    {
        $db = JFactory::getDBO();
        $tbFncUG = new JTableUnidadFuncionario($db);
        $lstCtrsCnvsFR = $tbFncUG->getCtrsCnvsByFR( $idFncUGOld );
        if ( !empty($lstCtrsCnvsFR) ){
            foreach ($lstCtrsCnvsFR AS $ctrORcnv){
                //  Actualizar la fecha de fin de gestion y la vigencia del antiguo Funcionario responsable
                $fecha = ( $ctrORcnv->fechaInicio > date("Y-m-d") ) ? date("Y-m-d") : $ctrORcnv->fechaInicio;
                $tbFncUG->actualizarFinFncRspCtrsCnvs($ctrORcnv->idPryFR, $fecha, 0);
                
                //  Registrar la nueva relacion del proyecto con el nuevo funcionario responsable
                $tbFncUG->registrarFncRspCtrsCnvs( $ctrORcnv->idPry, $idFncUGNew, $fchInicioNew );
            }
        }
    }
    
    /**
     *  Retorna el objeto sin funcionario de una determinada unidad de gestion
     * @param type $idUG        ID de la unidad de gestion
     */
    public function getSinFunionarioUG( $idUG )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadFuncionario($db);
        $result = $tbUG->getSinFunionarioUG( $idUG );
        return $result;
    }
 
    /**
     *  retorna la entidad de un funcionario
     * @param type $idFnc
     * @return type
     */
    public function getEntidadFnc( $idFnc )
    {
        $db = JFactory::getDBO();
        $tbFnc = new JTableUnidadFuncionario($db);
        $result = $tbFnc->getEntidadFuncionario( $idFnc );
        return $result->idEntidadFun;
    }
    
}
?>
