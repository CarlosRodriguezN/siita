<?php

//  
jimport( 'ecorae.objetivos.objetivo.actividades.actividad' );

class Actividades
{

    /**
     *  Gestiona la lista de actividades.
     * @param   object    $lstActividades     lista de actividades
     * @param   int       $idPlnObj         IDENTIFICADOR del PLAN-OBJETIVO
     * @param   int       $tipo                 IDENTIFICADOR del tipo
     * @return  int                           IDENTIFICADOR de la ACTIVIDAD
     * 
     */
    public function saveActividades( $lstActividades, $idPadre, $idPlnObj, $tipo, $idObjetivo )
    {
        $resultLst = array();
        if( count( $lstActividades ) > 0 ) {
            foreach( $lstActividades as $actividad ) {
                $oActividad = new Actividad();
                $idActividad = $oActividad->saveActividad( $actividad, $idPadre, $idPlnObj, $tipo, $idObjetivo );
                if ( (int)$idActividad > 0 ) {
                    $actividad->idActividad = $idActividad;
                    $actividad->lstArchivosActividad = $this->updateDataArchivos( $actividad, $idObjetivo );
                    $resultLst[(int)$actividad->registroAct] = $actividad;
                }
            }
        }
        
        return $resultLst;
    }

    /**
     *  Recupera la lista de acciones de un objetivo
     *  
     * @param int $id                  Identificador al que pertenece el Objetivo. "Id del Plan", idPei, idPoa... 
     * @param int $idPlnObj            Id. del PLAN-OBJETIVO
     * @param int $regObjetivo         Id. de registro del objetivo.
     * @param int $tipo                Tipo de entidad del lan.
     * @param int $idObjetivo          Identificador del objetivo.
     * @return type
     */
    public function getLstActividades( $id, $idPlnObj, $regObjetivo, $tipo, $idObjetivo )
    {
        $oActividad     = new Actividad();
        $lstActividades = $oActividad->getLstActividades( $id, $idPlnObj, $regObjetivo, $tipo, $idObjetivo );

        return $lstActividades;
    }
    
    /**
     *  Actualiza la informacion de los archivos de la actividad
     * @param type $actividad
     * @param type $idObjetivo
     * @return type
     */
    public function updateDataArchivos( $actividad, $idObjetivo )
    {
        $lstArchivos = array();
        if ( $actividad->lstArchivosActividad ) {
            foreach( $actividad->lstArchivosActividad as $archivo ) {
                $archivo->idActividad = (int)$actividad->idActividad;
                $archivo->idObjetivo = (int)$idObjetivo;
                $lstArchivos[$archivo->regArchivo] = $archivo;
            }
        } 
        
        return $lstArchivos;
    }

}

?>
