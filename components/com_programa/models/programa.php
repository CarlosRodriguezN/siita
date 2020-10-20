<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
jimport( 'ecorae.uploadfile.upload' );

// Adjunto libreria de gestion de Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

// Adjunto libreria de gestion de Indicadores
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );
jimport( 'ecorae.objetivosOperativos.objetivoOperativo' );

jimport( 'ecorae.entidad.proyecto' );
jimport( 'ecorae.entidad.programa' );
jimport( 'ecorae.entidad.contrato' );
jimport( 'ecorae.entidad.convenio' );
jimport( 'ecorae.entidad.entidad' );
jimport( 'ecorae.unidadgestion.unidadgestion' );
jimport( 'ecorae.organigrama.organigrama' );
jimport( 'ecorae.entidad.EstadoEntidad' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_programa' . DS . 'tables' );

/**
 * Modelo del componente Programa
 */
class ProgramaModelPrograma extends JModelAdmin
{

    private $_idEntidad;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Programa', $prefix = 'ProgramaTable', $config = array() )
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
    public function getForm( $data = array(), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_programa.programa', 'programa', array( 'control'=>'jform', 'load_data'=>$loadData ) );

        //  Obtengo informacion sobre el identificador Id del registro
        $idPrograma = (int)$form->getField( 'intCodigo_prg' )->value;
        
        //  Obtengo informacion de identificador de la entidad relacionada con el proyecto
        $this->_idEntidad = $idEntidad = $form->getField( 'intIdEntidad_ent' )->value;

        //  Seteo Informacion de indicadores de un Proyecto
        $dtaIndicadores = $this->_setDataIndicadoresPrograma();
        
        //  Seteo Informacion de indicadores fijos en el formulario
        $this->_setDtaFrmIndFijos( $form, $dtaIndicadores );

        //  Datos de INDICADORES Registrados
        $form->setFieldAttribute( 'dataIndicadores', 'default', json_encode( $dtaIndicadores ) );

        //  Set de los atributos extas;
        $this->_setAributesValues( $form, $this->_idEntidad, $idPrograma );
        
        if( empty( $form ) ){
            return false;
        }

        return $form;
    }
    
    /**
     * Agrega los valores de los atributos extras.
     * @param type $form
     * @param type $idEnt
     * @param type $idPrg
     */
    private function _setAributesValues( $form, $idEnt, $idPrg )
    {
        // set de la unidad de gestion 
        $tbFR = $this->getTable( 'ResponsablePrograma', 'ProgramaTable' );
        $prgFR = $tbFR->getResponsablePrograma( $idPrg );
        if( $prgFR ){
            $form->setValue( 'intIdUGResponsable', null, $prgFR->idUnidadGestion );
            $form->setValue( 'idResponsable', null, $prgFR->undGestionFuncionario );
            $form->setValue( 'fchInicioPeriodoFuncionario', null, $prgFR->fechaInicio );
        }

        // set de la unidad de gestion responsable del contrato.
        $tbUGR = $this->getTable( 'UnidadGestionPrograma', 'ProgramaTable' );
        $prgUGR = $tbUGR->getUnidadGestionPrograma( $idPrg );
        if( $prgUGR ){
            $form->setValue( 'intIdUndGestion', null, $prgFR->idUnidadGestion );
            $form->setValue( 'fchInicioPeriodoUG', null, $prgFR->fechaInicio );
        }
    }
    
    /**
     * 
     * Seteo Informacion de Indicadores pertenecientes a un proyecto 
     * asociado por su entidad
     * 
     * @return type
     */
    private function _setDataIndicadoresPrograma()
    {
        $indicadores = array();

        //  Instancio la Clase Indicadores
        $objIndicadores = new Indicadores();
        $dtaIndicadores = $objIndicadores->getLstIndicadores( $this->_idEntidad );

        //  Seteo informacion de Indicadores en el Formulario
        if( count( $dtaIndicadores ) ){

            foreach( $dtaIndicadores as $indicador ){
                //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                $indicador->tpoIndicador                = $indicador->alias;
                $dtoIndicador["idIndEntidad"]           = $indicador->idIndEntidad;
                $dtoIndicador["idIndicador"]            = $indicador->idIndicador;
                $dtoIndicador["nombreIndicador"]        = $indicador->nombreIndicador;
                $dtoIndicador["modeloIndicador"]        = $indicador->modeloIndicador;
                $dtoIndicador["umbral"]                 = $indicador->umbral;
                $dtoIndicador["tendencia"]              = (int) $indicador->tendencia;
                $dtoIndicador["descripcion"]            = $indicador->descripcion;
                $dtoIndicador["idUndAnalisis"]          = (int) $indicador->idUndAnalisis;
                $dtoIndicador["undAnalisis"]            = (int) $indicador->undAnalisis;
                $dtoIndicador["idTpoUndMedida"]         = (int) $indicador->idTpoUndMedida;
                $dtoIndicador["idUndMedida"]            = (int) $indicador->idUndMedida;
                $dtoIndicador["undMedida"]              = (int) $indicador->undMedida;
                $dtoIndicador["idTpoIndicador"]         = $indicador->idTpoIndicador;
                $dtoIndicador["formula"]                = $indicador->formula;
                $dtoIndicador["fchHorzMimimo"]          = $indicador->fchHorzMimimo;
                $dtoIndicador["fchHorzMaximo"]          = $indicador->fchHorzMaximo;
                $dtoIndicador["umbMinimo"]              = $indicador->umbMinimo;
                $dtoIndicador["umbMaximo"]              = $indicador->umbMaximo;
                $dtoIndicador["idClaseIndicador"]       = $indicador->idClaseIndicador;
                $dtoIndicador["idHorizonte"]            = $indicador->idHorizonte;
                $dtoIndicador["idFrcMonitoreo"]         = $indicador->idFrcMonitoreo;
                $dtoIndicador["idUGResponsable"]        = $indicador->idUGResponsable;
                $dtoIndicador["idResponsableUG"]        = $indicador->idResponsableUG;
                $dtoIndicador["fchInicioUG"]            = $indicador->fchInicioUG;
                $dtoIndicador["idResponsable"]          = $indicador->idResponsable;
                $dtoIndicador["fchInicioFuncionario"]   = $indicador->fchInicioFR;
                $dtoIndicador["idDimension"]            = $indicador->idDimension;
                $dtoIndicador["idEnfoque"]              = $indicador->idEnfoque;
                $dtoIndicador["enfoque"]                = $indicador->enfoque;
                $dtoIndicador["idCategoria"]            = $indicador->categoriaInd;
                $dtoIndicador["idDimension"]            = $indicador->idDimension;
                $dtoIndicador["idDimIndicador"]         = $indicador->idDimIndicador;

                $dtoIndicador["lstUndsTerritoriales"]   = $objIndicadores->getLstIndUndTerritorial( $indicador->idIndEntidad );
                $dtoIndicador["lstLineaBase"]           = $objIndicadores->getLstLineasBase( $indicador->idIndicador );
                $dtoIndicador["lstRangos"]              = $objIndicadores->getLstRangos( $indicador->idIndEntidad );
                $dtoIndicador["lstVariables"]           = $objIndicadores->getLstVariables( $indicador->idIndicador );
                $dtoIndicador["lstDimensiones"]         = $objIndicadores->getLstDimensiones( $indicador->idIndicador );
                $dtoIndicador["lstPlanificacion"]       = $objIndicadores->getLstPlanificacion( $indicador->idIndEntidad );
                $dtoIndicador["published"] = 1;
                
                switch( true ){
                    //  Armo informacion de indicadores Fijos - Economicos ( 1 )
                    case( $indicador->categoriaInd == 1 ):
                        $lstIndEconomicos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Financieros ( 2 )
                    case( $indicador->categoriaInd == 2 ):
                        $lstIndFinancieros[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Beneficiarios Directos ( 1 )
                    case( $indicador->categoriaInd == 3 ):
                        $lstBDirectos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Beneficiarios Indirectos ( 1 )
                    case( $indicador->categoriaInd == 4 ):
                        $lstBIndirectos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - GAP ( 2 )
                    case( $indicador->categoriaInd == 5 ):
                        $lstTmpGAP[] = $dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - Enfoque de Igualdad ( 2 )
                    case( $indicador->categoriaInd == 6 ):
                        $lstTmpEI[] = $dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - Enfoque de Ecorae ( 2 )
                    case( $indicador->categoriaInd == 7 ):
                        $lstTmpEE[] = $dtoIndicador;
                    break;

                    //  Armo informacion de Indicador Meta Economico
                    case( $indicador->categoriaInd == 8 ):
                        $ime = $dtoIndicador;
                    break;
                }
            }

            $indicadores["indEconomicos"]   = $this->_procesoIndicadores( 31, $lstIndEconomicos );
            $indicadores["indFinancieros"]  = $this->_procesoIndicadores( 32, $lstIndFinancieros );
            $indicadores["indBDirectos"]    = $this->_procesoIndicadores( 33, $lstBDirectos );
            $indicadores["indBIndirectos"]  = $this->_procesoIndicadores( 34, $lstBIndirectos );

            //  Seteo indicadores de tipo GAP
            $indicadores["lstGAP"] = ( count( $lstTmpGAP ) )? $objIndicadores->getLstGAP( $lstTmpGAP ) 
                                                            : array();

            //  Seteo indicadores de tipo Enfoque de Igualdad
            $indicadores["lstEnfIgualdad"] = ( count( $lstTmpEI ) ) ? $objIndicadores->getLstEI( $lstTmpEI ) 
                                                                    : array();

            //  Seteo indicadores de tipo Enfoque de ECORAE
            $indicadores["lstEnfEcorae"] = ( count( $lstTmpEE ) )   ? $objIndicadores->getLstEE( $lstTmpEE ) 
                                                                    : array();
            
            //  Seteo informacion de INDICADORES especificos, catalogados como "Otros Indicadores"
            $indicadores["lstOtrosIndicadores"] = $this->getOtrosIndicadores();
        }else{
            $indicadores["indEconomicos"]       = $this->_getPtllaIndicador( 31 );
            $indicadores["indFinancieros"]      = $this->_getPtllaIndicador( 32 );
            $indicadores["indBDirectos"]        = $this->_getPtllaIndicador( 33 );
            $indicadores["indBIndirectos"]      = $this->_getPtllaIndicador( 34 );

            $indicadores["lstGAP"]              = array();
            $indicadores["lstEnfIgualdad"]      = array();
            $indicadores["lstEnfEcorae"]        = array(); 
            $indicadores["lstOtrosIndicadores"] = array();
        }

        return $indicadores;
    }
    
    /**
     * 
     * Valido la creacion de indicadores 
     * 
     * @param int $idDimension     Identificador de dimension
     * @param array $lstIndicadores  Lista de indicadores a procesar
     * 
     * @return array
     */
    private function _procesoIndicadores( $idDimension, $lstIndicadores )
    {
        $lstInd = array();
        $ptllaIndicadores = $this->_getPtllaIndicador( $idDimension );
        
        foreach( $ptllaIndicadores as $pi ){
            //  Verifico al existencia del Indicador en funcion a su modelo
            $dtaInd = $this->_getIndPorModelo( $pi->modeloIndicador, $lstIndicadores );            
            if( $dtaInd ){
                $lstInd[] = $dtaInd;
            }else{
                $lstInd[] = $pi;
            }
        }
        
        return $lstInd;
    }
    
    /**
     * 
     * @param type $modeloIndicador
     * @param type $lstIndicadores
     * 
     * @return type
     */
    private function _getIndPorModelo( $modeloIndicador, $lstIndicadores )
    {
        $dtaInd = false;
        
        foreach( $lstIndicadores as $ind ){
            if( $ind->modeloIndicador == $modeloIndicador ){
                $dtaInd = $ind;
            }
        }
        
        return $dtaInd;
    }
    
    /**
     * 
     * Retorna Lista de Otros Indicadores 
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type Lista de Proyectos
     */
    public function getOtrosIndicadores()
    {
        $objIndicadores = new Indicadores();
        return $objIndicadores->getLstOtrosIndicadores( $this->_idEntidad );
    }
    
    
    /**
     * 
     * Obtengo informacion de indicadores predefinidos desde una plantilla
     * 
     * @param int $idDimension     Identificador de Dimension ( 31: Economico, etc. )
     * 
     * @return object
     * 
     */
    private function _getPtllaIndicador( $idDimension )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getDtaPtllaPorDimension( $idDimension );
    }
    
    
    /**
     * 
     * Seteo Informacion de indicadores fijos en los formualrios
     * 
     * @param type $form
     * @param type $indicadores
     */
    private function _setDtaFrmIndFijos( $form, $indicadores )
    {
        //  Seteo Informacion de Indicadores Economicos en el formulario
        $this->_setDataIndEconomicos( $form, (object)$indicadores["indEconomicos"], 31 );

        //  Seteo Informacion de Indicadores Financieros en el formulario
        $this->_setDataIndFinancieros( $form, (object)$indicadores["indFinancieros"], 32 );

        //  Seteo Informacion de Beneficiarios Directos en el Formulario
        $this->_setDataBDirectos( $form, (object)$indicadores["indBDirectos"], 33 );

        //  Seteo Informacion de Beneficiarios Directos en el Formulario
        $this->_setDataBIndirectos( $form, (object)$indicadores["indBIndirectos"], 34 );
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
        $data = JFactory::getApplication()->getUserState( 'com_programa.edit.programas.data', array() );
        if( empty( $data ) ){
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * Guarda la información de que viene del formulario.
     * @param type $dataJSON
     * @return type
     */
    public function saveFromJSON( $dataJSON, $dtaIndicadores )
    {
        //  Codifico los arrays que vienen por el post
        $programa = json_decode( $dataJSON );
        
        //  Instancio la tabla "Programa"
        $tbPrograma = $this->getTable();

        //  Registro al programa como una entidad
        $idEntidad = $this->saveEntidad( $programa->idEntidad, $programa->urlTableU );

        //  Registro los datos de un programa
        $idPrograma = $tbPrograma->savePrograma( $programa, $idEntidad );

        if ( $idPrograma ){
            //  Registro de Objetivos de un proyecto
            $this->_registroObjetosOperativos( $idEntidad, $programa->lstObjetivos );
            
            //  Registro de el estado de la entidad
            $this->_registroEstadoEntidad( JRequest::getVar( 'idEstadoEntidad' ) );

            //  gestion del articulo asociado al programa
            $this->_registroArtToPrg( $idPrograma, $programa );

            //  Guardo los subprogrmas
            $this->saveSubProgramas( $programa, $idPrograma );

            //  Gestión de Responsables.
            $this->_gestionResponsable( $idPrograma, $programa->responsable );

            //  Registro informacion de Indicadores de un proyecto
            $this->_saveDataIndicadores( $programa->idEntidad, $dtaIndicadores );
        }

        echo $idPrograma; 
        exit;
    }

    /**
     * 
     * @param type $idPrograma
     * @param type $programa
     * @return type
     */
    private function _registroArtToPrg( $idPrograma, $programa )
    {
        $oldArt = ( $programa->articlePrg ) ? json_decode(base64_decode($programa->articlePrg)) 
                                            : array();

        $programa->articlePrg = $oldArt;
        $article = array();

        //  Registro el Programa en Menu
        $article["idMenu"] = $this->saveProgramaMenu( $programa, $idPrograma );
        
        //  Registro el programa en la tabla assets
        $article["idAssets"] = $this->_getIdPrgAssets( $idPrograma, $programa );
        
        //  Registro programa en la tabla content
        if( $article["idAssets"] ) {
            $article["idContent"] = $this->_savePrgContent( $article["idAssets"], $programa );
        }

        //  Asocio el menu al programa.
        if ( empty($oldArt) || $oldArt->idMenu == 0 || $oldArt->idAssets == 0 || $oldArt->idContent == 0 ){
            $tbPrograma = $this->getTable();
            $tbPrograma->setIdsArticleToPrg( $idPrograma, $article );
        }

        return $article;
    }
    
    /**
     * 
     * @param type $idEstadoEntidad
     * @return type
     */
    private function _registroEstadoEntidad( $idEstadoEntidad )
    {
        $tbEstEntidad = new EstadoEntidad();
        $idRegEstEntidad = $tbEstEntidad->gestionEstadoEntidad( $this->_idEntidad, $idEstadoEntidad );
        return $idRegEstEntidad;
    }

    /**
     *  Gestiona el registro de la entidad de un programa
     * @param int    $idEntidad   Identificador de la entidad
     * @param String $urlTableU   URL tableU
     * @return int
     */
    public function saveEntidad( $idEntidad, $urlTableU )
    {

        $entidad = new Entidad();
        $idEntidad = $this->_idEntidad = $entidad->saveEntidad( $idEntidad, 1, $urlTableU );

        return $idEntidad;
    }

    /**
     * 
     * @param type $info
     * @param type $idPrograma
     * @return type
     */
    public function saveProgramaMenu( $info, $idPrograma )
    {
        $tbMenu = $this->getTable( "Menu", "ProgramaTable" );
        $idMenuPrograma = $tbMenu->saveProgramaMenu( $idPrograma, $info );
        return $idMenuPrograma;
    }

    /**
     * 
     * @param type $subPrograma
     * @param type $idPrograma
     * @param type $idSubPrograma
     */
    public function saveSubProgramas( $programa, $idPrograma )
    {
        if (count($programa->lstSubProgramas) > 0 ) {
            $tbSubPrograma = $this->getTable( "SubPrograma", "ProgramaTable" );
            foreach( $programa->lstSubProgramas AS $subPrograma ){
                if ( !($subPrograma->idSubPrograma == 0 && $subPrograma->published == 0) ){
                    $idSubPrograma = $tbSubPrograma->saveSubPrograma( $subPrograma, $idPrograma );
                    if ( $idSubPrograma && $subPrograma->published != 0) {
                        $this->saveTiposSubProgramas( $subPrograma, $idSubPrograma );
                    }
                    // $idMenuSubPrograma = $this->saveSubProgramaMenu($subPrograma, $idSubPrograma, $idPrograma, $idMenuPrograma);
                    // $tbSubPrograma->setIdMenuToSubPrograma($idSubPrograma, $idMenuSubPrograma);
                }
            }
        }
        return true;
    }

    /**
     * Gestiona la lista de "TIPOS de SUB PROGRAMAS" que tiene un programa. 
     * @param object    $subPrograma        objeto tipo sub programa
     * @param int       $idSubPrograma      identificador del sub programa.
     */
    public function saveTiposSubProgramas( $subPrograma, $idSubPrograma )
    {

        foreach( $subPrograma->lstTiposSubPrograma AS $tipoSubPrograma ){
            $tbTipoSubPrograma = $this->getTable( "TipoSubPrograma", "ProgramaTable" );
            $idTipoSubPrograma = $tbTipoSubPrograma->saveTipoSubPrograma( $tipoSubPrograma, $idSubPrograma );
        }
    }

    /**
     * Gestiona le MENU de el SUB Programa.
     * @param object    $subPrograma    Objeto sub programa.
     * @param int       $idPrograma     Identificador del programa.
     * @param int       $idSubPrograma  Identificador del sub programa.
     * @return int                      Identificador del "Menu de un sub programa"
     */
    public function saveSubProgramaMenu( $subPrograma, $idSubPrograma, $idPrograma, $idMenuPrograma )
    {
        $tbMenu = $this->getTable( "Menu", "ProgramaTable" );

        $idMenuPrograma = $tbMenu->saveSubProgramaMenu( $subPrograma, $idSubPrograma, $idPrograma, $idMenuPrograma );
        return $idMenuPrograma;
    }

    /**
     * Gestiona la informacion del responsable de un programa
     * @param type $programa
     */
    private function _gestionResponsable( $idPrograma, $programa )
    {
        // Registro del UNIDAD DE GESTION RESPONSABLE de un PROGRAMA
        $this->saveUnidadGestionPrograma( $idPrograma, $programa->idUGR, $programa->fchIniciUGR );

        // Registro del FUNCIONARIO RESPONSABLE de un PROGRAMA
        $this->saveResponsablePrograma( $idPrograma, $programa->idResponsable, $programa->fchIniciRes );
    }

    /**
     * Gestiona la informacion de una UNIDAD de GESTION RESPONSABLE del CONTRATO.
     * @param int   $idPrograma         Identificador de programa
     * @param int   $idUnidadGestion    Identificador de la Unidad de Gestión
     * @param date  $fchIniciUGR        Fecha de Iniciio de la gestion
     */
    public function saveUnidadGestionPrograma( $idPrograma, $idUnidadGestion, $fchIniciUGR )
    {
        $tbUnidadGestioPrograma = $this->getTable( 'UnidadGestionPrograma', 'ProgramaTable' );
        if( $idUnidadGestion ){
            $tbUnidadGestioPrograma->saveUnidadGestionPrograma( $idPrograma, $idUnidadGestion, $fchIniciUGR );
        }
    }

    /**
     * Regista los responsables del Programa
     * @param int $idPrograma       Identificador del Programa
     * @param int $idResponsable    Identificador del Responsable
     */
    public function saveResponsablePrograma( $idPrograma, $idResponsable, $fchIniciRes )
    {
        $tbUnidadGestioPrograma = $this->getTable( 'ResponsablePrograma', 'ProgramaTable' );
        if( $idResponsable ){
            $tbUnidadGestioPrograma->saveResponsablePrograma( $idPrograma, $idResponsable, $fchIniciRes );
        }
    }

    /**
     * Permite guarda una imagen un logo y un icono, ademas guarda la data cuando 
     * el tipo es IMAGEN
     * @return boolean
     */
    public function saveImagesPrograma( $idPrograma )
    {
        $ban = FALSE;
        $tipo = JRequest::getVar( 'typeFileUpl' );

        if( strcmp( $tipo, 'imagen' ) == 0 ){// cuando es imagen
            $info = JRequest::getVar( 'dataFrm' );
            $this->saveUploadFiles( $idPrograma );

            $ban = true;
        }

        if( strcmp( $tipo, 'logo' ) == 0 || strcmp( $tipo, 'icono' ) == 0 ){
            if( $this->saveUploadFiles() ){
                $ban = true;
            }
        }
        
        return $ban;
    }

    /**
     * 
     * Gestiona la carga de Imagenes
     * 
     */
    public function saveUploadFiles( $idPrograma )
    {
        switch( JRequest::getVar( 'typeFileUpl' ) ){
            case "imagen":
                //  Directorio del carrusel de imagenes
                $path = JPATH_SITE . DS . 'cache' . DS . 'lofthumbs';
                $idPrograma = '708x248-'. $idPrograma;
                break;

            case "logo"://  Path para la carga LOGOS
                $path = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'logo';
                break;

            case "icono"://   Path para ICONOS
                $path = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'icono';
                break;
        }

        $up_file = new upload( "Filedata", NULL, $path, $idPrograma );
        return $up_file->save();
    }

    /**
     *  Elimina el programa de la tabla. (programa.php)
     */
    function delPrograma( $idPrograma )
    {
        $tbPrg = $this->getTable();
        $dtaPrg = $tbPrg->getProgramaByID( $idPrograma );
        
        $result = $tbPrg->delPrograma( $idPrograma );
        if ($result){
            $this->delImgsPrg( $idPrograma );
            $tbPrg->delContentArt( $dtaPrg->idContent );
            $tbPrg->delAssetsArt( $dtaPrg->idAssets );
            $tbPrg->delMenuArt( $dtaPrg->idMenu );
        }
        return $result;
    }
    
    /**
     * 
     * @param type $idPrg
     * @return boolean
     */
    function delImgsPrg( $idPrg )
    {
        try{
            $paths[ 'imagenes' ] = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'imagenes' . DS . $idPrg . '.png';
            $paths[ 'logos' ] = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'logo' . DS . $idPrg . '.png';
            $paths[ 'iconos' ] = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'programa' . DS . 'icono' . DS . $idPrg . '.png';
            $paths[ 'carusel' ] = JPATH_SITE . DS . 'cache' . DS . 'lofthumbs' . DS; //directorio del carrusel de imagenes

            foreach( $paths AS $path ){
                unlink( $path );
            }
            return true;
        }catch( Exception $e ){
            return false;
        }
    }

    /**
     *  Recupera la lista de los subProgramas de un programa
     * @param type $idPrograma idetificador de el programa
     */
    public function getSubProgramas()
    {
        $idPrograma = JRequest::getVar( 'intCodigo_prg' ); //recupero el identificador de el programa;
        $subProgramasList = array();
        if( (int)$idPrograma != 0 ){
            $tbSubProgramas = $this->getTable( 'SubPrograma', 'ProgramaTable' );
            $subProgramasList = $tbSubProgramas->getSubProgramas( $idPrograma );
            if( !empty($subProgramasList) ){
                foreach( $subProgramasList AS $subPrograma ){
                    $tbTiposSubProgramas = $this->getTable( 'TipoSubPrograma', 'ProgramaTable' );
                    $subPrograma->lstTiposSubPrograma = $tbTiposSubProgramas->getTiposSubPrograma( $subPrograma->idSubPrograma );
                }
            }
        }
        
        return $subProgramasList;
    }

    /**
     * Retorna los responsables de un programa.
     * @return type
     */
    private function getResponsablePrograma()
    {
        $idPrograma = JRequest::getVar( 'intCodigo_prg' );
        $responsable = array();
        if( (int)$idPrograma != 0 ){
            $tbProrroga = $this->getTable( 'ResponsablePrograma', 'ProgramaTable' );
            $responsable = $tbProrroga->getResponsablePrograma( $idPrograma );
        }
        return $responsable;
    }

    /**
     * Recupera la UNIDAD DE GESTION DE UN CONTRATO.
     * @return type
     */
    private function getUnidadGestionPrograma()
    {
        $idPrograma = JRequest::getVar( 'intCodigo_prg' );
        $responsable = array();
        if( (int)$idPrograma != 0 ){
            $tbProrroga = $this->getTable( 'UnidadGestionPrograma', 'ProgramaTable' );
            $responsable = $tbProrroga->getUnidadGestionPrograma( $idPrograma );
        }
        return $responsable;
    }

    /**
     * 
     * @return type
     */
    public function getSubProgramasArraylist()
    {
        $idPrograma = JRequest::getVar( 'intcodigo_prg' ); //recupero el identificador de el programa;
        if( (int)$idPrograma != 0 ){
            $tbSubProgramas = $this->getTable( 'SubPrograma', 'ProgramaTable' );
            $subProgramasList = $tbSubProgramas->getSubPrograma( $idPrograma );
        }
        return $subProgramasList;
    }

    /**
     * Recupera la información de los programas.
     * @param type $idPrograma
     * @return type
     */
    public function getDataPrograma( $idPrograma )
    {
        $tbProgramas = $this->getTable( 'Programa', 'ProgramaTable' );

        $programaData[ 'data' ] = $tbProgramas->getProgramaByID( $idPrograma );

        $subProgramas = $tbProgramas->getSubProgramasByProgranaId( $idPrograma );

        $listaSubPrograma = array();

        foreach( $subProgramas AS $subProgramaData ){
            $subPrograma = array();
            $subPrograma[ 'data' ] = $subProgramaData;
            $subPrograma[ 'tiposSubPrograma' ] = $tbProgramas->getTipoSubProgramasBySubProgranaId( $subProgramaData->idSubPrograma );

            $listaSubPrograma[ $subProgramaData->idSubPrograma ] = $subPrograma;
        }
        $programaData[ 'subProgramas' ] = $listaSubPrograma;

        return $programaData;
    }

    /**
     * 
     * Gestiono el registro en la tabla Assets
     * 
     * @param int $idPrograma       Identificador del programa
     * @param object $dtaPrograma   Datos del programa
     * 
     * @return int  Identificador del programa
     * 
     */
    private function _getIdPrgAssets( $idPrograma, $dtaPrograma )
    {
        $tbAssets = $this->getTable( 'Assets', 'ProgramaTable' );
        return $tbAssets->registroProgramaAssets( $idPrograma, $dtaPrograma );
    }

    /**
     * 
     * Gestiono el registro de informacion del proyecto en la tabla 
     * 
     * @param int $idPrgAssets      Identificador del Proyecto en la tabla assets
     * @param object $programa      Datos del programa a registrar
     */
    private function _savePrgContent( $idPrgAssets, $dtaPrograma )
    {
        $tbContent = $this->getTable( 'Content', 'ProgramaTable' );
        return $tbContent->registroProgramaContent( $idPrgAssets, $dtaPrograma );
    }

///////////////////////////////
//  INDICADORES - PROGRAMA
///////////////////////////////

    /**
     * 
     * Seteo Informacion de indicadores Estaticos como Economicos / Financieros
     * 
     * @param Objeto $form              Formulario
     * @param Objeto $dtaIndicadores    Datos de Indicador
     * @param Int $idDimension          Identificador de Dimension
     * 
     * @return type
     */
    private function _setDataIndEconomicos( $form, $dtaIndicadores, $idDimension )
    {
        foreach( $dtaIndicadores as $indicador ){
            switch( true ){
                //  Tasa de Descuento
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 14 ) ):
                    $form->setFieldAttribute( 'intTasaDctoEco', 'default', (int)$indicador->umbral );
                break;

                //  Valor Actual Neto
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 15 ) ):

                    $form->setFieldAttribute( 'intValActualNetoEco', 'default', round( $indicador->umbral, 2 ) );
                break;

                //  Tasa Interna de Retorno
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 16 ) ):
                    $form->setFieldAttribute( 'intTIREco', 'default', (int)$indicador->umbral );
                break;
            }
        }

        return;
    }

    /**
     * 
     * Indicadores Financieros
     * 
     * @param type $form            Objeto Formulario
     * @param type $dtaIndicadores  Datos de Indicadores
     * @param type $idDimension     Datos de Dimension
     * 
     * @return type
     * 
     */
    private function _setDataIndFinancieros( $form, $dtaIndicadores, $idDimension )
    {
        foreach( $dtaIndicadores as $indicador ){
            switch( true ){
                //  Tasa de Descuento
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 14 ) ):
                    $form->setFieldAttribute( 'intTasaDctoFin', 'default', (int) $indicador->umbral );
                break;

                //  Valor Actual Neto
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 15 ) ):
                    $form->setFieldAttribute( 'intValActualNetoFin', 'default', round( $indicador->umbral ) );
                break;

                //  Tasa Interna de Retorno
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 16 ) ):
                    $form->setFieldAttribute( 'intTIRFin', 'default', (int) $indicador->umbral );
                break;
            }
        }

        return;
    }

    /**
     * 
     * Indicadores Financieros
     * 
     * @param type $form            Objeto Formulario
     * @param type $dtaIndicadores  Datos de Indicadores
     * @param type $idDimension     Datos de Dimension
     * 
     * @return type
     * 
     */
    private function _setDataBDirectos( $form, $dtaIndicadores, $idDimension )
    {
        foreach( $dtaIndicadores as $indicador ){
            switch( true ){
                //  Beneficiarios Directos Hombre
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 6 ) ):
                    $form->setFieldAttribute( 'intBenfDirectoHombre', 'default', ( int ) $indicador->umbral );
                break;

                //  Beneficiarios Directos Mujer
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 7 ) ):
                    $form->setFieldAttribute( 'intBenfDirectoMujer', 'default', ( int ) $indicador->umbral );
                break;

                //  Total Beneficiarios Directo
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 4 ) ):
                    $form->setFieldAttribute( 'intTotalBenfDirectos', 'default', ( int ) $indicador->umbral );
                break;
            }
        }

        return;
    }

    /**
     * 
     * Muestra en interfaz informacion de indicadores Indirectos
     * 
     * @param type $form                Formulario
     * @param type $dtaIndicadores      Datos de indicadores
     * @param type $idDimension         Identificador de la dimension
     * 
     * @return type
     */
    private function _setDataBIndirectos( $form, $dtaIndicadores, $idDimension )
    {
        foreach( $dtaIndicadores as $indicador ){
            switch( true ){
                //  Beneficiarios inDirectos Hombre
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 6 ) ):
                    $form->setFieldAttribute( 'intBenfIndDirectoHombre', 'default', (int)$indicador->umbral );
                break;

                //  Beneficiarios inDirectos Mujer
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 7 ) ):
                    $form->setFieldAttribute( 'intBenfIndDirectoMujer', 'default', (int)$indicador->umbral );
                break;

                //  Total Beneficiarios inDirecto
                case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 4 ) ):
                    $form->setFieldAttribute( 'intTotalBenfIndDirectos', 'default', (int)$indicador->umbral );
                break;
            }
        }

        return;
    }

    /**
     * 
     * Registro informacion de indicadores pertenecienes a un determinado programa
     * 
     * @param int  $idEntidad       Identificador de la entidad del Proyecto
     * @param JSon $dtaIndicadores  Informacion de indicadores de un proyecto
     * 
     * @return type
     */
    private function _saveDataIndicadores( $idEntidad, $dtaIndicadores )
    {
        if( strlen( $dtaIndicadores ) ){
            $dtaIndicador = json_decode( $dtaIndicadores );

            //  Gestion Indicadores Economicos - Categoria # 1
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indEconomico, 1 );
            
            //  Gestion Indicadores Financieros - Categoria # 2
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indFinanciero, 2 );
            
            //  Gestion Indicadores Beneficiarios Directos - Categoria # 3
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indBDirecto, 3 );
            
            //  Gestion Indicadores Beneficiarios Indirectos - Categoria # 4
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indBIndirecto, 4 );
            
            //  Gestion Indicadores Dinamicos - GAP - Categoria # 5
            $this->_registroIndicadoresGAP( $idEntidad, $dtaIndicador->lstGAP, 5 );

            //  Gestion Indicadores Dinamicos - EI - Categoria # 6
            $this->_registroIndicadoresEI( $idEntidad, $dtaIndicador->lstEnfIgualdad, 6 );
            
            //  Gestion Indicadores Dinamicos - EE - Categoria # 7
            $this->_registroIndicadoresEE( $idEntidad, $dtaIndicador->lstEnfEcorae, 7 );
            
            //  Gestion de Otros Indicadores
            $this->_registroOtrosIndicadores( $idEntidad, $dtaIndicador->lstOtrosIndicadores, 0 );
        }

        return;
    }

    /**
     * 
     * Gestiono el Registro de Indicadores Fijos
     * 
     * @param type $idEntidad           Identificador de Entidad
     * @param type $dtaIndicadores      Datos del Indicador
     * @param type $banCategoriaInd     Categoria del Indicador 
     * 
     */
    private function _registroIndicadoresFijos( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador( $idEntidad, $indicador, $banCategoriaInd );
        }
    }

    /**
     * 
     * Gestion de Registro de Indicadores GAP
     * 
     * @param type $idEntidad       Identificador de Indicador Entidad
     * @param type $dtaIndicador    Datos de Indicadores GAP
     */
    private function _registroIndicadoresGAP( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador( $idEntidad, $indicador->gapMasculino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->gapFemenino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->gapTotal, $banCategoriaInd );
        }
    }

    /**
     * 
     * Gestiono el registro de Enfoque de igualdad
     * 
     * @param type $idEntidad           Identificador del Indicador
     * @param type $dtaIndicadores      Datos del indicador Enfoque de Igualdad
     */
    private function _registroIndicadoresEI( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador( $idEntidad, $indicador->eiMasculino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eiFemenino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eiTotal, $banCategoriaInd );
        }
    }

    /**
     * 
     * Gestiona el registro de Indicadores Ecorae
     * 
     * @param type $idEntidad
     * @param type $dtaIndicadores
     */
    private function _registroIndicadoresEE( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador( $idEntidad, $indicador->eeMasculino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eeFemenino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eeTotal, $banCategoriaInd );
        }
    }

    /**
     * 
     * Gestiona Informacion de un nuevo indicador de tipo Otro Indicador
     * 
     * @param type $idEntidad
     * @param type $lstOtrosIndicadores
     */
    private function _registroOtrosIndicadores( $idEntidad, $lstOtrosIndicadores, $banCategoriaInd )
    {
        foreach( $lstOtrosIndicadores as $oi ){
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador( $idEntidad, $oi, $banCategoriaInd );
        }
    }

    /**
     * 
     * Retorna una lista de Objetivos Estrategicos (OEI)
     * 
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     * 
     */
    public function lstIndicadores( $idPrograma )
    {
        $lstObjetivos = false;
        $indicadores = new Indicadores();

        $lstProgramasVigentes = $this->_lstProgramasVigentes( $idPrograma );
        if( $lstProgramasVigentes ){
            foreach( $lstProgramasVigentes as $programa ){
                $dtaIndicadores[ "idPrograma" ] = $programa->idPrograma;
                $dtaIndicadores[ "nombre" ] = $programa->nombre;
                $dtaIndicadores[ "idEntidad" ] = $programa->idEntidad;
                $dtaIndicadores[ "lstIndicadores" ] = $indicadores->getLstIndicadores( $programa->idEntidad );

                $lstIndicadores[] = $dtaIndicadores;
            }
        }

        return $lstIndicadores;
    }

    /**
     * 
     * Retorna una lista de programas vigentes 
     * identificados por su entidad
     * 
     * @return type
     * 
     */
    private function _lstProgramasVigentes( $idPrograma )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );

        $query->select( '   t1.intIdEntidad_ent AS idEntidad,
                            t1.intCodigo_prg AS idPrograma,
                            t1.strNombre_prg AS nombre' );
        $query->from( '#__pfr_programa t1' );
        $query->where( 't1.intCodigo_prg = ' . $idPrograma );
        $query->where( 't1.published = 1' );

        $db->setQuery( (string)$query );
        $db->query();

        $lstProgramas = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

        return $lstProgramas;
    }

    //  Listo todos los proyectos asociados a este Programa
    public function lstProyectosPrg( $idProyecto )
    {
        $objProyecto = new Proyecto();
        return $objProyecto->getLstProyectosPrg( $idProyecto );
    }

    /**
     * 
     * Listo todos los programas asociados a este proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     * 
     */
    public function lstContratosPrg( $idProyecto )
    {
        $objContrato = new Contrato();
        return $objContrato->getLstContratosPrg( $idProyecto );
    }

    /**
     * 
     * Listo todos los convenios asociados a este proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @return type
     */
    public function lstConveniosPrg( $idProyecto )
    {
        $objConvenio = new Convenio();
        return $objConvenio->getLstConveniosPrg( $idProyecto );
    }

    /**
     * Recupera la lista de objetivos de una entidad en este caso un programa
     * @param int $idEntidad   identificador de la entidad de un programa
     * @return type
     */
    public function getLstObjetivos( $idEntidad )
    {
        $oObjetivoOperativo = new ObjetivoOperativo();
        $lstObjetivos = $oObjetivoOperativo->getObjetivosOperativos( $idEntidad );
        return $lstObjetivos;
    }

    /**
     * Gestiona el registro de los objetivos de un programa.
     * @param type $idEntidad       Id entidad del (programa).    
     * @param type $dtaObjetivos    lst de objetivo del programa.
     * @return boolean
     */
    private function _registroObjetosOperativos( $idEntidad, $dtaObjetivos )
    {
        if( $dtaObjetivos->lstObjetivos ){
            
            foreach( $dtaObjetivos->lstObjetivos AS $objetivo ){
                $oObjetivoOperativo = new ObjetivoOperativo();
                $idObjetivoOperativo = $oObjetivoOperativo->saveObjetivoOperativo( $objetivo, $idEntidad );
            }
        }
        return true;
    }

    /**
     * Recupera la unidad de gestion de la entidad.
     * @param type $idEntidad
     * @return type
     */
    private function _getOrganigramaByEntidad( $idEntidad )
    {
        $oOrganigrama = new Organigrama();
        $organigrama = $oOrganigrama->getOrganigrama( (int)$idEntidad );
        $organigramaJSON = json_encode( $organigrama );
        return $organigramaJSON;
    }

    /**
     * Method to check if it's OK to delete a message. Overwrites JModelAdmin::canDelete
     */
    protected function canDelete( $record )
    {
        if( !empty( $record->id ) ){
            $user = JFactory::getUser();
            return $user->authorise( "core.delete", "com_programa.message." . $record->id );
        }
    }

    /**
     * Retorna Informacion de funcionarios asociados a un determinada Unidad de Gestion
     * @param type $idUndGestion    Identificador Unidad de Gestion
     * @return type
     */
    public function getResponsablesUG( $idUndGestion )
    {
        $objIndicador = new UnidadGestion();
        return $objIndicador->getFuncionariosResponsables( $idUndGestion );
    }

    public function __destruct()
    {
        return;
    }

}