<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'EstadoEntidad.php';

/**
 * Description of EstadoEntidad
 *
 * @author carlos_rodriguez
 */
class EstadoEntidad
{

    public function __construct()
    {
        return;
    }
    
    
    public function gestionEstadoEntidad( $idEntidad, $idEstEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador
        $tbEstEntidad = new JTableEstadoEntidad( $db );

        $dtaEstadoEntidad["intIdEntidad_ent"]   = $idEntidad;
        $dtaEstadoEntidad["intIdEstadoEnt_ee"]  = $idEstEntidad; 
        $dtaEstadoEntidad["dtFchRegistro_ee"]   = date("Y-m-d H:i:s");
        
        $idRegEstEntidad = $tbEstEntidad->registrarEstadoEntidad( $dtaEstadoEntidad );
        
        return $idRegEstEntidad;
    }
    
    
    public function getUltimoEstadoVigente( $idEntidad )
    {
        try {
            $rstEstadoEntidad = new stdClass();
            
            if( $idEntidad ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );

                $query->select( '   t1.intIdEstado_ee   AS idEstadoEntidad, 
                                    t1.intIdEntidad_ent AS idEntidad, 
                                    t1.dtFchInicio_ee   AS fchInicio, 
                                    t1.dtFchRegistro_ee AS fchRegistro' );
                $query->from( '#__gen_estado_entidad t1' );
                $query->where( 't1.intIdEntidad_ent = '. $idEntidad );
                $query->where( 't1.intVigencia_ee = 1' );

                $db->setQuery( (string) $query );
                $db->query();

                $rstEstadoEntidad = ( $db->getNumRows() > 0 )   ? $db->loadObject()
                                                                : new stdClass();
            }

            return $rstEstadoEntidad;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}