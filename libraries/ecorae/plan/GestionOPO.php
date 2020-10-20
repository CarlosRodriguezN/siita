<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanificacionOperativa.php';

class GestionOPO
{
    public function __construct()
    {
        return;
    }

    /**
     * 
     * Generar Planes Operativos, en funcion a una 
     * 
     * @param String    $nombre
     * @param int       $idUGResponsable
     * @param int       $idUGFuncionarioR
     * @param int       $idFuncionarioR
     * @param date      $fchInicio
     * @param date      $fchFin
     * @param Float     $valor
     * 
     * @return type
     */
    public function generarPlanOperativo( $nombre, $idUGResponsable, $idUGFuncionarioR, $idFuncionarioR, $fchInicio, $fchFin, $valor )
    {
        $objOPO = new PlanificacionOperativa();

        $objOPO->setDtaPlanOperativo(   $nombre,
                                        $idUGResponsable, 
                                        $idUGFuncionarioR, 
                                        $idFuncionarioR, 
                                        $fchInicio, 
                                        $fchFin, 
                                        $valor );
        
        return $objOPO->generarPlanesOperativos();
    }
    
    /**
     * 
     * Retorno Planes Operativos asociados a una determinada Entidad
     * 
     * @param int $idEntidad    Identificador de la entidad Proyectos, Contratos, Convenios
     * 
     * @return object
     * 
     */
    public function getPlanesOperativo( $idEntidad )
    {
        $objOPO = new PlanificacionOperativa();
        return $objOPO->getDtaPlanesOperativos( $idEntidad );
    }

}