<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  Gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_apirest' . DS . 'tables' );

/**
 * Modelo Fase
 */
class ApiRestModelUrl extends JModelAdmin
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
    public function getTable( $type = 'Url', $prefix = 'ApiRestTable', $config = array() ) 
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
        $form   = $this->loadForm( 'com_apirest.url', 'url', array( 'control' => 'jform', 'load_data' => $loadData ) );
        
        if( (int)$form->getField( 'intIdApiUrl' )->value ){
            $lstIPs = $this->_getInt2IP( $form->getField( 'strIPInstitucion_api' )->value );
            $form->setValue( 'strIPInstitucion_api', null, $lstIPs );
            
            $form->setFieldAttribute( 'intCodigo_ins', 'readonly', 'true' );
        }

        if( empty( $form ) ){
            return false;
        }

        return $form;
    }
    
    private function _getInt2IP( $dtaIPs )
    {
        $lstIPs = explode( ',', $dtaIPs );
        if( count( $lstIPs ) ){
            foreach( $lstIPs as $ip ){
                $rst .= long2ip( $ip ) .', ';
            }
        }
        
        return rtrim( $rst, ', ' );
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
        $data = JFactory::getApplication()->getUserState( 'com_apirest.edit.urls.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }
        
        return $data;
    }
    
    public function registrarUrl( $dataUrl )
    {
        $dtaFrm     = json_decode( $dataUrl );
        
        $dtaLstIps  = $this->_ips2Long( $dtaFrm->strIPInstitucion_api );
        $dtaUrl["intIdApiUrl"]          = $dtaFrm->intIdApiUrl;
        $dtaUrl["intCodigo_ins"]        = $dtaFrm->intCodigo_ins;
        $dtaUrl["strIPInstitucion_api"] = $dtaLstIps;
        $dtaUrl["strUrl_api"]           = 'com_apirest&token='. md5( $dtaLstIps ) .'&task=urls.indicadores';
        $dtaUrl["strNombres_api"]       = $dtaFrm->strNombres_api;
        $dtaUrl["strCorreo_api"]        = $dtaFrm->strCorreo_api;
        $dtaUrl["dteFechaInicio_api"]   = $dtaFrm->dteFechaInicio_api;
        $dtaUrl["dteFechaFin_api"]      = $dtaFrm->dteFechaFin_api;
        $dtaUrl["strToken_api"]         = md5( $dtaLstIps );

        $tbApi = $this->getTable( 'Url', 'ApiRestTable' );
        return $tbApi->registrarUrl( $dtaUrl );
    }
    
    
    private function _ips2Long( $strIPInstitucion )
    {
        $lst = array();
        $lstIps = explode( ',', $strIPInstitucion );
        
        foreach( $lstIps as $ip ){
            $lst[] = ip2long( trim( $ip ) );
        }
        
        return implode( ',', $lst );
    }
    
    /**
     * 
     * Gestiona la carga de Imagenes
     * 
     */
    public function saveUploadFiles( $idUrl )
    {
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . 'apirest';
        $up_file = new upload( "Filedata", NULL, $path, $idUrl );

        return $up_file->save();
    }
    
    
    
    public function existeDocumento( $idUrl )
    {
        $ban = FALSE;
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . 'apirest';

        if( file_exists( $path ) ){
            $directorio = opendir( $path );
            while( $archivo = readdir( $directorio ) ){
                if( $archivo != "." && $archivo != ".." ){
                    $dtaFile = explode( '.', $archivo );
                    $ban = ( (int)$dtaFile[0] == (int)$idUrl )  ? $archivo 
                                                                : FALSE;
                }
            }

            closedir( $directorio );
        }

        return $ban;
    }
    
    
    
    public function delDocumento( $idDocumento )
    {
        $ban = false;
        $nameArchivo = $this->existeDocumento( $idDocumento );

        if( $nameArchivo ){
            $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . 'apirest' . DS . $nameArchivo;
            $ban = unlink( $path );
        }

        return $ban;
    }

    public function updVigencia( $idUrl )
    {
        $tbApi = $this->getTable();
        $tbApi->updVigencia( $idUrl );
        
        return $this->_getVigencia( $idUrl );
    }
    
    
    private function _getVigencia( $idUrl )
    {
        $tbApi = $this->getTable();
        $tbApi->load( $idUrl );

        return (int)$tbApi->intVigente_Api;
    }
    
}