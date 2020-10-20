<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador proyecto
 * 
 */
class plannacionalControllerIndicadornacional extends JControllerForm {

   protected $view_list = 'Indicadoresnacionales';

   protected function allowAdd($data = array()) {
      return true;
   }
 
   protected function allowEdit($data = array(), $key = 'id') {
      return true;
   }

   function add() {
      parent::add();
   }

}