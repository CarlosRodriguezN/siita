<?php

//  
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'actividad.php';

class Actividad
{

    /**
     *  Registra la una accion de un objetivo
     * 
     * @param   Object    $actividad        Datos de la accion
     * @param   int       $idPlnObj       Idetificador del objetivo
     * @param   int       $tipo             Idetificador del tipo
     * @return  int                         identificador de la ACTIVIDAD.
     */
    public function saveActividad( $actividad, $idPadre, $idPlnObj, $tipo, $idObjetivo )
    {
        $db = JFactory::getDBO();
        $tbActividad = new jTableActividad( $db );
        $idActividad = 0;
        if ( $actividad->published == 1 ) {
            $idActividad = $tbActividad->saveActividad( $actividad, $idPlnObj );
            $this->_makeDirActividad( $idPadre, $idObjetivo, $idActividad, $tipo );
        } else if ( $actividad->idActividad != 0 ) {
            $this->_deleteLogicoActividad($actividad->idActividad);
        }

        return $idActividad;
    }

    /**
     *  Recupera la lista de ACTIVIDADES de un OBJETIVO
     * 
     * @param type $idPoa           Identificador del PLAN
     * @param type $idObjPln        Identificador del PLAN-OBJETIVO
     * @param type $regObjetivo     Id del resgistro del objetivo
     * @param type $tipo            Tipo de entidad del plan
     * @param type $idObjetivo      Identificador del objetivo
     * @return array
     */
    public function getLstActividades(  $idPoa, 
                                        $idObjPln, 
                                        $regObjetivo, 
                                        $tipo, 
                                        $idObjetivo )
    {
        $db = JFactory::getDBO();
        $tbActividad = new jTableActividad( $db );
        $lstActividades = $tbActividad->getLstActividades( $idObjPln );

        if( count( $lstActividades ) ) {
            foreach( $lstActividades AS $key => $actividad ) {
                $actividad->registroAct = $key;
                $actividad->lstArchivosActividad = $this->_getLstArchivosActividad( $idPoa, 
                                                                                    $idObjetivo, 
                                                                                    $regObjetivo, 
                                                                                    $actividad->idActividad, 
                                                                                    $actividad->registroAct, 
                                                                                    $tipo );
            }
        } else {
            $lstActividades = array();
        }

        return $lstActividades;
    }

    /**
     * Crea el DIRECTORIO para los archivos de las atividades.
     * @param int $idPadre      Identificador del PADRE
     * @param int $idObjetivo   Identificador del OBJETIVO
     * @param int $idActividad  Identificador de la ACTIVIDAD
     * @param int $tipo         Identificador del tipo
     *                          1 peis
     *                          2 poas
     *                          3 programas
     * 
     */
    private function _makeDirActividad( $idPadre, $idObjetivo, $idActividad, $tipo )
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
        // path=media/ecorae/docs/[peis|poas|programas]/[idPeo|idPoa|idProgramas]/objetivos/idObjetivo/actividades/idActividad
        $path = JPATH_BASE . DS . 'media' . DS . 'ecorae' . DS . 'docs' . DS . $dirName . DS . $idPadre . DS . 'objetivos' . DS . $idObjetivo . DS . 'actividades' . DS . $idActividad;
        if( !(file_exists( $path )) ) {
            mkdir( $path, 0777, true );
        }
    }

    
    
    /**
     * Recupera la lista de Archivos de una actividad
     * 
     * 
     * @param int $idPoa            Identificador del POA
     * @param int $idObjetivo       Identificador del OBJETIVO
     * @param int $regObjetivo      registro del OBJETIVO
     * @param int $idActividad      Identificador del ACTIVIDAD
     * @param int $registroAct      registro del ACTIVIDAD
     * @param type $idTpoEntidad    Identificador del Tipo de PEI
     *                                  1: PEI
     *                                  2: POA
     *                                  3: Programas
     * 
     * @return boolean
     */
    private function _getLstArchivosActividad( $idPoa, $idObjetivo, $regObjetivo, $idActividad, $registroAct, $idTpoEntidad )
    {
        $lstArchivos = array();

        switch( $idTpoEntidad ) {
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

        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . $dirName . DS . $idPoa . DS . 'objetivos' . DS . $idObjetivo . DS . 'actividades' . DS . $idActividad . DS;

        if( file_exists( $path ) ) {
            $count = 0;
            $directorio = opendir( $path );

            while( $archivo = readdir( $directorio ) ) {
                if( $archivo != "." && $archivo != ".." ) {
                    $docu["idObjetivo"] = $idObjetivo;
                    $docu["regObjetivo"] = $regObjetivo;
                    $docu["idActividad"] = $idActividad;
                    $docu["registroAct"] = $registroAct;
                    $docu["regArchivo"] = $count++;
                    $docu["nameArchivo"] = $archivo;
                    $docu["flag"] = false;
                    $docu["published"] = 1;
                    $lstArchivos[] = $docu;
                }
            }
            
            closedir( $directorio );
        }

        return $lstArchivos;
    }

}

