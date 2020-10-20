<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );

//  Adjunto libreria de gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_conflictos' . DS . 'tables' );

/**
 * Modelo tipo obra
 */
class ConflictosModelTema extends JModelAdmin
{

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Tema', $prefix = 'ConflictosTable', $config = array( ) )
    {
        return JTable::getInstance( $type, $prefix, $config );
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm( $data = array( ), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_conflictos.tema', 'tema', array( 'control' => 'jform', 'load_data' => $loadData ) );
        if( empty( $form ) ) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState( 'com_conflictos.edit.tema.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getTema()
    {
        $idTema = JRequest::getVar( "intId_tma" );
        $tema = false;
        if( $idTema != 0 ) {
            $tbTema = $this->getTable( 'Tema', 'ConflictosTable' );
            $tema = $tbTema->getTema( $idTema );
            if( $tema ) {
                // Asocio la lista de los actores
                $tema->lstActDeta = $this->_getActoresTema( $idTema );
                //  Asocio la lista de los estados
                $tema->lstEstTema = $this->_getEstadosTema( $idTema );
                //  Asocio la lista de las fuentes del tema
                $tema->lstFetTema = $this->_getFuentesTema( $idTema );
                //  Asocio la lista de las unidades territoriales del tema
                $tema->lstUnidadesTerritoriales = $this->_getUnidadTerritorial( $idTema );
                //  Asocio la lista de archivos del tema
                $tema->lstArchivo = $this->_getLstArchivos( $idTema );
            }
        }
        return $tema;
    }

    /**
     * Retorna la lista detalles del los actores en un tema.
     * @param type $idTema      Identificador del tema
     * @return array
     */
    private function _getActoresTema( $idTema )
    {
        $tbActorTema = $this->getTable( 'ActorTema', 'ConflictosTable' );
        $lstActores = $tbActorTema->getActoresTema( $idTema );
        if( !empty($lstActores) ) {
            foreach( $lstActores AS $key => $actor ) {
                $actor->regActDeta = $key;
                $actor->lstArchivosActor = $this->_getLstArchivosActor( $idTema, $actor->idActorDetalle );
            }
        }
        return $lstActores;
    }

    /**
     *  Obtiene las fuentes de un tema 
     * @param int       $idTema identificador del tema
     * @return array            Lista de fuentes de un tema
     */
    private function _getFuentesTema( $idTema )
    {
        $tbFuenteTema = $this->getTable( 'FuenteTema', 'ConflictosTable' );
        $lstFuentes = $tbFuenteTema->getFuenteTema( $idTema );
        if( !empty($lstFuentes) ) {
            foreach( $lstFuentes AS $key => $fuente ) {
                $fuente->regFueTema = $key;
            }
        }
        return $lstFuentes;
    }

    /**
     * Lista de estadoss de un tema 
     * @param type $idTema
     * @return type
     */
    private function _getEstadosTema( $idTema )
    {
        $tbActorTema = $this->getTable( 'EstadoTema', 'ConflictosTable' );
        $lstEstados = $tbActorTema->getEstadosTema( $idTema );
        if( !empty($lstEstados) ) {
            foreach( $lstEstados AS $key => $actor ) {
                $actor->regEstTema = $key;
            }
        }
        return $lstEstados;
    }

    /**
     * Lista de unidades territoriales del conflicto
     * @return type
     */
    private function _getUnidadTerritorial( $idTema )
    {
        $unidadesTerritoriales = array( );
        if( $idTema != 0 ) {
            $tbvwdpa = $this->getTable( 'vwdpa', 'ConflictosTable' );
            $unidadesTerritoriales = $tbvwdpa->getLstUndTerritoriales( $idTema );
            if( !empty($unidadesTerritoriales) ) {
                foreach( $unidadesTerritoriales AS $key => $unidadTerrir ) {
                    $unidadTerrir->regUnidadTerritorial = $key;
                    $unidadTerrir->published = 1;
                }
            }
        }
        return $unidadesTerritoriales;
    }

    /**
     *  Gestiona el registro del tema 
     * @param type $tema
     * @return type
     */
    public function saveTema( $tema )
    {
        $idTema = 0;
        $tbTema = $this->getTable( 'Tema', 'ConflictosTable' );
        if( $tema ) {
            $idTema = $tbTema->saveTema( $tema );
            $tema->idTema = $idTema;
            if( $idTema != 0 ) {
                $this->_mkdirTema( $idTema );
                //Gestiono el registro de actores de un tema
                $tema->lstActDeta = $this->_saveActoresTema( $tema->lstActDeta, $idTema );
                //Gestiono el registro de estados de un tema
                $this->_saveEstadosTema( $tema->lstEstados, $idTema );
                //Gestiona el registro de estados de un tema
                $this->_saveFuentesTema( $tema->lstFuentes, $idTema );
                //Gestiona el registro de estados de un tema
                $this->_saveUnidadesTerritoriales( $tema->lstUnidadesTerritoriales, $idTema );
            }
        }
        return $tema;
    }

    /**
     *  Gestiona el registro de la relacion tema actor
     * @param type $lstActDeta  Lista de actores tema
     * @param type $idTema      Identificador del tema
     */
    private function _saveActoresTema( $lstActDeta, $idTema )
    {
        if( $lstActDeta ) {
            $tbActorDetalle = $this->getTable( 'ActorTema', 'ConflictosTable' );
            $result = array();
            foreach( $lstActDeta AS $actDeta ) {
                if (!($actDeta->idActorDetalle == 0 && $actDeta->published == 0)){
                    $actDeta->idActorDetalle = $tbActorDetalle->saveActDeta( $actDeta, $idTema );
                    $this->_mkdirActorDetalle( $idTema, $actDeta->idActorDetalle );
                    if ($actDeta->published == 1) {
                        $result[] = $actDeta;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Gestiona el registro d elas fuentes de un tema 
     * @param array $lstFuentes         Lista de fuentes del tema
     * @param int   $idTema             Identificador de tema
     */
    private function _saveFuentesTema( $lstFuentes, $idTema )
    {
        if( $lstFuentes ) {
            foreach( $lstFuentes AS $fuente ) {
                $tbFuenteTema = $this->getTable( 'FuenteTema', 'ConflictosTable' );
                $idFuente = $tbFuenteTema->saveFuenteTema( $fuente, $idTema );
            }
        }
    }

    /**
     *  Gestiona el registo de la lista de estados de un tema 
     * @param array $lstEstados  Lista de estados de un tema
     * @param int   $idTema      Identificador del tema
     */
    private function _saveEstadosTema( $lstEstados, $idTema )
    {
        if( $lstEstados ) {
            foreach( $lstEstados AS $estTema ) {
                $tbActorDetalle = $this->getTable( 'EstadoTema', 'ConflictosTable' );
                $idEstTema = $tbActorDetalle->saveEstadoTema( $estTema, $idTema );
            }
        }
    }

    /**
     * 
     *  Registro la unidad territorial
     * @param int   $idTema                 Identificador del tema.
     * @param array $unidadesTerritoriales  Lista de unidades Territoriales
     * @return int  $idUnidadTerritorial    Identificador de la unidad territorial
     */
    private function _saveUnidadesTerritoriales( $unidadesTerritoriales, $idTema )
    {
        if( count( $unidadesTerritoriales ) > 0 )
            $tbUndTer = $this->getTable( 'UnidadTerritorial', 'ConflictosTable' );
        $tbUndTer->deleteUnidadesTerritoriales( $idTema );
        if( $unidadesTerritoriales ) {
            foreach( $unidadesTerritoriales AS $unidadTerritorial ) {
                $tbUnidadTerritorial = $this->getTable( 'UnidadTerritorial', 'ConflictosTable' );
                $idUnidadTerritorial = $tbUnidadTerritorial->saveUnidadTerritorial( $idTema, $unidadTerritorial );
            }
        }
        return $idUnidadTerritorial;
    }

    /*     * **** FUNCIONES UTILIZADAS EN EL AJAX *** */

    /**
     *  Retorna la lista de fuentes segun un tipo 
     * @param   int   $idTipoFuente     Identificador del tipo de fuente
     * @return  array                   Lsta de fuentes segun el tipo
     */
    public function getFuentesByTipo( $idTipoFuente )
    {
        $tbFuente = $this->getTable( 'Fuente', 'ConflictosTable' );
        $lstFuentesByTipo = $tbFuente->getFuentesByTipo( $idTipoFuente );
        return $lstFuentesByTipo;
    }

    // funciones para la carga de archivos

    /**
     * 
     * @param type $idTema
     */
    private function _getLstArchivos( $idTema )
    {
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idTema . DS . "archivos" . DS;

        if( file_exists( $path ) ) {
            $count = 0;
            $directorio = opendir( $path );
            while( $archivo = readdir( $directorio ) ) {
                if( $archivo != "." && $archivo != ".." ) {
                    $docu["idTema"] = $idTema;
                    $docu["regArchivo"] = $count++;
                    $docu["nameArchivo"] = $archivo;
                    $docu["flagUp"] = false;
                    $docu["published"] = 1;
                    $lstArchivos[] = $docu;
                }
            }
            closedir( $directorio );
        }
        return $lstArchivos;
    }

    /**
     * Retorna la lista de archivos de un actor en un tema
     * @param type $idTema          Identificador del tema
     * @param type $idActorTema     Identificador del actor en el tema(#__actorDetalle)
     * @return boolean
     */
    private function _getLstArchivosActor( $idTema, $idActorTema )
    {
        $lstArchivos = array();
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idTema . DS . "actorTema" . DS . $idActorTema;
        if( file_exists( $path ) ) {
            $count = 0;
            $directorio = opendir( $path );
            while( $archivo = readdir( $directorio ) ) {
                if( $archivo != "." && $archivo != ".." ) {
                    $docu["idActTema"] = $idActorTema;
                    $docu["idTema"] = $idTema;
                    $docu["regFile"] = $count++;
                    $docu["nameFile"] = $archivo;
                    $docu["flagUp"] = false;
                    $docu["published"] = 1;
                    $lstArchivos[] = $docu;
                }
            }
            closedir( $directorio );
        }
        return $lstArchivos;
    }

    /**
     * Crea el directorio donde se almacenaran los archivos del tema
     * @param type $idTema
     */
    private function _mkdirTema( $idTema )
    {
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idTema;
        
        if( !(file_exists( $path )) ) {
            // Directorio del tema
            mkdir( $path, 0777, true );
            chmod( $path, 0777);

            // directorio para los archivos
            $tmaPath = $path . DS . "archivos";
            if ( !(file_exists( $tmaPath )) ) {
                mkdir( $tmaPath, 0777, true );
                chmod( $tmaPath, 0777);
            }

            // Directorio para los archivos de un actor en un tema
            $tmaActoPath = $path . DS . "actorTema";
            if ( !(file_exists( $tmaActoPath )) ) {
                mkdir( $tmaActoPath, 0777, true );
                chmod( $tmaActoPath, 0777);
            }
        }
    }

    /**
     * Elimina el Archivo.
     * @param type $idTema
     * @param type $dataArchivo
     */
    public function delArchivo( $idTema, $dataArchivo )
    {
        $retval = TRUE;
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idTema . DS . 'archivos' . DS . $dataArchivo;
        if ( file_exists($path) ) {
            $retval = unlink( $path );
        }
        return $retval;
    }

    /**
     * 
     * @param type $idTema
     * @param type $actDeta
     */
    private function _mkdirActorDetalle( $idTema, $actDeta )
    {
        // Directorio para los archivos de un actor en un tema
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idTema . DS . "actorTema" . DS . $actDeta;
        if( !(file_exists( $path )) ) {
            mkdir( $path, 0777, true );
            chmod( $path, 0777);
        }
    }

    /**
     *  Elimina un archivo de un actor 
     * @param type $dataArchivo
     * @param type $idTema
     */
    public function delArchivoActor( $idTema, $actor, $dataArchivo )
    {
        $retval = true; 
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idTema . DS . "actorTema" . DS . $actor . DS . $dataArchivo;
        if ( file_exists($path) ) {
            $retval = unlink( $path );
        }
        return $retval;
    }

    
    public function eliminarTema( $id ){
        $tbTema = $this->getTable( 'Tema', 'ConflictosTable' );
        return $tbTema->eliminarLogicaTema( $id );
    }
    
    ############################################################################
    #####################        Tipo de Tema             ######################
    ############################################################################
    
    /**
     *  Retorna el ID de un nuevo registro de un Tipo de Tema
     * @param type $data
     * @return type
     */
    public function saveTipoTema( $data )
    {
        $tbTT = $this->getTable( 'TipoTema', 'ConflictosTable' );
        $dataTipoTema = json_decode( $data );
        
        $dtaTT["intId_tt"]      = $dataTipoTema->id;
        $dtaTT["strNombre_tt"]  = $dataTipoTema->nombre;
        $dtaTT["published"]     = $dataTipoTema->published;
        
        $idTT = $tbTT->saveTipoTema( $dtaTT );
        
        if ( $idTT ) {
            $list = $tbTT->getListTposTema();
        } else {
            $list = array();
        }
        
        return $list;
    }
    
    /**
     * 
     * @param type $idReg
     * @return type
     */
    public function deleteTipoTema( $idReg )
    {
        $tbTT = $this->getTable( 'TipoTema', 'ConflictosTable' );
        $result = false;
        
        if ( $tbTT->avalibleDeleteTT( $idReg, 1 ) ) {
            $del = false;
            if ( $tbTT->avalibleDeleteTT( $idReg, 2 ) ) {
                $del= $tbTT->eliminadoFisicoTT($idReg);
            } else {
                $del = $tbTT->eliminadoLogicoTT($idReg);
            }
            
            if ( $del ) {
                $result = $tbTT->getListTposTema();
            }
        }
        
        return $result;
    }
    
    ############################################################################
    #####################        Tipo de Tema             ######################
    ############################################################################
    
    /**
     *  Retorna el ID de un nuevo registro de un Tipo de Tema
     * @param type $data
     * @return type
     */
    public function saveEstado( $data )
    {
        $tbEst = $this->getTable( 'Estado', 'ConflictosTable' );
        $dataEst = json_decode( $data );
        
        $dtaEst["intId_ec"]      = $dataEst->id;
        $dtaEst["strNombre_ec"]  = $dataEst->nombre;
        $dtaEst["published"]     = $dataEst->published;
        
        $result = $tbEst->saveEstado( $dtaEst );
        
        if ( $result ) {
            $list = $tbEst->getListEstados();
        } else {
            $list = array();
        }
        
        return $list;
    }
    
    /**
     * 
     * @param type $idReg
     * @return type
     */
    public function deleteEstado( $idReg )
    {
        $tbEst = $this->getTable( 'Estado', 'ConflictosTable' );
        $result = false;
        
        if ( $tbEst->avalibleDeleteEst( $idReg, 1 ) ) {
            $del = false;
            
            if ( $tbEst->avalibleDeleteEst( $idReg, 2 ) ) {
                $del= $tbEst->eliminadoFisicoEst($idReg);
            } else {
                $del = $tbEst->eliminadoLogicoEst($idReg);
            }
            
            if ( $del ) {
                $result = $tbEst->getListEstados();
            }
        }
        
        return $result;
    }
    
    ############################################################################
    #####################        carga de archivos            ##################
    ############################################################################
    
    /**
     * 
     */
    public function saveFilesTema()
    {
        $data = array();
        $idOwner = JRequest::getVar( 'id' );
        $task = JRequest::getVar( 'reditecTo' );
        $op = JRequest::getVar( 'tpo' );
                
        //  Ubicacion de un directorio de registro de archivos relacionsd con el tema
        $pathBase = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "conflictos" . DS . $idOwner;
        
        switch ($op) {
            case 1:
                $pathFiles = $pathBase . DS . 'archivos';
                if( !is_dir( $pathFiles ) ){
                    mkdir( $pathFiles, 0777, true );
                    chmod( $pathFiles, 0777, true );
                }
                if ( $_FILES["filesTema"] ) {
                    $up_file = new upload( "filesTema", NULL, $pathFiles, $_FILES["filesTema"]->name );
                    $ban = $up_file->save();
                }
                break;
            case 2:
                $idTemaActor = JRequest::getVar( 'idActTma' );
                $pathFleAct = $pathBase . DS . 'actorTema' . DS . $idTemaActor;
                if( !is_dir( $pathFleAct ) ){
                    mkdir( $pathFleAct, 0777, true );
                    chmod( $pathFleAct, 0777, true );
                }
                if ( $_FILES["filesActorTema"] ) {
                    $up_file = new upload( "filesActorTema", NULL, $pathFleAct, $_FILES["filesTema"]->name );
                    $ban = $up_file->save();
                }
                break;
        }
        
        $data["id"] = $idOwner;  
        $data["redirecTo"] = $task;  
        $data["ban"] = $ban;  
        
        echo json_encode( $data );
        exit;
    }
    
}