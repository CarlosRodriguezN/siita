<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );


// Inserto libreria de gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );

jimport('joomla.application.component.controller');

/**
 * 
 *  Controlador Convenio
 * 
 */
class contratosControllerConvenio extends JControllerForm
{

    protected $view_list = 'convenios';

    protected function allowAdd( $data = array( ) )
    {
        return true;
    }

    protected function allowEdit( $data = array( ), $key = 'id' )
    {
        return true;
    }

    public function editConvenio()
    {        
        $controller = JController::getInstance( 'Contrato' );
        
        $controller->execute( 'contrato.edit&amp;intIdContrato_ctr=16' );

        $controller->redirect(); 
    }

    public function listarConvenios()
    {
        $this->setRedirect(
            JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                    . $this->getRedirectToListAppend(), false
            )
        );
    }

    public function add()
    {
        parent::add();
    }

    public function save()
    {
        $data = JRequest::getVar( 'jform' );

        // Información general
        $dataGeneral = $this->dataGeneral( $data );

        $dataAtributos          = json_decode( $data['dataAtributo'] )->lstAtributos;
        $dataGarantias          = json_decode( $data['dataAtributo'] )->lstGarantias;
        $dataMultas             = json_decode( $data['dataAtributo'] )->lstMultas;
        $dataContratistaContrato= json_decode( $data['dataAtributo'] )->lstContratistas;
        $dataFiscalizador       = json_decode( $data['dataAtributo'] )->lstFiscalizadores;
        $dataProrrogas          = json_decode( $data['dataAtributo'] )->lstProrrogas;
        $dataPlanesPagos        = json_decode( $data['dataAtributo'] )->lstPlanesPagos;
        $dataFacturas           = json_decode( $data['dataAtributo'] )->lstFacturas;
        $anticipo               = json_decode( $data['dataAtributo'] )->anticipo;
        $graficos               = json_decode( $data['dataAtributo'] )->lstGraficos;
        $lstIndicadores         = $data['dataIndicadores'];
        $unidadesTerritoriales  = json_decode( $data['dataAtributo'] )->lstUnidadesTerritoriales;

        // recupero el modelo
        $modelContrato = $this->getModel();

        // redirecciono

        if( $modelContrato->saveDataForm( $dataGeneral, $dataAtributos, $dataGarantias, $dataMultas, $dataContratistaContrato, $dataFiscalizador, $dataProrrogas, $dataPlanesPagos, $dataFacturas, $anticipo, $graficos, $unidadesTerritoriales, $lstIndicadores
                )
        ) {
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
        }
    }

    /**
     * Arma el Array de información general
     * @param type $data
     * @return type
     */
    public function dataGeneral( $data )
    {
        $general["intIdContrato_ctr"]       = (int) $data["intIdContrato_ctr"];
        $general["intCodigo_pry"]           = (int) $data["intCodigo_pry"];
        $general["intIdTipoContrato_tc"]    = (int) $data["intIdTipoContrato_tc"];
        $general["intIdPartida_pda"]        = (int) $data["intIdPartida_pda"];
        $general["intIdFiscalizador_fc"]    = (int) $data["intIdFiscalizador_fc"];
        $general["strCodigoContrato_ctr"]   = $data["strCodigoContrato_ctr"];
        $general["strCUR_ctr"]              = $data["strCUR_ctr"];
        $general["dcmMonto_ctr"]            = $data["dcmMonto_ctr"];
        $general["intNumContrato_ctr"]      = $data["intNumContrato_ctr"];
        $general["strDescripcion_ctr"]      = $data["strDescripcion_ctr"];
        $general["strObservacion_ctr"]      = $data["strObservacion_ctr"];
        $general["idEntidad"]               = (int) $data["intIdentidad_ent"];
        $general["published"]               = $data["published"];

        return $general;
    }

    /**
     * Gestiona la carga de archivos de un contrato.
     */
    public function saveFiles()
    {
        $idContrato = JRequest::getVar( "idContrato" );
        
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . "contratos" . DS . $idContrato;
        $this->_makeDirPath( $path );

        $up_file = new upload( 'Filedata', NULL, $path, NULL );

        $up_file->save();

        $data['idContrato'] = $idContrato;
        $data['flag'] = true;

        echo json_encode( $data );
        exit();
    }

    /**
     * 
     * Crea el DIRECTORIO para los archivos de las Objetivos.
     * 
     * @param int $idPadre      Identificador del PEI|POA|PROGRAMAS.... la que pertenece el objetivo
     * @param int $idObjetivo   Identificador del OBJETIVO
     * @param int $tipo         Identificador del tipo
     *                          1 PEI
     *                          2 POA
     *                          3 PROGRAMAS
     */
    private function _makeDirPath( $path )
    {
        if( !(file_exists( $path )) ) {
            mkdir( $path, 0777, true );
        }
    }

    /**
     * 
     * @param type $path
     * @return boolean
     */
    private function _fileExist( $path )
    {
        $flag = false;
        if( !(file_exists( $path )) ) {
            $flag = true;
        }
        return $flag;
    }
    
    
    public function panel()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=convenios', false ) );
    }
    
    /**
     *  Permite cerrar secion en el sistema 
     */
    public function cerrarSesion()
    {
        JSession::checkToken( 'request' ) or jexit( JText::_( 'JInvalid_Token' ) );

        $app = JFactory::getApplication();

        // Perform the log in.
        $error = $app->logout();

        // Check if the log out succeeded.
        if( !($error instanceof Exception) ){
            // Get the return url from the request and validate that it is internal.
            $return = JRequest::getVar( 'return', '', 'method', 'base64' );
            $return = base64_decode( $return );
            if( !JURI::isInternal( $return ) ){
                $return = '';
            }

            // Redirect the user.
            $app->redirect( JRoute::_( $return, false ) );
        } else{
            $app->redirect( JRoute::_( 'index.php?option=com_users&view=login', false ) );
        }
    }
}
