<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

//  Import Joomla JUser Library
jimport( 'joomla.user.user' );

/**
 * 
 * Clase que gestiona informacion de la tabla programa ( #__pfr_programa )
 * 
 */
class ProgramaTablePrograma extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pfr_programa', 'intCodigo_prg', $db );
    }

    /**
     * Registo un programa.
     * @param int $info         Objeto con la información a editar o almacenar.
     * @param int $idEntidad    Identificador de la entidad.
     * @return int              Idetificador del registro.
     */
    public function savePrograma( $info, $idEntidad )
    {
        $idPrograma = 0;

        $data["intCodigo_prg"]      = $info->idPrg;
        $data["strNombre_prg"]      = $info->nombrePrg;
        $data["strAlias_prg"]       = $info->alias;
        $data["strDescripcion_prg"] = $info->descripcionPrg;
        $data["intIdEntidad_ent"]   = $idEntidad;
        $data["published"]          = $info->estadoPrg;

        if( !$this->save( $data ) ){
            echo $this->getError();
            exit;
        }

        return $this->intCodigo_prg;
    }

    /**
     * Asigma el identificador de menu que le corresponde
     * @param int $idPrograma       Identificador del programa.
     * @param int $idMenuPrograma   Identificador del menu.
     * @return int $idSubPrograma   Identificador del programa que fue actualizado.  
     */
    public function setIdMenuToPrograma( $idPrograma, $idMenuPrograma )
    {
        $subPrograma["intCodigo_prg"]   = ( int ) $idPrograma;
        $subPrograma["idMenu"]          = ( int ) $idMenuPrograma;
        $idSubPrograma = $idPrograma;

        
        if( !$this->save( $subPrograma ) ){
            echo $this->getError();
            exit;
        }
        
        return $this->intCodigo_prg;
    }

    /**
     *  Actualiza los IDs de Menu Assets y Content relacionados al Articulo para el Programa
     * @param type $idPrograma
     * @param type $article
     * @return type
     */
    public function setIdsArticleToPrg( $idPrograma, $article )
    {
        $programa["intCodigo_prg"]  = ( int ) $idPrograma;
        $programa["idMenu"]         = ( int ) $article["idMenu"];
        $programa["idAssets"]       = ( int ) $article["idAssets"];
        $programa["idContent"]      = ( int ) $article["idContent"];
        
        if( !$this->save( $programa ) ){
            echo $this->getError();
            exit;
        }
        
        return $this->intCodigo_prg;
    }
    
    /**
     * 
     * inserta los campos en la tabla programa.
     * @param int $idPrograma   Identificador del programa
     * @param int $idArticulo   Identificador del articulo
     * @param int $idContenido  Identificador del contenido
     */
    public function setMenuPrograma( $idPrograma, $idMenu, $idContenido )
    {
        try{

            $programaArray['intcodigo_prg'] = $idPrograma;
            $programaArray['idMenu'] = $idMenu;
            $programaArray['idContent'] = $idContenido;

            if( $this->bind( $programaArray ) ){
                return $this->store();
            } else{
                return false;
            }
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Recupera los sub programas de un programa
     * @param int $idPrograma   Identificador del programa.
     * @return Array            Lista de Sub Programas.
     */
    function getSubProgramasByProgranaId( $idPrograma )
    {
        try{
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select( 'intId_SubPrograma   AS idSubPrograma,
                            strCodigo_sprg      AS codigoSubPrograma, 
                            strDescripcion_sprg AS descripcionSubPrograma, 
                            strAlias_sprg       AS aliasSubPrograma' );
            $query->from( '#__pfr_sub_programa' );
            $query->where( 'intCodigo_prg=' . $idPrograma );
            $query->where( 'published=1' );
            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            return ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Recupera el programa por el identificador del programa.
     * @param int   $idPrograma      Identificador del programa.
     * @return Object                Objeto del programa
     */
    function getProgramaByID( $idPrograma )
    {
        try{
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select( '*' );
            $query->from( '#__pfr_programa' );
            $query->where( "intCodigo_prg =" . $idPrograma );
            $query->where( 'published=1' );
            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Recupera la entidad dado el id de la entidad
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     */
    function getProgramaByIdEntidad( $idEntidad )
    {
        try{
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select( '*' );
            $query->from( '#__pfr_programa' );
            $query->where( "intIdEntidad_ent =" . $idEntidad );
            $query->where( 'published = 1' );
            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Recupera la entidad dado el id de la entidad
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     */
    function getProgramas()
    {
        try{
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select( ' '
                    . 'strNombre_prg    AS grDescripcion,'
                    . 'intIdEntidad_ent AS  intIdEntidad_ent' );
            $query->from( ' #__pfr_programa ' );
            $query->where( ' published = 1 ' );
            $query->where( ' intIdEntidad_ent <> 0 ' );
            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObjectList() : array();
            return $retval;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_programa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    function delPrograma( $id ){
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->update( "#__pfr_programa" );
            $query->set( "published = 0" );
            $query->where( "intCodigo_prg = {$id}" );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_programa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $idContent
     * @return type
     */
    function delContentArt( $idContent ){
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->delete( "#__content" );
            $query->where( "id = {$idContent}" );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_programa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $idAssets
     * @return type
     */
    function delAssetsArt( $idAssets ){
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->delete( "#__assets" );
            $query->where( "id = {$idAssets}" );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_programa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $idMenu
     * @return type
     */
    function delMenuArt( $idMenu ){
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->delete( "#__menu" );
            $query->where( "id = {$idMenu}" );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_programa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
}
