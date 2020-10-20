<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );
//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista Categoria
 */
class FuncionariosViewFuncionario extends JView
{
    protected $_idUsr;
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

        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = FuncionariosHelper::getActions();
        
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        
        //  var_dump( $this->form ); exit;
        
        $this->script = $script;
        
        //  var_dump( $item->intIdentidad_ent ); exit;
        
        //  Asigna la data relacionada con el funcionario
        if( $item->intCodigo_fnc != 0 ){
            //  Instancia del modelo
            $mdFuncionario = $this->getModel();
            $this->anioVigente = $mdFuncionario->getAnioPlnVigente();

            //  Lista de POAs del funcionario
            $this->lstPoasFnc = $mdFuncionario->lstPoasFnc( $item->intIdentidad_ent );
            $this->items = $this->_getLstObjs();

            //  Listo todos los programas asociados al Funcionario
            $this->lstProgramas = $mdFuncionario->lstProgramasFnc( $item->intCodigo_fnc );

            //  Listo todos los proyectos asociados al Funcionario
            $this->lstProyectos = $mdFuncionario->lstProyectosFnc( $item->intCodigo_fnc );

            //  Listo todos los contratos asociados al Funcionario
            $this->lstContratos = $mdFuncionario->lstContratosFnc( $item->intCodigo_fnc );

            //  Listo todos los convenios asociados al Funcionario
            $this->lstConvenios = $mdFuncionario->lstConveniosFnc( $item->intCodigo_fnc );
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

        $bar->appendButton( 'Standard', 'organization', JText::_( 'COM_FUNCIONARIO_ORGANIGRAMA' ), 'funcionario.organigrama', false );
        $bar->appendButton( 'Standard', 'control', JText::_( 'COM_FUNCIONARIO_CONTROL_AND_MONINOTING' ), 'funcionario.panel', false );
        //  $bar->appendButton( 'Standard', 'list', JText::_( 'COM_FUNCIONARIO_EVENTO_LISTAR' ), 'funcionario.list', false );

        //and make whatever calls you require
        if( $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_FUNCIONARIO_EVENTO_GUARDAR' ), 'funcionario.registrar', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_FUNCIONARIO_EVENTO_GUARDAR_SALIR' ), 'funcionario.registrarSalir', false );
        }

        
        if( $this->canDo->get( 'core.delete' ) ){
            $del = $this->_avalibleDel();
            if( $del ){
                $bar->appendButton( 'Standard', 'delete', JText::_( 'COM_FUNCIONARIO_EVENTO_ELIMINAR' ), 'funcionario.eliminar', false );
            }
        }

        $bar->appendButton( 'Separator' );
        
        $bar->appendButton( 'Separator' );
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_FUNCIONARIO_EVENTO_CANCELAR' ), 'funcionario.cancel', false );

        //generate the html and return
        return $bar->render();
    }

    /**
     * Metodo para establecer las propiedades del documento
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
                '<![endif]-->' . "\n" .
                '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
        );

        //  hoja de estilos para los formularios en backgrawn
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );

        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadfive/uploadifive.css' );

        //  Hoja de estilos para la iconografia de objetivos
        $document->addStyleSheet( JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css' );

        //  Hoja de estilos para la iconografia de los objetivos
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Adjunto libreria para el Idioma
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/ES_OBJETIVO.js' );
        
        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria java script upladifi
        $document->addScript( JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );

        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );


        //  Adjunto librerias para la gestion de POA's
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/Poa.js' );
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/GestionPoa.js' );
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/GestionPoas.js' );

        //  Adjunto librerias para la gestion de Objetivos de un POA
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/Objetivo.js' );
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/GestionObjetivo.js' );
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/GestionObjetivos.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/Reglas.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/Indicador.js' );
        
        //  Adjunto libreria que controla las llamadas ajax de la gestion de funcionarios 
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionario/assets/Funcionario.js' );
        
        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );
        
        //  Adjunto el script para la carga de la lista de POA's
        $document->addScriptDeclaration( $this->_getLstPoas(), $type = 'text/javascript' );

        JText::script( 'COM_FUNCIONARIOS_ERROR_UNACCEPTABLE' );
    }

    private function _getLstPoas()
    {

        $retval = '';

        //  Lista de Poas de una Unidad de Gestión
        $retval .= 'objLstPoas = new GestionPoas();';

        //  lista de poas de la unidad de gestion 
        if( $this->lstPoasFnc ){
            foreach( $this->lstPoasFnc as $poa ){
                $retval .= 'var objPoa = new Poa();';
                $retval .= 'objPoa.setDtaPoa( ' . json_encode( $poa ) . ' );';
                $retval .= 'objLstPoas.addPoa( objPoa );';
            }
        }

        return $retval;
    }

    private function _getLstObjs()
    {
        if( $this->lstPoasFnc && count( $this->lstPoasFnc ) > 0 ){
            $lstObjetivos = $this->lstPoasFnc[0]->lstObjetivos;
            // Recorro los POAs
            if( $this->lstPoasFnc ){
                foreach( $this->lstPoasFnc AS $Poa ){
                    //  Obtengo los objetivos de un POA y recorro la lista de Objetivos
                    $lstObjs = $Poa->lstObjetivos;
                    if( $lstObjs ){
                        foreach( $lstObjs AS $objetivo ){
                            //  Si flag es true se agrega al array de objetivos UG
                            $flag = true;
                            //  Recorre el array de objetivos UG para verificar que no se repita
                            foreach( $lstObjetivos AS $objUg ){
                                if( (int) $objetivo->idObjetivo == (int) $objUg->idObjetivo ){
                                    $flag = false;
                                }
                            }

                            //  Si la vandera es false ese objetivo ya existe en el array
                            if( $flag ){
                                $lstObjetivos[] = $objetivo;
                            }
                        }
                    }
                }
            }
        }
        
        return $lstObjetivos;
    }

    /**
     *  Retorna TRUE en el caso que se pueda ELIMINAR un registro si no retorna FALSE 
     * @return boolean
     */
    private function _avalibleDel()
    {
        $reslut = true;
        if( count( $this->lstPoasFnc ) != 0 || count( $this->lstProgramas ) != 0 || count( $this->lstContratos ) != 0 || count( $this->lstConvenios ) != 0 || $this->item->intCodigo_fnc == 0 ){
            $reslut = false;
        }
        return $reslut;
    }
}
