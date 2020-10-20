<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso/Edicion de un plan estrategico institucional
 */
class UnidadGestionViewUnidadGestion extends JView
{
    protected $items;
    protected $canDo;


    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        // get the Data
        
        $form = $this->get( 'Form' );
        $item = $this->get( 'Item' );
        $script = $this->get( 'Script' );

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        
        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = UnidadGestionHelper::getActions();
        
        //  Control para la opcion de OPCIONES ADICIONALES
        $mdUndGes = $this->getModel();
        $this->opAdd = $mdUndGes->getOpAdd();

        //  Asigna la data relacionada con la Unidad de gestion
        if( $item->intCodigo_ug != 0 ) {
            
            $this->opsAdds = ( $item->strOpAdd_ug ) ? json_decode( $item->strOpAdd_ug ) 
                                                    : array();

            $this->anioVigente = $mdUndGes->getAnioPlnVigente();

            $this->lstPoasUG = $mdUndGes->lstPoasUG( $item->intIdentidad_ent );
            $this->items = $this->_getLstObjsUg();
            
            //  Listo todos los programas asociados a esta unidad de gestion
            $this->lstProgramas = $mdUndGes->lstProgramasUG( $item->intIdentidad_ent );
            
            //  Listo todos los proyectos asociados a esta unidad de gestion
            $this->lstProyectos = $mdUndGes->lstProyectosUG( $item->intIdentidad_ent );
            
            //  Listo todos los contratos asociados a esta unidad de gestion
            $this->lstContratos = $mdUndGes->lstContratosUG( $item->intIdentidad_ent );
            
            //  Listo todos los convenios asociados a esta unidad de gestion
            $this->lstConvenios = $mdUndGes->lstConveniosUG( $item->intIdentidad_ent );
            
            //  Listo los funcionarios asociados a la unidad de gestion
            $this->lstFuncionarios = $mdUndGes->lstFuncionariosUG( $item->intCodigo_ug );
        }

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        $bar->appendButton( 'Standard', 'organization', JText::_( 'COM_UNIDAD_GESTION_ORGANIGRAMA' ), 'unidadgestion.organigrama', false );
        $bar->appendButton( 'Standard', 'control', JText::_( 'COM_UNIDAD_GESTION_CONTROL_AND_MONINOTING' ), 'unidadgestion.panel', false );
        
        $bar->appendButton( 'Separator' );

        //  and make whatever calls you require
        if( $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_UNIDAD_GESTION_EVENTO_GUARDAR' ), 'unidadgestion.registro', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_UNIDAD_GESTION_EVENTO_GUARDAR_SALIR' ), 'unidadgestion.registrarSalir', false );
        }

        
        $bar->appendButton( 'Separator' );

        if( $this->canDo->get( 'core.delete' ) ){
        $del = $this->_avalibleDel();
            if( $del ) {
                $bar->appendButton( 'Standard', 'delete', JText::_( 'COM_UNIDAD_GESTION_EVENTO_ELIMINAR' ), 'unidadgestion.eliminar', false );
            }
        }

        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_UNIDAD_GESTION_EVENTO_CANCELAR' ), 'unidadgestion.cancel', false );
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
        $document->addStyleSheet( 'administrator/templates/system/css/system.css' );

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
       
        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadfive/uploadifive.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );
        
        //  Hoja de estilos para la iconografia de los objetivos
        $document->addStyleSheet( JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css' );

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );
        
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Adjunto libreria del lenguaje java-script de la vista de Unidad de Gestion
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/ES_OBJETIVO.js' );
        
        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria java script upladifi
        $document->addScript( JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria para alertas
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );
        
        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );

        //  Adjunto libreria para la gestion de los funcionarios de la unidad de gestion
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/Funcionario.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionFuncionarios.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionFuncionario.js' );

        //  Adjunto libreria para la gestion de la Unidad de Gestion
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/Poa.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionPoas.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionPoa.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/Reglas.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/Indicador.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'libraries/ecorae/objetivos/objetivo/indicadores/assets/Programacion.js' );

        //  Adjunto libreria para la gestion de la Unidad de Gestion
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/UnidadGestion.js' );

        //  Adjunto libreria para la gestion de los objetivos
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/Objetivo.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionObjetivos.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionObjetivo.js' );
        
        //  Adjunto libreria para la gestion de las opciones adicionales
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/OpcionAdicional.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionOpsAdicionales.js' );
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/unidadgestion/assets/GestionOpAdicional.js' );

        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );

        //  Adjunto el script para la carga de la lista de POA's
        $document->addScriptDeclaration( $this->_getLstData(), $type = 'text/javascript' );

        //  Adjunto el script para la carga de lista de funcionarios
        $document->addScriptDeclaration( $this->_getLstFuncionarios(), $type = 'text/javascript' );

        //  Adjunto el script para la carga de lista de funcionarios
        $document->addScriptDeclaration( $this->_getLstObjetivosUG(), $type = 'text/javascript' );
        
        //  Adjunto el script para la carga de lista de funcionarios
        $document->addScriptDeclaration( $this->_getLstOpsAdds(), $type = 'text/javascript' );

        JText::script( 'COM_UNIDAD_GESTION_UG_ERROR_UNACCEPTABLE' );
    }

    private function _getLstData() {

        $retval = '';

        //  Lista de Poas de una Unidad de Gestión
        $retval .= 'objLstPoas = new GestionPoas();';

        //  lista de poas de la unidad de gestion 
        if( $this->lstPoasUG ) {
            foreach( $this->lstPoasUG as $poa ) {
                $retval .= 'var objPoa = new Poa();';
                $retval .= 'objPoa.setDtaPoa( ' . json_encode( $poa ) . ' );';
                $retval .= 'objLstPoas.addPoa( objPoa );';
            }
        }

        return $retval;
    }

    /**
     * 
     * @return string
     */
    private function _getLstFuncionarios()
    {

        $retval = '';

        //  Lista de funcionarios de una unidad de gestión
        $retval .= 'objLstFuncionarios = new GestionFuncionarios();';

        //  lista de funcionarios
        if( $this->lstFuncionarios ) {
            foreach( $this->lstFuncionarios as $funcionario ) {
                $retval .= 'var objFuncionario = new Funcionario();';
                $retval .= 'objFuncionario.setDtaFuncionario( ' . json_encode( $funcionario ) . ' );';
                $retval .= 'objLstFuncionarios.addFuncionario( objFuncionario );';
            }
        }

        return $retval;
    }

   private function _getLstObjetivosUG()
   {
        $retval = '';

        //  Lista de Objetivos de un Pei
        $retval .= 'objLstObjetivo = new GestionObjetivos();';

        //  lista de objetivos del pei
        if( $this->lstObjetivosUG ) {
            foreach( $this->lstObjetivosUG as $objetivo ) {
                $retval .= 'var objObjetivo = new Objetivo();';
                $retval .= 'objObjetivo.setDtaObjetivo( ' . json_encode($objetivo) . ' );';
                $retval .= 'objLstObjetivo.addObjetivo( objObjetivo );';
            }
        }

        return $retval;
    }
    
    /**
     * 
     * @return string
     */
    private function _getLstOpsAdds()
    {
        //  Lista de opciones adicionales de la unidad de gestion
        $retval = '';
        $retval .= 'objLstOpsAdds = new GestionOpsAdds();';
        $lstGruposUsr = JFactory::getUser()->groups;
        if( !empty($this->opsAdds) ) {
            foreach( $this->opsAdds as $key=>$opAdd ) {
                $opAdd->registroOpAdd = $key;
                $opAdd->published = 1;
                $opAdd->disponibleUsr = ( in_array($opAdd->idGrupo, $lstGruposUsr) || in_array(8, $lstGruposUsr)) ? 1 : 0;
                $retval .= 'var opAdd = new OpcionAdicional();';
                $retval .= 'opAdd.setDtaOpAdd( ' . json_encode($opAdd) . ' );';
                $retval .= 'objLstOpsAdds.addOpAdd( opAdd );';
            }
        }
        return $retval;
    }

    
    /**
     * 
     * Retorna una lista de Objetivos de la Unidad de Gestion
     * 
     * @return type
     */
    private function _getLstObjsUg() {
        if ($this->lstPoasUG && count($this->lstPoasUG) > 0) {
            $lstObjetivosUG = $this->lstPoasUG[0]->lstObjetivos;

            // Recorro los POAs
            if( count($this->lstPoasUG) > 0 ){
                foreach( $this->lstPoasUG AS $Poa ) {
                    //  Obtengo los objetivos de un POA y recorro la lista de Objetivos
                    $lstObjs = $Poa->lstObjetivos;
                    if( count($lstObjs ) > 0) {
                        foreach ($lstObjs AS $objetivo) {
                            //  Si flag es true se agrega al array de objetivos UG
                            $flag = true;
                            //  Recorre el array de objetivos UG para verificar que no se repita
                            if ($lstObjetivosUG) {
                                foreach ($lstObjetivosUG AS $objUg) {
                                    if ((int) $objetivo->idObjetivo == (int) $objUg->idObjetivo) {
                                        $flag = false;
                                    }
                                }
                            }
                            //  Si la vandera es false ese objetivo ya existe en el array
                            if ($flag) {
                                $lstObjetivosUG[] = $objetivo;
                            }
                        }
                    }
                }
            }
        }

        return $lstObjetivosUG;
    }
    
    /**
     *  Retorna TRUE en el caso que se pueda ELIMINAR un registro si no retorna FALSE 
     * @return boolean
     */
    private function _avalibleDel()
    {
        $reslut = true;
        
        if( count( $this->lstPoasUG ) != 0 || 
                count( $this->lstProgramas ) != 0 || 
                count( $this->lstProyectos ) != 0 || 
                count( $this->lstContratos ) != 0 || 
                count( $this->lstConvenios ) != 0 || 
                count( $this->lstFuncionarios ) != 0){
            $reslut = false;
        }
        return $reslut;
    }

}
