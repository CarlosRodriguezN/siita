<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

/**
 * Vista de Ingreso /Edicion de un SubProgramaPrograma
 */
class MantenimientoViewAgenda extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        
        //  Controla si se puede modificar la estructura de la agenda
        $this->avalibleUpdEst = true;
        
        //  Asigna la data relacionada con la Agenda
        if( $item->intIdAgenda_ag != 0 ) {
            //  Obtengo el modelo de agenda (MantenimientoModelAgenda)
            $mdAgenda = $this->getModel();
            
            //  Obtiene la lista de detalles de una determinada agenda
            $this->lstDetallesAgd = $mdAgenda->getDetallesAgd( $item->intIdAgenda_ag );
            
            //  Obtiene la estructura de la agenda
            $this->estructuraAgd = $mdAgenda->getEstructuraAgd( $item->intIdAgenda_ag );
            
            //  Lista los items con los que cuenta la agenda 
            $this->lstItemsAgd = $mdAgenda->getItemsAgd( $this->estructuraAgd );

            //  Bandera para poder editar la estructura de una agenda
            $this->avalibleUpdEst = $mdAgenda->avalibleUpdEst ($item->intIdAgenda_ag);
            
            //  Bandera para poder eliminar una agenda
            $this->canDelete = $mdAgenda->availableDelAgd ($item->intIdAgenda_ag);
            
        }

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar() {
        $bar = new JToolBar('toolbar');

        //and make whatever calls you require
        $bar->appendButton('Standard', 'save',JText::_('COM_MANTENIMIENTO_GUARDAR'), 'agenda.save', false);
        $bar->appendButton('Standard', 'save',JText::_('COM_MANTENIMIENTO_GUARDAR_SALIR'), 'agenda.saveExit', false);
        
//        if( $this->canDo->get( 'core.delete' ) && $this->canDelete && $this->item->intIdContrato_ctr != 0  ){
        if( $this->item->intIdAgenda_ag != 0 && $this->canDelete ){
            $bar->appendButton('Standard', 'delete', JText::_('COM_MANTENIMIENTO_ELIMINAR'), 'agenda.delete', false);
        }
        
        $bar->appendButton('Separator');
        
        $bar->appendButton('Standard', 'cancel', JText::_('COM_MANTENIMIENTO_CANCELAR'), 'agenda.cancel', false);

        //generate the html and return
        return $bar->render();
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() {
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

        //  Accdemos a la hoja de estilos de uploadify
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadify/uploadify.css');

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para la vista de arboles
        $document->addStyleSheet( JURI::root() . 'media/system/css/treeview/jquery.treeview.css' );
        $document->addStyleSheet( JURI::root() . 'media/system/css/treeview/screen.css' );
        
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );
        
        //  Adjunto libreria para alertas
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria para armar los la vista de arbol
        $document->addScript( JURI::root() . 'media/system/js/treeview/jquery.treeview.js' );
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );
        
        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/additional-methods.js' );
        
        //  Adjunto libreria que controla la gestion de la agenda
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/Agenda.js' );
        
        //  Adjunto libreria para la gestion de los detalles de agendas
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/DetalleAgd.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/GestionDetalleAgd.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/GestionDetallesAgd.js' );

        //  Adjunto libreria para la gestion de la estructura de agendas
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/EstructuraAgd.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/GestionEstructuraAgd.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/GestionEstructurasAgd.js' );
        
        //  Adjunto libreria para la gestion de la estructura de agendas
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/ItemAgd.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/GestionItemAgd.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/GestionItemsAgd.js' );
        
        //  Adjunto libreria para el lenguaje
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/ES_AGENDA.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/agenda/assets/Reglas.js' );
        
        //  Adjunto el script para la carga de lista de detalles de la agenda
        $document->addScriptDeclaration( $this->_getLstDetallesAgd(), $type = 'text/javascript' );

        //  Adjunto el script para la carga de estructura de la agenda
        $document->addScriptDeclaration( $this->_getEstructuraAgd(), $type = 'text/javascript' );
        
        //  Adjunto el script para la carga de los items de la agenda
        $document->addScriptDeclaration( $this->_getItemsAgd(), $type = 'text/javascript' );

        JText::script('COM_MANTENIMIENTO_AGENDA_ERROR_UNACCEPTABLE');
    }
    
    private function _getLstDetallesAgd()
    {
        $retval = '';
        
        //  Lista de funcionarios de una unidad de gestión
        $retval .= 'objLstDetallesAgd = new GestionDetallesAgd();';

        //  lista de detalles de la agenda
        if( $this->lstDetallesAgd ) {
            foreach( $this->lstDetallesAgd as $detalle ) {
                $retval .= 'var objDetalleAgd = new DetalleAgd();';
                $retval .= 'objDetalleAgd.setDtaDetalleAgd( ' . json_encode( $detalle ) . ' );';
                $retval .= 'objLstDetallesAgd.addDetalleAgd( objDetalleAgd );';
            }
        }

        return $retval;
    }
    
    private function _getEstructuraAgd()
    {
        $retval = '';
        
        //  Lista de funcionarios de una unidad de gestión
        $retval .= 'objLstEstructuraAgd = new GestionEstructurasAgd();';

        //  lista de detalles de la agenda
        if( $this->estructuraAgd ) {
            foreach( $this->estructuraAgd as $estructura ) {
                $retval .= 'var objEstructuraAgd = new EstructuraAgd();';
                $retval .= 'objEstructuraAgd.setDtaEstructuraAgd( ' . json_encode( $estructura ) . ' );';
                $retval .= 'objLstEstructuraAgd.addEstructuraAgd( objEstructuraAgd );';
            }
        }
        return $retval;
    }
    
    private function _getItemsAgd()
    {
        $retval = '';
        
        //  Lista de funcionarios de una unidad de gestión
        $retval .= 'objLstItemsAgd = new GestionItemsAgd();';

        //  lista de detalles de la agenda
        if( $this->lstItemsAgd ) {
            foreach( $this->lstItemsAgd as $item ) {
                $retval .= 'var objItemAgd = new ItemAgd();';
                $retval .= 'objItemAgd.setDtaItemAgd( ' . json_encode( $item ) . ' );';
                $retval .= 'objLstItemsAgd.addItemAgd( objItemAgd );';
            }
        }
        return $retval;
    }
    
}