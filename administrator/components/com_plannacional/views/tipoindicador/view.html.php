<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Vista estado PLANNACIONAL
 */
class PlanNacionalViewTipoIndicador extends JView
{
    /**
    * display method of Hello view
    * @return void
    */
    public function display($tpl = null) 
    {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');

        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }
 
    /**
    * Configuracion de la barra de herramientas
    */
    protected function addToolBar() 
    {
        JRequest::setVar( 'hidemainmenu', true );
        $isNew = ( $this->item->INTCODIGOTIPO_IND == 0 );
        
        JToolBarHelper::title( $isNew   ? JText::_('COM_PLANNACIONAL_ADD_TIPOINDICADOR_TITLE') 
                                        : JText::_('COM_PLANNACIONAL_EDIT_TIPOINDICADOR_TITLE'), 'tipoindicador' );
        
        JToolBarHelper::save( 'tipoindicador.save' );
        JToolBarHelper::cancel( 'tipoindicador.cancel', $isNew  ? 'JTOOLBAR_CANCEL' 
                                                                : 'JTOOLBAR_CLOSE' );
    }
    
    /**
    * Metodo para establecer las propiedades del documento
    *
    * @return void
    */
    protected function setDocument() 
    {
        $isNew = ($this->item->INTCODIGOTIPO_IND < 1);
        $document = JFactory::getDocument();
        
        $document->setTitle( $isNew ? JText::_( 'COM_PLANNACIONAL_TIPOINDICADOR_CREATING' ) 
                                    : JText::_( 'COM_PLANNACIONAL_TIPOINDICADOR_EDITING' ) );
        
        $document->addScript( JURI::root() . $this->script );
        $document->addScript( JURI::root() . "/administrator/components/com_plannacional/views/categoria/submitbutton.js" );
        
        JText::script('COM_PLANNACIONAL_CATEGORIA_ERROR_UNACCEPTABLE');
    }
}