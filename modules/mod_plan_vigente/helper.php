<?php

// no direct access
defined('_JEXEC') or die;
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'planinstitucion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadgestion.php';

/**
 * @package		Joomla.Site
 * @subpackage          mod_plan_vigente
 * @since		1.5
 */
class modPlanVigenteHelper {

    public function __construct() 
    {
        
    }

    public function getPEI() 
    {
        $modPlanInstitucion = new PlanInstitucion();
        $pei = $modPlanInstitucion->getPeiVigente();
        if ( is_object( $pei ) ) {
            if ( $this->validateDatePln($pei->fechaInicioPln, $pei->fechaFinPln) ) {
                $pei->color = 1;
            } else {
                $pei->color = 0;
            }
        }
        return $pei;
    }

    public function getPlanVigenteByOwner($owner, $tipoPln, $entidadOwner = 0) {
        $modplaninstitucion = new PlanInstitucion();
        $plan = $modplaninstitucion->getPlanVigente($owner, $tipoPln, $entidadOwner);
        if (is_object($plan)) {
            if ($this->validateDatePln($plan->fechaInicioPln, $plan->fechaFinPln)) {
                $plan->color = 1;
            } else {
                $plan->color = 0;
            }
        }
        return $plan;
    }

    public function validateDatePln($fInicio, $fFin) 
    {
        $result = false;
        $inicio_ts = strtotime($fInicio);
        $fin_ts = strtotime($fFin);
        $date_ts = strtotime(date('Y-m-d'));
        if (($date_ts >= $inicio_ts) && ($date_ts <= $fin_ts)) {
            $result = true;
        }
        return $result;
    }

    public function getDtaUnidadGestion( $id )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );
        $result = $tbUG->getEntidadUG( $id ); 
        return $result;
    }
    
    public function getDtaFuncionario( $id )
    {
        $db = JFactory::getDBO();
        $tbFun = new JTableUnidadFuncionario( $db );
        $result = $tbFun->getEntidadFuncionario( $id ); 
        return $result;
    }
}
