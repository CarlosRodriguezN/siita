<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
/**
 * Vista PLANNACIONAL
 */
class plannacionalViewCategoria extends JView {

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
      $bar->appendButton('Standard', 'save', 'Guardar', 'categoria.save', false);
      $bar->appendButton('Separator');
      $bar->appendButton('Standard', 'cancel', 'Cancelar', 'categoria.cancel', false);

      //generate the html and return
      return $bar->render();
   }

   /**
    * Metodo para establecer las propiedades del documento
    *
    * @return void
    */
   protected function setDocument() {
      $isNew = ($this->item->intCodigoCategoria < 1);
      $document = JFactory::getDocument();
  
        //  Accdemos a la hoja de estilos del administrador
        $document->addStyleSheet( 'administrator/templates/system/css/system.css' );
        
        //  Agregamos hojas de estilo complementarias 
        $document->addCustomTag(
            '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
            '<!--[if IE 7]>'."\n".
            '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
            '<![endif]-->'."\n".
            '<!--[if gte IE 8]>'."\n\n".
            '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
            '<![endif]-->'."\n".
            '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
        );
        
      $document->setTitle($isNew ? JText::_('COM_PLANNACIONAL_CATEGORIA_CREATING') : JText::_('COM_PLANNACIONAL_CATEGORIA_EDITING') );

      $document->addScript(JURI::root() . $this->script);



      JText::script('COM_PLANNACIONAL_CATEGORIA_ERROR_UNACCEPTABLE');
   }

}