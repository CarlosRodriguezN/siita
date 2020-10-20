<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );


// Inserto libreria de gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );

/**
 * 
 *  Controlador Contrato
 * 
 */
class contratosControllerContrato extends JControllerForm
{

    protected $view_list = 'contratos';

    protected function allowAdd( $data = array( ) )
    {
        return true;
    }

    protected function allowEdit( $data = array( ), $key = 'id' )
    {
        return true;
    }

    public function add()
    {
        parent::add();
    }
    
    public function listConvenios()
    {
        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&tpoContrato=2'
                . $this->getRedirectToItemAppend($recordId, $urlVar), false
            )
        );        
    }
    
    
    public function edit($key = null, $urlVar = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel();
        $table = $model->getTable();
        
        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        $context = "$this->option.edit.$this->context";

        // Determine the name of the primary key for the data.
        if (empty($key)) {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar)) {
            $urlVar = $key;
        }

        // Get the previous record id (if any) and the current record id.
        $recordId = (int) (count($cid) ? $cid[0] : JRequest::getInt($urlVar));
        $checkin = property_exists($table, 'checked_out');

        // Access check.
        if (!$this->allowEdit(array($key => $recordId), $key)) {
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );

            return false;
        }

        // Attempt to check-out the new record for editing and redirect.
        if ($checkin && !$model->checkout($recordId)) {
            // Check-out failed, display a notice but allow the user to see the record.
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');
            
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_item . '&tpoContrato='. JRequest::getVar( 'tpoContrato' ) 
                            . $this->getRedirectToItemAppend($recordId, $urlVar), false
                    )
            );

            return false;
        } else {
            // Check-out succeeded, push the new record id into the session.
            $this->holdEditId($context, $recordId);
            $app->setUserState($context . '.data', null);

            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_item . '&tpoContrato='. JRequest::getVar( 'tpoContrato' )
                            . $this->getRedirectToItemAppend($recordId, $urlVar), false
                    )
            );

            return true;
        }
    }
    
    
    
    public function save()
    {
        $data = JRequest::getVar( 'jform' );

        // Información general
        $dataGeneral            = $this->dataGeneral( $data );

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
        $lstObjetivos           = $data['dtaObjetivos'];
        $unidadesTerritoriales  = json_decode( $data['dataAtributo'] )->lstUnidadesTerritoriales;

        // recupero el modelo
        $modelContrato = $this->getModel();

        // redirecciono

        if( $modelContrato->saveDataForm( $dataGeneral, $dataAtributos, $dataGarantias, $dataMultas, $dataContratistaContrato, $dataFiscalizador, $dataProrrogas, $dataPlanesPagos, $dataFacturas, $anticipo, $graficos, $unidadesTerritoriales, $lstIndicadores,$lstObjetivos
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
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=contratos', false ) );
    }
}
