<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

JHTML::_('behavior.modal');

/**
 * Vista de Ingreso /Edicion de un Programa
 */
class contratosViewContrato extends JView
{
    protected $canDo;
    
    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null)
    {
        if( JRequest::getVar( 'tpoContrato' ) == 2 ){
            //  Cambio el lenguaje de Contratos a Convenios
            $languaje = JFactory::getLanguage();
            $languaje->load( 'com_convenios', JPATH_SITE );
        }

        //  Estos archivos van en busca de el modelo en /com_admin mapas/models        
        $item = $this->get('Item');
        $script = $this->get('Script');

        //  Controla que el registro este eliminado de manera logica
        if( $item->published == 0 && $item->intIdContrato_ctr != 0 ){
            JError::raiseError(500, implode('<br />', array( 0 => "Pagina no existe" )));
            return false;
        }

        //  Cargando la lista de atributos
        $atributos = $this->get('Atributos');

        //  Cargando la lista de garantias
        $garantias = $this->get('GarantiaContrato');

        //  Cargando la lista de garantias
        $graficos = $this->get('GraficosContrato');

        //  Cargando la lista de garantias
        $prorrogas = $this->get('ProrrogasContrato');

        //  Cargando la lista de pagos de un contrato
        $pagos = $this->get('PagosContrato');

        //  Cargando la lista de pagos de un contrato
        $unidadesTerritoriales = $this->get('UnidadTerritorial');

        //  Cargando el anticipo.
        $anticipo = $this->get('AnticipoContrato');

        //  Cargando la lista de pagos de un contrato
        $planesPagos = $this->get('PlanesPagoContrato');

        //  Cargando la lista de garantias
        $multas = $this->get('MultasContrato');

        //  Contratistas , contactos
        $contratistas = $this->get('ContratistasContrato');

        //  Lista de fiscalizadores de un contrato
        $fiscalizadores = $this->get('FiscalizadoresContrato');

        //  Lista de factura de un contrato
        $facturas = $this->get('FacturasContrato');

        // Identificador del programa
        $idPrograma = $this->get('ProgramaContrato');

        // Lista de documentos del contrato
        $lstDocsContrato = $this->get('DocsContratos');

        // var_dump($idPrograma);exit();
        $form = $this->get('Form');

        // Check for errors.
        if( count($errors = $this->get('Errors')) ){
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = ContratosHelper::getActions();

        // Assign the Data
        $this->idEntidad            = $item->intIdentidad_ent;
        $this->form                 = $form;
        $this->item                 = $item;
        $this->script               = $script;
        $this->atributos            = $atributos;
        $this->garantias            = $garantias;
        $this->prorrogas            = $prorrogas;
        $this->pagos                = $pagos;
        $this->anticipo             = $anticipo;
        $this->planesPagos          = $planesPagos;
        $this->facturas             = $facturas;
        $this->multas               = $multas;
        $this->graficos             = $graficos;
        $this->contratistas         = $contratistas;
        $this->fiscalizadores       = $fiscalizadores;
        $this->unidadesTerritoriales= $unidadesTerritoriales;
        $this->idPrograma           = $idPrograma;
        $this->lstDocsContrato      = $lstDocsContrato;

        //  Accedo al Modelo ( ProyectosModelProyecto )
        $modelo = $this->getModel();

        //  Obtengo la lista de indicadores de un proyecto
        $this->lstIndicadores = $modelo->lstIndicadores($item->intIdentidad_ent);

        //  Obtengo una lista de Imagenes cargadas en el sistema
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar('toolbar');
        if( $this->canDo->get('core.edit') ){
            $bar->appendButton('Standard', 'save', JText::_('COM_CONTRATO_EVENTO_GUARDAR'), 'contrato.save', false);
            $bar->appendButton('Standard', 'save', JText::_('COM_CONTRATO_EVENTO_GUARDAR_SALIR'), 'contrato.saveExit', false);
        }

        if( $this->canDo->get('core.delete') && $this->_availableDelete() && $this->item->intIdContrato_ctr != 0 ){
            $bar->appendButton('Standard', 'delete', JText::_('COM_CONTRATO_REPORTE_ELIMINAR'), 'contrato.delete', false);
        }

        $bar->appendButton('Separator');

        $bar->appendButton('Standard', 'organization', JText::_('COM_CONTRATO_ORGANIGRAMA'), 'contrato.organigrama', false);
        $bar->appendButton('Standard', 'control', JText::_('COM_CONTRATO_CONTROL_AND_MONINOTING'), 'contrato.panel', false);

        $bar->appendButton('Separator');

        if( JRequest::getVar( 'tpoContrato' ) == 2 ){
            $bar->appendButton('Standard', 'list', JText::_('COM_CONTRATO_EVENTO_LISTAR'), 'contrato.listConvenios', false);
        }else{
            $bar->appendButton('Standard', 'list', JText::_('COM_CONTRATO_EVENTO_LISTAR'), 'contrato.list', false);
        }
        
        $bar->appendButton('Standard', 'cancel', JText::_('COM_CONTRATO_REPORTE_CANCELAR'), 'contrato.cancel', false);

        //generate the html and return
        return $bar->render();
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();

        //  Accdemos a la hoja de estilos del administrador
        $document->addStyleSheet('administrator/templates/system/css/system.css');

        //  Agregamos hojas de estilo complementarias 
        $document->addCustomTag(
                '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />' . "\n\n" .
                '<!--[if IE 7]>' . "\n" .
                '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<!--[if gte IE 8]>' . "\n\n" .
                '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n"
        );

        //  hoja de estilos para los formularios
        $document->addStyleSheet(JURI::root() . 'media/system/css/siita/common.css');

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css');

        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadfive/uploadifive.css');

        //  Hoja de estilos para pestañas
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css');

        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  PlugIn jQuery uploadify
        $document->addScript(JURI::root() . 'media/system/js/uploadify/swfobject.js');

        //  Adjunto script JQuery sobre los eventos
        $document->addScript(JURI::root() . 'media/system/js/jquery-1.7.1.min.js');

        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript(JURI::root() . 'media/system/js/jquery.tablesorter.min.js');

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript(JURI::root() . 'media/system/js/jquery-noconflict.js');

        //  Adjunto libreria que permite el trabajo carga de imagenes
        $document->addScript(JURI::root() . 'media/system/js/uploadfive/jquery.uploadifive.min.js');

        // Adjunto libreria de la api de google.
        $document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry");

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/css/alerts/jquery.alerts.css');

        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-validate/cmxform.css');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/css/contratos.css');

        //  Archivo que gestiona formateo de numeros de tipo moneda
        $document->addScript(JURI::root() . 'media/system/js/FormatoNumeros.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript(JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js');

        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript(JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js');

        //  Adjunto librerias para la validacion de formularios
        $document->addScript(JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js');
        $document->addScript(JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js');
        $document->addScript(JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js');

        //  Planificacion Operativa de Contratos
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/plan/GestionPlanes.js');
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/plan/PlanObjetivo.js');
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/plan/PlanOperativo.js');

        //  Adjunto libreria para mostrar la ventana pop pop de guardado.
        $document->addScript(JURI::root() . 'media/system/js/bPopup.js');

        //  Adjunto libreria para administrar el mapa
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/documentos.js');

        //  Adjunto libreria para administrar el mapa
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/mapa.js');

        //  Adjunto libreria que gestiona las reglas
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/ruler.js');

        //  Adjunto libreria para gestionar las reglas de los campos
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/controles.js');

        //  Adjunto libreria que gestiona las coordenadas
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/coordenada.js');

        //  Adjunto libreria que gestiona las unidades territoriales
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/unidadterritorial.js');

        //  Adjunto libreria que gestiona los graficos de cun contrato.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/grafico.js');

        //  Adjunto libreria que gestiona la planilla.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/planilla.js');

        //  Adjunto libreria que gestiona los adelanros de un contrato.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/adelanto.js');

        //  Adjunto libreria que gestiona los contratos
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/contratos.js');

        //  Adjunto libreria que gestiona los pagos de una facturas de un contrato.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/facturapago.js');

        //  Adjunto libreria que gestiona los pagos
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/pago.js');

        //  Adjunto libreria que gestiona las facturas
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/factura.js');

        //  Adjunto libreria que gestiona las planes de pago
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/planpago.js');

        //  Adjunto libreria que gestiona las prorrogas de un contrato.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/prorroga.js');

        //  Adjunto libreria que gestiona las gestion de un contrato.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/save.js');

        //  Adjunto libreria que gestiona las llamadas AJAX
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/ajaxCall.js');

        //  Adjunto libreria que gestiona los contratistas de un contrato
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/contratista.js');

        //  Adjunto libreria que gestiona las multas
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/multas.js');

        //  Adjunto libreria que gestiona las fiscalizadores 
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/fiscalizador.js');

        //  Adjunto libreria que gestiona los contactos de un contrato.
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/contactos.js');

        //  Adjunto libreria que gestiona las garantias de un contrato
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/garantias.js');

        // Gestiona los lenguajes en java script
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/Common.js');

        // Gestiona los lenguajes en java script
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/uploadFather.js');

        //  INDICADORES
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/Indicador.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/Dimension.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/Common.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndFijos.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndGAP.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndEIgualdad.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndEEcorae.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionIndicador.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/OtrosIndicadores.js');
        $document->addScript(JURI::root() . 'components/com_indicadores/views/assets/IndicadorFijo/GestionOtrosIndicadores.js');

        //  Adjunto libreria que la gestion de Objetivos
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/GestionObjetivo.js');
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/GestionObjetivos.js');
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/Objetivo.js');
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/Objetivos.js');

        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/ES_OBJETIVO.js');

        //  Adjunto libreria que gestiona las reglas
        $document->addScript(JURI::root() . 'components/com_contratos/views/contrato/assets/estadosGarantia.js');

        //  Adjunto lista de Arreglos de unidades territoriales
        $document->addScriptDeclaration($this->arrayUnidadesTerritorialesContraro(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayFiscalizadoresContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Graficos de un contrato.
        $document->addScriptDeclaration($this->arrayGraficosContrato(), $type = 'text/javascript');

        //  Adjunto objeto anticipo de un contrato.
        $document->addScriptDeclaration($this->objAnticipoContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayPagosContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayPlanesPagosContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayFacturasContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayProrrogasContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayAtributosContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos de Garantias contrato
        $document->addScriptDeclaration($this->arrayGarantiasContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos de Garantias contratistas
        $document->addScriptDeclaration($this->arrayContratistaContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos Atributos contrato
        $document->addScriptDeclaration($this->arrayMultasContrato(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos de Sub contratos
        $document->addScriptDeclaration($this->arrayContratosData(), $type = 'text/javascript');

        //  Adjunto lista de Arreglos de Sub contratos
        $document->addScriptDeclaration($this->arrayDocsContratos(), $type = 'text/javascript');
    }

    public function _availableDelete()
    {
        $result = false;
        if( empty($this->atributos) &&
                empty($this->garantias) &&
                empty($this->prorrogas) &&
                empty($this->planesPagos) &&
                empty($this->multas) &&
                empty($this->graficos) &&
                empty($this->contratistas) &&
                empty($this->fiscalizadores) &&
                empty($this->unidadesTerritoriales) ){
            $result = true;
        }
        return $result;
    }

    /**
     * arma el objeto java escrip de contratos.
     */
    public function arrayContratosData()
    {
        $arrayContratos = 'data = new Object();';
        $arrayContratos .= 'data.lstAtributos = lstAtributos;';
        $arrayContratos .= 'data.lstGarantias = lstGarantias;';
        $arrayContratos .= 'data.lstMultas = lstMultas;';
        $arrayContratos .= 'data.lstContratistas = lstContratistas;';
        $arrayContratos .= 'data.lstFiscalizadores = lstFiscalizadores;';
        $arrayContratos .= 'data.lstProrrogas = lstProrrogas;';
        $arrayContratos .= 'data.lstPagos = lstPagos;';
        $arrayContratos .= 'data.lstFacturas = lstFacturas;';
        $arrayContratos .= 'data.lstPlanesPagos = lstPlanesPagos;';
        $arrayContratos .= 'data.anticipo = anticipo;';
        $arrayContratos .= 'data.lstGraficos = lstGraficos;';
        $arrayContratos .= 'data.lstUnidadesTerritoriales = lstUnidadesTerritoriales;';
        $arrayContratos .= 'var contratos = new Contratos(data);';
        //  Lista de Objetivos Especificos

        return $arrayContratos;
    }

    /**
     * recupera la lista de atributos de un contrato para transformarlo en objetos javaScript
     * @return string
     */
    public function arrayAtributosContrato()
    {
        $atributos = $this->atributos;
        $scripAtributos = "var lstAtributos = new Array();";
        if( !empty($atributos) ){
            foreach( $atributos AS $atributo ){
                $scripAtributos.='var atributo= [];';
                $scripAtributos.='atributo["idAtributo"] = ' . $atributo->idAtributo . ';';
                $scripAtributos.='atributo["codAtributo"] ="' . $atributo->codAtributo . '";';
                $scripAtributos.='atributo["nombre"] = "' . $atributo->nombre . '";';
                $scripAtributos.='atributo["valor"] = "' . number_format($atributo->valor, 2, '.', '') . '";';
                $scripAtributos.='atributo["published"] = ' . $atributo->published . ';';
                $scripAtributos.='lstAtributos.push(atributo);';
            }
        }
        return $scripAtributos;
    }

    /**
     * recupera las lista de garantias de un contrato para transformarlos en objetos java script
     * @return string
     */
    public function arrayGarantiasContrato()
    {
        $garantias = $this->garantias;
        $scripGarantias = "var lstGarantias = new Array();";
        if( !empty($garantias) ){
            foreach( $garantias AS $garantia ){
                $scripGarantias.='var garantia= [];';
                $scripGarantias.='garantia["idGarantia"] = ' . $garantia->idGarantia . ';';
                $scripGarantias.='garantia["idTipoGarantia"] ="' . $garantia->idTipoGarantia . '";';
                $scripGarantias.='garantia["idFormaGarantia"] ="' . $garantia->idFormaGarantia . '";';
                $scripGarantias.='garantia["codGarantia"] = "' . $garantia->codGarantia . '";';
//                $scripGarantias.='garantia["monto"] = "' .          number_format($garantia->monto, 2, '.','') . '";';
                $scripGarantias.='garantia["monto"] = "' . $garantia->monto . '";';
                $scripGarantias.='garantia["fchDesde"] = "' . $garantia->fchDesde . '";';
                $scripGarantias.='garantia["fchHasta"] = "' . $garantia->fchHasta . '";';
                $scripGarantias.='var estadosGarantia  = new Array();';
                foreach( $garantia->estados AS $estado ){
                    $dtaFecha = explode(' ', $estado->fchRegistro);
                    $scripGarantias.='var estado= [];';
                    $scripGarantias.='estado["idGarantiaEstado"]=' . $estado->idGarantiaEstado . ';';
                    $scripGarantias.='estado["idEstadoGarantia"]=' . $estado->idEstadoGarantia . ';';
                    $scripGarantias.='estado["nmbEstadoGarantia"]="' . $estado->nmbEstadoGarantia . '";';
                    $scripGarantias.='estado["fchRegistro"]="' . $dtaFecha[0] . '";';
                    $scripGarantias.='estado["observacion"]="' . $estado->observacion . '";';
                    $scripGarantias.='estado["published"]=' . $estado->published . ';';
                    $scripGarantias.='estado["estadoAct"]=' . $estado->estadoAct . ';';
                    $scripGarantias.='estadosGarantia.push(estado);';
                }
                $scripGarantias.='garantia["estados"] = estadosGarantia;';
                $scripGarantias.='garantia["published"] = ' . $garantia->published . ';';
                $scripGarantias.='lstGarantias.push(garantia);';
            }
        }
        return $scripGarantias;
    }

    /**
     * recupera el array de contratistas con sus contactos para transformarlos en objetos java script
     * @return string
     */
    public function arrayContratistaContrato()
    {
        $contratistas = $this->contratistas;
        $scripContratista = "var lstContratistas = new Array();";
        if( !empty($contratistas) ){
            foreach( $contratistas AS $contratista ){
                $scripContratista.='var contratista= [];';
                $scripContratista.='contratista["idContratistaContrato"]=' . $contratista->idContratistaContrato . ';';
                $scripContratista.='contratista["idContratista"]="' . $contratista->idContratista . '";';
                $scripContratista.='contratista["strContratista"]="' . $contratista->strContratista . '";';
                $scripContratista.='contratista["idContrato"]="' . $contratista->idContrato . '";';
                $scripContratista.='contratista["fechaInicio"]="' . $contratista->fechaInicio . '";';
                $scripContratista.='contratista["fechaFin"]="' . $contratista->fechaFin . '";';
                $scripContratista.='contratista["fechaRegistro"]="' . $contratista->fechaRegistro . '";';
                $scripContratista.='contratista["observacion"]="' . str_replace(array( "\r", "\n" ), '', (string)$contratista->observacion) . '";';
                $scripContratista.='contratista["published"]=' . $contratista->published . ';';
                $scripContratista.='var contactosContratista= new Array();';
                if( $contratista->contactos ){
                    foreach( $contratista->contactos AS $contacto ){
                        $scripContratista.='var contacto=[];';
                        $scripContratista.='contacto["idContacto"]=' . $contacto->idContacto . ';';
                        $scripContratista.='contacto["idCargo"]=' . $contacto->idCargo . ';';
                        $scripContratista.='contacto["idPersona"]=' . $contacto->idPersona . ';';
                        $scripContratista.='contacto["perApellido"]="' . $contacto->perApellido . '";';
                        $scripContratista.='contacto["perNombre"]="' . $contacto->perNombre . '";';
                        $scripContratista.='contacto["perCedula"]="' . $contacto->perCedula . '";';
                        $scripContratista.='contacto["perCorreo"]="' . $contacto->perCorreo . '";';
                        $scripContratista.='contacto["perTelefono"]="' . $contacto->perTelefono . '";';
                        $scripContratista.='contacto["perCelular"]= "' . $contacto->perCelular . '";';
                        $scripContratista.='contacto["cgoCargo"]="' . str_replace(array( "\r", "\n" ), '', (string)$contacto->cgoCargo) . '";';
                        $scripContratista.='contacto["published"]= "' . $contacto->published . '";';
                        $scripContratista.='contactosContratista.push(contacto);';
                    }
                }
                $scripContratista.='contratista["contactos"]= contactosContratista;';
                $scripContratista.='lstContratistas.push(contratista);';
            }
        }
        return $scripContratista;
    }

    /**
     * Recupera el array de estados de garantia que se transformara en un objeto javaScript
     * @param type $ltsGarantia
     */
    public function arrayEstadosGarantia($ltsEstadosGarantia)
    {
        $scripGarantias = "var lstEstadosGarantias = new Array();";
        foreach( $ltsEstadosGarantia->estados AS $estado ){
            $scripGarantias.='var estado=[];';
            $scripGarantias.='estado["idGarantiaEstado"]="' . $estado->idGarantiaEstado . '";';
            $scripGarantias.='estado["idEstadoGarantia"]="' . $estado->idEstadoGarantia . '";';
            $scripGarantias.='estado["nmbEstadoGarantia"]="' . $estado->nmbEstadoGarantia . '";';
            $scripGarantias.='estado["fchRegistro"]="' . $estado->fchRegistro . '";';
            $scripGarantias.='estado["observacion"]="' . $estado->observacion . '";';
            $scripGarantias.='lstEstadosGarantias.push(estados);';
        }
        return $scripGarantias;
    }

    /**
     * recupera el array que se transoramara en objetos javaScritp de multas de un contrato.
     * @return string
     */
    public function arrayMultasContrato()
    {
        $multas = $this->multas;
        $scripMultas = "var lstMultas = new Array();";
        if( !empty($multas) ){
            foreach( $multas AS $multa ){
                $scripMultas.='var multa=[];';
                $scripMultas.='multa["idMulta"] = ' . $multa->idMulta . ';';
                $scripMultas.='multa["codMulta"] = "' . $multa->codMulta . '";';
                $scripMultas.='multa["monto"] = ' . $multa->monto . ';';
                $scripMultas.='multa["observacion"] = "' . $multa->observacion . '";';
                $scripMultas.='multa["published"] = ' . $multa->published . ';';
                $scripMultas.='lstMultas.push(multa);';
            }
        }
        return $scripMultas;
    }

    /**
     * Lista de prorrogas.
     * @return string
     */
    public function arrayProrrogasContrato()
    {
        $prorrogas = $this->prorrogas;
        $scripProrrogas = "var lstProrrogas = new Array();";
        if( !empty($prorrogas) ){
            foreach( $prorrogas AS $prorroga ){
                $JSONProrroga = json_encode($prorroga);
                $scripProrrogas.='var oProrroga = new Prorroga(' . $JSONProrroga . ');';
                $scripProrrogas.='oProrroga.regProrroga = lstProrrogas.length;';
                $scripProrrogas.='lstProrrogas.push( oProrroga );';
            }
        }
        return $scripProrrogas;
    }

    /**
     * Lista de pagos de un contrato.
     * @return string
     */
    public function arrayPagosContrato()
    {
        $pagos = $this->pagos;
        $scripPagos = "var lstPagos = new Array();";
        if( !empty($pagos) ){
            foreach( $pagos AS $pago ){
                $JSONPago = json_encode($pago);
                $scripPagos.='var oPago = new Pago(' . $JSONPago . ');';
                $scripPagos.='oPago.regPago = lstPagos.length + 1;';
                $scripPagos.='lstPagos.push( oPago );';
            }
        }
        return $scripPagos;
    }

    /**
     * Agrega el anticipo a un contrato.
     * @return string
     */
    public function objAnticipoContrato()
    {
        $scripAnticipo = false;
        if( !empty($this->anticipo) ){
            $anticipo = $this->anticipo;
            $JSONAnticipo = json_encode($anticipo);
            $idFacturaPago = ($anticipo->idFacturaPago) ? $anticipo->idFacturaPago : 0;
            $factura = ($anticipo->factura) ? json_encode($anticipo->factura) : null;
            $scripAnticipo.= 'anticipo = new Pago(' . $JSONAnticipo . ');';
            $scripAnticipo.= 'anticipo.idFacturaPago=' . $idFacturaPago . ';';
            $scripAnticipo.= 'var factura= new Factura(' . $factura . ');';
            $scripAnticipo.= 'anticipo.factura = factura;';
        } else{
            $scripAnticipo.= 'anticipo = new Pago(null);';
            $scripAnticipo.= 'anticipo.idFacturaPago=0;';
            $scripAnticipo.= 'var factura= new Factura(null);';
            $scripAnticipo.= 'anticipo.factura = factura;';
        }
        return $scripAnticipo;
    }

    /**
     * Lista de pagos de un contrato.
     * @return string
     */
    public function arrayPlanesPagosContrato()
    {
        $planesPagos = $this->planesPagos;
        $scripPlanPagos = "var lstPlanesPagos = new Array();";
        if( !empty($planesPagos) ){
            foreach( $planesPagos AS $planPago ){
                $JSONPlanPago = json_encode($planPago);
                $scripPlanPagos.='var oPlanPago = new PlanPago(' . $JSONPlanPago . ');';
                $scripPlanPagos.='oPlanPago.regPlanPago = lstPlanesPagos.length + 1;';
                $scripPlanPagos.='lstPlanesPagos.push( oPlanPago );';
            }
        }
        return $scripPlanPagos;
    }

    /**
     * Lista de prorrogas.
     * @return string
     */
    public function arrayFacturasContrato()
    {
        $facturas = $this->facturas;
        $scripFacturas = "var lstFacturas = new Array();";
        if( !empty($facturas) ){
            foreach( $facturas AS $factura ){
                $JSONFactura = json_encode($factura);
                $scripFacturas.='var oFactura = new Factura(' . $JSONFactura . ');';
                $scripFacturas.='oFactura.regFactura = lstFacturas.length + 1;';
                $scripFacturas.='lstFacturas.push( oFactura );';
            }
        }
        return $scripFacturas;
    }

    /**
     * Lista de graficos.
     * @return string
     */
    public function arrayGraficosContrato()
    {
        $graficos = $this->graficos;
        $scripGraficos = "var lstGraficos = new Array();";
        if( !empty($graficos) ){
            foreach( $graficos AS $grafico ){
                $JSONGrafico = json_encode($grafico);
                $scripGraficos.='var oGrafico = new Grafico(' . $JSONGrafico . ');';
                $scripGraficos.='oGrafico.regGrafico = lstGraficos.length + 1;';
                $scripGraficos.='lstGraficos.push( oGrafico );';
            }
        }
        return $scripGraficos;
    }

    /**
     * 
     * @return string
     */
    public function arrayFiscalizadoresContrato()
    {

        $fiscalizadores = $this->fiscalizadores;

        $scripFiscaliazadores = "var lstFiscalizadores = new Array();";

        if( !empty($fiscalizadores) ){

            foreach( $fiscalizadores AS $multa ){

                $scripFiscaliazadores.='var fiscalizador= [];';
                $scripFiscaliazadores.='fiscalizador["idFiscaContrato"] = ' . $multa->idFiscaContrato . ';';
                $scripFiscaliazadores.='fiscalizador["idFiscalizador"] = ' . $multa->idFiscalizador . ';';
                $scripFiscaliazadores.='fiscalizador["fchIncio"] = "' . $multa->fchIncio . '";';
                $scripFiscaliazadores.='fiscalizador["fchFin"] = "' . $multa->fchFin . '";';
                $scripFiscaliazadores.='fiscalizador["fschRegisto"] = "' . $multa->fschRegisto . '";';
                $scripFiscaliazadores.='fiscalizador["idPersona"] = "' . $multa->idPersona . '";';
                $scripFiscaliazadores.='fiscalizador["ruc"] = "' . $multa->ruc . '";';
                $scripFiscaliazadores.='fiscalizador["fchRegistoPersona"] = "' . $multa->fchRegistoPersona . '";';
                $scripFiscaliazadores.='fiscalizador["apellidos"] = "' . $multa->apellidos . '";';
                $scripFiscaliazadores.='fiscalizador["nombres"] = "' . $multa->nombres . '";';
                $scripFiscaliazadores.='fiscalizador["cedula"] = "' . $multa->cedula . '";';
                $scripFiscaliazadores.='fiscalizador["correo"] = "' . $multa->correo . '";';
                $scripFiscaliazadores.='fiscalizador["telefono"] = "' . $multa->telefono . '";';
                $scripFiscaliazadores.='fiscalizador["celular"] = "' . $multa->celular . '";';
                $scripFiscaliazadores.='fiscalizador["published"] = "' . $multa->published . '";';
                $scripFiscaliazadores.='lstFiscalizadores.push(fiscalizador);';
            }
        }
        return $scripFiscaliazadores;
    }

    /**
     * Recupera el java script de las unidades territoriales
     * @return string
     */
    public function arrayUnidadesTerritorialesContraro()
    {
        $unidadesTerritoriales = $this->unidadesTerritoriales;
        $scripUndsTerr = "var lstUnidadesTerritoriales = new Array();";
        if( !empty($unidadesTerritoriales) ){
            foreach( $unidadesTerritoriales AS $unidadTerritorial ){
                $JSONUnidadTerritorial = json_encode($unidadTerritorial);
                $scripUndsTerr.='var oUnidadTerritorial = new UnidadTerritorial(' . $JSONUnidadTerritorial . ');';
                $scripUndsTerr.='oUnidadTerritorial.regUnidadTerritorial = lstUnidadesTerritoriales.length + 1;';
                $scripUndsTerr.='lstUnidadesTerritoriales.push( oUnidadTerritorial );';
            }
        }
        return $scripUndsTerr;
    }

    /**
     * Arma la lista de archivos
     * 
     * @return string
     */
    public function arrayDocsContratos()
    {
        $documentos = $this->lstDocsContrato;
        $script = "var lstDocumentos = new Array();";
        if( !empty($documentos) ){
            foreach( $documentos AS $doc ){
                $script .='var doc = new Object();';
                $script .='doc.nameArchivo="' . $doc["nameArchivo"] . '";';
                $script .='doc.regArchivo="' . $doc["regArchivo"] . '";';
                $script .='doc.published="' . $doc["published"] . '";';
                $script .="lstDocumentos.push(doc);";
            }
        }
        return $script;
    }

}
