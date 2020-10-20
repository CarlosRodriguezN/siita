<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso /Edicion de un Programa
 */
class contratosViewConvenio extends JView
{

    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $action = JRequest::getVar( 'action' );

        switch( true ) {
            case( $action == 'getSubProgramas' ):
                $dataFormulario = JRequest::getVar( 'idPrograma' );
                $mdContrato = $this->getModel();
                $lstSubProgramas = $mdContrato->getSubProgramas( $dataFormulario );
                echo json_encode( $lstSubProgramas );
                break;
            case( $action == 'getProyectos' ):
                $dataFormulario = JRequest::getVar( 'idPrograma' );
                $mdContrato = $this->getModel();
                // guardamos la data
                $lstProyectos = $mdContrato->getProyectos( $dataFormulario );
                //  Obtengo el Id de registro del programa
                echo json_encode( $lstProyectos );
                break;
            case($action == 'getDataPersona' ):
                $dataFormulario = JRequest::getVar( 'idPersona' );
                $mdContrato = $this->getModel();
                // guardamos la data
                $dataPersona = $mdContrato->getDataPersona( $dataFormulario );
                //  Obtengo el Id de registro del programa
                echo json_encode( $dataPersona );
                break;
            case($action == 'getDataPersonaFiscalizador' ):
                $dataFormulario = JRequest::getVar( 'idFiscalizador' );
                $mdContrato = $this->getModel();
                // guardamos la data
                $dataPersonaFiscalizador = $mdContrato->getDataPersonaFicalizador( $dataFormulario );
                //  Obtengo el Id de registro del programa
                echo json_encode( $dataPersonaFiscalizador );
                break;
            case($action == 'getCantonesContrato' ):
                $dataFormulario = JRequest::getVar( 'idProvincia' );
                $mdContrato = $this->getModel();
                // guardamos la data
                $dataCantones = $mdContrato->getCantones( $dataFormulario );
                //  Obtengo el Id de registro del programa
                echo json_encode( $dataCantones );
                break;
            case($action == 'getParroquias' ):
                $dataFormulario = JRequest::getVar( 'idCanton' );
                $mdContrato = $this->getModel();
                // guardamos la data
                $dataCantones = $mdContrato->getParroquias( $dataFormulario );
                //  Obtengo el Id de registro del programa
                echo json_encode( $dataCantones );
                break;
            case($action == 'saveDataContrato' ):
                $idContrato = $this->savedata();
                echo json_encode( $idContrato );
                break;
            case($action == 'delDocumento' ):
                $nameArchivo = JRequest::getVar( 'nameArchivo' );
                $idContrato = JRequest::getVar( 'idContrato' );
                $mdContrato = $this->getModel();
                $retval= $mdContrato->delArchivoContrato( $idContrato, $nameArchivo );
                echo json_encode( $retval );
                break;
        }
    }

    
    /**
     * 
     * Arma la información para ser guardada.
     * 
     * @return int  retorna el identificador del convenio.
     */
    public function savedata()
    {
        $dataFormulario = JRequest::getVar( 'dataSaveContrato' );
        $data = json_decode( $dataFormulario );
        $mdContrato = $this->getModel();
        
        $dataGeneral            = $this->dataGeneral( $data->dataGeneral );
        $dataAtributos          = $data->dataGeneral->lstAtributos;
        $dataGarantias          = $data->dataGeneral->lstGarantias;
        $dataMultas             = $data->dataGeneral->lstMultas;
        $dataContratistaContrato= $data->dataGeneral->lstContratistas;
        $dataFiscalizadores     = $data->dataGeneral->lstFiscalizadores;
        $dataProrrogas          = $data->dataGeneral->lstProrrogas;
        $dataPlanesPagos        = $data->dataGeneral->lstPlanesPagos;
        $dataFacturas           = $data->dataGeneral->lstFacturas;
        $anticipo               = $data->dataGeneral->anticipo;
        $graficos               = $data->dataGeneral->lstGraficos;
        $lstIndicadores         = $data->lstIndicadores;
        $lstObjetivos           = $data->lstObjetivos;
        $unidadesTerritoriales  = $data->dataGeneral->lstUnidadesTerritoriales;
        $idContrato = $mdContrato->saveDataForm( $dataGeneral, $dataAtributos, $dataGarantias, $dataMultas, $dataContratistaContrato, $dataFiscalizadores, $dataProrrogas, $dataPlanesPagos, $dataFacturas, $anticipo, $graficos, $unidadesTerritoriales, $lstIndicadores,$lstObjetivos );
        return $idContrato;
    }

    /**
     * Arma el Array de información general
     * @param type $data
     * @return type
     */
    public function dataGeneral( $data )
    {
        $general["intIdContrato_ctr"]    = (int) $data->contratoGen->intIdContrato_ctr;
        $general["intCodigo_pry"]        = (int) $data->contratoGen->intCodigo_pry;
        $general["intIdTipoContrato_tc"] = (int) $data->contratoGen->intIdTipoContrato_tc;
        $general["intIdPartida_pda"]     = (int) $data->contratoGen->intIdPartida_pda;
        $general["intIdFiscalizador_fc"] = (int) $data->contratoGen->intIdFiscalizador_fc;
        $general["strCodigoContrato_ctr"]= $data->contratoGen->strCodigoContrato_ctr;
        $general["strCUR_ctr"]           = $data->contratoGen->strCUR_ctr;
        $general["dcmMonto_ctr"]         = $data->contratoGen->dcmMonto_ctr;
        $general["intNumContrato_ctr"]   = $data->contratoGen->intNumContrato_ctr;
        $general["strDescripcion_ctr"]   = $data->contratoGen->strDescripcion_ctr;
        $general["strObservacion_ctr"]   = $data->contratoGen->strObservacion_ctr;
        $general["idEntidad"]            = (int) $data->contratoGen->intIdentidad_ent;
        $general["published"]            = (int) $data->contratoGen->published;
        $general["strObservacion_ctr"]   = $data->contratoGen->strObservacion_ctr;
        $general["dteFechaInicio_ctr"]   = $data->contratoGen->dteFechaInicio_ctr;
        $general["dteFechaFin_ctr"]      = $data->contratoGen->dteFechaFin_ctr;
        $general["intPlazo_ctr"]         = $data->contratoGen->intPlazo_ctr;
        $general["intcodigo_unimed"]     = $data->contratoGen->intcodigo_unimed;
        
        // CAMPOS DE LA UNIDAD DE GESTION RESPONSABLE
        $general["idUGR"]                = $data->contratoGen->idUGR;
        $general["fchIniciUGR"]          = $data->contratoGen->fchIniciUGR;
        // CAMPOS DEL FUNCIONARIO RESPONSABLE
        $general["idResponsable"]        = $data->contratoGen->idResponsable;
        $general["fchIniciRes"]          = $data->contratoGen->fchIniciRes;
        // URL tableU
        $general["urlTableU"]            = $data->contratoGen->urlTableU;
        return $general;
    }

}